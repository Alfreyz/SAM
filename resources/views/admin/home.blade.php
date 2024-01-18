@extends('layouts.app')
@section('title', 'Home')
@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Data Matakuliah</h3>
                    <div class="d-flex ml-auto">
                        <div class="mr-5">
                            <form method="POST" action="{{ route('admin.uploadmatakuliah') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="d-flex">
                                    <input type="file" class="form-control-file" id="fileUpload" name="fileUpload"
                                        style="width: auto;">
                                    <button type="submit" class="btn btn-primary"
                                        style="height: 31.5px; padding: 3px">Upload File</button>
                                </div>
                            </form>
                        </div>
                        <form action="{{ route('admin.home') }}" method="GET" class="input-group justify-content-end">
                            <input type="text" class="form-control-sm" id="search" name="search" placeholder="Search"
                                value="{{ $search ?? '' }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">Search</button>
                            </div>
                        </form>
                    </div>
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
                                <th style="width: 4%">Action</th>
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
                                    <td>
                                        <form
                                            action="{{ route('admin.deletematakuliah', ['kode_matakuliah' => $m->kode_matakuliah]) }}"
                                            method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </td>
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
        <div class="col-md-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Data Dosen</h3>
                    <button class="btn btn-success text-white add-btn ml-auto" style="text-decoration: none"
                        data-bs-toggle="modal" data-bs-target="#addModal">
                        Add Dosen
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 4%;">No</th>
                                <th style="width: 9%;">NIDN</th>
                                <th style="width: 75%;">Nama</th>
                                <th style="width: 2%; text-align: center;">Action</th>
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
                                    <td class="d-flex gap-3" style="text-align: center"><a
                                            class="btn btn-primary text-white" style="text-decoration: none"
                                            href="{{ route('admin.datadosen', ['nidn' => str_replace(["\r", "\n"], '', $d->nidn)]) }}">Select</a>
                                        <button class="btn btn-warning text-white update-btn" style="text-decoration: none"
                                            data-bs-toggle="modal" data-bs-target="#updateModal"
                                            data-nidn="{{ $d->nidn }}" data-nama_dosen="{{ $d->nama_dosen }}">
                                            Update
                                        </button>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Add Dosen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addForm" action="{{ route('admin.adddosen') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <label for="nidn" class="col-sm-2 col-form-label">NIDN</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="input_nidn" name="nidn">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="nama_dosen" class="col-sm-2 col-form-label">Nama Dosen</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="input_nama_dosen" name="nama_dosen">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="password" class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="input_password" name="password">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- update Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Add Dosen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addForm" action="{{ route('admin.updatenamadosen') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <label for="nidn" class="col-sm-2 col-form-label">NIDN</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nidn" name="nidn" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="nama_dosen" class="col-sm-2 col-form-label">Nama Dosen</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nama_dosen" name="nama_dosen">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="nama_dosen" class="col-sm-2 col-form-label">password</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="password" name="password">
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = new bootstrap.Modal(document.getElementById('updateModal'));
            $('.update-btn').on('click', function() {
                var nidn = $(this).data('nidn');
                var nama_dosen = $(this).data('nama_dosen');
                var password = $(this).data('password');
                $('#nidn').val(nidn);
                $('#nama_dosen').val(nama_dosen);
                $('#password').val(password);
                myModal.show();
            });
        });
    </script>

@endsection
