@extends('layouts.main')

@section('title', 'Detail Committee')

@section('content')
    <!-- Page Title -->
    <div class="page-title dark-background" data-aos="fade" style="background-image: url({{ asset('assets/img/page-title-bg.jpg') }});">
      <div class="container position-relative">
        <h1>{{ $committee->name }}</h1>
        <!-- <p>Esse dolorum voluptatum ullam est sint nemo et est ipsa porro placeat quibusdam quia assumenda numquam molestias.</p> -->
        <nav class="breadcrumbs">
          <ol>
            <li><a href="{{ url()->previous() }}">Registration Form</a></li>
            <li class="current">Interview Schedules</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- About Section -->
    <section id="about" class="about section">
      <div class="container">
        <div class="row gy-4">
          <div class="card-body px-0 pt-0 pb-2">
            <div id="calendar" style="min-height: 600px; margin-right: 10px; margin-left: 10px;"></div>
          </div>
        </div>
      </div>
    </section><!-- /About Section -->
@endsection

@push('js')
<!-- full calendar render -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@5.11.3/main.min.js"></script>
    <script> 
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                // initialView: 'dayGridMonth',
                events: {!! json_encode($events) !!},        
                //styling
                eventDidMount: function(info){
                    info.el.style.cursor = 'pointer';
                }
            });
            calendar.render();

        });
    </script>
  @endpush
