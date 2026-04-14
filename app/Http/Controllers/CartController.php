<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('customer_id', Auth::guard('customer')->id())->with('product')->get();
        return view('website.cart.index', compact('cartItems'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        $qty = $request->quantity ?? 1;

        // Stock check
        if ($product->stock <= 0) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'This product is out of stock.'], 422);
            }
            return redirect()->back()->with('error', 'This product is out of stock.');
        }

        $cart = Cart::where('customer_id', Auth::guard('customer')->id())
                    ->where('product_id', $product->id)
                    ->first();

        if ($cart) {
            $cart->update(['quantity' => $cart->quantity + $qty]);
        } else {
            Cart::create([
                'customer_id' => Auth::guard('customer')->id(),
                'product_id' => $product->id,
                'quantity' => $qty
            ]);
        }

        if ($request->wantsJson()) {
            $cartCount = Cart::where('customer_id', Auth::guard('customer')->id())->sum('quantity');
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart!',
                'cart_count' => $cartCount
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::where('id', $id)->where('customer_id', Auth::guard('customer')->id())->firstOrFail();
        
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart->update(['quantity' => $request->quantity]);

        if ($request->wantsJson()) {
            $cartCount = Cart::where('customer_id', Auth::guard('customer')->id())->sum('quantity');
            
            // Calculate total price for cart items again? Wait, we can let frontend reload or we compute it.
            $cartItems = Cart::where('customer_id', Auth::guard('customer')->id())->with('product')->get();
            $totalPrice = 0;
            $totalMrp = 0;
            foreach($cartItems as $item) {
                $totalPrice += $item->product->price * $item->quantity;
                $totalMrp += $item->product->mrp * $item->quantity;
            }

            $deliveryCharge = $totalPrice < 999 && $totalPrice > 0 ? 90 : 0;
            $finalTotal = $totalPrice + $deliveryCharge;

            return response()->json([
                'success' => true,
                'message' => 'Cart updated!',
                'cart_count' => $cartCount,
                'item_total' => $cart->quantity * $cart->product->price,
                'cart_total' => $totalPrice,
                'cart_mrp' => $totalMrp,
                'savings' => $totalMrp - $totalPrice,
                'delivery_charge' => $deliveryCharge,
                'final_total' => $finalTotal
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Cart updated!');
    }

    public function remove($id)
    {
        $cart = Cart::where('id', $id)->where('customer_id', Auth::guard('customer')->id())->firstOrFail();
        $cart->delete();

        return redirect()->route('cart.index')->with('success', 'Product removed from cart!');
    }
}
