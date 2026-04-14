<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $orderId)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $order = Order::where('customer_id', auth('customer')->id())->findOrFail($orderId);

        if ($order->status !== 'delivered') {
            return back()->with('error', 'You can only review delivered orders.');
        }

        // Check if already reviewed
        $existing = Review::where('order_id', $order->id)->first();
        if ($existing) {
            return back()->with('error', 'You have already reviewed this delivery.');
        }

        Review::create([
            'customer_id'     => auth('customer')->id(),
            'order_id'        => $order->id,
            'delivery_boy_id' => $order->delivery_boy_id,
            'rating'          => $request->rating,
            'comment'         => $request->comment,
        ]);

        return back()->with('success', 'Thank you for your feedback!');
    }
}
