@extends('Dashboard.layouts.master2')
@section('title')
برنامج تسليماتى 
@endsection
@section('css')
<style>
    .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background-color: #f7f7f7;
    }
    .login-card {
        width: 100%;
        max-width: 450px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        border-radius: 8px;
        background-color: #f0f0f0;
    }
    @media (max-width: 768px) {
        .login-card {
            width: 90%;
            margin: auto;
        }
    }
</style>
@endsection
@section('content')
<div class="container-fluid login-container">
    <div class="login-card">
        <div class="d-flex justify-content-center mb-5">
            <a href="#"><img src="{{ URL::asset('dashboard/img/brand/favicona.png') }}" class="sign-favicon ht-200" alt="logo"></a>
            <h1 class="main-logo1 ml-1 mr-0 my-auto tx-28"></h1>
        </div>
        <div class="main-signup-header text-center">
            <h2> مرحبا </h2>
            <br>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('login.admin') }}">
                @csrf
                <div class="form-group">
                 
                    <input class="form-control" placeholder="ادخل اسم المستخدم" type="text" name="email" required autofocus autocomplete="username">
                </div>
                <div class="form-group">
                   
                    <input class="form-control" placeholder="ادخل الباسورد" type="password" name="password" required autocomplete="current-password" />
                </div>
                <button class="btn btn-main-primary btn-block"> دخول </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection
