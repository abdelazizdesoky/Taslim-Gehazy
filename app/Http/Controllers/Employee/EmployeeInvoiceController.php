<?php

namespace App\Http\Controllers\Employee;

use App\Models\Invoice;
use App\Models\Employee;
use App\Models\SerialNumber;
use Illuminate\Http\Request;
use App\Models\InvoiceProduct;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EmployeeInvoiceController extends Controller
{
    public function index() {}



    public function update($id)
    {

        if ($id == 1) {
            //اذن التسليم 

            $activeInvoicesCount = Invoice::where('employee_id', Auth::user()->id)
                ->whereIn('invoice_status', [1]) //,مرتجع  حالة الفاتورة تحت تسليم  
                ->whereIn('invoice_type', [$id, 3]) // إتسليم 
                ->count();

            $Invoices = Invoice::where('employee_id', Auth::user()->id)
                ->whereIn('invoice_status', [1])
                ->whereIn('invoice_type', [$id, 3]) // -مرتجعات | تسليم
                ->orderBy('invoice_date', 'desc')
                ->paginate(500);


            return view('Dashboard.Employees.Invoices.invoice2', compact('Invoices', 'activeInvoicesCount'));
        } elseif ($id == 2) {

            // الاذن الاستلام 

            $activeInvoicesCount = Invoice::where('employee_id', Auth::user()->id)
                ->whereIn('invoice_status', [1])  // حالة الفاتورة تحت استلام 
                ->whereIn('invoice_type', [2, 3]) // استلام
                ->count();

            $Invoices = Invoice::where('employee_id', Auth::user()->id)
                ->whereIn('invoice_status', [1])
                ->whereIn('invoice_type', [2, 3]) // "استلام"
                ->orderBy('invoice_date', 'desc')
                ->paginate(500);


            return view('Dashboard.Employees.Invoices.invoice', compact('Invoices', 'activeInvoicesCount'));
        } elseif ($id == 3) {

            // الاذن تسلسم الجوكر  

            $activeInvoicesCount = Invoice::whereIn('invoice_status', [1])  // حالة الفاتورة تحت استلام 
                ->whereIn('invoice_type', [2, 3]) // استلام
                ->count();

            $Invoices = Invoice::whereIn('invoice_status', [1])
                ->whereIn('invoice_type', [2, 3]) // "استلام"
                ->orderBy('invoice_date', 'desc')
                ->paginate(500);


            return view('Dashboard.Employees.Invoices.invoice2', compact('Invoices', 'activeInvoicesCount'));
        }
    }

    //----------------------------------------------------------------------------

    public function Compinvoice()
    {

        // //مكتمل 
        if (Auth::user()->permission == 3 || Auth::user()->permission == 4) {

            $activeInvoicesCount = Invoice::where('employee_id', Auth::user()->id)
            ->where('invoice_status', 3)
            ->count();


            $Invoices = Invoice::where('employee_id', Auth::user()->id)
            ->where('invoice_status', 3)
            ->orderBy('invoice_date', 'desc')
            ->paginate(100);


            return view('Dashboard.Employees.Invoices.compinvoice', compact('Invoices', 'activeInvoicesCount'));
        } elseif (Auth::user()->permission == 6) {

            $activeInvoicesCount = Invoice::where('invoice_status', 3)->count();

            $Invoices = Invoice::where('invoice_status', 3)
                ->orderBy('invoice_date', 'desc')
                ->paginate(100);


            return view('Dashboard.Employees.Invoices.compinvoice', compact('Invoices', 'activeInvoicesCount'));
        } else {
            return redirect()->back()->with('error', 'ليس لديك صلاحية الوصول إلى هذه الصفحة.');
        }
    }


    //----------------------------------------------------------------------------

    public function show($id)
    {

        //اذن  

        $invoice = Invoice::with(['products'])->where('id', $id)->firstOrFail();


        return view('Dashboard.Employees.Invoices.invoicedateils', compact('invoice'));
    }

    //---------------------------------------------------
    public function edit($id)
    {
        // جلب الفاتورة مع المنتجات المرتبطة والكمية من جدول invoice_products
        $Invoices = Invoice::with(['products' => function ($query) use ($id) {
            $query->select(
                'products.id',
                'products.product_name',
                'products.product_code',
                'products.vendor_code',
                'ip.quantity' 
            )
                ->join('invoice_products as ip', 'products.id', '=', 'ip.product_id')
                ->where('ip.invoice_id', $id); 
        }])->findOrFail($id);

        // حساب الكمية الإجمالية من جدول invoice_products
        $totalQuantity = DB::table('invoice_products')
            ->where('invoice_id', $id)
            ->sum('quantity');

        // ترتيب البيانات المرتبطة بالفاتورة والمنتجات
        $invoiceProducts = $Invoices->products->map(function ($product) {
            return [
                'id' => $product->id,
                'product_code' => $product->product_code ?? '', // استخدم `product_code` بدلاً من `serial_prefix`
                'product_name' => $product->product_name ?? '',
                'vendor_code' => $product->vendor_code ?? '', // إضافة vendor_code
                'quantity' => $product->quantity ?? 0, // الكمية من جدول invoice_products
            ];
        });


        // إرسال البيانات إلى الـ View
        return view('Dashboard.Employees.Invoices.addserial', compact('Invoices', 'totalQuantity', 'invoiceProducts'));
    }





    ///-----------------------------------------------
    public function store(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|string|max:255',
                'serials' => 'required|string',
            ]);

            $invoiceId = $request->input('id');
            $serials = $request->input('serials');

           

           
           $serialsArray = array_unique(array_filter(array_map('trim', explode("\n", $serials))));

            DB::beginTransaction();

            $existingSerialsInInvoice = SerialNumber::where('invoice_id', $invoiceId)
                ->pluck('serial_number')
                ->toArray();

            $successfulSerials = [];
            $failedSerials = [];

            foreach ($serialsArray as $serial) {
                if (empty($serial)) {
                    $failedSerials[] = $serial . ' (سيريال فارغ)';
                    continue;
                }

                if (in_array($serial, $existingSerialsInInvoice)) {
                    $failedSerials[] = $serial . ' (مكرر في الفاتورة)';
                    continue;
                }

               
                try {
                    SerialNumber::create([
                        'invoice_id' => $invoiceId,
                        'serial_number' => $serial,
                    ]);

                    $successfulSerials[] = $serial;
                    $existingSerialsInInvoice[] = $serial; 
                } catch (\Exception $e) {
                    $failedSerials[] = $serial . ' (خطأ غير معروف)';
                }
           }

            // تحديث حالة الفاتورة إذا كان هناك سيريالات ناجحة
            if (!empty($successfulSerials)) {
                $invoice = Invoice::findOrFail($invoiceId);
                $invoice->invoice_status = 3;
                $invoice->save();
            }

            DB::commit();

            if (!empty($successfulSerials)) {
                session()->flash('add', 'تم إضافة السيريالات بنجاح: ' . implode(', ', $successfulSerials));
            }

            if (!empty($failedSerials)) {
                session()->flash('warning', 'السيريالات التي فشلت: ' . implode(', ', $failedSerials));
            }

            return redirect()->route('Dashboard.employee');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function checkSerialExists($serial)
{
    $exists = SerialNumber::where('serial_number', $serial)->exists();
    return response()->json(['exists' => $exists]);
}
   

}
