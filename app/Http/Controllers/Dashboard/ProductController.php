<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\ProductDataTable;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{

    public function index(ProductDataTable $datatable)
    {

        if (request()->ajax()) {
            return $datatable->ajax();
        }


        return $datatable->render('Dashboard.Admin.Product.index');
    }



    public function create()
    {

        $brands = Brand::with('productTypes')->get();
        $productTypes = ProductType::all();

        return view('Dashboard.Admin.Product.create', compact('brands', 'productTypes'));
    }



    public function store(request $request)
    {

        $validatedData = $request->validate([
            'product_name' => 'required|string|max:255',
            'type_id' => 'required|exists:product_types,id', // تأكد من أن type_id موجود في جدول product_types
            'detail_name' => 'required|string|max:255',
            'product_code' => 'required|string|max:255|unique:products,product_code', // كود المنتج يجب أن يكون فريدًا
            'vendor_code' => 'required|string|max:255',
        ], [

            'product_name.required' => 'اسم المنتج مطلوب.',
            'type_id.required' => 'نوع المنتج مطلوب.',
            'type_id.exists' => 'نوع المنتج المحدد غير موجود.',
            'detail_name.required' => 'تفاصيل المنتج مطلوبة.',
            'product_code.required' => 'كود المنتج مطلوب.',
            'vendor_code.required' => 'كود المورد مطلوب.',
            'product_code.unique' => 'كود المنتج مستخدم بالفعل.',
        ]);

        DB::beginTransaction();

        try {

            $products = new Product();
            $products->product_name = $validatedData['product_name'];
            $products->type_id = $validatedData['type_id'];
            $products->detail_name = $validatedData['detail_name'];
            $products->product_code = $validatedData['product_code'];
            $products->vendor_code = $validatedData['vendor_code'];
            $products->save();


            DB::commit();
            session()->flash('add', 'تم إضافة المنتج بنجاح.');
            return redirect()->route('admin.products.index');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }



    public function edit($id)
    {

        $brands = Brand::all();
        $productTypes = ProductType::all();
        $product = Product::with(['brand', 'productType'])->findOrFail($id);

        return view('Dashboard.Admin.Product.edit', compact('brands', 'productTypes', 'product'));
    }

    public function update(Request $request)
    {


        DB::beginTransaction();

        try {

            $product = Product::findOrFail($request->id);
            $product->product_name = $request->product_name;
            $product->detail_name = $request->detail_name;
            $product->product_code = $request->product_code;
            $product->vendor_code = $request->vendor_code;
            $product->save();



            DB::commit();
            session()->flash('edit');
            return redirect()->route('admin.products.index');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    public function destroy(request $request)
    {
        Product::destroy($request->id);

        session()->flash('delete');
        return redirect()->back();
    }
}
