<!DOCTYPE html>
<html lang="en">

<head>
    <title>Springbord - Reset Password link</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        .XbIp4.jmmB7 {
            margin-left: 0px;
        }

        .bluebg-body {
            padding: 48px;
        }

        .whitebg-body {
            padding: 50px;
        }

        .email-foote-text {
            display: flex;
            justify-content: center;
        }

        .he-40 {
            height: 40px;
        }

        @media (max-width: 767.98px) {
            .bluebg-body {
                padding: 20px;
            }

            .whitebg-body {
                padding: 20px;
            }

            .company-text {
                display: block;
                text-align: center;
                margin: 0 auto;
            }

            .email-foote-text {
                display: block;
            }
            .he-40 {
                height: 70px;
            }
        }
    </style>
</head>

<body class="bluebg-body" style=" background: #F4FCFF; font-family: 'Arial', sans-serif; margin: 0 auto;">
    <table class="body-wrap" cellpadding="0" cellspacing="0"
        style="width: 100%; max-width:600px; text-align: center; display: table; margin: 0 auto;">
        <tr>
            <td class="whitebg-body" style="box-shadow: 0px 4px 10px rgba(145, 158, 171, 0.16); border-radius: 16px;background: #FFFFFF; text-align: center; margin: 0 auto;">
                <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td align="center"  class="logo" style="margin: 0px auto 15px; display: block;">
                            <a href="javascript:void(0);" class="app-brand-link gap-2">
                                <span class="app-brand-logo">@include('_partials.macros', ['height' => 60, 'withbg' => 'fill: #fff;'])</span>
                            </a>
                        </td>
                    </tr>
                    {{-- <tr style="color: #535650; font-size: 14px; font-family: 'Arial', sans-serif;">
                        <td align="center">
                            <h2 style="margin-top: 30px;">Reset Password</h2>
                        </td>
                    </tr> --}}
                    <tr style="color: #535650; font-size: 14px; font-family: 'Arial', sans-serif; margin: 0px auto; padding: 5px 0px; display: block;">
                        <td align="left">
                            <p style="margin: 0px 0px;">Dear <strong>{{ $username }},</strong>
                            </p>
                        </td>
                    </tr>
                    <tr style="color: #535650; font-size: 14px; font-family: 'Arial', sans-serif; margin: 0px auto; display: block; padding: 5px 0px;">
                        <td align="left">
                            <p style="margin: 10px 0px 0px 0px;">We received a request to reset the password for the account associated with this email address. If you made this request, please follow the instructions below.
                            </p>
                        </td>
                    </tr>
                    <tr style="color: #535650; font-size: 14px; font-family: 'Arial', sans-serif; margin: 0px auto; display: block; padding: 5px 0px;">
                        <td align="left">
                            <p style="margin: 0px 0px;"><strong>Click the link below to reset your password:</strong></p>
                        </td>
                    </tr>
                    <tr style="color: #535650; font-size: 14px; font-family: 'Arial', sans-serif; margin: 0px auto 0; display: block; padding: 5px 0px; border: 0px;">
                        <td align="left">
                            <table border="0" cellpadding="0" cellspacing="0">
                                <tbody>
                                    <tr>
                                        <td bgcolor="#E35205" valign="top" style="padding:6px 10px; border-radius:0px; color: #fff; background-color:#E35205">
                                            <a style="line-height: 24px; text-decoration: none; word-break: break-word; font-weight: 500; display: block; font-size: 16px;color: #ffffff;" href="{{ route('reset-password-check', 'token=' . $token) }}" target="_blank">
                                                Reset Password
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- <p style="margin-bottom: 0px; margin-top:0px;"> -->

                            <!-- </p> -->
                        </td>
                    </tr>
                    <tr style="color: #535650; font-size: 14px; font-family: 'Arial', sans-serif; margin: 0px auto 0; display: block; padding: 5px 0px;">
                        <td align="left">
                            <p style="margin: 10px 0px 0px 0px;">If you did not request a password reset, please ignore this email.</p>
                        </td>
                    </tr>
                    <tr style="color: #535650; font-size: 14px; font-family: 'Arial', sans-serif; margin: 0px auto; display: block; padding: 5px 0px;">
                        <td align="left">
                            <p style="margin: 10px 0px 0px 0px;">If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:<br><small><a href="{{ route('reset-password-check', 'token=' . $token) }}">{{ route('reset-password-check', 'token=' . $token) }}</a></small></p>
                        </td>
                    </tr>
                    <tr style="color: #535650; font-size: 14px; font-family: 'Arial', sans-serif; margin: 0px auto; display: block; padding: 5px 0px;">
                        <td align="left">
                            <p>Best regards,</p>
                            <p style="margin: 10px 0px 0px 0px;">Springbord</p>
                        </td>
                    </tr>
                    <tr style="color: #fff; font-size:13px; font-family:'Arial',sans-serif; width:100%; margin: 0px auto 0px; background-color: #E35205; border-top: 1px solid #eee; padding: 10px 0px; text-align: center; display: inline-block;">
                        <td style="width:100%;  display: inline-block; line-height: 20px;">
                            <span>
                                © Copyright {{ date('Y') }} All Right Reserved By
                            </span>
                            <span class="company-text"> Springbord </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
