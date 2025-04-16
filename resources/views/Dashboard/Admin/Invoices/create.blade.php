
@extends('Dashboard.layouts.master')
@section('css')
    <!--Internal   Notify -->
    <link href="{{URL::asset('dashboard/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
        <!-- Internal Select2 css -->
        <link href="{{URL::asset('dashboard/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
        <link href="{{URL::asset('dashboard/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    
      
@endsection
@section('title')
  اضافة الاذن  جديد
@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">الاذن </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ اضافة الاذن  </span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection
@section('content')
@include('Dashboard.messages_alert')
@include('Dashboard.Admin.Invoices.location.add')
<!-- row -->
<div class="row">
					<!-- Col -->
					<div class="col-lg-6">
						<div class="card">
							<div class="card-body">
								<div class="mb-4 main-content-label"> الاذن</div>

                                    <form class="form-horizontal" action="{{route('admin.invoices.store')}}" method="post" autocomplete="off">
                                        @csrf
                                    <div class="form-group ">
										<div class="row">
											<div class="col-md-3">
												<label class="form-label">رقم الاذن</label>
											</div>
											<div class="col-md-9">
												<input type="text" name="code"  value="{{old('code')}}" class="form-control @error('code') is-invalid @enderror" required>
											</div>
										</div>
									</div>

                                    <div class="form-group ">
										<div class="row">
											<div class="col-md-3">
												<label class="form-label">تاريخ الاذن</label>
											</div>
											<div class="col-md-9">
                                                <div class="row row-sm mg-b-20">
                                                    <div class="input-group col-md-6">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                            </div>
                                                        </div><input class="form-control fc-datepicker"  placeholder="MM/DD/YYYY" type="text" name="invoice_date" >
                                                    </div>
                                                </div>
											</div>
										</div>
									</div>
									<!------------------------------------------------------------>

                                                                    <div class="form-group">
                                    <label class="form-label">نوع الاذن</label>
                                    <select class="form-control" name="invoice_type" id="status" required>
                                        <option value="1">استلام</option>
                                        <option value="2">تسليم</option>
                                        <option value="3">مرتجعات عام </option> <!-- الخيار الثالث -->
                                    </select>
                                </div>

                                <!-- قسم المورد -->
                                <div class="form-group d-none" id="supplier-section">
                                    <label class="form-label">المورد</label>
                                    <select class="form-control" name="supplier_id">
                                        <option value="" disabled>--اختر المورد</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->code}}-{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- قسم العميل -->
                                <div class="form-group d-none" id="client-section">
                                    <label class="form-label">العميل</label>
                                    <select class="form-control select2" name="customer_id" style="width: 100%;">
                                        <option value="" disabled>--اختر العميل</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->code}}-{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- قسم العميل/المورد (للمرتجع) -->
                                <div class="form-group d-none" id="contact-section">
                                    <label class="form-label">العميل/المورد</label>
                                    <select class="form-control select2" name="contact_id" id="contact-id" style="width: 100%;">
                                        <option value="" disabled>--اختر العميل أو المورد</option>
                                        @foreach($contacts as $contact)
                                            <option value="{{ $contact['id'] }}" data-type="{{ $contact['type'] }}">
                                                {{ $contact['code'] }} - {{ $contact['name'] }} ({{ $contact['type'] == 'customer' ? 'عميل' : 'مورد' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="contact_type" id="contact-type">
                                </div>
                                        
                                    <!------------------------------------------------------------>


                                    
                                    <div class="form-group ">
										<div class="row">
											<div class="col-md-3">
												<label class="form-label">  امين مخزن/المندوب</label>
											</div>
											<div class="col-md-9">
												<select class="form-control select2" name="employee_id" style="width: 100%;" >
                                                    @foreach($admins as $admin)
                                                   <option value="{{$admin->id}}">{{$admin->id}} - {{$admin->name}} -{{$admin->permission == 3? 'مندوب تسليم ':'امين مخزن ' }}</option>
                                                    @endforeach
												</select>
											</div>
										</div>	
                                    </div>

                                    <div class="form-group ">
										<div class="row">
											<div class="col-md-3">
												<label class="form-label"> موقع -  <a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add_location"><i class="fas fa-edit"></i></a></label>
											</div>
											<div class="col-md-9">
												<select class="form-control select2" name="location_id" >
                                                    @foreach($locations as $location)
                                                   <option value="{{$location->id}}">{{$location->location_name}}</option>
                                                    @endforeach
												</select>
											</div>
										</div>	
                                    </div>
                     
                                    <div class="form-group ">
										<div class="row">
											<div class="col-md-3">
												<label class="form-label"> ملاحظات  </label>
											</div>
											<div class="col-md-9">
                                                <textarea class="form-control" name="notes" rows="3"></textarea>
											</div>
										</div>	
                                    </div>

							</div>
						</div>
					</div>
					<!-- /Col -->


                           <!-- Col -->
					<div class="col-lg-6">
						<div class="card">
							<div class="card-body">
                      <!-- مكون Livewire لإضافة المنتجات -->
                      <div class="form-group">
                        <label for="products">المنتجات</label>
                        <div id="products-container">
                            <!-- صف المنتج الأول -->
                            <div class="product-row row mb-3">
                                <div class="col-md-5">
                                    <select class="form-control select2 product-select" name="items[0][product_id]" required>
                                        <option value="">-- اختر المنتج --</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->product_code }}-{{ $product->product_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <input type="number" class="form-control quantity" name="items[0][quantity]" placeholder="الكمية" required min="1">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger remove-product">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    
                        <!-- زر إضافة منتج -->
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary" id="add-product">
                                    <i class="fas fa-plus"></i> إضافة منتج
                                </button>
                            </div>
                        </div>
                    </div>
                    
</div>

                 </div>
             </div>

             <div class="card-footer text-left">
                <button type="submit" class="btn btn-success waves-effect waves-light">حفظ الاذن</button>
            </div>
        </form>

          </div>
	<!-- /Col -->

	</div>

   
</div>
</div>
<!-- row closed -->
@endsection
@section('js')

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status');
    const supplierSection = document.getElementById('supplier-section');
    const clientSection = document.getElementById('client-section');
    const contactSection = document.getElementById('contact-section');
    const contactSelect = document.getElementById('contact-id');
    const contactTypeInput = document.getElementById('contact-type');

    function updateSections() {
        const selectedValue = statusSelect.value;

        supplierSection.classList.add('d-none');
        clientSection.classList.add('d-none');
        contactSection.classList.add('d-none');

        if (selectedValue == '1') {
            supplierSection.classList.remove('d-none');
        } else if (selectedValue == '2') {
            clientSection.classList.remove('d-none');
        } else if (selectedValue == '3') {
            contactSection.classList.remove('d-none');
        }
    }

    function updateContactType() {
        const selectedOption = contactSelect.options[contactSelect.selectedIndex];
        if (selectedOption) {
            contactTypeInput.value = selectedOption.getAttribute('data-type');
        }
    }

    // Update sections based on the initial selection
    updateSections();
    updateContactType();

    statusSelect.addEventListener('change', updateSections);
    contactSelect.addEventListener('change', updateContactType);
});

//---------------------------------
$(document).ready(function() {
    let productCounter = $('.product-row').length;  // تهيئة العداد بعدد الصفوف الحالية

    // دالة إضافة صف منتج جديد
    function addProductRow() {
        const container = $('#products-container');
        const newRow = $(`
            <div class="product-row row mb-3">
                <div class="col-md-5">
                    <select class="form-control select2 product-select" name="items[${productCounter}][product_id]" required>
                        <option value="">-- اختر المنتج --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->product_code }}-{{ $product->product_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <input type="number" class="form-control quantity" name="items[${productCounter}][quantity]" placeholder="الكمية" required min="1">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-product">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `);
        
        container.append(newRow);
        newRow.find('.select2').select2();  // تهيئة Select2 للمنتجات الجديدة
        productCounter++;  // زيادة العداد
        updateRemoveButtons();  // تحديث أزرار الحذف
    }

    // تحديث أزرار الحذف
    function updateRemoveButtons() {
        const rows = $('.product-row');
        if (rows.length === 1) {
            rows.find('.remove-product').hide();  // إخفاء زر الحذف إذا كان هناك صف واحد فقط
        } else {
            rows.find('.remove-product').show();  // إظهار زر الحذف إذا كان هناك أكثر من صف
        }
    }

    // إضافة حدث لزر إضافة منتج
    $('#add-product').on('click', function() {
        addProductRow();  // إضافة منتج جديد
    });

    // إضافة حدث حذف للصفوف
    $(document).on('click', '.remove-product', function() {
        if ($('.product-row').length > 1) {
            $(this).closest('.product-row').remove();  // إزالة الصف
            updateRemoveButtons();  // تحديث أزرار الحذف
        }
    });



function collectProductData() {
    let products = [];

    // جمع بيانات المنتجات والكميات
    $('.product-row').each(function() {
        const productId = $(this).find('.product-select').val();
        const quantity = $(this).find('.quantity').val();

        if (productId && quantity && quantity > 0) {
            products.push({
                product_id: productId,
                quantity: quantity
            });
        }
    });

    return products;
}

$('form').on('submit', function(e) {
    e.preventDefault();

    let isValid = true;
    let hasProducts = false;

    // التحقق من صحة المدخلات
    $('.product-row').each(function() {
        const productId = $(this).find('.product-select').val();
        const quantity = $(this).find('.quantity').val();

        if (productId && quantity && quantity > 0) {
            hasProducts = true;
        } else {
            isValid = false;
            return false;
        }
    });

    if (!isValid) {
        alert('الرجاء ملء جميع بيانات المنتجات والكميات بشكل صحيح');
        return false;
    }

    if (!hasProducts) {
        alert('يجب إضافة منتج واحد على الأقل');
        return false;
    }

    // جمع بيانات المنتجات
    const products = collectProductData();
    if (products.length === 0) {
        alert('يجب إضافة منتج واحد على الأقل');
        return false;
    }

    // إضافة بيانات المنتجات إلى حقل مخفي
    $('<input>').attr({
        type: 'hidden',
        name: 'products_data',
        value: JSON.stringify(products)
    }).appendTo('form');

    this.submit();  // إرسال النموذج
});


    // التحقق من النموذج قبل الإرسال
    $('form').on('submit', function(e) {
        e.preventDefault();

        let isValid = true;
        let hasProducts = false;

        $('.product-row').each(function() {
            const productId = $(this).find('.product-select').val();
            const quantity = $(this).find('.quantity').val();

            if (productId && quantity && quantity > 0) {
                hasProducts = true;
            } else {
                isValid = false;
                return false;
            }
        });

        if (!isValid) {
            alert('الرجاء ملء جميع بيانات المنتجات والكميات بشكل صحيح');
            return false;
        }

        if (!hasProducts) {
            alert('يجب إضافة منتج واحد على الأقل');
            return false;
        }

        // طباعة البيانات للتحقق (يمكن إزالتها لاحقاً)
        console.log('Form data:', $(this).serialize());

        this.submit();  // إرسال النموذج
    });

    // تهيئة أزرار الحذف عند تحميل الصفحة
    updateRemoveButtons();
});


    </script>
    



       <!--Internal  Notify js -->
       <script src="{{URL::asset('dashboard/plugins/notify/js/notifIt.js')}}"></script>
       <script src="{{URL::asset('/plugins/notify/js/notifit-custom.js')}}"></script>
   
       <!--Internal  Datepicker js -->
       <script src="{{URL::asset('dashboard/plugins/jquery-ui/ui/widgets/datepicker.js')}}"></script>
       <!--Internal  jquery.maskedinput js -->
       <script src="{{URL::asset('dashboard/plugins/jquery.maskedinput/jquery.maskedinput.js')}}"></script>
       <!--Internal  spectrum-colorpicker js -->
       <script src="{{URL::asset('dashboard/plugins/spectrum-colorpicker/spectrum.js')}}"></script>
       <!-- Internal Select2.min js -->
       <script src="{{URL::asset('dashboard/plugins/select2/js/select2.min.js')}}"></script>
       <!--Internal Ion.rangeSlider.min js -->
       <script src="{{URL::asset('dashboard/plugins/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
       <!--Internal  jquery-simple-datetimepicker js -->
       <script src="{{URL::asset('dashboard/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js')}}"></script>
       <!-- Ionicons js -->
       <script src="{{URL::asset('dashboard/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js')}}"></script>
       <!-- Internal form-elements js -->
       <script src="{{URL::asset('dashboard/js/form-elements.js')}}"></script>
@endsection
