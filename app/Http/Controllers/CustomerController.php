<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function browseProducts()
    {
        $products = Product::all(); // Get all products
        return view('customer.browse', compact('products'));
    }

    public function viewProduct($id)
    {
        $product = Product::findOrFail($id);
        return view('customer.product', compact('product'));
    }

    public function addToCart($productId)
    {
        $product = Product::findOrFail($productId);
        Cart::add($product); // Using a Cart package like `gloudemans/cart`
        return redirect()->route('cart.view');
    }

    public function viewCart()
    {
        $cartItems = Cart::content(); // Get all cart items
        return view('customer.cart', compact('cartItems'));
    }

    public function checkout()
    {
        $cartItems = Cart::content();
        return view('customer.checkout', compact('cartItems'));
    }

    public function placeOrder(Request $request)
    {
        $order = Order::create([
            'user_id' => auth()->user()->id,
            'total_price' => Cart::total(),
            'status' => 'pending',
        ]);

        foreach (Cart::content() as $cartItem) {
            $order->orderItems()->create([
                'product_id' => $cartItem->id,
                'quantity' => $cartItem->qty,
                'price' => $cartItem->price,
            ]);
        }

        Cart::destroy(); // Clear the cart after placing an order
        return redirect()->route('customer.orders');
    }

    public function viewOrderHistory()
    {
        $orders = auth()->user()->orders;
        return view('customer.orders', compact('orders'));
    }
}

