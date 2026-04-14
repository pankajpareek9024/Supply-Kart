<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::guard('customer')->user();
        return view('website.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'gst_number' => 'nullable|string|max:50',
            'address' => 'required|string',
            'lat' => 'nullable|string',
            'lng' => 'nullable|string',
        ]);

        $user = Auth::guard('customer')->user();
        
        $user->update([
            'shop_name' => $request->shop_name,
            'owner_name' => $request->owner_name,
            'gst_number' => $request->gst_number,
            'address' => $request->address,
            'lat' => $request->lat,
            'lng' => $request->lng,
        ]);

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully.');
    }
}
