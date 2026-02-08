@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Add Interview Criteria'])

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
                    <form method="POST" action="{{ route('intvcriteria.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Interview Criteria</p>
                                <button class="btn btn-primary btn-sm ms-auto" type="submit">Save</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                          <label class="form-control-label">Division</label>
                                            <input class="form-control" type="text" id="max_score" name="max_score" value="{{ $division->name }}" disabled>
                                            <input type="hidden" name="idDivision" value="{{ $division->idDivisions }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Select Existing AHP Criteria</label>
                                        <select class="form-control" id="master_ahp" name="master_ahp">
                                            <option value="">-- Choose Existing AHP Criteria --</option>
                                            @foreach ($masterAHPcriteria as $item)
                                                <option value="{{ $item->idAHPCriterias }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                          <label class="form-control-label">Or Add New AHP Criteria</label>
                                            <input class="form-control" type="text" id="ahp_criteria" name="ahp_criteria"
                                           placeholder="Enter new AHP Criteria name">
                                           @error('ahp_criteria')
                                           <div class="text-danger small">{{ $message }}</div>
                                           @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                          <label class="form-control-label">Question</label>
                                            <input class="form-control" type="text" id="question" name="question">
                                           @error('question')
                                           <div class="text-danger small">{{ $message }}</div>
                                           @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                          <label class="form-control-label">Maximum Score</label>
                                            <input class="form-control" type="number" id="max_score" name="max_score">
                                           @error('max_score')
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
        document.getElementById('master_ahp').addEventListener('change', function(){
            const selectedOption = this.options[this.selectedIndex]; // ambil value yg di pilih 
            const nameInput = document.getElementById('ahp_criteria'); // tempat untuk taruh valuenya

            if(this.value){
                nameInput.value = selectedOption.text;
                nameinput.setAttribute('disabled', true); // kalo milih dari combobox text inputnya di disable
            } else{
                nameInput.value = '';
                nameinput.removeAttribute('disabled');
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
