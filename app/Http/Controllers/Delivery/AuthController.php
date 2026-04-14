<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('delivery_boy')->check()) {
            return redirect()->route('delivery.dashboard');
        }
        return view('delivery.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::guard('delivery_boy')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('delivery.dashboard'));
        }

        return back()->withInput($request->only('email'))->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showRegister()
    {
        if (Auth::guard('delivery_boy')->check()) {
            return redirect()->route('delivery.dashboard');
        }
        return view('delivery.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:delivery_boys'],
            'phone'    => ['required', 'string', 'max:20', 'unique:delivery_boys'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'address'  => ['nullable', 'string'],
        ]);

        $deliveryBoy = \App\Models\DeliveryBoy::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'password'  => \Illuminate\Support\Facades\Hash::make($request->password),
            'address'   => $request->address,
            'is_active' => true, // Active by default for now
        ]);

        Auth::guard('delivery_boy')->login($deliveryBoy);

        return redirect()->route('delivery.dashboard')->with('success', 'Welcome! Your delivery partner account has been created.');
    }

    public function logout(Request $request)
    {
        Auth::guard('delivery_boy')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('delivery.login');
    }
}
