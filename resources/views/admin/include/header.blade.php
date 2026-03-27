<!--**********************************
    Nav header start
***********************************-->
<div class="nav-header">
    <div class="text-center">
        <a href="{{ route('admin.dashboard') }}" class="brand-logo">
            <img src="{{ asset('assets/images/canntum.png') }}" alt="Canntum Emporium" class="nav-logo">
        </a>
    </div>
</div>

<style>
    .cke_notification_warning {
        display: none;
    }

    /* ── Nav header / logo bar ── */
    .nav-header {
        width: 260px;           /* must match your .dlabnav width */
        height: 70px;           /* must match margin-top in .dlabnav-scroll */
        position: fixed;
        top: 0;
        left: 0;
        z-index: 102;           /* sit above .dlabnav (z-index:101) */
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid #f0f0f0;
        box-shadow: 2px 0 8px rgba(0,0,0,.07);
    }

    .nav-header .brand-logo {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Control the logo size here */
    .nav-header .nav-logo {
        max-height: 50px;
        width: auto;
        object-fit: contain;
    }
</style>
<!--**********************************
    Nav header end
***********************************-->


<!--**********************************
    Header start
***********************************-->
<div class="header border-bottom">
    <div class="header-content">
        <nav class="navbar navbar-expand">
            <div class="collapse navbar-collapse justify-content-between">
                <div class="header-left">
                    <div class="dashboard_bar"></div>
                </div>
                <ul class="navbar-nav header-right">

                    {{-- Profile dropdown --}}
                    <li class="nav-item dropdown header-profile">
                        <a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
                            <img src="{{ asset('assets/images/canntum.png') }}" width="40" alt="Profile">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="{{ route('admin.profile') }}" class="dropdown-item ai-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <span class="ms-2">Profile</span>
                            </a>
                            <a href="{{ route('admin.logout') }}" class="dropdown-item ai-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                                <span class="ms-2">Logout</span>
                            </a>
                        </div>
                    </li>

                </ul>
            </div>
        </nav>
    </div>
</div>
<!--**********************************
    Header end
***********************************-->