@extends('Dashboard.layouts.master')
@section('css')
    <!--Internal   Notify -->
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <!-- Internal Select2 css -->
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('dashboard/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection
@section('title')
    تعديل الاذن
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الاذن </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/تعديل
                    الاذن</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    @include('Dashboard.messages_alert')
    <!-- row -->
    
                    <div class="mb-4 main-content-label"><h5>تعديل الاذن </h5></div>

                    <form action="{{ route('admin.invoices.update', $Invoices->id) }}" method="post">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="id" value="{{ $Invoices->id }}">
                        <div class="row">
                            <!-- Col -->
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">


                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">رقم الاذن</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="code" value="{{ $Invoices->code }}"
                                        class="form-control @error('code') is-invalid @enderror" disabled>
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
                                            </div><input class="form-control fc-datepicker"
                                                value="{{ $Invoices->invoice_date }}" placeholder="MM/DD/YYYY"
                                                type="text" name="invoice_date">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">نوع الاذن</label>
                            <select class="form-control" name="invoice_type" id="status" required>
                                <option value="1" {{ $Invoices->invoice_type == 1 ? 'selected' : '' }}>استلام</option>
                                <option value="2" {{ $Invoices->invoice_type == 2 ? 'selected' : '' }}>تسليم</option>
                                <option value="3" {{ $Invoices->invoice_type == 3 ? 'selected' : '' }}>مرتجعات عام
                                </option>
                            </select>
                        </div>

                        <div class="form-group" id="supplier-section"
                            style="{{ $Invoices->invoice_type == 1 ? 'display: block;' : 'display: none;' }}">
                            <label class="form-label">المورد</label>
                            <select class="form-control select2" name="supplier_id" style="width: 100%;">
                                <option value="" disabled>--اختر المورد</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ $supplier->id == $Invoices->supplier_id ? 'selected' : '' }}>
                                        {{ $supplier->code }}-{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" id="client-section"
                            style="{{ $Invoices->invoice_type == 2 ? 'display: block;' : 'display: none;' }}">
                            <label class="form-label">العميل</label>
                            <select class="form-control select2 " name="customer_id" style="width: 100%;">
                                <option value="" disabled>--اختر العميل</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ $customer->id == $Invoices->customer_id ? 'selected' : '' }}>
                                        {{ $customer->code }}-{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" id="contact-section"
                            style="{{ $Invoices->invoice_type == 3 ? 'display: block;' : 'display: none;' }}">
                            <label class="form-label">العميل/المورد</label>
                            <select class="form-control select2 " name="contact_id" id="contact-id" style="width: 100%;">
                                <option value="" disabled>--اختر العميل أو المورد</option>
                                @foreach ($contacts as $contact)
                                    <option value="{{ $contact['id'] }}" data-type="{{ $contact['type'] }}">
                                        {{ $contact['code'] }}-{{ $contact['name'] }}
                                        ({{ $contact['type'] == 'customer' ? 'عميل' : 'مورد' }})
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="contact_type" id="contact-type">
                        </div>

                        <div class="form-group">
                            <div class="col-md-3">
                                <label class="form-label">المندوب</label>
                            </div>
                            <div class="col-md-9">
                                <select class="form-control select2" name="employee_id" style="width: 100%;">
                                    @foreach ($admins as $admin)
                                        <option value="{{ $admin->id }}"
                                            {{ $Invoices->employee_id == $admin->id ? 'selected' : '' }}>
                                            {{ $admin->id }}{{ $admin->name }}-{{ $admin->permission == 3 ? 'مندوب تسليم ' : 'امين مخزن ' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group ">
                            <div class="col-md-3">
                                <label class="form-label"> موقع </label>
                            </div>
                            <select class="form-control " name="location_id">
                                @foreach ($locations as $location)
                                    <option
                                        value="{{ $location->id }}"{{ $location->id == $Invoices->location_id ? 'selected' : '' }}>
                                        {{ $location->location_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <div class="col-md-3">
                                <label class="form-label">اضافة بواسطة </label>
                            </div>
                            <div class="col-md-9">
                                <select class="form-control select2" name="created_by" style="width: 100%;">
                                    @foreach ($creators as $creator)
                                        <option value="{{ $creator->id }}"
                                            {{ $Invoices->created_by == $creator->id ? 'selected' : '' }}>
                                            {{ $creator->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label"> الحالة الاذن </label>
                            <select class="form-control" name="invoice_status" id="status" required>
                                <option value="1" {{ $Invoices->invoice_status == 1 ? 'selected' : '' }}>تحت تسليم
                                </option>
                                <option value="3" {{ $Invoices->invoice_status == 3 ? 'selected' : '' }}>مكتملة
                                </option>
                                <option value="4" {{ $Invoices->invoice_status == 4 ? 'selected' : '' }}>مرتجع
                                </option>
                                <option value="5" {{ $Invoices->invoice_status == 5 ? 'selected' : '' }}>ملغى
                                </option>
                            </select>
                        </div>
                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label"> ملاحظات  </label>
                                </div>
                                <div class="col-md-9">
                                    <textarea class="form-control" name="notes" rows="3"> {{ $Invoices->notes}}</textarea>
                                </div>
                            </div>	
                        </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">


                    <div class="form-group">
                        <label class="form-label">المنتجات</label>
                        <table class="table table-bordered" id="products-table">
                            <thead>
                                <tr>
                                    <th>المنتج</th>
                                    <th>الكمية</th>
                                    <th>حذف</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($invoiceProducts) && count($invoiceProducts) > 0)
                                    @foreach ($invoiceProducts as $product)
                                        <tr>
                                            <td>
                                                <select name="products[]" class="form-control select2 product" required>
                                                    @foreach ($allProducts as $p)
                                                        <option value="{{ $p->id }}"
                                                            {{ $p->id == $product->product_id ? 'selected' : '' }}>
                                                            {{ $p->product_code }} - {{ $p->product_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="quantities[]" class="form-control"
                                                    value="{{ $product->quantity }}" required min="1">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger remove-row">حذف</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" class="text-center">لا توجد منتجات</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-primary mt-2" id="add-product">إضافة منتج</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer text-left">
            <button type="submit" class="btn btn-success waves-effect waves-light">تحديث ألاذن</button>
        </div>
        </form>

    </div>
    </div>
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

                // إخفاء جميع الأقسام أولاً
                supplierSection.style.display = 'none';
                clientSection.style.display = 'none';
                contactSection.style.display = 'none';

                // إظهار القسم المناسب بناءً على القيمة المختارة
                if (selectedValue === '1') {
                    supplierSection.style.display = 'block';
                } else if (selectedValue === '2') {
                    clientSection.style.display = 'block';
                } else if (selectedValue === '3') {
                    contactSection.style.display = 'block';
                }
            }

            function updateContactType() {
                const selectedOption = contactSelect.options[contactSelect.selectedIndex];
                if (selectedOption) {
                    contactTypeInput.value = selectedOption.getAttribute('data-type');
                }
            }

            // تحديث الأقسام بناءً على الاختيار الحالي عند تحميل الصفحة
            updateSections();
            updateContactType();

            // إضافة مستمعين لتحديث الأقسام والنوع عند التغيير
            statusSelect.addEventListener('change', updateSections);
            contactSelect.addEventListener('change', updateContactType);
        });
        //------------------------------------------------
        document.addEventListener('DOMContentLoaded', function() {
            // تهيئة select2 للعناصر الموجودة
            initializeSelect2();

            // إضافة منتج جديد
            document.getElementById('add-product').addEventListener('click', function() {
                const tbody = document.querySelector('#products-table tbody');

                // إذا كان هناك رسالة "لا توجد منتجات"، قم بإزالتها
                const noProductsRow = tbody.querySelector('tr td[colspan="3"]');
                if (noProductsRow) {
                    noProductsRow.parentElement.remove();
                }

                const newRow = document.createElement('tr');
                newRow.innerHTML = `
            <td>
                <select name="products[]" class="form-control select2 product" required>
                    <option value="" disabled selected>اختر المنتج</option>
                    @foreach ($allProducts as $product)
                        <option value="{{ $product->id }}"> {{ $product->product_code }} -{{ $product->product_name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="quantities[]" class="form-control" required min="1" value="1">
            </td>
            <td>
                <button type="button" class="btn btn-danger remove-row">حذف</button>
            </td>
        `;

                tbody.appendChild(newRow);

                // تهيئة select2 للعنصر الجديد
                $(newRow.querySelector('.select2')).select2({
                    width: '100%',
                    placeholder: 'اختر...',
                    allowClear: true
                });
            });

            // حذف المنتج
            document.querySelector('#products-table tbody').addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-row')) {
                    const row = event.target.closest('tr');
                    const tbody = row.parentElement;

                    row.remove();

                    // إذا لم يعد هناك منتجات، أضف رسالة
                    if (tbody.children.length === 0) {
                        tbody.innerHTML = `
                    <tr>
                        <td colspan="3" class="text-center">لا توجد منتجات</td>
                    </tr>
                `;
                    }
                }
            });

            // التحقق من الفورم قبل الإرسال
            document.querySelector('form').addEventListener('submit', function(event) {
                const products = document.querySelectorAll('select[name="products[]"]');
                const quantities = document.querySelectorAll('input[name="quantities[]"]');

                if (products.length === 0 || quantities.length === 0) {
                    event.preventDefault();
                    alert('يجب إضافة منتج واحد على الأقل');
                    return false;
                }

                // التحقق من اختيار المنتجات وإدخال الكميات
                for (let i = 0; i < products.length; i++) {
                    if (!products[i].value || !quantities[i].value || quantities[i].value < 1) {
                        event.preventDefault();
                        alert('يرجى اختيار المنتج وإدخال كمية صحيحة');
                        return false;
                    }
                }

                return true;
            });
        });

        // دالة لتهيئة select2
        function initializeSelect2() {
            $('.select2').select2({
                width: '100%',
                placeholder: 'اختر...',
                allowClear: true
            });
        }
    </script>


    <!--Internal  Notify js -->
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('/plugins/notify/js/notifit-custom.js') }}"></script>

    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('dashboard/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!--Internal  jquery.maskedinput js -->
    <script src="{{ URL::asset('dashboard/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
    <!--Internal  spectrum-colorpicker js -->
    <script src="{{ URL::asset('dashboard/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
    <!-- Internal Select2.min js -->
    <script src="{{ URL::asset('dashboard/plugins/select2/js/select2.min.js') }}"></script>
    <!--Internal Ion.rangeSlider.min js -->
    <script src="{{ URL::asset('dashboard/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <!--Internal  jquery-simple-datetimepicker js -->
    <script src="{{ URL::asset('dashboard/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js') }}"></script>
    <!-- Ionicons js -->
    <script src="{{ URL::asset('dashboard/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js') }}"></script>
    <!-- Internal form-elements js -->
    <script src="{{ URL::asset('dashboard/js/form-elements.js') }}"></script>
@endsection
