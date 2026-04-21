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
                        <div class="badge-select-wrapper">
                            <select name="division" id="division" class="badge-select text-sm division-select">
                                @foreach($masterDivision as $division)
                                <option value="{{ $division->idDivisions }}" @selected($division->idDivisions == $default)>{{ $division->name }}</option>
                                @endforeach
                            </select>
                            <!-- <span class="badge bg-success">valid</span> -->
                        </div>
                        <button id="btn-normalize" target="" class="btn btn-dark btn-add ms-auto">Cek Bobot</button>
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

               
            });
        });

        document.getElementById('btn-normalize').addEventListener('click', function(){
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
                    <div class="alert alert-${data.type} alert-dismissible fade show" role="alert">
                        <strong>${data.type === 'success' ? 'Success!' : 'Warning!'}</strong> ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;

                setTimeout(()=>{
                    const alert = document.querySelector('#ahp-alert .alert');
                    if(alert){
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                }, 3000); // auto close 3 detik
            });
        });
    </script>
@endsection
