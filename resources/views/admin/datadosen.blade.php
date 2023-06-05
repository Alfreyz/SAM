@extends('layouts.app')
@section('title', 'Data Dosen')
@section('content')

    <div class="row">
        <div class="col-md-6 mt-3">
            <!-- AREA CHART -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Per Group Capaian Pembelajaran</h3>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="danaChart"
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
        <div class="col-md-8 mt-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Data Mahasiswa</h3>
                    <!-- Search Field -->
                    <form action="{{ route('admin.datadosen', ['nidn' => $nidn ?? '']) }}" method="GET"
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
                                <th style="width: 20%; text-align: center;">NIM</th>
                                <th style="width: 2%; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = ($mahasiswa->currentPage() - 1) * $mahasiswa->perPage() + 1;
                            @endphp
                            @foreach ($mahasiswa as $m)
                                <tr>
                                    <td style="text-align: center">{{ $no++ }}</td>
                                    <td>{{ $m->nim }}</td>
                                    <td style="text-align: center">
                                        <a class="btn btn-primary text-white" style="text-decoration: none"
                                            href="{{ route('admin.adminmahasiswa', ['nim' => $m->nim]) }}">Select</a>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div style="display:flex; justify-content: center; margin-top:20px">
                        {{ $mahasiswa->links() }}
                        <!-- Render pagination links -->
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mt-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Best Mahasiswa</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <!-- Add your table content here -->
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
