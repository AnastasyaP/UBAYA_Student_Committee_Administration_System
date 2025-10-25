@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Committee History'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center" >
                        <h6>Committees History</h6>
                        @if(!$activeCommittee)
                        <a href="{{ route('committees.add') }}" target=""
                            class="btn btn-dark btn-add w-15 mb-3">Add Committee</a>
                        @endif
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Name</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Description</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Start Period</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            End Period</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Start Regis</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            End Regis</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Status</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($committees as $committee)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    <img src="/img/team-2.jpg" class="avatar avatar-sm me-3"
                                                        alt="user1">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $committee->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $committee->organizerName }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs text-secondary mb-0">{{ Str::limit($committee->description, 30, '..') }}</p>
                                        </td>
                             
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ $committee->start_period }}</span>
                                        </td>
                                           <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ $committee->end_period }}</span>
                                        </td>
                                           <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ $committee->start_regis }}</span>
                                        </td>
                                           <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ $committee->end_regis }}</span>
                                        </td>
                                        <td>
                                            @if($committee->is_active == 1)
                                                <span class="badge bg-success">On Going</span>
                                            @else
                                                <span class="badge bg-danger">Ended</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <a href="javascript:;" class="text-secondary font-weight-bold text-xs"
                                                data-toggle="tooltip" data-original-title="Edit user">
                                                See Evaluation
                                            </a>
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
@endsection
