@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Registration'])
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
                        <h6>Registrations List</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Name</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            NRP</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Division</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Status</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7" colspan=3>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($registrations as $regis)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <!-- <div>
                                                    <img src="{{ asset('storage/' . $regis->cv) }}" class="avatar avatar-sm me-3"
                                                        alt="division picture">
                                                </div> -->
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $regis->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $regis->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <h6 class="mb-0 text-sm">{{ $regis->nrp }}</h6>
                                        </td>
                                        <td>
                                            <h6 class="mb-0 text-sm">{{ $regis->division }}</h6>
                                        </td>
                                        <td>
                                            @if ($regis->status == "pending")
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($regis->status == 'accepted')
                                                <span class="badge bg-success">Accepted</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <form action="{{ route('view.regis', ['idRegis' => $regis->idRegis]) }}" method="GET">
                                                <button type="submit" class="btn btn-warning btn-sm">Details</button>
                                            </form>
                                        </td>
                                        @if($regis->status == 'pending')
                                        <td class="align-middle">
                                            <form action="{{ route('accept.regis', ['idRegis' => $regis->idRegis]) }}" method="POST" onsubmit="return confirm('Are you sure want to Accept {{ $regis->name }}?');">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success btn-sm" value="accepted">Accept</button>                                                
                                            </form>                                         
                                        </td>
                                        <td class="align-middle">
                                            <form action="{{ route('reject.regis', ['idRegis' => $regis->idRegis]) }}" method="POST" onsubmit="return confirm('Are you sure want to Reject {{ $regis->name }}?');">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-danger btn-sm" value="rejected">Reject</button>
                                            </form>
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
        }, 3000); // auto close 3 detik
    </script>
@endsection
