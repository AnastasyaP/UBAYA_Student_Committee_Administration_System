@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Billing'])
    <div class="container-fluid py-4">
        <div class="row">
            <!-- grafik evaluasi -->
            <div class="col-md-7 mt-4">
                <div class="card h-100 mb-4">
                    <div class="card-header pb-0 px-3">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-0">Grafik Evaluasi Kinerja</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-4 p-3">
                        <!-- <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">Newest</h6> -->
                        {{ $chart->container() }}
                    </div>
                    <script src="{{ $chart->cdn() }}"></script>
                    {{ $chart->script() }}
                </div>
            </div>

            <!-- kritik & saran -->
            <div class="col-md-5 mt-4">
                <div class="card">
                    <div class="card-header pb-0 px-3">
                        <h6 class="mb-0">Evaluasi dari Panitia</h6>
                    </div>
                    <div class="card-body pt-4 p-3">
                        <ul class="list-group">
                            {{-- General Evaluations --}}
                            @foreach($generalEvaluation as $general)
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
                            @foreach($criteriaEvaluations as $eval)
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
                            @if($generalEvaluation->isEmpty() && $criteriaEvaluations->isEmpty())
                                <li class="list-group-item border-0 d-flex justify-content-center align-items-center p-4 mb-2 bg-gray-100 border-radius-lg">
                                    <h6 class="mb-0 text-center">
                                        Belum memiliki evaluasi dari panitia lain
                                    </h6>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
