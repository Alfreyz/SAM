@extends('layouts.app')
@section('title', 'Home')
@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Data Matakuliah</h3>
                    <!-- Search Field -->
                    <form action="{{ route('admin.home') }}" method="GET" class="input-group justify-content-end">
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
                                <th>Bahan Kajian</th>
                                <th>CPL</th>
                                <th style="width: 16%;">Semester</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($matakuliah as $m)
                                <tr>
                                    <td>{{ $m->kode_matakuliah }}</td>
                                    <td>{{ $m->nama_matakuliah }}</td>
                                    <td>{{ $m->bahan_kajian }}</td>
                                    <td>{{ $m->cpl }}</td>
                                    <td align="center">{{ $m->semester }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div style="display:flex; justify-content: center; margin-top:20px">
                        {{ $matakuliah->appends(['search' => $search, 'page_matakuliah' => $matakuliah->currentPage()])->links('pagination::bootstrap-4') }}
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
                    <div style="display:flex; justify-content: center; margin-top:20px">
                        {{ $dosen->appends(['search' => $search, 'page_dosen' => $dosen->currentPage()])->links('pagination::bootstrap-4', ['paginator' => 'dosen']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
