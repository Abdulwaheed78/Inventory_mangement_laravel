<?php

namespace App\Http\Controllers;

use App\Http\Controllers\LogController;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\supplier;
use App\Models\Warehouse;
use App\Models\Category;
use App\Models\PurchaseTrans;
use Barryvdh\DomPDF\Facade\Pdf As PDF;

class PurchaseController extends Controller
{

    public function index()
    {
        $purchases = Purchase::with(['supplier'])->orderBy('id', 'desc')->get();
        return view('admin.purchases.index', compact('purchases'));
    }

    public function create()
    {
        $supplier = Supplier::pluck('name')->toArray();
        return view('admin.purchases.create', compact('supplier'));
    }

    public function store(Request $request)
    {
        $id = Supplier::where('name', $request->name)->value('id');

        // Store the product data
        $pur = Purchase::create([
            'supplier_id' => $id,
            'total_amount' => 0,
            'purchase_date' => $request->date,
            'status' => $request->status,
        ]);
        app(LogController::class)->insert('insert', 'purchases', auth()->id(), $pur->id);
        return redirect()->route('purchases.index')->with('success', 'Purchase created successfully.');
    }


    // public function show($id)
    // {
    //     $category = Category::all();
    //     $warehouse = Warehouse::all();
    //     $product = Product::findOrFail($id);
    //     return view('admin.products.show', compact('product', 'category', 'warehouse'));
    // }

    public function edit($id)
    {
        $supplier = Supplier::pluck('name')->toArray();
        $purchase = Purchase::with(['supplier'])->findOrFail($id);
        $products = PurchaseTrans::with(['products'])->where('purchase_id', $id)->get();
        return view('admin.purchases.edit', compact('supplier', 'purchase', 'products'));
    }

    public function update(Request $request, $id)
    {
        $supplier_id = Supplier::where('name', $request->name)->value('id');
        $order = Purchase::find($id);
        if (!$order) {
            return redirect()->back()->with('error', 'Purchase not found.');
        }

        // Store the product data
        $order->update([
            'supplier_id' => $supplier_id,
            'total_amount' => $request->amount,
            'order_date' => $request->date,
            'status' => $request->status,
        ]);
        app(LogController::class)->insert('update', 'purchases', auth()->id(), $id);
        return redirect()->route('purchases.index')->with('success', 'Purchase Updated successfully.');
    }

    public function destroy($id)
    {
        $order = Purchase::findOrFail($id);
        $order->delete();
        app(LogController::class)->insert('delete', 'purchases', auth()->id(), $id);
        return redirect()->route('purchases.index')->with('success', 'Order deleted successfully.');
    }

    public function addproduct($id)
    {
        $order_id = $id;
        $products = Product::with(['category', 'warehouse'])->get();
        $warehouse = Warehouse::all();
        $category = Category::all();
        return view('admin.purchases.add_product', compact('order_id', 'products', 'warehouse', 'category'));
    }

    public function search(Request $request)
    {
        $name = $request->name;
        $request->validate([
            'name' => 'required|string'
        ]);

        $products = Product::where('name', 'like', '%' . $name . '%')->where('status', 'active')->pluck('name');

        return response()->json([
            'status' => true,
            'data' => $products
        ]);
    }

    public function loadother(Request $request)
    {
        $name = $request->name;
        $request->validate([
            'name' => 'required|string'
        ]);

        $sku = Product::where('name', 'like', '%' . $name . '%')
            ->select('sku')
            ->limit(1)
            ->value('sku'); // Retrieve only the SKU value

        return response()->json(['data' => $sku]);
    }

    public function productstore(Request $request)
    {
        $total_amount = 0;
        for ($i = 0; $i < count($request->name); $i++) {
            if (!isset($request->name[$i]) || empty($request->name[$i])) {
                continue; // Skip empty entries
            }

            $product = Product::create([
                'name' => $request->name[$i],
                'sku' => $request->sku[$i],
                'category_id' => $request->category[$i],
                'price' => $request->s_price[$i],
                'stock_quantity' => $request->qty[$i],
                'warehouse_id' => $request->warehouse[$i],
                'description' => $request->description[$i] ?? null, // Handle optional field
                'status' => $request->status[$i]
            ]);

            if ($product) { // Ensure product is created
                PurchaseTrans::create([
                    'purchase_id' => $request->pid,
                    'product_id' => $product->id,
                    'qty' => $request->qty[$i],
                    'price' => $request->b_price[$i] // Corrected variable name
                ]);
            }

            $total_amount += $request->b_price[$i] *  $request->qty[$i];
        }

        $purchase = Purchase::find($request->pid);
        $prevt = $purchase->total_amount;
        $purchase->update([
            'total_amount' => $prevt + $total_amount
        ]);

        return redirect()->back()->with('success', 'Products Added successfully.');
    }

    public function delproduct($id)
    {
        $trans = PurchaseTrans::find($id);
        if (!$trans) {
            return redirect()->back()->with('error', 'Transaction not found.');
        }

        $product = Product::find($trans->product_id);
        if ($product) {
            $product->delete();
        }


        $purchase = Purchase::find($trans->purchase_id);
        $prevt = $trans->price * $trans->qty;
        $purchase->update([
            'total_amount' => $purchase->total_amount - $prevt
        ]);

        $trans->delete(); // Delete the transaction after removing the product

        return redirect()->back()->with('success', 'Product deleted successfully.');
    }
    public function editproduct($id)
    {
        $category = Category::all();
        $warehouse = Warehouse::all();
        $product = PurchaseTrans::with('products')->find($id);

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        return view('admin.purchases.edit_product', compact('product', 'category', 'warehouse'));
    }

    public function updateproduct(Request $request, $id)
    {
        $trans = PurchaseTrans::find($id);
        $product = Product::find($trans->product_id);

        $product->update([
            'name' => $request->name,
            'sku' => $request->sku,
            'category_id' => $request->category,
            'price' => $request->s_price,
            'stock_quantity' => $request->qty,
            'warehouse_id' => $request->warehouse,
            'description' => $request->description ?? null, // Handle optional field
            'status' => $request->status
        ]);

        $prevT = $trans->qty * $trans->price;
        $newT = $request->qty * $request->b_price;

        $purchase = Purchase::find($trans->purchase_id);
        $finalT = $purchase->total_amount - $prevT + $newT;
        $purchase->update([
            'total_amount' => $finalT
        ]);

        $trans->update([
            'qty' => $request->qty,
            'price' => $request->b_price
        ]);

        return redirect()->back()->with('success', 'Product Updated successfully.');
    }

    public function invoice($id)
    {
        $order = Purchase::with(['supplier', 'trans'])->findOrFail($id);

        $pdf = PDF::loadView('invoices.invoice2', ['order' => $order]);

        return $pdf->stream('invoice_' . $id . '.pdf');
    }
}
