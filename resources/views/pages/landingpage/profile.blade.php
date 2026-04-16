@extends('layouts.main')

@section('title', 'Profile')
@section('content')
    @if(session('success'))
      <div class="flash-message success">
        {{ session('success') }}
      </div>
    @endif

    @if(session('warning'))
      <div class="flash-message warning">
        {{ session('warning') }}
      </div>
    @endif

    @if(session('error'))
      <div class="flash-message error">
        {{ session('error') }}
      </div>
    @endif

    @if ($errors->any())  
      <div class="flash-message error">
        {{ $errors->first() }}
      </div>
    @endif
    <!-- Page Title -->
    <div class="page-title dark-background" data-aos="fade" style="background-image: url(assets/img/page-title-bg.jpg);">
      <div class="container position-relative">
        <h1>Profil</h1>
        <p>Kelola dan perbarui informasi profilmu untuk memastikan data tetap akurat dan terbaru</p>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="{{  route('home') }}">Beranda</a></li>
            <li class="current">Profil</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Contact Section -->
    <section id="contact" class="contact section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">


        <div class="row gy-4">

          <div class="col-lg-4 d-flex justify-content-center">
            <div class="profile-wrapper">
              <div class="profile-img-container">
                <img src="{{ $profile->picture ? asset('storage/' . $profile->picture) : asset('/img/profile-default.png') }}" alt="profile picture">
              </div>
              
              <button class="edit-btn" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                <i class="fas fa-pen"></i>
              </button>
            </div>
          </div>

          <div class="col-lg-8">
            <form action="{{ route('lp.save.files') }}" method="POST" enctype="multipart/form-data" data-aos="fade-up" data-aos-delay="200">
              @csrf
              @method('PUT')
              <div class="row gy-4">

                <div class="col-md-6">
                  <h6>NRP</h6>
                  <input type="text" name="nrp" class="form-control" placeholder="NRP" required="" value="{{ $profile->nrp }}" disabled>
                </div>

                <div class="col-md-6 ">
                  <h6>Email</h6>
                  <input type="text" class="form-control" name="email" placeholder="Email" required="" value="{{ $profile->email }}" disabled>
                </div>

                <div class="col-md-6 ">
                  <h6>Nama Depan</h6>
                  <input type="text" class="form-control" name="firstname" placeholder="Nama Depan" required="" value="{{ $profile->firstname }}" disabled>
                </div>

                <div class="col-md-6 ">
                  <h6>Nama Belakang</h6>
                  <input type="text" class="form-control" name="lastname" placeholder="Nama Belakang" required="" value="{{ $profile->lastname }}" disabled>
                </div>

                <div class="col-md-12">
                  <h6>Ubah Password</h6>
                  <div class="password-wrapper">
                    <input type="password" class="form-control" value="12345678" disabled>
                    <button type="button" class="edit-pass-btn" data-bs-toggle="modal" data-bs-target="#editPwdModal">
                      <i class="fas fa-pen"></i>
                    </button>
                  </div>
                </div>

                <div class="col-md-6">
                  <h6>CV</h6>
                  <input type="file" class="form-control" name="cv">
                   @if($profile->cv)
                      <small class="text-success">
                        File saat ini: 
                        <a href="{{ asset('storage/' . $profile->cv) }}" target="_blank">
                          Lihat CV
                        </a>
                      </small>
                    @endif
                </div>

                <div class="col-md-6">
                  <h6>Portofolio</h6>
                  <input type="file" class="form-control" name="portofolio">
                   @if($profile->portofolio)
                      <small class="text-success">
                        File saat ini: 
                        <a href="{{ asset('storage/' . $profile->portofolio) }}" target="_blank">
                          Lihat Portofolio
                        </a>
                      </small>
                    @endif
                </div>

                <div class="col-md-12 text-center">
                  <button type="submit" class="btn-save">Simpan File</button>
                </div>

              </div>
            </form>
          </div><!-- End Contact Form -->

        </div>

      </div>

      <!-- modal edit profile picture -->
      <form action="{{ route('lp.profile.edit') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal fade" id="editProfileModal" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              
              <div class="modal-header">
                <h5 class="modal-title">Edit Foto Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>

              <div class="modal-body">
                @if ($errors->has('picture'))
                  <div class="alert alert-danger">
                    {{ $errors->first('picture') }}
                  </div>
                @endif
                <div class="mb-3">
                  <img src="{{ asset('/img/noimage.jpg') }}" alt="Preview picture" id="preview" class="img-fluid rounded" style="max-width:200px">
                </div>
                <input type="file" class="form-control" name="picture" id="picture" accept="image/*">
                <small class="text-muted">Format: JPG, JPEG, PNG, Maks. 2MB</small>                                        
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
              </div>

            </div>
          </div>
        </div>
      </form>
            
      <!-- modal edit password -->
      <form action="{{ route('lp.pwd.change') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="modal fade" id="editPwdModal" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              
              <div class="modal-header">
                <h5 class="modal-title">Edit Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>

              <div class="modal-body">
                @if ($errors->has('old_pwd') || $errors->has('new_password') || $errors->has('confirm_password'))
                  <div class="alert alert-danger">
                    {{ $errors->first() }}
                  </div>
                @endif
                <div class="mb-3">
                  <h6>Password Lama</h6>
                  <input type="password" class="form-control" name="old_pwd" placeholder="Masukkan password lama">
                </div>
                <div class="mb-3">
                  <h6>Password Baru</h6>
                  <input type="password" class="form-control" name="new_password" placeholder="Masukkan password baru">
                </div>
                <div class="mb-3">
                  <h6>Konfirmasi Ulang</h6>
                  <input type="password" class="form-control" name="confirm_password" placeholder="Ketikan ulang password baru">
                </div>        
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
              </div>

            </div>
          </div>
        </div>
      </form>
    </div>

    </section><!-- /Contact Section -->
    @if ($errors->has('picture'))
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        var myModal = new bootstrap.Modal(document.getElementById('editProfileModal'));
        myModal.show();
      });

      setTimeout(()=>{
            const alert = document.querySelector('.alert');
            if(alert){
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 3000); // auto close 3 detik
    </script>
    @endif

    @if ($errors->has('old_pwd') || $errors->has('new_password') || $errors->has('confirm_password'))
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        var myModal = new bootstrap.Modal(document.getElementById('editPwdModal'));
        myModal.show();
      });

      setTimeout(()=>{
            const alert = document.querySelector('.alert');
            if(alert){
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 3000); // auto close 3 detik
    </script>
    @endif

    <script>
      document.getElementById('picture').addEventListener('change', function(event){
            const preview = document.getElementById('preview');
            const file = event.target.files[0];

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