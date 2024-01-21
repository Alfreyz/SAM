@extends('layouts.app')
@section('title', 'Data Dosen - ' . ($dosen ? $dosen->nama_dosen : 'Nama Belum Diinsert oleh admin'))
@section('content')
    <!-- /.card -->
    <div class="row">
        <div class="col-md-6 mt-3">
            <div class="card card-primary">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Per Group Capaian Pembelajaran</h3>
                    <div class="dropdown ml-auto mr-3">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="cplDropdown" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            Pilih Angkatan
                        </button>
                        <div class="dropdown-menu dropdown-menu-left" aria-labelledby="cplDropdown">
                            @foreach ($angkatanList as $a)
                                <a id="angkatan_{{ $a }}" class="dropdown-item" style="color: black"
                                    href="#" onclick="changeChartcpl('{{ $a }}')">
                                    Angkatan {{ $a }}
                                </a>
                            @endforeach
                        </div>
                    </div>
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
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Per Group Bahan Kajian</h3>
                    <div class="dropdown ml-auto mr-3">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="bkDropdown"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Pilih Angkatan
                        </button>
                        <div class="dropdown-menu dropdown-menu-left" aria-labelledby="bkDropdown">
                            @foreach ($angkatanList as $a)
                                <a id="angkatan_{{ $a }}" class="dropdown-item" style="color: black"
                                    href="#" onclick="changeChartbk('{{ $a }}')">
                                    Angkatan {{ $a }}
                                </a>
                            @endforeach
                        </div>
                    </div>
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
    </div>
    <div class="row">
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Data Mahasiswa</h3>
                    <form action="{{ route('dosen.home', ['mahasiswa' => $mahasiswa ?? '']) }}" method="GET"
                        class="input-group justify-content-end">
                        <input type="text" class="form-control-sm" id="search" name="search" placeholder="Search"
                            value="{{ $search ?? '' }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <!-- Table -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 2%; text-align: center;">No</th>
                                <th style="width: 8%; text-align: center;">NIM</th>
                                <th style="width: 30%">Nama</th>
                                <th style="width: 5%">Status</th>
                                <th style="width: 2%; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($mahasiswaTable as $m)
                                <tr>
                                    <td style="text-align: center">{{ $no++ }}</td>
                                    <td>{{ $m->nim }}</td>
                                    <td>{{ $m->nama }}</td>
                                    <td>{{ $m->status }}</td>
                                    <td class="d-flex gap-3" style="text-align: center">
                                        <a class="btn btn-primary text-white" style="text-decoration: none"
                                            href="{{ route('dosen.datamahasiswa', ['nim' => $m->nim]) }}">Select</a>
                                        <button class="btn btn-warning text-white update-btn" style="text-decoration: none"
                                            data-nim="{{ $m->nim }}" data-status="{{ $m->status }}"
                                            data-bs-toggle="modal" data-bs-target="#updateModal">
                                            Update
                                        </button>
                                    </td>
                            @endforeach
                            </tr>
                        </tbody>
                    </table>
                    <div style="display:flex; justify-content: center; margin-top:20px">
                        {{ $mahasiswaTable->appends(['search' => $search])->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- <script>
        function changeChartcpl(selectedAngkatan) {
            console.log('Selected Angkatan:', selectedAngkatan);
            console.log('Generated URL:', "{{ route('admin.datadosen', ['nidn' => $nidn]) }}" + '/' + selectedAngkatan);

            // You can add additional logic here to update the chart or perform other actions
        }
    </script> --}}
    <script>
        var cplChart = $('#cplChart').get(0).getContext('2d');
        var initialData1 = {
            labels: <?php echo json_encode($chartData['labels_cpl']); ?>,
            datasets: [{
                data: <?php echo json_encode($chartData['data_cpl']); ?>,
                backgroundColor: [
                    '#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de',
                    '#ff5733', '#33ff57', '#5733ff', '#33b6ff', '#b633ff'
                ],
            }]
        };

        var cplOptions = {
            maintainAspectRatio: false,
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 4.0
                }
            }
        };

        var cplChartInstance = new Chart(cplChart, {
            type: 'bar',
            data: initialData1,
            options: cplOptions
        });

        // Hide the legend after creating the chart
        cplChartInstance.options.plugins.legend.display = false;
        cplChartInstance.update();

        function updateChartcpl(labels, data) {
            cplChartInstance.data.labels = labels;
            cplChartInstance.data.datasets[0].data = data;
            cplChartInstance.update();
        }

        function changeChartcpl(selectedAngkatan) {
            console.log('Selected Angkatan:', selectedAngkatan);
            console.log('Current selectedAngkatan:', selectedAngkatan);
            console.log('Generated URL:', "{{ route('dosen.home') }}" + '/' + selectedAngkatan);
            $('div.dropdown-menu a').removeClass('active');
            $('#angkatan_' + selectedAngkatan).addClass('active');
            $.ajax({
                url: "{{ route('dosen.home') }}/" + selectedAngkatan,
                method: 'GET',
                success: function(response) {
                    console.log('Data fetched successfully:', response);

                    if (response.labels_cpl && response.data_cpl) {
                        updateChartcpl(response.labels_cpl, response.data_cpl);
                    } else {
                        console.error('Invalid data received. Response:', response);
                    }
                },
                error: function(error) {
                    console.error('Error fetching data:', error);
                }
            });
        }
    </script>

    <script>
        var bkChart = $('#bkChart').get(0).getContext('2d');
        var initialData = {
            labels: <?php echo json_encode($chartData['labels_bk']); ?>,
            datasets: [{
                data: <?php echo json_encode($chartData['data_bk']); ?>,
                backgroundColor: [
                    '#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de', '#ff5733',
                    '#33ff57', '#5733ff', '#33b6ff', '#b633ff'
                ],
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

        // Create the chart without legend
        var bkChartInstance = new Chart(bkChart, {
            type: 'bar',
            data: initialData,
            options: bkOptions // Fix here: use bkOptions instead of cplOptions
        });

        // Hide the legend after creating the chart
        bkChartInstance.legend.options.display = false;
        bkChartInstance.update();

        // Function to update the chart
        function updateChartbk(labels, data) {
            bkChartInstance.data.labels = labels;
            bkChartInstance.data.datasets[0].data = data;
            bkChartInstance.update();
        }

        function changeChartbk(selectedAngkatan) {
            console.log('Selected Angkatan:', selectedAngkatan);
            console.log('Current selectedAngkatan:', selectedAngkatan);
            console.log('Generated URL:', "{{ route('dosen.home') }}" + '/' + selectedAngkatan);

            $('div.dropdown-menu a').removeClass('active');
            $('#angkatan_' + selectedAngkatan).addClass('active');
            $.ajax({
                url: "{{ route('dosen.home') }}/" + selectedAngkatan,
                method: 'GET',
                success: function(response) {
                    console.log('Data fetched successfully:', response);

                    // Check if the response contains valid data
                    if (response.labels_bk && response.data_bk) {
                        updateChartbk(response.labels_bk, response.data_bk);
                    } else {
                        console.error('Invalid data received. Response:', response);
                    }
                },
                error: function(error) {
                    console.error('Error fetching data:', error);
                }
            });
        }
    </script>
@endsection
