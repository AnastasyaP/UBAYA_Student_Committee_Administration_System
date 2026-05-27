@if(Auth::user()->role == 'admin')

    @if(isSuperAdmin())
        @include('layouts.navbars.auth.sidenav.sidenav-super')
    
    @else
        @include('layouts.navbars.auth.sidenav.sidenav-admin')
    
    @endif

@else
    @include('layouts.navbars.auth.sidenav.sidenav-member')

@endif