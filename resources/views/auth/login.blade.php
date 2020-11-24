@extends('layouts.blank')

@section('title')
    Login
@endsection

@section('content')
<div class="container">
    <div class="row">

        <div class="col-md-6 col-md-offset-3 login-box">
            <div class="card">

                <div class="card-header text-center app-logo-text">
                    Pizza App
                </div>

                <div class="card-body">

                    <div class="row">
                        <form method="POST" action="{{ route('login') }}">

                            @csrf

                            <div class="col-md-8 col-md-offset-2 login-form-row">
                                <div class="form-group">

                                    <label for="email">Email</label>

                                    <input id="email" type="email"
                                           class="form-control @error('email') is-invalid @enderror" name="email"
                                           value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="form-error-msg" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror

                                </div>
                            </div>

                            <div class="col-md-8 col-md-offset-2 login-form-row">
                                <div class="form-group">

                                    <label for="password">Password</label>

                                    <input id="password" type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           name="password" required autocomplete="current-password">

                                    @error('password')
                                        <span class="form-error-msg" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror

                                </div>
                            </div>

                            <div class="col-md-8 col-md-offset-2 login-form-row">
                                <div class="form-group">

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            Remember Me
                                        </label>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-8 col-md-offset-2 login-form-row">
                                <div class="form-group">

                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>

                                    {{--@if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif--}}

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
