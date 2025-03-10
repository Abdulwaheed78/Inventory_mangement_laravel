@extends('admin.includes.app')
@section('content')
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
                    {{-- <li><a class="dropdown-item" href="{{ route('all.create') }}">Add All</a></li> --}}
                </ul>
            </div>

            <div class="mb-3">
                <select class="form-select" id="filterSelect" onchange="getAmounts(this.value)">
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                    <option value="year">This Year</option>
                    <option value="all">Overall</option>
                </select>
            </div>
        </div>
    </div>

    {{-- cards start --}}
    <div class="row">
        <!-- Total Orders Card -->
        <div class="col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Orders Amount</p>
                                <h4 class="card-title" id="card_order_amount"></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Purchase Orders Card -->
        <div class="col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Purchase Amount</p>
                                <h4 class="card-title" id="card_purchase_amount"></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Payments Card -->
        <div class="col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-warning bubble-shadow-small">
                                <i class="fas fa-credit-card"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Revenue</p>
                                <h4 class="card-title" id="card_revenue_amount"></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Products Card -->
        <div class="col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-danger bubble-shadow-small">
                                <i class="fas fa-box"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">In Stock Products</p>
                                <h4 class="card-title" id="card_instock_amount"></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- cards end  --}}

    {{-- <div class="row">
                <div class="col-md-8">
                    <div class="card card-round">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title">User Statistics</div>
                                <div class="card-tools">
                                    <a href="#" class="btn btn-label-success btn-round btn-sm me-2">
                                        <span class="btn-label">
                                            <i class="fa fa-pencil"></i>
                                        </span>
                                        Export
                                    </a>
                                    <a href="#" class="btn btn-label-info btn-round btn-sm">
                                        <span class="btn-label">
                                            <i class="fa fa-print"></i>
                                        </span>
                                        Print
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="min-height: 375px">
                                <canvas id="statisticsChart"></canvas>
                            </div>
                            <div id="myChartLegend"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-primary card-round">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title">Daily Sales</div>
                                <div class="card-tools">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-label-light dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Export
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-category">March 25 - April 02</div>
                        </div>
                        <div class="card-body pb-0">
                            <div class="mb-4 mt-2">
                                <h1>$4,578.58</h1>
                            </div>
                            <div class="pull-in">
                                <canvas id="dailySalesChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="card card-round">
                        <div class="card-body pb-0">
                            <div class="h1 fw-bold float-end text-primary">+5%</div>
                            <h2 class="mb-2">17</h2>
                            <p class="text-muted">Users online</p>
                            <div class="pull-in sparkline-fix">
                                <div id="lineChart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

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
                        <h4 class="card-title">Payments ({{ $totalPayments }})</h4>
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
                                <!-- Avatar -->
                                <div class="avatar">
                                    <span class="avatar-title rounded-circle border border-white bg-success">
                                        {{ strtoupper(substr($payment->order->customer->name, 0, 1)) }}
                                    </span>
                                </div>

                                <!-- Payment Info -->
                                <div class="info-user ms-3 flex-grow-1">
                                    <div class="username"><strong>Payment #{{ $payment->id }}</strong></div>
                                    <div class="status">Order: #000{{ $payment->order->id }}</div>
                                    <div class="status">Customer: {{ $payment->order->customer->name }}</div>
                                    <div class="status">Final Amount: ${{ number_format($payment->final_amount, 2) }}
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
        <div class="col-md-12">
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
        function getAmounts(filterType) {
            $.ajax({
                url: 'dashboard/payments',
                type: 'POST',
                data: {
                    filter: filterType,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $("#card_order_amount").text("Rs " + response.total_order_amount);
                    $("#card_purchase_amount").text("Rs " + response.total_purchase_amount);
                    $("#card_revenue_amount").text("Rs " + response.net_amount);
                    $("#card_instock_amount").text("Rs " + response.total_stock_value);

                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }
    </script>
@endsection
