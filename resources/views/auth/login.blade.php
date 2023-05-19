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
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" id="email"
                                    aria-describedby="emailHelp">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="password">
                            </div>
                            <button type="submit" class="btn btn-primary text-white float-end"><a
                                    style="text-decoration: none; color:black;" href="A_beranda">Login</a></button>
                            <button type="submit" class="btn btn-primary text-white float-end"><a
                                    style="text-decoration: none; color:black;" href="D_beranda">Login</a></button>
                            <button type="submit" class="btn btn-primary text-white float-end"><a
                                    style="text-decoration: none; color:black;" href="M_beranda">Login</a></button>
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
