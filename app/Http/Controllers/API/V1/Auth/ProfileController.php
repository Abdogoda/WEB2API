<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\API\BaseApiController;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\DeleteAccountRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends BaseApiController
{
    public function index()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->orderByDesc('created_at')->get();
        return $this->sendResponse([
            'user' => $user,
            'orders' => $orders
        ], 'Profile data fetched successfully.');
    }

    public function update(UpdateProfileRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();
        if (isset($data['email']) && $data['email'] != $user->email) {
            $data['email_verified_at'] = null;
        }
        $user->update($data);
        return $this->sendResponse($user, 'Profile updated successfully.');
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();
        if (!$user || !Hash::check($data['current_password'], (string) $user->password)) {
            return $this->sendError('Current password incorrect!', [], 422);
        }
        $user->update(['password' => Hash::make($data['new_password'])]);
        return $this->sendResponse(null, 'Your password changed successfully!');
    }

    public function deleteAccount(DeleteAccountRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();
        if (!$user || !Hash::check($data['password'], (string) $user->password)) {
            return $this->sendError('Password incorrect!', [], 422);
        }
        $user->delete();
        return $this->sendResponse(null, 'Your account has been deleted successfully!');
    }
}