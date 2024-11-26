<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure user is authenticated
        $this->middleware('seller'); // Ensure the user is a seller
    }

    public function index()
    {
        $products = auth()->user()->products; // Get seller's products
        return view('seller.index', compact('products'));
    }

    public function createProduct()
    {
        return view('seller.create');
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        auth()->user()->products()->create($validated); // Save the product
        return redirect()->route('seller.index');
    }

    public function editProduct($id)
    {
        $product = auth()->user()->products()->findOrFail($id);
        return view('seller.edit', compact('product'));
    }

    public function updateProduct(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'required|string',
        ]);

        $product = auth()->user()->products()->findOrFail($id);
        $product->update($validated);
        return redirect()->route('seller.index');
    }

    public function destroyProduct($id)
    {
        $product = auth()->user()->products()->findOrFail($id);
        $product->delete();
        return redirect()->route('seller.index');
    }

    public function viewOrders()
    {
        $orders = auth()->user()->orders; // Get all orders made for the seller's products
        return view('seller.orders', compact('orders'));
    }

    public function viewPayments()
    {
        $payments = auth()->user()->payments; // Get all payments made for the seller's orders
        return view('seller.payments', compact('payments'));
    }
}

