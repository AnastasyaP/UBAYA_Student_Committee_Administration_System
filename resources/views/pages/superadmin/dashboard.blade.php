@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Dashboard'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-xl-6 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-4 text-center">
                        <div class="row">
                            <div class="col-12">
                                <div class="numbers">
                                    <p class="text-sm mb-3 text-uppercase font-weight-bold">Jumlah Kepanitiaan Aktif</p>
                                    <h5 class="font-weight-bolder">
                                        {{ $activeCommittee}}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-4 text-center">
                        <div class="row">
                            <div class="col-12">
                                <div class="numbers">
                                    <p class="text-sm mb-3 text-uppercase font-weight-bold">Jumlah Kepanitiaan Tidak Aktif</p>
                                    <h5 class="font-weight-bolder">
                                        {{ $disactiveCommittee}}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="card z-index-2 h-100">
                    <!-- <div class="card-header pb-0 pt-3 bg-transparent">
                        <h6 class="text-capitalize">Grafik Pendaftaran Kepanitiaan per Bulan</h6>
                    </div> -->
                    <div class="card-body p-3">
                        {!! $registrationChart->container() !!}

                        <script src="{{ $registrationChart->cdn() }}"></script>

                        {{ $registrationChart->script() }}
                    </div>
                </div>
            </div>
           <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="card h-100">
                    <div class="card-header pb-0 p-3">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-2">TOP 10 Kepanitiaan Populer</h6>
                        </div>
                    </div>
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table align-items-center ">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                        Nama Kepanitiaan</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                        Status</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                        Jumlah Pendaftar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topCommittees as $committee)
                                <tr>
                                    <td class="w-30">
                                        <div class="d-flex px-2 py-1 align-items-center">
                                            <div>
                                                <img src="{{ $committee->picture ? asset('storage/' . $committee->picture) : asset('/img/profile-default.png') }}" alt="Profil divisi" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                            </div>
                                            <div class="ms-4">
                                                <h6 class="text-sm mb-0">{{$committee->name}}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                                @if($committee->is_active == 1)
                                                <span class="badge bg-success">Aktif</span>
                                                @else
                                                <span class="badge bg-danger">Tidak Aktif</span>
                                                @endif
                                        </div>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <div class="col text-center">
                                            <h6 class="text-sm mb-0">{{ $committee->totalPendaftar }}</h6>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        @include('layouts.footers.auth.footer')
    </div>
@endsection

