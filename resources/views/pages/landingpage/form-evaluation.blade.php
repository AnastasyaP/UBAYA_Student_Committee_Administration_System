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
            <form action="#" method="POST">
                @csrf
                 @if($target == 'division')
                <div class="mb-3">
                    <label>Pilih Divisi</label>
                    <select name="target_division" id="target_division" class="form-control" required>
                        <option value="">-- Pilih Divisi --</option>
                        @foreach($divisions as $index => $div)
                        <option value="{{ $div->idDivisions }}"
                            {{ $index == 0 ? 'selected' : '' }}>
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
                <div id="criteria-container">
                    @forelse($criterias as $criteria)
                    <div class="card mb-3 p-3 shadow-sm">

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
                            <label>Nilai:</label><br>

                            @for($i = 1; $i <= 5; $i++)
                                <label style="cursor:pointer; margin-right:10px;">
                                    <input type="radio" 
                                        name="scores[{{ $criteria->idEvaluationCriterias }}][score]" 
                                        value="{{ $i }}" required>
                                    {{ $i }}
                                </label>
                            @endfor
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

                <!-- KOMENTAR UMUM -->
                <div class="mt-3">
                    <label>Komentar Umum</label>
                    <textarea name="overall_comment" class="form-control"></textarea>
                </div>

                <button type="submit" class="btn btn-primary mt-3">
                    Simpan Evaluasi
                </button>

            </form>
          </div><!-- End Contact Form -->

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

                    let ratingHTML = '';

                    for (let i = 1; i <= 5; i++) {
                        ratingHTML += `
                            <label style="margin-right:10px;">
                                <input type="radio" 
                                    name="scores[${item.idEvaluationCriterias}][score]" 
                                    value="${i}" required> ${i}
                            </label>
                        `;
                    }

                    container.innerHTML += `
                        <div class="card mb-3 p-3 shadow-sm">

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
        document.getElementById('target_division').addEventListener('change', function(){
            loadCriteria(this.value);
        });

        // 🔥 default load
        document.addEventListener('DOMContentLoaded', function () {
            const select = document.getElementById('target_division');
            if (select && select.value) {
                loadCriteria(select.value);
            }
        });
    </script>
@endsection