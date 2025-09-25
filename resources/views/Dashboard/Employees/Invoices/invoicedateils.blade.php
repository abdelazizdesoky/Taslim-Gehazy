
@extends('Dashboard.layouts.master')
@section('css')
  
    <style>
        .serial-item {
            display: flex;
            align-items: center;
            padding: 5px;
            margin-bottom: 5px;
            border: 1px solid #ddd;
            border-radius: 3px;
            background-color: #f0f8ff;
        }
        .remove-serial-btn {
            background: #f44336; /* لون خلفية زر الإلغاء */
            color: white;
            border: none;
            border-radius: 3px;
            padding: 0 5px;
            margin-left: 10px;
            cursor: pointer;
        }
        .remove-serial-btn:hover {
            background: #d32f2f; /* لون خلفية زر الإلغاء عند التحويم */
        }
        #interactive video {
    width: 100%;
    height: auto;
}

    </style>
@endsection

@section('title')
عرض المنتجات بالاذن  
@endsection

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">
              
                @if(Auth::guard('admin')->check())
										@php $permission = Auth::guard('admin')->user()->permission; @endphp
										
										@if($permission == 3)
											<a href="{{route('employee.invoices.update',2)}}">
											الاذون 
										</a>
										

										@elseif($permission == 4)
											<a href="{{route('employee.invoices.update',1)}}">
										الاذون  
											</a>
										@endif
									
									@endif

                
                
            
            
            
            
            </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ عرض المنتجات   </span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')
@include('Dashboard.messages_alert')
<!-- row -->
<div class="col-md-12 col-xl-12 col-xs-12 col-sm-12">
    <div class="card">
        <div class="card-body">
            رقم الاذن - {{$invoice->code}}
            <div class="main-content-label mg-b-5"></div>
            <p class="mg-b-20">
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
                
                 <i class="mdi mdi-truck-fast text-gray"></i> {{ $invoice->customer->name??$invoice->supplier->name}}
            </p>
            
            <div class="container mt-4">
          

       

                <!--------------------------------------------------------------------------->

                <h4>المنتجات:</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>اسم المنتج</th>
                            
                            <th>الكمية</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->products as $product)
                            <tr>
                                <td>
                                    {{ $product->product_code }} - {{ $product->product_name }}  - {{ $product->vendor_code }} 
                                </td>
                                <td>{{ $product->pivot->quantity }}</td>
                               
                            </tr>
                        @endforeach
                    </tbody>
                </table>

  
         <!--------------------------------------------------------------------------->
         
         @if($invoice->invoice_status == 1)
         <a href="{{route ('employee.invoices.edit',$invoice->id)}}" class="btn btn-sm btn-success"><i class="fas fa-edit"></i>سحب السيريال  </a>	
         @else
         @endif






              
            </div>
        </div>
    </div>
        </div>
    </div>
</div>
<!-- row closed -->
@endsection

@section('js')






@endsection
