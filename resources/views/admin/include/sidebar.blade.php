<style>
    /* ── Active & hover states ── */
    .metismenu li.mm-active > a,
    .metismenu li a.active {
        background-color: #bac1cb !important;
        color: #fff !important;
        border-radius: 6px;
    }

    .metismenu li.mm-active > a i,
    .metismenu li a.active i {
        color: #fff !important;
    }

    .metismenu li a:hover {
        background-color: #c3ccda;
        border-radius: 6px;
    }

    /* ── Sidebar shell ── */
    .dlabnav {
        width: 260px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 101;
        background: #fff;
        box-shadow: 2px 0 8px rgba(0, 0, 0, 0.07);
        display: flex;
        flex-direction: column;
        transition: all 0.2s ease;
    }

    /* ── Scrollable nav area ──
       starts right below the HEADER/navbar (which is ~60–70px tall).
       Adjust the top offset to match your actual header height.        */
    .dlabnav-scroll {
        flex: 1 1 auto;
        overflow-y: auto;
        overflow-x: hidden;
        padding: 12px 10px 20px;
        /* Push content down so it never slides under the top navbar */
        margin-top: 70px;   /* ← set this = your header bar height */
    }

    /* Clean thin scrollbar */
    .dlabnav-scroll::-webkit-scrollbar       { width: 4px; }
    .dlabnav-scroll::-webkit-scrollbar-track { background: transparent; }
    .dlabnav-scroll::-webkit-scrollbar-thumb { background: #bac1cb; border-radius: 10px; }

    /* ── Menu items ── */
    .metismenu {
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .metismenu li {
        margin-bottom: 2px;
    }

    /* .metismenu li > a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 14px;
        color: #374151;
        text-decoration: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        transition: background 0.15s ease;
        white-space: nowrap;
    } */

    /* Fixed-width icon so text always lines up */
    /* .metismenu li > a i {
        width: 18px;
        text-align: center;
        font-size: 15px;
        flex-shrink: 0;
        color: #6b7280;
    }

    .metismenu li.mm-active > a i,
    .metismenu li a.active i {
        color: #fff !important;
    } */

    /* Chevron arrow for collapsible items */
    /* .metismenu li > a.has-arrow::after {
        content: '';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        margin-left: auto;
        transition: transform 0.2s ease;
        font-size: 12px;
        color: #9ca3af;
    } */

    .metismenu li.mm-active > a.has-arrow::after {
        transform: rotate(-180deg);
    }

    /* ── Sub-menu ── */
    /* .metismenu .collapse,
    .metismenu .collapsing {
        padding-left: 44px;
    } */

    /* .metismenu .collapse li,
    .metismenu .collapsing li {
        margin-bottom: 1px;
    }

    .metismenu .collapse li a,
    .metismenu .collapsing li a {
        padding: 7px 12px;
        font-size: 13px;
        color: #6b7280;
        border-radius: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: background 0.15s ease;
    } */

    /* Dash bullet for sub-items */
    /* .metismenu .collapse li a::before,
    .metismenu .collapsing li a::before {
        content: '';
        font-size: 12px;
        color: #bac1cb;
       
    }

    .metismenu .collapse li a:hover,
    .metismenu .collapsing li a:hover {
        background: #f3f4f6;
        color: #111827;
    }

    .metismenu .collapse li a.active,
    .metismenu .collapsing li a.active {
        background-color: #bac1cb !important;
        color: #fff !important;
    }

    .metismenu .collapse li a.active::before,
    .metismenu .collapsing li a.active::before {
        color: #fff;
    } */
</style>

<div class="dlabnav">
    <div class="dlabnav-scroll">
        <ul class="metismenu" id="menu">

            <li class="{{ request()->routeIs('admin.dashboard') ? 'mm-active' : '' }}">
                <a href="{{ route('admin.dashboard') }}"
                   class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('admin.users') ? 'mm-active' : '' }}">
                <a href="{{ route('admin.users') }}"
                   class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span class="nav-text">Users</span>
                </a>
            </li>

            {{-- Product --}}
            <li class="{{ request()->routeIs('admin.product*') ? 'mm-active' : '' }}">
                <a class="has-arrow" href="#productMenu" data-bs-toggle="collapse"
                   aria-expanded="{{ request()->routeIs('admin.product*') ? 'true' : 'false' }}">
                    <i class="fab fa-product-hunt"></i>
                    <span class="nav-text">Product</span>
                </a>
                <ul class="collapse {{ request()->routeIs('admin.product*') ? 'show' : '' }}" id="productMenu">
                    <li><a href="{{ route('admin.product') }}"
                           class="{{ request()->routeIs('admin.product') ? 'active' : '' }}">All Product</a></li>
                    <li><a href="{{ route('admin.product.add') }}"
                           class="{{ request()->routeIs('admin.product.add') ? 'active' : '' }}">Add New Product</a></li>
                    <li><a href="{{ route('admin.product.category') }}"
                           class="{{ request()->routeIs('admin.product.category') ? 'active' : '' }}">Category</a></li>
                    <li><a href="{{ route('admin.product.subcategory') }}"
                           class="{{ request()->routeIs('admin.product.subcategory') ? 'active' : '' }}">SubCategory</a></li>
                </ul>
            </li>

            {{-- Attributes --}}
            <li class="{{ request()->routeIs('admin.color') || request()->routeIs('admin.size') ? 'mm-active' : '' }}">
                <a class="has-arrow" href="#attributesMenu" data-bs-toggle="collapse"
                   aria-expanded="{{ request()->routeIs('admin.color') || request()->routeIs('admin.size') ? 'true' : 'false' }}">
                    <i class="fas fa-tags"></i>
                    <span class="nav-text">Attributes</span>
                </a>
                <ul class="collapse {{ request()->routeIs('admin.color') || request()->routeIs('admin.size') ? 'show' : '' }}" id="attributesMenu">
                    <li><a href="{{ route('admin.color') }}"
                           class="{{ request()->routeIs('admin.color') ? 'active' : '' }}">Colors</a></li>
                    <li><a href="{{ route('admin.size') }}"
                           class="{{ request()->routeIs('admin.size') ? 'active' : '' }}">Sizes</a></li>
                </ul>
            </li>

            <li class="{{ request()->routeIs('admin.order.*') ? 'mm-active' : '' }}">
                <a href="{{ route('admin.order.list') }}"
                   class="{{ request()->routeIs('admin.order.*') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice"></i>
                    <span class="nav-text">Orders</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('admin.coupon_code*') ? 'mm-active' : '' }}">
                <a href="{{ route('admin.coupon_code') }}"
                   class="{{ request()->routeIs('admin.coupon_code*') ? 'active' : '' }}">
                    <i class="fas fa-ticket-alt"></i>
                    <span class="nav-text">Coupon Code</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('review*') ? 'mm-active' : '' }}">
                <a href="{{ route('review') }}"
                   class="{{ request()->routeIs('review*') ? 'active' : '' }}">
                    <i class="fas fa-star"></i>
                    <span class="nav-text">Reviews</span>
                </a>
            </li>

            {{-- <li class="{{ request()->routeIs('admin.complaints.*') ? 'mm-active' : '' }}">
                <a href="{{ route('admin.complaints.index') }}"
                   class="{{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}">
                    <i class="fas fa-pen"></i>
                    <span class="nav-text">Complaints</span>
                </a>
            </li> --}}

            {{-- Website Setup --}}
            <li class="{{ request()->routeIs('admin.banner.*') ? 'mm-active' : '' }}">
                <a class="has-arrow" href="#setup" data-bs-toggle="collapse"
                   aria-expanded="{{ request()->routeIs('admin.banner.*') ? 'true' : 'false' }}">
                    <i class="fas fa-globe"></i>
                    <span class="nav-text">Website Setup</span>
                </a>
                <ul class="collapse {{ request()->routeIs('admin.banner.*') ? 'show' : '' }}" id="setup">
                    <li><a href="{{ route('admin.banner.show') }}"
                           class="{{ request()->routeIs('admin.banner.show') ? 'active' : '' }}">Banner Image</a></li>
                </ul>
            </li>

            <li class="{{ request()->routeIs('admin.profile') ? 'mm-active' : '' }}">
                <a href="{{ route('admin.profile') }}"
                   class="{{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                    <i class="fas fa-user-circle"></i>
                    <span class="nav-text">Admin Profile</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('password.change') ? 'mm-active' : '' }}">
                <a href="{{ route('password.change') }}"
                   class="{{ request()->routeIs('password.change') ? 'active' : '' }}">
                    <i class="fas fa-key"></i>
                    <span class="nav-text">Change Password</span>
                </a>
            </li>

        </ul>
    </div>
</div>

<script>
    $(function () {
        $("#menu").metisMenu();
    });
</script>