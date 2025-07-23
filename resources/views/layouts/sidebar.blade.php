<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">

                <li class="menu-title" key="t-menu">@lang('translation.Menu')</li>
                <li>
                    <a href="{{ route('dashboard') }}" class="waves-effect">
                        <i class="bx bx-list-ul"></i>
                        <span >@lang('translation.Dashboards')</span>
                    </a>
                </li>

                 <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-store-alt"></i>
                        <span>@lang('translation.seller')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li><a href="javascript: void(0);">@lang('translation.seller_req')</a></li>
                        <li><a href="javascript: void(0);">@lang('translation.seller_list')</a></li>
                    </ul>
                </li>

                <li>
                     <a href="{{ route('users.index') }}" class="waves-effect">
                        <i class='bx bx-user-circle'  ></i>
                        <span >@lang('translation.userIndex')</span>
                    </a>
                </li>

                <li>
                     <a href="{{ url('/permission') }}" class="waves-effect">
                        <i class='bx bx-user-circle'  ></i>
                        <span >@lang('translation.Permission')</span>
                    </a>
                </li>


                 <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-cog"></i>
                        <span>@lang('translation.settings')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        @can('users.index')
                            <li><a href="javascript: void(0);">@lang('translation.user')</a></li>
                        @endcan
                        <li><a href="{{ route('role.index') }}">@lang('translation.role')</a></li>
                        <li><a href="javascript: void(0);">@lang('translation.permission')</a></li>
                    </ul>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
