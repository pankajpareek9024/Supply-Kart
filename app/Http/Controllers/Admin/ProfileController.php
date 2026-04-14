<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show the logged-in admin's profile page.
     */
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profile.index', compact('admin'));
    }

    /**
     * Update basic profile info (name, email, avatar).
     */
    public function updateInfo(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $data = $request->validate([
            'name'   => ['required', 'string', 'max:150'],
            'email'  => ['required', 'email', 'max:255', 'unique:admins,email,' . $admin->id],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
        ]);

        if ($request->hasFile('avatar')) {
            if ($admin->avatar) {
                \Storage::disk('public')->delete($admin->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('admins', 'public');
        }

        $admin->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Change the admin's own password.
     */
    public function changePassword(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        // Verify current password
        if (!Hash::check($request->current_password, $admin->password)) {
            return back()
                ->withErrors(['current_password' => 'The current password is incorrect.'])
                ->with('active_tab', 'password');
        }

        $admin->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password changed successfully.');
    }
}
