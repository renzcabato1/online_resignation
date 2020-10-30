@extends('layouts.app')
@section('content')
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
            <div class="row w-100">
                <div class="col-lg-4 mx-auto">
                    <div class="auth-form-light text-left p-5">
                        <div class="brand-logo">
                            <img src="{{asset('images/header.png')}}" style='width:100%;'>
                        </div>
                        <div style='width:100%;'><h2 style='text-align:center;'>OFF BOARDING</h2></div>
                        <h2 class="font-weight-light"></h2>
                        <form method="POST" action="{{ route('login') }}" onsubmit='show()'>
                            {{ csrf_field() }}
                            <div class="form-group">
                                <input type="email" class="form-control form-control-lg" name='email' id="exampleInputEmail1" placeholder="Email" required>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-lg" name='password' id="exampleInputPassword1" placeholder="Password" required>
                            </div>
                            <div class="mt-3">
                                <button  type='submit' class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn" >SIGN IN</button>
                                <br>
                               <a href='{{ asset('/manual.pdf') }}' target='_' class="btn btn-block btn-gradient-danger btn-lg font-weight-medium auth-form-btn" >HOW TO USE THE PORTAL</a>
                            </div>
                            <div class="container">
                                <div class='row justify-content-md-center' style='height:4vh;margin:0px '>
                                    @if($errors->any())
                                    <div class='col-md-12'>
                                        <span class="help-block" style="color:red;">
                                            <strong>{{$errors->first()}}</strong>
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
