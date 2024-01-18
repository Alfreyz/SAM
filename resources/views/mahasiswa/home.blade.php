@extends('layouts.app')
@section('title', 'Data Mahasiswa - ' . $idn)
@section('content')
    <div class="row">
        <div class="col-md-6 mt-3">
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
    <!-- TABLE -->
    <div class="row">
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Matakuliah</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 2%">Semester</th>
                                <th style="width: 10%;">Kode Matakuliah</th>
                                <th style="width: 30%;">Nama Matakuliah</th>
                                <th style="width: 5%">Kode BK</th>
                                <th style="width: 5%">Kode CPL</th>
                                <th style="width: 2%; text-align: center;">Nilai</th>
                                <th style="width: 2%; text-align: center;">Bobot</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transkrip_mahasiswa as $data)
                                <tr>
                                    <td class="text-center">{{ $data->semester }}</td>
                                    <td>{{ $data->kode_matakuliah }}</td>
                                    <td>{{ $data->nama_matakuliah }}</td>
                                    <td>{{ $data->bahan_kajian }}</td>
                                    <td>{{ $data->cpl }}</td>
                                    <td class="text-center">{{ $data->nilai }}</td>
                                    <td class="text-center">{{ $data->bobot }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $transkrip_mahasiswa->appends(['search' => $search])->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            var bkChart = $('#bkChart').get(0).getContext('2d');
            var bkData = {
                labels: <?php echo json_encode($labels_bk); ?>,
                datasets: [{
                    label: 'Data Mahasiswa',
                    data: <?php echo json_encode($data_bk); ?>,
                    backgroundColor: [
                        'rgba(245, 105, 84, 0.5)', // Contoh warna merah dengan opacity 0.5
                        'rgba(0, 166, 90, 0.5)', // Contoh warna hijau dengan opacity 0.5
                        'rgba(243, 156, 18, 0.5)', // Contoh warna kuning dengan opacity 0.5
                        'rgba(0, 192, 239, 0.5)', // Contoh warna biru dengan opacity 0.5
                        'rgba(60, 141, 188, 0.5)', // Contoh warna biru tua dengan opacity 0.5
                        'rgba(210, 214, 222, 0.5)',
                        'rgba(255, 87, 51, 0.5)',
                        'rgba(51, 255, 87, 0.5)',
                        'rgba(87, 51, 255, 0.5)',
                        'rgba(51, 182, 255, 0.5)',
                        'rgba(182, 51, 255, 0.5)'
                    ],
                    type: 'bar'
                }, {
                    label: 'Data Group Mahasiswa',
                    data: <?php echo json_encode($data_bk_group); ?>,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2,
                    fill: false,
                    type: 'line'
                }]
            };
            var bkOptions = {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 4.0
                    }
                }
            };

            // Create the chart
            new Chart(bkChart, {
                type: 'bar',
                data: bkData,
                options: bkOptions
            });
        </script>
        <script>
            var combinedChart = $('#cplChart').get(0).getContext('2d');
            var combinedData = {
                labels: <?php echo json_encode($labels_cpl); ?>,
                datasets: [{
                    label: 'Data Mahasiswa',
                    data: <?php echo json_encode($data_cpl); ?>,
                    backgroundColor: [
                        'rgba(245, 105, 84, 0.5)', // Contoh warna merah dengan opacity 0.5
                        'rgba(0, 166, 90, 0.5)', // Contoh warna hijau dengan opacity 0.5
                        'rgba(243, 156, 18, 0.5)', // Contoh warna kuning dengan opacity 0.5
                        'rgba(0, 192, 239, 0.5)', // Contoh warna biru dengan opacity 0.5
                        'rgba(60, 141, 188, 0.5)', // Contoh warna biru tua dengan opacity 0.5
                        'rgba(210, 214, 222, 0.5)',
                        'rgba(255, 87, 51, 0.5)',
                        'rgba(51, 255, 87, 0.5)',
                        'rgba(87, 51, 255, 0.5)',
                        'rgba(51, 182, 255, 0.5)',
                        'rgba(182, 51, 255, 0.5)'
                    ],
                    type: 'bar'
                }, {
                    label: 'Data Group Mahasiswa',
                    data: <?php echo json_encode($data_cpl_group); ?>,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2,
                    fill: false,
                    type: 'line'
                }]
            };
            var combinedOptions = {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 4.0
                    }
                }
            };
            new Chart(combinedChart, {
                type: 'bar',
                data: combinedData,
                options: combinedOptions
            });
        </script>
    @endsection
