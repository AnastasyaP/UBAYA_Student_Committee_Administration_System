@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Form Penilaian'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                @if(session('success'))
                        <div>
                            <div class="alert alert-success auto-close-alert alert-dismissible fade show" role="alert">
                                <strong>Success!</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    @elseif(session('warning'))
                        <div>
                            <div class="alert alert-warning auto-close-alert alert-dismissible fade show" role="alert">
                                <strong>Warning!</strong> {{ session('warning') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    @endif
                <div class="card mb-4">
                    <form method="POST" action="{{ route('intvscoring.score') }}" id="form-score">
                        @csrf
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center" >
                        <h6>Form Penilaian</h6>
                        <button id="btn-save" class="btn btn-dark btn-sm ms-auto" type="button" data-bs-toggle="modal" data-bs-target="#confirmSaveModal">Simpan</button>
                        </div>
                        <div class="card-body">
                                
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0 text-center">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                Kriteria Interview</th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($criterias as $criteria)
                                        <tr>
                                            <td>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $criteria->kriteria }}</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <input type="number" class="form-control intv-score" name="scores[{{ $loop->index }}][score]" value="{{ $criteria->score ?? 0 }}">
                                                    <input type="hidden" class="form-control intv-score" name="scores[{{ $loop->index }}][idInterviewCriteria]" value="{{ $criteria->idCriterias ?? 0 }}">
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Komentar</label>
                                        <textarea 
                                            class="form-control" 
                                            id="comment" 
                                            name="comment" 
                                            rows="4">
                                        {{ $criterias->first()->comment ?? ' ' }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <p>
                                <button class="btn btn-primary toggle-detail collapsed d-inline-flex align-items-center justify-content-center" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDetail" aria-expanded="false" aria-controls="collapseDetail">
                                        Detail Pendaftar <i class="ni ni-bold-right icon-toggle ms-2"></i>
                                </button>
                            </p>
                            <div class="collapse" id="collapseDetail">
                                <div class="card card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-control-label">Nama</label>
                                                <input type="text" class="form-control" id="name" value="{{ $mahasiswa->name }}" disabled>
                                                <input type="hidden" value="{{ $mahasiswa->idUser }}" id="idMahasiswa" name="idMahasiswa">
                                                <input type="hidden" value="{{ $mahasiswa->idRegis }}" name="idRegis">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-control-label">Divisi</label>
                                                    <input class="form-control" type="text" id="division" name="division"
                                                    value="{{ $divisionName->name }}" disabled>
                                                    <input type="hidden" value="{{ $divisionName->idDivisions }}" name="idDivision">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-control-label">Email</label>
                                                    <input class="form-control" type="email" id="email" name="email"
                                                    value="{{ $mahasiswa->email }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-control-label">Persentase</label>
                                                <input class="form-control" type="text" id="percentage" name="percentage"
                                                    value="{{ $mahasiswa->percentage }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-control-label">Motivasi</label>
                                                <textarea 
                                                    class="form-control" 
                                                    id="motivation" 
                                                    name="motivation" 
                                                    rows="4" 
                                                    disabled>{{ $mahasiswa->motivation }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-control-label">CV</label>
                                                <iframe
                                                    src="/pdfjs/web/viewer.html?file={{ asset('storage/' . $mahasiswa->cv) }}"
                                                    width="100%"
                                                    height="500px">
                                                </iframe>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-control-label">Portofolio</label>
                                                @if($mahasiswa->portofolio)
                                                <iframe
                                                    src="/pdfjs/web/viewer.html?file={{ asset('storage/' . $mahasiswa->portofolio) }}"
                                                    width="100%"
                                                    height="500px">
                                                </iframe>
                                                @else
                                                <h6>Tidak Ada Portofolio yang Tersedia</h6>
                                                @endif
                                            </div>
                                        </div>
                                    </div>  
                                </div>
                            </div>

                            <!-- confirm save modal  -->
                            <div class="modal fade" id="confirmSaveModal" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalTitle">Konfirmasi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            Apakah Anda yakin ingin menyimpan penilaian?<br>
                                            <strong>Data yang sudah disimpan tidak dapat diubah.</strong>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="button" class="btn btn-primary" id="confirmBtn">Ya, simpan</button>
                                        </div>
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
        document.getElementById('confirmBtn').addEventListener('click', function(){
            document.getElementById('form-score').submit();
        });

        setTimeout(()=>{
            document.querySelectorAll('.auto-close-alert').forEach(a => {
                new bootstrap.Alert(a).close();
            });
        }, 3000); // auto close 3 detik
    </script>
    
@endsection
