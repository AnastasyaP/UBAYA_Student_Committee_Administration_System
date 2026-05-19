@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Edit Divisi'])
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
                    <form method="POST" action="{{ route('division.update', ['idDivisions' => $division->idDivisions, 'idCommittees' => $division->idCommittees]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Divisi</p>
                                <button class="btn btn-primary btn-sm ms-auto" type="submit">Simpan</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Pilih Divisi yang Tersedia</label>
                                        <select class="form-control" id="master_division" name="master_division" disabled>
                                            <option value="">-- Pilih Divisi --</option>
                                            @foreach($masterDivisions as $master)
                                            <option value="{{ $master->idDivisions }}" @selected($division->idDivisions == $master->idDivisions)>
                                                {{ $master->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                          <label class="form-control-label">Atau Tambah Divisi Baru</label>
                                            <input class="form-control" type="text" id="division_name" name="name"
                                           placeholder="Enter new division name" value="{{ $division->name }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Upload Gambar</label>
                                        <div class="mb-3">
                                            <img src="{{ asset('storage/' . $division->picture) }}" alt="Preview picture" id="preview" class="img-fluid rounded" style="max-width:200px">
                                        </div>
                                        <input type="file" class="form-control" name="picture" id="picture" accept="image/*">
                                        <small class="text-muted">Format: JPG, JPEG, PNG</small>
                                        
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Deskripsi</label>
                                        <textarea class="form-control" rows="5" name="description">{{ $division->description }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                          <label class="form-control-label">Jumlah Maksimal Anggota</label>
                                            <input class="form-control" type="number" id="num_member" name="num_member" value="{{ $division->num_member }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Status Pendaftaran</label>
                                        <select name="is_open" id="is_open" class="form-control">
                                            <option value=0 @selected($division->is_open == 0)>Tidak Buka</option>
                                            <option value=1 @selected($division->is_open == 1)>Buka</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Keywords</label>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <select class="form-control" id="master_keyword">
                                                    <option value="">-- Pilih Keyword --</option>
                                                    @foreach ($masterKeywords as $keyword)
                                                        <option value="{{ $keyword->idKeywords }}">
                                                            {{ $keyword->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <input type="text"
                                                    id="keyword_input"
                                                    class="form-control"
                                                    placeholder="Pilih keyword atau ketik lalu tekan Enter">
                                            </div>
                                        </div>
                                        
                                        {{-- keyword yang sudah dipilih --}}
                                        <div id="keyword_container" class="mb-2 mt-2"></div>

                                        {{-- hidden input untuk dikirim --}}
                                        <input type="hidden" name="keywords" id="keyword_hidden">

                                        <small class="text-muted">
                                            Contoh: desain, editing, public speaking
                                        </small>
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
                nameInput.setAttribute('disabled', true); // kalo milih dari combobox text inputnya di disable
            } else{
                nameInput.value = '';
                nameInput.removeAttribute('disabled');
            }
        });

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
                  preview.src = "#";
                    preview.style.display = 'none';
            }
        })

        keywordContainer = document.getElementById('keyword_container');
        keywordInput = document.getElementById('keyword_input');
        keywordHidden = document.getElementById('keyword_hidden');
        masterKeyword = document.getElementById('master_keyword');

        let keywords = @json($keywords->pluck('keyword_name')); // ini uda berupa json array

        keywordInput.addEventListener('keydown', function(e){
            if(e.key === 'Enter'){
                e.preventDefault();

                addKeyword(this.value.trim());

                this.value = '';
            }
        });

        masterKeyword.addEventListener('change', function(){
            const selectedItem = this.options[this.selectedIndex].text;

            if(this.value !== ''){
                addKeyword(selectedItem);
            }

            this.value = '';
        })

        function addKeyword(name){
            keywordName = name.trim().toLowerCase();

            if(keywordName === ''){
                return;
            }

            if(!keywords.includes(keywordName)){
                keywords.push(keywordName);
            }

            renderKeywords();
        }

        function renderKeywords(){
            keywordContainer.innerHTML = '';

            keywords.forEach((keyword, index) => {
                keywordContainer.innerHTML += `
                    <span class="badge bg-primary me-1 mb-1">
                        ${keyword}
                        <button type="button"
                            class="btn-close btn-close-white ms-2"
                            style="font-size:10px"
                            onclick="removeKeyword(${index})">
                        </button>
                    </span>
                `;

            });

            keywordHidden.value = JSON.stringify(keywords);
        }

        function removeKeyword(index){
            keywords.splice(index, 1);
            renderKeywords();
        }

        renderKeywords();
        
    </script>
@endsection
