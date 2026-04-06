<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(to bottom, #fffaf5, #fff3e8); }
        .btn-orange { background: #f97316; border: none; color: white; }
        .btn-orange:hover { background: #ea580c; }
    </style>
</head>
<body>
    <div class="container">
          
        <div class="row min-vh-100 justify-content-center align-items-center">
            
            <div class="col-lg-4 col-md-6 col-sm-8">
                <div class="card shadow">
                    <div class="card-body">

                        <h4 class="text-center mb-4">Login</h4>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3"> @if($errors->any())
                            <div class="alert alert-danger mt-3">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required>
                                @error('username')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                @error('password')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-orange w-100">Login</button>
                        </form>
                     
                        <div class="mt-3 text-center">
                            <small class="text-muted">Akun Uji: peminjam1/password atau peminjam2/password</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>