<?php

namespace App\Http\Controllers\User;



use App\Models\Admin;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Location;
use App\Models\Supplier;
use App\Models\Customers;
use App\Models\SerialNumber;
use Illuminate\Http\Request;
use App\Models\InvoiceProduct;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\DataTables\UserInvoiceDataTable;

class UserInvoicesController extends Controller
{
    //-------------------------------------------------------------------------------------------------------

    public function index(UserInvoiceDataTable $datatable)
    {
        if (request()->ajax()) {
            return $datatable->ajax();
        }
        return $datatable->render('Dashboard.User.Invoices.index');
    }


    ///-------------------------------------------------------------------------------------------------------

    public function create()
    {
        $customers = Customers::select('id', 'name', 'code')->where('status', 1)->get();
        $suppliers = Supplier::select('id', 'name', 'code')->where('status', 1)->get();
        $products = Product::all(); // جلب المنتجات المتاحة
        // دمج الاثنين في قائمة واحدة
        $contacts = $customers->map(function ($customer) {
            return [
                'id' => $customer->id,
                'name' => $customer->name,
                'code' => $customer->code,
                'type' => 'customer',
            ];
        })->merge(
            $suppliers->map(function ($supplier) {
                return [
                    'id' => $supplier->id,
                    'name' => $supplier->name,
                    'code' => $supplier->code,
                    'type' => 'supplier',
                ];
            })
        );

        $admins = Admin::whereIn('permission', [3, 4])->get();
        $locations = Location::all();

        return view('Dashboard.User.Invoices.create', compact('contacts', 'admins', 'locations', 'customers', 'suppliers', 'products'));
    }






    //-----------------------------------
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:invoices,code',
            'invoice_date' => 'required',
            'invoice_type' => 'required',
            'employee_id' => 'required|exists:admins,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // إنشاء الفاتورة
            $invoice = new Invoice();
            $invoice->code = $request->code;
            $invoice->invoice_date = Carbon::createFromFormat('m/d/Y', $request->invoice_date)->format('Y-m-d');
            $invoice->invoice_type = $request->invoice_type;
            $invoice->employee_id = $request->employee_id;
            $invoice->created_by = Auth::user()->id;
            $invoice->location_id = $request->location_id;
            $invoice->notes = $request->notes;

            // تعيين المورد أو العميل حسب نوع الفاتورة
            if ($request->invoice_type == 1) {
                $request->validate(['supplier_id' => 'required|exists:suppliers,id']);
                $invoice->supplier_id = $request->supplier_id;
            } elseif ($request->invoice_type == 2) {
                $request->validate(['customer_id' => 'required|exists:customers,id']);
                $invoice->customer_id = $request->customer_id;
            } elseif ($request->invoice_type == 3) {
                $request->validate([
                    'contact_id' => 'required',
                    'contact_type' => 'required|in:customer,supplier'
                ]);

                if ($request->contact_type == 'customer') {
                    $request->validate(['contact_id' => 'exists:customers,id']);
                    $invoice['customer_id'] = $request->contact_id;
                } else {
                    $request->validate(['contact_id' => 'exists:suppliers,id']);
                    $invoice['supplier_id'] = $request->contact_id;
                }
            }

            $invoice->save();

            // حفظ المنتجات
            $productsData = json_decode($request->products_data, true);

            if (!empty($productsData)) {
                foreach ($productsData as &$productData) {
                    $productData['invoice_id'] = $invoice->id;
                }
                InvoiceProduct::insert($productsData);
            }

            DB::commit();

            session()->flash('add', 'تم حفظ الفاتورة بنجاح');
            return redirect()->route('user.invoices.index');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    //-------------------------------------------------------------------------------------------------------
    public function edit($id)
    {
        $Invoices = Invoice::findorfail($id);
        $suppliers = Supplier::where('status', 1)->get();
        $customers = Customers::where('status', 1)->get();
        $admins = Admin::whereIn('permission', [3, 4])->get();
        $creators = Admin::whereIn('permission', [1, 2])->get();
        $locations = Location::all();
        $invoiceProducts = InvoiceProduct::where('invoice_id', $id)->get();
        $allProducts = Product::all(); // جلب المنتجات المتاحة


        // دمج العملاء والموردين في قائمة واحدة
        $contacts = $customers->map(function ($customer) {
            return [
                'id' => $customer->id,
                'name' => $customer->name,
                'code' => $customer->code,
                'type' => 'customer',
            ];
        })->merge(
            $suppliers->map(function ($supplier) {
                return [
                    'id' => $supplier->id,
                    'name' => $supplier->name,
                    'code' => $supplier->code,
                    'type' => 'supplier',
                ];
            })
        );

        return view('Dashboard.User.Invoices.edit', compact('Invoices', 'suppliers', 'customers', 'admins', 'locations', 'contacts', 'creators', 'invoiceProducts', 'allProducts'));
    }


    //--------------------------------------------------


    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        // Validate input data
        $validatedData = $request->validate([
            'invoice_date' => 'required',
            'invoice_type' => 'required|in:1,2,3',
            'employee_id' => 'required|exists:admins,id',
            'invoice_status' => 'required',
            'location_id' => 'required',
            'notes' => 'nullable',
            'products' => 'required|array|min:1',
            'quantities' => 'required|array|min:1',
            'products.*' => 'required|exists:products,id',
            'quantities.*' => 'required|numeric|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Format invoice date
            $carbonDate = Carbon::parse($validatedData['invoice_date']);
            $newInvoiceDate = $carbonDate->format('Y-m-d');

            $updateData = [
                'invoice_type' => $validatedData['invoice_type'],
                'employee_id' => $validatedData['employee_id'],
                'created_by' =>   Auth::user()->id,
                'invoice_status' => $validatedData['invoice_status'],
                'location_id' => $validatedData['location_id'],
                'notes' => $validatedData['notes'],
            ];

            // Handle invoice type specific validation and updates
            if ($request->invoice_type == 1) {
                $request->validate(['supplier_id' => 'required|exists:suppliers,id']);
                $updateData['supplier_id'] = $request->supplier_id;
                $updateData['customer_id'] = null;
            } elseif ($request->invoice_type == 2) {
                $request->validate(['customer_id' => 'required|exists:customers,id']);
                $updateData['customer_id'] = $request->customer_id;
                $updateData['supplier_id'] = null;
            } elseif ($request->invoice_type == 3) {
                $request->validate([
                    'contact_id' => 'required',
                    'contact_type' => 'required|in:customer,supplier',
                ]);

                if ($request->contact_type == 'customer') {
                    $request->validate(['contact_id' => 'exists:customers,id']);
                    $updateData['customer_id'] = $request->contact_id;
                    $updateData['supplier_id'] = null;
                } else {
                    $request->validate(['contact_id' => 'exists:suppliers,id']);
                    $updateData['supplier_id'] = $request->contact_id;
                    $updateData['customer_id'] = null;
                }
            }

            // Update invoice date if changed
            if ($newInvoiceDate !== $invoice->invoice_date) {
                $updateData['invoice_date'] = $newInvoiceDate;
            }

            // Update invoice
            $invoice->update($updateData);

            // Get existing products
            $existingProducts = InvoiceProduct::where('invoice_id', $invoice->id)
                ->pluck('quantity', 'product_id')
                ->toArray();


            // Prepare new products data
            $newProducts = [];
            foreach ($request->products as $index => $productId) {
                $newProducts[$productId] = [
                    'quantity' => $request->quantities[$index]
                ];
            }



            // Process products
            foreach ($newProducts as $productId => $data) {
                try {
                    if (isset($existingProducts[$productId])) {
                        // Update existing product if quantity changed
                        if ($existingProducts[$productId] != $data['quantity']) {
                            InvoiceProduct::where('invoice_id', $invoice->id)
                                ->where('product_id', $productId)
                                ->update(['quantity' => $data['quantity']]);
                          
                        }
                        unset($existingProducts[$productId]);
                    } else {
                        // Add new product
                        InvoiceProduct::create([
                            'invoice_id' => $invoice->id,
                            'product_id' => $productId,
                            'quantity' => $data['quantity']
                        ]);
                    
                    }
                } catch (\Exception $e) {
                  
                    throw $e;
                }
            }

            // Remove products that are no longer in the invoice
            if (!empty($existingProducts)) {
                InvoiceProduct::where('invoice_id', $invoice->id)
                    ->whereIn('product_id', array_keys($existingProducts))
                    ->delete();
                
            }



            // Remove products that are no longer in the invoice
            if (!empty($existingProducts)) {
                InvoiceProduct::where('invoice_id', $invoice->id)
                    ->whereIn('product_id', array_keys($existingProducts))
                    ->delete();
            }
            DB::commit();
            session()->flash('edit');
            return redirect()->route('user.invoices.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    //-------------------------------------------------------------------------------------------------------
    public function cancel(Request $request)
    {
        try {

            $invoice = Invoice::findOrFail($request->id);

            $invoice->invoice_status = 5;


            $invoice->save();

            session()->flash('edit');
            return redirect()->route('user.invoices.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }



    //-------------------------------------------------------------------------------------------------------
    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);

        // المنتجات المرتبطة بالفاتورة
        $invoiceProducts = InvoiceProduct::where('invoice_id', $id)->with('product')->get();

        // استرجاع السيريالات المرتبطة بالفاتورة
        $serials = SerialNumber::where('invoice_id', $id)->get();

        // تجميع السيريالات لكل منتج
        $serialsGroupedByProduct = $serials->groupBy(function ($serial) {
            $serialNumber = ltrim($serial->serial_number, '0');
            $serialPrefix = substr($serialNumber, 0, 7); // استخراج أول 7 أرقام
            $product = Product::where('vendor_code', $serialPrefix)->first();
            return $product ? $product->id : null; // إرجاع معرف المنتج
        });

        // تجهيز بيانات المنتجات وكميات السيريالات
        $productsWithSerialCounts = [];
        foreach ($invoiceProducts as $invoiceProduct) {
            $product = $invoiceProduct->product;

            // التحقق من وجود المنتج
            if ($product) {
                // التحقق من وجود productType و brand
                $productName = optional($product->productType)->type_name . ' ' .
                    optional($product->productType->brand)->brand_name . ' ' .
                    $product->product_name;
            } else {
                $productName = 'اسم المنتج غير متاح'; // نص افتراضي في حال عدم وجود المنتج
            }

            $productId = $invoiceProduct->product_id;

            // حساب عدد السيريالات المرتبطة بكل منتج
            $serialCount = isset($serialsGroupedByProduct[$productId]) ? $serialsGroupedByProduct[$productId]->count() : 0;

            $productsWithSerialCounts[] = [
                'product_name' => $productName,
                'quantity_required' => $invoiceProduct->quantity, // الكمية المطلوبة
                'serial_count' => $serialCount, // عدد السيريالات المسحوبة
            ];
        }

        return view('Dashboard.User.Invoices.showinvoice', compact('invoice', 'serials', 'productsWithSerialCounts'));
    }






    //-------------------------------------------------------------------------------------------------------
}
