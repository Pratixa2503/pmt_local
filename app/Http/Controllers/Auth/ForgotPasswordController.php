<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ResetPassword;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function index()
    {
        $pageConfigs = ['myLayout' => 'blank'];
        return view('content.authentications.auth-forgot-password-cover', ['pageConfigs' => $pageConfigs]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        try {
            $token = Str::random(20);

            $check = User::where('email', $request->email)->first();
            if (!$check) {
                $message = Lang::get('messages.password.MatchEmail');
                return redirect()->back()->withInput()->withErrors($message);
            } else {
                ResetPassword::where('email', $request->email)->delete();

                ResetPassword::insert([
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);

                Mail::send('emails.forgotPassword', ['token' => $token, 'username' => $request->email], function ($message) use ($request) {
                    $message->to($request->email);
                    $message->subject('Springbord - Reset Password link');
                });

                $message = Lang::get('messages.password.TokenSent');

                return redirect()->back()->with('success', $message);
            }
        } catch (Exception $exception) {
            $status   = false;
            $message  = $exception->getMessage();
            return back()->withError($message)->withInput();
        }
    }
}
