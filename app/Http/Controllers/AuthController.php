<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            if (auth()->user()->user_type == 'admin') {
                return view('admin.dashboard')->with('success', 'Login successful');
            } else {
                return redirect('/')->with('success', 'Login successful');
            }
        }

        return back()
            ->withErrors(['error' => 'The provided credentials do not match our records.'])
            ->withInput($request->only('email'))
            ->with('form_type', 'login');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'gender' => 'required|in:male,female',
            'phone_number' => 'required|string',
            'home_address' => 'required|string',
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'confirm_password' => 'required|same:password',
        ]);

        User::create([
            'name' => $request->name,
            'gender' => $request->gender,
            'phone_number' => $request->phone_number,
            'home_address' => $request->home_address,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/')->with('success', 'Registration successful. Admin will verify your account.');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function logout()
    {
        auth()->logout();
        return redirect('/')->with('success', 'Logout successful');
    }
}
