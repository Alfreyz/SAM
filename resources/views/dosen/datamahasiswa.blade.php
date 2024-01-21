@extends('layouts.app')
@section('title', 'Data Mahasiswa - ' . $nim)
@section('back-button')
    <a href="{{ route('dosen.home') }}">- Home</a>
@endsection
@section('content')
    <div class="row">
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
    </div>
    <!-- TABLE -->
    <div class="row">
        <div class="col-md-8 mt-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Matakuliah</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('dosen.datamahasiswa') }}" method="GET" class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Search by Nama Matakuliah" name="search"
                            value="{{ $search ?? '' }}">
                        <input type="hidden" name="nim" value="{{ $nim ?? '' }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </form>

                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 2%">Semester</th>
                            <th style="width: 10%;">Kode Matakuliah</th>
                            <th style="width: 30%;">Nama Matakuliah</th>
                            <th style="width: 5%">Kode BK</th>
                            <th style="width: 5%">Kode CPL</th>
                            <th style="width: 2%; text-align: center;">Nilai</th>
                            <th style="width: 1%">Bobot</th>
                            <th style="width: 1%">Bobot lama</th>
                            <th style="width: 1%; text-align:center;">Action</th>
                        </tr>
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
                                    <td class="text-center">{{ session('bobot_lama_' . $data->kode_matakuliah) }}</td>
                                    <td class="d-flex gap-3">
                                        <button class="btn btn-warning text-white update-btn" style="text-decoration: none"
                                            data-kode_matakuliah="{{ $data->kode_matakuliah }}"
                                            data-nilai="{{ $data->nilai }}" data-bobot="{{ $data->bobot }}"
                                            data-nama_matakuliah="{{ $data->nama_matakuliah }}" data-bs-toggle="modal"
                                            data-bs-target="#updateModal">
                                            Update
                                        </button>
                                        <form action="" method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $transkrip_mahasiswa->appends(['nim' => $nim])->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
        <div class="col-md-2 mt-4">
            <div class="card">
                <div class="card-header d-flex">
                    <h3 class="card-title" style="font-weight: bold">Data BK</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10%;">BK</th>
                                <th style="width: 12%">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataCountBKInMatakuliah as $bkCount)
                                @php
                                    $dataBKMatched = $dataBK->firstWhere('kode_bk', $bkCount->kode_bk);
                                    $jumlah_entri_dataBK = optional($dataBKMatched)->jumlah_entri ?? 0;
                                    $jumlah_entri_bkCount = $bkCount->jumlah_entri;
                                @endphp
                                <tr>
                                    <td>{{ $bkCount->kode_bk }}</td>
                                    <td>{{ $jumlah_entri_dataBK }} / {{ $jumlah_entri_bkCount }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $dataCountBKInMatakuliah->appends(['nim' => $nim])->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
        <div class="col-md-2 mt-4">
            <div class="card">
                <div class="card-header d-flex">
                    <h3 class="card-title" style="font-weight: bold">Data CP</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10%;">CP</th>
                                <th style="width: 12%">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataCountCPLInMatakuliah as $cplCount)
                                @php
                                    $dataCPLMatched = $dataCPL->firstWhere('kode_cpl', $cplCount->kode_cpl);
                                    $jumlah_entri_datacpl = optional($dataCPLMatched)->jumlah_entri ?? 0;
                                    $jumlah_entri_cplCount = $cplCount->jumlah_entri;
                                @endphp
                                <tr>
                                    <td>{{ $cplCount->kode_cpl }}</td>
                                    <td>{{ $jumlah_entri_datacpl }} / {{ $jumlah_entri_cplCount }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $dataCountCPLInMatakuliah->appends(['nim' => $nim])->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateForm" action="{{ route('dosen.updatenilai', ['nim' => $nim]) }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <label for="kode_matakuliah" class="col-sm-2 col-form-label">kode matakuliah</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="kode_matakuliah" name="kode_matakuliah"
                                    readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="nama_matakuliah" class="col-sm-2 col-form-label">nama matakuliah</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nama_matakuliah" name="nama_matakuliah"
                                    readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="nilai" class="col-sm-2 col-form-label">Nilai</label>
                            <div class="col-sm-10">
                                <select class="form-select" id="nilai" name="nilai">
                                    <option value="A">A</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B">B</option>
                                    <option value="B-">B-</option>
                                    <option value="C+">C+</option>
                                    <option value="C">C</option>
                                    <option value="D">D+</option>
                                    <option value="E">E</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="bobot" class="col-sm-2 col-form-label">bobot</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="bobot" name="bobot" readonly>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = new bootstrap.Modal(document.getElementById('updateModal'));

            $('.update-btn').on('click', function() {
                var kode_matakuliah = $(this).data('kode_matakuliah');
                var nama_matakuliah = $(this).data('nama_matakuliah');
                var nilai = $(this).data('nilai');
                var bobot = $(this).data('bobot');

                $('#kode_matakuliah').val(kode_matakuliah);
                $('#nama_matakuliah').val(nama_matakuliah);
                $('#nilai').val(nilai);
                $('#bobot').val(bobot);
                updateBobot();
                myModal.show();
            });

            $('#nilai').on('change', function() {
                updateBobot();
            });

            function updateBobot() {
                var nilai = $('#nilai').val();
                var bobot = calculateBobot(nilai);
                $('#bobot').val(bobot);
            }

            function calculateBobot(nilai) {
                switch (nilai) {
                    case 'A':
                        return 4.0;
                    case 'A-':
                        return 3.7;
                    case 'B+':
                        return 3.3;
                    case 'B':
                        return 3.0;
                    case 'B-':
                        return 2.7;
                    case 'C+':
                        return 2.3;
                    case 'C':
                        return 2.0;
                    case 'D':
                        return 1.0;
                    case 'E':
                        return 0.0;
                    default:
                        return 0.0;
                }
            }
        });
    </script>
    <script>
        var bkChart = $('#bkChart').get(0).getContext('2d');
        var bkData = {
            labels: <?php echo json_encode($labels_bk); ?>,
            datasets: [{
                label: 'Data Mahasiswa',
                data: <?php echo json_encode($data_bk); ?>,
                backgroundColor: [
                    'rgba(245, 105, 84, 0.5)',
                    'rgba(0, 166, 90, 0.5)',
                    'rgba(243, 156, 18, 0.5)',
                    'rgba(0, 192, 239, 0.5)',
                    'rgba(60, 141, 188, 0.5)',
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
                data: <?php echo json_encode($bk_group_avg); ?>,
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
                data: <?php echo json_encode($cpl_group_avg); ?>,
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
