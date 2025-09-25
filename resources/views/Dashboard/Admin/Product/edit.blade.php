@extends('Dashboard.layouts.master')
@section('css')
    <!--Internal   Notify -->
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet"/>
@endsection
@section('title')
    تعديل المنتج
@endsection
@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">المنتجات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل المنتج</span>
        </div>
    </div>
</div>
@endsection
@section('content')
@include('Dashboard.messages_alert')
@include('Dashboard.Admin.Product.brand.add')
@include('Dashboard.Admin.Product.product_type.add')
<!-- row -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('admin.products.update', $product->id) }}" method="post" autocomplete="off">
                    @csrf
                    @method('PUT') <!-- يستخدم مع التحديث لتحديد نوع الطلب PUT -->
                    
                    <input type="hidden" name="id" value="{{ $product->id }}">

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">ماركة  </label>
                            </div>
                            <div class="col-md-9">
                                <input value="{{ $product->brand->brand_name ?? 'غير محدد' }}" disabled class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">نوع المنتج  </label>
                            </div>
                            <div class="col-md-9">
                                <input value="{{$product->productType->type_name ?? 'غير محدد' }}" disabled class="form-control">
                            </div>
                        </div>
                    </div>

                    
                   

                    <div class="form-group" id="client-section">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">اسم المنتج </label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="product_name" value="{{ $product->product_name }}" class="form-control @error('product_name') is-invalid @enderror" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" id="client-section">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">تفاصيل المنتج  </label>
                            </div>
                            <div class="col-md-9">

                                        <input type="text" name="detail_name" value="{{ $product->detail_name }}" class="form-control @error('product_name') is-invalid @enderror" required>
                               
                              

                            </div>
                        </div>
                    </div>

                    <div class="form-group" id="client-section">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">كود المنتج</label>
                            </div>
                            <div class="col-md-9">
                                       <input type="text" name="product_code" value="{{ $product->product_code }}" class="form-control @error('product_name') is-invalid @enderror" required>

                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="client-section">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label    ">كود المورد</label>
                            </div>
                            <div class="col-md-9">          
                                        <input type="text" name="vendor_code" value="{{ $product->vendor_code }}" class="form-control @error('product_name') is-invalid @enderror" required>
                            </div>
                        </div>
                    </div>      
                    

                    <div class="card-footer text-left">
                        <button type="submit" class="btn btn-success waves-effect waves-light">حفظ التعديلات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 
    </div>
</div>
@endsection
@section('js')
<script>

</script>
<script src="{{ URL::asset('dashboard/plugins/notify/js/notifIt.js') }}"></script>
<script src="{{ URL::asset('/plugins/notify/js/notifit-custom.js') }}"></script>
@endsection
