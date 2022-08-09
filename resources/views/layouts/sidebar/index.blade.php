<div class="left side-menu">
    <button type="button" class="button-menu-mobile button-menu-mobile-topbar open-left waves-effect">
        <i class="ion-close"></i>
    </button>
    <!-- LOGO -->
    <div class="topbar-left">
        <div class="text-center"><a href="/" class="logo"><i class="mdi mdi-server"></i> Nuture Pay</a>
            <!-- <a href="index.html" class="logo"><img src="assets/images/logo.png" height="24" alt="logo"></a> -->
        </div>
    </div>

    {{--sidebar for mfb--}}
    <div class="sidebar-inner slimscrollleft">
        <div id="sidebar-menu">
            <ul>
                <li class="menu-title">Main</li>
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="waves-effect">
                        <i class="mdi mdi-airplay"></i>
                        <span>
                            Dashboard
{{--                            <span class="badge badge-pill badge-primary float-right">--}}
                            {{--                                7--}}
                            {{--                            </span>--}}
                        </span>
                    </a>
                </li>
                @canany(['view all customer'])
                @include('layouts.sidebar.customer')
                @endcan

                @include('layouts.sidebar.loan')

                @canany(['view all admins','view admin'])
                    <li class="menu-title">Settings</li>
                    @can('create role')
                        <li>
                            <a href="{{ route('admin.role.show') }}" class="waves-effect">
                                <i class="mdi mdi-settings"></i>
                                <span>
                        New Role
                        </span>
                            </a>
                        </li>
                    @endcan
                    @can('view roles')
                        <li>
                            <a href="{{ route('admin.role.index') }}" class="waves-effect">
                                <i class="mdi mdi-settings"></i>
                                <span>
                        Role Management
                        </span>
                            </a>
                        </li>
                    @endcan
                    @can('view all admins')
                        <li>
                            <a href="{{ route('admin.user.index') }}" class="waves-effect">
                                <i class="mdi mdi-account-settings"></i>
                                <span>
                                    Admin Management
                                </span>
                            </a>
                        </li>
                    <li>
                        <a href="{{ route('admin.registration.settings') }}" class="waves-effect">
                            <i class="mdi mdi-account-settings"></i>
                            <span>
                                    Registration Settings
                                </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.advert') }}" class="waves-effect">
                            <i class="mdi mdi-account-settings"></i>
                            <span>
                                    Advert
                            </span>
                        </a>
                    </li>
                        <li>
                            <a href="{{ route('admin.loan.settings') }}" class="waves-effect">
                                <i class="mdi mdi-account-settings"></i>
                                <span>
                                    Loan Settings
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.fixed-deposit.settings') }}" class="waves-effect">
                                <i class="mdi mdi-account-settings"></i>
                                <span>
                                    Fixed Deposit Setting(Investment)
                                </span>
                            </a>
                        </li>
                    <li>
                        <a href="{{ route('admin.otp') }}" class="waves-effect">
                            <i class="mdi mdi-account-settings"></i>
                            <span>
                                    OTP
                                </span>
                        </a>
                    </li>
                        <li>
                            <a href="{{ route('admin.log.activities') }}" class="waves-effect">
                                <i class="mdi mdi-account-settings"></i>
                                <span>
                                    Log Activities
                                </span>
                            </a>
                        </li>
                    @endcan
                @endcanany
                @can('view bills log')
                    <li>
                        <a href="{{ route('admin.bills') }}" class="waves-effect">
                            <i class="mdi mdi-account-settings"></i>
                            <span>
                                Bills Log
                            </span>
                        </a>
                    </li>
                @endcan
                {{--                <li class="has_sub"><a href="javascript:void(0);" class="waves-effect"><i--}}
                {{--                            class="mdi mdi-bullseye"></i> <span>UI Elements </span><span class="float-right"><i--}}
                {{--                                class="mdi mdi-chevron-right"></i></span></a>--}}
                {{--                    <ul class="list-unstyled">--}}
                {{--                        <li><a href="ui-buttons.html">Buttons</a></li>--}}
                {{--                        <li><a href="ui-sweet-alert.html">Sweet-Alert</a></li>--}}
                {{--                        <li><a href="ui-grid.html">Grid</a></li>--}}
                {{--                    </ul>--}}
                {{--                </li>--}}


            </ul>
        </div>
        <div class="clearfix"></div>
    </div>

    <!-- end sidebarinner -->
</div>


