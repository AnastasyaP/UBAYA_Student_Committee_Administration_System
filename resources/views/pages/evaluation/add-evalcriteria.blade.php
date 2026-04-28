@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Tambah Kriteria Evaluasi'])

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8">
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
                <div class="card">
                    <form method="POST" action="{{ route('evalcriteria.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Kriteria Evaluasi</p>
                                <button class="btn btn-primary btn-sm ms-auto" type="submit">Simpan</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Target Evaluasi</label>
                                        <select class="form-control" id="target_eval" name="target_eval">
                                            <option value="">-- Pilih Target Evaluasi --</option>
                                            @foreach ($masterTarget as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('target_eval')
                                           <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Divisi</label>
                                        <select class="form-control" id="target_divisi" name="target_divisi" disabled>
                                            <option value="">-- Pilih Target Divisi --</option>
                                            @foreach ($divisions as $value)
                                                <option value="{{ $value->idDivisions }}">{{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('target_divisi')
                                           <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Pilih Kriteria Evaluasi Yang Tersedia</label>
                                        <select class="form-control" id="master_eval" name="master_eval">
                                            <option value="">-- Pilih Kriteria Evaluasi --</option>
                                            @foreach ($criterias as $item)
                                                <option value="{{ $item->idEvaluationCriterias }}"
                                                    data-description="{{ $item->description }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('master_eval')
                                           <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                          <label class="form-control-label">Atau Tambah Kriteria Evaluasi Baru</label>
                                            <input class="form-control" type="text" id="eval_criteria" name="eval_criteria"
                                           placeholder="Masukkan Kriteria Evaluasi baru">
                                           @error('eval_criteria')
                                           <div class="text-danger small">{{ $message }}</div>
                                           @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                          <label class="form-control-label">Deskripsi</label>
                                          <textarea name="description" id="description" class="form-control"></textarea>
                                           @error('description')
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
        document.getElementById('target_eval').addEventListener('change', function(){
            const target = this.value;
            const divisionSelect = document.getElementById('target_divisi');

            if(target === 'division'){
                divisionSelect.disabled = false;
            }else{
                divisionSelect.disabled = true;
                divisionSelect.value = '';
            }
        });

        document.getElementById('master_eval').addEventListener('change', function(){
            const selectedOption = this.options[this.selectedIndex];
            const criteriaInput = document.getElementById('eval_criteria');
            const description = document.getElementById('description');

            if(this.value){
                criteriaInput.value = selectedOption.text;
                criteriaInput.readOnly = true;

                description.value = selectedOption.dataset.description;
            }else{
                criteriaInput.value = '';
                criteriaInput.readOnly = false;

                description.value = '';
            }
        });

        setTimeout(()=>{
            const alert = document.querySelector('.alert');
            if(alert){
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 3000); // auto close 3 detik
    </script>
@endsection
