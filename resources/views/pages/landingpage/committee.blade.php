@extends('layouts.main')

@section('title', 'Committee')
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

    @if ($errors->any())  
      <div class="flash-message error">
        {{ $errors->first() }}
      </div>
    @endif
    <!-- Page Title -->
    <div class="page-title dark-background" data-aos="fade" style="background-image: url(assets/img/page-title-bg.jpg);">
      <div class="container position-relative">
        <h1>Kepanitiaan</h1>
        <p>Lihat riwayat pendaftaran dan kepanitiaan yang pernah Anda ikuti</p>        
        <nav class="breadcrumbs">
          <ol>
            <li><a href="{{  route('home') }}">Beranda</a></li>
            <li class="current">Kepanitiaan</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Riwayat Pendaftaran Section -->
    <section id="featured-services" class="featured-services section">
      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <span>Riwayat Pendaftaran<br></span>
        <h2>Riwayat Pendaftaran</h2>
        <p>Pantau status pendaftaran Anda pada berbagai kepanitiaan yang diikuti</p>      
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">
          @if(!$committees)  
            <div>
              <h3>Anda belum memiliki kepanitiaan</h3>
            </div>
          @endif
          @foreach($registrations as $regis)
            <div class="col-lg-4 col-md-6 service-item d-flex" data-aos="fade-up" data-aos-delay="100">
              <div class="icon flex-shrink-0">
                <img src="{{ $regis->picture ? asset('storage/' . $committee->picture) : asset('/img/profile-default.png') }}" alt="committee" class="committee-icon">
              </div>
              <div>
                <h4 class="title">{{ $regis->committee }}</h4>
                <p class="description"><b>{{ $regis->division }}</b> - {{ $regis->position }}</p>
                @if($regis->status === 'menunggu')
                <span class="badge bg-warning">Menunggu Interview</span>
                @elseif($regis->status === 'dinilai')
                <span class="badge bg-info">Menunggu Hasil Interview</span>
                @elseif($regis->status === 'diterima')
                <span class="badge bg-success">Diterima</span>
                @elseif($regis->status === 'ditolak')
                <span class="badge bg-danger">Ditolak</span>
                @endif
                <!-- <a href="#" class="readmore stretched-link"><span>Detail</span><i class="bi bi-arrow-right"></i></a> -->
              </div>
            </div>
            <!-- End Service Item -->
          @endforeach
        </div>
      </div>
    </section><!-- /Riwayat Pendaftaran Section -->
    
    <!-- Riwayat Kepanitiaan Section -->
    <section id="alt-pricing" class="alt-pricing section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <span>Riwayat Kepanitiaan<br></span>
        <h2>Riwayat Kepanitiaan</h2>
        <p>Lihat riwayat kepanitiaan yang pernah Anda ikuti beserta detailnya</p>      
      </div><!-- End Section Title -->

      <div class="container">
        @foreach($committees as $committee)
        <div class="row gy-4 pricing-item mb-5" data-aos="fade-up" data-aos-delay="100">
          <div class="col-lg-3 d-flex align-items-center justify-content-center">
            <img src="{{ $committee->picture ? asset('storage/' . $committee->picture) : asset('/img/profile-default.png') }}" alt="committee" class="committee-icon">
          </div>

          <div class="col-lg-3 d-flex align-items-center justify-content-center">
            <h3>{{ $committee->committee }}</h3>
          </div>

          <div class="col-lg-3 d-flex align-items-center">
            <ul class="committee-info">
                <li>
                    <i class="bi bi-diagram-3 text-secondary"></i> 
                    <span><b>Divisi:</b> {{ $committee->division }}</span>
                </li>
                <li>
                    <i class="bi bi-person-badge text-secondary"></i> 
                    <span><b>Posisi:</b> {{ $committee->position }}</span>
                </li>
                <li>
                    <i class="bi bi-activity text-secondary"></i>
                    <span>
                        <b>Status:</b> 
                        <span class="badge 
                          {{ $committee->is_active == 1 ? 'bg-primary' : 'bg-secondary' }}">
                            {{ $committee->is_active == 1 ? 'Kepanitiaan Aktif' : 'Kepanitiaan Berakhir' }}
                        </span>
                    </span>
                </li>
                <li>
                    <i class="bi bi-calendar-event text-secondary"></i>
                    <span>
                        <b>Periode:</b> 
                        {{ \Carbon\Carbon::parse($committee->start_period)->translatedFormat('d M Y') }}
                        - 
                        {{ \Carbon\Carbon::parse($committee->end_period)->translatedFormat('d M Y') }}
                    </span>
                </li>
            </ul>
          </div>
            <div class="col-lg-3 d-flex align-items-center justify-content-center">
                @if($committee->is_active === 0)
                    @if(\Carbon\Carbon::parse($committee->end_eval)->isPast())
                      <button class="buy-btn disabled" disabled>Evaluasi Berakhir</button>
                    @else
                      <a href="{{ route('lp.eval', ['idCommittee' => $committee->idCommittee]) }}" class="buy-btn">Evaluasi</a>
                    @endif
                @else
                    <a href="{{ route('detail.committee', ['idCommittee' => $committee->idCommittee]) }}" class="buy-btn ">Detail</a>
                @endif
            </div>
        </div><!-- End Pricing Item -->
        @endforeach
      </div>
    </section><!-- /Riwayat Kepanitiaan Section -->

    @endsection