@extends('Dashboard.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->


    <!-- /breadcrumb -->
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <div>
                <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1"> -{{ Auth::user()->name }}</h2>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            

            @if (Auth::guard('admin')->check())
                @php $permission = Auth::guard('admin')->user()->permission; @endphp

                @if ($permission == 3)
                    <div class="pr-1 mb-3 mb-xl-0">
                        <a href="{{ route('employee.invoices.update', 2) }}">
                            <button type="button" class="btn btn-info  ml-2"><i class="mdi mdi-filter-variant"></i>الاذون
                                تسليم </button>
                        </a>
                    </div>
					@elseif ($permission == 6)
                        <div class="pr-1 mb-3 mb-xl-0">
                            <a href="{{ route('employee.invoices.update', 3) }}">
                                <button type="button" class="btn btn-info  ml-2"><i
                                        class="mdi mdi-filter-variant"></i>الاذون تسليم </button>
                            </a>
                        </div>
                    
                @elseif($permission == 4)
                    <div class="pr-1 mb-3 mb-xl-0">
                        <a href="{{ route('employee.invoices.update', 1) }}">
                            <button type="button" class="btn btn-warning   ml-2"><i class="mdi mdi-refresh"></i>الاذون
                                استلام </button>
                        </a>
                    </div>
                @endif

            @endif



            <div class="pr-1 mb-3 mb-xl-0">
                <a href="{{ route('employee.invoices.completed') }}">
                    <button type="button" class="btn btn-danger  ml-2"><i class="mdi mdi-star"></i>الاذون مكتملة</button>
                </a>
            </div>
        </div>

    </div>
    <!-- /breadcrumb -->

@endsection
@section('content')
    <!-- row -->

    <div class="row">

    </div>
    <!-- row opened -->

    <!-- /row -->
    </div>
    </div>
@endsection
@section('js')
@endsection
