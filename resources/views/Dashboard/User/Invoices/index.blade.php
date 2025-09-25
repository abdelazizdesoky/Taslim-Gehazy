@extends('Dashboard.layouts.master')
@section('css')
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <style>
        /* إضافة تأثيرات Hover لتغيير لون الـ Pagination عند التمرير */
        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }

        .pagination .page-item:hover .page-link {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
@endsection
@section('title')
    الاذون | تسليماتى
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الاذون </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ كل الاذون
                </span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    @include('Dashboard.messages_alert')
    <!-- row opened -->
    <div class="row row-sm">
        <!--div-->
        <div class="col-xl-12">
            <!---start --->
            <div class="col-lg-8">
                <div class="row row-sm">
                    <div class="col-sm-12 col-xl-4 col-lg-12 col-md-12">
                        <div class="card ">
                            <div class="card-body">
                                <div class="counter-status d-flex md-mb-0">
                                    <div class="counter-icon bg-primary-transparent">
                                        <i class="icon-layers text-primary"></i>
                                    </div>
                                    <div class="mr-auto">
                                        <h5 class="tx-13">الاذون</h5>
                                        <h2 class="mb-0 tx-22 mb-1 mt-1"> {{ \App\Models\Invoice::where('created_by', Auth::id())->count() }}</h2>
                                        <p class="text-muted mb-0 tx-11">
                                            <i class="si si-arrow-up-circle text-success mr-1"></i>
                                            استلام - {{ \App\Models\Invoice::where('invoice_type', 1)->where('created_by', Auth::id())->count() }}
                                            <br> <i class="si si-arrow-down-circle text-danger mr-1"></i>
                                            تسليم - {{ \App\Models\Invoice::where('invoice_type', 2)->where('created_by', Auth::id())->count() }}
                                            <br> <i class="si si-arrow-down-circle text-secondary  mr-1"></i>
                                            مرتجعات - {{ \App\Models\Invoice::where('invoice_type', 3)->where('created_by', Auth::id())->count() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xl-4 col-lg-12 col-md-12">
                        <div class="card ">
                            <div class="card-body">
                                <div class="counter-status d-flex md-mb-0">
                                    <div class="counter-icon bg-danger-transparent">
                                        <i class="icon-paypal text-danger"></i>
                                    </div>
                                    <div class="mr-auto">
                                        <h5 class="tx-13">الاذون المفعلة </h5>
                                        <h2 class="mb-0 tx-22 mb-1 mt-1">
                                            {{ \App\Models\Invoice::where('invoice_status', 1)->where('created_by', Auth::id())->count() }}</h2>
                                        <p class="text-muted mb-0 tx-11">
                                            <i class="si si-arrow-up-circle text-success mr-1"></i>
                                            استلام -
                                            {{ \App\Models\Invoice::where('invoice_type', 1)->where('invoice_status', 1)->where('created_by', Auth::id())->count() }}
                                            <br> <i class="si si-arrow-down-circle text-danger mr-1"></i>
                                            تسليم -
                                            {{ \App\Models\Invoice::where('invoice_type', 2)->where('invoice_status', 1)->where('created_by', Auth::id())->count() }}
                                            <br> <i class="si si-arrow-down-circle text-secondary mr-1"></i>
                                            مرتجعات -
                                            {{ \App\Models\Invoice::where('invoice_type', 3)->where('invoice_status', 1)->where('created_by', Auth::id())->count() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xl-4 col-lg-12 col-md-12">
                        <div class="card ">
                            <div class="card-body">
                                <div class="counter-status d-flex md-mb-0">
                                    <div class="counter-icon bg-success-transparent">
                                        <i class="icon-rocket text-success"></i>
                                    </div>
                                    <div class="mr-auto">
                                        <h5 class="tx-13">الاذون المكتملة </h5>
                                        <h2 class="mb-0 tx-22 mb-1 mt-1">
                                            {{ \App\Models\Invoice::where('invoice_status', 3)->count() }}</h2>
                                        <p class="text-muted mb-0 tx-11">
                                            <i class="si si-arrow-up-circle text-success mr-1"></i>
                                            استلام -
                                            {{ \App\Models\Invoice::where('invoice_type', 1)->where('invoice_status', 3)->where('created_by', Auth::id())->count() }}
                                            <br> <i class="si si-arrow-down-circle text-danger mr-1"></i>
                                            تسليم -
                                            {{ \App\Models\Invoice::where('invoice_type', 2)->where('invoice_status', 3)->where('created_by', Auth::id())->count() }}
                                            <br> <i class="si si-arrow-down-circle text-secondary mr-1"></i>
                                            مرتجعات -
                                            {{ \App\Models\Invoice::where('invoice_type', 3)->where('invoice_status', 3)->where('created_by', Auth::id())->count() }}
                                              <i class=" text-danger mr-1">X</i>
                                            ملغى  -
                                            {{ \App\Models\Invoice::where('invoice_status', 5)->count() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <!---end --->
            <div class="card mg-b-20">
                <div class="card-header pb-0">

                    <div class="  mg-b-20">
                        <div class="card ">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-2">
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('user.invoices.create') }}" class="btn btn-primary">اضافة
                                                اذن جديد</a>

                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('user.invoices.downloadTemplate') }}"
                                                class="btn btn-primary">تحميل نموذج Excel</a>

                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <form action="{{ route('user.invoices.import') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <input type="file" name="file" required>
                                                <button class="btn btn-success waves-effect waves-light"
                                                    type="submit">استيراد البيانات</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- card-header -->
                <div class="card-body">
                    <div class="table-responsive">

                        {!! $dataTable->table() !!}

                    </div>
                </div><!-- bd -->
            </div><!-- bd -->
        </div>
        <!--/div-->
    </div>
    <!-- /row -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    {!! $dataTable->scripts() !!}
    <!--Internal  Notify js -->
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('/plugins/notify/js/notifit-custom.js') }}"></script>
@endsection
