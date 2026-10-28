@php
    $languages = App\Models\Language::all();
    $pages = App\Models\Page::where('tempname', $activeTemplate)->get();
    $user = auth()->user();
    $socialIconElements = getContent('social_icon.element', false);
@endphp
<div class="header-main-area two">
    <div class="header-top">
        <div class="container">
            <div class="row">
                <div class="top-header-wrapper">
                     <!-- logo -->
                     <div class="header-menu-wrapper align-items-center d-flex">
                        <div class="logo-wrapper">
                            <a href="{{ route('home') }}" class="logo-normal">
                                <img src="{{ getImage(getFilePath('logoIcon') . '/logo_white.png', '?' . time()) }}"
                                    alt="{{ config('app.name') }}">
                            </a>
                        </div>
                    </div>
                    <!-- / logo -->
                    <div class="top-button">
                        <div class="language-box-2">
                            <select class="select langSel">
                                @foreach ($languages as $language)
                                    <option value="{{ $language->code }}"
                                        @if (Session::get('lang') === $language->code) selected @endif>
                                        {{ __($language->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- social item -->
                        <ul>
                            @foreach( $socialIconElements as $item)
                            <li>
                                <a href="{{$item->data_values->url}}">
                               @php echo $item->data_values->social_icon; @endphp
                                </a> 
                            </li>
                        @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header header-2" id="header">
        <div class="container position-relative">
            <div class="row">
                <div class="header-wrapper-2">
                    <!-- ham menu -->
                    <i class="fas fa-bars sidebar-menu-show-hide"></i>

                    <div class="menu-wrapper">
                        <ul class="main-menu">
                            @auth
                                <li class="home">
                                    <a class='{{ Request::routeIs('user.home') ? 'active' : 'aa' }}'
                                        href="{{ route('user.home') }}">@lang('Dashboard')</a>
                                </li>
                            @endauth
                            <li class="home">
                                <a class='{{ Request::routeIs('home') ? 'active' : 'aa' }}'
                                    href="{{ route('home') }}">@lang('Home')</a>
                            </li>
                            @foreach ($pages as $page)
                                @if ($page->slug != '/')
                                    <a class="{{ request()->url() === route('pages', [$page->slug]) ? 'active' : 'aa' }}"
                                      
                                        href="{{ route('pages', [$page->slug]) }}">{{ __($page->name) }}
                                    </a>
                                @endif
                            @endforeach
                           
                        </ul>

                    </div>


                    <div class="menu-right-wrapper two">
                        <ul>
                            @auth
                                <li class="login-registration-list__item">
                                    <a href="{{ route('user.logout') }}" class="login-registration-list__link"> 
                                        @lang('Logout')
                                    </a> 
                                </li>
                            @else
                                <li class="login-registration-list__item">
                                    <a href="{{ route('user.login') }}" class="login-registration-list__link"> 
                                        @lang('Login')
                                    </a> 
                                </li>
                            @endauth
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!--========================== Sidebar mobile menu wrap Start ==========================-->
<!-- CashKaro Style Category Drawer -->
<div class="sidebar-menu-wrapper">
    <!-- Top Bar with Category title and Close (✕) button -->
    <div class="ck-drawer-header">
        <span class="ck-drawer-title">@lang('Category')</span>
        <button type="button" class="ck-drawer-close close-hide-show" aria-label="Close">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Scrollable Drawer Body -->
    <div class="ck-drawer-body">
        @auth
        @php
            $name = $user->fullname ?? $user->firstname ?? $user->username;
            $initial = strtoupper(substr($name, 0, 1));
        @endphp
        <div class="ck-drawer-user-card">
            <div class="ck-drawer-user-avatar">
                @if(!empty($user->image) && file_exists(getFilePath('userProfile') . '/' . $user->image))
                    <img src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, getFileSize('userProfile')) }}" alt="{{ $name }}">
                @else
                    <span>{{ $initial }}</span>
                @endif
            </div>
            <div class="ck-drawer-user-meta">
                <h6>{{ $name }}</h6>
                <a href="{{ route('user.home') }}"><i class="fas fa-th-large"></i> @lang('My Dashboard')</a>
            </div>
        </div>
        <div class="ck-drawer-divider"></div>
        @endauth

        <!-- Main Navigation Links -->
        <ul class="ck-drawer-nav">
            <li>
                <a href="{{ route('home') }}" class="ck-nav-item {{ Request::routeIs('home') ? 'active' : '' }}">
                    <span class="ck-nav-left"><i class="fas fa-home"></i> @lang('Home')</span>
                    <i class="fas fa-chevron-right ck-nav-arrow"></i>
                </a>
            </li>

            @foreach ($pages as $page)
                @if ($page->slug != '/')
                    @php
                        $pName = strtolower($page->name);
                        $pIcon = 'fas fa-tag';
                        if (str_contains($pName, 'coupon')) $pIcon = 'fas fa-ticket-alt';
                        elseif (str_contains($pName, 'cashback')) $pIcon = 'fas fa-coins';
                        elseif (str_contains($pName, 'deal')) $pIcon = 'fas fa-fire';
                        elseif (str_contains($pName, 'affiliate')) $pIcon = 'fas fa-users';
                        elseif (str_contains($pName, 'blog')) $pIcon = 'fas fa-newspaper';
                    @endphp
                    <li>
                        <a href="{{ route('pages', [$page->slug]) }}" class="ck-nav-item {{ request()->url() === route('pages', [$page->slug]) ? 'active' : '' }}">
                            <span class="ck-nav-left"><i class="{{ $pIcon }}"></i> {{ __($page->name) }}</span>
                            <i class="fas fa-chevron-right ck-nav-arrow"></i>
                        </a>
                    </li>
                @endif
            @endforeach

            @if(Route::has('feature.store'))
            <li>
                <a href="{{ route('feature.store') }}" class="ck-nav-item {{ Request::routeIs('feature.store') ? 'active' : '' }}">
                    <span class="ck-nav-left"><i class="fas fa-store"></i> @lang('All Stores')</span>
                    <i class="fas fa-chevron-right ck-nav-arrow"></i>
                </a>
            </li>
            @endif
        </ul>

        <!-- Top Categories from Database (CashKaro replica) -->
        @php
            $drawerCats = App\Models\Category::where('status', 1)->take(8)->get();
        @endphp
        @if($drawerCats && $drawerCats->count() > 0)
            <div class="ck-drawer-divider"></div>
            <div class="ck-drawer-section-label">@lang('Top Categories')</div>
            <ul class="ck-drawer-nav">
                @foreach($drawerCats as $cat)
                    @php
                        $cName = strtolower($cat->name);
                        $cIcon = 'fas fa-shapes';
                        if (str_contains($cName, 'mobile') || str_contains($cName, 'phone')) $cIcon = 'fas fa-mobile-alt';
                        elseif (str_contains($cName, 'laptop') || str_contains($cName, 'computer')) $cIcon = 'fas fa-laptop';
                        elseif (str_contains($cName, 'fashion') || str_contains($cName, 'cloth')) $cIcon = 'fas fa-tshirt';
                        elseif (str_contains($cName, 'shoe') || str_contains($cName, 'footwear')) $cIcon = 'fas fa-shoe-prints';
                        elseif (str_contains($cName, 'beauty') || str_contains($cName, 'cosmetic')) $cIcon = 'fas fa-gem';
                        elseif (str_contains($cName, 'grocery') || str_contains($cName, 'food')) $cIcon = 'fas fa-shopping-basket';
                        elseif (str_contains($cName, 'electronic')) $cIcon = 'fas fa-tv';
                        elseif (str_contains($cName, 'health')) $cIcon = 'fas fa-heartbeat';
                        elseif (str_contains($cName, 'travel')) $cIcon = 'fas fa-plane';
                    @endphp
                    <li>
                        <a href="{{ route('category.coupons', $cat->id) }}" class="ck-nav-item">
                            <span class="ck-nav-left">
                                <i class="{{ $cIcon }}"></i>
                                {{ $cat->name }}
                            </span>
                            <i class="fas fa-chevron-right ck-nav-arrow"></i>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="ck-drawer-divider"></div>

        <!-- Account Section -->
        <ul class="ck-drawer-nav">
            @auth
                <li>
                    <a href="{{ route('user.home') }}" class="ck-nav-item {{ Request::routeIs('user.home') ? 'active' : '' }}">
                        <span class="ck-nav-left"><i class="fas fa-tachometer-alt"></i> @lang('Dashboard')</span>
                        <i class="fas fa-chevron-right ck-nav-arrow"></i>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.logout') }}" class="ck-nav-item" style="color: #ef4444 !important;">
                        <span class="ck-nav-left"><i class="fas fa-sign-out-alt" style="color: #ef4444 !important;"></i> @lang('Logout')</span>
                        <i class="fas fa-chevron-right ck-nav-arrow"></i>
                    </a>
                </li>
            @else
                <li>
                    <a href="{{ route('user.login') }}" class="ck-nav-item">
                        <span class="ck-nav-left"><i class="fas fa-user-circle"></i> @lang('Login / Signup')</span>
                        <i class="fas fa-chevron-right ck-nav-arrow"></i>
                    </a>
                </li>
            @endauth
        </ul>
    </div>
</div>
