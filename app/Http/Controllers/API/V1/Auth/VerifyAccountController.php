<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\API\BaseApiController;
use App\Http\Requests\Auth\VerifyAccountRequest;
use App\Mail\VerifyAccountMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class VerifyAccountController extends BaseApiController
{
    public function sendVerificationEmail()
    {
        $user = Auth::user();

        if ($user->email_verified_at) {
            return $this->sendError('Email already verified', [], 409);
        }

        $user->otp = rand(100000, 999999);
        $user->save();

        Mail::to($user)->send(new VerifyAccountMail($user->otp));

        return $this->sendResponse(null, 'Verification email sent successfully');
    }

    public function verifyAccount(VerifyAccountRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();

        if (!$user || $user->otp != $data['otp']) {
            return $this->sendError('Invalid OTP', [], 422);
        }

        $user->email_verified_at = now();
        $user->save();

        return $this->sendResponse(null, 'Your Email has been verified successfully');
    }
}