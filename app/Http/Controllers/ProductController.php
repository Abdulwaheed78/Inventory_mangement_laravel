<?php

namespace App\Http\Controllers;

use App\Http\Controllers\LogController;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Warehouse;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'warehouse'])->where('deleted','no')->orderBy('id', 'desc')->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $category = Category::where('deleted','no')->get();
        $warehouse = Warehouse::where('deleted','no')->get();
        return view('admin.products.create', compact('category', 'warehouse'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50',
            'sku' => 'required|max:50', // SKU should be unique and max 50 characters
            'category' => 'required|exists:categories,id', // Ensure the category exists in the categories table
            'warehouse' => 'required|exists:warehouses,id', // Ensure the warehouse exists in the warehouses table
            'qty' => 'required|integer|min:0', // Ensure stock quantity is an integer and not negative
            'price' => 'required|numeric|min:0', // Price should be a positive number
            'status' => 'required|in:Active,Passive', // Ensure status is either 'Active' or 'Passive'
            'description' => 'nullable|string|max:500', // Description is optional but should be a string with a max length of 500
        ]);

        // Store the product data
        $pro = Product::create([
            'name' => $request->name,
            'sku' => $request->sku,
            'category_id' => $request->category,
            'warehouse_id' => $request->warehouse,
            'stock_quantity' => $request->qty,
            'price' => $request->price,
            'status' => $request->status,
            'description' => $request->description,
        ]);
        app(LogController::class)->insert('insert', 'products', auth()->id(), $pro->id);
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }


    public function show($id)
    {
        $category = Category::where('deleted','no')->get();
        $warehouse = Warehouse::where('deleted','no')->get();
        $product = Product::findOrFail($id);
        return view('admin.products.show', compact('product', 'category', 'warehouse'));
    }

    public function edit($id)
    {
        $category = Category::where('deleted','no')->get();
        $warehouse = Warehouse::where('deleted','no')->get();
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product', 'category', 'warehouse'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:50', // Allow current product's name
            'sku' => 'required|max:50', // Allow current product's SKU
            'category' => 'required|exists:categories,id', // Ensure the category exists in the categories table
            'warehouse' => 'required|exists:warehouses,id', // Ensure the warehouse exists in the warehouses table
            'qty' => 'required|integer|min:0', // Ensure stock quantity is an integer and not negative
            'price' => 'required|numeric|min:0', // Price should be a positive number
            'status' => 'required|in:Active,Passive', // Ensure status is either 'Active' or 'Passive'
            'description' => 'nullable|string|max:500', // Description is optional but should be a string with a max length of 500
        ]);


        $product = Product::findOrFail($id);
        $product->update([
            'name' => $request->name,
            'sku' => $request->sku,
            'category_id' => $request->category,
            'warehouse_id' => $request->warehouse,
            'stock_quantity' => $request->qty,
            'price' => $request->price,
            'status' => $request->status,
            'description' => $request->description,
        ]);
        app(LogController::class)->insert('update', 'products', auth()->id(), $id);
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->update([
            'deleted'=>'yes',
        ]);
        app(LogController::class)->insert('delete', 'products', auth()->id(), $id);
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
