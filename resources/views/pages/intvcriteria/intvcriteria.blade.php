@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Kriteria Interview'])
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
                
                @foreach($intvCriteria as $idDivision => $criterias)
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center" >
                        <h6>{{ $criterias->first()->division }}</h6>
                        <a href="{{ route('intvcriteria.add', ['idDivision' => $idDivision]) }}" target=""
                            class="btn btn-dark btn-add ms-auto">Tambah Kriteria</a>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Kriteria Interview</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Kriteria AHP</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7" colspan=2>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $hascriteria = false; @endphp

                                    @foreach($criterias as $item)
                                    @if($item->name)
                                    @php $hascriteria = true; @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                               
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $item->name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <h6 class="mb-0 text-sm">{{ $item->ahpCriteria }}</h6>
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
                                    @endif
                                    @endforeach

                                    @if(!$hascriteria)
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">
                                                Tidak ada Kriteria Interview yang Tersedia
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            
                        </div>    
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
    <script>
        setTimeout(()=>{
            const alert = document.querySelector('.alert');
            if(alert){
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 3000); // auto close 3 detik
    </script>
@endsection
