<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
        } else {
            $user->status = 'inactive';
        }
        $user->save();
        return redirect()->back()->with('success', 'User status updated successfully.');
    }
}
