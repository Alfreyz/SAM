@extends('layouts.app')
@section('title', 'Data Dosen')
@section('content')

    <div class="alert alert-danger">
        <strong>Data Transkrip Kosong</strong>
    </div>
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
    <div class="bg-secondary p-3">
        <form action="{{ route('admin.uploadfiletm') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="d-flex">
                <input type="file" class="form-control-file" id="fileUpload" name="fileUpload" style="width: auto;">
                <button type="submit" class="btn btn-primary" style="height: 31.5px;">Upload File</button>
            </div>
        </form>
    </div>

@endsection
