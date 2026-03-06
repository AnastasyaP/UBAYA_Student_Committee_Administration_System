@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Member'])
    <div class="container-fluid py-4">
        
        <div id="alert-container"></div>

        <div class="row">
            <div class="col-12">
                    
                @foreach($members as $divisionNames => $divisionMembers)
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center" >
                        <h6>{{ $divisionNames }}</h6>
                        <a href="#"
                            class="btn btn-dark btn-add w-15 mb-3">
                            Add Member
                        </a>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Name</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Division</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Position</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7" colspan=3>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $hasMember = false; @endphp

                                    @foreach($divisionMembers as $m)
                                    @if($m->name)
                                    @php $hasMember = true; @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                               
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $m->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $m->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <h6 class="mb-0 text-sm">{{ $m->division }}</h6>
                                        </td>
                                        <td>
                                            <h6 class="mb-0 text-sm position-label">{{ $m->position }}</h6>
                                        </td>
                                        <td>
                                            <div class="badge-select-wrapper">
                                                <select name="position" class="badge-select text-sm position-select" data-member="{{ $m->idUser }}" data-division="{{ $m->idDivision }}">
                                                    <option value="bph" @selected($m->position == 'bph')>BPH</option>
                                                    <option value="koor" @selected($m->position =='koor')>Koor</option>
                                                    <option value="wakoor" @selected($m->position == 'wakoor')>Wakoor</option>
                                                    <option value="anggota" @selected($m->position == 'anggota')>Anggota</option>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach

                                    @if(!$hasMember)
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">
                                                No accepted members
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
        document.querySelectorAll('.position-select').forEach(select => {
            select.addEventListener('change', function(){
                const newPosition = this.value;
                const memberId = this.dataset.member;
                const divisionId = this.dataset.division;

                fetch(`/update-position/${memberId}/${divisionId}/${newPosition}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if(!res.ok){
                        throw new Error('Network response was not ok');
                    }
                    return res.json();
                })
                .then(data => {
                    console.log(data);

                    if(data.success){
                        const container = document.getElementById('alert-container');

                        this.closest('tr').querySelector('.position-label').textContent = newPosition;

                        document.getElementById('alert-container').innerHTML = `
                            <div class="alert alert-success alert-dismissible fade show">
                                <strong>Success!</strong> ${data.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `;
                        setTimeout(()=>{
                            location.reload();
                        },1000);
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Error updating position');
                });
            });
        });
     
    </script>
@endsection
