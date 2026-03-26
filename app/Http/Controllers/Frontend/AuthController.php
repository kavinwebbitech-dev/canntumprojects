<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use App\Models\TempUser;
use Mail;
use App\Services\SmsService;
use Carbon\Carbon;

class AuthController extends Controller
{

    public function register()
    {
        return view('frontend.auth.register');
    }


    public function signup(Request $request, SmsService $smsService)
    {
        $validated = $request->validate([
            'name'              => 'required|string|min:3|max:100',
            'phone'             => 'required|digits:10',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|string|min:6|max:50',
            'confirm_password'  => 'required|same:password',
        ]);


        $existingUser = User::where('phone', $validated['phone'])->first();
        if ($existingUser) {
            return back()->with('danger', 'User already exists with this phone number.')->withInput();
        }

        TempUser::where('phone', $validated['phone'])->delete();

        $otp = rand(1000, 9999);
        $prefix = 'WIB';
        date_default_timezone_set('Asia/Kolkata');

        $user = TempUser::create([
            'name'               => $validated['name'],
            'phone'              => $validated['phone'],
            'email'              => $validated['email'],
            'password'           => Hash::make($validated['password']),
            'verification_code'  => $otp,
            'user_unique_id'     => $prefix . str_pad(TempUser::max('id') + 1, 3, '0', STR_PAD_LEFT),
        ]);

        $response = $smsService->sendSms($validated['phone'], "Your OTP is: $otp", 'register_otp');

        if ($response !== true) {
            return back()->with('danger', 'Failed to send OTP. Please try again.')->withInput();
        }

        return view('frontend.auth.registerwithotp', compact('user'))->with('refresh', true);
    }



    public function otpvarification(Request $request)
    {
        $otp = implode('', $request->otp);
        $user_id = $request->user_id;

        $verifyuser = TempUser::where('id', $user_id)
            ->where('verification_code', $otp)
            ->first();

        if ($verifyuser) {
            date_default_timezone_set('Asia/Kolkata');
            $prefix = 'AIOT';

            $user = new User();
            $user->name = $verifyuser->name;
            $user->email = $verifyuser->email;
            $user->phone = $verifyuser->phone;
            $user->password = $verifyuser->password;
            $user->email_verified_at = Carbon::now();
            $user->save();

            $user->user_unique_id = $prefix . str_pad($user->id, 3, '0', STR_PAD_LEFT);
            $user->save();

            $verifyuser->delete();

            Auth::login($user);

            return redirect()->route('user.dashboard')
                ->with('success', 'Registration successful! Welcome to your dashboard.');
        } else {
            $user = TempUser::find($user_id);
            return view('frontend.auth.registerwithotp', compact('user'))
                ->with('danger', 'Invalid OTP. Please try again.');
        }
    }


    public function login()
    {
        return view('frontend.auth.login');
    }

    public function signin(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits_between:10,12',
            'password' => 'required|string|min:6',
        ]);

        $user = User::where('phone', $request->phone)
            ->where('user_type', 'user')
            ->first();

        if (!$user) {
            return back()->with('danger', 'No account found with this mobile number.')->withInput();
        }

        if (!\Hash::check($request->password, $user->password)) {
            return back()->with('danger', 'Incorrect password.')->withInput();
        }

        Auth::login($user);

        return redirect('/')->with('success', 'Logged in successfully..!');
    }


    public function verify_user(Request $request, SmsService $smsService)
    {
        if (filter_var($request->phone_or_email, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $request->phone_or_email)->first();
        } else {
            $user = User::where('phone', $request->phone_or_email)->first();
        }

        if ($user) {
            $otp = rand(1000, 9999);
            $phone   = $user->phone;
            $message = $otp;
            $type = 'forget_password';
            $response = $smsService->sendSms($phone, $message, $type);

            if ($response === true) {
                $user->verification_code = $otp;
                $user->save();
            } else {
                $user->verification_code = null;
                $user->save();
            }

            $userdata = [
                'name' => $user->name,
                'email' => $user->email,
                'otp' => $message,

            ];

            try {
                Mail::send('frontend.auth.email.forget_password', $userdata, function ($message) use ($userdata) {
                    $message->from('preethabtechit252@gmail.com', 'OTP')
                        ->to($userdata['email'])
                        ->subject('Verification code');
                });
            } catch (Exception $e) {
            }
            return view('frontend.auth.get_otp_screen', compact('user'))->with('success', 'OTP send successfully..!
            ');
        } else {
            return Redirect::back()->with('danger', 'User not found!');
        }
    }

    public function userresendOtp($userId, SmsService $smsService)
    {
        $user = user::where('id', $userId)->first();

        $otp = rand(1000, 9999);
        $phone   = $user->phone;
        $message = $otp;
        $type = 'register_otp';
        $response = $smsService->sendSms($phone, $message, $type);

        if ($response === true) {
            $verifyuser = user::where('id', $user->id)->first();
            $verifyuser->verification_code = $otp;
            $verifyuser->save();
        }

        $email   = $user->email;

        $userdata = [
            'name' => $user->name,
            'email' => $email,
            'otp' => $otp,

        ];

        try {
            Mail::send('frontend.auth.email.otp', $userdata, function ($message) use ($userdata) {
                $message->from('preethabtechit252@gmail.com', 'OTP')
                    ->to($userdata['email'])
                    ->subject('Verification code');
            });
        } catch (Exception $e) {
        }

        return response()->json(['success' => 'OTP resent successfully', 'user' => $user]);
    }

    public function user_verify_otp(Request $request)
    {
        $verification_code = implode('', $request->otp);
        // print_r($request->user_id); exit();
        $user = User::where('verification_code', $verification_code)->where('id', $request->user_id)->first();

        if ($user) {
            $user->save();
            Auth::login($user);
            // return view('frontend.my_profile',compact('user'))->with('success', 'Your logged in successfully!!');
            return redirect()->route('user.dashboard')->with('success', 'logged in successfully..!');
        } else {

            return redirect()->route('login.otp')->with('danger', 'Verification code does not match!!');
        }
    }

    public function forgot_password_step2(Request $request, SmsService $smsService)
    {
        if (filter_var($request->phone_or_email, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $request->phone_or_email)->first();
        } else {
            $user = User::where('phone', $request->phone_or_email)->first();
        }

        if ($user) {
            $otp = rand(1000, 9999);
            $phone   = $user->phone;
            $message = $otp;
            $type = 'forget_password';
            $response = $smsService->sendSms($phone, $message, $type);

            if ($response === true) {
                $user->verification_code = $otp;
                $user->save();
            } else {
                $user->verification_code = null;
                $user->save();
            }

            $userdata = [
                'name' => $user->name,
                'email' => $user->email,
                'otp' => $message,

            ];

            Mail::send('frontend.auth.email.forget_password', $userdata, function ($message) use ($userdata) {
                $message->from('preethabtechit252@gmail.com', 'OTP')
                    ->to($userdata['email'])
                    ->subject('Verification code');
            });

            return view('frontend.auth.forgot_password_step2', compact('user'))->with('success', 'OTP send successfully..!');
        } else {
            return Redirect::back()->with('danger', 'User not found!');
        }
    }

    public function verification_code(Request $request)
    {
        // print_r($request->otp); exit();
        $verification_code = implode('', $request->otp);
        $user = User::where('verification_code', $verification_code)->where('id', $request->user_id)->first();

        if ($user) {
            $user->save();
            return view('frontend.auth.forgot_password', compact('user'))->with('success', 'Verify code matched!!');
        } else {

            return redirect()->route('forgot_password_step1')->with('danger', 'Verification code does not match!!');
        }
    }

    public function reset_password(Request $request)
    {
        $user_id = $request->user_id;
        $user = User::where('id', $user_id)->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->email_verified_at = Carbon::now();
            $user->save();
            // return redirect()->route('login')->with('success', 'Password has been updated, you can login now');
            return response()->json(['success' => 'Password has been updated, you can login now', 'redirect' => route('user.login')], 200);
        } else {
            return response()->json(['danger' => 'User not found'], 404);
        }
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('user.login')
            ->with('success', 'logged out successfully..!');
    }
}
