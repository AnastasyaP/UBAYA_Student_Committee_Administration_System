@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Add Schedule Interview'])

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8">
                @if(session('warning'))
                    <div>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Warning!</strong> {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif
                <div class="card">
                    <form method="POST" action="{{ route('intv.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Division</p>
                                <button class="btn btn-primary btn-sm ms-auto" type="submit">Save</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Select Existing Division</label>
                                        <select class="form-control" id="division" name="division">
                                            <option value="">-- Choose Existing Division --</option>
                                            @foreach ($divisions as $division)
                                                <option value="{{ $division->idDivisions }}">{{ $division->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                          <label class="form-control-label">Interview Date</label>
                                            <input class="form-control" type="date" id="date" name="date">
                                           @error('date')
                                           <div class="text-danger small">{{ $message }}</div>
                                           @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                          <label class="form-control-label">Start Time</label>
                                            <input class="form-control" type="time" id="start_time" name="start_time">
                                           @error('start_time')
                                           <div class="text-danger small">{{ $message }}</div>
                                           @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                          <label class="form-control-label">End Time</label>
                                            <input class="form-control" type="time" id="end_time" name="end_time">
                                           @error('end_time')
                                           <div class="text-danger small">{{ $message }}</div>
                                           @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                          <label class="form-control-label">Place</label>
                                            <input class="form-control" type="text" id="place" name="place">
                                           @error('place')
                                           <div class="text-danger small">{{ $message }}</div>
                                           @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                          <label class="form-control-label">Link</label>
                                            <input class="form-control" type="text" id="link" name="link">
                                           @error('link')
                                           <div class="text-danger small">{{ $message }}</div>
                                           @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
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
