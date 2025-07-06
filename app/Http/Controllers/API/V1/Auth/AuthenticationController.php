<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\API\BaseApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends BaseApiController
{

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);
        $token = $user->createToken('api_token')->plainTextToken;

        return $this->sendResponse(
            [
                'user' => $user,
                'token' => $token
            ],
            'Thank you for creating an account, Enjoy your stay',
            201
        );
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials, $request->filled('remember'))) {
            return $this->sendError('Invalida Credentials', [], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('api_token')->plainTextToken;
        return $this->sendResponse(
            [
                'user' => $user,
                'token' => $token
            ],
            'Login Successfully'
        );
    }

    public function logout(Request $request)
    {
        Auth::user()->currentAccessToken()->delete();

        return $this->sendResponse(message: 'Logged out successfully');
    }

    public function logoutOtherDevices(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);
        $user = $request->user();
        if (!Hash::check($request->password, (string) $user->getAuthPassword())) {
            return $this->sendError('Invalid password', [], 401);
        }
        $user->tokens()->where('id', '!=', $user->currentAccessToken()->id)->delete();
        return $this->sendResponse(message: 'Logged out from other devices');
    }
}