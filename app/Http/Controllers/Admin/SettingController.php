<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name'        => 'required|string|max:100',
            'contact_email'    => 'required|email',
            'contact_phone'    => 'required|string|max:20',
            'contact_address'  => 'required|string',
            'delivery_charge'  => 'required|numeric|min:0',
            'free_delivery_min'=> 'required|numeric|min:0',
            'logo'             => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'favicon'          => 'nullable|image|mimes:png,ico,jpg|max:512',
        ]);

        $keys = ['site_name', 'contact_email', 'contact_phone', 'contact_address', 'delivery_charge', 'free_delivery_min'];

        foreach ($keys as $key) {
            Setting::set($key, $request->input($key));
        }

        if ($request->hasFile('logo')) {
            $oldLogo = Setting::get('logo');
            if ($oldLogo) Storage::disk('public')->delete($oldLogo);
            Setting::set('logo', $request->file('logo')->store('settings', 'public'));
        }

        if ($request->hasFile('favicon')) {
            $oldFav = Setting::get('favicon');
            if ($oldFav) Storage::disk('public')->delete($oldFav);
            Setting::set('favicon', $request->file('favicon')->store('settings', 'public'));
        }

        return back()->with('success', 'Settings saved successfully.');
    }
}
