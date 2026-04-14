<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    /**
     * List all admin accounts.
     */
    public function index(Request $request)
    {
        $query = Admin::latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $admins = $query->paginate(15)->withQueryString();

        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Show the create admin form.
     */
    public function create()
    {
        return view('admin.admins.create');
    }

    /**
     * Store a new admin account.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:150'],
            'email'                 => ['required', 'email', 'max:255', 'unique:admins,email'],
            'password'              => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'role'                  => ['required', 'string', 'in:super_admin,editor'],
            'avatar'                => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
        ]);

        $data['password'] = Hash::make($data['password']);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('admins', 'public');
        }

        Admin::create($data);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin account created successfully.');
    }

    /**
     * Show the edit form for an admin.
     */
    public function edit(Admin $admin)
    {
        return view('admin.admins.edit', compact('admin'));
    }

    /**
     * Update an existing admin account.
     */
    public function update(Request $request, Admin $admin)
    {
        $rules = [
            'name'   => ['required', 'string', 'max:150'],
            'email'  => ['required', 'email', 'max:255', 'unique:admins,email,' . $admin->id],
            'role'   => ['required', 'string', 'in:super_admin,editor'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
        ];

        // Only validate password if provided
        if ($request->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()];
        }

        $data = $request->validate($rules);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($admin->avatar) {
                \Storage::disk('public')->delete($admin->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('admins', 'public');
        }

        $admin->update($data);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin account updated successfully.');
    }

    /**
     * Delete an admin account (cannot delete yourself).
     */
    public function destroy(Admin $admin)
    {
        // Prevent deleting own account
        if ($admin->id === Auth::guard('admin')->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete your own account.',
            ], 403);
        }

        if ($admin->avatar) {
            \Storage::disk('public')->delete($admin->avatar);
        }

        $admin->delete();

        return response()->json([
            'success' => true,
            'message' => 'Admin account deleted successfully.',
        ]);
    }

    /**
     * Toggle active status of an admin.
     */
    public function toggleStatus(Admin $admin)
    {
        if ($admin->id === Auth::guard('admin')->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot deactivate your own account.',
            ], 403);
        }

        $admin->update(['is_active' => !$admin->is_active]);

        return response()->json([
            'success'   => true,
            'message'   => 'Admin status updated.',
            'is_active' => $admin->is_active,
        ]);
    }
}
