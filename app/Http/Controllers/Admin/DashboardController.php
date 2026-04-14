<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Summary cards
        $totalOrders    = Order::count();
        $totalCustomers = Customer::count();
        $totalProducts  = Product::count();
        $totalRevenue   = Order::where('payment_status', 'paid')->sum('total_amount');

        // Recent 10 orders
        $recentOrders = Order::with('customer')
            ->latest()
            ->take(10)
            ->get();

        // Daily sales - last 7 days
        $dailySales = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Monthly revenue - last 12 months
        $monthlyRevenue = Order::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Order status counts
        $orderStatusCounts = Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        // Delivery Boy Daily Summary (today)
        $today = Carbon::today();
        $deliveryBoySummary = \App\Models\DeliveryBoy::with(['orders' => function($q) use ($today) {
            $q->whereDate('delivered_at', $today)->where('status', 'delivered');
        }])->get()->map(function($boy) {
            $totalDelivered = $boy->orders->count();
            $totalCash = $boy->orders->where('payment_method', 'cod')->where('payment_status', 'paid')->sum('total_amount');
            return [
                'id' => $boy->id,
                'name' => $boy->name,
                'phone' => $boy->phone,
                'total_delivered' => $totalDelivered,
                'total_cash' => $totalCash,
                'orders' => $boy->orders,
            ];
        });

        return view('admin.dashboard', compact(
            'totalOrders', 'totalCustomers', 'totalProducts', 'totalRevenue',
            'recentOrders', 'dailySales', 'monthlyRevenue', 'orderStatusCounts',
            'deliveryBoySummary'
        ));
    }
}
