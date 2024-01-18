@extends('layouts.app')
@section('title', 'Relasi BK Dan CPL')
@section('back-button')
    <a href="{{ route('admin.home') }}">- Home</a>
@endsection
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
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Bahan Kajian</h3>
                    @if (auth()->user()->role == 'admin')
                        <div class="d-flex ml-auto">
                            <div class="mr-5">
                                <form action="{{ route('upload.bk') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="d-flex">
                                        <input type="file" class="form-control-file" id="fileUpload" name="fileUpload"
                                            style="width: auto;">
                                        <button type="submit" class="btn btn-primary"
                                            style="height: 31.5px; padding: 3px">Upload File</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Deskripsi</th>
                                @if (auth()->user()->role == 'admin')
                                    <th class="text-center" style="width: 2%">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bkall as $bk)
                                <tr>
                                    <td>{{ $bk->kode_bk }}</td>
                                    <td>{{ $bk->nama_bk }}</td>
                                    @if (auth()->user()->role == 'admin')
                                        <td class="text-center">
                                            <form action="{{ route('delete.bk', ['id' => $bk->id]) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div style="display:flex; justify-content: center; margin-top:20px">
                        {{ $bkall->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">CPL</h3>
                    @if (auth()->user()->role == 'admin')
                        <div class="d-flex ml-auto">
                            <div class="mr-5">
                                <form action="{{ route('upload.cpl') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="d-flex">
                                        <input type="file" class="form-control-file" id="fileUpload" name="fileUpload"
                                            style="width: auto;">
                                        <button type="submit" class="btn btn-primary"
                                            style="height: 31.5px; padding: 3px">Upload File</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Deskripsi</th>
                                @if (auth()->user()->role == 'admin')
                                    <th class="text-center">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cplall as $cpl)
                                <tr>
                                    <td>{{ $cpl->kode_cpl }}</td>
                                    <td>{{ $cpl->nama_cpl }}</td>
                                    @if (auth()->user()->role == 'admin')
                                        <td class="text-center">
                                            <form action="{{ route('delete.cpl', ['id' => $cpl->id]) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div style="display:flex; justify-content: center; margin-top:20px">
                        {{ $cplall->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Relasi BK dan CPL</h3>
                    @if (auth()->user()->role == 'admin')
                        <form action="{{ route('upload.bk_cpl') }}" method="post" class="ml-auto"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="d-flex">
                                <input type="file" class="form-control-file" id="fileUpload" name="fileUpload"
                                    style="width: auto;">
                                <button type="submit" class="btn btn-primary" style="height: 31.5px; padding: 3px">Upload
                                    File</button>
                            </div>
                        </form>
                        <form class="ml-2" action="{{ route('reset.relasi') }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Apakah Anda yakin?')">Reset Relasi</button>
                        </form>
                    @endif
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th></th> {{-- Kolom kosong di sudut kiri atas --}}
                                @foreach ($bkCodes as $bkCode)
                                    <th>{{ $bkCode }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cplCodes as $cplCode)
                                <tr>
                                    <td>{{ $cplCode }}</td>
                                    @foreach ($bkCodes as $bkCode)
                                        @php
                                            $count = $cplData[$cplCode][$bkCode];
                                        @endphp
                                        <td class="text-center">
                                            @if ($count > 0)
                                                {{ $count }}
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div style="display:flex; justify-content: center; margin-top:20px">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
