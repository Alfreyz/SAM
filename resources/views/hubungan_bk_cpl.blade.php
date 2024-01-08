@extends('layouts.app')
@section('title', 'Relasi BK Dan CPL')
@section('back-button')
    <a href="{{ route('admin.home') }}">- Home</a>
@endsection
@section('content')

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    BK
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bkall as $bk)
                                <tr>
                                    <td>{{ $bk->kode_bk }}</td>
                                    <td>{{ $bk->nama_bk }}</td>
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
                <div class="card-header">
                    CPL
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cplall as $cpl)
                                <tr>
                                    <td>{{ $cpl->kode_cpl }}</td>
                                    <td>{{ $cpl->nama_cpl }}</td>
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
