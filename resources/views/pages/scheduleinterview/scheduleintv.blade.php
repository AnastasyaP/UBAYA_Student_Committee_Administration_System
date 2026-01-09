@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Interview Schedule'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                @if(session('success'))
                    <div>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @elseif(session('warning'))
                    <div>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Warning!</strong> {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center" >
                        <h6>Interview Schedule List</h6>
                        <a href="{{ route('intv.add') }}" target=""
                            class="btn btn-dark btn-add ms-auto">Add Schedule</a>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div id="calendar" style="min-height: 600px; margin-right: 10px; margin-left: 10px;"></div>
                    </div>
                </div>
                <!-- modal -->                
                <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="detailModalLabel">Detail Schedule Interview</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="col-form-label">Division:</label>
                                            <select class="form-control" name="division" id="division">
                                                @foreach($masterDivisions as $master)
                                                <option value="{{ $master->idDivisions }}">{{ $master->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="col-form-label">Date:</label>
                                            <input type="date" class="form-control" name="date" id="date">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="col-form-label">Start Time:</label>
                                                    <input type="time" class="form-control" name="start_time" id="start_time">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="col-form-label">End Time:</label>
                                                    <input type="time" class="form-control" name="end_time" id="end_time">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="col-form-label">Place:</label>
                                            <input type="text" class="form-control" name="place" id="place">
                                        </div>
                                        <div class="mb-3">
                                            <label class="col-form-label">Link:</label>
                                            <input type="text" class="form-control" name="link" id="link">
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <form action="" id="form-detail" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </form>
                                    <input type="hidden" name="idSchedule" id="idSchedule">
                                </div>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection

    @push('js')
    <script>
        setTimeout(()=>{
            const alert = document.querySelector('.alert');
            if(alert){
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 3000); // auto close 3 detik
    </script>

    <!-- full calendar render -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script> 
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                // initialView: 'dayGridMonth',
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
