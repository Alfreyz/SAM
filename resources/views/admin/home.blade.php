@extends('layouts.app')
@section('title', 'Home')
@section('content')
    <div class="row">
        <div class="col-md-3 mt-5">
            <div class="card card-primary">
                <div class="card-body">
                    <h5 class="card-title">Data Dosen</h5>
                </div>
            </div>
        </div>

        <div class="col-md-3 mt-5">
            <div class="card card-success">
                <div class="card-body">
                    <h5 class="card-title">Data Mahasiswa</h5>
                </div>
            </div>
        </div>

        <div class="col-md-3 mt-5">
            <div class="card card-info">
                <div class="card-body">
                    <h5 class="card-title">Data Matakuliah</h5>
                </div>
            </div>
        </div>

        <div class="col-md-3 mt-5">
            <div class="card card-warning">
                <div class="card-body">
                    <h5 class="card-title">Data Users</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mt-5">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Per Angkatan Capaian Pembelajaran</h3>
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
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Per Angkatan Bahan Kajian</h3>
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

    <div class="row">
        <div class="col-md-12 mt-5">
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
@endsection
