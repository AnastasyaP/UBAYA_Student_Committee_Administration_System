@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Admin'])
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
                        <h6>Daftar Admin Kepanitiaan</h6>
                        <a href="{{ route('create.admin.super') }}" target=""
                            class="btn btn-dark btn-add ms-auto">Tambah Admin</a>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Nama</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Username</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Role</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Unit Penyelenggara</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Status</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7" colspan=2>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($admins as $admin)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    <img src="{{ $admin->picture ? asset('storage/' . $admin->picture) : asset('/img/profile-default.png') }}" class="avatar avatar-sm me-3"
                                                        alt="picture">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $admin->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $admin->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <h6 class="mb-0 text-sm">{{ $admin->username }}</h6>
                                        </td>
                                        <td>
                                            <h6 class="mb-0 text-sm">{{ $admin->role }}</h6>
                                        </td>
                                        <td>
                                            <h6 class="mb-0 text-sm">{{ $admin->organizer_unit }}</h6>
                                        </td>
                                        <td>
                                            @if ($admin->is_active == 1)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-danger">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <form action="{{ route('edit.admin.super', ['idAdmin' => $admin->idAdmins]) }}" method="GET">
                                                <button type="submit" class="btn btn-warning btn-sm">Edit</button>                                                
                                            </form>                                         
                                        </td>
                                         <td class="align-middle">
                                            <form action="{{ route('status.admin.super', ['idAdmin' => $admin->idAdmins]) }}" method="GET">
                                                @if($admin->is_active == 1)
                                                    <button type="submit" class="btn btn-danger btn-sm">Nonaktifkan</button>
                                                @else
                                                    <button type="submit" class="btn btn-primary btn-sm">Aktifkan</button>
                                                @endif
                                            </form>
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
        }, 3000); // auto close 3 detik
    </script>
@endsection
