<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CustomerController extends Controller
{
    // 🔹 Register Page
    public function index()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('home');
        }
        return view('website.auth.register');
    }

    // 🔹 Login Page
    public function loginPage()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('home');
        }
        return view('website.auth.login');
    }

    // 🔹 Register + Send OTP
    public function sendOtp(Request $request)
    {
        $request->validate([
            'mobile'    => 'required|digits:10',
            'shopName'  => 'required|string|max:255',
            'ownerName' => 'required|string|max:255',
            'address'   => 'required|string',
        ]);

        $otp = rand(1000, 9999);

        // Save or update customer record
        Customer::updateOrCreate(
            ['mobile' => $request->mobile],
            [
                'shop_name'     => $request->shopName,
                'owner_name'    => $request->ownerName,
                'gst_number'    => $request->gst,
                'address'       => $request->address,
                'otp'           => $otp,
                'otp_expire_at' => Carbon::now()->addMinutes(5),
            ]
        );

        // Store mobile in session
        session([
            'otp_mobile' => $request->mobile,
            'otp_type'   => 'register',
        ]);

        // ⚠️ For testing — OTP shown via flash; replace with SMS in production
        return redirect()->route('otp')->with('otp_hint', $otp);
    }

    // 🔹 Login + Send OTP
    public function loginOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10',
        ]);

        $user = Customer::where('mobile', $request->mobile)->first();

        if (!$user) {
            return back()->withInput()->with('error', 'Mobile number not registered. Please register first.');
        }

        $otp = rand(1000, 9999);

        $user->update([
            'otp'           => $otp,
            'otp_expire_at' => Carbon::now()->addMinutes(5),
        ]);

        // Store mobile in session
        session([
            'otp_mobile' => $request->mobile,
            'otp_type'   => 'login',
        ]);

        // ⚠️ For testing — OTP shown via flash; replace with SMS in production
        return redirect()->route('otp')->with('otp_hint', $otp);
    }

    // 🔹 OTP Verify (Register + Login both)
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:4',
        ]);

        $mobile = session('otp_mobile');

        if (!$mobile) {
            return redirect()->route('register')->with('error', 'Session expired. Please try again.');
        }

        $user = Customer::where('mobile', $mobile)->first();

        if (!$user) {
            return redirect()->route('register')->with('error', 'User not found. Please register.');
        }

        // OTP match check
        if ((string) $user->otp !== (string) $request->otp) {
            return back()->with('error', 'Invalid OTP. Please try again.');
        }

        // Expiry check
        if (Carbon::now()->gt($user->otp_expire_at)) {
            return back()->with('error', 'OTP has expired. Please request a new one.');
        }

        // Login customer using custom guard
        Auth::guard('customer')->login($user);

        // Clear OTP after successful login
        $user->update([
            'otp'           => null,
            'otp_expire_at' => null,
        ]);

        // Forget OTP session data
        session()->forget(['otp_mobile', 'otp_type']);

        return redirect()->route('home')->with('success', 'Welcome! You are now logged in.');
    }

    // 🔹 Resend OTP
    public function resendOtp(Request $request)
    {
        $mobile = session('otp_mobile');

        if (!$mobile) {
            return redirect()->route('register')->with('error', 'Session expired. Please try again.');
        }

        $user = Customer::where('mobile', $mobile)->first();

        if (!$user) {
            return redirect()->route('register')->with('error', 'User not found.');
        }

        $otp = rand(1000, 9999);

        $user->update([
            'otp'           => $otp,
            'otp_expire_at' => Carbon::now()->addMinutes(5),
        ]);

        return redirect()->route('otp')->with('otp_hint', $otp)->with('success', 'OTP resent successfully!');
    }

    // 🔹 Logout
    public function logout()
    {
        Auth::guard('customer')->logout();
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}