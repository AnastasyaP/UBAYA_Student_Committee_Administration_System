@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Daftar Kepanitiaan'])
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
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center" >
                        <h6>Daftar Kepanitiaan</h6>                    
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Nama</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Periode Dimulai</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Periode Berakhir</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Mulai Pendaftaran</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Akhir Pendaftaran</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Status</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7" colspan=4>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($committees as $committee)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    <img src="{{ $committee->picture ? asset('storage/' . $committee->picture) : asset('/img/profile-default.png') }}" class="avatar avatar-sm me-3"
                                                        alt="committee picture">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $committee->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $committee->organizerName }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ $committee->start_period }}</span>
                                        </td>
                                           <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ $committee->end_period }}</span>
                                        </td>
                                           <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ $committee->start_regis }}</span>
                                        </td>
                                           <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ $committee->end_regis }}</span>
                                        </td>
                                        <td>
                                            @if($committee->is_active == 1)
                                                <span class="badge bg-success">Periode Berjalan</span>
                                            @else
                                                <span class="badge bg-danger">Periode Berakhir</span>
                                            @endif

                                            @if($committee->is_published === 1)
                                                <span class="badge bg-success">Terpublikasi</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <form action="{{ route('status.committee.super', ['idCommittee' => $committee->idCommittees]) }}" method="GET">
                                                @if($committee->is_active == 1)
                                                    <button type="submit" class="btn btn-danger btn-sm">Nonaktifkan</button>
                                                @else
                                                    <button type="submit" class="btn btn-primary btn-sm">Aktifkan</button>
                                                @endif
                                            </form>
                                        </td>
                                        <td>
                                            <a href="{{ route('detail.committee.super', ['idCommittee' => $committee->idCommittees]) }}" class="btn btn-dark font-weight-bold text-xs"
                                                data-toggle="tooltip" data-original-title="Evaluationr">
                                                Lihat Detail
                                            </a>
                                        </td>                                        
                                    </tr>
                                   @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
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
        }, 3000);
    </script>
@endsection
