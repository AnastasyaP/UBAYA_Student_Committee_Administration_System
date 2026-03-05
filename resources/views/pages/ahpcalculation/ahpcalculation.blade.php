@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'AHP Calculation'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                    @if(session('success'))
                        <div>
                            <div class="alert alert-success auto-close-alert alert-dismissible fade show" role="alert">
                                <strong>Success!</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    @elseif(session('warning'))
                        <div>
                            <div class="alert alert-warning auto-close-alert alert-dismissible fade show" role="alert">
                                <strong>Warning!</strong> {{ session('warning') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    @endif
                
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center" >
                        <div class="badge-select-wrapper">
                            <select name="division" id="division" class="badge-select text-sm division-select">
                                @foreach($masterDivision as $division)
                                <option value="{{ $division->idDivisions }}" @selected($division->idDivisions == $default)>{{ $division->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button id="btn-normalize" target="" class="btn btn-dark btn-add ms-auto">Normalize</button>
                    </div>
                    <div class="card-body px-2 pt-2 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0 text-center">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Criteria 1</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7"></th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Criteria 2</th>
                                    </tr>
                                </thead>
                                <tbody id="pairwise-body"> 
                                    @php
                                        $reverseScale = [
                                            9 => -4,
                                            7 => -3,
                                            5 => -2,
                                            3 => -1,
                                            1 => 0,
                                            0.3333333333 => 1,
                                            0.2 => 2,
                                            0.1428571429 => 3,
                                            0.1111111111 => 4
                                        ];
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
                                                value="{{ $reverseScale[$row['weight'] ?? 1] ?? 0 }}"
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
            const val = slider.value;
            const label = labelMap[val];

            slider.closest('td').querySelector('.slider-label').innerText = label;
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

        const reverseScale = {   
            "9": -4,
            "7": -3,
            "5": -2,
            "3": -1,
            "1": 0,
            "0.3333333333": 1,
            "0.2": 2,
            "0.1428571429": 3,
            "0.1111111111": 4
        };

        document.getElementById('division').addEventListener('change', function(){
            const idDivision = this.value;

            fetch(`/ahp/division/${idDivision}/criterias`)
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

            fetch(`/ahp/normalize`,{
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
                alert("Normalize done. Check console");
            });
        });
    </script>
@endsection
