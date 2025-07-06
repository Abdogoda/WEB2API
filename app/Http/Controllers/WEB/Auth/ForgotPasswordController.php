<?php

namespace App\Http\Controllers\WEB\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Mail\SendResetLinkMail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{

    public function forgot(ForgotPasswordRequest $request)
    {
        $token = Str::random(60);

        DB::table('password_reset_tokens')->updateOrInsert([
            'email' => $request->email
        ], [
            'token' => Hash::make($token),
            'created_at' => now()
        ]);

        Mail::to($request->email)->send(new SendResetLinkMail($token));

        return back()->with("success", 'We have sent you an email with the reset link');
    }

    public function reset(ResetPasswordRequest $request)
    {
        $token = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$token || !Hash::check($request->token, $token->token)) {
            return back()->with("error", 'Invalid token');
        }

        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->to("/login")->with("success", 'Password reset successfully, you can login now');
    }
}