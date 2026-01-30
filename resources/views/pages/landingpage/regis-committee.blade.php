@extends('layouts.main')

@section('title', 'Registration Form')
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
        <h1>Registration Form</h1>
        <p>Esse dolorum voluptatum ullam est sint nemo et est ipsa porro placeat quibusdam quia assumenda numquam molestias.</p>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="{{ url()->previous() }}">Detail</a></li>
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
            <form id="registration" action="{{ route('regis.store') }}" method="POST" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
              @csrf
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
                  <h6>Motivation</h6>
                  <textarea class="form-control" name="motivation" rows="6" placeholder="What motivate you to join us?" required=""></textarea>
                </div>

                <table class="table" id="selectedDivision">
                  <thead>
                    <tr>
                      <th scope="col">Division</th>
                      <th scope="col">Percentage</th>
                      <th scope="col">Interview Schedule</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>

                <div style="overflow-y: scroll; height:500px;">
                  @foreach($divisions as $division)
                  <div class="col-md-12">
                    <div class="card mb-3" style="min-height:200px;">
                      <div class="row g-0">
                        <div class="col-md-4">
                          <img src="{{ asset('storage/' . $division->picture) }}" class="rounded-start w-100" alt="..." style="object-fit:cover; height: 100%;">
                        </div>
                        <div class="col-md-8">
                          <div class="card-body">
                            <h5 class="card-title">{{ $division->dname }}</h5>
                            <p class="card-text">{{ $division->description }}</p>
                            <p class="card-text mb-3"><small class="text-muted">Last updated 3 mins ago</small></p>
                            <div class="d-grid gap-2 d-md-block">
                              <button class="btn btn-primary choose-division" 
                                      type="button"
                                      data-id="{{ $division->idDivision }}"
                                      data-name="{{ $division->dname }}">
                                      Choose
                              </button>
                              <a href="{{ route('view.scheduleintv', ['idCommittee' => $division->idCommittee, 'idDivision' => $division->idDivision]) }}" class="btn btn-secondary">
                                See Interview Schedule</a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  @endforeach
                  <input type="hidden" name="idCommittee" value="{{ $division->idCommittee }}">
                </div>
                <!-- <div class="col-md-12">
                  <div class="form-group">
                    <label class="form-control-label">CV</label>
                      <iframe
                        src="/pdfjs/web/viewer.html?file={{ asset('storage/' . $profil->cv) }}"
                        width="100%"
                        height="500px">
                      </iframe>
                  </div>
                </div> -->
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

    <script>
      let selectedDivisions = [];
      
      const intvSchedules = @json($intvSchedules);
      
      document.querySelectorAll('.choose-division').forEach(button => {
        button.addEventListener('click', function () {
          const id = this.dataset.id;
          const name = this.dataset.name;

          if(selectedDivisions.find(d => d.id === id)){
            alert('Division already selected!');
            return;
          }

          if(selectedDivisions.length < 2){
            selectedDivisions.push({
              id: id,
              name: name,
              percentage: '',
              intv_id: '',
              intv: intvSchedules[id] ?? [],
            });
          }else{
            alert('Maximum Division Choice is 2!');
            return;
          }
          
          renderTable();
        });
      });

      function renderTable(){
        const tbody = document.querySelector('#selectedDivision tbody');
        tbody.innerHTML = '';

        selectedDivisions.forEach((div, index) => {

          let percentageSelect = `<option value="">-- Choose the Percentage --</option>`;
          let scheduleSelect = `<option value="">-- Choose the Interview Schedule --</option>`;
          const percentageOptions = [0,30,40,50,60,70,100];

          percentageOptions.forEach(p => {
            percentageSelect += `<option value="${p}">${p}%</option>`;
          });

          div.intv.forEach(s => {
            scheduleSelect += `<option value="${s.idInterviewSchedules}">
                                 ${formatDate(s.date)} | ${formatTime(s.start_time)} (${s.place})
                               </option>`;
          });

          tbody.innerHTML += `
            <tr>
              <td>${div.name}</td>
              <td>
                <select class="form-control" onChange="updatePercentage(${index}, this.value)">
                  ${percentageSelect}
                </select>
              </td>
              <td>
                <select class="form-control" onChange="updateSchedule(${index}, this.value)">
                  ${scheduleSelect}
                </select>
              </td>
              <td>
                <button type="button" class="btn btn-danger" onclick="removeDivision(${index})">X</button>                      
              </td>
            </tr>
          `;
        });
      }

      function updatePercentage(index, value){
        selectedDivisions[index].percentage = value;
      }

      function updateSchedule(index, value){
        selectedDivisions[index].intv_id = value;
      }

      function removeDivision(index){
        selectedDivisions.splice(index, 1);
        renderTable();
      }

      function formatDate(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleDateString('id-ID', {
          day: '2-digit',
          month: 'short',
          year: 'numeric'
        });
      }

      function formatTime(timeStr) {
        return timeStr.substring(0, 5);
      }

      document.querySelector('#registration').addEventListener('submit', function(e){
        e.preventDefault();
        // ini nge hapus input yg lama dulu biar nga ke double
        document.querySelectorAll('.division-input').forEach(e => e.remove());

        selectedDivisions.forEach((div, index) => {
          this.insertAdjacentHTML(
            'beforeend', 
            `
              <input type="hidden" class="division-input" name="divisions[${index}][idDivision]" value="${div.id}">
              <input type="hidden" class="division-input" name="divisions[${index}][percentage]" value="${div.percentage}">
              <input type="hidden" class="division-input" name="divisions[${index}][idInterviewSchedule]" value="${div.intv_id}">
            `
          )
        });

        this.submit();
      });
    </script>
@endsection