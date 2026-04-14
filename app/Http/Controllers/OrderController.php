<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('customer_id', Auth::guard('customer')->id())->orderBy('created_at', 'desc')->get();
        return view('website.orders.index', compact('orders'));
    }

    public function details($id)
    {
        $order = Order::where('id', $id)->where('customer_id', Auth::guard('customer')->id())->with('items.product')->firstOrFail();
        return view('website.orders.details', compact('order'));
    }

    public function checkout()
    {
        $cartItems = Cart::where('customer_id', Auth::guard('customer')->id())->with('product')->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }
        return view('website.checkout', compact('cartItems'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'payment_method' => 'required|in:cod,online'
        ]);

        $customerId = Auth::guard('customer')->id();
        $cartItems = Cart::where('customer_id', $customerId)->with('product')->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $totalAmount += $item->product->price * $item->quantity;
        }

        $deliveryCharge = $totalAmount < 999 && $totalAmount > 0 ? 90 : 0;
        
        $order = Order::create([
            'customer_id' => $customerId,
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'total_amount' => $totalAmount + $deliveryCharge,
            'delivery_charge' => $deliveryCharge,
            'payment_method' => $request->payment_method,
            'shipping_address' => $request->shipping_address,
            'status' => 'pending',
            'payment_status' => $request->payment_method == 'online' ? 'paid' : 'pending'
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product->id,
                'quantity' => $item->quantity,
                'price' => $item->product->price
            ]);
        }

        // Clear cart
        Cart::where('customer_id', $customerId)->delete();

        return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
    }

    public function downloadInvoice($id)
    {
        $order = Order::where('id', $id)->where('customer_id', Auth::guard('customer')->id())->with('items.product')->firstOrFail();
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('website.orders.invoice', compact('order'));
        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }
}
