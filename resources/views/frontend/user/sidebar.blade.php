<!-- ================= WEB VIEW (UNCHANGED) ================= -->
<div class="col-lg-2 col-md-12">
    <div class="profile_left web-view d-none d-lg-block">
        <ul class="profile_list">
            <li>
                <a href="{{ route('user.dashboard') }}"
                   class="profile_link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    My Profile
                </a>
            </li>
            <li>
                <a href="{{ route('user.order.history') }}"
                   class="profile_link {{ request()->routeIs('user.order.history') ? 'active' : '' }}">
                    My Orders
                </a>
            </li>
            <li>
                <a href="{{ route('user.address') }}"
                   class="profile_link {{ request()->routeIs('user.address') ? 'active' : '' }}">
                    My Address
                </a>
            </li>
            <li>
                <a href="{{ route('forgot.password') }}"
                   class="profile_link {{ request()->routeIs('forgot.password') ? 'active' : '' }}">
                    Change Password
                </a>
            </li>
            <li>
                <a href="{{ route('user.logout') }}"
                   class="profile_link {{ request()->routeIs('user.logout') ? 'active' : '' }}">
                    Logout
                </a>
            </li>
        </ul>
    </div>
</div>


<!-- ================= MOBILE VIEW BUTTON ================= -->
<div class="d-block d-lg-none mb-3">
    <button class="mobile-menu-btn" data-bs-toggle="offcanvas" data-bs-target="#mobileProfileMenu">
        <i class="bi bi-list"></i> Menu
    </button>
</div>


<!-- ================= MOBILE OFFCANVAS ================= -->
<div class="offcanvas offcanvas-start mobile-profile-canvas" tabindex="-1" id="mobileProfileMenu">

    <div class="offcanvas-header profile-header">
        <div class="profile-info">
            <div class="avatar">
                <i class="bi bi-person"></i>
            </div>
            <h5 class="username">My Account</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body">

        <ul class="list-unstyled mobile-profile-list">

            <li>
                <a href="{{ route('user.dashboard') }}"
                   class="mp-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-person-circle"></i>
                    <span>My Profile</span>
                </a>
            </li>

            <li>
                <a href="{{ route('user.order.history') }}"
                   class="mp-link {{ request()->routeIs('user.order.history') ? 'active' : '' }}">
                    <i class="bi bi-bag"></i>
                    <span>My Orders</span>
                </a>
            </li>

            <li>
                <a href="{{ route('user.address') }}"
                   class="mp-link {{ request()->routeIs('user.address') ? 'active' : '' }}">
                    <i class="bi bi-geo-alt"></i>
                    <span>My Address</span>
                </a>
            </li>

            <li>
                <a href="{{ route('forgot.password') }}"
                   class="mp-link {{ request()->routeIs('forgot.password') ? 'active' : '' }}">
                    <i class="bi bi-shield-lock"></i>
                    <span>Change Password</span>
                </a>
            </li>

            <li>
                <a href="{{ route('user.logout') }}" class="mp-link logout">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
            </li>

        </ul>

    </div>

</div>



<!-- ================= CSS ================= -->
<style>

@media(max-width: 991px){

    /* Mobile Button */
    .mobile-menu-btn{
        background: #001E40;
        color: #fff;
        border: none;
        padding: 12px 22px;
        font-weight: 600;
        font-size: 16px;
        border-radius: 8px;
        width: max-content;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        z-index: 1500 !important;
    }

    /* Offcanvas */
    .mobile-profile-canvas{
        width: 280px !important;
        background: #f6f8f7;
        border-right: 1px solid #e0e0e0;
    }

    /* Header */
    .profile-header{
        background: #ffffff;
        border-bottom: 1px solid #e5e5e5;
        padding: 18px;
    }

    .profile-info{
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .avatar{
        width: 48px;
        height: 48px;
        background: #001E40;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .username{
        font-size: 17px;
        font-weight: 700;
        color: #222;
    }

    /* Menu List */
    .mobile-profile-list li{
        margin-bottom: 8px;
    }

    .mp-link{
        display: flex;
        align-items: center;
        gap: 12px;
        background: #fff;
        padding: 13px 15px;
        border-radius: 10px;
        border: 1px solid #e6e6e6;
        font-size: 15px;
        font-weight: 600;
        color: #222;
        transition: 0.25s;
    }

    .mp-link i{
        font-size: 20px;
        color: #001E40;
    }

    .mp-link:hover{
        background: #9fb9d6;
        border-color:  #6c8097;
        transform: translateX(4px);
    }

    .mp-link.active{
        background: #001E40;
        color: #fff !important;
        border-color: #001E40;
    }

    .mp-link.active i{
        color: #fff !important;
    }

    /* Logout Item */
    .mp-link.logout{
        background: #ffeaea;
        border-color: #ffcccc;
        color: #cc0000;
    }

    .mp-link.logout i{
        color: #cc0000;
    }
}
</style>


