<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ResetPassword;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    public function index()
    {
        $pageConfigs = ['myLayout' => 'blank'];
        return view('content.authentications.auth-reset-password-cover', ['pageConfigs' => $pageConfigs]);
    }

    public function resetPasswordCheck(Request $request)
    {
        $token = $request->token;
        $check = ResetPassword::where('token', $token)->first();
        if (!$check) {
            $message = Lang::get('messages.password.InvalidToken');
            return redirect()->route('login')->with('error', $message);
        } else {
            $difference = Carbon::now()->diffInSeconds($check->created_at);
            $expiry_time = env('RESET_LINK_EXPIRY_TIME');
            if ($difference > $expiry_time) {
                $message = Lang::get('messages.password.TokenExpired');
                return redirect()->route('login')->with('error', $message);
            } else {
                return redirect()->route('reset-password-cover.get', $token);
            }
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'token'     => 'required',
            'password'  => 'required|confirmed',
        ]);

        $check = ResetPassword::where('token', $request->token)->first();
        if (!$check) {
            $message = Lang::get('messages.password.InvalidToken');
            return redirect()->route('login')->with('error', $message);
        } else {
            $difference = Carbon::now()->diffInSeconds($check->created_at);
            $expiry_time = env('RESET_LINK_EXPIRY_TIME');
            if ($difference > $expiry_time) {
                $message = Lang::get('messages.password.TokenExpired');
                return redirect()->route('login')->with('error', $message);
            } else {
                $user = User::where('Email', $check->email)->first();
                if (!Hash::check($request->password, $user->password)) {
                    $user->update([
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60),
                    ]);
                    $data = ResetPassword::where('token', $check->token)->delete();
                    $message = Lang::get('messages.password.PasswordReset');
                    return redirect()->route('login')->with('success', $message);
                } else {
                    $message = Lang::get('messages.password.oldNewPassword');
                    return redirect()->back()->withInput()->with('error', $message);
                }
            }
        }
    }
}
