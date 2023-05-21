<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <div class="user-sidebar text-center bg-success">
            <div class="dropdown">
                <div class="user-img">
                    <img src="{{ Auth::user()->foto != null ? Auth::user()->foto : asset('/assets/images/users/avatar-1.png')}}" alt="" class="rounded-circle">
                    <span class="avatar-online bg-warning"></span>
                </div>
                <div class="user-info">
                    <h5 class="mt-3 font-size-16 text-white">{{ Auth::user()->name }}</h5>
                    <span class="font-size-13 text-white-50">Administrator</span>
                </div>
            </div>
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>
                <li>
                    <a href="/" class="waves-effect nav_link">
                        <i class="fas fa-home"></i>
                        <span>Dashboard </span>
                    </a>
                </li>

                <li>
                    <a href="/daftar-kehadiran" class="waves-effect nav_link">
                        <i class="fas fas fa-calendar-day"></i>
                        <span>Daftar Presensi</span>
                    </a>
                </li>

                <li>
                    <a href="/pegawai" class="waves-effect">
                        <i class="fas fa-user-friends"></i>
                        <span>Karyawan</span>
                    </a>
                </li>

                <li>
                    <a href="/permission" class="waves-effect">
                        <i class="fas fa-users-cog"></i>
                        <span>Izin Karyawan</span>
                    </a>
                </li>

                <li class="menu-title">Master Data</li>
                <li>
                    <a href="/pengaturan-waktu-guru" class="waves-effect">
                        <i class="fas fa-stopwatch"></i>
                        <span>Waktu Guru</span>
                    </a>
                </li>
                <li>
                    <a href="/pengaturan-waktu-pegawai" class="waves-effect">
                        <i class="fas fa-stopwatch"></i>
                        <span>Waktu Pegawai</span>
                    </a>
                </li>
                <li>
                    <a href="/tahun-ajaran" class="waves-effect">
                        <i class="fas fa-calendar"></i>
                        <span>Tahun Ajaran</span>
                    </a>
                </li>
                <li>
                    <a href="/kalender-libur" class="waves-effect">
                        <i class="fas fa-calendar-week"></i>
                        <span>Kalender Libur</span>
                    </a>
                </li>
                <li>
                    <a href="/lokasi" class="waves-effect">
                        <i class="fas fa-map"></i>
                        <span>Lokasi</span>
                    </a>
                </li>

                <li class="menu-title">Laporan</li>
                <li>
                    <a href="/laporan-absensi" class="waves-effect">
                        <i class="fas fa-file-signature"></i>
                        <span>Laporan Presensi</span>
                    </a>
                </li>

                <li class="menu-title">User</li>
                <li>
                    <a href="/user" class="waves-effect">
                        <i class="fas fa-user"></i>
                        <span>Akun User</span>
                    </a>
                </li>
                <li>
                    <a href="/user/verifikasi-pegawai" class="waves-effect">
                        <i class="fas fa-user-check"></i>
                        <span>Verifikasi Karyawan</span>
                    </a>
                </li>
                <li class="menu-title">Feedback</li>
                <li>
                    <a href="/feedback" class="waves-effect">
                        <i class="fas fa-user"></i>
                        <span>Feedback</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
