<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Show the application's custom login form.
     */
    public function index()
    {
        $pageConfigs = ['myLayout' => 'blank'];
        return view('content.authentications.auth-login-cover', ['pageConfigs' => $pageConfigs]);
    }

    // public function store(Request $request)
    // {
    //     // Validation
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required|min:8',
    //     ], [
    //         'email.required' => 'The Email field is required.',
    //         'email.email' => 'Please enter a valid email address.',
    //         'password.required' => 'The Password field is required.',
    //         'password.min' => 'The :attribute must contain at least 8 characters.',
    //     ]);

    //     // Attempt authentication
    //     $credentials = $request->only('email', 'password');

    //     if (Auth::attempt($credentials)) {
    //         // Regenerate session for security
    //         $request->session()->regenerate();

    //         return redirect()->intended('dashboard')
    //             ->with('success', __('messages.signin.success'));
    //     }
    //     return redirect()->route('login')->with('error', 'Login failed. Please check your credentials.');
    // }
   
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ], [
            'email.required' => 'The Email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'The Password field is required.',
            'password.min' => 'The :attribute must contain at least 8 characters.',
        ]);

        // Attempt authentication
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Regenerate session for security
            $request->session()->regenerate();

            $user = Auth::user();

            // Deny ONLY when billing=1 AND project=0
            $denyAccess = ((int)($user->is_billing_contact ?? 0) === 1)
                    && ((int)($user->is_project_contact ?? 0) !== 1);

            if ($denyAccess) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()
                    ->route('login')
                    ->with('error', 'Access restricted. Billing-only accounts cannot sign in.');
            }

            return redirect()
                ->intended('dashboard')
                ->with('success', __('messages.signin.success'));
        }

        return redirect()
            ->route('login')
            ->with('error', 'Login failed. Please check your credentials.');
    }


    public function logout()
    {
        Auth::logout();
        $message = Lang::get('messages.logout.success');
        return redirect()->route('login')->with('success', $message);
    }
}
