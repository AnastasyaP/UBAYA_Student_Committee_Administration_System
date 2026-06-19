@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Tambah Kriteria Evaluasi'])

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">
                                            Pilih Deskripsi Yang Sudah Ada
                                        </label>

                                        <select class="form-control" id="master_description" name="master_description" disabled>
                                            <option value="">-- Pilih Deskripsi --</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                          <label class="form-control-label">Atau Tambah Deskripsi Baru</label>
                                          <textarea name="description" id="description" class="form-control" placeholder="Masukkan Deskripsi Baru"></textarea>
                                           @error('description')
                                           <div class="text-danger small">{{ $message }}</div>
                                           @enderror
                                    </div>
                                </div>
                                <input type="hidden" id="selected_criteria_id" name="selected_criteria_id">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
    <script>
        const evaluationData = @json($allEvaluationData);

        document.getElementById('target_eval').addEventListener('change', function(){
            const target = this.value;
            const divisionSelect = document.getElementById('target_divisi');
            const criteriaSelect = document.getElementById('master_eval');

            if(target === 'division'){
                divisionSelect.disabled = false;
            }else{
                divisionSelect.disabled = true;
                divisionSelect.value = '';
            }

            // reset combobox kriteria
            criteriaSelect.innerHTML =
                '<option value="">-- Pilih Kriteria Evaluasi --</option>';

            // reset field lain
            document.getElementById('eval_criteria').value = '';
            document.getElementById('eval_criteria').readOnly = false;

            document.getElementById('master_description').innerHTML =
                '<option value="">-- Pilih Deskripsi --</option>';

            document.getElementById('master_description').disabled = true;

            document.getElementById('description').value = '';
            document.getElementById('selected_criteria_id').value = '';

            // ambil nama kriteria unik
            let added = [];

            evaluationData.forEach(function(item){

                if(
                    item.target_type === target &&
                    !added.includes(item.name)
                ){
                    added.push(item.name);

                    criteriaSelect.innerHTML += `
                        <option value="${item.name}">
                            ${item.name}
                        </option>
                    `;
                }
            });
        });

        document.getElementById('master_eval').addEventListener('change', function(){
            const criteria = this.value;
            const descSelect = document.getElementById('master_description');
            const criteriaInput = document.getElementById('eval_criteria');
            const target = document.getElementById('target_eval').value;

            if(criteria != ""){
                criteriaInput.value = criteria;
                criteriaInput.readOnly = true;
            }else{
                criteriaInput.value = "";
                criteriaInput.readOnly = false;
            }

            descSelect.innerHTML =
                '<option value="">-- Pilih Deskripsi --</option>';

            let found = false;

            evaluationData.forEach(function(item){

                if(item.name === criteria && item.target_type === target){
                    found = true;

                    descSelect.innerHTML += `
                        <option
                            value="${item.idEvaluationCriterias}"
                            data-description="${item.description}">
                            ${item.description}
                        </option>
                    `;
                }
            });

            if(found){
                descSelect.disabled = false;
            }else{
                descSelect.disabled = true;
                descSelect.selectedIndex = 0;

                document.getElementById('description').value = '';
                document.getElementById('selected_criteria_id').value = '';
            }
        });

        document.getElementById('master_description').addEventListener('change', function(){

            const option = this.options[this.selectedIndex];

            document.getElementById('eval_criteria').value = document.getElementById('master_eval').value;

            document.getElementById('description').value = option.dataset.description ?? '';

            document.getElementById('selected_criteria_id').value = option.value;
        });

        document.getElementById('eval_criteria').addEventListener('input', function(){

            if(this.readOnly == false){
                document.getElementById('master_eval').value = "";
                document.getElementById('master_description').innerHTML =
                    '<option value="">-- Pilih Deskripsi --</option>';
                document.getElementById('master_description').disabled = true;
                document.getElementById('selected_criteria_id').value = "";
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
