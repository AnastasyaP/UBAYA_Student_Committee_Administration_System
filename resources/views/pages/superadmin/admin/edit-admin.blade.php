@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Edit Admin Kepanitiaan'])

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
                    <form method="POST" action="{{ route('update.admin.super') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Edit Admin Kepanitiaan</p>
                                <button class="btn btn-primary btn-sm ms-auto" type="submit">Simpan</button>
                                <input type="hidden" name="idAdmin" value="{{ $idAdmin }}">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Pilih Unit Penyelenggara</label>
                                        <select class="form-control" id="organizer_unit" name="organizer_unit">
                                            <option value="">-- Pilih Unit Penyelenggara --</option>
                                            @foreach ($organizerUnits as $unit)
                                                <option value="{{ $unit->idOrganizerUnits }}" @selected($admin->idOrganizerUnits == $unit->idOrganizerUnits)>{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                          <label class="form-control-label">Nama Depan</label>
                                            <input class="form-control" type="text" id="firstname" name="firstname" value="{{ $admin->firstname }}">
                                           @error('firstname')
                                           <div class="text-danger small">{{ $message }}</div>
                                           @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                          <label class="form-control-label">Nama Belakang</label>
                                            <input class="form-control" type="text" id="lastname" name="lastname" value="{{ $admin->lastname }}">
                                           @error('lastname')
                                           <div class="text-danger small">{{ $message }}</div>
                                           @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                          <label class="form-control-label">Username</label>
                                            <input class="form-control" type="text" id="username" name="username" value="{{ $admin->username }}">
                                           @error('username')
                                           <div class="text-danger small">{{ $message }}</div>
                                           @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                          <label class="form-control-label">Email</label>
                                            <input class="form-control" type="text" id="email" name="email" value="{{ $admin->email }}">
                                           @error('email')
                                           <div class="text-danger small">{{ $message }}</div>
                                           @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Upload Gambar</label>
                                        <div class="mb-3">
                                            <img src="{{ $admin->picture ? asset('storage/' . $admin->picture) : asset('/img/noimage.jpg') }}" alt="Preview picture" id="preview" class="img-fluid rounded" style="max-width:200px">
                                        </div>
                                        <input type="file" class="form-control" name="picture" id="picture" accept="image/*">
                                        <small class="text-muted">Format: JPG, JPEG, PNG</small>
                                        @error('picture')
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
    <script>
        document.getElementById('picture').addEventListener('change', function(){
            const preview = document.getElementById('preview');
            const file = this.files[0];

            if(file){
                const reader = new FileReader();
                reader.onload = function (e){
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }else{
                    preview.src = "{{ asset('assets_lp/img/noimage.jpg') }}";
                    preview.style.display = 'none';
            }
        })
    </script>
@endsection
