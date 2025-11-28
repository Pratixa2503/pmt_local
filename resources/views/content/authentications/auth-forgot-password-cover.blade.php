@php
    $customizerHidden = 'customizer-hide';
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Forgot Password')

@section('vendor-style')
    <!-- Vendor -->
    {{-- <link rel="stylesheet" href="{{asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" /> --}}
@endsection

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}">
@endsection

@section('vendor-script')
    {{-- <script src="{{asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script> --}}
@endsection

@section('page-script')
    {{-- <script src="{{asset('assets/js/pages-auth.js')}}"></script> --}}
@endsection

@section('content')
    <div class="authentication-wrapper authentication-cover authentication-bg">
        <div class="authentication-inner row">

            <!-- /Left Text -->
            <div class="d-none d-lg-flex col-lg-6 p-0">
                <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
                    <img src="{{ asset('assets/img/pmtool.jpg') }}"
                        alt="auth-forgot-password-cover" class="img-fluid my-5 auth-illustration"
                        data-app-light-img="illustrations/auth-forgot-password-illustration-light.png"
                        data-app-dark-img="illustrations/auth-forgot-password-illustration-dark.png">
                </div>
            </div>
            <!-- /Left Text -->

            <!-- Forgot Password -->
            <div class="d-flex col-12 col-lg-6 align-items-center p-sm-5 p-4">
                <div class="w-px-400 mx-auto">
                    <!-- Logo -->
                    <div class="app-brand mb-4">
                        <a href="{{ url('/') }}" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">@include('_partials.macros', ['height' => 20, 'withbg' => 'fill: #fff;'])</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h3 class="mb-1 fw-bold">Forgot Password?</h3>
                    <p class="mb-4">Enter your email and we'll send you instructions to reset your password</p>
                    <form id="formAuthentication" class="mb-3" action="{{ route('forgot-password-cover') }}"
                        method="POST">
                        @csrf
                        @if (Session::get('success'))
                            <div class="alert alert-success alert-block mt-2">
                                <strong>{{ Session::get('success') }}</strong>
                            </div>
                        @endif
                        @if (Session::get('error'))
                            <div class="alert alert-danger alert-block mt-2">
                                <strong>{{ Session::get('error') }}</strong>
                            </div>
                        @endif
                        <div class="mb-4 custom-validation">
                            <div class="form-floating">
                                <input type="email" class="form-control {{ $errors->any() && str_contains($errors->first(), 'email') ? 'is-invalid' : '' }}" id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" autofocus>
                                <label for="email">Email</label>
                            </div>

                            @if ($errors->any() && str_contains($errors->first(), 'email'))
                                <div class="error fv-plugins-message-container invalid-feedback d-block">
                                    <div data-field="email">{{ $errors->first() }}
                                    </div>
                                </div>
                            @endif
                        </div>
                        <button class="btn btn-primary btn-lg d-grid w-100 mb-4">Reset Password</button>
                    </form>
                    <div class="text-center">
                        <a href="{{ url('login') }}" class="d-flex align-items-center justify-content-center">
                            <i class="ti ti-chevron-left scaleX-n1-rtl"></i>
                            Back to Sign in
                        </a>
                    </div>
                </div>
            </div>
            <!-- /Forgot Password -->
        </div>
    </div>
@endsection
@section('extra-script')
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script>
$("document").ready(function() {
    $('#email').on('keyup', function() {
        var data = $('.error').next().prevObject.remove();
        $(this).removeClass('is-invalid');
    });

    $('#formAuthentication').validate({
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
        },
        messages: {
            email: {
                required: "Please enter a valid email address",
                email: "Please enter a valid email address"
            },
        }
    });

    setTimeout(function() {
        $(".alert-block").remove();
    }, 5000);
});
</script>
@endsection

