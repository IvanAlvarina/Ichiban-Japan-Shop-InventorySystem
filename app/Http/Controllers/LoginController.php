<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegisteredUser;

class LoginController extends Controller
{
    public function index()
    {
        return view('Login/loginView');
    }

    public function register()
    {
        return view('Login/registerView');
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'username' => 'required|string|max:255|unique:registered_users,username',
            'email' => 'required|email|unique:registered_users,email',
            'password' => 'required|min:6',
        ]);

        // Create a new user
        $user = RegisteredUser::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Redirect to login page with success message
        return redirect()->route('login')->with('success', 'Registration successful. Please log in.');
    }

    public function login(Request $request)
    {
        // Validate login credentials
        $credentials = $request->only('username', 'password');

        if (auth()->attempt($credentials)) {
            // Authentication passed
            return redirect()->route('dashboard')->with('success', 'Logged in successfully.');
        }

        // Authentication failed
        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]); 
    }

    public function dashboard()
    {
        // Ensure the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access the dashboard.');
        }

        return view('dashboard');
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
