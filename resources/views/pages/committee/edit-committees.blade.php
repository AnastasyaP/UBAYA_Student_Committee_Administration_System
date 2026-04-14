@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Edit Kepanitiaan'])
    @foreach($committees as $committee)
    <div class="card shadow-lg mx-4 card-profile-bottom">
        <div class="card-body p-3">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        <img src="{{ $committee->picture ? asset('storage/' . $committee->picture) : asset('/img/profile-default.png') }}" alt="commitee picture" class="w-100 border-radius-lg shadow-sm">
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h1 class="mb-1">
                            {{ $committee->committee_name }}
                        </h1>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                    <div class="nav-wrapper position-relative end-0">
                        <ul class="nav nav-pills nav-fill p-1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 active d-flex align-items-center justify-content-center "
                                    data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="true">
                                    <i class="ni ni-app"></i>
                                    <span class="ms-2">App</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center "
                                    data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="false">
                                    <i class="ni ni-email-83"></i>
                                    <span class="ms-2">Messages</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center "
                                    data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="false">
                                    <i class="ni ni-settings-gear-65"></i>
                                    <span class="ms-2">Settings</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8">
                @if(session('warning'))
                    <div>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Warning!</strong> {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @elseif(session('success'))
                    <div>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif
                <div class="card">
                    <form action="{{ route('committees.update', ['idCommittees' => $committee->idCommittees]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <p class="mb-0">Profil Kepanitiaan</p>
                            <button class="btn btn-primary btn-sm ms-auto">Simpan</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-uppercase text-sm">Informasi Kontak</p>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Nama</label>
                                    <input class="form-control" type="text" name="name" value="{{ $committee->name }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Email</label>
                                    <input class="form-control" type="text" name="email" value="{{ $committee->email }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Kontak</label>
                                    <input class="form-control" type="text" name="contact" value="{{ $committee->contact }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label">Unit Penyelenggara</label>
                                    <input class="form-control" type="text" name="contact" value="{{ $committee->organizerName }}" disabled>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="horizontal dark">
                        <p class="text-uppercase text-sm">Detail Kepanitiaan</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Periode Dimulai</label>
                                    <!-- <input class="form-control" type="text" value="{{ \Carbon\Carbon::parse($committee->start_period)->format('d F Y') }}"> -->
                                    <input class="form-control" type="date" name="start_period" value="{{ $committee->start_period }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Periode Berakhir</label>
                                    <!-- <input class="form-control" type="text" value="{{ \Carbon\Carbon::parse($committee->end_period)->format('d F Y') }}"> -->
                                    <input class="form-control" type="date" name="end_period" value="{{ $committee->end_period }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Mulai Pendaftaran</label>
                                    <!-- <input class="form-control" type="text" value="{{ \Carbon\Carbon::parse($committee->start_regis)->format('d F Y') }}"> -->
                                    <input class="form-control" type="date" name="start_regis" value="{{ $committee->start_regis }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Akhir Pendaftaran</label>
                                    <!-- <input class="form-control" type="text" value="{{ \Carbon\Carbon::parse($committee->end_regis)->format('d F Y') }}"> -->
                                    <input class="form-control" type="date" name="end_regis" value="{{ $committee->end_regis }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Upload Poster</label>
                                            <div class="mb-3">
                                                <img src="{{ $committee->poster ? asset('storage/' . $committee->poster) : asset('/img/noimage.jpg') }}" alt="Preview picture" id="preview" class="img-fluid rounded" style="max-width:200px">
                                            </div>
                                            <input type="file" class="form-control" name="poster" id="poster" accept="image/*">
                                            <small class="text-muted">Format: JPG, JPEG, PNG</small>
                                            @error('poster')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label">Deskripsi</label>
                                    <textarea class="form-control" rows="5" name="description">{{ $committee->description }}</textarea>
                                </div>
                            </div>
                              <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label">Persyaratan</label>
                                    <textarea class="form-control" rows="5" name="requirement">{{ $committee->requirements }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Status Kepanitiaan</label>
                                        <select name="is_active" id="is_active" class="form-control">
                                            <option value="1" @selected($committee->is_active == 1)>Aktif</option>
                                            <option value="0" @selected($committee->is_active == 0)>Tidak Aktif</option>
                                        </select>
                                        @error('is_active')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                            </div>
                        </div>
                        <hr class="horizontal dark">
                        <p class="text-uppercase text-sm">Evaluasi</p>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label">Evaluasi</label>
                                    <textarea class="form-control" rows="5" name="evaluation">{{ $committee->evaluation }}</textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- <hr class="horizontal dark">
                        <p class="text-uppercase text-sm">About me</p>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">About me</label>
                                    <input class="form-control" type="text"
                                        value="A beautiful Dashboard for Bootstrap 5. It is Free and Open Source.">
                                </div>
                            </div>
                        </div>-->
                    </form>
                    
                </div>
            </div>
            <!-- <div class="col-md-4">
                <div class="card card-profile">
                    <img src="/img/bg-profile.jpg" alt="Image placeholder" class="card-img-top">
                    <div class="row justify-content-center">
                        <div class="col-4 col-lg-4 order-lg-2">
                            <div class="mt-n4 mt-lg-n6 mb-4 mb-lg-0">
                                <a href="javascript:;">
                                    <img src="/img/team-2.jpg"
                                        class="rounded-circle img-fluid border border-2 border-white">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-header text-center border-0 pt-0 pt-lg-2 pb-4 pb-lg-3">
                        <div class="d-flex justify-content-between">
                            <a href="javascript:;" class="btn btn-sm btn-info mb-0 d-none d-lg-block">Connect</a>
                            <a href="javascript:;" class="btn btn-sm btn-info mb-0 d-block d-lg-none"><i
                                    class="ni ni-collection"></i></a>
                            <a href="javascript:;"
                                class="btn btn-sm btn-dark float-right mb-0 d-none d-lg-block">Message</a>
                            <a href="javascript:;" class="btn btn-sm btn-dark float-right mb-0 d-block d-lg-none"><i
                                    class="ni ni-email-83"></i></a>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col">
                                <div class="d-flex justify-content-center">
                                    <div class="d-grid text-center">
                                        <span class="text-lg font-weight-bolder">22</span>
                                        <span class="text-sm opacity-8">Friends</span>
                                    </div>
                                    <div class="d-grid text-center mx-4">
                                        <span class="text-lg font-weight-bolder">10</span>
                                        <span class="text-sm opacity-8">Photos</span>
                                    </div>
                                    <div class="d-grid text-center">
                                        <span class="text-lg font-weight-bolder">89</span>
                                        <span class="text-sm opacity-8">Comments</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <h5>
                                Mark Davis<span class="font-weight-light">, 35</span>
                            </h5>
                            <div class="h6 font-weight-300">
                                <i class="ni location_pin mr-2"></i>Bucharest, Romania
                            </div>
                            <div class="h6 mt-4">
                                <i class="ni business_briefcase-24 mr-2"></i>Solution Manager - Creative Tim Officer
                            </div>
                            <div>
                                <i class="ni education_hat mr-2"></i>University of Computer Science
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    @endforeach
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

        document.getElementById('poster').addEventListener('change', function(){
            const preview = document.getElementById('preview');
            const file = event.target.files[0];

            if(file){
                const reader = new FileReader();
                reader.onload = function(e){
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }else{
                preview.src = "{{ asset('/img/noimage.jpg') }}";
                preview.style.display = 'none';
            }
        })
    </script>
@endsection
