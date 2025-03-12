<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentModeController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Models\Category;
use App\Models\purchase;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return Redirect()->route('login');
});

// Public Routes (Login)
Route::post('forgotpassword', [AdminController::class, 'forgotpassword'])->name('forgotpassword');
Route::post('verify', [AdminController::class, 'verify'])->name('verify');
Route::get('forgotpassword', function () {
    return view('admin.verify');
});
Route::get('/login', [AdminController::class, 'login'])->name('login')->middleware('guest');
Route::post('/login', [AdminController::class, 'loginuser'])->name('loginuser');

// Protected Routes (Only for Authenticated Users)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

    // category route
    Route::resource('categories', CategoryController::class);
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.delete');

    //Wahehouse Routes
    Route::resource('warehouses', WarehouseController::class);
    Route::delete('warehouses/{warehoouse}', [WarehouseController::class, 'destroy'])->name('warehouses.delete');

    //Products Route
    Route::resource('products', ProductController::class);
    Route::delete('products/{products}', [ProductController::class, 'destroy'])->name('products.delete');


    //Customers Route
    Route::resource('customers', CustomerController::class);
    Route::delete('customers/{customers}', [CustomerController::class, 'destroy'])->name('customers.delete');
    Route::get('/customers/search', [CustomerController::class, 'search'])->name('customers.search');

    //Suppliers Route
    Route::resource('suppliers', SupplierController::class);
    Route::delete('suppliers/{suppliers}', [SupplierController::class, 'destroy'])->name('suppliers.delete');


    //Stages Route
    Route::resource('stages', StageController::class);
    Route::delete('stages/{stages}', [StageController::class, 'destroy'])->name('stages.delete');

    //Orders Route
    Route::resource('orders', OrderController::class);
    Route::delete('orders/{orders}', [OrderController::class, 'destroy'])->name('orders.delete');
    Route::get('orders/{orders}/product-add', [OrderController::class, 'addproduct'])->name('orders.add_product');
    Route::post('orders/search', [OrderController::class, 'search'])->name('orders.search');
    Route::post('orders/loadother', [OrderController::class, 'loadother'])->name('orders.loadother');
    Route::post('orders/productstore', [OrderController::class, 'productstore'])->name('orders.productstore');
    Route::get('orders/delete/{id}', [OrderController::class, 'delproduct'])->name('orders.del_product');
    Route::get('orders/edit/{id}', [OrderController::class, 'editproduct'])->name('orders.edit_product');
    Route::post('orders/update/{id}', [OrderController::class, 'updateproduct'])->name('orders.update_product');
    Route::post('orders/getdetails', [OrderController::class, 'getdetails'])->name('getdetails');

    //Purchase Route
    Route::resource('purchases', PurchaseController::class);
    Route::delete('purchases/{purchases}', [PurchaseController::class, 'destroy'])->name('purchases.delete');
    Route::get('purchases/{purchases}/product-add', [PurchaseController::class, 'addproduct'])->name('purchases.add_product');
    Route::post('purchases/search', [PurchaseController::class, 'search'])->name('purchases.search');
    Route::post('purchases/loadother', [PurchaseController::class, 'loadother'])->name('purchases.loadother');
    Route::post('purchases/productstore', [PurchaseController::class, 'productstore'])->name('purchases.productstore');
    Route::get('purchases/delete/{id}', [PurchaseController::class, 'delproduct'])->name('purchases.del_product');
    Route::get('purchases/edit/{id}', [PurchaseController::class, 'editproduct'])->name('purchases.edit_product');
    Route::post('purchases/update/{id}', [PurchaseController::class, 'updateproduct'])->name('purchases.update_product');
    Route::post('purchases/getdetails', [PurchaseController::class, 'getdetail_sup'])->name('getdetail_sup');

    // route for the index of logs
    Route::get('logs/', [LogController::class, 'index'])->name('logs.index');

    // route for the payment modes
    Route::resource('modes', PaymentModeController::class);
    Route::delete('modes/{modes}', [PaymentModeController::class, 'destroy'])->name('modes.delete');

    // route for the payment
    Route::resource('payments', PaymentController::class);
    Route::delete('payments/{payments}', [PaymentController::class, 'destroy'])->name('payments.delete');

    //for admin profile update
    Route::get('profile', [AdminController::class, 'profile'])->name('profile');
    Route::post('profile_update/{id}', [AdminController::class, 'update_profile'])->name('update_profile');
    Route::get('changepassword', [AdminController::class, 'changepassword'])->name('changepassword');
    Route::post('password_update/{id}', [AdminController::class, 'update_password'])->name('update_password');


    //routes for current stocks
    route::get('stocks', [CategoryController::class, 'stock'])->name('stocks');
    route::post('stocks/detail', [CategoryController::class, 'stock_detail'])->name('stock_detail');
    Route::get('stocks/export', [CategoryController::class, 'export'])->name('export_stock');


    //reports
    Route::get('dashboard/categories/export', [ReportController::class, 'category_export'])->name('export_category');
    Route::get('dashboard/warehouses/export', [ReportController::class, 'warehouse_export'])->name('export_warehouse');
    Route::get('dashboard/stages/export', [ReportController::class, 'stage_export'])->name('export_stage');
    Route::get('dashboard/products/export', [ReportController::class, 'product_export'])->name('export_product');
    Route::get('dashboard/purchases/export', [ReportController::class, 'purchase_export'])->name('export_purchase');
    Route::get('dashboard/customers/export', [ReportController::class, 'customer_export'])->name('export_customer');
    Route::get('dashboard/payments/export', [ReportController::class, 'payment_export'])->name('export_payment');
    Route::get('dashboard/logs/export', [ReportController::class, 'logs_export'])->name('export_logs');
    Route::get('dashboard/orders/export', [ReportController::class, 'order_export'])->name('export_order');
    Route::get('dashboard/suppliers/export', [ReportController::class, 'supplier_export'])->name('export_supplier');

    //for getting payments in cards
    Route::post('dashboard/payments', [AdminController::class, 'getAmounts'])->name('get_payments');


    //invoices order and purchase
    Route::get('orders/invoice/{id}', [OrderController::class, 'invoice'])->name('order_invoice');
    Route::get('purchases/invoice/{id}', [PurchaseController::class, 'invoice'])->name('purchase_invoice');
});
