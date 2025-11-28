@extends('layouts/layoutMaster')

@section('title', 'Change Password')

@section('vendor-style')
    {{-- <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" /> --}}
@endsection

<!-- Page -->
@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-profile.css') }}" />
    {{-- <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script> --}}
@endsection

@section('vendor-script')
    {{-- <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script> --}}
@endsection

@section('page-script')
    {{-- <script src="{{ asset('assets/js/profile-auth.js') }}"></script> --}}
@endsection

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">{{ __('My Profile') }} /</span> {{ __('Users') }}
    </h4>

    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="user-profile-header-banner">
                    <img src="{{ asset('assets/img/pages/profile-banner.png') }}" alt="User Profile Banner" class="rounded-top" height="100%" data-app-light-img="illustrations/auth-login-illustration-light.png" data-app-dark-img="illustrations/auth-login-illustration-dark.png">
                </div>
                <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                    <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                        <img src="{{ asset('assets/img/avatars/blank.png') }}" alt="user image"
                            class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img">
                    </div>
                    <div class="flex-grow-1 mt-3 mt-sm-5">
                        <div
                            class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                            <div class="user-profile-info">
                                <h4>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h4>
                                <ul
                                    class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                    <li class="list-inline-item">
                                        <i class='ti ti-crown'></i>
                                        {{ ucwords(auth()->user()->getRoleNames()->first()) }}
                                    </li>
                                </ul>
                            </div>
                            <a href="javascript:void(0)" class="btn btn-primary">
                                <i class='ti ti-user-check me-1'></i>Connected
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Header -->

    <!-- Navbar pills -->
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-pills flex-column flex-sm-row mb-4">
                <li class="nav-item"><a class="nav-link" href="{{ route('pages-profile-user') }}"><i class='ti-xs ti ti-user-check me-1'></i> {{ __('Profile') }}</a></li>
                <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class='ti-xs ti ti-layout-grid me-1'></i> {{ __('Change Password') }}</a></li>
            </ul>
        </div>
    </div>
    <!--/ Navbar pills -->

    <!-- Users Cards -->
    <div class="row g-4">
        <div class="card mb-4">
            <h5 class="card-header">Change Password</h5>
            <div class="card-body">
                <form id="formAuthentication" method="POST">
                    <div class="message"></div>
                    <div class="row">
                        <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                        <div class="mb-3 col-md-6 form-password-toggle custom-validation">
                            <label class="form-label" for="currentPassword">Current Password</label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="password" name="currentPassword" id="currentPassword" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                            <span class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6 form-password-toggle custom-validation">
                            <label class="form-label" for="newPassword">New Password</label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="password" id="newPassword"  name="newPassword" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                            <span class="invalid-feedback"></span>
                        </div>

                        <div class="mb-3 col-md-6 form-password-toggle custom-validation">
                            <label class="form-label" for="password_confirmation">Confirm New Password</label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                            <span class="invalid-feedback"></span>
                        </div>
                        <div>
                            <button class="btn btn-primary me-2 submit">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--/ Users Cards -->
@endsection

@section('extra-script')
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript">
        var table;
        $(document).ready(function() {
            $('#formAuthentication').validate({
                errorElement: 'span',
                errorClass: 'invalid-feedback', // Bootstrap-compatible error styling
                highlight: function(element) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid').addClass('is-valid');
                },
                errorPlacement: function(error, element) {
                    // Place error outside input-group if exists
                    if (element.closest('.input-group').length) {
                        error.insertAfter(element.closest('.input-group'));
                    } else {
                        error.insertAfter(element);
                    }
                },
                rules: {
                    currentPassword: {
                        required: true,
                        minlength: 8
                    },
                    newPassword: {
                        required: true,
                        minlength: 8
                    },
                    password_confirmation: {
                        required: true,
                        minlength: 8,
                        equalTo: "#newPassword"
                    }
                },
                messages: {
                    currentPassword: {
                        required: "Please provide a current password",
                        minlength: "Your current password must be at least 8 characters long"
                    },
                    newPassword: {
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

            $(document).on('click', '.submit', function(event) {
                event.preventDefault();
                if ($('#formAuthentication').valid()) {
                    submitForm('#formAuthentication');
                }
            });

            function submitForm(formSelector) {
                var form = $(formSelector);
                var serialized = new FormData(form[0]);
                $('.loader').removeClass('d-none');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: "{{ route('password-update') }}",
                    data: serialized,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status) {
                            $('.message').html(
                                '<div class="alert alert-success alert-block mt-2 mb-4"><strong>' +
                                response.message + '</strong></div>');
                            $('#currentPassword').val('');
                            $('#newPassword').val('');
                            $('#password_confirmation').val('');
                            setTimeout(() => {
                                $(".alert-block").remove();
                            }, 5000);
                        } else {
                            $('.message').html(
                                '<div class="alert alert-danger alert-block mt-2 mb-4"><strong>' +
                                response.message + '</strong></div>');
                            setTimeout(() => {
                                $(".alert-block").remove();
                            }, 5000);
                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.error('Error:', textStatus, errorThrown);
                    }
                });
            }
        });
    </script>
@endsection
