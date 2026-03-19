@extends('admin.includes.app')
@section('content')
    <style>
        .dashboard-chart-wrap {
            position: relative;
            width: 100%;
        }

        .dashboard-chart-wrap.chart-lg {
            height: 340px;
        }

        .dashboard-chart-wrap.chart-md {
            height: 340px;
        }

        .dashboard-chart-wrap.chart-sm {
            height: 300px;
        }

        .dashboard-chart-wrap canvas {
            width: 100% !important;
            height: 100% !important;
            display: block;
        }

        .dashboard-filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .dashboard-filter-select {
            min-width: 170px;
            max-width: 170px;
            font-size: 0.9rem;
            padding-top: 0.45rem;
            padding-bottom: 0.45rem;
        }

        .dashboard-range-chip {
            display: inline-flex;
            align-items: center;
            padding: 0.42rem 0.75rem;
            border-radius: 999px;
            background: #e0f2fe;
            color: #075985;
            font-weight: 600;
            font-size: 0.78rem;
            line-height: 1.2;
            white-space: nowrap;
        }

        .dashboard-modal-note {
            color: #64748b;
            font-size: 0.9rem;
        }

        .dashboard-stat-card {
            height: 100%;
        }

        .dashboard-stat-card .card-body {
            min-height: 138px;
            display: flex;
            align-items: center;
        }

        .dashboard-stat-card .numbers {
            min-width: 0;
        }

        .dashboard-stat-card .card-title {
            margin-bottom: 0;
            line-height: 1.2;
            word-break: break-word;
        }

        .dashboard-stat-card #card_stock_value {
            display: block;
            margin-top: 4px;
            line-height: 1.2;
        }
    </style>
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Dashboard</h3>
        </div>
        <div class="d-flex ms-md-auto py-2 py-md-0 gap-2">
            <div class="dropdown">
                <button class="btn btn-primary btn-round dropdown-toggle" type="button" id="addDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-plus"></i> Create
                </button>
                <ul class="dropdown-menu" aria-labelledby="addDropdown">
                    <li><a class="dropdown-item" href="{{ route('categories.create') }}">Add Category</a></li>
                    <li><a class="dropdown-item" href="{{ route('warehouses.create') }}">Add Warehouse</a></li>
                    <li><a class="dropdown-item" href="{{ route('modes.create') }}">Add Payment Mode</a></li>
                    <li><a class="dropdown-item" href="{{ route('customers.create') }}">Add Customer</a></li>
                    <li><a class="dropdown-item" href="{{ route('stages.create') }}">Add Stage</a></li>
                    <li><a class="dropdown-item" href="{{ route('products.create') }}">Add Product</a></li>
                    <li><a class="dropdown-item" href="{{ route('orders.create') }}">Add Order</a></li>
                    <li><a class="dropdown-item" href="{{ route('suppliers.create') }}">Add Supplier</a></li>
                    <li><a class="dropdown-item" href="{{ route('purchases.create') }}">Add Purchase</a></li>
                    <li><a class="dropdown-item" href="{{ route('payments.create') }}">Add Payment</a></li>
                </ul>
            </div>

            <div class="mb-3 dashboard-filter-group">
                <select class="form-select dashboard-filter-select" id="filterSelect" onchange="handleFilterChange(this.value)">
                    <option value="today">Today</option>
                    <option value="7">Last 7 Days</option>
                    <option value="30">Last 30 Days</option>
                    <option value="60">Last 60 Days</option>
                    <option value="90">Last 90 Days</option>
                    <option value="180" selected>Last 180 Days</option>
                    <option value="yearly">This Year</option>
                    <option value="custom">Custom Range</option>
                </select>
                <span class="dashboard-range-chip" id="activeFilterLabel">Showing: Last 180 Days</span>
            </div>
        </div>
    </div>

    {{-- cards start --}}
    <div class="row mb-3">
        <!-- Total Orders Card -->
        <div class="col-md-3">
            <div class="card card-stats card-round dashboard-stat-card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Orders Total</p>
                                <h4 class="card-title" id="card_order_amount"></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Purchase Orders Card -->
        <div class="col-md-3">
            <div class="card card-stats card-round dashboard-stat-card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Purchases Total</p>
                                <h4 class="card-title" id="card_purchase_amount"></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Payments Card -->
        <div class="col-md-3">
            <div class="card card-stats card-round dashboard-stat-card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-warning bubble-shadow-small">
                                <i class="fas fa-credit-card"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Payments Received</p>
                                <h4 class="card-title" id="card_revenue_amount"></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Products Card -->
        <div class="col-md-3">
            <div class="card card-stats card-round dashboard-stat-card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-danger bubble-shadow-small">
                                <i class="fas fa-box"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Outstanding + Stock Value</p>
                                <h4 class="card-title" id="card_instock_amount"></h4>
                                <small class="text-muted" id="card_stock_value"></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- cards end  --}}

    <div class="row">
        <div class="col-md-8">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-title">Sales, Purchases, and Payments</div>
                    <div class="card-category">Financial movement for the selected date range</div>
                </div>
                <div class="card-body">
                    <div class="dashboard-chart-wrap chart-lg">
                        <canvas id="salesOverviewChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-title">Orders by Stage</div>
                    <div class="card-category">Current pipeline distribution</div>
                </div>
                <div class="card-body">
                    <div class="dashboard-chart-wrap chart-md">
                        <canvas id="stageBreakdownChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-title">Payment Collection by Mode</div>
                    <div class="card-category">Received amount split across payment methods</div>
                </div>
                <div class="card-body">
                    <div class="dashboard-chart-wrap chart-sm">
                        <canvas id="paymentModeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="customRangeModal" tabindex="-1" aria-labelledby="customRangeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customRangeModalLabel">Select Custom Date Range</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="dashboard-modal-note mb-3">Choose a start and end date to update the cards and all charts.</p>
                    <div class="mb-3">
                        <label for="startDate" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="startDate" value="{{ now()->subDays(29)->toDateString() }}">
                    </div>
                    <div class="mb-0">
                        <label for="endDate" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="endDate" value="{{ now()->toDateString() }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="applyCustomRange">Apply Range</button>
                </div>
            </div>
        </div>
    </div>


    {{-- <div class="row">
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            <div class="card-head-row card-tools-still-right">
                                <h4 class="card-title">Users Geolocation</h4>
                                <div class="card-tools">
                                    <button class="btn btn-icon btn-link btn-primary btn-xs">
                                        <span class="fa fa-angle-down"></span>
                                    </button>
                                    <button class="btn btn-icon btn-link btn-primary btn-xs btn-refresh-card">
                                        <span class="fa fa-sync-alt"></span>
                                    </button>
                                    <button class="btn btn-icon btn-link btn-primary btn-xs">
                                        <span class="fa fa-times"></span>
                                    </button>
                                </div>
                            </div>
                            <p class="card-category">
                                Map of the distribution of users around the world
                            </p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="table-responsive table-hover table-sales">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="flag">
                                                            <img src="assets/img/flags/id.png" alt="indonesia" />
                                                        </div>
                                                    </td>
                                                    <td>Indonesia</td>
                                                    <td class="text-end">2.320</td>
                                                    <td class="text-end">42.18%</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="flag">
                                                            <img src="assets/img/flags/us.png" alt="united states" />
                                                        </div>
                                                    </td>
                                                    <td>USA</td>
                                                    <td class="text-end">240</td>
                                                    <td class="text-end">4.36%</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="flag">
                                                            <img src="assets/img/flags/au.png" alt="australia" />
                                                        </div>
                                                    </td>
                                                    <td>Australia</td>
                                                    <td class="text-end">119</td>
                                                    <td class="text-end">2.16%</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="flag">
                                                            <img src="assets/img/flags/ru.png" alt="russia" />
                                                        </div>
                                                    </td>
                                                    <td>Russia</td>
                                                    <td class="text-end">1.081</td>
                                                    <td class="text-end">19.65%</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="flag">
                                                            <img src="assets/img/flags/cn.png" alt="china" />
                                                        </div>
                                                    </td>
                                                    <td>China</td>
                                                    <td class="text-end">1.100</td>
                                                    <td class="text-end">20%</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="flag">
                                                            <img src="assets/img/flags/br.png" alt="brazil" />
                                                        </div>
                                                    </td>
                                                    <td>Brasil</td>
                                                    <td class="text-end">640</td>
                                                    <td class="text-end">11.63%</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mapcontainer">
                                        <div id="world-map" class="w-100" style="height: 300px"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
    <div class="row">
        <!-- Orders & Logs (8-4 Pair) -->
        <div class="col-md-8">
            <div class="card card-round">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="card-title">New Orders ({{ $totalOrders }})</div>
                        <div>
                            <a href="{{ route('orders.index') }}" class="text-primary">View All</a> |
                            <a href="{{ route('export_order') }}" class="text-primary ml-2">
                                Export
                            </a>
                        </div>
                    </div>
                    <div class="card-list py-4">
                        @foreach ($orders as $order)
                            <div class="item-list d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="avatar">
                                        <span class="avatar-title rounded-circle border border-white bg-warning">O</span>
                                    </div>
                                    <div class="info-user ms-3">
                                        <div class="username">
                                            {{ $order->customer->name ?? 'Unknown Customer' }} |
                                            Order #{{ $order->id }} |
                                            {{ date('d M Y h:i A', strtotime($order->created_at)) }}
                                        </div>

                                        <div class="text-dark">
                                            <i class="fas fa-phone-alt text-success"></i>
                                            <a href="tel:{{ $order->customer->phone }}" class="text-dark">
                                                {{ $order->customer->phone ?? 'N/A' }}
                                            </a> |
                                            <i class="fas fa-envelope text-primary"></i>
                                            <a href="mailto:{{ $order->customer->email }}" class="text-dark">
                                                {{ $order->customer->email ?? 'N/A' }}
                                            </a>
                                        </div>
                                        <div class="text-muted">
                                            <strong>Total:</strong> ₹{{ number_format($order->total_amount, 2) }} |
                                            <strong>Payment:</strong> {{ ucfirst($order->status) }} |
                                            <strong>Stage:</strong>{{ ucfirst($order->stage->name) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="actions d-flex">
                                    <a href="{{ route('orders.edit', $order->id) }}" class="text-warning me-2">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    <form action="{{ route('orders.delete', $order->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this Order?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-danger border-0 bg-transparent">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-round">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="card-title">Logs ({{ $totalLogs }})</div>
                        <div>
                            <a href="{{ route(name: 'logs.index') }}" class="text-primary">View All</a> |
                            <a href="{{ route('export_logs') }}" class="text-primary ml-2">
                                Export
                            </a>
                        </div>
                    </div>
                    <div class="card-list py-4">
                        @foreach ($logs as $log)
                            <div class="item-list d-flex align-items-center">
                                <div class="log-icon me-3">
                                    @php
                                        $icons = [
                                            'login' => ['icon' => 'fas fa-sign-in-alt', 'color' => 'text-success'],
                                            'logout' => ['icon' => 'fas fa-sign-out-alt', 'color' => 'text-danger'],
                                            'insert' => ['icon' => 'fas fa-plus-circle', 'color' => 'text-primary'],
                                            'update' => ['icon' => 'fas fa-edit', 'color' => 'text-warning'],
                                            'delete' => ['icon' => 'fas fa-trash-alt', 'color' => 'text-danger'],
                                            'default' => ['icon' => 'fas fa-info-circle', 'color' => 'text-secondary'],
                                        ];
                                        $logType = strtolower($log->type);
                                        $icon = $icons[$logType] ?? $icons['default'];
                                    @endphp
                                    <i class="{{ $icon['icon'] }} {{ $icon['color'] }} fa-lg"></i>
                                </div>
                                <div class="info-user">
                                    <div>
                                        <strong>{{ $log->user->name ?? 'N/A' }}</strong> -
                                        {{ ucfirst($log->type) }} ,
                                        {{ $log->table ?? 'N/A' }} ,
                                        ({{ $log->rid ?? 'N/A' }})
                                    </div>
                                    <div class="text-muted">
                                        {{ $log->created_at->format('d M Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('logs.index') }}" class="btn btn-primary btn-sm">See All</a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Payments & Customers (4-8 Pair) -->
    <div class="row">
        <div class="col-md-4">
            <div class="card card-round">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="card-title">New Customers ({{ $totalCustomers }})</div>
                        <div>
                            <a href="{{ route('customers.index') }}" class="text-primary">View All</a> |
                            <a href="{{ route('export_customer') }}" class="text-primary ml-2">
                                Export
                            </a>
                        </div>
                    </div>
                    <div class="card-list py-4">
                        @foreach ($customers as $customer)
                            <div class="item-list d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="avatar">
                                        <span class="avatar-title rounded-circle border border-white bg-dark">
                                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="info-user ms-3">
                                        <div class="username">
                                            {{ $customer->name }}
                                            <a href="mailto:{{ $customer->email }}" class="text-dark">
                                                <i class="fas fa-envelope text-primary"></i>
                                            </a> |
                                            <a href="tel:{{ $customer->phone }}" class="text-dark">
                                                <i class="fa-solid fa-phone text-success"></i>
                                            </a>

                                        </div>
                                        <div class="text-muted">
                                            {{ date('d M Y h:i A', strtotime($customer->created_at)) }}
                                        </div>

                                    </div>
                                </div>
                                <div class="actions d-flex">
                                    <a href="{{ route('customers.edit', $customer->id) }}" class="text-warning me-2">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    <form action="{{ route('customers.delete', $customer->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this Customer?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="text-danger border-0 bg-transparent">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card card-round">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Payments ({{ $totalPaymentRecords }})</h4>
                        <div>
                            <a href="{{ route('payments.index') }}" class="text-primary">View All</a> |
                            <a href="{{ route('export_payment') }}" class="text-primary ml-2">
                                Export
                            </a>
                        </div>
                    </div>

                    <div class="card-list py-4">
                        @foreach ($payments as $payment)
                            <div class="item-list d-flex align-items-center justify-content-between border-bottom py-2">
                                @php
                                    $customerName = $payment->order->customer->name ?? 'N/A';
                                    $orderId = $payment->order->id ?? 'N/A';
                                @endphp
                                <!-- Avatar -->
                                <div class="avatar">
                                    <span class="avatar-title rounded-circle border border-white bg-success">
                                        {{ strtoupper(substr($customerName, 0, 1)) }}
                                    </span>
                                </div>

                                <!-- Payment Info -->
                                <div class="info-user ms-3 flex-grow-1">
                                    <div class="username"><strong>Payment #{{ $payment->id }}</strong></div>
                                    <div class="status">Order: #000{{ $orderId }}</div>
                                    <div class="status">Customer: {{ $customerName }}</div>
                                    <div class="status">Final Amount: Rs {{ number_format($payment->amount, 2) }}
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex">
                                    <a href="{{ route('payments.edit', $payment->id) }}" class="text-warning me-2">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    <form action="{{ route('payments.destroy', $payment->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this payment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-danger border-0 bg-transparent">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Products & Purchases (6-6 Pair) -->
    <div class="row">
        <div class="col-md-8">
            <div class="card card-round">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="card-title">New Products ({{ $totalProducts }})</div>
                        <div>
                            <a href="{{ route('products.index') }}" class="text-primary">View All</a> |
                            <a href="{{ route('export_product') }}" class="text-primary ml-2">
                                Export
                            </a>
                        </div>
                    </div>
                    <div class="card-list py-4">
                        @foreach ($products as $product)
                            <div class="item-list d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="avatar">
                                        <img src="{{ asset('admin/assets/img/default.png') }}" alt="No IMg"
                                            class="avatar-img rounded-circle" />
                                    </div>
                                    <div class="info-user ms-3">
                                        <div class="username">
                                            {{ $product->name }} |
                                            {{ $product->category->name ?? 'N/A' }} |
                                            {{ $product->warehouse->name ?? 'N/A' }}
                                        </div>
                                        <div class="text-muted">
                                            <strong>Price:</strong> ₹{{ number_format($product->price, 2) }} |
                                            <strong>SKU:</strong> {{ $product->sku }} |
                                            <strong>Qty:</strong> {{ $product->stock_quantity }}
                                        </div>
                                        <small class="text-muted">Created:
                                            {{ date('d M Y h:i A', strtotime($product->created_at)) }}</small>
                                    </div>
                                </div>
                                <div class="actions d-flex">
                                    <a href="{{ route('products.edit', $product->id) }}" class="text-warning me-2">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    <form action="{{ route('products.delete', $product->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this Product?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="text-danger border-0 bg-transparent">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-round">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="card-title">Payment Modes ({{ $totalModes }})</div>
                        <div>
                            <a href="{{ route('modes.index') }}" class="text-primary">View All</a> |
                            <a href="{{ route('export_pmode') }}" class="text-primary ml-2">
                                Export
                            </a>
                        </div>
                    </div>
                    <div class="card-list py-4">
                        @foreach ($pmodes as $stage)
                            <div class="item-list d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="avatar">
                                        <span class="avatar-title rounded-circle border border-white bg-purple">S</span>
                                    </div>
                                    <div class="info-user ms-3">
                                        <div class="username">{{ $stage->name }} | {{ $stage->initial }}</div>
                                        <small
                                            class="text-muted">{{ date('d M Y h:i A', strtotime($stage->created_at)) }}</small>
                                    </div>
                                </div>
                                <div class="actions d-flex">
                                    <a href="{{ route('modes.edit', $stage->id) }}" class="text-warning me-2"><i
                                            class="fa fa-edit"></i></a>

                                    <form action="{{ route('modes.delete', $stage->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this Payment Mode?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="text-danger border-0 bg-transparent">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-round">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="card-title">New Suppliers ({{ $totalSuppliers }})</div>
                        <div>
                            <a href="{{ route('suppliers.index') }}" class="text-primary">View All</a> |
                            <a href="{{ route('export_supplier') }}" class="text-primary ml-2">
                                Export
                            </a>
                        </div>
                    </div>
                    <div class="card-list py-4">
                        @foreach ($suppliers as $purchase)
                            <div class="item-list d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="avatar">
                                        <span class="avatar-title rounded-circle border border-white bg-primary">P</span>
                                    </div>
                                    <div class="info-user ms-3">
                                        <div class="username">
                                            {{ $purchase->name ?? 'Unknown Supplier' }} |
                                            {{ date('d M Y h:i A', strtotime($purchase->created_at)) }}
                                        </div>
                                        <div class="text-muted">
                                            <a href="mailto:{{ $purchase->cemail }}" class="text-dark">
                                                {{ $purchase->email ?? 'N/A' }}
                                            </a> |
                                            <a href="tel:{{ $purchase->phone }}" class="text-dark">
                                                {{ $purchase->phone ?? 'N/A' }}
                                            </a>
                                        </div>

                                    </div>
                                </div>
                                <div class="actions d-flex">
                                    <a href="{{ route('suppliers.edit', $purchase->id) }}" class="text-warning me-2">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    <form action="{{ route('suppliers.delete', $purchase->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this Supplier?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="text-danger border-0 bg-transparent">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-round">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="card-title">New Purchases ({{ $totalPurchaseOrders }})</div>
                        <div>
                            <a href="{{ route('purchases.index') }}" class="text-primary">View All</a> |
                            <a href="{{ route('export_purchase') }}" class="text-primary ml-2">
                                Export
                            </a>
                        </div>
                    </div>
                    <div class="card-list py-4">
                        @foreach ($purchases as $purchase)
                            <div class="item-list d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="avatar">
                                        <span class="avatar-title rounded-circle border border-white bg-primary">P</span>
                                    </div>
                                    <div class="info-user ms-3">
                                        <div class="username">
                                            {{ $purchase->supplier->name ?? 'Unknown Supplier' }} |
                                            {{ date('d M Y h:i A', strtotime($purchase->created_at)) }}
                                        </div>
                                        <div class="text-muted">
                                            <a href="mailto:{{ $purchase->supplier->email }}" class="text-dark">
                                                {{ $purchase->supplier->email ?? 'N/A' }}
                                            </a> |
                                            <a href="tel:{{ $purchase->supplier->phone }}" class="text-dark">
                                                {{ $purchase->supplier->phone ?? 'N/A' }}
                                            </a>
                                        </div>
                                        <div class="text-muted">
                                            <strong>Total:</strong>
                                            ₹{{ number_format($purchase->total_amount, 2) }} |
                                            <strong>Payment:</strong> {{ ucfirst($purchase->status) }}

                                        </div>
                                    </div>
                                </div>
                                <div class="actions d-flex">
                                    <a href="{{ route('purchases.edit', $purchase->id) }}" class="text-warning me-2">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    <form action="{{ route('purchases.delete', $purchase->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this Purchase?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="text-danger border-0 bg-transparent">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories, Warehouses & Stages (3-3-3 Pair) -->
    <div class="row">
        <!-- Categories -->
        <div class="col-md-4">
            <div class="card card-round">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="card-title">Categories ({{ $totalCategories }})</div>
                        <div>
                            <a href="{{ route('categories.index') }}" class="text-primary">View All</a> |
                            <a href="{{ route('export_category') }}" class="text-primary ml-2">
                                Export
                            </a>
                        </div>

                    </div>
                    <div class="card-list py-4">
                        @foreach ($categories as $category)
                            <div class="item-list d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="avatar">
                                        <span class="avatar-title rounded-circle border border-white bg-info">C</span>
                                    </div>
                                    <div class="info-user ms-3">
                                        <div class="username">{{ $category->name }}</div>
                                        <small
                                            class="text-muted">{{ date('d M Y h:i A', strtotime($category->created_at)) }}</small>
                                    </div>
                                </div>
                                <div class="actions d-flex">
                                    <a href="{{ route('categories.edit', $category->id) }}" class="text-warning me-2"><i
                                            class="fa fa-edit"></i></a>
                                    <form action="{{ route('categories.delete', $category->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this Category?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="text-danger border-0 bg-transparent">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Warehouses -->
        <div class="col-md-4">
            <div class="card card-round">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="card-title">Warehouses ({{ $totalWarehouses }})</div>
                        <div>
                            <a href="{{ route('warehouses.index') }}" class="text-primary">View All</a> |
                            <a href="{{ route('export_warehouse') }}" class="text-primary ml-2">
                                Export
                            </a>
                        </div>
                    </div>
                    <div class="card-list py-4">
                        @foreach ($warehouses as $warehouse)
                            <div class="item-list d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="avatar">
                                        <span class="avatar-title rounded-circle border border-white bg-dark">W</span>
                                    </div>
                                    <div class="info-user ms-3">
                                        <div class="username">{{ $warehouse->name }}</div>
                                        <small
                                            class="text-muted">{{ date('d M Y h:i A', strtotime($warehouse->created_at)) }}</small>
                                    </div>
                                </div>
                                <div class="actions d-flex">
                                    <a href="{{ route('warehouses.edit', $warehouse->id) }}"
                                        class="text-warning me-2"><i class="fa fa-edit"></i></a>

                                    <form action="{{ route('warehouses.delete', $warehouse->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this Warehouse?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="text-danger border-0 bg-transparent">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Stages -->
        <div class="col-md-4">
            <div class="card card-round">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="card-title">Stages ({{ $totalStages }})</div>
                        <div>
                            <a href="{{ route('stages.index') }}" class="text-primary">View All</a> |
                            <a href="{{ route('export_stage') }}" class="text-primary ml-2">
                                Export
                            </a>
                        </div>
                    </div>
                    <div class="card-list py-4">
                        @foreach ($stages as $stage)
                            <div class="item-list d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="avatar">
                                        <span class="avatar-title rounded-circle border border-white bg-purple">S</span>
                                    </div>
                                    <div class="info-user ms-3">
                                        <div class="username">{{ $stage->name }}</div>
                                        <small
                                            class="text-muted">{{ date('d M Y h:i A', strtotime($stage->created_at)) }}</small>
                                    </div>
                                </div>
                                <div class="actions d-flex">
                                    <a href="{{ route('stages.edit', $stage->id) }}" class="text-warning me-2"><i
                                            class="fa fa-edit"></i></a>

                                    <form action="{{ route('stages.delete', $stage->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this Stage?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="text-danger border-0 bg-transparent">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let dashboardPayload = @json($dashboardData);
        let salesOverviewChartInstance = null;
        let stageBreakdownChartInstance = null;
        let paymentModeChartInstance = null;
        let customRangeModalInstance = null;

        function formatCurrency(amount) {
            const value = Number(amount || 0);
            const absoluteValue = Math.abs(value);

            if (absoluteValue >= 1000000) {
                return `Rs ${ (value / 1000000).toFixed(1).replace(/\.0$/, '') }M`;
            }

            if (absoluteValue >= 100000) {
                return `Rs ${ (value / 100000).toFixed(1).replace(/\.0$/, '') }L`;
            }

            if (absoluteValue >= 1000) {
                return `Rs ${ (value / 1000).toFixed(1).replace(/\.0$/, '') }K`;
            }

            return `Rs ${ value.toFixed(2).replace(/\.00$/, '') }`;
        }

        function formatCurrencyFull(amount) {
            return new Intl.NumberFormat('en-IN', {
                style: 'currency',
                currency: 'INR',
                maximumFractionDigits: 2
            }).format(Number(amount || 0));
        }

        function getFilterLabel(filterType) {
            const labels = {
                today: 'Today',
                7: '7D',
                30: '30D',
                60: '60D',
                90: '90D',
                180: '180D',
                yearly: 'Year',
                custom: `${$('#startDate').val()} to ${$('#endDate').val()}`
            };

            return labels[filterType] || 'Custom Range';
        }

        function shortenChartLabel(label) {
            if (!label) {
                return '';
            }

            if (label.includes(' - ')) {
                const [start, end] = label.split(' - ');
                const shortStart = start.split(' ').slice(0, 2).join(' ');
                const shortEnd = end.split(' ').slice(0, 2).join(' ');
                return `${shortStart}-${shortEnd}`;
            }

            return label.length > 10 ? label.slice(0, 10) : label;
        }

        function renderDashboardCharts(payload) {
            const salesContext = document.getElementById('salesOverviewChart');
            if (salesContext) {
                if (salesOverviewChartInstance) {
                    salesOverviewChartInstance.destroy();
                }

                salesOverviewChartInstance = new Chart(salesContext, {
                    type: 'line',
                    data: {
                        labels: payload.sales_chart.labels.map(shortenChartLabel),
                        datasets: [{
                                label: 'Orders',
                                data: payload.sales_chart.orders,
                                borderColor: '#2563eb',
                                backgroundColor: 'rgba(37, 99, 235, 0.12)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.35
                            },
                            {
                                label: 'Purchases',
                                data: payload.sales_chart.purchases,
                                borderColor: '#f97316',
                                backgroundColor: 'rgba(249, 115, 22, 0.08)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.35
                            },
                            {
                                label: 'Payments',
                                data: payload.sales_chart.payments,
                                borderColor: '#16a34a',
                                backgroundColor: 'rgba(22, 163, 74, 0.08)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.35
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `${context.dataset.label}: ${formatCurrencyFull(context.parsed.y)}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                ticks: {
                                    maxRotation: 0,
                                    autoSkip: true,
                                    maxTicksLimit: 8
                                }
                            },
                            y: {
                                ticks: {
                                    callback: function(value) {
                                        return formatCurrency(value);
                                    }
                                }
                            }
                        }
                    }
                });
            }

            const stageContext = document.getElementById('stageBreakdownChart');
            if (stageContext) {
                if (stageBreakdownChartInstance) {
                    stageBreakdownChartInstance.destroy();
                }

                stageBreakdownChartInstance = new Chart(stageContext, {
                    type: 'doughnut',
                    data: {
                        labels: payload.stage_chart.labels,
                        datasets: [{
                            data: payload.stage_chart.values,
                            backgroundColor: ['#2563eb', '#0ea5e9', '#14b8a6', '#f59e0b', '#ef4444', '#8b5cf6'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `${context.label}: ${context.parsed}`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            const paymentContext = document.getElementById('paymentModeChart');
            if (paymentContext) {
                if (paymentModeChartInstance) {
                    paymentModeChartInstance.destroy();
                }

                paymentModeChartInstance = new Chart(paymentContext, {
                    type: 'bar',
                    data: {
                        labels: payload.payment_mode_chart.labels.map(shortenChartLabel),
                        datasets: [{
                            label: 'Amount Received',
                            data: payload.payment_mode_chart.values,
                            backgroundColor: ['#1d4ed8', '#0ea5e9', '#14b8a6', '#f59e0b', '#ef4444', '#8b5cf6'],
                            borderRadius: 8,
                            barPercentage: 0.45,
                            categoryPercentage: 0.5,
                            maxBarThickness: 24
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `${context.label}: ${formatCurrencyFull(context.parsed.y)}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                ticks: {
                                    callback: function(value) {
                                        return formatCurrency(value);
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }

        function updateDashboardCards(response) {
            $("#card_order_amount").text(formatCurrency(response.orders_total));
            $("#card_purchase_amount").text(formatCurrency(response.purchases_total));
            $("#card_revenue_amount").text(formatCurrency(response.payments_total));
            $("#card_instock_amount").text("Outstanding: " + formatCurrency(response.outstanding_total));
            $("#card_stock_value").text("Stock value: " + formatCurrency(response.stock_value_total));
        }

        function updateActiveFilterLabel(filterType) {
            $('#activeFilterLabel').text(`Showing: ${getFilterLabel(filterType)}`);
        }

        function getFilterRequestData(filterType) {
            const data = {
                filter: filterType,
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            if (filterType === 'custom') {
                data.start_date = $('#startDate').val();
                data.end_date = $('#endDate').val();
            }

            return data;
        }

        function handleFilterChange(filterType) {
            if (filterType === 'custom') {
                customRangeModalInstance.show();
                return;
            }

            getAmounts(filterType);
        }

        function getAmounts(filterType) {
            $.ajax({
                url: 'dashboard/payments',
                type: 'POST',
                data: getFilterRequestData(filterType),
                success: function(response) {
                    dashboardPayload = response;
                    updateDashboardCards(response);
                    renderDashboardCharts(response);
                    updateActiveFilterLabel(filterType);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }
        $(document).ready(function() {
            customRangeModalInstance = new bootstrap.Modal(document.getElementById('customRangeModal'));
            renderDashboardCharts(dashboardPayload);
            updateDashboardCards(dashboardPayload);
            updateActiveFilterLabel($('#filterSelect').val());

            $('#applyCustomRange').on('click', function() {
                if (!$('#startDate').val() || !$('#endDate').val()) {
                    return;
                }

                if ($('#startDate').val() > $('#endDate').val()) {
                    return;
                }

                getAmounts('custom');
                customRangeModalInstance.hide();
            });

            getAmounts($('#filterSelect').val());
        });
    </script>
@endsection
