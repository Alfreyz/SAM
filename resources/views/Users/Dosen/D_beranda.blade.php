@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-6 mt-5">
            <!-- AREA CHART -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Capaian Pembelajaran</h3>
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
                    <h3 class="card-title">Bahan Kajian</h3>
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
                    <h3 class="card-title">Data Mahasiswa</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 4%; text-align: center;">No</th>
                                <th style="width: 9%; text-align: center;">Nik</th>
                                <th style="width: 75%; text-align: center;">Nama</th>
                                <th style="width: 9%; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align: center">1.</td>
                                <td>9873219</td>
                                <td>Budi Sutedjo</td>
                                <td style="text-align: center"><a class="btn btn-primary text-white"
                                        style="text-decoration: none" href="A_datadosen">Select</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
