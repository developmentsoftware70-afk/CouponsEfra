<!--========================== Header section Start ==========================-->
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');

/* Main Header Container - 1:1 Replica */
.header {
    background: #ffffff !important;
    position: sticky !important; 
    top: 0 !important;
    width: 100% !important;
    z-index: 9999999 !important; /* Extremely high z-index */
    font-family: 'Inter', sans-serif !important;
    border-bottom: 1px solid #eaeaec !important; /* Faint gray bottom line */
    box-shadow: none !important; /* No shadow, just clean border */
    transform: none !important; /* Stop JS from moving it */
    animation: none !important; /* Stop JS animations */
    transition: none !important; /* Stop JS delays */
    overflow: visible !important; /* Ensure children can spill out */
}

.header .container-fluid, .header .row {
    overflow: visible !important;
}

.header-wrapper {
    padding: 10px 20px !important;
    display: flex !important;
    flex-wrap: wrap !important;
    align-items: center !important;
    max-width: 1400px !important;
    margin: 0 auto !important;
}

/* Hamburger Menu (Left) */
.sidebar-menu-show-hide {
    order: 0 !important;
    background: transparent !important;
    color: #111111 !important;
    font-size: 24px !important;
    margin-right: 15px !important;
    padding: 0 !important;
    border: none !important;
    cursor: pointer !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: auto !important;
    height: auto !important;
}

/* Logo (Left) */
.header-menu-wrapper {
    order: 1 !important;
    padding: 0 !important;
}
.logo-wrapper {
    min-width: 200px !important;
    display: flex !important;
    align-items: center !important;
}
.logo-wrapper img {
    height: 55px !important; /* Increased logo size */
    width: 200px !important; /* Fixed width to prevent FOUC */
    object-fit: contain !important;
    object-position: left center !important;
}

/* Search Bar & Right Section Container */
.menu-right-wrapper {
    order: 3 !important;
    flex-grow: 1 !important;
    display: flex !important;
    justify-content: flex-end !important;
}
.menu-right-wrapper ul {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    width: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
}

/* Search Bar Center (Soft Gray Rounded Pill) */
.hero-search-bar {
    flex-grow: 1 !important;
    max-width: 650px !important;
    margin-left: 40px !important;
    margin-right: auto !important;
}
.hero-search-bar form {
    position: relative !important; /* Bulletproof positioning */
    display: flex !important;
    align-items: center !important;
    background: #f4f5f8 !important; 
    border-radius: 8px !important; 
    border: 1px solid transparent !important;
    height: 44px !important; /* Reverted search bar height */
    padding: 0 !important; 
    transition: all 0.2s ease !important;
    overflow: hidden !important;
}
.hero-search-bar form:focus-within {
    border-color: #d1d5db !important;
    background: #ffffff !important;
}
.hero-search-bar button.search-btn {
    position: absolute !important;
    left: 0 !important; /* Move search icon to the LEFT */
    top: 0 !important;
    height: 100% !important;
    width: 45px !important; /* Reverted width */
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    background: transparent !important;
    border: none !important;
    color: #5a6675 !important;
    font-size: 16px !important; /* Reverted icon size */
    cursor: pointer !important;
    padding: 0 !important;
    margin: 0 !important;
    z-index: 2 !important;
}
.hero-search-bar button.search-btn i {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}
.hero-search-bar button.search-btn span {
    display: none !important; /* Hide extra text */
}
.hero-search-bar input.form--control {
    background: transparent !important;
    border: none !important;
    font-size: 14px !important; /* Reverted search text size */
    color: #111111 !important;
    width: 100% !important;
    height: 100% !important;
    outline: none !important;
    box-shadow: none !important;
    padding: 0 15px 0 45px !important; /* Space on left for the absolute button */
    font-family: 'Inter', sans-serif !important;
    position: relative !important;
    z-index: 1 !important;
}
.hero-search-bar input.form--control::placeholder {
    color: #7b8593 !important;
}

/* Right Side - Login / Signup (Plain Text) */
.login-registration-list__item {
    margin-left: 25px !important;
    position: relative !important;
}
/* Divider Line */
.login-registration-list__item::before {
    content: '' !important;
    position: absolute !important;
    left: -25px !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    height: 20px !important;
    width: 1px !important;
    background: #eaeaec !important; /* Faint gray divider */
}
.login-registration-list__item a {
    background: transparent !important; 
    color: #000000 !important;
    font-weight: 600 !important; /* Bold like image */
    font-size: 14px !important; /* Reverted login font size */
    text-decoration: none !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 8px !important;
    padding: 5px !important;
    border-radius: 0 !important;
    border: none !important;
}
.login-registration-list__item a i {
    font-size: 18px !important; /* Reverted icon size */
    color: #000000 !important;
}
.login-registration-list__item a:hover {
    color: #f37254 !important; /* Orange hover effect */
}
.login-registration-list__item a:hover i {
    color: #f37254 !important;
}

/* Logout text */
.login-registration-list__item a.login-registration-list__link {
    background: transparent !important;
    color: #000000 !important;
}
.login-registration-list__item a.login-registration-list__link:hover {
    color: #f37254 !important;
}

/* Logged in User Text */
.home a {
    color: #000000 !important;
    font-weight: 600 !important;
    font-size: 14px !important; /* Reverted font size */
    padding-right: 15px !important;
}

/* Nav Links */
.nav-links-container {
    padding: 0 20px !important;
}
.nav-links-container .main-menu {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 25px !important;
    margin: 0 !important;
    padding: 0 !important;
}
.nav-links-container .main-menu li {
    list-style: none !important;
}
.nav-links-container .main-menu li a {
    color: #333333 !important;
    font-weight: 500 !important; /* Reverted weight */
    font-size: 14px !important; /* Reverted menu font size */
    padding: 12px 0 !important;
    display: block !important;
    border-bottom: 2px solid transparent !important;
    border-radius: 0 !important;
    transition: all 0.2s !important;
}
.nav-links-container .main-menu li a:hover,
.nav-links-container .main-menu li a.active {
    color: #f37254 !important;
    border-bottom-color: #f37254 !important;
    background: transparent !important;
}

/* Profile Dropdown (Google Style) */
.profile-dropdown {
    position: relative !important;
    margin-left: 20px !important;
    display: flex !important;
    align-items: center !important;
    height: 32px !important; /* Restrict height to exactly the avatar height */
    width: 32px !important; /* Restrict width */
}
/* Invisible bridge to keep hover active while moving mouse down */
.profile-dropdown::after {
    content: '' !important;
    position: absolute !important;
    bottom: -15px !important;
    left: -20px !important;
    width: 80px !important;
    height: 20px !important;
}
.profile-avatar {
    width: 32px !important;
    height: 32px !important;
    border-radius: 50% !important;
    background: #5c3b99 !important; /* Elegant Purple */
    color: white !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 14px !important;
    font-weight: 600 !important;
    cursor: pointer !important;
    user-select: none !important;
}
.profile-dropdown-menu {
    position: absolute !important;
    top: 100% !important;
    margin-top: 10px !important; /* Replaces the top:120% gap */
    right: 0 !important;
    width: 280px !important;
    background: #fff !important;
    border-radius: 12px !important;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
    border: 1px solid #eaeaec !important;
    opacity: 0 !important;
    visibility: hidden !important;
    pointer-events: none !important; /* PREVENT HOVER WHEN CLOSED */
    transform: translateY(10px) !important;
    transition: all 0.3s ease !important;
    z-index: 9999 !important;
    cursor: default !important;
}
.profile-dropdown:hover .profile-dropdown-menu {
    opacity: 1 !important;
    visibility: visible !important;
    pointer-events: auto !important; /* ENABLE HOVER WHEN OPEN */
    transform: translateY(0) !important;
}
.profile-dropdown-header {
    padding: 20px !important;
    text-align: center !important;
    border-bottom: 1px solid #eaeaec !important;
    background: #f8f9fa !important;
    border-radius: 12px 12px 0 0 !important;
}
.profile-dropdown-header .big-avatar {
    width: 60px !important;
    height: 60px !important;
    border-radius: 50% !important;
    background: #5c3b99 !important;
    color: white !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 28px !important;
    font-weight: 600 !important;
    margin: 0 auto 10px !important;
}
.profile-dropdown-header h6 {
    margin: 0 0 5px 0 !important;
    font-size: 16px !important;
    font-weight: 600 !important;
    color: #111 !important;
}
.profile-dropdown-header p {
    margin: 0 !important;
    font-size: 13px !important;
    color: #666 !important;
    word-break: break-all !important;
}
.profile-dropdown-menu ul {
    list-style: none !important;
    padding: 10px 0 !important;
    margin: 0 !important;
    display: block !important;
}
.profile-dropdown-menu ul li {
    padding: 0 !important;
    display: block !important;
    margin: 0 !important;
}
.profile-dropdown-menu ul li a {
    display: flex !important;
    align-items: center !important;
    padding: 10px 20px !important;
    color: #333 !important;
    text-decoration: none !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    transition: background 0.2s !important;
    border: none !important;
}
.profile-dropdown-menu ul li a i {
    width: 24px !important;
    color: #666 !important;
    font-size: 16px !important;
}
.profile-dropdown-menu ul li a:hover {
    background: #f4f5f8 !important;
    color: #111 !important;
}
.profile-dropdown-menu ul li a:hover i {
    color: #f37254 !important;
}
/* Tablet specific adjustments to fit everything on one line (992px - 1250px) */
@media (min-width: 992px) and (max-width: 1250px) {
    .header-wrapper {
        padding: 5px 10px !important;
    }
    .logo-wrapper {
        min-width: 150px !important;
    }
    .logo-wrapper img {
        width: 150px !important;
        height: 45px !important;
    }
    .hero-search-bar {
        margin-left: 10px !important;
        max-width: 250px !important; /* shrink search bar */
    }
    .nav-links-container .main-menu {
        gap: 12px !important; /* smaller gap between links */
    }
    .nav-links-container .main-menu li a {
        font-size: 12px !important; /* smaller font */
    }
    .login-registration-list__item {
        margin-left: 10px !important;
    }
    .login-registration-list__item::before {
        left: -10px !important;
    }
    .login-registration-list__item a {
        font-size: 12px !important;
    }
    .profile-dropdown {
        margin-left: 10px !important;
    }
}

/* Mobile responsive */
@media (min-width: 992px) {
    .sidebar-menu-show-hide {
        display: none !important;
    }
}
@media (max-width: 991px) {
    .header-wrapper {
        padding: 10px 15px !important;
        display: flex !important;
        flex-wrap: wrap !important;
        align-items: center !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
    .sidebar-menu-show-hide {
        order: 1 !important;
        margin-right: 15px !important;
        font-size: 22px !important;
        flex-shrink: 0 !important;
        display: block !important;
    }
    .header-menu-wrapper {
        order: 2 !important;
        flex-grow: 1 !important;
        display: flex !important;
    }
    .logo-wrapper {
        min-width: auto !important;
        flex-grow: 0 !important;
        flex-shrink: 1 !important;
    }
    .logo-wrapper img {
        width: auto !important;
        max-width: 140px !important;
        height: 35px !important;
        object-fit: contain !important;
    }
    .mobile-header-auth {
        order: 3 !important;
        flex-shrink: 0 !important;
        display: flex !important;
        align-items: center !important;
    }
    .mobile-header-auth a {
        padding: 0 !important;
        display: flex !important;
        align-items: center !important;
    }
    .mobile-header-auth a i {
        font-size: 26px !important;
        color: #111 !important;
    }
    .mobile-header-auth .profile-avatar {
        width: 32px !important;
        height: 32px !important;
        border-radius: 50% !important;
        background: #5c3b99 !important;
        color: white !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 14px !important;
        font-weight: 600 !important;
    }
    .menu-right-wrapper {
        order: 4 !important;
        width: 100% !important;
        margin-top: 10px !important;
        flex-grow: 1 !important;
    }
    .menu-right-wrapper ul {
        display: flex !important;
        align-items: center !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .menu-right-wrapper .profile-dropdown,
    .menu-right-wrapper .login-registration-list__item,
    .menu-right-wrapper .nav-links-container,
    .menu-right-wrapper .language {
        display: none !important;
    }
    .hero-search-bar { 
        display: block !important; 
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        list-style: none !important;
    }
    .hero-search-bar form {
        display: flex !important;
        align-items: center !important;
        height: 40px !important;
        border: 1px solid #d1d5db !important;
        width: 100% !important;
        border-radius: 20px !important;
        background: #f4f5f8 !important;
        padding: 0 10px !important;
    }
    .hero-search-bar form input {
        order: 2 !important;
        font-size: 14px !important;
        padding: 0 !important;
        width: 100% !important;
        background: transparent !important;
        border: none !important;
        outline: none !important;
        flex-grow: 1 !important;
    }
    .hero-search-bar button.search-btn {
        order: 1 !important;
        position: static !important;
        width: auto !important;
        background: transparent !important;
        border: none !important;
        color: #666 !important;
        padding: 0 8px 0 0 !important;
        display: flex !important;
        align-items: center !important;
    }
    .hero-search-bar button.search-btn span {
        display: none !important;
    }
}

@media (min-width: 992px) {
    .mobile-header-auth { display: none !important; }
}
</style>
@php
$languages = App\Models\Language::all();
$pages = App\Models\Page::where('tempname', $activeTemplate)->get();
$user = auth()->user();
@endphp
<div class="header" id="header">
    <div class="container-fluid container-custom position-relative">
        <div class="row">
            <div class="header-wrapper">
                <!-- ham menu -->
                <i class="fas fa-bars sidebar-menu-show-hide"></i>
                <div class="header-menu-wrapper align-items-center d-flex">
                    <div class="logo-wrapper">
                        <a href="{{ route('home') }}" class="logo-normal">
                            <img src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="{{ config('app.name') }}" height="60" fetchpriority="high">
                        </a>
                    </div>
                </div>
                <!-- / logo -->
                {{-- Mobile only: profile/login directly in header row 1 --}}
                @auth
                @php $user = auth()->user(); $name = $user->firstname ?? $user->username; $initial = strtoupper(substr($name, 0, 1)); @endphp
                <div class="mobile-header-auth">
                    <div class="profile-avatar">{{ $initial }}</div>
                </div>
                @else
                <div class="mobile-header-auth">
                    <a href="{{ route('user.login') }}"><i class="fa fa-user"></i></a>
                </div>
                @endauth
                <div class="menu-right-wrapper">
                    <ul>
                        {{-- 
                        <li class="language">
                            <div class="language-box">
                                <select class="select langSel">
                                    @foreach ($languages as $language)
                                    <option value="{{ $language->code }}" @if (Session::get('lang')===$language->
                                        code) selected @endif>
                                        {{ __($language->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </li>
                         --}}
                        <li class="hero-search-bar">
                            <form action="{{route('single.coupon.search')}}" method="get">
                                <input class="form--control" name="search" type="text" placeholder="@lang('Search') ...">
                                <button class="search-btn btn btn--base" type="submit"> <i class="fas fa-search"></i></button>
                            </form>
                        </li>
                        
                        <!-- Moved Nav Links Here -->
                        <li class="nav-links-container">
                            <ul class="main-menu">
                                @if(auth()->check() && auth()->user()->signup_type == 2)
                                    <li><a href="{{ route('affiliate') }}">@lang('Affiliates')</a></li>
                                    <li><a href="{{ route('blog') }}">@lang('Blogs')</a></li>
                                    <li><a href="{{ route('user.my.earnings') }}">@lang('Earning')</a></li>
                                @else
                                    <li class="home">
                                        <a class="{{ Request::routeIs('home') ? 'active' : 'demo-class' }}"
                                            href="{{ route('home') }}">@lang('Home')</a>
                                    </li>
                                    @foreach ($pages as $page)
                                        @if ($page->slug != '/')
                                        <li>
                                            <a class="{{ request()->url() === route('pages', [$page->slug]) ? 'active' : '' }}"
                                                href="{{ route('pages', [$page->slug]) }}">{{ __($page->name) }}
                                            </a>
                                        </li>
                                        @endif
                                    @endforeach
                                    <li><a href="{{ route('user.my.earnings') }}">@lang('Earning')</a></li>
                                @endif
                            </ul>
                        </li>
                        
                        @auth
                        @php
                            $user = auth()->user();
                            $name = $user->firstname ?? $user->username;
                            $initial = strtoupper(substr($name, 0, 1));
                        @endphp
                        <li class="profile-dropdown">
                            <div class="profile-avatar">{{ $initial }}</div>
                            <div class="profile-dropdown-menu">
                                <div class="profile-dropdown-header">
                                    <div class="big-avatar">{{ $initial }}</div>
                                    <h6>{{ $name }}</h6>
                                    <p>{{ $user->email }}</p>
                                    <a href="{{ route('user.home') }}" class="btn btn--base mt-3" style="width: 100%; border-radius: 20px;">@lang('Dashboard')</a>
                                </div>
                                <ul>
                                    <li style="border-top: 1px solid #eaeaec; margin-top: 5px; padding-top: 5px;">
                                        <a href="{{ route('user.logout') }}"><i class="fas fa-sign-out-alt"></i> @lang('Sign out')</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        @else
                        <li class="login-registration-list__item">
                            <a href="{{ route('user.login') }}" class="">
                                <i class="fa fa-user"></i>
                                 <span class="login-text">@lang('Login/Signup')</span>
                            </a>
                        </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="sidebar-menu-wrapper">
    <div class="offcanvas-header">
        <div class="logo">
            <div class="header-menu-wrapper align-items-center d-flex">
                <div class="logo-wrapper">
                    <a href="{{ route('home') }}" class="normal-logo" id="offcanvas-logo-normal"> <img
                            src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}"
                            alt="{{ config('app.name') }}" height="60"></a>
                </div>
            </div>
        </div>
        <button type="button" class="btn--close sidebar close-hide-show"><i class="fas fa-times"></i></button>
    </div>
    <div class="offcanvas-body">
        @auth
        <div class="user-info bg--img"
            style="background: url({{ asset($activeTemplateTrue . 'images/bg/user2.jpg') }})">
            <div class="user-thumb">
                <a href="javascript:void(0)">

                    <img src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, getFileSize('userProfile')) }}"
                        alt="agent">
                </a>
            </div>
            <a href="javascript:void(0)">
                <h4>{{ __($user->fullname) }}</h4>
            </a>
        </div>
        @endauth
        <ul class="side-Nav">

            @auth
            <li>
                <a class="{{ Request::routeIs('user.home') ? 'active' : 'aaa' }}"
                    href="{{ route('user.home') }}">@lang('Dashboard')</a>
            </li>
            @endauth
            <li>
                <a class="{{ Request::routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">@lang('Home')</a>
            </li>
            <li>

                @foreach ($pages as $page)
                @if ($page->slug != '/')
                <a class="{{ request()->url() === route('pages', [$page->slug]) ? 'active' : '' }}" aria-current="page"
                    href="{{ route('pages', [$page->slug]) }}">{{ __($page->name) }}
                </a>
                @endif
                @endforeach
            </li>

            @auth
            <li>
                <a href="{{ route('user.logout') }}"> <span>
                    </span>@lang('Logout')</a>
            </li>
            @else
            <li>
                <a href="{{ route('user.login') }}" class="">
                    <i class="fa fa-user"></i>
                     @lang('Login/Signup')
                </a>
            </li>
            @endauth
        </ul>
    </div>
</div>

