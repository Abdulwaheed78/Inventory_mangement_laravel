<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\LogController;
use App\Models\Product;
use App\Models\Stage;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\Customer;
use App\Models\Category;
use App\Models\Order;
use App\Models\Purchase;
use App\Models\Log;
use App\Models\Payment;
use App\Models\PaymentMode;

class AdminController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard'); // Redirect logged-in users
        }
        return view('admin.login');
    }

    public function loginuser(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|max:20'
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            app(LogController::class)->insert('login', 'users', auth()->id(), rid: auth()->id());
            return redirect()->route('dashboard')->with('success', 'Login successful');
        }
    }

    public function dashboard()
    {
        $customers = Customer::where('deleted', 'no')->orderBy('id', 'desc')->limit(5)->get();
        $categories = Category::where('deleted', 'no')->orderBy('id', 'desc')->limit(5)->get();
        $warehouses = Warehouse::where('deleted', 'no')->orderBy('id', 'desc')->limit(5)->get();
        $stages = Stage::where('deleted', 'no')->orderBy('id', 'desc')->limit(5)->get();
        $products = Product::where('deleted', 'no')->orderBy('id', 'desc')->limit(5)->get();
        $purchases = Purchase::with(['supplier'])->orderBy('id', 'desc')->limit(5)->get();
        $suppliers = Supplier::where('deleted', 'no')->orderBy('id', 'desc')->limit(value: 5)->get();
        $orders = Order::with(['customer'])->orderBy('id', 'desc')->limit(5)->get();
        $logs = Log::with(['user'])->orderBy('id', 'desc')->limit(value: 10)->get();
        $payments = Payment::with(['order', 'pmode'])->orderBy('id', 'desc')->get();
        $suppliers = Supplier::where('deleted', 'no')->orderBy('id', 'desc')->limit(5)->get();
        $pmodes = PaymentMode::where('deleted', 'no')->orderBy('id', 'desc')->limit(5)->get();

        // Total counts for dashboard
        $totalCustomers = Customer::where('deleted','no')->count();
        $totalCategories = Category::where('deleted','no')->count();
        $totalWarehouses = Warehouse::where('deleted','no')->count();
        $totalStages = Stage::where('deleted','no')->count();
        $totalProducts = Product::where('deleted','no')->count();
        $totalSuppliers = Supplier::where('deleted','no')->count();
        $totalOrders = Order::count();
        $totalPurchaseOrders = Purchase::count();
        $totalLogs = Log::count();
        $totalPayments = Payment::sum('amount');
        $totalSuppliers = Supplier::where('deleted','no')->count();
        $totalModes = PaymentMode::where('deleted','no')->count();

        return view('admin.index', compact(
            'customers',
            'categories',
            'warehouses',
            'stages',
            'products',
            'suppliers',
            'totalCustomers',
            'totalCategories',
            'totalWarehouses',
            'totalStages',
            'totalProducts',
            'totalSuppliers',
            'totalOrders',
            'totalPurchaseOrders',
            'purchases',
            'orders',
            'totalLogs',
            'logs',
            'payments',
            'totalPayments',
            'totalSuppliers',
            'suppliers',
            'pmodes',
            'totalModes'

        ));
    }

    public function getAmounts(Request $request)
    {
        $filter = $request->filter;

        // Apply date filters based on selection
        $dateRange = $this->getDateRange($filter);

        // Convert to correct date format
        $startDate = Carbon::parse($dateRange['start'])->format('Y-m-d H:i:s');
        $endDate = Carbon::parse($dateRange['end'])->format('Y-m-d H:i:s');

        // Sum of Order `total_amount`
        $totalOrderAmount = Order::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');

        // Sum of Purchase `total_amount`
        $totalPurchaseAmount = Purchase::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');

        // Total Orders - Total Purchases
        $netAmount = $totalOrderAmount - $totalPurchaseAmount;

        // Total Products Stock Value (stock_quantity * price)
        $totalStockValue = Product::select(DB::raw('SUM(stock_quantity * price) as total_stock_value'))
            ->value('total_stock_value');

        return response()->json([
            'total_order_amount' => $totalOrderAmount,
            'total_purchase_amount' => $totalPurchaseAmount,
            'net_amount' => $netAmount,
            'total_stock_value' => $totalStockValue
        ]);
    }

    /**
     * Get start and end date based on filter type.
     */
    private function getDateRange($filter)
    {
        if ($filter == 'today') {
            $start = now()->startOfDay()->format('Y-m-d H:i:s');
            $end = now()->endOfDay()->format('Y-m-d H:i:s');
        } elseif ($filter == 'week') {
            $start = now()->startOfWeek()->format('Y-m-d H:i:s');
            $end = now()->endOfWeek()->format('Y-m-d H:i:s');
        } elseif ($filter == 'month') {
            $start = now()->startOfMonth()->format('Y-m-d H:i:s');
            $end = now()->endOfMonth()->format('Y-m-d H:i:s');
        } elseif ($filter == 'year') {
            $start = now()->startOfYear()->format('Y-m-d H:i:s');
            $end = now()->endOfYear()->format('Y-m-d H:i:s');
        } elseif ($filter == 'all') {
            $start = '2000-01-01 00:00:00';
            $end = now()->format('Y-m-d H:i:s');
        } else {
            $start = '2000-01-01 00:00:00';
            $end = now()->format('Y-m-d H:i:s');
        }

        return ['start' => $start, 'end' => $end];
    }

    public function logout(Request $request)
    {
        app(LogController::class)->insert('logout', 'users', Auth::user()->id, Auth::user()->id);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully');
    }

    public function profile()
    {
        return view('admin.profile');
    }

    public function update_profile(Request $request, $id)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $id,
            'phone' => 'required|numeric|digits:10',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $user = User::findOrFail($id);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($user->image) {
                $oldImagePath = public_path('user/' . $user->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Store new image
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('user'), $imageName);

            // Update user record with new image
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'image' => $imageName
            ]);
        } else {
            // Update user without changing the image
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone
            ]);
        }
        app(LogController::class)->insert('update', 'users', auth()->id(), $id);

        return redirect()->back()->with('success', 'Profile Updated Successfully');
    }

    public function changepassword()
    {
        return view('admin.change_password');
    }


    public function update_password(Request $request, $id)
    {

        $request->validate([
            'password' => 'required',
            'cpassword' => 'required',
        ]);

        if ($request->password == $request->cpassword) {
            $user = User::findOrFail($id);

            // Hash the new password before updating
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }
        app(LogController::class)->insert('change password', 'users', auth()->id(), $id);
        return redirect()->route('changepassword')->with(['success' => 'Password updated successfully!']);
    }

    public function forgotpassword(Request $request)
    {
        $email = $request->email;
        $user = User::where('email', $email)->first();

        if ($user) {
            $otp = random_int(1000, 9999);
            $user->update(['otp' => $otp]);
            $message = view('emails.otp', ['otp' => $otp])->render(); // Using Blade view

            //Send OTP via Email
            Mail::send([], [], function ($mail) use ($email, $message) {
                $mail->to($email)
                    ->subject('Password Reset OTP')
                    ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->html($message);
            });

            return view('admin.verify')->with('success', 'Otp Send To Mail');
        } else {
            return redirect()->back()->with('error', 'Invalid Email Address');
        }
    }


    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:4',
            'password' => 'required|min:6'
        ]);

        $user = User::where('otp', $request->otp)->first();
        if ($user) {
            $password = Hash::make($request->password);
            $user->update([
                'password' => $password,
            ]);
            app(abstract: LogController::class)->insert('Forget Password', 'users', $user->id, $user->id);

            return redirect()->route('login')->with('success', 'Password Changed Now Login');
        }
        return redirect()->back()->with('error', 'Invalid OTP');
    }
}
