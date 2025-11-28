@php
    $customizerHidden = 'customizer-hide';
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Reset Password')

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
    <div class="authentication-wrapper authentication-cover authentication-bg ">
        <div class="authentication-inner row">

            <!-- /Left Text -->
            <div class="d-none d-lg-flex col-lg-6 p-0">
                <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
                    <img src="{{ asset('assets/img/pmtool.jpg') }}"
                        alt="auth-reset-password-cover" class="img-fluid my-5 auth-illustration"
                        data-app-light-img="illustrations/auth-reset-password-illustration-light.png"
                        data-app-dark-img="illustrations/auth-reset-password-illustration-dark.png">

                   
                </div>
            </div>
            <!-- /Left Text -->

            <!-- Reset Password -->
            <div class="d-flex col-12 col-lg-6 align-items-center p-4 p-sm-5">
                <div class="w-px-400 mx-auto">
                    <!-- Logo -->
                    <div class="app-brand mb-4">
                        <a href="{{ url('/') }}" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">@include('_partials.macros', ['height' => 20, 'withbg' => 'fill: #fff;'])</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h3 class="mb-1 fw-bold">Change password</h3>
                    <form id="formAuthentication" class="mb-3" action="{{ route('reset-password-cover') }}"
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
                        <input type="hidden" name="token" value="{{ Request::segment(2) }}">
                        <div class="mb-4 custom-validation form-password-toggle">
                            <div class="form-floating">

                                <input type="password" id="password" class="form-control" name="password" placeholder="Enter password" aria-describedby="password" />
                                <label for="password">New Password</label>
                                <span class="input-group-text cursor-pointer"><i  class="eye-icon ti ti-eye-off"></i></span>
                            </div>
                        </div>
                        <div class="mb-4 custom-validation form-password-toggle">
                            <div class="form-floating">
                                <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" placeholder="Enter Confirm Password" aria-describedby="password" />
                                <label for="password_confirmation">Confirm Password</label>
                                <span class="input-group-text cursor-pointer"><i class="eye-icon-confirmed ti ti-eye-off"></i></span>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-lg d-grid w-100 mb-4">
                            Change password
                        </button>
                        <div class="text-center">
                            <a href="{{ url('login') }}"
                                class="d-flex align-items-center btn-lg btn btn-outline d-grid w-100 justify-content-center">
                                <i class="ti ti-chevron-left scaleX-n1-rtl"></i>
                                Back to login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /Reset Password -->
        </div>
    </div>
@endsection
@section('extra-script')
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $("document").ready(function() {
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
                    password: {
                        required: true,
                        minlength: 8
                    },
                    password_confirmation: {
                        required: true,
                        minlength: 8,
                        equalTo: "#password"
                    }
                },
                messages: {
                    password: {
                        required: "Please provide a new password",
                        minlength: "Your new password must be at least 8 characters long"
                    },
                    password_confirmation: {
                        required: "Please provide a confirm password",
                        minlength: "Your confirm password must be at least 8 characters long",
                        equalTo: "The password and its confirm are not the same"
                    }
                }
            });
            setTimeout(function() {
                $(".alert-block").remove();
            }, 5000);
        });
    });
</script>
@endsection
