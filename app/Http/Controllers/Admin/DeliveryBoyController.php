<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryBoy;
use Illuminate\Http\Request;

class DeliveryBoyController extends Controller
{
    public function index(Request $request)
    {
        $query = DeliveryBoy::withCount('orders');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $deliveryBoys = $query->latest()->paginate(15)->withQueryString();

        return view('admin.delivery_boys.index', compact('deliveryBoys'));
    }

    public function show(DeliveryBoy $deliveryBoy)
    {
        $today = now()->startOfDay();

        $stats = [
            'today_orders' => $deliveryBoy->orders()->whereDate('created_at', clone $today)->count(),
            'today_collection' => $deliveryBoy->orders()
                ->whereDate('delivered_at', clone $today)
                ->where('payment_method', 'cod')
                ->where('payment_status', 'paid')
                ->sum('total_amount'),
            'total_orders' => $deliveryBoy->orders()->count(),
            'total_collection' => $deliveryBoy->orders()
                ->where('payment_method', 'cod')
                ->where('payment_status', 'paid')
                ->sum('total_amount')
        ];

        $recentOrders = $deliveryBoy->orders()->with('customer')->latest()->take(10)->get();

        return view('admin.delivery_boys.show', compact('deliveryBoy', 'stats', 'recentOrders'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:150',
            'email'     => 'required|email|unique:delivery_boys,email',
            'phone'     => 'required|string|max:15|unique:delivery_boys,phone',
            'password'  => 'required|string|min:8',
            'address'   => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        $data['is_active'] = $request->boolean('is_active', true);

        $boy = DeliveryBoy::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Delivery boy added successfully.',
            'boy'     => $boy,
        ]);
    }

    public function edit(DeliveryBoy $deliveryBoy)
    {
        return response()->json(['success' => true, 'boy' => $deliveryBoy]);
    }

    public function update(Request $request, DeliveryBoy $deliveryBoy)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:150',
            'email'     => 'required|email|unique:delivery_boys,email,' . $deliveryBoy->id,
            'phone'     => 'required|string|max:15|unique:delivery_boys,phone,' . $deliveryBoy->id,
            'password'  => 'nullable|string|min:8',
            'address'   => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $data['is_active'] = $request->boolean('is_active', true);

        $deliveryBoy->update($data);

        return response()->json(['success' => true, 'message' => 'Delivery boy updated successfully.']);
    }

    public function destroy(DeliveryBoy $deliveryBoy)
    {
        $deliveryBoy->delete();

        return response()->json(['success' => true, 'message' => 'Delivery boy deleted successfully.']);
    }

    public function toggleStatus(DeliveryBoy $deliveryBoy)
    {
        $deliveryBoy->update(['is_active' => !$deliveryBoy->is_active]);

        return response()->json([
            'success'   => true,
            'message'   => 'Status updated.',
            'is_active' => $deliveryBoy->is_active,
        ]);
    }
}
