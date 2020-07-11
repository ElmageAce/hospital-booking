<!-- Left navbar-header -->
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse slimscrollsidebar">
        <ul class="nav" id="side-menu">
            <li class="nav-small-cap m-t-10">--- Main Menu</li>
            <li>
                <a href="{{ route('dashboard') }}" class="waves-effect">
                    <i class="ti-dashboard p-r-10"></i> <span class="hide-menu">Dashboard</span>
                </a>
            </li>

            <li>
                <a href="{{ route('profile.user', auth()->id()) }}" class="waves-effect">
                    <i class="ti-user p-r-10"></i> <span class="hide-menu">Profile</span>
                </a>
            </li>

            <li class="nav-small-cap m-t-10">--- Professional</li>
            <li>
                <a href="javascript:void(0);" class="waves-effect">
                    <i class="ti-calendar p-r-10"></i>
                    <span class="hide-menu"> Appointments <span class="fa arrow"></span></span>
                </a>
                <ul class="nav nav-second-level">

                    @can('doctor')
                        <li> <a href="{{ route('schedules.index') }}">My Schedule</a> </li>
                    @endcan

                    @can('patient')
                        <li> <a href="{{ route('appointments.create') }}">Book Appointment</a> </li>
                        <li> <a href="{{ route('appointments.index') }}">My Appointments</a> </li>
                    @endcan

                </ul>
            </li>

            <li> <a href="widgets.html" class="waves-effect">
                    <i data-icon="P" class="linea-icon linea-basic fa-fw"></i>
                    <span class="hide-menu">Settings</span></a>
            </li>

        </ul>
    </div>
</div>
<!-- Left navbar-header end -->
