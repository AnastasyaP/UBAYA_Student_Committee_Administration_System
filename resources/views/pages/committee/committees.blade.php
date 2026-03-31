@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Kepanitiaan'])
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
                        <!-- <a href="{{ $activeCommittee ? '#' : route('committees.add') }}"
                            class="btn btn-dark btn-add w-15 mb-3 {{ $activeCommittee ? 'disabled' : '' }}">
                            Tambah Kepanitiaan
                        </a> -->
                        <a href="{{ route('committees.add') }}"
                            class="btn btn-dark btn-add w-15 mb-3">
                            Tambah Kepanitiaan
                        </a>
                        
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
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7" colspan=2>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($committees as $committee)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    <img src="{{ asset('storage/' . $committee->picture) }}" class="avatar avatar-sm me-3"
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
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-danger">Berakhir</span>
                                            @endif
                                        </td>
                                        @if($committee->is_active == 1)
                                        <td>       
                                            <a href="{{ route('committees.show', ['idCommittees'=> $committee->idCommittees, 'idCommittees' => $committee->idCommittees]) }}" 
                                            class="btn btn-warning btn-sm">Edit</a>                                
                                        </td>
                                        @else
                                        <td>
                                            <a href="javascript:;" class="btn btn-dark font-weight-bold text-xs"
                                                data-toggle="tooltip" data-original-title="Evaluationr">
                                                Lihat Evaluasi
                                            </a>
                                        </td>
                                        @endif
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
