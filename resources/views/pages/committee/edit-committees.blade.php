@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Edit Kepanitiaan'])
    @foreach($committees as $committee)
    <div class="card shadow-lg mx-4">
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
            </div>
        </div>
    </div>
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
                        <div class="row">
                            <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Pilih Nama Umum Yang Tersedia</label>
                                        <select class="form-control" id="master_committee" name="master_committee">
                                            <option value="">-- Pilih  Nama Umum --</option>
                                            @foreach ($master_committee as $item)
                                                <option value="{{ $item }}" @selected($item == $committee->committee_name)>{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                          <label class="form-control-label">Atau Tambah Nama Umum Baru</label>
                                            <input class="form-control" type="text" id="committee_name" name="committee_name"
                                           placeholder="Masukkan nama umum baru" value="{{ $committee->committee_name }}">
                                           @error('committee_name')
                                           <div class="text-danger small">{{ $message }}</div>
                                           @enderror
                                    </div>
                                </div>
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
                                    <label class="form-control-label">Batas Pengisian Evaluasi</label>
                                    <!-- <input class="form-control" type="text" value="{{ \Carbon\Carbon::parse($committee->end_regis)->format('d F Y') }}"> -->
                                    <input class="form-control" type="date" name="end_eval" value="{{ $committee->end_evaluation }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Upload Profil</label>
                                            <div class="mb-3">
                                                <img src="{{ $committee->picture ? asset('storage/' . $committee->picture) : asset('/img/noimage.jpg') }}" alt="Preview picture" id="preview_picture" class="img-fluid rounded" style="max-width:200px">
                                            </div>
                                            <input type="file" class="form-control" name="picture" id="picture" accept="image/*">
                                            <small class="text-muted">Format: JPG, JPEG, PNG</small>
                                            @error('picture')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                            </div>
                            <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Upload Poster</label>
                                            <div class="mb-3">
                                                <img src="{{ $committee->poster ? asset('storage/' . $committee->poster) : asset('/img/noimage.jpg') }}" alt="Preview poster" id="preview_poster" class="img-fluid rounded" style="max-width:200px">
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
                    </form>
                </div>
            </div>
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
            const preview = document.getElementById('preview_poster');
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

        document.getElementById('picture').addEventListener('change', function(){
            const preview = document.getElementById('preview_picture');
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
