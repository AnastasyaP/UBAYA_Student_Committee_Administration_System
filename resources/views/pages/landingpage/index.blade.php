@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.main')

@section('title', 'Home - Student Committee')
@section('content')
    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

      <img src="{{ asset('assets_lp/img/world-dotted-map.png') }}" alt="" class="hero-bg" data-aos="fade-in">

      <div class="container">
        <div class="row gy-4 d-flex justify-content-between">
          <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center">
            <h2 data-aos="fade-up">Bergabung, Jelajahi, dan Berkembang Bersama Kepanitiaan Kampus</h2>
            <p data-aos="fade-up" data-aos-delay="100">Temukan dan ikuti kepanitiaan mahasiswa yang sesuai dengan minatmu dengan mudah. ​​Kelola keterlibatan dan pengalamanmu di satu tempat — cepat, sederhana, dan terorganisir.</p>

            <form action="{{ route('home') }}" method="GET" class="form-search d-flex align-items-stretch mb-3" data-aos="fade-up" data-aos-delay="200">
              <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Temukan kepanitiaanmu disini..">
              <button type="submit" class="btn btn-primary">Cari</button>
            </form>

            <!-- <div class="row gy-4" data-aos="fade-up" data-aos-delay="300">

              <div class="col-lg-3 col-6">
                <div class="stats-item text-center w-100 h-100">
                  <span data-purecounter-start="0" data-purecounter-end="232" data-purecounter-duration="0" class="purecounter">232</span>
                  <p>Clients</p>
                </div>
              </div>

              <div class="col-lg-3 col-6">
                <div class="stats-item text-center w-100 h-100">
                  <span data-purecounter-start="0" data-purecounter-end="521" data-purecounter-duration="0" class="purecounter">521</span>
                  <p>Projects</p>
                </div>
              </div>

              <div class="col-lg-3 col-6">
                <div class="stats-item text-center w-100 h-100">
                  <span data-purecounter-start="0" data-purecounter-end="1453" data-purecounter-duration="0" class="purecounter">1453</span>
                  <p>Support</p>
                </div>
              </div>

              <div class="col-lg-3 col-6">
                <div class="stats-item text-center w-100 h-100">
                  <span data-purecounter-start="0" data-purecounter-end="32" data-purecounter-duration="0" class="purecounter">32</span>
                  <p>Workers</p>
                </div>
              </div>

            </div> -->

          </div>

          <div class="col-lg-5 order-1 order-lg-2 hero-img" data-aos="zoom-out">
            <img src="{{ asset('assets_lp/img/collaboration.svg') }}" class="img-fluid mb-3 mb-lg-0" alt="">
          </div>

        </div>
      </div>

    </section><!-- /Hero Section -->

    <!-- Pricing Section -->
    <section id="services" class="services section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <span>Rekomendasi</span>
        <h2>Rekomendasi</h2>
        <p>Rekomendasi komite yang dipersonalisasi berdasarkan partisipasi dan minatmu✨</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          <div class="container">
            @if($needPreferences)
              <div class="text-center my-5">
                  <h6>Belum ada rekomendasi 😢</h6>
                  @if($hasHistory)
                      <p>
                          Isi evaluasi atau pilih minat kamu untuk mendapatkan rekomendasi kepanitiaan
                      </p>
                  @else
                      <p>
                          Pilih minat kamu dulu untuk mendapatkan rekomendasi kepanitiaan
                      </p>
                  @endif
                  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#preferenceModal">
                      Pilih Minat Kepanitiaan
                  </button>
              </div>
            @else
              <div class="row gy-4">
                @foreach($initialRecommendations  as $committee)
                  <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <a href="{{ route('detail.committee', ['idCommittee' => $committee->idCommittees]) }}">
                      
                      <div class="card committee-card">
                        <div class="committee-img-wrapper">
                          <img src="{{ $committee->picture ? asset('storage/' . $committee->picture) : asset('/img/profile-default.png') }}" class="committee-img">
                        </div>

                        <h3>{{ $committee->name }}</h3>
                        <p>{{ Str::limit($committee->description, 150, '..') }}</p>
                      </div>

                    </a>
                  </div>
                @endforeach

                @if($hasMoreRecommendations)
                  <div id="moreRecommendations" class="collapse">
                      <div class="row gy-4">
                          @foreach($remainingRecommendations as $committee)
                              <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                                <a href="{{ route('detail.committee', ['idCommittee' => $committee->idCommittees]) }}">
                                  
                                  <div class="card committee-card">
                                    <div class="committee-img-wrapper">
                                      <img src="{{ $committee->picture ? asset('storage/' . $committee->picture) : asset('/img/profile-default.png') }}" class="committee-img">
                                    </div>

                                    <h3>{{ $committee->name }}</h3>
                                    <p>{{ Str::limit($committee->description, 150, '..') }}</p>
                                  </div>

                                </a>
                              </div>
                          @endforeach
                      </div>
                  </div>

                  <div class="text-center mt-4">
                      <button
                          id="toggleRecommendation"
                          class="btn btn-outline-secondary btn-sm mt-2"
                          data-bs-toggle="collapse"
                          data-bs-target="#moreRecommendations"
                          data-aos="fade-up" data-aos-delay="100">
                          Tampilkan {{ $remainingCount }} rekomendasi lainnya
                      </button>
                  </div>
                @endif
              </div>
              
            @endif
          </div>

        </div>

      </div>

    </section><!-- /Pricing Section -->

    <!-- Services Section -->
    <section id="committee-search" class="services section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <span>Kepanitiaan Aktif<br></span>
        <h2>Kepanitiaan Aktif</h2>
        <p>Temukan kepanitiaan yang kini membuka pendaftaran anggota baru. Jangan lewatkan kesempatanmu untuk mendaftar🤩</p>
      </div><!-- End Section Title -->

      <div class="container">
        @if(request('search'))
            <div class="mb-4 text-center">
                <h5>
                    Hasil pencarian untuk:
                    <b>"{{ request('search') }}"</b>
                </h5>
                <a href="{{ route('home') }}"
                  class="btn btn-outline-secondary btn-sm mt-2">
                    <i class="bi bi-arrow-counterclockwise"></i>
                    Tampilkan Semua Kepanitiaan
                </a>
            </div>
        @endif
        @if($committees->count() > 0)
        <div class="row gy-4">
          @foreach($committees as $committee)
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
              <a href="{{ route('detail.committee', ['idCommittee' => $committee->idCommittees]) }}">
                
                <div class="card committee-card">
                  <div class="committee-img-wrapper">
                    <img src="{{ $committee->picture ? asset('storage/' . $committee->picture) : asset('/img/profile-default.png') }}" class="committee-img">
                  </div>

                  <h3>{{ $committee->name }}</h3>
                  <p>{{ Str::limit($committee->description, 150, '..') }}</p>
                </div>

              </a>
            </div>
          @endforeach
        </div>
        @else
            <div class="text-center py-5">
                <h5>Tidak ada kepanitiaan yang ditemukan 😢</h5>
                <p>Coba gunakan kata kunci lain.</p>
            </div>
        @endif
      </div>

    </section><!-- /Services Section -->


    <!-- Testimonials Section -->
    <!-- <section id="testimonials" class="testimonials section dark-background">

      <img src="{{ asset('assets_lp/img/testimonials-bg.jpg') }}" class="testimonials-bg" alt="">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="swiper init-swiper">
          <script type="application/json" class="swiper-config">
            {
              "loop": true,
              "speed": 600,
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": "auto",
              "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
              }
            }
          </script>
          <div class="swiper-wrapper">

            <div class="swiper-slide">
              <div class="testimonial-item">
                <img src="{{ asset('assets_lp/img/testimonials/testimonials-1.jpg') }}" class="testimonial-img" alt="">
                <h3>Saul Goodman</h3>
                <h4>Ceo &amp; Founder</h4>
                <div class="stars">
                  <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                </div>
                <p>
                  <i class="bi bi-quote quote-icon-left"></i>
                  <span>Proin iaculis purus consequat sem cure digni ssim donec porttitora entum suscipit rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et. Maecen aliquam, risus at semper.</span>
                  <i class="bi bi-quote quote-icon-right"></i>
                </p>
              </div>
            </div>
            <div class="swiper-slide">
              <div class="testimonial-item">
                <img src="{{ asset('assets_lp/img/testimonials/testimonials-2.jpg') }}" class="testimonial-img" alt="">
                <h3>Sara Wilsson</h3>
                <h4>Designer</h4>
                <div class="stars">
                  <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                </div>
                <p>
                  <i class="bi bi-quote quote-icon-left"></i>
                  <span>Export tempor illum tamen malis malis eram quae irure esse labore quem cillum quid cillum eram malis quorum velit fore eram velit sunt aliqua noster fugiat irure amet legam anim culpa.</span>
                  <i class="bi bi-quote quote-icon-right"></i>
                </p>
              </div>
            </div>
            <div class="swiper-slide">
              <div class="testimonial-item">
                <img src="{{ asset('assets_lp/img/testimonials/testimonials-3.jpg') }}" class="testimonial-img" alt="">
                <h3>Jena Karlis</h3>
                <h4>Store Owner</h4>
                <div class="stars">
                  <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                </div>
                <p>
                  <i class="bi bi-quote quote-icon-left"></i>
                  <span>Enim nisi quem export duis labore cillum quae magna enim sint quorum nulla quem veniam duis minim tempor labore quem eram duis noster aute amet eram fore quis sint minim.</span>
                  <i class="bi bi-quote quote-icon-right"></i>
                </p>
              </div>
            </div>
            <div class="swiper-slide">
              <div class="testimonial-item">
                <img src="{{ asset('assets_lp/img/testimonials/testimonials-4.jpg') }}" class="testimonial-img" alt="">
                <h3>Matt Brandon</h3>
                <h4>Freelancer</h4>
                <div class="stars">
                  <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                </div>
                <p>
                  <i class="bi bi-quote quote-icon-left"></i>
                  <span>Fugiat enim eram quae cillum dolore dolor amet nulla culpa multos export minim fugiat minim velit minim dolor enim duis veniam ipsum anim magna sunt elit fore quem dolore labore illum veniam.</span>
                  <i class="bi bi-quote quote-icon-right"></i>
                </p>
              </div>
            </div>
            <div class="swiper-slide">
              <div class="testimonial-item">
                <img src="{{ asset('assets_lp/img/testimonials/testimonials-5.jpg') }}" class="testimonial-img" alt="">
                <h3>John Larson</h3>
                <h4>Entrepreneur</h4>
                <div class="stars">
                  <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                </div>
                <p>
                  <i class="bi bi-quote quote-icon-left"></i>
                  <span>Quis quorum aliqua sint quem legam fore sunt eram irure aliqua veniam tempor noster veniam enim culpa labore duis sunt culpa nulla illum cillum fugiat legam esse veniam culpa fore nisi cillum quid.</span>
                  <i class="bi bi-quote quote-icon-right"></i>
                </p>
              </div>
            </div>
          </div>
          <div class="swiper-pagination"></div>
        </div>

      </div>

    </section> -->
    <!-- /Testimonials Section -->

    <!-- Faq Section -->
    <!-- <section id="faq" class="faq section">

      <div class="container section-title" data-aos="fade-up">
        <span>Frequently Asked Questions</span>
        <h2>Frequently Asked Questions</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
      </div>

      <div class="container">

        <div class="row justify-content-center">

          <div class="col-lg-10">

            <div class="faq-container">

              <div class="faq-item faq-active" data-aos="fade-up" data-aos-delay="200">
                <i class="faq-icon bi bi-question-circle"></i>
                <h3>Non consectetur a erat nam at lectus urna duis?</h3>
                <div class="faq-content">
                  <p>Feugiat pretium nibh ipsum consequat. Tempus iaculis urna id volutpat lacus laoreet non curabitur gravida. Venenatis lectus magna fringilla urna porttitor rhoncus dolor purus non.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div>

              <div class="faq-item" data-aos="fade-up" data-aos-delay="300">
                <i class="faq-icon bi bi-question-circle"></i>
                <h3>Feugiat scelerisque varius morbi enim nunc faucibus a pellentesque?</h3>
                <div class="faq-content">
                  <p>Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque elit ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa tincidunt dui.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div>

              <div class="faq-item" data-aos="fade-up" data-aos-delay="400">
                <i class="faq-icon bi bi-question-circle"></i>
                <h3>Dolor sit amet consectetur adipiscing elit pellentesque?</h3>
                <div class="faq-content">
                  <p>Eleifend mi in nulla posuere sollicitudin aliquam ultrices sagittis orci. Faucibus pulvinar elementum integer enim. Sem nulla pharetra diam sit amet nisl suscipit. Rutrum tellus pellentesque eu tincidunt. Lectus urna duis convallis convallis tellus. Urna molestie at elementum eu facilisis sed odio morbi quis</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div>

              <div class="faq-item" data-aos="fade-up" data-aos-delay="500">
                <i class="faq-icon bi bi-question-circle"></i>
                <h3>Ac odio tempor orci dapibus. Aliquam eleifend mi in nulla?</h3>
                <div class="faq-content">
                  <p>Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque elit ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa tincidunt dui.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div>

              <div class="faq-item" data-aos="fade-up" data-aos-delay="600">
                <i class="faq-icon bi bi-question-circle"></i>
                <h3>Tempus quam pellentesque nec nam aliquam sem et tortor consequat?</h3>
                <div class="faq-content">
                  <p>Molestie a iaculis at erat pellentesque adipiscing commodo. Dignissim suspendisse in est ante in. Nunc vel risus commodo viverra maecenas accumsan. Sit amet nisl suscipit adipiscing bibendum est. Purus gravida quis blandit turpis cursus in</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div>

            </div>

          </div>

        </div>

      </div>

    </section> -->
    <!-- /Faq Section -->

    <!-- Modal Choose Preference -->
    <div class="modal fade" id="preferenceModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

          <form action="{{ route('save.preference') }}" method="POST">
            @csrf

            <div class="modal-header">
              <h5 class="modal-title">Pilih Minat Kamu</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
              <p>Pilih bidang yang kamu minati untuk mendapatkan rekomendasi</p>

              @foreach($keywords as $keyword)
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="keywords[]" value="{{ $keyword->idKeywords }}">
                  <label class="form-check-label">
                    {{ $keyword->name }}
                  </label>
                </div>
              @endforeach

            </div>

            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>

          </form>

        </div>
      </div>
    </div>
    
    <script>
        console.log(@json($debugData));
        
        document.addEventListener("DOMContentLoaded", function(){

            let needPreference = {{ $needPreferences ? 'true' : 'false' }};
            let hasHistory = {{ $hasHistory ? 'true' : 'false' }};

            if (needPreference && !hasHistory) {
                let modal = new bootstrap.Modal(document.getElementById('preferenceModal'));
                modal.show();
            }

            @if(request('search'))
                document.getElementById('committee-search')
                    .scrollIntoView({
                        behavior: 'smooth'
                    });
            @endif
        });

        const collapse = document.getElementById('moreRecommendations');
        const button = document.getElementById('toggleRecommendation');

        if (collapse && button) {

            collapse.addEventListener('show.bs.collapse', function () {
                button.innerHTML = 'Sembunyikan rekomendasi';
            });

            collapse.addEventListener('hide.bs.collapse', function () {
                button.innerHTML = 'Tampilkan {{ $remainingCount }} rekomendasi lainnya';
            });

        }
    </script>
@endsection