<div class="sidebar" data-color="orange" data-background-color="white"
    data-image="{{ asset('material') }}/img/sidebar-1.jpg">
    <!--
      Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

      Tip 2: you can also add an image using data-image tag
  -->
    <div class="logo">
        <img src="{{ asset('img/VCT-Logo.png')}}" alt="" style="max-width: 55%;">
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li class="nav-item{{ $activePage == 'dashboard' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('home') }}">
                    <i class="material-icons">dashboard</i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>
            <li class="nav-item{{ $activePage == 'observa' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('observa') }}">
                    <i class="material-icons">search</i>
                    <p>{{ __('Observa') }}</p>
                </a>
            </li>
        </ul>
    </div>
</div>
