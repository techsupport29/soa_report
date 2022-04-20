@extends('layouts.app')


@section('content')
	<div class="container-login100" style="background-image: url('image/login_bg.png');">
  
        <div style="text-align:center;">
            <div class="wrap-login100 p-l-55 p-r-55 p-t-20 p-b-30">
                <v-row >
                    <v-col col="md-6">
                        <div class="login-box">
                            <div   class="kiosk-soa" >
                                <img  src="image/KIOSK SOA.png" width="250" height="30" title="Logo of a company" alt="Logo of a company" />
                            </div>
                        @foreach($errors->all() as $error)
                            <div class=>
                                <span class="text-danger h4">  {{$error}}</span>
                            </div>
                        @endforeach
                        <form  method="POST" action="{{ route('login') }}" class="login100-form validate-form">
                            <div class="mt-5 ml-5 text-left">
                                      
                                @csrf
                                <h5 class="Signin-login-text">Signin to your account</h5>
                                <div class="left-inner-addon input-container mt-2">
                                    <i class="fa fa-user  fa-2x" ></i>
                                    <input type="text"
                                           class="custom-login-input" 
                                           name="login"
                                           placeholder="Enter Username or Email"
                                           required autofocus
                                    />
                                </div>
                                <div class="left-inner-addon input-container mt-4">
                                    <i class="fa fa-lock  fa-2x" ></i>
                                    <input type="password"
                                            name="password" required autocomplete="current-password"
                                           class="custom-login-input" 
                                           placeholder="Enter password" 
                                    />
                                </div>
                            </div>
                            <h5 class="forgot-password-login">Forgot Password??</h5>
                            
                            <div class="container-login100-form-btn mt-5">
                                <button class="login100-form-btn">
                                    Sign In
                                </button>
                            </div>
                        </form>
                            
                        </div>
                    </v-col >
                    <v-col col="md-6">
                        <div class="login-icon">
                                    <img  src="image/LCUKY-8 1.png" width="300" height="200" title="Logo of a company" alt="Logo of a company" />
                                    <div class="login-bottom-pic">
                                        <img  src="image/login-pic.svg" width="700" height="650" title="Logo of a company" alt="Logo of a company" />
                                    </div>
                                   
                        </div>
                       
                    </v-col >
                </v-row>




                {{-- <img  src="image/LUCKY.png" width="300" height="150" title="Logo of a company" alt="Logo of a company" />
               
                @foreach($errors->all() as $error)
                    <div class=>
                        <span class="text-danger h4">  {{$error}}</span>
                    </div>
                @endforeach --}}
                {{-- <form  method="POST" action="{{ route('login') }}" class="login100-form validate-form">

                    @csrf
                    <span class="login100-form-title p-b-20 p-t-30">
                        KIOSK SOA
                    </span>

                    <div class="wrap-input100 validate-input m-b-20" data-validate="Enter Username or Email">
                        <input  type="text"  placeholder="Username or Email"
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


                </form> --}}


            </div>
        </div>
	</div>
@endsection
