 <header id="page-topbar">
     <div class="navbar-header">
         <div class="d-flex">
             <!-- LOGO -->
             <div class="navbar-brand-box">
                 <a href="#" class="logo logo-dark">
                     <span class="logo-lg">
                         <img src="{{ asset('/assets/images/logo-web-ypsim.jpg') }}" 
                            alt="" 
                            height="42">
                     </span>
                 </a>
             </div>
             <button type="button" 
                    class="btn btn-sm px-3 font-size-24 header-item waves-effect" 
                    id="vertical-menu-btn">
                <i class="mdi mdi-menu"></i>
             </button>
         </div>

        <div class="d-flex">
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="{{ Auth::user()->foto != null ? Auth::user()->foto : asset('/assets/images/users/avatar-1.png')}}" alt="User">
                    <span class="d-none d-xl-inline-block ms-1">{{ Auth::user()->name }}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="mdi mdi-power font-size-16 align-middle me-1 text-danger"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
 </header>