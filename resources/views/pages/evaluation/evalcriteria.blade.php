@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Kriteria Evaluasi'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                    <div id="ahp-alert"></div>
                
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center" >
                        <div class="badge-select-wrapper">
                            <select name="target" id="target" class="badge-select text-sm target-select">
                                @foreach($masterTarget as $value => $label)
                                <option value="{{ $value }}" @selected($target == $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <!-- <span class="badge bg-success">valid</span> -->
                        </div>
                        <a href="{{ route('evalcriteria.add') }}" target=""
                            class="btn btn-dark btn-add ms-auto">Tambah Kriteria</a>                    
                    </div>
                    <div class="card-body px-2 pt-2 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Kriteria Evaluasi</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Target</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7" colspan=2>
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="criteria-body"> 
                                    @foreach($criterias as $criteria)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                               
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $criteria->name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <h6 class="mb-0 text-sm">{{ $criteria->target_type }}</h6>
                                        </td>
                                        <td class="align-middle">
                                            <form action="" method="GET">
                                                <button type="submit" class="btn btn-warning btn-sm">Edit</button>                                                
                                            </form>                                         
                                        </td>
                                         <td class="align-middle">
                                            <form action="" method="POST" onsubmit="return confirm('Are you sure want to delete this criteria?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
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
        document.getElementById('target').addEventListener('change', function(){
            const target = this.value;

            fetch(`/evaluation-criteria/${target}`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('criteria-body');
                tbody.innerHTML = '';

                data.criterias.forEach(item => {
                    const tr  = document.createElement('tr');

                    tr.innerHTML = `
                    <td>
                        <div class="d-flex px-2 py-1">                               
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">${item.name}</h6>
                            </div>
                        </div>
                    </td>
                    <td>
                        <h6 class="mb-0 text-sm">${item.target_type}</h6>
                    </td>
                    <td class="align-middle">
                        <form action="" method="GET">
                            <button type="submit" class="btn btn-warning btn-sm">Edit</button>                                                
                        </form>                                         
                    </td>
                    <td class="align-middle">
                        <form action="" method="POST" onsubmit="return confirm('Are you sure want to delete this criteria?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                `;

                tbody.appendChild(tr);

                });
                
            });
        });
    </script>
@endsection
