@extends('Dashboard.layouts.master')
@section('css')
    <link href="{{URL::asset('dashboard/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">الاذن </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ عرض الاذن </span>
						</div>
					</div>
				
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')
				<!-- row -->
				<div class="row row-sm">
					<div class="col-md-12 col-xl-12">
						<div class=" main-content-body-invoice">
							<div class="card card-invoice">
								<div class="card-body">
									<div class="invoice-header">
										<h1 class="invoice-title">اذن 
											@switch($invoice->invoice_type)
											@case(1)
											استلام
											@break

											@case(2)
											تسليم
											@break

											@default
											مرتجعات
											@endswitch

										</h1>
										<div class="billed-from">
											<h6>العربية جروب    </h6>
											        16399
											<h6>  ناقل للتوزيع  </h6>
											<p>مخازن رمسيس </p>
											
										</div><!-- billed-from -->
									</div><!-- invoice-header -->
									<div class="row mg-t-20">
										<div class="col-md">
											<label class="tx-gray-600"> </label>
											<div class="billed-to">
												<h6></h6>
												<p><br>
												<br>
												
												</p>
											</div>
										</div>
										<div class="col-md">
											<label class="tx-gray-600"> تفاصيل الاذن </label>
											<p class="invoice-info-row"><span>الاذن  </span> <span>{{ $invoice->code }}</span></p>
											<p class="invoice-info-row"><span>تاريخ  </span> <span>{{ $invoice->invoice_date }}</span></p>

											@switch($invoice->invoice_type)
											@case(1)
											<p class="invoice-info-row"><span>المورد  :</span> <span>{{ $invoice->supplier->name ??'-' }}-{{ $invoice->supplier->code ??'-' }}</span></p>
											@break

											@case(2)
											<p class="invoice-info-row"><span>العميل  :</span> <span>{{ $invoice->customer->name ??'-' }}-{{ $invoice->customer->code ??'-' }}</span></p>
											@break

											@default
											<p class="invoice-info-row"><span>مورد/العميل   :</span> <span>{{ $invoice->customer->name ??$invoice->supplier->name }}-{{ $invoice->customer->code ??$invoice->supplier->code }}</span></p>
											@endswitch
										
											
											<p class="invoice-info-row"><span>المنسق :</span> <span>	{{$invoice->creator->name??'-'}}</span></p>
											<p class="invoice-info-row"><span>المندوب :</span> <span>{{ $invoice->admin->name ??'-' }}</span></p>
											<p class="invoice-info-row"><span>مجموع سيريالات المسحوبة  :</span> <span>{{$serials->count()}}</span></p>
										</div>
									</div>
									<div class="table-responsive mg-t-20">
										<table class="table table-striped mg-b-0 text-md-nowrap">
											<thead>	
												<tr class="bg-secondary-gradient">
													<th class="wd-10p">#</th>
													<th class="wd-20p">المنتج  </th>
													<th class="wd-20p">الكمية المطلوبة   </th>
													<th class="wd-20p">عدد السيريال المسحوب  </th>
													
												</tr>
											</thead>
											<tbody>
												@foreach($productsWithSerialCounts as $index => $productData)
												<tr>
													<td>{{ $index + 1 }}</td>
													<td>{{ $productData['product_name'] }}</td>
													<td>{{ $productData['quantity_required'] }}</td>
													<td>{{ $productData['serial_count'] }}</td>
												</tr>
											@endforeach
										
											</tbody>

											<thead>
											
												<tr class="bg-secondary-gradient">												
													<th class="wd-10p">#</th>
													<th class="wd-20p">سيريال </th>
													<th class="wd-20p">المنتج </th>
													<th class="wd-20p">تاريخ سحب </th>
												
												</tr>
											</tbody>

											</thead>
											<tbody>


												@foreach($serials as $serial)
												<tr>
													<td>{{ $loop->iteration }}</td>
													<td>{{ $serial->serial_number }}</td>
													<td>
														@php
                                                            $patterns = ['/^09\/I-/']; 
															$serialNumber = preg_replace($patterns, '', $serial->serial_number); 
															$serialNumber = ltrim($serialNumber, '0'); 
															$serialPrefix = substr($serialNumber, 0, 7); 
															$product = \App\Models\Product::where('vendor_code', $serialPrefix)->first(); 

														
														@endphp
											
														@if ($product)
															{{-- عرض تفاصيل المنتج إذا كان المنتج موجوداً --}}
															{{ $product->productType->type_name ?? 'نوع غير موجود' }}
															{{ $product->productType->brand->brand_name ?? 'ماركة غير موجودة' }}
															{{ $product->product_name ?? 'اسم المنتج غير موجود' }}
															{{ $product->detail_name ?? 'تفاصيل غير موجودة' }}
														@else
															{{-- عرض رسالة في حال عدم وجود المنتج --}}
															{{ 'غير موجود بالمنتجات' }}
														@endif
													</td>    
													<td>{{ $serial->created_at }}

													

													</td>

												
												</tr>
											@endforeach

												
											
											
											</tbody>
									
										</table>
									</div>
									<hr class="mg-b-40">
									
									<a href="#" class="btn btn-danger float-left mt-3 mr-2">
										<i class="mdi mdi-printer ml-1"></i>Print
									</a>
							
									<a href="#" class="btn btn-success float-left mt-3" id="save-excel">
										<i class="mdi mdi-telegram ml-1"></i>Save as Excel
									</a>
								</div>
							</div>
						</div>
					</div><!-- COL-END -->
				</div>
				<!-- row closed -->
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection
@section('js')

	<!-- Add SheetJS library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>


    <!-- Print functionality -->
    <script>
	// Print functionality
document.querySelector('.btn-danger').addEventListener('click', function () {
    window.print();
});
document.querySelector('.btn-success').addEventListener('click', function () {
    // إعداد اسم الملف بناءً على كود الفاتورة
    var invoiceCode = "{{ $invoice->code }}";
    var fileName = invoiceCode + '-invoice.xlsx';

    // --- 1. إنشاء ملف Excel وورقة عمل ---
    var wb = XLSX.utils.book_new();
    var wsData = [];

    // --- 2. إضافة تفاصيل الفاتورة ---
    wsData.push(["تفاصيل الفاتورة"]); // العنوان
    wsData.push(["كود الفاتورة", "{{ $invoice->code }}"]);
    wsData.push(["تاريخ الفاتورة", "{{ $invoice->invoice_date }}"]);
    wsData.push(["المورد / العميل", "{{ $invoice->supplier->name ?? $invoice->customer->name ?? '-' }}"]);
    wsData.push(["رقم الهاتف", "{{ $invoice->supplier->phone ?? $invoice->customer->phone ?? '-' }}"]);
    wsData.push(["المنسق", "{{ $invoice->creator->name ?? '-' }}"]);
    wsData.push(["المندوب", "{{ $invoice->admin->name ?? '-' }}"]);
    wsData.push(["إجمالي السيريالات", "{{ $serials->count() }}"]);

    // إضافة صف فارغ للفصل
    wsData.push([]);

    // --- 3. إضافة جدول المنتجات ---
    wsData.push(["جدول المنتجات"]); // عنوان الجدول
    wsData.push(["#", "المنتج", "الكمية المطلوبة", "عدد السيريال المسحوب"]); // رؤوس الجدول

    document.querySelectorAll('.table-striped tbody')[0].querySelectorAll('tr').forEach((row, index) => {
        var cells = row.querySelectorAll('td');
        wsData.push([
            index + 1,
            cells[1].innerText, // المنتج
            cells[2].innerText, // الكمية المطلوبة
            cells[3].innerText  // عدد السيريال المسحوب
        ]);
    });

    // إضافة صف فارغ للفصل
    wsData.push([]);

    // --- 4. إضافة جدول السيريالات ---
    wsData.push(["جدول السيريالات"]); // عنوان الجدول
    wsData.push(["#", "السيريال", "المنتج", "تاريخ السحب"]); // رؤوس الجدول

    document.querySelectorAll('.table-striped tbody')[1].querySelectorAll('tr').forEach((row, index) => {
        var cells = row.querySelectorAll('td');
        wsData.push([
            index + 1,
            cells[1].innerText, // السيريال
            cells[2].innerText, // المنتج
            cells[3].innerText  // تاريخ السحب
        ]);
    });

    // --- 5. تحويل البيانات إلى ورقة عمل واحدة ---
    var ws = XLSX.utils.aoa_to_sheet(wsData);
    XLSX.utils.book_append_sheet(wb, ws, "Invoice");

    // --- 6. حفظ ملف Excel ---
    XLSX.writeFile(wb, fileName);
});


    </script>

<!--Internal  Chart.bundle js -->
<script src="{{URL::asset('assets/plugins/chart.js/Chart.bundle.min.js')}}"></script>
@endsection