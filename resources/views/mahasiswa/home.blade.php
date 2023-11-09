@extends('layouts.app')
@section('title', 'Data Mahasiswa')
@section('content')
    <div class="row">
        <div class="col-md-6 mt-5">
            <!-- AREA CHART -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Per Individu Capaian Pembelajaran</h3>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="danaChart"
                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mt-5">
            <!-- AREA CHART -->
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Per Individu Bahan Kajian</h3>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="prokerChart"
                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- TABLE -->
    <div class="row">
        <div class="col-md-6 mt-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Matakuliah</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10%;">Kode Matakuliah</th>
                                <th style="width: 10%;">Nama Matakuliah</th>
                                <th style="width: 2%; text-align: center;">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transkrip_mahasiswa as $data)
                                <tr>
                                    <td>{{ $data->kode_matakuliah }}</td>
                                    <td>{{ $data->nama_matakuliah }}</td>
                                    <td class="text-center">{{ $data->nilai }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $transkrip_mahasiswa->appends(request()->query())->links() }}

                </div>
            </div>
        </div>

        <div class="col-md-6 mt-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Grafik Nilai Matakuliah</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <!-- Table content -->
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
