<!DOCTYPE html>
<html>
<head>
    <title>Your Login Credentials</title>
</head>
<body>
    <p>Hello <strong>{{ $customer->first_name }} {{ $customer->last_name }}</strong>,</p>

    <p>Your account has been created. Here are your login credentials:</p>

    <p>
        <strong>Email:</strong> {{ $customer->email }}<br>
        <strong>Password:</strong> {{ $password }}
    </p>

    <p>Please log in to the <strong><a href="{{ env('APP_URL') }}">site</a></strong> using the email and password above.</p>

    <p>Regards,<br>Springbord Team</p>
</body>
</html>
