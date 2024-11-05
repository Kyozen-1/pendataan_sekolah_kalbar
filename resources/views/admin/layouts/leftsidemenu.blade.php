<div class="left-side-menu">

    <div class="slimscroll-menu">

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <ul class="metismenu" id="side-menu">
                <li>
                    <a href="{{route('admin.dashboard.index')}}">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span> Dashboard </span>
                    </a>
                </li>
                <li>
                    <a  href="javascript: void(0);">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span> Master Data </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="{{ route('admin.master-jenjang-sekolah.index') }}">Jenjang Sekolah</a></li>
                        <li><a href="{{ route('admin.master-kecepatan-internet.index') }}">Kecepatan Internet</a></li>
                        <li><a href="{{ route('admin.master-kurikulum.index') }}">Kecepatan Kurikulum</a></li>
                    </ul>
                </li>
            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
