<?php

namespace App\Http\Controllers\pages;

use Helper;
use Exception;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;

class UserProfile extends Controller
{
    public function index(Request $request)
    {
        try {
            $data = $request;
            $title    = __('User Profile') . ' : ' .  __('Profile');
            $user   = Auth::user();
        } catch (Exception $exception) {
            $status   = false;
            $message  = $exception->getMessage();
            return back()->withError($message)->withInput();
        }
        return view('content.pages.pages-profile-user', compact('title', 'user'));
    }

    public function profileUserUpdate(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = $request->all();
                $first_name = $data['first_name'];
                $last_name = $data['last_name'];
                $userId = $data['userId'];
                $contact_no = $data['contact_no'];
                $company_name = $data['company_name'];

                $user = User::where('Id', $userId)->update(['first_name' => $first_name, 'last_name' => $last_name, 'contact_no' => $contact_no, 'company_name' => $company_name]);
                return response()->json([
                    'status'        => true,
                    'message'       => Lang::get('messages.profile.update'),
                    'first_name'    => $first_name,
                    'last_name'     => $last_name,
                    'contact_no'    => $contact_no,
                    'company_name'  => $company_name,
                ]);
            }
        } catch (Exception $exception) {
            $status   = false;
            $message  = $exception->getMessage();
            return back()->withError($message)->withInput();
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            $data = $request;
            $title    = __('User Profile') . ' : ' .  __('Update Password');
        } catch (Exception $exception) {
            $status   = false;
            $message  = $exception->getMessage();
            return back()->withError($message)->withInput();
        }
        return view('content.pages.updatePassword', compact('title'));
    }

    public function passwordUpdate(Request $request)
    {
        try {
            $data = $request->all();
            $email              = $data['email'];
            $currentpassword    = $data['currentPassword'];
            $newpassword        = $data['newPassword'];
            $user = User::where('email', $email)->first();
            if (Hash::check($currentpassword, $user->password)) {
                if (!Hash::check($newpassword, $user->password)) {
                    $user->update([
                        'password' => Hash::make($newpassword),
                        'remember_token' => Str::random(60),
                        'is_password_update' => true
                    ]);
                    $message = Lang::get('messages.password.PasswordUpdate');
                    return response()->json([
                        'status'    => true,
                        'message'   => $message
                    ]);
                } else {
                    $message = Lang::get('messages.password.oldNewPassword');
                    return response()->json([
                        'status'    => false,
                        'message'   => $message
                    ]);
                }
            } else {
                $message = Lang::get('messages.password.CurrentPassword');
                return response()->json([
                    'status'    => false,
                    'message'   => $message
                ]);
            }
        } catch (Exception $exception) {
            $status   = false;
            $message  = $exception->getMessage();
            return back()->withError($message)->withInput();
        }
    }
}
