<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\DeliveryBoy;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('customer', 'deliveryBoy');

        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        // Delivery Boy Daily Summary (today)
        $today = Carbon::today();
        $deliveryBoySummary = DeliveryBoy::where('is_active', true)->with(['orders' => function($q) use ($today) {
            $q->whereDate('delivered_at', $today)->where('status', 'delivered');
        }])->get()->map(function($boy) {
            return [
                'id' => $boy->id,
                'name' => $boy->name,
                'total_delivered' => $boy->orders->count(),
                'total_cash' => $boy->orders->where('payment_method', 'cod')->where('payment_status', 'paid')->sum('total_amount'),
            ];
        })->filter(function($boy) {
            return $boy['total_delivered'] > 0 || $boy['total_cash'] > 0;
        });

        return view('admin.orders.index', compact('orders', 'deliveryBoySummary'));
    }

    public function show(Order $order)
    {
        $order->load('customer', 'items.product', 'deliveryBoy');
        $deliveryBoys = DeliveryBoy::where('is_active', true)->get();

        return view('admin.orders.show', compact('order', 'deliveryBoys'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,processing,packed,assigned,picked_up,out_for_delivery,delivered,cancelled',
        ]);

        if ($data['status'] === 'delivered') {
            $data['payment_status'] = 'paid';
            $data['delivered_at'] = now();
            // Optionally, set delivery_boy_id if not set
            if (!$order->delivery_boy_id && $request->delivery_boy_id) {
                $data['delivery_boy_id'] = $request->delivery_boy_id;
            }
        } elseif ($data['status'] === 'cancelled') {
            if ($order->status !== 'cancelled') {
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }
            }
            if ($order->payment_status === 'paid') {
                $data['payment_status'] = 'refunded';
            } else {
                $data['payment_status'] = 'failed'; // or cancelled/failed depending on DB enum
            }
        }
        $order->update($data);
        return response()->json([
            'success' => true,
            'message' => 'Order status updated to ' . ucfirst(str_replace('_', ' ', $data['status'])) . '.',
        ]);
    }

    public function assignDeliveryBoy(Request $request, Order $order)
    {
        $data = $request->validate([
            'delivery_boy_id' => 'required|exists:delivery_boys,id',
        ]);

        $boy = DeliveryBoy::find($data['delivery_boy_id']);

        $order->update([
            'delivery_boy_id'    => $boy->id,
            'delivery_boy_name'  => $boy->name,
            'delivery_boy_phone' => $boy->phone,
            'status'             => 'assigned',
            'assigned_at'        => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => $boy->name . ' assigned to order #' . $order->order_number . '. Status updated to Assigned.',
        ]);
    }

    public function downloadInvoice(Order $order)
    {
        $order->load('customer', 'items.product');

        $pdf = Pdf::loadView('admin.orders.invoice', compact('order'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('Invoice-' . $order->order_number . '.pdf');
    }
}
