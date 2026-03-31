@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Form Penilaian'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                @if(session('success'))
                    <div>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @elseif(session('warning'))
                    <div>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Warning!</strong> {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif
                <div class="card mb-4">
                    <form method="POST" action="">
                        @csrf
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center" >
                        <h6>Form Penilaian</h6>
                        <button class="btn btn-dark btn-sm ms-auto" type="submit">Simpan</button>
                        </div>
                        <div class="card-body">
                                
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0 text-center">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                Kriteria Interview</th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($criterias as $criteria)
                                        <tr>
                                            <td>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $criteria->kriteria }}</h6>
                                                    </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <input type="number" class="form-control">
                                                </div>
                                                
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <p>
                                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDetail" aria-expanded="false" aria-controls="collapseDetail">
                                        Detail Pendaftar
                                </button>
                            </p>
                                <div class="collapse" id="collapseDetail">
                                    <div class="card card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-control-label">Nama</label>
                                                <input type="text" class="form-control" id="name" value="{{ $mahasiswa->name }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-control-label">Divisi</label>
                                                    <input class="form-control" type="text" id="division" name="division"
                                                    value="{{ $divisionName->name }}" disabled>
                                            </div>
                                        </div>
                                        </div>
                                        
                                    </div>
                                </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
    <script>
        setTimeout(()=>{
            const alert = document.querySelector('.alert');
            if(alert){
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 3000); // auto close 3 detik
    </script>
@endsection
