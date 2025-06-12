<?php

namespace App\Http\Controllers\WEB\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerifyAccountMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class VerifyAccountController extends Controller
{

    public function sendVerificationEmail()
    {
        $user = Auth::user();

        if ($user->email_verified_at) {
            return back()->with('warning', 'Email already verified');
        }

        $user->otp = rand(100000, 999999);
        $user->save();

        Mail::to($user)->send(new VerifyAccountMail($user->otp));

        return redirect()->route('verification.verify')->with('success', 'Verification email sent successfully');
    }

    public function verifyAccount(Request $request)
    {

        $request->validate([
            'otp' => 'required|numeric|digits:6'
        ]);

        $user = Auth::user();

        if (!$user || $user->otp != $request->otp) {
            return back()->with('error', 'Invalid OTP');
        }

        $user->email_verified_at = now();
        $user->save();

        return redirect()->route("profile")->with('success', 'Your Email has been verified successfully');
    }
}