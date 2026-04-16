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
            if ($item->quantity > $item->product->stock) {
                return redirect()->route('cart.index')->with('error', 'Insufficient stock for product: ' . $item->product->name . '. Only ' . $item->product->stock . ' left.');
            }
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
            $item->product->decrement('stock', $item->quantity);
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

    public function reorder(Request $request, $id)
    {
        $order = Order::where('id', $id)->where('customer_id', Auth::guard('customer')->id())->with('items.product')->firstOrFail();
        $customerId = Auth::guard('customer')->id();
        $addedCount = 0;

        foreach ($order->items as $item) {
            if ($item->product && $item->product->stock > 0 && $item->product->is_active) {
                $cart = Cart::where('customer_id', $customerId)
                            ->where('product_id', $item->product_id)
                            ->first();

                if ($cart) {
                    $cart->update(['quantity' => $cart->quantity + $item->quantity]);
                } else {
                    Cart::create([
                        'customer_id' => $customerId,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity
                    ]);
                }
                $addedCount++;
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $addedCount > 0 ? "{$addedCount} items added to your cart!" : "Products are out of stock.",
                'cart_count' => Cart::where('customer_id', $customerId)->sum('quantity')
            ]);
        }

        if ($addedCount > 0) {
            return redirect()->route('cart.index')->with('success', "{$addedCount} items added to your cart!");
        }

        return redirect()->back()->with('error', 'Products are out of stock or no longer available.');
    }
}
