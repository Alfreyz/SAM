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
        {{-- Data Dosen --}}
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
                                        <button class="btn btn-warning text-white update-dosen-btn"
                                            style="text-decoration: none" data-bs-toggle="modal"
                                            data-bs-target="#updateModal" data-nidn="{{ $d->nidn }}"
                                            data-nama_dosen="{{ $d->nama_dosen }}">
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
        {{-- Matakuliah --}}
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
                                        style="height: 31.5px; padding: 3px">Upload
                                        File</button>
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
                                    <td class="d-flex gap-3">
                                        <button class="btn btn-warning text-white update-matakuliah-btn"
                                            style="text-decoration: none" data-bs-toggle="modal"
                                            data-bs-target="#updateModalmk"
                                            data-kode_matakuliah="{{ $m->kode_matakuliah }}"
                                            data-nama_matakuliah="{{ $m->nama_matakuliah }}"
                                            data-bahan_kajian="{{ $m->bahan_kajian }}" data-cpl="{{ $m->cpl }}"
                                            data-semester="{{ $m->semester }}">
                                            Update
                                        </button>
                                        <form class="delete-form" data-kode-matakuliah="{{ $m->kode_matakuliah }}"
                                            action="{{ route('admin.deletematakuliah', ['kode_matakuliah' => $m->kode_matakuliah]) }}"
                                            method="post">
                                            @csrf
                                            @method('DELETE')
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
    </div>
    <!-- update Modal -->
    <div class="modal fade" id="updateModalmk" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Matakuliah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateForm" action="{{ route('admin.updatematakuliah') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <label for="kode_matakuliah" class="col-sm-2 col-form-label">Kode Matakuliah</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="kode_matakuliah" name="kode_matakuliah"
                                    readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="nama_matakuliah" class="col-sm-2 col-form-label">Nama Matakuliah</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nama_matakuliah" name="nama_matakuliah">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="bahan_kajian" class="col-sm-2 col-form-label">Bahan Kajian</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="bahan_kajian" name="bahan_kajian">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="cpl" class="col-sm-2 col-form-label">CPL</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="cpl" name="cpl">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="semester" class="col-sm-2 col-form-label">Semester</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="semester" name="semester">
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var deleteForms = document.querySelectorAll('.delete-form');

            deleteForms.forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!confirm('Apakah Anda yakin ingin menghapus matakuliah ini?')) {
                        event.preventDefault();
                    } else {
                        try {
                            // Kirim formulir secara asynchronous menggunakan Fetch API
                            fetch(form.action, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': form.querySelector(
                                            'input[name="_token"]').value
                                    }
                                })
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Gagal menghapus matakuliah');
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    alert('Matakuliah berhasil dihapus!');
                                    form.closest('.matakuliah-item').remove();
                                })
                                .catch(error => {
                                    alert('Gagal menghapus matakuliah. Error: ' + error
                                        .message);
                                });
                        } catch (error) {
                            alert('Gagal menghapus matakuliah. Error: ' + error.message);
                        }
                    }
                });
            });
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = new bootstrap.Modal(document.getElementById('updateModal'));
            $('.update-dosen-btn').on('click', function() {
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = new bootstrap.Modal(document.getElementById('updateModalmk'));
            $('.update-matakuliah-btn').on('click', function() {
                var kode_matakuliah = $(this).data('kode_matakuliah');
                var nama_matakuliah = $(this).data('nama_matakuliah');
                var bahan_kajian = $(this).data('bahan_kajian');
                var cpl = $(this).data('cpl');
                var semester = $(this).data('semester');
                $('#kode_matakuliah').val(kode_matakuliah);
                $('#nama_matakuliah').val(nama_matakuliah);
                $('#bahan_kajian').val(bahan_kajian);
                $('#cpl').val(cpl);
                $('#semester').val(semester);
                myModal.show();
            });
        });
    </script>
@endsection
