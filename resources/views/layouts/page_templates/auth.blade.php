<div class="wrapper ">
    @include('layouts.navbars.sidebar')
    <div class="main-panel">
        <div class="container-fluid" style="background-color: #0076c1; weight: 100%;">
            <img src="{{ asset('img/Cintillo.png') }}" alt="" style="max-height: 91px;">
        </div>
        @include('layouts.navbars.navs.auth')
        @yield('content')
        @include('layouts.footers.auth')
    </div>
</div>
