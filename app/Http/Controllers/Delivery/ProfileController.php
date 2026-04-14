<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $deliveryBoy = Auth::guard('delivery_boy')->user();
        return view('delivery.profile.index', compact('deliveryBoy'));
    }

    public function update(Request $request)
    {
        $deliveryBoy = Auth::guard('delivery_boy')->user();

        $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|unique:delivery_boys,phone,' . $deliveryBoy->id,
            'address'  => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $deliveryBoy->name = $request->name;
        $deliveryBoy->phone = $request->phone;
        $deliveryBoy->address = $request->address;

        if ($request->filled('password')) {
            $deliveryBoy->password = Hash::make($request->password);
        }

        $deliveryBoy->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully!'
        ]);
    }
}
