@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Interview Criteria'])
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
                            class="btn btn-dark btn-add ms-auto">Add Question</a>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Question</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Max Score</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            AHP Criteria</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7" colspan=2>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $hasquestion = false; @endphp

                                    @foreach($criterias as $item)
                                    @if($item->question)
                                    @php $hasquestion = true; @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                               
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $item->question }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <h6 class="mb-0 text-sm">{{ $item->max_score }}</h6>
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
                                            <form action="" method="POST" onsubmit="return confirm('Are you sure want to delete this question?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach

                                    @if(!$hasquestion)
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">
                                                No Interview question available
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
@endsection
