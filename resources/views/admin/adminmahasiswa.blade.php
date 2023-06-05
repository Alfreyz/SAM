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
        <div class="col-md-12 mt-5">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Matakuliah</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 4%; text-align: center;">No</th>
                                <th style="width: 10%; text-align: center;">Kode Matakuliah</th>
                                <th style="width: 81%; text-align: center;">Nama Matakuliah</th>
                                <th style="width: 5%; text-align: center;">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = ($transkrip_mahasiswa->currentPage() - 1) * $transkrip_mahasiswa->perPage() + 1;
                            @endphp
                            @foreach ($transkrip_mahasiswa as $tm)
                                <tr>
                                    <td style="text-align: center">{{ $no++ }}</td>
                                    <td>{{ $tm->kode_matakuliah }}</td>
                                    <td style="text-align: center">{{ $tm->nilai }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection