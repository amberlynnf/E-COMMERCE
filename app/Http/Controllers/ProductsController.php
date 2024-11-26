<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use App\Models\Orders;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orderBy = $request->sort_stock ? 'stock' : 'name';
        // appends : menambahkan/membawa request pagination (data-data pagination tidak berubah meskipun ada request)
        $products = Products::where('name', 'LIKE', '%'.$request->cari.'%')->orderBy($orderBy, 'ASC')->simplePaginate(5)->appends($request->all());
        // compact() -> mengirimkan data ($) agar data $nya bisa dipake di blade
        return view('pages.data_product', compact('products')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'type' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric'
        ], [
            'name.required',
            'name.max',
            'type.required',
            'price.required',
            'price.numeric',
            'stock.required',
            'stock.numeric'
        ]);

        Products::create($request->all());


        return redirect()->back()->with('success', 'Successfully Added Product!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Products $products)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $products = Products::find($id);
        return view('product.edit', compact('products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'name' => 'required|max:100',
            'type' => 'required',
            'price' => 'required|numeric'
        ]);

        Products::where('id', $id)->update([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price
        ]);

        return redirect()->route('data_produk.data')->with('success', 'Successfully Updated Product!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function updateStock(Request $request,  $id) {
     
        if(isset($request->stock)==FALSE) {
           $medicineBefore = Products::find($id);
           return redirect()->back()->with([
               'failed' => 'Stock Cant be Empty!', 
               'id' =>  $id, 
               'stock' => $medicineBefore->stock
           ]);
       }

       Products::where('id', $id)->update([
           'stock' => $request->stock
       ]);

       return redirect()->back()->with('success', 'Successfully Changed Stock!');
   }

    public function destroy(string $id)
    {
        Products::where('id', $id)->delete();

        return redirect()->back()->with('success', 'Successfully deleted Product!');
    }
}
