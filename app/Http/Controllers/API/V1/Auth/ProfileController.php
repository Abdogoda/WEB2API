<?php

namespace App\Http\Controllers\WEB\Auth;

use App\Enums\PermissionsEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\DeleteAccountRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    public function index()
    {
        if (Auth::user()->hasPermissionTo(PermissionsEnum::VIEW_DASHBOARD->value)) {
            return view('admin.profile');
        }

        $orders = Order::where('user_id', auth()->id())->orderByDesc('created_at')->get();
        return view('user.profile', compact('orders'));
    }

    public function update(UpdateProfileRequest $request)
    {
        if ($request->filled('email') && $request->email != Auth::user()->email) {
            $data['email_verified_at'] = null;
        }

        Auth::user()->update($data);

        return back()->with('success', 'Profile updated successfully');
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = User::find(Auth::id());
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password incorrect!');
        }

        $user->update(['password' => Hash::make($request->new_password)]);
        return back()->with('success', 'Your password changed successfully!');
    }

    public function deleteAccount(DeleteAccountRequest $request)
    {
        $user = User::find(Auth::id());
        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Password incorrect!');
        }

        $user->delete();
        return redirect()->route('home')->with('success', 'Your account has been deleted successfully!');
    }
}