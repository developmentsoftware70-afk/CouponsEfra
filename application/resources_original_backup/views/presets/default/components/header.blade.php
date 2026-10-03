<!--========================== Header section Start ==========================-->
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
                            <img src="{{ getImage(getFilePath('logoIcon') . '/logo.png', '?' . time()) }}" alt="{{ config('app.name') }}">
                        </a>
                    </div>
                </div>
                <!-- / logo -->
                <div class="menu-wrapper">
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
                </div>
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
                                <button class="search-btn btn btn--base" type="submit"> <i class="fas fa-search"></i> <span></button>
                            </form>
                        </li>
                        
                        @auth
                        <li class="home">
                            <a class="{{ Request::routeIs('user.home') ? 'active' : 'demo-class' }}"
                                href="{{ route('user.home') }}">{{ auth()->user()->firstname ?? auth()->user()->username }}</a>
                        </li>
                        <li class="login-registration-list__item">
                            <a href="{{ route('user.logout') }}" class="login-registration-list__link">
                                @lang('Logout')
                            </a>
                        </li>
                        @else
                        <li class="login-registration-list__item">
                            <a href="{{ route('user.login') }}" class="">
                                <i class="fa fa-user"></i>
                                 @lang('Login/Signup')
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
                            src="{{ getImage(getFilePath('logoIcon') . '/logo.png', '?' . time()) }}"
                            alt="{{ config('app.name') }}"></a>
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