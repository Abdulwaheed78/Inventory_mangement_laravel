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
        $suppliers = Supplier::where('deleted', 'no')->orderBy('id', 'desc')->limit(5)->get();
        $orders = Order::with(['customer'])->orderBy('id', 'desc')->limit(5)->get();
        $logs = Log::with(['user'])->orderBy('id', 'desc')->limit(10)->get();
        $payments = Payment::with(['order.customer', 'pmode'])->orderBy('date', 'desc')->limit(8)->get();
        $pmodes = PaymentMode::where('deleted', 'no')->orderBy('id', 'desc')->limit(5)->get();

        $totalCustomers = Customer::where('deleted', 'no')->count();
        $totalCategories = Category::where('deleted', 'no')->count();
        $totalWarehouses = Warehouse::where('deleted', 'no')->count();
        $totalStages = Stage::where('deleted', 'no')->count();
        $totalProducts = Product::where('deleted', 'no')->count();
        $totalSuppliers = Supplier::where('deleted', 'no')->count();
        $totalOrders = Order::count();
        $totalPurchaseOrders = Purchase::count();
        $totalLogs = Log::count();
        $totalPaymentRecords = Payment::count();
        $totalModes = PaymentMode::where('deleted', 'no')->count();

        $totalOrderAmount = (float) Order::sum('total_amount');
        $totalPurchaseAmount = (float) Purchase::sum('total_amount');
        $totalReceivedAmount = (float) Payment::sum('amount');
        $totalOutstandingAmount = max($totalOrderAmount - $totalReceivedAmount, 0);
        $totalStockValue = (float) Product::where('deleted', 'no')
            ->selectRaw('COALESCE(SUM(stock_quantity * price), 0) as total_stock_value')
            ->value('total_stock_value');

        $dashboardData = $this->buildDashboardData('180');

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
            'totalPaymentRecords',
            'pmodes',
            'totalModes',
            'totalOrderAmount',
            'totalPurchaseAmount',
            'totalReceivedAmount',
            'totalOutstandingAmount',
            'totalStockValue',
            'dashboardData'
        ));
    }

    public function getAmounts(Request $request)
    {
        return response()->json(
            $this->buildDashboardData(
                $request->filter,
                $request->start_date,
                $request->end_date
            )
        );
    }

    /**
     * Get start and end date based on filter type.
     */
    private function getDateRange($filter, $customStart = null, $customEnd = null)
    {
        if ($filter == 'today') {
            $start = now()->startOfDay()->format('Y-m-d H:i:s');
            $end = now()->endOfDay()->format('Y-m-d H:i:s');
        } elseif (in_array((string) $filter, ['7', '30', '60', '90', '180'], true)) {
            $days = (int) $filter - 1;
            $start = now()->subDays($days)->startOfDay()->format('Y-m-d H:i:s');
            $end = now()->endOfDay()->format('Y-m-d H:i:s');
        } elseif ($filter == 'yearly') {
            $start = now()->startOfYear()->format('Y-m-d H:i:s');
            $end = now()->endOfYear()->format('Y-m-d H:i:s');
        } elseif ($filter == 'custom' && $customStart && $customEnd) {
            $start = Carbon::parse($customStart)->startOfDay()->format('Y-m-d H:i:s');
            $end = Carbon::parse($customEnd)->endOfDay()->format('Y-m-d H:i:s');
        } elseif ($filter == 'all') {
            $start = '2000-01-01 00:00:00';
            $end = now()->format('Y-m-d H:i:s');
        } else {
            $start = '2000-01-01 00:00:00';
            $end = now()->format('Y-m-d H:i:s');
        }

        return ['start' => $start, 'end' => $end];
    }

    private function buildDashboardData($filter, $customStart = null, $customEnd = null)
    {
        $dateRange = $this->getDateRange($filter, $customStart, $customEnd);
        $startDate = Carbon::parse($dateRange['start']);
        $endDate = Carbon::parse($dateRange['end']);

        $totalOrderAmount = (float) Order::whereBetween('order_date', [
            $startDate->toDateString(),
            $endDate->toDateString(),
        ])->sum('total_amount');

        $totalPurchaseAmount = (float) Purchase::whereBetween('purchase_date', [
            $startDate->toDateString(),
            $endDate->toDateString(),
        ])->sum('total_amount');

        $totalReceivedAmount = (float) Payment::whereBetween('date', [
            $startDate->copy()->startOfDay(),
            $endDate->copy()->endOfDay(),
        ])->sum('amount');

        $totalStockValue = (float) Product::where('deleted', 'no')
            ->select(DB::raw('COALESCE(SUM(stock_quantity * price), 0) as total_stock_value'))
            ->value('total_stock_value');

        return [
            'orders_total' => $totalOrderAmount,
            'purchases_total' => $totalPurchaseAmount,
            'payments_total' => $totalReceivedAmount,
            'outstanding_total' => max($totalOrderAmount - $totalReceivedAmount, 0),
            'stock_value_total' => $totalStockValue,
            'sales_chart' => $this->getSalesChartData($startDate, $endDate, $filter),
            'stage_chart' => $this->getStageChartData($startDate, $endDate),
            'payment_mode_chart' => $this->getPaymentModeChartData($startDate, $endDate),
        ];
    }

    private function getSalesChartData(Carbon $startDate, Carbon $endDate, $filter)
    {
        $labels = [];
        $orders = [];
        $purchases = [];
        $payments = [];
        $diffInDays = $startDate->diffInDays($endDate) + 1;
        $diffInYears = $startDate->diffInYears($endDate);

        if ($diffInDays <= 30) {
            $cursor = $startDate->copy()->startOfDay();
            $last = $endDate->copy()->startOfDay();

            while ($cursor->lte($last)) {
                $periodStart = $cursor->copy()->startOfDay();
                $periodEnd = $cursor->copy()->endOfDay();

                $labels[] = $cursor->format('d M');
                $orders[] = (float) Order::whereDate('order_date', $periodStart->toDateString())->sum('total_amount');
                $purchases[] = (float) Purchase::whereDate('purchase_date', $periodStart->toDateString())->sum('total_amount');
                $payments[] = (float) Payment::whereBetween('date', [$periodStart, $periodEnd])->sum('amount');

                $cursor->addDay();
            }
        } elseif ($diffInDays <= 90) {
            $cursor = $startDate->copy()->startOfWeek();
            $last = $endDate->copy()->endOfWeek();

            while ($cursor->lte($last)) {
                $periodStart = $cursor->copy()->max($startDate->copy()->startOfDay());
                $periodEnd = $cursor->copy()->endOfWeek()->min($endDate->copy()->endOfDay());

                $labels[] = $periodStart->format('d M') . ' - ' . $periodEnd->format('d M');
                $orders[] = (float) Order::whereBetween('order_date', [
                    $periodStart->toDateString(),
                    $periodEnd->toDateString(),
                ])->sum('total_amount');
                $purchases[] = (float) Purchase::whereBetween('purchase_date', [
                    $periodStart->toDateString(),
                    $periodEnd->toDateString(),
                ])->sum('total_amount');
                $payments[] = (float) Payment::whereBetween('date', [
                    $periodStart->copy()->startOfDay(),
                    $periodEnd->copy()->endOfDay(),
                ])->sum('amount');

                $cursor->addWeek();
            }
        } elseif ($diffInYears < 3 || $filter === 'yearly') {
            $cursor = $startDate->copy()->startOfMonth();
            $last = $endDate->copy()->startOfMonth();

            while ($cursor->lte($last)) {
                $periodStart = $cursor->copy()->startOfMonth()->max($startDate->copy()->startOfDay());
                $periodEnd = $cursor->copy()->endOfMonth()->min($endDate->copy()->endOfDay());

                $labels[] = $cursor->format('M Y');
                $orders[] = (float) Order::whereBetween('order_date', [
                    $periodStart->toDateString(),
                    $periodEnd->toDateString(),
                ])->sum('total_amount');
                $purchases[] = (float) Purchase::whereBetween('purchase_date', [
                    $periodStart->toDateString(),
                    $periodEnd->toDateString(),
                ])->sum('total_amount');
                $payments[] = (float) Payment::whereBetween('date', [
                    $periodStart->copy()->startOfDay(),
                    $periodEnd->copy()->endOfDay(),
                ])->sum('amount');

                $cursor->addMonth();
            }
        } else {
            $cursor = $startDate->copy()->startOfYear();
            $last = $endDate->copy()->startOfYear();

            while ($cursor->lte($last)) {
                $periodStart = $cursor->copy()->startOfYear()->max($startDate->copy()->startOfDay());
                $periodEnd = $cursor->copy()->endOfYear()->min($endDate->copy()->endOfDay());

                $labels[] = $cursor->format('Y');
                $orders[] = (float) Order::whereBetween('order_date', [
                    $periodStart->toDateString(),
                    $periodEnd->toDateString(),
                ])->sum('total_amount');
                $purchases[] = (float) Purchase::whereBetween('purchase_date', [
                    $periodStart->toDateString(),
                    $periodEnd->toDateString(),
                ])->sum('total_amount');
                $payments[] = (float) Payment::whereBetween('date', [
                    $periodStart->copy()->startOfDay(),
                    $periodEnd->copy()->endOfDay(),
                ])->sum('amount');

                $cursor->addYear();
            }
        }

        return [
            'labels' => $labels,
            'orders' => $orders,
            'purchases' => $purchases,
            'payments' => $payments,
        ];
    }

    private function getStageChartData(Carbon $startDate, Carbon $endDate)
    {
        $stageCounts = Stage::where('deleted', 'no')
            ->leftJoin('orders', 'stages.id', '=', 'orders.stage_id')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('orders.order_date', [
                    $startDate->toDateString(),
                    $endDate->toDateString(),
                ])->orWhereNull('orders.order_date');
            })
            ->groupBy('stages.id', 'stages.name')
            ->orderBy('stages.name')
            ->select('stages.name', DB::raw('COUNT(orders.id) as total_orders'))
            ->get();

        return [
            'labels' => $stageCounts->pluck('name')->toArray(),
            'values' => $stageCounts->pluck('total_orders')->map(fn ($value) => (int) $value)->toArray(),
        ];
    }

    private function getPaymentModeChartData(Carbon $startDate, Carbon $endDate)
    {
        $modeTotals = PaymentMode::where('payment_modes.deleted', 'no')
            ->leftJoin('payments', 'payment_modes.id', '=', 'payments.pmid')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('payments.date', [
                    $startDate->copy()->startOfDay(),
                    $endDate->copy()->endOfDay(),
                ])->orWhereNull('payments.date');
            })
            ->groupBy('payment_modes.id', 'payment_modes.name')
            ->orderBy('payment_modes.name')
            ->select('payment_modes.name', DB::raw('COALESCE(SUM(payments.amount), 0) as total_amount'))
            ->get();

        return [
            'labels' => $modeTotals->pluck('name')->toArray(),
            'values' => $modeTotals->pluck('total_amount')->map(fn ($value) => (float) $value)->toArray(),
        ];
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
