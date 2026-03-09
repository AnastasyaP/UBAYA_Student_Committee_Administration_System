@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Add Division'])

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8">
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
                <div class="card">
                    <form method="POST" action="{{ route('division.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Member</p>
                                <button class="btn btn-primary btn-sm ms-auto" type="submit">Invite</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                          <label class="form-control-label">Division Name</label>
                                            <input class="form-control" type="text" id="division_name" name="name" value="{{ $division->division_name }}"
                                           disabled>
                                           <input type="hidden" name="idDivision" value="{{ $division->idDivision }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                          <label class="form-control-label">Email Member</label>
                                            <input class="form-control" type="text" id="email" name="email">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Position</label>
                                        <select name="position" id="position" class="form-control">
                                            <option value="bph">BPH</option>
                                            <option value="koor">Koor</option>
                                            <option value="wakoor">Wakoor</option>
                                            <option value="anggota">Anggota</option>
                                        </select>
                                        @error('position')
                                        <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-profile">
                    <div class="card">
                        <form method="POST" action="#" enctype="multipart/form-data">
                            @csrf
                            <div class="card-header pb-0">
                                <div class="d-flex align-items-center">
                                    <p class="mb-0">Pending Invitation</p>
                                </div>
                            </div>
                            <div class="card mb-4">
                        
                                <div class="card-body px-0 pt-0 pb-2">
                                    <div class="table-responsive p-0">
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        Name</th>
                                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        Division</th>
                                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        Position</th>
                                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7" colspan=3>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                        
                                                <tr>
                                                    <td>
                                                        <div class="d-flex px-2 py-1">
                                        
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="mb-0 text-sm"></h6>
                                                                <p class="text-xs text-secondary mb-0"></p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0 text-sm"></h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0 text-sm position-label"></h6>
                                                    </td>
                                            </tbody>
                                        </table>
                                        
                                    </div>    
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
