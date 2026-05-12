@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Perhitungan AHP'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                    <div id="ahp-alert"></div>
                
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center" >
                        <div class="badge-select-wrapper d-flex align-items-center gap-2">
                            <select name="division" id="division" class="badge-select text-sm division-select">
                                @foreach($masterDivision as $division)
                                <option value="{{ $division->idDivisions }}" @selected($division->idDivisions == $default)>
                                    {{ $division->name }}
                                </option>
                                @endforeach
                            </select>

                            <span id="consistency-badge" class="badge bg-secondary">
                                Belum dicek
                            </span>
                        </div>
                        <button type="button" id="btn-normalize" class="btn btn-dark btn-add ms-auto">Cek Bobot</button>
                    </div>
                    <div class="card-body px-2 pt-2 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0 text-center">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Kriteria 1</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7"></th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Kriteria 2</th>
                                    </tr>
                                </thead>
                                <tbody id="pairwise-body"> 
                                    @php
                                        function sliderValue($weight){
                                            $map = [
                                                ['value'=>9,'slider'=>-4],
                                                ['value'=>7,'slider'=>-3],
                                                ['value'=>5,'slider'=>-2],
                                                ['value'=>3,'slider'=>-1],
                                                ['value'=>1,'slider'=>0],
                                                ['value'=>1/3,'slider'=>1],
                                                ['value'=>1/5,'slider'=>2],
                                                ['value'=>1/7,'slider'=>3],
                                                ['value'=>1/9,'slider'=>4],
                                            ];

                                            foreach($map as $m){
                                                if(abs($weight - $m['value']) < 0.001){
                                                    return $m['slider'];
                                                }
                                            }

                                            return 0;
                                        }
                                    @endphp
                                    @foreach($pairwise as $row)
                                    <tr>
                                        <td>
                                            <h6 class="mb-0 text-sm">{{ $row['c1']->name }}</h6>
                                        </td>
                                        <td>
                                            <input type="range" 
                                                class="form-range ahp-slider"
                                                min = -4
                                                max =  4
                                                step = 1                                                
                                                data-c1="{{ $row['c1']->idAHPCriterias }}"
                                                data-c2="{{ $row['c2']->idAHPCriterias }}"
                                                value="{{ sliderValue($row['weight'] ?? 1) }}"
                                            >
                                            <div class="text-xs mt-1 text-muted text-center">
                                                <span class="slider-label">Sama penting</span>
                                            </div>
                                        </td>
                                        <td>
                                            <h6 class="mb-0 text-sm">{{ $row['c2']->name }}</h6>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                        </div> 
                        <div class="mt-3">
                            <button class="btn btn-primary toggle-detail collapsed d-inline-flex align-items-center justify-content-center" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAHP" aria-expanded="false" aria-controls="collapseAHP">
                                Lihat Rincian Perhitungan <i class="ni ni-bold-right icon-toggle ms-2"></i>
                            </button>

                            <div class="collapse mt-3" id="collapseAHP">

                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Rincian Perhitungan AHP</h5>
                                    </div>

                                    <div class="card-body" id="ahp-result">
                                    </div>

                                </div>

                            </div>

                        </div> 
                    </div>
                </div>
                   <!-- modal -->                
                <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmModalTitle">Konfirmasi pengubahan bobot</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="col-form-label" id="confirmModalLabel"></label>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="confirmNormalize" class="btn btn-primary">Simpan</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                </div>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
    <script>
        const scaleMap = {
            "-4": 9,
            "-3": 7,
            "-2": 5,
            "-1": 3,
            "0": 1,
            "1": 1/3,
            "2": 1/5,
            "3": 1/7,
            "4": 1/9
        };

        const labelMap = {
            "-4": "Mutlak lebih penting (C1)",
            "-3": "Sangat lebih penting (C1)",
            "-2": "Lebih penting (C1)",
            "-1": "Sedikit lebih penting (C1)",
            "0": "Sama penting",
            "1": "Sedikit lebih penting (C2)",
            "2": "Lebih penting (C2)",
            "3": "Sangat lebih penting (C2)",
            "4": "Mutlak lebih penting (C2)"
        };

        let originalSliders = [];

        function saveOriginalSliders(){
            originalSliders = [];

            document.querySelectorAll('.ahp-slider').forEach(slider => {
                originalSliders.push({
                    element: slider,
                    value: slider.value
                });
            });
        }
        
        document.querySelectorAll('.ahp-slider').forEach(slider => {    
            // set label awal
            const val = slider.value;
            slider.closest('td').querySelector('.slider-label').innerText = labelMap[val];

            // update saat slider digeser
            slider.addEventListener('input', function(){
                const val = this.value;
                const weight = scaleMap[val];
                const label = labelMap[val];

                this.dataset.weight = weight;
                this.closest('td').querySelector('.slider-label').innerText = label;
            });
        });

        function getSliderValue(weight){
            weight = parseFloat(weight);

            const map = [
                {value:9, slider:-4},
                {value:7, slider:-3},
                {value:5, slider:-2},
                {value:3, slider:-1},
                {value:1, slider:0},
                {value:1/3, slider:1},
                {value:1/5, slider:2},
                {value:1/7, slider:3},
                {value:1/9, slider:4}
            ];

            for(let m of map){
                if(Math.abs(weight - m.value) < 0.001){
                    return m.slider;
                }
            }

            return 0;
        }

        document.getElementById('division').addEventListener('change', function(){
            const idDivision = this.value;

            const baseRegUrl = "{{ session()->has('idCommittee') ? url('/members') : url('') }}";

            fetch(`${baseRegUrl}/ahp/division/${idDivision}/criterias`)
            .then(res => res.json())
            .then(data => {
                console.log(data.pairwise);

                renderAHPResult(data);
                updateConsistencyBadge(data.is_consistent);

                currentConsistency = data.is_consistent;
                currentUsed = data.isUsed;
                
                const tbody = document.getElementById('pairwise-body');
                tbody.innerHTML = '';

                data.pairwise.forEach(row => {
                    const tr = document.createElement('tr');

                    tr.innerHTML = `
                        <td>
                            <h6 class="mb-0 text-sm">${row.c1.name}</h6>
                        </td>
                        <td>
                            <input type="range" 
                                class="form-range ahp-slider"
                                min = -4
                                max =  4
                                step = 1                                                
                                data-c1="${row.c1.idAHPCriterias}"
                                data-c2="${row.c2.idAHPCriterias}"
                                value="${getSliderValue(row.weight)}"
                            >
                            <div class="text-xs mt-1 text-muted text-center">
                                <span class="slider-label">Sama penting</span>
                            </div>
                        </td>
                        <td>
                            <h6 class="mb-0 text-sm">${row.c2.name}</h6>
                        </td>
                    `;
                    tbody.appendChild(tr);

                    const slider = tr.querySelector('.ahp-slider');

                    const val = slider.value;
                    tr.querySelector('.slider-label').innerText = labelMap[val];

                    slider.addEventListener('input', function(){
                        const val = this.value;
                        const weight = scaleMap[val];
                        const label = labelMap[val];

                        this.dataset.weight = weight;
                        this.closest('td').querySelector('.slider-label').innerText = label;
                    });
                });
                
                saveOriginalSliders();
               
            });
        });

        function updateConsistencyBadge(isConsistent = null){

            const badge = document.getElementById('consistency-badge');

            if(isConsistent === true){

                badge.className = 'badge bg-success';
                badge.innerText = 'Konsisten';

            }else if(isConsistent === false){

                badge.className = 'badge bg-danger';
                badge.innerText = 'Tidak Konsisten';

            }else{

                badge.className = 'badge bg-secondary';
                badge.innerText = 'Belum dicek';
            }
        }

        function renderAHPResult(data = null){
            const resultBox = document.getElementById('ahp-result');

            if(!data || !data.criterias || data.criterias.length === 0){

                resultBox.innerHTML = `
                    <div class="alert alert-warning mb-0" style="color:white;">
                        Belum ada perhitungan AHP.
                    </div>
                `;
                return;
            }

            function num(val){
                if(val === null || val === undefined){
                    return '-';
                }
                return parseFloat(val).toFixed(6);
            }

            let pairwiseRows = '';
            let normalizeRows = '';
            let priorityRows = '';
            let weightedRows = '';
            let lambdaRows = '';

            data.criterias.forEach((c, i) => {

                pairwiseRows += `
                    <tr>
                        <td>${c.name}</td>
                        ${(data.matrix?.[i] || []).map(v => `<td>${num(v)}</td>`).join('')}
                    </tr>
                `;

                normalizeRows += `
                    <tr>
                        <td>${c.name}</td>
                        ${(data.normalized?.[i] || []).map(v => `<td>${num(v)}</td>`).join('')}
                    </tr>
                `;

                priorityRows += `
                    <tr>
                        <td>${c.name}</td>
                        <td>${num(data.priority_vector?.[i])}</td>
                    </tr>
                `;

                weightedRows += `
                    <tr>
                        <td>${c.name}</td>
                        <td>${num(data.weighted_sum?.[i])}</td>
                    </tr>
                `;

                lambdaRows += `
                    <tr>
                        <td>${c.name}</td>
                        <td>${num(data.lambda_vector?.[i])}</td>
                    </tr>
                `;
            });

            let sumColumns = `
                <tr>
                    <td><b>Jumlah</b></td>
                    ${(data.column_sum || [])
                        .map(v => `<td><b>${num(v)}</b></td>`)
                        .join('')}
                </tr>
            `;

            resultBox.innerHTML = `
                <h6>Matriks Pairwise</h6>

                <div class="table-responsive mb-4">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>Kriteria</th>
                                ${data.criterias.map(c => `<th>${c.name}</th>`).join('')}
                            </tr>
                        </thead>

                        <tbody>
                            ${pairwiseRows}
                            ${sumColumns}
                        </tbody>
                    </table>
                </div>

                <h6>Normalisasi Matriks</h6>

                <div class="table-responsive mb-4">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>Kriteria</th>
                                ${data.criterias.map(c => `<th>${c.name}</th>`).join('')}
                            </tr>
                        </thead>

                        <tbody>
                            ${normalizeRows}
                        </tbody>
                    </table>
                </div>

                <h6>Bobot Prioritas</h6>

                <table class="table table-bordered text-center mb-4">
                    <thead>
                        <tr>
                            <th>Kriteria</th>
                            <th>Bobot</th>
                        </tr>
                    </thead>

                    <tbody>
                        ${priorityRows}
                    </tbody>
                </table>

                <h6>Weighted Sum</h6>

                <table class="table table-bordered text-center mb-4">
                    <thead>
                        <tr>
                            <th>Kriteria</th>
                            <th>Nilai</th>
                        </tr>
                    </thead>

                    <tbody>
                        ${weightedRows}
                    </tbody>
                </table>

                <h6>Lambda Per Kriteria</h6>

                <table class="table table-bordered text-center mb-4">
                    <thead>
                        <tr>
                            <th>Kriteria</th>
                            <th>Lambda</th>
                        </tr>
                    </thead>

                    <tbody>
                        ${lambdaRows}
                    </tbody>
                </table>

                <div class="row">

                    <div class="col-md-3">
                        <div class="alert alert-light">
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">λ Max</p>
                            <h5 class="font-weight-bolder">
                                ${num(data.lambda_max)}
                            </h5>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="alert alert-light">
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">CI</p>
                            <h5 class="font-weight-bolder">
                                 ${num(data.CI)}
                            </h5>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="alert alert-light">
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">RI</p>
                            <h5 class="font-weight-bolder">
                                 ${num(data.RI)}
                            </h5>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="alert alert-${data.is_consistent ? 'success' : 'danger'}">
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">CR</p>
                             <h5 class="font-weight-bolder">
                                 ${num(data.CR)}
                            </h5>
                        </div>
                    </div>

                </div>
            `;
        }

        function runNormalize(){
                let comparisons = [];

                document.querySelectorAll('.ahp-slider').forEach(slider => {
                    const val = slider.value;
                    const weight = scaleMap[val];

                    comparisons.push({
                        c1: slider.dataset.c1,
                        c2: slider.dataset.c2,
                        value: weight
                    });
                });

                const baseRegUrl = "{{ session()->has('idCommittee') ? url('/members') : url('') }}";

                fetch(`${baseRegUrl}/ahp/normalize`,{
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        division: document.getElementById('division').value,
                        comparisons: comparisons
                    })
                })
                .then(res => res.json())
                .then(data => {
                    console.log(data);

                    let alertBox = document.getElementById("ahp-alert");

                    alertBox.innerHTML = `
                        <div class="alert alert-${data.type} alert-dismissible fade show" role="alert" style="color:white;">
                            <strong>${data.type === 'success' ? 'Success!' : 'Warning!'}</strong> ${data.message}
                        </div>
                    `;
                    
                    // hanya render kalau data AHP lengkap
                    if(data.criterias){

                        renderAHPResult(data);
                        updateConsistencyBadge(data.is_consistent);
                        currentConsistency = data.is_consistent;
                        currentUsed = data.is_used;

                        // collapse auto kebuka
                        const collapseEl = document.getElementById('collapseAHP');
                        const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapseEl);
                        bsCollapse.show();
                    }

                    setTimeout(()=>{
                        const alert = document.querySelector('#ahp-alert .alert');
                        if(alert){
                            const bsAlert = new bootstrap.Alert(alert);
                            bsAlert.close();
                        }
                    }, 3000); // auto close 3 detik
                });
        }

        document.getElementById('btn-normalize').addEventListener('click', function(){

            const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));

            if(currentUsed === true){
                    document.getElementById('confirmModalLabel').innerHTML = `
                        Bobot ini sudah digunakan pada penilaian interview.
                        <br><br>
                        Mengubah bobot dapat mempengaruhi hasil penilaian.
                        <br><br>
                        Apakah anda yakin ingin menghitung ulang bobot?
                    `;

                    confirmModal.show();
                    return;
            }
            else if(currentConsistency){
                    document.getElementById('confirmModalLabel').innerHTML = `
                        Bobot saat ini sudah <b>konsisten</b>. 
                        Jika diubah, hasil penilaian bisa berubah.
                        <br><br>
                        Apakah anda yakin ingin menghitung ulang bobot?
                    `;

                    confirmModal.show();
                    return;
            }else{
                runNormalize();
            }
        });

        // submit btn simpan di modal
        document.getElementById('confirmNormalize').addEventListener('click', function(){

            const modal = bootstrap.Modal.getInstance(document.getElementById('confirmModal'));
            modal.hide();

            runNormalize();

        });

        // reset isi scale klo klik btn batal di modal
        document.querySelector('#confirmModal .btn-secondary').addEventListener('click', function(){

            document.querySelectorAll('.ahp-slider').forEach((slider, index) => {

                const original = originalSliders[index];

                slider.value = original.value;
                slider.dataset.weight = scaleMap[original.value];

                slider.closest('td')
                    .querySelector('.slider-label')
                    .innerText = labelMap[original.value];

            });

        });

        const initialAHP = {
            criterias: @json($criterias),
            matrix: @json($result['matrix']),
            column_sum: @json($result['column_sum']),
            normalized: @json($result['normalized']),
            priority_vector: @json($result['priority_vector']),
            weighted_sum: @json($result['weighted_sum']),
            lambda_vector: @json($result['lambda_vector']),
            lambda_max: @json($result['lambda_max']),
            CI: @json($result['CI']),
            CR: @json($result['CR']),
            RI: @json($result['RI']),
            is_consistent: @json($result['is_consistent']),
            is_used: @json($isUsed)
        };

        let currentConsistency = initialAHP.is_consistent;
        let currentUsed = initialAHP.is_used ?? false;

        renderAHPResult(initialAHP);
        updateConsistencyBadge(initialAHP.is_consistent);
        saveOriginalSliders();
    </script>
@endsection
