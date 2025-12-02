@php
    $customizerHidden = 'customizer-hide';
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Login')

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('vendors/js/jquery/jquery.validate.min.js') }}"></script>
@endsection

@section('page-script')
    {{-- <script src="{{ asset('assets/js/pages-auth.js') }}"></script> --}}
@endsection

@section('content')
<div class="authentication-wrapper authentication-cover authentication-bg">
  <div class="authentication-inner row">
    <!-- /Left Text -->
    <div class="d-none d-lg-flex col-lg-6 p-0">
      <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
        <img src="{{ asset('assets/img/pmtool.jpg') }}" alt="auth-login-cover" class="img-fluid my-5 auth-illustration" data-app-light-img="illustrations/auth-login-illustration-light.png" data-app-dark-img="illustrations/auth-login-illustration-dark.png">

       
      </div>
    </div>
    <!-- /Left Text -->

    <!-- Login -->
    <div class="d-flex col-12 col-lg-6 align-items-center p-sm-5 p-4">
      <div class="w-px-400 mx-auto">
        <!-- Logo -->
        <div class="app-brand mb-4">
          <a href="{{url('/')}}" class="app-brand-link gap-2">
            <span class="app-brand-logo demo">@include('_partials.macros',["height"=>20,"withbg"=>'fill: #fff;'])</span>
          </a>
        </div>
        <!-- /Logo -->
        <h3 class=" mb-1 fw-bold">Welcome to {{config('variables.templateName')}}! 👋</h3>
        <p class="mb-4">Please sign-in to your account and start the adventure</p>

                    <form id="LoginForm" class="mb-3" action="{{ route('auth-login') }}" method="POST">
                        @csrf
                        @if ($message = Session::get('error'))
                            <div class="alert alert-danger alert-block mt-2">
                                <strong>{{ Session::get('error') }}</strong>
                            </div>
                        @endif
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-block mt-2">
                                <strong>{{ Session::get('success') }}</strong>
                            </div>
                        @endif
                        <div class="mb-4 custom-validation">
                            <div class="form-floating">
                                <input type="email" class="form-control {{ $errors->any() && str_contains($errors->first(), 'email') ? 'is-invalid' : '' }}" id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" autofocus>
                                <label for="email">Email</label>
                            </div>

                            @if ($errors->any() && str_contains($errors->first(), 'email'))
                                <div class="error-email d-block fv-plugins-message-container invalid-feedback">
                                    <div data-field="email">{{ $errors->first() }}</div>
                                </div>
                            @endif
                        </div>
                        <div class="mb-4 custom-validation form-password-toggle">
                            <div class="form-floating">
                                <input type="password" id="password" class="form-control {{ $errors->any() && str_contains($errors->first(), 'password') ? 'is-invalid' : '' }}" name="password" id="password" placeholder="Enter your password" value="{{ old('password') }}" aria-describedby="password" />
                                <label for="password">Password</label>
                                <span class="input-group-text cursor-pointer"><i id="eye-icon" class="ti ti-eye-off"></i></span>
                            </div>
                            @if ($errors->any() && str_contains($errors->first(), 'password'))
                                <div class="error-password d-block fv-plugins-message-container invalid-feedback">
                                    <div data-field="password">{{ $errors->first() }}</div>
                                </div>
                            @endif
                        </div>
                        <div class="mb-4 mt-1">
                            <div class="d-flex justify-content-between">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember_me" name="remember_me">
                                    <label class="form-check-label" for="remember_me">
                                        Remember Me
                                    </label>
                                </div>

                                <a class="text-primary" href="{{ url('forgot-password') }}">
                                    Forgot Password?
                                </a>
                            </div>
                        </div>
                        <button class="btn btn-primary d-grid w-100">
                            Sign in
                        </button>
                    </form>
                </div>
            </div>
            <!-- /Login -->
        </div>
    </div>
@endsection

@section('extra-script')
    <script>
        $(document).ready(function() {
            $('#LoginForm').validate({
                errorElement: 'span', // Wrap errors in 'span' element
                highlight: function(element) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                },
                // Custom unhighlight function to remove 'is-invalid' class from valid fields
                unhighlight: function(element) {
                    if (!$(element).hasClass('error')) {
                        $(element).removeClass('is-invalid').addClass('is-valid');
                    } else {
                        $(element).addClass('is-invalid').removeClass('is-valid');
                    }
                },
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 8
                    },
                },
                messages: {
                    email: {
                        required: "Please enter a valid email address",
                        email: "Please enter a valid email address"
                    },
                    password: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 8 characters long"
                    }
                }
            });

            $('#email').on('keyup', function() {
                var data = $('.error-email').next().prevObject.remove();
                $(this).removeClass('is-invalid');
            });

            $('#password').on('keyup', function() {
                var data = $('.error-password').next().prevObject.remove();
                $(this).removeClass('is-invalid');
            });

            // $('#email, #password').on('keyup', function () {
            //     alert('afdsf');
            //     if ($(this).valid()) {
            //         $(this).next('.invalid-feedback').removeClass('d-block');  // Remove d-block if valid
            //     } else {
            //         $(this).next('.invalid-feedback').addClass('d-block');  // Add d-block if invalid
            //     }
            // });

            setTimeout(function() {
                $(".alert-block").remove();
            }, 5000);
        });
    </script>
@endsection
