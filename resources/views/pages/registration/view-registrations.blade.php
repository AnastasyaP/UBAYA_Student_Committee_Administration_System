@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Detail Registrasi'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form action="#" enctype="multipart/form-data">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-control-label">Nama</label>
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
                                          <label class="form-control-label">Divisi</label>
                                            <input class="form-control" type="text" id="division" name="division"
                                            value="{{ $registration->division }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Persentase</label>
                                        <input class="form-control" type="text" id="percentage" name="percentage"
                                            value="{{ $registration->percentage }}" disabled>
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
                                            disabled>{{ $registration->motivation }}</textarea>
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
                                        <h6>Tidak Ada Portofolio yang Tersedia</h6>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header pb-0 p-3">
                                            <h6 class="mb-0">Histori Kepanitiaan</h6>
                                        </div>
                                        <div class="card-body p-3">
                                            <ul class="list-group">
                                                @forelse($committeeHistory as $history)
                                                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                                        <div class="d-flex align-items-center">
                                                            <div class="me-3">
                                                                <img 
                                                                    src="{{ $history->picture ? asset('storage/' . $history->picture) : asset('/img/profile-default.png') }}" 
                                                                    alt="{{ $history->committee_name }}"
                                                                    class="border-radius-lg shadow-sm"
                                                                    width="50"
                                                                    height="50"
                                                                    style="object-fit: cover;"
                                                                >
                                                            </div>

                                                            <div class="d-flex flex-column">
                                                                <h6 class="mb-1 text-dark text-sm">
                                                                    {{ $history->committee_name }}
                                                                </h6>

                                                                <span class="text-xs">
                                                                    {{ $history->position }} Divisi {{ $history->division_name }}
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <div class="d-flex align-items-center">
                                                            <a href="{{ route('view.eval.history', ['idUser' => $history->idUsers, 'idCommittee' => $history->idCommittees]) }}" 
                                                                class="btn btn-sm bg-gradient-primary mb-0 d-flex align-items-center gap-2 px-3 py-2"> 
                                                                    <span class="text-white">Lihat Evaluasi</span>
                                                                    <i class="ni ni-bold-right text-white" aria-hidden="true"></i>
                                                            </a>
                                                        </div>
                                                    </li>

                                                @empty
                                                    <li class="list-group-item border-0">
                                                        <h6 class="mb-0 text-center text-secondary">
                                                            Tidak Ada Riwayat Kepanitiaan yang Tersedia
                                                        </h6>
                                                    </li>
                                                @endforelse
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($registration->status == 'dinilai')
                            <div class="col-md-12 mt-3">
                                <div class="form-group">
                                    <label class="form-control-label">Perhitungan Nilai Akhir AHP</label>
                                    <div class="table-responsive p-0">
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        Kriteria AHP</th>
                                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        Kriteria Interview</th>
                                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        Nilai Kriteria Interview<br>(Raw score)</th>
                                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        Nilai Rata-rata</th>
                                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        Bobot Rata-rata</th>
                                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        (Nilai Rata-rata x Bobot Rata-rata )</th>
                                                </tr>
                                            </thead>
                                            <tbody id="reg-division-body">
                                                @foreach($ahpCalcs as $calc)
                                                @php
                                                    $maxScale = floor(100 / $criteriasCount);

                                                    $scoreLabels = [
                                                        floor($maxScale * 0.2) => 'Sangat Tidak Baik',
                                                        floor($maxScale * 0.4) => 'Tidak Baik',
                                                        floor($maxScale * 0.6) => 'Cukup',
                                                        floor($maxScale * 0.8) => 'Baik',
                                                        $maxScale => 'Sangat Baik'
                                                    ];

                                                    $label = $scoreLabels[$calc->raw_score] ?? '-';
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 text-sm">{{ $calc->ahp }}</h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0 text-sm">{{ $calc->intv_criteria }}</h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0 text-sm">{{ $calc->raw_score }}  ({{  $label }})</h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0 text-sm">{{ $calc->avg_score }}</h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0 text-sm">{{ $calc->average_weight }}</h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0 text-sm">{{ $calc->score }}</h6>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>            
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Nilai Akhir Perhitungan AHP</label>
                                        <input class="form-control" type="text"
                                            value="{{ $final_score }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Komentar Evaluator</label>
                                        <textarea class="form-control" rows="4" disabled>{{ $comment }}</textarea>
                                    </div>
                                </div>
                            </div>  
                            @endif 
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
