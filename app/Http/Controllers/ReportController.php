<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Log;
use App\Models\Payment;
use App\Models\Stage;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Order;
use League\Csv\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    public function category_export()
    {
        $categories = Category::all(['id', 'name', 'description', 'created_at']);
        $csv = Writer::createFromString('');
        $csv->insertOne(['Srno', 'id', 'Name', 'Description', 'Created At']);
        foreach ($categories as $index => $category) {
            $csv->insertOne([
                $index + 1,
                $category->id,
                $category->name,
                $category->description,
                $category->created_at,
            ]);
        }

        return Response::make($csv->toString(), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="categories.csv"',
        ]);
    }
    public function warehouse_export()
    {
        $categories = Warehouse::all(['id', 'name', 'location', 'created_at']);
        $csv = Writer::createFromString('');
        $csv->insertOne(['Srno', 'id', 'Name', 'Location', 'Created At']);
        foreach ($categories as $index => $category) {
            $csv->insertOne([
                $index + 1,
                $category->id,
                $category->name,
                $category->location,
                $category->created_at,
            ]);
        }

        return Response::make($csv->toString(), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="warehouses.csv"',
        ]);
    }
    public function stage_export()
    {
        $categories = Stage::all(['id', 'name', 'created_at']);
        $csv = Writer::createFromString('');
        $csv->insertOne(['Srno', 'id', 'Name', 'Created At']);
        foreach ($categories as $index => $category) {
            $csv->insertOne([
                $index + 1,
                $category->id,
                $category->name,
                $category->created_at,
            ]);
        }

        return Response::make($csv->toString(), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="stages.csv"',
        ]);
    }
    public function product_export()
    {
        $products = Product::with(['category', 'warehouse'])->get(); // Use get() instead of all()

        $csv = Writer::createFromString('');
        $csv->insertOne(['Srno', 'ID', 'Name', 'SKU', 'Price', 'Description', 'Category', 'Stock Quantity', 'Warehouse', 'Status', 'Created At']);

        foreach ($products as $index => $product) {
            $csv->insertOne([
                $index + 1,
                $product->id,
                $product->name,
                $product->sku,
                $product->price,
                $product->description,
                optional($product->category)->name, // Avoid errors if category is null
                $product->stock_quantity,
                optional($product->warehouse)->name, // Avoid errors if warehouse is null
                $product->status,
                $product->created_at,
            ]);
        }

        return Response::make($csv->toString(), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="products.csv"',
        ]);
    }

    public function purchase_export()
    {
        $categories = Purchase::with('supplier')->get();
        $csv = Writer::createFromString('');
        $csv->insertOne(['Srno', 'id', 'Supplier', 'date', 'Payment Status', 'Total Amount', 'Created At']);
        foreach ($categories as $index => $category) {
            $csv->insertOne([
                $index + 1,
                $category->id,
                $category->supplier->name . "\n" .
                    $category->supplier->email . "\n" .
                    $category->supplier->phone,
                $category->purchase_date,
                $category->status,
                $category->total_amount,
                $category->created_at,
            ]);
        }

        return Response::make($csv->toString(), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="purchases.csv"',
        ]);
    }
    public function customer_export()
    {
        $categories = Customer::all();
        $csv = Writer::createFromString('');
        $csv->insertOne(['Srno', 'id', 'Name', 'Email', 'Phone', 'Address', 'Created At']);
        foreach ($categories as $index => $category) {
            $csv->insertOne([
                $index + 1,
                $category->id,
                $category->name,
                $category->email,
                $category->phone,
                $category->address,
                $category->created_at,
            ]);
        }

        return Response::make($csv->toString(), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="customers.csv"',
        ]);
    }
    public function payment_export()
    {
        $categories = Payment::with(['pmode', 'order'])->get();
        $csv = Writer::createFromString('');
        $csv->insertOne(['Srno', 'id', 'Order Id', 'Payment Mode', 'Amount', 'Created At']);
        foreach ($categories as $index => $category) {
            $csv->insertOne([
                $index + 1,
                $category->id,
                "Order Id: #" . $category->order->id . "\n" .
                    $category->order->customer->name . "\n" .
                    $category->order->customer->email . "\n" .
                    $category->order->customer->phone . "\n" . "Order Amount: " .
                    $category->order->total_amount,
                $category->pmode->name,
                $category->amount,
                $category->created_at,
            ]);
        }

        return Response::make($csv->toString(), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="payments.csv"',
        ]);
    }
    public function logs_export()
    {
        $categories = Log::with('user')->get();
        $csv = Writer::createFromString('');
        $csv->insertOne(['Srno', 'id', 'Type', 'Table', 'Action By User', 'Record id', 'Created At']);
        foreach ($categories as $index => $category) {
            $csv->insertOne([
                $index + 1,
                $category->id,
                $category->type,
                $category->table,
                $category->user->name,
                $category->rid,
                $category->created_at,
            ]);
        }

        return Response::make($csv->toString(), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="backend_logs.csv"',
        ]);
    }
    public function order_export()
    {
        $categories = Order::with(['customer', 'stage'])->get();
        $csv = Writer::createFromString('');
        $csv->insertOne(['Srno', 'id', 'Customer Info', 'Order date','Order Stage', 'Payment Status', 'Order Amount', 'Created At']);
        foreach ($categories as $index => $category) {
            $csv->insertOne([
                $index + 1,
                $category->id,
                $category->customer->name . "\n" .
                    $category->customer->email . "\n" .
                    $category->customer->phone,
                $category->order_date,
                $category->stage->name,
                $category->status,
                $category->total_amount,
                $category->created_at,
            ]);
        }

        return Response::make($csv->toString(), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="purchases.csv"',
        ]);
    }
    public function supplier_export()
    {
        $categories = Supplier::all();
        $csv = Writer::createFromString('');
        $csv->insertOne(['Srno', 'id', 'Name', 'Email', 'Phone', 'Address', 'Created At']);
        foreach ($categories as $index => $category) {
            $csv->insertOne([
                $index + 1,
                $category->id,
                $category->name,
                $category->email,
                $category->phone,
                $category->address,
                $category->created_at,
            ]);
        }

        return Response::make($csv->toString(), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="suppliers.csv"',
        ]);
    }
}
