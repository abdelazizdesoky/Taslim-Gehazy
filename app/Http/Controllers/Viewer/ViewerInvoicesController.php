<?php

namespace App\Http\Controllers\Viewer;



use App\Models\Invoice;
use App\Models\Product;
use App\Models\SerialNumber;
use Illuminate\Http\Request;
use App\Models\InvoiceProduct;
use App\DataTables\ProductDataTable;
use App\Http\Controllers\Controller;
use App\DataTables\ViewerInvoiceDataTable;


class ViewerInvoicesController extends Controller
{
        //-------------------------------------------------------------------------------------------------------
        public function index(ViewerInvoiceDataTable $datatable)
        {
            if (request()->ajax()) {
                return $datatable->ajax();
            }
            return $datatable->render('Dashboard.Viewer.Invoices.index');
        }
   

  
    //-------------------------------------------------------------------------------------------------------
    public function create()
    {

        return view('Dashboard.Viewer.Serial.index');
    }



   
   public function searchInvoices(Request $request)

   {

   

       $query = $request->input('query');
   
       // البحث عن السيريالات المطابقة
       $serials = SerialNumber::where('serial_number', $query)->get();
   
       if ($serials->count() > 0) {
           // جلب معرفات الفواتير المرتبطة بالسيريالات
           $invoiceIds = $serials->pluck('invoice_id')->unique();
   
           // التأكد من وجود معرفات فواتير
           if ($invoiceIds->isNotEmpty()) {
               // جلب الفواتير
               $invoices = Invoice::whereIn('id', $invoiceIds)->orderBy('invoice_date','asc')->get();
   
               // التأكد من وجود فواتير
               if ($invoices->count() > 0) {

                   return view('Dashboard.Viewer.Serial.show', compact('invoices', 'query'));

               } else {
                   return view('Dashboard.Viewer.Serial.show')->with('message', 'لا توجد فواتير مرتبطة بهذا السيريال.');
               }
           } else {
               return view('Dashboard.Viewer.Serial.show')->with('message', 'لا توجد فواتير مرتبطة بهذا السيريال.');
           }
       } else {
           return view('Dashboard.Viewer.Serial.show')->with('message', 'لم يتم العثور على السيريال.');
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

    return view('Dashboard.Viewer.Invoices.showinvoice', compact('invoice', 'serials', 'productsWithSerialCounts'));
}





 



//-------------------------------------------------------------------------------------------------------

public function viewProduct(ProductDataTable $datatable)
{

    if (request()->ajax()) {
        return $datatable->ajax();
    }


    return $datatable->render('Dashboard.Viewer.Product.index');
}


}
