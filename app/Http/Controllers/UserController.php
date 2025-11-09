<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\AuthController;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('user_type', 'customer')->orderBy('created_at', 'desc')->get();
        return view('admin.user', compact('users'));
    }

    public function user_activation(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($request->status == 'inactive') {
            $user->status = 'active';
            
            $auth = new AuthController();
            $auth->sendActivationMailNotification($user->email, $user->name);
            
        } else {
            $user->status = 'inactive';
        }
        $user->save();
        return redirect()->back()->with('success', 'User status updated successfully.');
    }

    public function profile()
    {
        $user = auth()->user();
        return view('profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'gender' => 'required|in:male,female',
            'phone_number' => 'required|string',
            'home_address' => 'required|string',
            'username' => 'required|string',
            'email' => 'required|email|',
            'password' => 'nullable|string',
            'confirm_password' => 'nullable|same:password',
        ]);

        $user = User::find($request->user_id);

        $user->name = $request->name;
        $user->gender = $request->gender;
        $user->phone_number = $request->phone_number;
        $user->home_address = $request->home_address;
        $user->username = $request->username;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
