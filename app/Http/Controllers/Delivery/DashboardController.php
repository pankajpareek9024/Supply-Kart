<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $deliveryBoy = Auth::guard('delivery_boy')->user();
        
        $stats = [
            'pending' => Order::where('delivery_boy_id', $deliveryBoy->id)->whereIn('status', ['assigned', 'picked_up', 'out_for_delivery'])->count(),
            'delivered' => Order::where('delivery_boy_id', $deliveryBoy->id)->where('status', 'delivered')->count(),
            'today_earnings' => Order::where('delivery_boy_id', $deliveryBoy->id)->where('status', 'delivered')->whereDate('delivered_at', today())->count(), // Example placeholder
        ];

        $recentOrders = Order::where('delivery_boy_id', $deliveryBoy->id)
            ->with('customer')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return view('delivery.dashboard', compact('stats', 'recentOrders'));
    }
}
