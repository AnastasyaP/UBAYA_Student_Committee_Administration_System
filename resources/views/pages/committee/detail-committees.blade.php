@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Detail Kepanitiaan'])
    <div class="card shadow-lg mx-4">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center">

                {{-- Kiri --}}
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-xl position-relative me-3">
                        <img
                            src="{{ $committee->picture ? asset('storage/' . $committee->picture) : asset('/img/profile-default.png') }}"
                            alt="profile_image"
                            class="w-100 border-radius-lg shadow-sm">
                    </div>

                    <div>
                        <h1 class="mb-0">
                            {{ $committee->committee_name }}
                        </h1>
                    </div>
                </div>

                {{-- Kanan --}}
                <a href="{{ route('committee.report', ['idCommittee' => $committee->idCommittees]) }}"
                    class="btn btn-danger mb-0">
                    <i class="fas fa-file-pdf me-1"></i>
                    Download Report
                </a>

            </div>
        </div>
    </div>
    <div class="container-fluid py-4">
        <div class="row">
            <!-- grafik evaluasi kepanitiaan keseluruhan -->
            <div class="col-md-7 mt-4 mb-4">
                <div class="card h-100 mb-4">
                    <div class="card-header pb-0 px-3">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-0">Grafik Evaluasi Kepanitiaan Keseluruhan</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-4 p-3">
                        <!-- <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">Newest</h6> -->
                        {{ $committeeChart->container() }}
                    </div>
                    <script src="{{ $committeeChart->cdn() }}"></script>
                    {{ $committeeChart->script() }}
                </div>
            </div>

            <!-- kritik & saran kepanitiaan keseluruhan -->
            <div class="col-md-5 mt-4 mb-4">
                <div class="card h-100 mb-4">
                    <div class="card-header pb-0 px-3">
                        <h6 class="mb-0">Evaluasi dari Panitia</h6>
                    </div>
                    <div class="card-body pt-4 p-3">
                        <ul class="list-group">
                            {{-- General Evaluations --}}
                            @foreach($generalCommitteeEvaluation as $general)
                                <li class="list-group-item border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg">
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-3 text-sm">
                                            Keseluruhan
                                        </h6>

                                        <span class="text-xs">
                                            {{ $general->general_comment }}
                                        </span>
                                    </div>
                                </li>
                            @endforeach


                            {{-- Criteria Evaluations --}}
                            @foreach($committeeCriteriaEvaluations as $eval)
                                @if($eval->criteria_comment)
                                <li class="list-group-item border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg">
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-3 text-sm">
                                            {{ $eval->criteria }}
                                        </h6>

                                        <span class="text-xs">
                                            {{ $eval->criteria_comment }}
                                        </span>
                                    </div>
                                </li>
                                @endif
                            @endforeach


                            {{-- Empty State --}}
                            @if($generalCommitteeEvaluation->isEmpty() && $committeeCriteriaEvaluations->isEmpty())
                                <li class="list-group-item border-0 d-flex justify-content-center align-items-center p-4 mb-2 bg-gray-100 border-radius-lg">
                                    <h6 class="mb-0 text-center">
                                        Belum memiliki evaluasi dari panitia
                                    </h6>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card mb-4 committee-detail-scroll" style="max-height: 80vh; overflow-y: auto;">
                    <form action="#">
                        <div class="card-body">

                            {{-- INFORMASI KONTAK --}}
                            <p class="text-uppercase text-sm">Informasi Kontak</p>

                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-control-label">Nama Kepanitiaan</label>
                                        <input 
                                            class="form-control"
                                            type="text"
                                            name="name"
                                            value="{{ $committee->name }}"
                                            disabled
                                        >
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-control-label">Email</label>
                                        <input 
                                            class="form-control"
                                            type="text"
                                            name="email"
                                            value="{{ $committee->email }}"
                                            disabled
                                        >
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-control-label">Kontak</label>
                                        <input 
                                            class="form-control"
                                            type="text"
                                            name="contact"
                                            value="{{ $committee->contact }}"
                                            disabled
                                        >
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Unit Penyelenggara</label>
                                        <input 
                                            class="form-control"
                                            type="text"
                                            value="{{ $committee->organizerName }}"
                                            disabled
                                        >
                                    </div>
                                </div>

                            </div>

                            <hr class="horizontal dark">

                            {{-- DETAIL COMMITTEE --}}
                            <p class="text-uppercase text-sm">Detail Kepanitiaan</p>

                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Periode Dimulai</label>
                                        <input 
                                            class="form-control"
                                            type="date"
                                            name="start_period"
                                            value="{{ $committee->start_period }}"
                                            disabled
                                        >
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Periode Berakhir</label>
                                        <input 
                                            class="form-control"
                                            type="date"
                                            name="end_period"
                                            value="{{ $committee->end_period }}"
                                            disabled
                                        >
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Mulai Pendaftaran</label>
                                        <input 
                                            class="form-control"
                                            type="date"
                                            name="start_regis"
                                            value="{{ $committee->start_regis }}"
                                            disabled
                                        >
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Akhir Pendaftaran</label>
                                        <input 
                                            class="form-control"
                                            type="date"
                                            name="end_regis"
                                            value="{{ $committee->end_regis }}"
                                            disabled
                                        >
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Batas Pengisian Evaluasi</label>
                                        <input 
                                            class="form-control"
                                            type="date"
                                            name="end_eval"
                                            value="{{ $committee->end_evaluation }}"
                                            disabled
                                        >
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Deskripsi</label>

                                        <textarea 
                                            class="form-control"
                                            rows="5"
                                            name="description"
                                            disabled
                                        >{{ $committee->description }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Status Kepanitiaan</label>

                                        <select 
                                            name="is_active"
                                            id="is_active"
                                            class="form-control"
                                            disabled
                                        >
                                            <option value="1" @selected($committee->is_active == 1)>
                                                Aktif
                                            </option>
                                            <option value="0" @selected($committee->is_active == 0)>Tidak Aktif</option>
                                        </select>

                                        @error('is_active')
                                            <div class="text-danger small">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                            </div>

                            <hr class="horizontal dark">

                            {{-- EVALUASI --}}
                            <p class="text-uppercase text-sm">Evaluasi</p>

                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Evaluasi</label>

                                        <textarea 
                                            class="form-control"
                                            rows="5"
                                            name="evaluation"
                                            disabled
                                        >{{ $committee->evaluation }}</textarea>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="{{ $committee->poster ? asset('storage/' . $committee->poster) : asset('/img/noimage.jpg') }}" alt="poster" class="img-fluid rounded-3">
                </div>
            </div>
            <div class="col-md-12">
                @foreach($members as $divisionNames => $divisionMembers)
                    <div class="row">
                        <!-- grafik evaluasi -->
                        <div class="col-md-7 mt-4 mb-4">
                            <div class="card h-100 mb-4">
                                <div class="card-header pb-0 px-3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h6 class="mb-0">Grafik Evaluasi Divisi {{ $divisionNames }} Kepanitiaan</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-4 p-3">
                                    <!-- <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">Newest</h6> -->
                                    @if(isset($divisionChart[$divisionNames]))
                                        {!! $divisionChart[$divisionNames]->container() !!}
                                    @endif

                                    {{-- Empty State --}}
                                    @if((!isset($divisionChart[$divisionNames]))                                    )
                                        <li class="list-group-item border-0 d-flex justify-content-center align-items-center p-4 mb-2 bg-gray-100 border-radius-lg">
                                            <h6 class="mb-0 text-secondary text-center">
                                                Belum memiliki evaluasi dari panitia
                                            </h6>
                                        </li>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- kritik & saran -->
                        <div class="col-md-5 mt-4 mb-4">
                            <div class="card h-100 mb-4">
                                <div class="card-header pb-0 px-3">
                                    <h6 class="mb-0">Evaluasi Divisi {{ $divisionNames }} dari Panitia</h6>
                                </div>
                                <div class="card-body pt-4 p-3">
                                    <ul class="list-group">
                                        {{-- General Evaluations --}}
                                        @if(isset($generalDivisionEvaluation[$divisionNames]))
                                            @foreach($generalDivisionEvaluation[$divisionNames] as $general)
                                                <li class="list-group-item border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg">
                                                    <div class="d-flex flex-column">
                                                        <h6 class="mb-3 text-sm">
                                                            Keseluruhan
                                                        </h6>

                                                        <span class="text-xs">
                                                            {{ $general->general_comment }}
                                                        </span>
                                                    </div>
                                                </li>
                                            @endforeach
                                        @endif


                                        {{-- Criteria Evaluations --}}
                                        @if(isset($divisionCriteriaEvaluations[$divisionNames]))
                                            @foreach($divisionCriteriaEvaluations[$divisionNames] as $eval)
                                                @if($eval->criteria_comment)
                                                <li class="list-group-item border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg">
                                                    <div class="d-flex flex-column">
                                                        <h6 class="mb-3 text-sm">
                                                            {{ $eval->criteria }}
                                                        </h6>

                                                        <span class="text-xs">
                                                            {{ $eval->criteria_comment }}
                                                        </span>
                                                    </div>
                                                </li>
                                                @endif
                                            @endforeach
                                        @endif

                                        {{-- Empty State --}}
                                        @if(
                                            (!isset($generalDivisionEvaluation[$divisionNames]) || $generalDivisionEvaluation[$divisionNames]->isEmpty())
                                            &&
                                            (!isset($divisionCriteriaEvaluations[$divisionNames]) || $divisionCriteriaEvaluations[$divisionNames]->isEmpty())
                                        )
                                            <li class="list-group-item border-0 d-flex justify-content-center align-items-center p-4 mb-2 bg-gray-100 border-radius-lg">
                                                <h6 class="mb-0 text-secondary text-center">
                                                    Belum memiliki evaluasi dari panitia
                                                </h6>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center" >
                            <h6>{{ $divisionNames }}</h6>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                Nama</th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                Divisi</th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                Posisi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $hasMember = false; @endphp

                                        @foreach($divisionMembers as $m)
                                        @if($m->name)
                                        @php $hasMember = true; @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $m->name }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $m->email }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <h6 class="mb-0 text-sm">{{ $m->division }}</h6>
                                            </td>
                                            <td>
                                                <h6 class="mb-0 text-sm position-label">{{ $m->position }}</h6>
                                            </td>
                                        </tr>
                                        @endif
                                        @endforeach

                                        @if(!$hasMember)
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">
                                                    Belum ada anggota yang diterima
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                
                            </div>    
                        </div>
                    </div>
                @endforeach
                @foreach($divisionChart as $chart)
                    {!! $chart->script() !!}
                @endforeach
            </div>
        </div>
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
    </script>
@endsection
