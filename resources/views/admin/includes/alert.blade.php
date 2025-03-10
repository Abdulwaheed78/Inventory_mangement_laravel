<div class="row">
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger alert-dismissible fade show" role="alert"
                style="position: fixed; top: 90px; right: 40px; z-index: 1050; max-width: 300px;" id="very-error-alert">
                <p class="text-error">{{ $error }}</p>
            </div>
        @endforeach
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert"
            style="position: fixed; top: 90px; right: 40px; z-index: 1050; max-width: 300px;" id="very-success-alert">
           <p class="text-success"> {{ session('success') }}</p>
        </div>
    @endif
</div>

<script>
    setTimeout(function() {
        var successAlert = document.getElementById('very-success-alert');
        var errorAlert = document.getElementById('very-error-alert');

        if (successAlert) {
            successAlert.classList.remove('show');
        }

        if (errorAlert) {
            errorAlert.classList.remove('show');
        }
    }, 3000); // 3000 ms = 3 seconds
</script>
