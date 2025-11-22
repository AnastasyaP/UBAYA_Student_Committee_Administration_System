@extends('layouts.main')

@section('title', 'Registration Form')
@section('content')
    <!-- Page Title -->
    <div class="page-title dark-background" data-aos="fade" style="background-image: url(assets/img/page-title-bg.jpg);">
      <div class="container position-relative">
        <h1>Registration Form</h1>
        <p>Esse dolorum voluptatum ullam est sint nemo et est ipsa porro placeat quibusdam quia assumenda numquam molestias.</p>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="#">Detail</a></li>
            <li class="current">Registration Form</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Contact Section -->
    <section id="contact" class="contact section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-4">
          <div class="col-lg-12">
            <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
              <div class="row gy-4">

                <div class="col-md-6">
                  <h6>Name</h6>
                  <input type="text" name="name" value="{{ $profil->name }}" class="form-control" placeholder="Your Name" required="">
                </div>

                <div class="col-md-6 ">
                  <h6>NRP</h6>
                  <input type="text" class="form-control" value="{{ $profil->nrp }}" name="subject" placeholder="Subject" required="">
                </div>

                <div class="col-md-12">
                  <h6>email</h6>
                  <input type="email" class="form-control" value="{{ $profil->email }}" name="email" placeholder="Your Email" required="">
                </div>

                <div class="col-md-12">
                  <textarea class="form-control" name="message" rows="6" placeholder="Message" required=""></textarea>
                </div>

                <div class="col-md-12 text-center">
                  <div class="loading">Loading</div>
                  <div class="error-message"></div>
                  <div class="sent-message">Your message has been sent. Thank you!</div>

                  <button type="submit">Submit</button>
                </div>

              </div>
            </form>
          </div><!-- End Contact Form -->

        </div>

      </div>

    </section><!-- /Contact Section -->

@endsection