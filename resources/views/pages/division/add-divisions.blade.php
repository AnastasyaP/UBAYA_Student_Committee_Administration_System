@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Add Division'])

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
                @endif
                <div class="card">
                    <form method="POST" action="{{ route('division.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Division</p>
                                <button class="btn btn-primary btn-sm ms-auto" type="submit">Save</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Select Existing Division</label>
                                        <select class="form-control" id="master_division" name="master_division">
                                            <option value="">-- Choose Existing Division --</option>
                                            @foreach ($masterDivisions as $division)
                                                <option value="{{ $division->idDivisions }}">{{ $division->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                          <label class="form-control-label">Or Add New Division Name</label>
                                            <input class="form-control" type="text" id="division_name" name="name"
                                           placeholder="Enter new division name">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Upload Picture</label>
                                        <div class="mb-3">
                                            <img src="#" alt="Preview picture" id="preview" class="img-fluid rounded" style="max-width:200px">
                                        </div>
                                        <input type="file" class="form-control" name="picture" id="picture" accept="image/*">
                                        <small class="text-muted">Format: JPG, JPEG, PNG</small>
                                        
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Description</label>
                                        <textarea class="form-control" rows="5" name="description"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Open for Recruitment?</label>
                                        <select name="is_open" id="is_open" class="form-control">
                                            <option value=0>No</option>
                                            <option value=1>Yes</option>
                                        </select>

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
        document.getElementById('master_division').addEventListener('change', function(){
            const selectedOption = this.options[this.selectedIndex]; // ambil value yg di pilih 
            const nameInput = document.getElementById('division_name'); // tempat untuk taruh valuenya

            if(this.value){
                nameInput.value = selectedOption.text;
                nameinput.setAttribute('disabled', true); // kalo milih dari combobox text inputnya di disable
            } else{
                nameInput.value = '';
                nameinput.removeAttribute('disabled');
            }
        });

        document.getElementById('picture').addEventListener('change', function(){
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
                  preview.src = "#";
                    preview.style.display = 'none';
            }
        })
    </script>
@endsection
