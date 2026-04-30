@extends('layouts.main')

@section('title', 'Form Evaluasi')
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
        <h1>Form Evaluasi</h1>
        <p>Esse dolorum voluptatum ullam est sint nemo et est ipsa porro placeat quibusdam quia assumenda numquam molestias.</p>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.html">Beranda</a></li>
            <li class="current">Evaluasi</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Contact Section -->
    <section id="contact" class="contact section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

            @php
                $icons = [
                    'user' => 'bi-person',
                    'committee' => 'bi-calendar-event',
                    'division' => 'bi-diagram-3',
                ];
            @endphp
            <div class="col-lg-4">
                @foreach($masterTarget as $value => $label)
                <a href="{{ route('lp.eval', [$idCommittee, $value]) }}"
                    class="info-item d-flex"
                    style="text-decoration:none; color:inherit;"
                    data-aos="fade-up" data-aos-delay="300">

                    <i class="bi {{ $icons[$value] ?? 'bi-circle' }}"></i>

                    <div>
                        <h3>{{ $label }}</h3>
                        <p>Berikan evaluasi untuk {{ $label }}</p>
                    </div>
                </a>
                @endforeach
            </div>

          <div class="col-lg-8">
            <!-- INFO TARGET -->
             @php
                $targetLabels = [
                    'committee' => 'Kepanitiaan',
                    'division' => 'Divisi',
                    'user' => 'Panitia',
                ];

                $currentTarget = $target ?? 'committee';
            @endphp
            <div class="mb-3">
                <h4>
                    Evaluasi: 
                    <span class="badge bg-primary">
                        {{ $targetLabels[$currentTarget] ?? 'Kepanitiaan' }}
                    </span>
                </h4>
            </div>
            @php
                $scoreLabels = [
                    1 => 'Sangat Buruk',
                    2 => 'Buruk',
                    3 => 'Cukup',
                    4 => 'Baik',
                    5 => 'Sangat Baik',
                ];
            @endphp
            @if($target == 'committee' && $isEvaluatedCommittee)
                <div class="alert alert-warning">
                    ⚠️ Kamu sudah mengisi evaluasi untuk kepanitiaan ini.
                </div>
            @endif
            <form action="{{ route('lp.store.eval') }}" method="POST">
                @csrf
                 @if($target == 'division')
                <div class="mb-3">
                    <label>Pilih Divisi</label>
                    <select name="target_division" id="target_division" class="form-control" required>
                        <option value="">-- Pilih Divisi --</option>
                        @foreach($divisions as $index => $div)
                        <option value="{{ $div->idDivisions }}"
                            {{ old('target_division') == $div->idDivisions ? 'selected' : '' }}>
                            {{ $div->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @endif
                @if($target == 'user')
                <div class="mb-3">
                    <label>Pilih Panitia</label>
                    <select name="target_user" class="form-control" required>
                        <option value="">-- Pilih Panitia --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->idUsers }}">
                                {{ $user->firstname }} {{ $user->lastname }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                @if(!($target == 'committee' && $isEvaluatedCommittee))
                <div id="criteria-container">

                    <!-- forelse -> loop kaya foreach klo ada data -> klo ngaada masuk ke empty -->
                    @forelse($criterias as $criteria)
                    @php
                        $hasError = $errors->has('scores.' . $criteria->idEvaluationCriterias . '.score');
                    @endphp

                    <div class="card mb-3 p-3 shadow-sm criteria-card 
                        {{ $hasError ? 'border border-danger' : '' }}">

                        <!-- NAMA KRITERIA -->
                        <h5>
                            {{ $criteria->name }}

                            <!-- LABEL TARGET -->
                            <!-- <span class="badge bg-secondary">
                                {{ $criteria->target_type }}
                            </span> -->
                        </h5>

                        <!-- DESKRIPSI -->
                        <small class="text-muted">
                            {{ $criteria->description }}
                        </small>

                        <!-- JIKA ADA DIVISI -->
                        @if($criteria->division_name)
                            <div class="mt-2">
                                <span class="badge bg-info">
                                    Divisi: {{ $criteria->division_name }}
                                </span>
                            </div>
                        @endif

                        <!-- RATING -->
                        <div class="mt-3">
                            <label class="mb-3 d-block">Penilaian:</label>

                            <div class="d-flex justify-content-between">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="text-center" style="cursor:pointer;">
                                        <input type="radio" 
                                            name="scores[{{ $criteria->idEvaluationCriterias }}][score]" 
                                            value="{{ $i }}"
                                            {{ old('scores.' . $criteria->idEvaluationCriterias . '.score') == $i ? 'checked' : '' }}>

                                        <div class="mt-1">
                                            <strong>{{ $i }}</strong><br>
                                            <small>{{ $scoreLabels[$i] }}</small>
                                        </div>
                                    </label>
                                @endfor
                            </div>
                            <!-- ERROR PER KRITERIA -->
                            @error('scores.' . $criteria->idEvaluationCriterias . '.score')
                                <div class="text-danger small mt-1">
                                    ⚠️ Penilaian belum diisi
                                </div>
                            @enderror
                        </div>

                        <!-- KOMENTAR -->
                        <textarea 
                            name="scores[{{ $criteria->idEvaluationCriterias }}][comment]" 
                            class="form-control mt-2" 
                            placeholder="Komentar (opsional)">
                        </textarea>

                    </div>
                    @empty
                        <div class="alert alert-warning">
                            Tidak ada kriteria untuk target ini.
                        </div>

                    @endforelse
                </div>

                <!-- OVERALL RATING -->
                <div class="mt-4">
                    <label class="mb-2 d-block">
                        Rating {{ $targetLabels[$currentTarget] ?? 'Kepanitiaan' }} Secara Keseluruhan
                    </label>

                    <div class="star-rating">
                        @for($i = 5; $i >= 1; $i--)
                            <input type="radio" 
                                id="overall-star{{ $i }}" 
                                name="overall_score" 
                                value="{{ $i }}"
                                {{ old('overall_score') == $i ? 'checked' : '' }}>

                            <label for="overall-star{{ $i }}">★</label>
                        @endfor
                    </div>
                    @error('overall_score')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- KOMENTAR KESELURUHAN -->
                <div class="mt-3">
                    <label>Evaluasi {{ $targetLabels[$currentTarget] ?? 'Kepanitiaan' }} Keseluruhan</label>
                    <textarea name="overall_comment" class="form-control"></textarea>
                </div>

                <input type="hidden" name="target_committee" value="{{ $idCommittee }}">
                <input type="hidden" name="target" value="{{ $target }}">

                <button type="submit" class="btn btn-primary mt-3"
                    {{ ($target == 'committee' && $isEvaluatedCommittee) ? 'disabled' : '' }}
                    data-bs-toggle="modal" data-bs-target="#confirmModal">
                    Kirim Evaluasi
                </button>
                @endif
            </form>
          </div><!-- End Contact Form -->

          <!-- confrim modal -->
            <div class="modal fade" id="confirmModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5>Konfirmasi</h5>
                    </div>
                    <div class="modal-body">
                        Setelah submit, evaluasi tidak bisa diubah lagi.
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-primary" onclick="document.querySelector('form').submit()">
                            Ya, Submit
                        </button>
                    </div>
                    </div>
                </div>
            </div>
        </div>

      </div>

    </section><!-- /Contact Section -->

    <script>
        function loadCriteria(idDivision) {
            const idCommittee = "{{ $idCommittee }}";

            fetch(`/form-evaluation/get-criteria/${idCommittee}/${idDivision}`)
            .then(res => res.json())
            .then(data => {

                const container = document.getElementById('criteria-container');
                container.innerHTML = '';

                data.criterias.forEach(item => {

                    let labels = {
                        1: 'Sangat Buruk',
                        2: 'Buruk',
                        3: 'Cukup',
                        4: 'Baik',
                        5: 'Sangat Baik'
                    };

                    let ratingHTML = `
                        <div class="d-flex justify-content-between">
                    `;

                    for (let i = 1; i <= 5; i++) {
                        ratingHTML += `
                            <label style="cursor:pointer; text-align:center;">
                                <input type="radio" 
                                    name="scores[${item.idEvaluationCriterias}][score]" 
                                    value="${i}">
                                <div>
                                    <strong>${i}</strong><br>
                                    <small>${labels[i]}</small>
                                </div>
                            </label>
                        `;
                    }

                    ratingHTML += `</div>`;

                    container.innerHTML += `
                        <div class="card mb-3 p-3 shadow-sm criteria-card">

                            <h5>${item.name}</h5>

                            <small class="text-muted">${item.description}</small>

                            ${item.division_name ? 
                                `<div class="mt-2">
                                    <span class="badge bg-info">
                                        Divisi: ${item.division_name}
                                    </span>
                                </div>` 
                            : ''}

                            <div class="mt-3">
                                ${ratingHTML}
                            </div>

                            <textarea 
                                name="scores[${item.idEvaluationCriterias}][comment]" 
                                class="form-control mt-2"
                                placeholder="Komentar (opsional)">
                            </textarea>

                        </div>
                    `;
                });
            });
        }

        // 🔥 change event (CUMA 1)
        const divisionSelect = document.getElementById('target_division');
        if (divisionSelect) {
            divisionSelect.addEventListener('change', function(){
                loadCriteria(this.value);
            });
        }

        // 🔥 default load
        document.addEventListener('DOMContentLoaded', function () {
            const select = document.getElementById('target_division');
                        // cek apakah ada error dari Laravel
            const hasError = {{ $errors->any() ? 'true' : 'false' }};

            if (select && select.value && !hasError) {
                loadCriteria(select.value);
            }
        });

        document.querySelector("form").addEventListener("submit", function(e) {

            let valid = true;

            document.querySelectorAll('.criteria-card').forEach(card => {

                let radios = card.querySelectorAll('input[type="radio"][name^="scores"]');
                let checked = Array.from(radios).some(r => r.checked);

                let errorMsg = card.querySelector('.client-error');

                if (!checked) {
                    valid = false;

                    card.classList.add('border', 'border-danger');

                    if (!errorMsg) {
                        let div = document.createElement('div');
                        div.classList.add('text-danger', 'small', 'mt-2', 'client-error');
                        div.innerText = "⚠️ Penilaian belum diisi";
                        card.appendChild(div);
                    }

                } else {
                    card.classList.remove('border', 'border-danger');

                    if (errorMsg) errorMsg.remove();
                }
            });

            if (!valid) {
                e.preventDefault();
            }
        });
    </script>
@endsection