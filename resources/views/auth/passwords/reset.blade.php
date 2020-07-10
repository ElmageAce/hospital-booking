@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')

    <div class="login-box">
        <div class="white-box">

            <h3 class="box-title m-b-20">Reset Password</h3>

            <form class="form-horizontal form-material" id="loginform" method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <div class="col-xs-12">
                        <label for="email" hidden>Email</label>
                        <input class="form-control" id="email" type="text" placeholder="Email Address" name="email"
                               value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <div class="text-danger" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-12">
                        <label for="password" hidden>Password</label>
                        <input class="form-control" id="password" type="password" placeholder="Password" name="password"
                               autocomplete="new-password" required>

                        @error('password')
                        <div class="text-danger" role="alert">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-12">
                        <label for="password-confirm" hidden>Confirm Password</label>
                        <input class="form-control" id="password-confirm" type="password" placeholder="Password" name="password_confirmation"
                               autocomplete="new-password" required>
                    </div>
                </div>

                <div class="form-group text-center m-t-20">
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-info btn-block btn-rounded text-uppercase waves-effect waves-light">
                            {{ __('Reset Password') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

