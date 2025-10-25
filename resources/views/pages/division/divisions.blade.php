@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Division'])
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
                        <h6>Divisions List</h6>
                        @if($activeCommittee)
                        <a href="{{ route('divisions.add') }}" target=""
                            class="btn btn-dark btn-add w-15 mb-3">Add Division</a>
                        @endif
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Name</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Description</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Status</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7" colspan=2>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($divisions as $division)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    <img src="{{ asset('storage/' . $division->picture) }}" class="avatar avatar-sm me-3"
                                                        alt="division picture">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $division->name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <h6 class="mb-0 text-sm">{{ Str::limit($division->description, 30, '..') }}</h6>
                                        </td>
                                        <td>
                                            @if ($division->status == 1)
                                                <span class="badge bg-success">Open</span>
                                            @else
                                                <span class="badge bg-danger">Close</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <form action="{{ route('division.edit', ['idDivisions'=> $division->idDivisions, 'idCommittees' => $division->idCommittees]) }}" method="GET">
                                                <button type="submit" class="btn btn-warning btn-sm">Edit</button>                                                
                                            </form>                                         
                                        </td>
                                         <td class="align-middle">
                                            <form action="{{ route('division.destroy', ['idDivisions' => (int) $division->idDivisions, 'idCommittees' => (int) $division->idCommittees]) }}" method="POST" onsubmit="return confirm('Are you sure want to delete division {{ $division->name }} from this committee?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
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
