@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Edit Division'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <form action="#" enctype="multipart/form-data">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-control-label">Name</label>
                                        <input type="text" class="form-control" id="name" value="{{ $registration->name }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                          <label class="form-control-label">NRP</label>
                                            <input class="form-control" type="text" id="nrp" name="nrp"
                                            value="{{ $registration->nrp }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                          <label class="form-control-label">Email</label>
                                            <input class="form-control" type="email" id="email" name="email"
                                            value="{{ $registration->email }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                          <label class="form-control-label">Division</label>
                                            <input class="form-control" type="text" id="division" name="division"
                                            value="{{ $registration->division }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Percentage</label>
                                        <input class="form-control" type="text" id="percentage" name="percentage"
                                            value="{{ $registration->percentage }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">CV</label>
                                        <iframe
                                            src="/pdfjs/web/viewer.html?file={{ asset('storage/' . $registration->cv) }}"
                                            width="100%"
                                            height="500px">
                                        </iframe>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Portofolio</label>
                                        @if($registration->portofolio)
                                        <iframe
                                            src="/pdfjs/web/viewer.html?file={{ asset('storage/' . $registration->portofolio) }}"
                                            width="100%"
                                            height="500px">
                                        </iframe>
                                        @else
                                        <h6>No portofolio existed</h6>
                                        @endif
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
