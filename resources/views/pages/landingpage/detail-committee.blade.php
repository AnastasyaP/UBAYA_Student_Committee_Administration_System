@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.main')

@section('title', 'Detail Committee')

@section('content')
    @if(session('success'))
      <div class="flash-message success">
        {{ session('success') }}
      </div>
    @endif

    @if(session('warning'))
      <div class="flash-message warning">
        {{ session('warning') }}
      </div>
    @endif

    @if(session('error'))
      <div class="flash-message error">
        {{ session('error') }}
      </div>
    @endif
    <!-- Page Title -->
    <div class="page-title dark-background" data-aos="fade" style="background-image: url(assets/img/page-title-bg.jpg);">
      <div class="container position-relative">
        <h1>{{ $committee->name }}</h1>
        <!-- <p>Esse dolorum voluptatum ullam est sint nemo et est ipsa porro placeat quibusdam quia assumenda numquam molestias.</p> -->
        <nav class="breadcrumbs">
          <ol>
            <li><a href="{{ route('home')}}">Beranda</a></li>
            <li class="current">Detail</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- About Section -->
    <section id="about" class="about section">
      
      <div class="container">
        <div class="row gy-4">

          <div class="col-lg-6 position-relative align-self-start order-lg-last order-first d-flex justify-content-center" data-aos="fade-up" data-aos-delay="200">
            <img src="{{ asset('storage/' . $committee->poster) }}" class="img-fluid" alt="" style="height:auto; width:500px; object-fit: cover;">
            <!-- <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8" class="glightbox pulsating-play-btn"></a> -->
          </div>

          <div class="col-lg-6 content order-last  order-lg-first" data-aos="fade-up" data-aos-delay="100">
            <div class="mb-3">
              @if($jmlDaftar > 0 && !$daftarDiterima)
                <span class="badge bg-warning fs-6">Sudah Mendaftar, Lihat status di halaman kepanitiaan</span>
              @endif
              @if($daftarDiterima)
                  <span class="badge bg-primary fs-6">Anggota Panitia</span>
              @endif
            </div>
            <h3>{{ $committee->name }}</h3>
            <p>{{ $committee->description }}</p>
            <ul>
               <li>
                  <i class="bi bi-calendar-event"></i>
                  <div>
                      <h5>Periode Pendaftaran</h5>
                      <p>
                          {{ \Carbon\Carbon::parse($committee->start_regis)->translatedFormat('d F Y') }}
                          -
                          {{ \Carbon\Carbon::parse($committee->end_regis)->translatedFormat('d F Y') }}
                      </p>
                  </div>
              </li>
              <li>
                  <i class="bi bi-calendar-check"></i>
                  <div>
                      <h5>Periode Kepanitiaan</h5>
                      <p>
                          {{ \Carbon\Carbon::parse($committee->start_period)->translatedFormat('d F Y') }}
                          -
                          {{ \Carbon\Carbon::parse($committee->end_period)->translatedFormat('d F Y') }}
                      </p>
                  </div>
              </li>
              <li>
                <i class="bi bi-diagram-3"></i>
                <div>
                  <h5>Persyaratan</h5>
                  <p>{{ $committee->requirements }}</p>
                </div>
              </li>
            </ul>
            <div class="d-grid gap-2">
              @if($allowedToRegister)  
               
                @if(\Carbon\Carbon::parse($committee->end_regis)->isPast())              
                  <button class="btn btn-primary" disabled>Pendaftaran Berakhir</button>
                @else
                  <a href="{{ route('regis.committee', ['idCommittee' => $committee->idCommittees]) }}"
                    class="btn btn-primary btn-lg mt-4">
                    Daftar
                  </a>
                @endif
              
              @elseif($isKoor)
                <a href="{{ route('members.set.committee', ['idCommittee' => $committee->idCommittees]) }}"
                  class="btn btn-primary btn-lg mt-4">
                  Dashboard Panitia
                </a>
              
              @elseif($daftarDiterima && !$isKoor)
                <button class="btn btn-primary" disabled>
                    Sudah Menjadi Anggota
                </button>
              @endif
            </div>
          </div>

        </div>

      </div>

    </section><!-- /About Section -->

    <!-- Team Section -->
    <section id="team" class="team section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <span>Divisi Kami<br></span>
        <h2>Divisi Kami</h2>
        <p>Explore divisi-divisi yang membentuk komite kita dan memastikan setiap kegiatan berjalan lancar.</p>
      </div><!-- End Section Title -->
      
      <div class="container">
        <div class="row">
          @foreach($divisions as $division)
          <div class="col-lg-4 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="100">
            <div class="member flex-fill">
              <img src="{{ asset('storage/' . $division->picture) }}" class="img-fluid" alt="">
              <div class="member-content">
                <h4>{{ $division->name }}</h4>
                <!-- <span>Web Development</span> -->
                <p>{{ Str::limit($division->description, 70, '..') }}</p>
              </div>
            </div>
          </div><!-- End Team Member -->
          @endforeach
        </div>
      </div>

    </section><!-- /Team Section -->
@endsection
