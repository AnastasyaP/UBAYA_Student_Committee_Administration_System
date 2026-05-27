@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Tambah Unit Penyelenggara'])

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
                @if(session('warning'))
                    <div>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Warning!</strong> {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif
                <div class="card">
                    <form method="POST" action="{{ route('store.unit.super') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Tambah Unit Penyelenggara</p>
                                <button class="btn btn-primary btn-sm ms-auto" type="submit">Simpan</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Pilih Level Unit</label>
                                        <select class="form-control" id="level" name="level">
                                            <option value="">-- Pilih Level --</option>
                                            @foreach ($levels as $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                          <label class="form-control-label">Nama Unit</label>
                                            <input class="form-control" type="text" id="name" name="name">
                                           @error('name')
                                           <div class="text-danger small">{{ $message }}</div>
                                           @enderror
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
@endsection
