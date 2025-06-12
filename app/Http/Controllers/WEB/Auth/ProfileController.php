<?php

namespace App\Http\Controllers\WEB\Auth;

use App\Enums\PermissionsEnum;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
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

    public function update(Request $request)
    {
        $user_id = Auth::id();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email,' . $user_id,
            'phone' => 'nullable|string|unique:users,phone,' . $user_id,
            'address' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:255',
        ]);

        if ($request->filled('email') && $request->email != Auth::user()->email) {
            $data['email_verified_at'] = null;
        }

        User::find($user_id)->update($data);

        return back()->with('success', 'Profile updated successfully');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8|confirmed'
        ]);

        $user = User::find(Auth::id());
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password incorrect!');
        }

        $user->update(['password' => Hash::make($request->new_password)]);
        return back()->with('success', 'Your password changed successfully!');
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required|string'
        ]);

        $user = User::find(Auth::id());
        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Password incorrect!');
        }

        $user->delete();
        return redirect()->route('home')->with('success', 'Your account has been deleted successfully!');
    }
}