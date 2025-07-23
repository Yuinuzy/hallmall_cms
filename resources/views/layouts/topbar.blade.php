<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="index" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ URL::asset('build/images/logo-halmall.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ URL::asset('build/images/logo-halmall.png') }}" alt="" height="17">
                    </span>
                </a>

                <a href="index" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ URL::asset('build/images/logo-halmall.png') }}" alt="" height="50">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ URL::asset('build/images/logo-halmall.png') }}" alt="" height="50">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>
    </div>

    <div class="d-flex">

        <div class="dropdown d-inline-block">
            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="rounded-circle header-profile-user" src="{{ isset(Auth::user()->avatar) ? asset(Auth::user()->avatar) : asset('build/images/users/avatar-1.jpg') }}"
                    alt="Header Avatar">
                <span class="d-none d-xl-inline-block ms-1" key="t-henry">{{ucfirst(Auth::user()->name)}}</span>
                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <!-- item-->
                <!-- <a class="dropdown-item" href="contacts-profile"><i class="bx bx-user font-size-16 align-middle me-1"></i> <span key="t-profile">@lang('translation.Profile')</span></a>
                <a class="dropdown-item" href="#"><i class="bx bx-wallet font-size-16 align-middle me-1"></i> <span key="t-my-wallet">@lang('translation.My_Wallet')</span></a>
                <a class="dropdown-item d-block" href="#" data-bs-toggle="modal" data-bs-target=".change-password"><span class="badge bg-success float-end">11</span><i class="bx bx-wrench font-size-16 align-middle me-1"></i> <span key="t-settings">@lang('translation.Settings')</span></a>
                <a class="dropdown-item" href="#"><i class="bx bx-lock-open font-size-16 align-middle me-1"></i> <span key="t-lock-screen">@lang('translation.Lock_screen')</span></a>
                <div class="dropdown-divider"></div> -->
                <a class="dropdown-item text-danger" href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span key="t-logout">@lang('translation.Logout')</span></a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
       
    </div>
</div>
</header>