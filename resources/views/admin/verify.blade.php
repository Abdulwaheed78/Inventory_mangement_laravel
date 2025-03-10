<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('admin/assets/js/core/jquery-3.7.1.min.js') }}"></script>

    <style>
        /* Full page background with gradient */
        body,
        html {
            height: 100%;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(to right, #b3e5fc, white);
        }

        /* Center the login card */
        .login-card {
            width: 350px;
            padding: 30px;
            border-radius: 5px;
            background: white;
            /* Ensure no extra background */
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            position: absolute;
            top: 45%;
            transform: translateY(-50%);
        }

        .text-success {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <h3 class="text-center mb-4">Verify OTP</h3>
        <!-- Display Success Message -->
        @if (session('success'))
            <div class="alert alert-success text-center p-2">
                {{ session('success') }}
            </div>
        @endif

        <!-- Display Error Messages -->
        @if (session('error'))
            <div class="alert alert-danger text-center p-2">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <p class="alert alert-danger text-center p-2">{{ $error }}</p>
            @endforeach
        @endif

        <form action="{{ route('verify') }}" method="post">
            @csrf
            <div class="mb-2">
                <input type="text" name="otp" id="otp" placeholder="Enter OTP" class="form-control"
                    required>
            </div>

            <div class="mb-2">
                <input type="password" name="password" id="password" placeholder="New Password" class="form-control"
                    required>
            </div>


            <div class="d-flex gap-1 mt-3">
                <button type="submit" class="btn btn-sm btn-primary w-100">Submit</button>
                <a href="{{ route('login') }}" class="btn btn-sm btn-danger w-100">Cancel</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
