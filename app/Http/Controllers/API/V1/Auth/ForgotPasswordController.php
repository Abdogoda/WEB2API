<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\API\BaseApiController;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Mail\SendResetLinkMail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends BaseApiController
{
    public function forgot(ForgotPasswordRequest $request)
    {
        $data = $request->validated();
        $token = Str::random(60);

        DB::table('password_reset_tokens')->updateOrInsert([
            'email' => $data['email']
        ], [
            'token' => Hash::make($token),
            'created_at' => now()
        ]);

        Mail::to($data['email'])->send(new SendResetLinkMail($token));

        return $this->sendResponse(null, 'We have sent you an email with the reset link.');
    }

    public function reset(ResetPasswordRequest $request)
    {
        $data = $request->validated();
        $tokenRow = DB::table('password_reset_tokens')->where('email', $data['email'])->first();

        if (!$tokenRow || !Hash::check($data['token'], $tokenRow->token)) {
            return $this->sendError('Invalid token.', [], 422);
        }

        User::where('email', $data['email'])->update([
            'password' => Hash::make($data['password'])
        ]);

        DB::table('password_reset_tokens')->where('email', $data['email'])->delete();

        return $this->sendResponse(null, 'Password reset successfully, you can login now.');
    }
}