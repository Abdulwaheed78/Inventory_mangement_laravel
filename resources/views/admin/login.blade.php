<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('admin/assets/img/ims.png') }}" type="image/x-icon" />
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('admin/assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(to right, #b3e5fc, white);
        }

        .login-card {
            width: 350px;
            padding: 30px;
            border-radius: 10px;
            background: white;
            /* Ensure no extra background */
            position: absolute;
            top: 45%;
            transform: translateY(-50%);
        }
    </style>
</head>

<body>
    <!-- Modal for forget password-->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Forget Password</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form name="EmailSubmit" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email: </label>
                            <input type="email" name= "email" class="form-control" id="email" required>
                            <span class="d-none text-danger" id="EmailErr">Email Required</span>
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary next_btn">Next</button>
                </div>
            </div>
        </div>
    </div>
    <div class="login-card">
        <h3 class="text-center mb-4">Login</h3>
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
        <form action="{{ route('loginuser') }}" method="post">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email: </label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-2">
                <label for="password" class="form-label">Password: </label>
                <input type="password" name="password" id="password" class="form-control" required autocomplete="on">
            </div>

            <a href="#" class="btn btn-link mb-2" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                Forgot Password?
            </a>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(".next_btn").on('click', function() {
            if ($("#email").val() !== '') {
                $("form[name='EmailSubmit']").attr('action', 'forgotpassword').submit();
            } else {
                $("#EmailErr").removeClass('d-none');
            }
        });
    </script>
</body>

</html>
