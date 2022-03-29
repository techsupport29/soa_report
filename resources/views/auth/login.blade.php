@extends('layouts.app')


@section('content')
    <div class="custom-layout__login container-fluid">
        <div class="container">


            <div class="d-flex justify-content-center align-items-center flex-column  ">

                <div class=" login-box  d-flex justify-content-center align-items-center p-4 card"
                    style="max-width: 90%; width: 600px">

                    <div class="card-body container-fluid px-5 py-3 x">
                        <div class="col-lg-12 login-key">
                            <i class="fa fa-key" aria-hidden="true"></i>
                        </div>
                        <h1 class="text-center text-white  login-title">KIOSK SOA</h1>
                            @foreach($errors->all() as $error)
                                <span class="text-danger h4">     <i class="nav-icon fas fa-exclamation-triangle"> </i> {{$error}}</span>
                            @endforeach
                           
                        <form method="POST" action="{{ route('login') }}" class="login-form">
                            @csrf
                          

                            <div class="mb-3">
                                @error('firstname')

                                @enderror
                                <label for="login"  class="form-label  custom-input__label">
                                    {{ __('Username or Email') }}
                                </label>

                                <div class="d-flex flex-column">
                                    <input id="login" type="text"
                                        class="custom-input form-control{{ $errors->has('username') || $errors->has('email') ? ' is-invalid' : '' }}"
                                        name="login" value="{{ old('username') ?: old('email') }}" required autofocus>

                                    {{-- @if ($errors->has('username') || $errors->has('email'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('username') ?: $errors->first('email') }}</strong>
                                        </span>
                                    @endif --}}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password"  class="form-label  custom-input__label">{{ __('Password') }}</label>

                                <input id="password" type="password"
                                    class="form-control custom-input form-control{{ $errors->has('username') || $errors->has('email') ? ' is-invalid' : '' }}"
                                    name="password" required autocomplete="current-password">

                                {{-- @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror --}}

                            </div>

                            <div class="d-grid col-6 mx-auto">
                                <button class="btn btn-block btn-primary mt-4 py-3 text-uppercase custom-button "
                                    type="submit">
                                    {{ __('Sign in') }}</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
