@extends('layouts.app')
@section('title', 'Home')
@section('content')
    <div class="row">
        <div class="col-md-3 mt-5">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title">Data Dosen</h5>
                        <p class="card-text" style="font-size: 18px;">{{ $dosenCount }}</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="bg-primary rounded-circle d-flex justify-content-center align-items-center"
                            style="width: 80px; height: 80px; opacity: 0.7;">
                            <i class="fas fa-users fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mt-5">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title">Data Mahasiswa</h5>
                        <p class="card-text" style="font-size: 18px;">{{ $mahasiswaCount }}</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="bg-success rounded-circle d-flex justify-content-center align-items-center"
                            style="width: 80px; height: 80px; opacity: 0.7;">
                            <i class="fas fa-user-graduate fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mt-5">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title">Data Matakuliah</h5>
                        <p class="card-text" style="font-size: 18px;">{{ $matakuliahCount }}</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="bg-info rounded-circle d-flex justify-content-center align-items-center"
                            style="width: 80px; height: 80px; opacity: 0.7;">
                            <i class="fas fa-book fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mt-5">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title">Data Users</h5>
                        <p class="card-text" style="font-size: 18px;">{{ $usersCount }}</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="bg-warning rounded-circle d-flex justify-content-center align-items-center"
                            style="width: 80px; height: 80px; opacity: 0.7;">
                            <i class="fas fa-users-cog fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7 mt-5">
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
                                <th style="width: 75%;">Semester</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($matakuliah as $m)
                                <tr>
                                    <td>{{ $m->kode_matakuliah }}</td>
                                    <td>{{ $m->nama_matakuliah }}</td>
                                    <td>{{ $m->semester }}</td>
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

        <div class="col-md-5 mt-5">
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
