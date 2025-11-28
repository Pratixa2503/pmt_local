$('#formAuthentication').validate({
    errorElement: 'span',
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
    },
    highlight: function (element) {
        $(element).addClass('is-invalid');
    },
    unhighlight: function (element) {
        $(element).removeClass('is-invalid');
    },
    errorPlacement: function (error, element) {
        const mergeGroup = element.closest('.input-group-merge');
        if (mergeGroup.length > 0) {
            error.addClass('invalid-feedback d-block');
            error.insertAfter(mergeGroup);
        } else {
            error.addClass('invalid-feedback');
            error.insertAfter(element);
        }
    }
});
