<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css">
</head>

<body class="hold-transition login-page"
    style="background: url('https://enagic.co.id/nv3/wp-content/uploads/2014/11/bg-site.jpg'); background-size: cover; background-repeat: no-repeat;">
    <div class="container my-5 py-5">
        <div class="row justify-content-center my-5">
            <div class="col-md-6 my-5 py-5">
                <div class="card my-5">
                    <div class="card-header text-white bg-primary text-center fw-bold " style="font-size: 2rem">
                        Login
                    </div>
                    <div class="card-body">
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">ID</label>
                                <input type="text" name="idn"
                                    class="form-control @error('idn') is-invalid @enderror" name="idn"
                                    value="{{ old('idn') }}" id="idn" autofocus required>
                                @error('idn')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="current-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="float-end">
                                <a class="btn btn-danger text-white " style="margin-right: 10px;"
                                    href="register">Registrasi</a>
                                <button type="submit" class="btn btn-primary text-white">
                                    Login
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
