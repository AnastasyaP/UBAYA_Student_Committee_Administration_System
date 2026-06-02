@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Dashboard'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-4 text-center">
                        <div class="row">
                            <div class="col-12">
                                <div class="numbers">
                                    <p class="text-sm mb-3 text-uppercase font-weight-bold">Status Pendaftar Menunggu</p>
                                    <h5 class="font-weight-bolder">
                                        {{ $menunggu }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-4 text-center">
                        <div class="row">
                            <div class="col-12">
                                <div class="numbers">
                                    <p class="text-sm mb-3 text-uppercase font-weight-bold">Status Pendaftar Dinilai</p>
                                    <h5 class="font-weight-bolder">
                                        {{ $dinilai }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-4 text-center">
                        <div class="row">
                            <div class="col-12">
                                <div class="numbers">
                                    <p class="text-sm mb-3 text-uppercase font-weight-bold">Status Pendaftar Diterima</p>
                                    <h5 class="font-weight-bolder">
                                        {{ $diterima }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body p-4 text-center">
                        <div class="row">
                            <div class="col-12">
                                <div class="numbers">
                                    <p class="text-sm mb-3 text-uppercase font-weight-bold">Status Pendaftar Ditolak</p>
                                    <h5 class="font-weight-bolder">
                                        {{ $ditolak }}
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
                    <div class="card-header pb-0 pt-3 bg-transparent">
                        <h6 class="text-capitalize">Jadwal Interview per minggu</h6>
                    </div>
                    <div class="card-body p-3">
                        <div id="calendar" style="max-height: 400px; margin-right: 10px; margin-left: 10px;"></div>
                    </div>
                </div>
            </div>
           <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="card h-100">
                    <div class="card-header pb-0 p-3">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-2">Pendaftaran yang sedang berjalan</h6>
                        </div>
                    </div>
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table align-items-center ">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                        Divisi</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                        Status</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                        Pendaftar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($divisions as $divisi)
                                <tr>
                                    <td class="w-30">
                                        <div class="d-flex px-2 py-1 align-items-center">
                                            <div>
                                                <img src="{{ $divisi->picture ? asset('storage/' . $divisi->picture) : asset('/img/profile-default.png') }}" alt="Profil divisi" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                            </div>
                                            <div class="ms-4">
                                                <h6 class="text-sm mb-0">{{ $divisi->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            @if ($divisi->is_open == 1)
                                                <span class="badge bg-success">Buka</span>
                                            @else
                                                <span class="badge bg-danger">Tidak Buka</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <div class="col text-center">
                                            <h6 class="text-sm mb-0">{{ $divisi->total_applicants }}</h6>
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
        <div class="row mt-4">
            
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection

@push('js')
    <!-- full calendar render -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js"></script>
    <script> 
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'id',
                height: 400,
                initialView: 'timeGridWeek',

                headerToolbar: {
                    left: 'prev',
                    center: 'title',
                    right: 'next'
                },
                nowIndicator: true,
                slotMinTime: '07:00:00',
                slotMaxTime: '23:59:00',

                slotLabelFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },

                allDaySlot: false,

                events: @json($events),

                eventClick: function(info){
                    // buat nge stop redirect url kek link zoom ama gmeet
                    info.jsEvent.preventDefault();

                    // objek yg nyimpen data event
                    const event = info.event;

                    // ngecek selected division buat di cmbbox
                    const idDivision = event.extendedProps.idDivision;
                    document.getElementById('division').value = idDivision;

                    // masukin datanya
                    document.getElementById('date').value = event.extendedProps.date;
                    document.getElementById('start_time').value = event.extendedProps.start_time;
                    document.getElementById('end_time').value = event.extendedProps.end_time;
                    document.getElementById('place').value = event.extendedProps.place;
                    document.getElementById('link').value = event.extendedProps.link;

                    document.getElementById('idSchedule').value = event.id;
                    // form action (save updates)
                    document.getElementById('form-detail').action = "/update-schedule/" + event.id;

                    //show modal
                    let dmodal = document.getElementById('detailModal');
                    let modal = new bootstrap.Modal(dmodal);
                    modal.show();
                },
                //styling
                eventDidMount: function(info){
                    info.el.style.cursor = 'pointer';
                }
            });
            calendar.render();

        });
    </script>
@endpush
