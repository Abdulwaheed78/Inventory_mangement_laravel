<?php

namespace App\Http\Controllers;

use App\Http\Controllers\LogController;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderTrans;
use App\Models\Stage;
use App\Models\Product;
use App\Models\Category;
use App\Models\Warehouse;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::with(['customer', 'stage'])->orderBy('id', 'desc')->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        $customer = Customer::pluck('name')->toArray();
        $stage = Stage::all();
        return view('admin.orders.create', compact('customer', 'stage'));
    }

    public function getdetails(Request $request)
    {
        $customer = Customer::where('name', $request->name)->first();

        if ($customer) {
            return response()->json($customer); // Return the customer details as JSON
        } else {
            return response()->json(null);
        }
    }


    public function store(Request $request)
    {
        $id = Customer::where('name', $request->name)
             ->where('email', $request->email)
             ->value('id'); // Fetch only the ID
        if ($id) {
            $customer = Customer::find($id);
            $customer->update([
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

        } else {
            $customer = Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
            $id = $customer->id;

            app(LogController::class)->insert('insert', 'customers', auth()->id(), $id);
        }

        // Store the product data
        $ord = Order::create([
            'customer_id' => $id,
            'total_amount' => 0,
            'order_date' => $request->date,
            'stage_id' => $request->stage,
            'status' => $request->status,
        ]);
        app(abstract: LogController::class)->insert('insert', 'orders', auth()->id(), $ord->id);
        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
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
        $stage = Stage::all();
        $customer = Customer::pluck('name')->toArray();
        $order = Order::with(['customer'])->findOrFail($id);
        $products = OrderTrans::with(['products'])->where('order_id', $id)->get();
        return view('admin.orders.edit', compact('customer', 'order', 'stage', 'products'));
    }

    public function update(Request $request, $id)
    {
        $cid = Customer::where('name', $request->name)
             ->where('email', $request->email)
             ->value('id'); // Fetch only the ID
        if ($cid) {
            $customer = Customer::find($cid);
            $customer->update([
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        } else {
            $customer = Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
            $cid = $customer->id;
            app(abstract: LogController::class)->insert('change Customer', 'orders', auth()->id(), $cid);

        }

        $order = Order::find($id);
        if (!$order) {
            return redirect()->back()->with('error', 'Order not found.');
        }

        // Store the product data
        $order->update([
            'customer_id' => $cid,
            'total_amount' => $request->amount,
            'order_date' => $request->date,
            'stage_id' => $request->stage,
            'status' => $request->status,
        ]);
        app(LogController::class)->insert('update', 'orders', auth()->id(), $id);
        return redirect()->back()->with('success', 'Order Updated successfully.');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        app(LogController::class)->insert('delete', 'orders', auth()->id(), $id);
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }


    public function addproduct($id)
    {
        $order_id = $id;
        $warehouse = Warehouse::all();
        return view('admin.orders.add_product', compact('order_id', 'warehouse'));
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
        $product = Product::where('name', 'like', '%' . $name . '%')->with(['warehouse', 'category'])
            ->first();
        return response()->json(['data' =>  $product]);
    }

    public function productstore(Request $request)
    {

        $total_amount = 0;

        for ($i = 0; $i < count($request->name); $i++) {

            if (!isset($request->name[$i]) || empty($request->name[$i])) {
                continue; // Skip empty entries
            }

            $product = Product::find($request->pid[$i]);
            $cqty = $product->stock_quantity;
            $product->update([
                'stock_quantity' => $cqty - $request->qty[$i]
            ]);

            // to set status passive
            if ($product->stock_quantity <= 0) {
                $product->update([
                    'status' => 'passive'
                ]);
            }

            $trans = OrderTrans::create([
                'order_id' => $request->oid,
                'product_id' => $request->pid[$i],
                'qty' => $request->qty[$i],
                'price' => $request->price[$i]

            ]);
            $total_amount += $request->price[$i] *  $request->qty[$i];
        }

        $order = Order::find($request->oid);
        $prevt = $order->total_amount;
        $order->update([
            'total_amount' => $prevt + $total_amount
        ]);

        return redirect()->back()->with('success', 'Products Added successfully.');
    }

    public function delproduct($id)
    {
        $trans = OrderTrans::find($id);
        if (!$trans) {
            return redirect()->back()->with('error', 'Transaction not found.');
        }

        $product = Product::find($trans->product_id);
        if ($product) {
            $cqty = $product->stock_quantity;
            $product->update([
                'stock_quantity' => $cqty + $trans->qty
            ]);
        }


        $purchase = Order::find($trans->order_id);
        $prevt = $trans->price * $trans->qty;
        $purchase->update([
            'total_amount' => $purchase->total_amount - $prevt
        ]);

        $trans->delete(); // Delete the transaction after removing the product

        return redirect()->back()->with('success', 'Product deleted successfully.');
    }
    public function editproduct($id)
    {

        $product = OrderTrans::with('products')->find($id);

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        return view('admin.orders.edit_product', compact('product'));
    }

    public function updateproduct(Request $request, $id)
    {
        $trans = OrderTrans::find($id);
        $product = Product::find($trans->product_id);

        if (isset($request->npid) && $request->npid != '') { // here new product find and now adding the new details and remove old one
            $qty = $trans->qty + $product->stock_quantity;

            $product->update([ //return old product quantity
                'stock_quantity' => $qty,
            ]);

            $order = Order::find($trans->order_id);
            $final_am = $order->total_amount - ($trans->qty * $trans->price);
            $order->update([
                'total_amount' => $final_am
            ]);

            //end of old thngs now new prod details saving

            $trans->update([
                'product_id' => $request->npid,
                'price' => $request->price,
                'qty' => $request->qty
            ]);

            $final_amn = $order->total_amount + ($request->qty * $request->price);
            $order->update([
                'total_amount' => $final_amn
            ]);
        } else {

            $order = Order::find($trans->order_id);
            $remove_old = $order->total_amount - ($trans->qty * $trans->price);
            $order->update([ //first remove old calculation
                'total_amount' => $remove_old
            ]);

            $add_new = $order->total_amount + ($request->qty * $request->price);
            $order->update([
                'total_amount' => $add_new
            ]);

            $trans->update([
                'product_id' => $request->pid,
                'price' => $request->price,
                'qty' => $request->qty
            ]);
        }

        return redirect()->back()->with('success', 'Product Updated successfully.');
    }

    public function invoice($id)
    {
        $order = Order::with(['customer', 'stage', 'trans'])->findOrFail($id);

        $pdf = PDF::loadView('invoices.invoice', ['order' => $order]);

        return $pdf->stream('invoice_' . $id . '.pdf');
    }
}
