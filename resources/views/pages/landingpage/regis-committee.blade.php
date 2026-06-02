@extends('layouts.main')

@section('title', 'Form Registrasi')
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
        <h1>Form Registrasi</h1>
        <p>Lengkapi formulir pendaftaran sebagai langkah awal untuk bergabung dalam kepanitiaan yang kamu pilih.</p>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="{{ url()->previous() }}">Detail</a></li>
            <li class="current">Form Registrasi</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Contact Section -->
    <section id="contact" class="contact section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-4">
          <div class="col-lg-12">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form id="registration" action="{{ route('regis.store') }}" method="POST" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
              @csrf

              <input type="hidden" name="idCommittee" value="{{ $idCommittee }}">

              <div class="row gy-4">

                <div class="col-md-6">
                  <h6>Nama</h6>
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
                  <h6>Motivasi</h6>
                  <textarea class="form-control" name="motivation" rows="6" placeholder="Apa yang memotivasi kamu untuk mendaftar kepanitiaan ini?" required=""></textarea>
                </div>

                <div id="divisionAlert"></div>
                @if($existingRegistrations->count() > 0)
                  <div class="alert alert-info">
                      Anda sudah terdaftar pada divisi
                      <strong>
                          {{ $existingRegistrations->pluck('name')->join(', ') }}
                      </strong>.
                      Jika ingin mendaftar divisi tambahan, persentase minat akan diperbarui.
                  </div>
                @endif

                <table class="table" id="selectedDivision">
                  <thead>
                    <tr>
                      <th scope="col">Divisi</th>
                      <th scope="col">
                        Persentase
                        <i class="bi bi-info-circle-fill text-primary"
                          data-bs-toggle="tooltip"
                          data-bs-placement="top"
                          title="Persentase menunjukkan tingkat minat Anda terhadap masing-masing divisi. Jika memilih 2 divisi, total persentase harus tepat 100%. Contoh: Acara 70% dan Publikasi 30%.">
                        </i>
                      </th>
                      <th scope="col">Jadwal Interview</th>
                      <th scope="col">Aksi</th>
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
                            <p class="card-text mb-3"><small class="text-muted">{{ implode(', ', $division->keywords) }}</small></p>
                            <div class="d-grid gap-2 d-md-block">
                              <button class="btn btn-primary choose-division" 
                                      type="button"
                                      data-id="{{ $division->idDivision }}"
                                      data-name="{{ $division->dname }}">
                                      Pilih
                              </button>
                              <a href="{{ route('view.scheduleintv', ['idCommittee' => $division->idCommittee, 'idDivision' => $division->idDivision]) }}" class="btn btn-secondary">
                                Lihat Jadwal Interview</a>
                            </div>
                            
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  @endforeach
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
                  <!-- <div class="loading">Loading</div>
                  <div class="error-message"></div>
                  <div class="sent-message">Your message has been sent. Thank you!</div> -->
                  <button class="btn btn-primary" type="submit">Kirim Pendaftaran</button>
                </div>
                
              </div>
            </form>
          </div><!-- End Contact Form -->

        </div>

      </div>

    </section><!-- /Contact Section -->

    <script>
      let selectedDivisions = @json($existingRegistrations);
      
      const intvSchedules = @json($intvSchedules);

      selectedDivisions.forEach(div => {
          div.percentage = null;
          div.intv = intvSchedules[div.id] ?? [];
      });
      
      document.querySelectorAll('.choose-division').forEach(button => {
        button.addEventListener('click', function () {
          const id = parseInt(this.dataset.id);
          const name = this.dataset.name;

          if(selectedDivisions.find(d => d.id === id)){
            showAlert('Divisi sudah dipilih!');
            return;
          }

          if(selectedDivisions.length >= 2){
            showAlert('Maksimal mendaftar 2 divisi!');
            return;
          }
          
          selectedDivisions.push({
            id: id,
            name: name,
            percentage: '',
            intv_id: '',
            intv: intvSchedules[id] ?? [],
            is_existing: false
          });
          renderTable();
        });
      });

      function renderTable(){
        const tbody = document.querySelector('#selectedDivision tbody');

        tbody.innerHTML = '';

        selectedDivisions.forEach((div, index) => {

          // schedule cmbx
          let scheduleSelect = `<option value="">-- Pilih Jadwal Interview --</option>`;
          
          div.intv.forEach(s => {
            scheduleSelect += `<option value="${s.idInterviewSchedules}">
                                 ${formatDate(s.date)} | ${formatTime(s.start_time)} (${s.place})
                               </option>`;
          });

          let percentageSelect = `<option value="">-- Pilih Persentase --</option>`;
          const percentageOptions = [0,30,40,50,60,70,100];

          percentageOptions.forEach(p => {
            percentageSelect += `
                <option value="${p}"
                    ${parseInt(div.percentage) === p ? 'selected' : ''}>
                    ${p}%
                </option>
            `;
          });

          let percentageField = '';
          const existingCount = {{ $existingRegistrations->count() }};

          if(selectedDivisions.length === 1 && existingCount === 0){
            div.percentage = 100;

            percentageField = `
              <span class="badge bg-success fs-6">
                  100%
              </span>
            `;
          }else{
            percentageField = `
                <select class="form-control"
                        onChange="updatePercentage(${index}, this.value)">
                    ${percentageSelect}
                </select>
            `;
          }

          tbody.innerHTML += `
            <tr>
              <td>
                ${div.name}
                ${div.is_existing
                    ? '<span class="badge bg-secondary ms-2">Terdaftar</span>'
                    : ''
                }
              </td>
              <td>
                ${percentageField}
              </td>
              <td>
                ${
                  div.is_existing
                  ? `
                    <select class="form-control" disabled>
                      <option selected>
                        ${formatDate(div.date)}
                        | ${formatTime(div.start_time)}
                        (${div.place})
                      </option>
                    </select>
                  `
                  : `
                    <select class="form-control"
                            onChange="updateSchedule(${index}, this.value)">
                        ${scheduleSelect}
                    </select>
                  `
                }
              </td>
              <td>
                ${
                  div.is_existing
                    ? '<span class="text-muted">-</span>'
                    : `<button type="button"
                              class="btn btn-danger"
                              onclick="removeDivision(${index})">
                          X
                      </button>`
                }
              </td>
            </tr>
          `;
        });
      }

      function updatePercentage(index, value){
        const newValue = parseInt(value) || 0;

        const total = selectedDivisions.reduce((sum, div, i) => {
          return sum + (i === index ? 0 : (parseInt(div.percentage) || 0));
        }, 0);

        if(total + newValue > 100){
          showAlert('Total persentase tidak boleh melebihi 100%!');
          renderTable();
          return;
        }

        selectedDivisions[index].percentage = value;
      }

      function showAlert(message, type = 'warning') {
          const alertContainer = document.getElementById('divisionAlert');

          alertContainer.innerHTML = `
              <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                  ${message}
                  <button type="button"
                          class="btn-close"
                          data-bs-dismiss="alert"
                          aria-label="Close"></button>
              </div>
          `;

          // Scroll ke alert
          alertContainer.scrollIntoView({
              behavior: 'smooth',
              block: 'center'
          });

          setTimeout(() => {
              const alert = alertContainer.querySelector('.alert');
              if (alert) {
                  alert.classList.remove('show');
                  setTimeout(() => alert.remove(), 150);
              }
          }, 3000);
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
        
        console.log('Submit ditekan');

        if(selectedDivisions.length === 0){
            showAlert('Pilih minimal satu divisi.');
            return;
        }

        // Jika hanya pilih 1 divisi, otomatis 100%
        if(selectedDivisions.length === 1){
            selectedDivisions[0].percentage = 100;
        }

        const totalPercentage = selectedDivisions.reduce((sum, div) => {
            return sum + (parseInt(div.percentage) || 0);
        }, 0);

        for(const div of selectedDivisions){
            // Persentase hanya wajib jika pilih 2 divisi
            if(selectedDivisions.length === 2 && div.percentage === ''){
                showAlert(`Persentase untuk divisi ${div.name} belum dipilih.`);
                return;
            }

            if(!div.intv_id){
                showAlert(`Jadwal interview untuk divisi ${div.name} belum dipilih.`);
                return;
            }
        }

        if(selectedDivisions.length === 2 && totalPercentage !== 100){
            showAlert('Total persentase pilihan divisi harus tepat 100%.');
            return;
        }

        // ini nge hapus input yg lama dulu biar nga ke double
        document.querySelectorAll('.division-input').forEach(e => e.remove());

        selectedDivisions.forEach((div, index) => {
          this.insertAdjacentHTML(
            'beforeend', 
            `
              <input type="hidden" class="division-input" name="divisions[${index}][idDivision]" value="${div.id}">
              <input type="hidden" class="division-input" name="divisions[${index}][percentage]" value="${div.percentage}">
              <input type="hidden" class="division-input" name="divisions[${index}][idInterviewSchedule]" value="${div.intv_id}">
              <input type="hidden" class="division-input" name="divisions[${index}][is_existing]" value="${div.is_existing ? 1 : 0}">
              <input type="hidden" class="division-input" name="divisions[${index}][registration_id]" value="${div.registration_id ?? ''}">
            `
          )
        });

        this.submit();
      });

      document.addEventListener('DOMContentLoaded', function () {
          const tooltipTriggerList = [].slice.call(
              document.querySelectorAll('[data-bs-toggle="tooltip"]')
          );

          tooltipTriggerList.map(function (tooltipTriggerEl) {
              return new bootstrap.Tooltip(tooltipTriggerEl);
          });
      });

      renderTable();
    </script>
@endsection