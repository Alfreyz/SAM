@extends('layouts.app')
@section('title', 'Home')
@section('content')
    <!-- /.card -->
    <div class="row">
        <div class="col-md-6 mt-3">
            <!-- AREA CHART -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Per Group Capaian Pembelajaran</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="cplChart"
                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mt-3">
            <!-- AREA CHART -->
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Per Group Bahan Kajian</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="bkChart"
                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Data Matakuliah</h3>
                    <!-- Search Field -->
                    <form action="{{ route('admin.home', ['matakuliah' => $matakuliah ?? '']) }}" method="GET"
                        class="input-group justify-content-end">
                        <input type="text" class="form-control-sm" id="search" name="search" placeholder="Search"
                            value="{{ $search ?? '' }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 9%;">Kode</th>
                                <th style="width: 75%;">Matakuliah</th>
                                <th style="width: 16%;">Semester</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($matakuliah as $m)
                                <tr>
                                    <td>{{ $m->kode_matakuliah }}</td>
                                    <td>{{ $m->nama_matakuliah }}</td>
                                    <td align="center">{{ $m->semester }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div style="display:flex; justify-content: center; margin-top:20px">
                        {{ $matakuliah->appends(['search' => $search])->links() }}
                        <!-- Render pagination links -->
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Dosen</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 4%;">No</th>
                                <th style="width: 9%;">NIDN</th>
                                <th style="width: 75%;">Nama</th>
                                <th style="width: 9%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($dosen as $d)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $d->nidn }}</td>
                                    <td>{{ $d->nama_dosen }}</td>
                                    <td><a class="btn btn-primary text-white" style="text-decoration: none"
                                            href="{{ route('admin.datadosen', ['nidn' => str_replace(["\r", "\n"], '', $d->nidn)]) }}">Select</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var cplChart = $('#cplChart').get(0).getContext('2d');
        var cplData = {
            labels: <?php echo json_encode($labels_cpl); ?>,
            datasets: [{
                data: <?php echo json_encode($data_cpl); ?>,
                backgroundColor: [
                    '#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de',
                    '#ff5733', '#33ff57', '#5733ff', '#33b6ff', '#b633ff'
                ],

            }]
        };
        var cplOptions = {
            maintainAspectRatio: false,
            responsive: true,
        };

        // Create the chart without legend
        var cplChartInstance = new Chart(cplChart, {
            type: 'bar',
            data: cplData,
            options: cplOptions
        });

        // Hide the legend after creating the chart
        cplChartInstance.legend.options.display = false;
        cplChartInstance.update();
    </script>



    <script>
        var bkChart = $('#bkChart').get(0).getContext('2d');
        var bkData = {
            labels: <?php echo json_encode($labels_bk); ?>,
            datasets: [{
                data: <?php echo json_encode($data_bk); ?>,
                backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de', '#ff5733',
                    '#33ff57', '#5733ff', '#33b6ff', '#b633ff'
                ],
            }]
        };
        var bkOptions = {
            maintainAspectRatio: false,
            responsive: true,
        };
        // Create pie or douhnut chart
        new Chart(bkChart, {
            type: 'bar',
            data: bkData,
            options: bkOptions
        });
    </script>
@endsection
