<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MobileLoginController extends Controller
{
    /**
     * Show the mobile login page.
     *
     * @return \Illuminate\View\View
     */
    public function loginform()
    {
        return view('mobile.login');
    }

    /**
     * Handle the mobile login request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            //generate token for mobile app
            $user = Auth::user();
            $token = $user->createToken('MobileAppToken')->plainTextToken;
            // Store the token in the session or return it as a response

            return redirect('/callback?token=' . $token);
        }

        // Authentication failed, redirect back with error
        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'The provided credentials do not match our records.']);
    }
}
