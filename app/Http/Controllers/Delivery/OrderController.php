<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('delivery_boy_id', auth('delivery_boy')->id())
            ->whereIn('status', ['assigned', 'picked_up', 'out_for_delivery'])
            ->with('customer')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('delivery.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::where('delivery_boy_id', auth('delivery_boy')->id())
            ->with(['customer', 'items.product', 'statusHistory'])
            ->findOrFail($id);

        return view('delivery.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::where('delivery_boy_id', auth('delivery_boy')->id())->findOrFail($id);
        $status = $request->status;
        $validStatuses = ['picked_up', 'out_for_delivery', 'delivered', 'cancelled'];
        
        if (!in_array($status, $validStatuses)) {
            return response()->json(['success' => false, 'message' => 'Invalid status update.']);
        }

        // Security: Ensure order is assigned to this delivery boy
        if ($order->delivery_boy_id !== auth('delivery_boy')->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        $order->status = $status;
        
        if ($status === 'picked_up') {
            $order->picked_up_at = now();
        } elseif ($status === 'delivered') {
            $order->delivered_at = now();
            
            // Handle Payment Status
            $order->payment_status = 'paid'; // Marking as paid on delivery confirmation
            
            if ($order->payment_method === 'cod') {
                $order->cash_collected = true;
            }
        } elseif ($status === 'cancelled') {
            if ($order->status !== 'cancelled') {
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }
            }
            if ($order->payment_status === 'paid') {
                $order->payment_status = 'refunded';
            } else {
                $order->payment_status = 'failed';
            }
        }

        $order->save();

        // Log history
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status'   => $status,
            'comment'  => $request->comment ?? "Order marked as " . str_replace('_', ' ', $status) . " by " . auth('delivery_boy')->user()->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order ' . str_replace('_', ' ', $status) . ' successfully!',
            'new_status' => $status,
            'redirect' => $status === 'delivered' ? route('delivery.orders.completed') : null
        ]);
    }

    public function completed()
    {
        $orders = Order::where('delivery_boy_id', auth('delivery_boy')->id())
            ->where('status', 'delivered')
            ->with('customer')
            ->orderBy('delivered_at', 'desc')
            ->paginate(15);

        return view('delivery.orders.completed', compact('orders'));
    }
}
