@extends('layouts.app')


@section('content')
	<div class="container-login100" style="background-image: url('image/background2.jpg');">
  
        <div style="text-align:center;">
            <div class="wrap-login100 p-l-55 p-r-55 p-t-20 p-b-30">
                <img  src="image/LUCKY.png" width="300" height="150" title="Logo of a company" alt="Logo of a company" />
               
                @foreach($errors->all() as $error)
                    <div class=>
                        <span class="text-danger h4">  {{$error}}</span>
                    </div>
                @endforeach
                <form  method="POST" action="{{ route('login') }}" class="login100-form validate-form">

                    @csrf
                    <span class="login100-form-title p-b-20 p-t-30">
                        KIOSK SOA
                    </span>

                    <div class="wrap-input100 validate-input m-b-20" data-validate="Enter Username or Email">
                        <input  type="text"  placeholder="Username or mail"
                        class="input100{{ $errors->has('username') || $errors->has('email') ? ' is-invalid' : '' }}"
                        name="login" value="{{ old('username') ?: old('email') }}" required autofocu>
                        <span class="focus-input100"></span>
                    </div>

                    <div class="wrap-input100 validate-input m-b-25" data-validate = "Enter password">
                        <input  type="password"  placeholder="Password"
                        class="input100{{ $errors->has('username') || $errors->has('email') ? ' is-invalid' : '' }}"
                        name="password" required autocomplete="current-password"
                        >

                        <span class="focus-input100"></span>
                    </div>

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn">
                            Sign In
                        </button>
                    </div>


                </form>


            </div>
        </div>
	</div>
@endsection
