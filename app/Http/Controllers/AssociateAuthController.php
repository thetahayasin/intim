<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AssociateAuthController extends Controller
{
    public function index()
    {
        
        // If user is already authenticated, redirect to dashboard
        if (Auth::guard('associate')->check()) {
            return redirect()->route('ass.dash');
        }

        return view('associate.auth.login');
    }

    //logim
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        
        if (Auth::guard('associate')->attempt($credentials)) {


            return redirect()->route('ass.dash');
        }

        // Authentication failed for employee...
        return redirect()->back()->withErrors(['email' => 'Invalid email or password']);

    }

    //logout
    public function logout(Request $request)
    {
        
        Auth::guard('associate')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('ass.login')->with('message', 'Logged Out');
    }
}
