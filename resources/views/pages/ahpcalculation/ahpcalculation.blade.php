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
                        <a href="" target=""
                            class="btn btn-dark btn-add ms-auto">Normalize</a>
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
            "-4": 1/9,
            "-3": 1/7,
            "-2": 1/5,
            "-1": 1/3,
            "0": 1,
            "1": 3,
            "2": 5,
            "3": 7,
            "4": 9
        };

        const labelMap = {
            "-4": "Mutlak lebih penting (C2)",
            "-3": "Sangat lebih penting (C2)",
            "-2": "Lebih penting (C2)",
            "-1": "Sedikit lebih penting (C2)",
            "0": "Sama penting",
            "1": "Sedikit lebih penting (C1)",
            "2": "Lebih penting (C1)",
            "3": "Sangat lebih penting (C1)",
            "4": "Mutlak lebih penting (C1)"
        };


        document.getElementById('division').addEventListener('change', function(){
            const idDivision = this.value;

            fetch(`/ahp/division/${idDivision}/criterias`)
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('pairwise-body');
                tbody.innerHTML = '';

                data.pairwise.forEach(row => {
                    const tr = document.createElement('tr');

                    tr.innerHTML = `
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
                            >
                            <div class="text-xs mt-1 text-muted text-center">
                                <span class="slider-label">Sama penting</span>
                            </div>
                        </td>
                        <td>
                            <h6 class="mb-0 text-sm">{{ $row['c2']->name }}</h6>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });

                document.querySelectorAll('.ahp-slider').forEach(slider => {
                    slider.addEventListener('input', function(){
                        const val = this.value;
                        const weight = scaleMap[val];
                        const label = labelMap[val];

                        this.dataset.weight = weight;
                        this.closest('td').querySelector('.slider-label').innerText = label;
                    });
                });
            })
        })
    </script>
@endsection
