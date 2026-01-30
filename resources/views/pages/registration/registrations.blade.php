@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Registration'])
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
                        <h6>Registrations List</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Name</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            NRP</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Division</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Status</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7" colspan=3>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($registrations as $regis)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <!-- <div>
                                                    <img src="{{ asset('storage/' . $regis->cv) }}" class="avatar avatar-sm me-3"
                                                        alt="division picture">
                                                </div> -->
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $regis->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $regis->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <h6 class="mb-0 text-sm">{{ $regis->nrp }}</h6>
                                        </td>
                                        <td>
                                            <h6 class="mb-0 text-sm">{{ $regis->division }}</h6>
                                        </td>
                                        <td>
                                            @if ($regis->status == "pending")
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($regis->status == 'accepted')
                                                <span class="badge bg-success">Accepted</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <form action="{{ route('view.regis', ['idRegis' => $regis->idRegis]) }}" method="GET">
                                                <button type="submit" class="btn btn-warning btn-sm">Details</button>
                                            </form>
                                        </td>
                                        @if($regis->status == 'pending')
                                        <td class="align-middle">
                                            <form id="acceptForm-{{ $regis->idRegis }}" action="{{ route('accept.regis', ['idRegis' => $regis->idRegis]) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button 
                                                    type="button" 
                                                    class="btn btn-action btn-success btn-sm" 
                                                    value="accepted" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#confirmModal" 
                                                    data-name="{{ $regis->name }}"
                                                    data-form="acceptForm-{{ $regis->idRegis }}" 
                                                    data-action="accept"
                                                    data-color="btn-success"
                                                    data-title="Confirm Accept"
                                                    data-message="Are you sure you want to accept"
                                                >Accept</button>                                                
                                            </form>                                         
                                        </td>
                                        
                                        <td class="align-middle">
                                            <form id="rejectForm-{{ $regis->idRegis }}" action="{{ route('reject.regis', ['idRegis' => $regis->idRegis]) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button 
                                                    type="button" 
                                                    class="btn btn-action btn-danger btn-sm" 
                                                    value="rejected" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target= "#confirmModal" 
                                                    data-name="{{ $regis->name }}"
                                                    data-form="rejectForm-{{ $regis->idRegis }}"
                                                    data-action="reject"
                                                    data-color="btn-danger"
                                                    data-title="Confirm Reject"
                                                    data-message="Are you sure you want to reject"
                                                >Reject</button>
                                            </form>
                                        </td>
                                        @endif
                                    </tr>
                                   @endforeach
                                </tbody>
                            </table>
                            <!-- confirm modal  -->
                            <div class="modal fade" id="confirmModal" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalTitle">Confirm Action</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <span id="modalMessage">Are you sure?</span>    
                                            <strong id="modalName"></strong>?
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn" id="confirmBtn">Yes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>    
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
    <script>
        setTimeout(()=>{
            document.querySelectorAll('.auto-close-alert').forEach(a => {
                new bootstrap.Alert(a).close();
            });
        }, 3000); // auto close 3 detik


        // confirm modal
        document.addEventListener('DOMContentLoaded', function () {
            const modalEl = document.getElementById('confirmModal');
            const modal = new bootstrap.Modal(modalEl);
            const modalTitle = document.getElementById('modalTitle');
            const modalMessage = document.getElementById('modalMessage');
            const modalName = document.getElementById('modalName');
            const confirmBtn = document.getElementById('confirmBtn');

            let activeForm = null;

    

            document.querySelectorAll('.btn.btn-action').forEach(btn =>{
                btn.addEventListener('click', function(){
                    const name = this.dataset.name;
                    const formId = this.dataset.form;
                    const title = this.dataset.title;
                    const message = this.dataset.message;
                    const color = this.dataset.color;
                    
                    activeForm = document.getElementById(formId);

                    modalTitle.textContent = title;
                    modalMessage.textContent = message;
                    modalName.textContent = name;
                    
                    confirmBtn.className = 'btn '  + color;
                    modal.show();
                });   
            });

            confirmBtn.onclick = ()=> activeForm.submit();
        });
    </script>
@endsection
