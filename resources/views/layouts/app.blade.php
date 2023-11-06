<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - SAM</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">

    <!-- Custom styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sidebar-sticky {
            position: fixed;
            left: 0;
            overflow-x: hidden;
            overflow-y: auto;
            padding: 48px 0 0;
            height: 100vh;
            /* Adjust the sidebar height to fill the viewport */
        }

        .sidebar-sticky {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            overflow-x: hidden;
            overflow-y: auto;
            background-color: #f8f9fa;
            border-right: 1px solid #e5e5e5;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            height: 60px;
            line-height: 60px;
            background-color: #f5f5f5;
            text-align: center;
        }

        .content-wrapper {
            margin-left: 13%;
            /* Adjust the margin to accommodate the sidebar width */
        }

        .card-title {
            font-size: 18px;
            font-weight: bold;
            color: #000;
            margin-bottom: 0.5rem;
        }

        .table th {
            text-align: center;
        }

        .table td {
            text-align: center;
        }
    </style>
</head>

<body style="background-color: #F1F4FA">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <aside class="col-md-1 sidebar-sticky">
                @include('layouts.side')
            </aside>

            <!-- Main Content -->
            <div class="col-md-10 content-wrapper">
                <main role="main" class="px-2">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer" style="background-color: #ffffff">
        <div class="container">
            &copy; {{ date('Y') }} SAM, All rights reserved.
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
