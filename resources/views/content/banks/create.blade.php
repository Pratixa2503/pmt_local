@extends('layouts/layoutMaster')

@section('title', $title)

@section('vendor-style')
@endsection

@section('vendor-script')
@endsection

@section('content')
<div class="card mb-4">
    <div class="card-header">
        <h4>{{ $title }}</h4>
    </div>
    <div class="card-body">
        @include('content.banks.form')
    </div>
</div>
@endsection
@section('page-script')
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
@endsection
@section('extra-script')

<script>
    $(document).ready(function () {
        $("#bankForm").validate({
            rules: {
                bank_name: {
                    required: true
                },
                account_holder_name: {
                    required: true
                },
                account_number: {
                    required: true,
                    digits: true,
                    minlength: 9,
                    maxlength: 18
                },
                ifsc_code: {
                    required: false,
                    minlength: 11,
                    maxlength: 11,
                    pattern: /^[A-Z]{4}0[A-Z0-9]{6}$/
                },
                branch_name: {
                    required: true
                },
                status: {
                    required: true
                }
            },
            messages: {
                bank_name: {
                    required: "Bank Name is required"
                },
                account_holder_name: {
                    required: "Account Holder Name is required"
                },
                account_number: {
                    required: "Account Number is required",
                    digits: "Only digits are allowed",
                    minlength: "Account number must be at least 9 digits",
                    maxlength: "Account number should not exceed 18 digits"
                },
                ifsc_code: {
                    minlength: "IFSC code must be 11 characters",
                    maxlength: "IFSC code must be 11 characters",
                    pattern: "Enter a valid IFSC code format (e.g., SBIN0001234)"
                },
                branch_name: {
                    required: "Branch Name is required"
                },
                status: {
                    required: "Please select the status"
                }
            },
            errorElement: 'div',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback d-block');
                element.closest('.form-group, .mb-3').append(error);
            },
            highlight: function (element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>

@endsection