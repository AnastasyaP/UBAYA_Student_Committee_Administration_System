@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Registrasi'])
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
                      <div class="card-header pb-0" >
                        <div class="badge-select-wrapper d-flex justify-content-between align-items-center">
                            <h6 class="division-title">Rekomendasi Divisi {{ $masterDivision->first()->name ?? 'Tidak ada divisi' }}</h6>
                            <select name="division" class="badge-select text-sm division-select">
                                @foreach($masterDivision as $division)
                                <option value="{{ $division->idDivisions }}">{{ $division->name }}</option>
                                @endforeach
                            </select>
                            <!-- <span class="badge bg-success">valid</span> -->
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Peringkat</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Nama</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            NRP</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Divisi</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Status</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7" colspan=3>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="reg-division-body">
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            Belum ada data kandidat di divisi ini
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>    
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header pb-0" >
                        <div class="badge-select-wrapper d-flex justify-content-between align-items-center">
                            <h6>Daftar Registrasi</h6>
                            <select name="status-select" id="status-select" class="badge-select text-sm status-select">
                                <option value="">Semua</option>
                                <option value="menunggu">Menunggu</option>
                                <option value="dinilai">Dinilai</option>
                                <option value="diterima">Diterima</option>
                                <option value="ditolak">Ditolak </option>
                            </select>
                            <!-- <span class="badge bg-success">valid</span> -->
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Nama</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            NRP</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Divisi</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Status</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7" colspan=3>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="reg-status-body">
                                    @forelse($registrations as $regis)
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
                                            @if ($regis->status == "menunggu")
                                                <span class="badge bg-warning">Menunggu</span>
                                            @elseif($regis->status == 'dinilai')
                                                <span class="badge bg-info">Dinilai</span>
                                            @elseif($regis->status == 'diterima')
                                                <span class="badge bg-success">Diterima</span>
                                            @else
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <form action="{{ route(routeForMember('view.regis', 'members.view.regis'), ['idRegis' => $regis->idRegis]) }}" method="GET">
                                                <button type="submit" class="btn btn-warning btn-sm">Detail</button>
                                            </form>
                                        </td>
                                        <td class="align-middle">
                                            @if($regis->status == 'menunggu')
                                            <form action="{{ route(routeForMember('intvscoring', 'members.intvscoring')) }}" method="POST">
                                                @csrf
                                                <input type="hidden" value="{{ $regis->idMahasiswa }}" name="idMahasiswa">
                                                <input type="hidden" value="{{ $regis->idDivision }}" name="idDivision">
                                                <input type="hidden" value="{{ $regis->idRegis }}" name="idRegis">
                                                <button type="submit" class="btn btn-success btn-sm">Nilai</button>
                                            </form>
                                        @elseif($regis->status == 'dinilai')
                                            <form id="acceptForm-{{ $regis->idRegis }}" action="{{ route(routeForMember('accept.regis', 'members.accept.regis'), ['idRegis' => $regis->idRegis]) }}" method="POST">
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
                                                    data-title="Konfirmasi Penerimaan"
                                                    data-message="Apakah anda yakin untuk menerima"
                                                >Terima</button>                                                
                                            </form>                                         
                                        </td>                                        
                                        <td class="align-middle">
                                            <form id="rejectForm-{{ $regis->idRegis }}" action="{{ route(routeForMember('reject.regis', 'members.reject.regis'), ['idRegis' => $regis->idRegis]) }}" method="POST">
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
                                                    data-title="Konfirmasi Penolakan"
                                                    data-message="Apakah anda yakin untuk menolak"
                                                >Tolak</button>
                                            </form>
                                        </td>
                                        @endif
                                        
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">
                                                Belum ada data registrasi yang tersedia
                                            </td>
                                        </tr>
                                   @endforelse
                                </tbody>
                            </table>
                        </div>    
                    </div>
                </div>
                <!-- confirm modal  -->
                            <div class="modal fade" id="confirmModal" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalTitle">Konfirmasi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <span id="modalMessage">Apakah Anda Yakin?</span>    
                                            <strong id="modalName"></strong>?
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="button" class="btn" id="confirmBtn">Yakin</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer') 
    </div>
    <script>
        // load division select saat pertama kali
        document.addEventListener('DOMContentLoaded', function () {
            const select = document.querySelector('.division-select');
            const title = document.querySelector('.division-title');

            if(select){
                const selectedText = select.options[select.selectedIndex].text;
                title.textContent = "Rekomendasi Divisi " + selectedText;

                const baseRegUrl = "{{ session()->has('idCommittee') 
                    ? url('/members') 
                    : url('') }}";
                    
                fetch(`${baseRegUrl}/registration/division/${select.value}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin' // 🔥 INI WAJIB
                })
                    .then(res => res.json())
                    .then(data =>{
                         console.log(data); // 🔥 tambahan
                        console.log(data.regByDivision); // 🔥 tambahan
                        console.log(Array.isArray(data.regByDivision)); // 🔥 tambahan
                        renderTable(data)
                    });
            }
        });

        function renderTable(data){
            const tbody = document.getElementById('reg-division-body');
            tbody.innerHTML = '';

            if (!data.regByDivision || Object.keys(data.regByDivision).length === 0) {
                tbody.innerHTML = `<tr>
                    <td colspan="8" class="text-center">
                        Belum ada data kandidat di divisi ini
                    </td>
                </tr>`;
                return;
            }

            data.regByDivision.forEach((row, index) => {
                let statusBadge = '';

                if (row.status === 'menunggu') {
                    statusBadge = `<span class="badge bg-warning">Menunggu</span>`;
                } else if (row.status === 'dinilai') {
                    statusBadge = `<span class="badge bg-info">Dinilai</span>`;
                } else if (row.status === 'diterima') {
                    statusBadge = `<span class="badge bg-success">Diterima</span>`;
                } else {
                    statusBadge = `<span class="badge bg-danger">Ditolak</span>`;
                }

                const baseRegUrl = "{{ session()->has('idCommittee') ? url('/members') : url('') }}";

                const tr = document.createElement('tr');
                tr.innerHTML = `
                            <td class="text-center">${index+1}</td>
                            <td>
                                <h6 class="mb-0 text-sm">${row.name}</h6>
                                <p class="text-xs text-secondary mb-0">${row.email}</p>
                            </td>
                            <td>${row.nrp}</td>
                            <td>${row.division}</td>
                            <td>${statusBadge}</td>
                            <td>
                                <a href="${baseRegUrl}/view-registrations/${row.idRegis}" 
                                class="btn btn-warning btn-sm">Detail</a>
                            </td>
                            <td class="align-middle">
                                <form id="acceptForm-${row.idRegis}" action="${baseRegUrl}/registrations/accepted/${row.idRegis}" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="PUT">
                                    <button 
                                        type="button" 
                                        class="btn btn-action btn-success btn-sm" 
                                        value="accepted" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#confirmModal" 
                                        data-name="${row.name}"
                                        data-form="acceptForm-${row.idRegis}" 
                                        data-action="accept"
                                        data-color="btn-success"
                                        data-title="Konfirmasi Penerimaan"
                                        data-message="Apakah anda yakin untuk menerima"
                                    >Terima</button>                                                
                                </form>                                         
                            </td>                                        
                            <td class="align-middle">
                                <form id="rejectForm-${row.idRegis}" action="${baseRegUrl}/registrations/rejected/${row.idRegis}" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="PUT">
                                    <button 
                                        type="button" 
                                        class="btn btn-action btn-danger btn-sm" 
                                        value="rejected" 
                                        data-bs-toggle="modal" 
                                        data-bs-target= "#confirmModal" 
                                        data-name="${row.name}"
                                        data-form="rejectForm-${row.idRegis}"
                                        data-action="reject"
                                        data-color="btn-danger"
                                        data-title="Konfirmasi Penolakan"
                                        data-message="Apakah anda yakin untuk menolak"
                                    >Tolak</button>
                                </form>
                            </td>
                        `;

                tbody.appendChild(tr);
            });
        }

        // division select
        document.querySelectorAll('.division-select').forEach(select => {
            select.addEventListener('change', function() {

            const baseRegUrl = "{{ session()->has('idCommittee') 
                ? url('/members') 
                : url('') }}";

                const idDivision = this.value;

                const card = this.closest('.card');
                const title = card.querySelector('.division-title');
                const selectedText = this.options[this.selectedIndex].text;
                title.textContent = "Rekomendasi Divisi " + selectedText;

                
                fetch(`${baseRegUrl}/registration/division/${idDivision}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin' // 🔥 INI WAJIB
                })
                .then(res => res.json())
                .then(data => {
                    console.log(data); // 🔥 tambahan
                    console.log(data.regByDivision); // 🔥 tambahan
                    console.log(Array.isArray(data.regByDivision)); // 🔥 tambahan

                    const tbody = this.closest('.card').querySelector('tbody');

                    tbody.innerHTML = '';

                    if (!data.regByDivision || Object.keys(data.regByDivision).length === 0) {
                        tbody.innerHTML = `<tr><td colspan="8" class="text-center">Belum ada data kandidat di divisi ini</td></tr>`;
                        return;
                    }
                    
                    data.regByDivision.forEach((row, index) => {

                        let statusBadge = '';

                        if (row.status === 'menunggu') {
                            statusBadge = `<span class="badge bg-warning">Menunggu</span>`;
                        } else if (row.status === 'dinilai') {
                            statusBadge = `<span class="badge bg-info">Dinilai</span>`;
                        } else if (row.status === 'diterima') {
                            statusBadge = `<span class="badge bg-success">Diterima</span>`;
                        } else {
                            statusBadge = `<span class="badge bg-danger">Ditolak</span>`;
                        }

                        const tr = document.createElement('tr');

                        tr.innerHTML = `
                            <td class="text-center">${index+1}</td>
                            <td>
                                <h6 class="mb-0 text-sm">${row.name}</h6>
                                <p class="text-xs text-secondary mb-0">${row.email}</p>
                            </td>
                            <td>${row.nrp}</td>
                            <td>${row.division}</td>
                            <td>${statusBadge}</td>
                            <td>
                                <a href="${baseRegUrl}/view-registrations/${row.idRegis}" 
                                class="btn btn-warning btn-sm">Detail</a>
                            </td>
                            <td class="align-middle">
                                <form id="acceptForm-${row.idRegis}" action="${baseRegUrl}/registrations/accepted/${row.idRegis}" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="PUT">
                                    <button 
                                        type="button" 
                                        class="btn btn-action btn-success btn-sm" 
                                        value="accepted" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#confirmModal" 
                                        data-name="${row.name}"
                                        data-form="acceptForm-${row.idRegis}" 
                                        data-action="accept"
                                        data-color="btn-success"
                                        data-title="Konfirmasi Penerimaan"
                                        data-message="Apakah anda yakin untuk menerima"
                                    >Terima</button>                                                
                                </form>                                         
                            </td>                                        
                            <td class="align-middle">
                                <form id="rejectForm-${row.idRegis}" action="${baseRegUrl}/registrations/rejected/${row.idRegis}" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="PUT">
                                    <button 
                                        type="button" 
                                        class="btn btn-action btn-danger btn-sm" 
                                        value="rejected" 
                                        data-bs-toggle="modal" 
                                        data-bs-target= "#confirmModal" 
                                        data-name="${row.name}"
                                        data-form="rejectForm-${row.idRegis}"
                                        data-action="reject"
                                        data-color="btn-danger"
                                        data-title="Konfirmasi Penolakan"
                                        data-message="Apakah anda yakin untuk menolak"
                                    >Tolak</button>
                                </form>
                            </td>
                        `;

                        tbody.appendChild(tr);
                    });
                });
            });
        });

        // status select
        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', function(){
                const status = this.value;

                let url = "{{ session()->has('idCommittee') 
                    ? url('/members/registration') 
                    : url('/registration') }}";

                if(status){
                    url += `?status=${status}`;
                }

                fetch(url)
                .then(res => res.json())
                .then(data => {
                    const tbody = document.getElementById('reg-status-body');

                    tbody.innerHTML = '';

                    if(!data.regByStatus || data.regByStatus.length === 0){
                        tbody.innerHTML = `<tr><td colspan="8" class="text-center">Belum ada data registrasi yang tersedia</td></tr>`;
                        return;
                    }

                    data.regByStatus.forEach(row => {
                        
                        let statusBadge = '';
                        if(row.status === 'menunggu'){
                            statusBadge = `<span class="badge bg-warning">Menunggu</span>`;
                        } else if(row.status === 'dinilai'){
                            statusBadge = `<span class="badge bg-info">Dinilai</span>`;
                        } else if(row.status === 'diterima'){
                            statusBadge = `<span class="badge bg-success">Diterima</span>`;
                        } else {
                            statusBadge = `<span class="badge bg-danger">Ditolak</span>`;
                        }

                        const baseRegUrl = "{{ session()->has('idCommittee') 
                            ? url('/members') 
                            : url('') }}";
                                        
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>
                                <h6 class="mb-0 text-sm">${row.name}</h6>
                                <p class="text-xs text-secondary mb-0">${row.email}</p>
                            </td>
                            <td>${row.nrp}</td>
                            <td>${row.division}</td>
                            <td>${statusBadge}</td>
                            <td>
                                <a href="${baseRegUrl}/view-registrations/${row.idRegis}" 
                                class="btn btn-warning btn-sm">Detail</a>
                            </td>
                            <td>
                                ${row.status === 'menunggu' ? `
                                    <form action="${baseRegUrl}/intv-scoring" method="POST">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" value="${row.idMahasiswa}" name="idMahasiswa">
                                        <input type="hidden" value="${row.idDivision}" name="idDivision">
                                        <input type="hidden" value="${row.idRegis}" name="idRegis">
                                        <button type="submit" class="btn btn-success btn-sm">Nilai</button>
                                    </form>
                                ` : ''} 
                            ${row.status === 'dinilai' ? `
                                    <form id="acceptForm-${row.idRegis}" action="${baseRegUrl}/registrations/accepted/${row.idRegis}" method="POST">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="PUT">
                                        <button 
                                            type="button" 
                                            class="btn btn-action btn-success btn-sm" 
                                            value="accepted" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#confirmModal" 
                                            data-name="${row.name}"
                                            data-form="acceptForm-${row.idRegis}" 
                                            data-action="accept"
                                            data-color="btn-success"
                                            data-title="Konfirmasi Penerimaan"
                                            data-message="Apakah anda yakin untuk menerima"
                                        >Terima</button>                                                
                                    </form>                                         
                                </td>                                        
                                <td class="align-middle">
                                    <form id="rejectForm-${row.idRegis}" action="${baseRegUrl}/registrations/rejected/${row.idRegis}" method="POST">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="PUT">
                                        <button 
                                            type="button" 
                                            class="btn btn-action btn-danger btn-sm" 
                                            value="rejected" 
                                            data-bs-toggle="modal" 
                                            data-bs-target= "#confirmModal" 
                                            data-name="${row.name}"
                                            data-form="rejectForm-${row.idRegis}"
                                            data-action="reject"
                                            data-color="btn-danger"
                                            data-title="Konfirmasi Penolakan"
                                            data-message="Apakah anda yakin untuk menolak"
                                        >Tolak</button>
                                    </form>
                                </td>
                            `
                            : ''}
                        `;

                        tbody.appendChild(tr);

                    });

                });
            });
        });

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

    
            // message
            document.addEventListener('click', function(e){
                const btn = e.target.closest('.btn-action');
                if(!btn) return;

                const name = btn.dataset.name;
                const formId = btn.dataset.form;
                const title = btn.dataset.title;
                const message = btn.dataset.message;
                const color = btn.dataset.color;

                const activeForm = document.getElementById(formId);

                modalTitle.textContent = title;
                modalMessage.textContent = message;
                modalName.textContent = name;

                confirmBtn.className = 'btn ' + color;

                confirmBtn.onclick = () => activeForm.submit();

                modal.show();
            });

            confirmBtn.onclick = function(){
                if(activeForm) activeForm.submit();
            };

            modalEl.addEventListener('hidden.bs.modal', function(){
                activeForm = null;
                confirmBtn.onclick = null;
            });
        });
    </script>
@endsection
