@php
    $languages = App\Models\Language::all();
    $user = auth()->user();
@endphp
<div class="dashboard_profile">
    <div class="dashboard_profile__details">
        <div class="dashboard_profile_wrap text-start ms-3 mt-4 mb-5">
            <div class="logo-wrapper ms-2">
                <a href="{{ route('home') }}"> <img
                        src="{{ getImage(getFilePath('logoIcon') . '/logo.png', '?' . time()) }}"
                        alt="{{ config('app.name') }}">
                </a>
            </div>
            <i class="fas fa-times close-hide-show"></i>
        </div>
        {{-- 
        <div class="responsive-lang-wrap">
            <div class="input-grp">
                <select class="form--control form-select f-control f-dropdown" aria-label="Default select example">
                    @foreach ($languages as $language)
                    <option value="{{ $language->code }}" @if (Session::get('lang')===$language->
                        code) selected @endif>
                        {{ __($language->name) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
         --}}
        <ul class="sidebar-menu-list">
            @if (auth()->check() && auth()->user()->signup_type == 2) 
            <li class="sidebar-menu-list__item {{ Route::is('user.home') ? 'active' : '' }}">
                <a href="{{ route('affiliate') }}" class="sidebar-menu-list__link">
                    <span class="icon"><i class="fas fa-house-user"></i></span>
                    @lang('Home')
                </a>
            </li>
            @else
            <li class="sidebar-menu-list__item {{ Route::is('user.home') ? 'active' : '' }}">
                <a href="{{ route('home') }}" class="sidebar-menu-list__link">
                    <span class="icon"><i class="fas fa-house-user"></i></span>
                    @lang('Home')
                </a>
            </li>
            @endif
            <li class="sidebar-menu-list__item {{ Route::is('user.home') ? 'active' : '' }}">
                <a href="{{ route('user.home') }}" class="sidebar-menu-list__link">
                    <span class="icon"><i class="fas fa-tachometer-alt"></i></span>
                    @lang('Dashboard')
                </a>
            </li>
            @if (auth()->check() && auth()->user()->signup_type == 2) 
            <li class="sidebar-menu-list__item has-dropdown {{ Route::is('user.store.index') || Route::is('user.store.active') || Route::is('user.store.pending') || Route::is('user.store.create') ? 'active' : '' }}">
              <a href="javascript:void(0)" class="sidebar-menu-list__link">
              <span class="icon"><i class="fas fa-store"></i></span>
              @lang('My store') 
              </a>
              <div class="sidebar-submenu" style=" display: {{ Route::is('user.store.create') || Route::is('user.store.active') || Route::is('user.store.pending') || Route::is('user.store.index') ? 'block' : '' }};">
                    <ul class="sidebar-submenu-list">
                       
                        <li class="sidebar-submenu-list__item {{ Route::is('user.store.index') ? 'active' : '' }}"">
                            <a href="{{route('user.store.index')}}" class="sidebar-submenu-list__link">@lang('All Store')</a>
                        </li>
                        <li class="sidebar-submenu-list__item {{ Route::is('user.store.active') ? 'active' : '' }}"">
                            <a href="{{route('user.store.active')}}" class="sidebar-submenu-list__link">@lang('Active Store')</a>
                        </li>
                        <li class="sidebar-submenu-list__item {{ Route::is('user.store.pending') ? 'active' : '' }}"">
                            <a href="{{route('user.store.pending')}}" class="sidebar-submenu-list__link">@lang('Pending Store')</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="sidebar-menu-list__item has-dropdown {{ Route::is('user.coupons.index') || Route::is('user.coupons.active') || Route::is('user.coupons.pending') || Route::is('user.coupons.create') ? 'active' : '' }}">
              <a href="javascript:void(0)" class="sidebar-menu-list__link">
              <span class="icon"><i class="fas fa-percentage"></i></span>
              @lang('My Coupons') 
              </a>
              <div class="sidebar-submenu" style=" display: {{ Route::is('user.coupons.index') || Route::is('user.coupons.active') || Route::is('user.coupons.pending') || Route::is('user.coupons.create') ? 'block' : '' }};">
                    <ul class="sidebar-submenu-list">
                        <li class="sidebar-submenu-list__item {{ Route::is('user.coupons.index') ? 'active' : '' }}"">
                            <a href="{{route('user.coupons.index')}}" class="sidebar-submenu-list__link">@lang('All Coupon')</a>
                        </li>
                        <li class="sidebar-submenu-list__item {{ Route::is('user.coupons.active') ? 'active' : '' }}"">
                            <a href="{{route('user.coupons.active')}}" class="sidebar-submenu-list__link">@lang('Active Coupon')</a>
                        </li>
                        <li class="sidebar-submenu-list__item {{ Route::is('user.coupons.pending') ? 'active' : '' }}"">
                            <a href="{{route('user.coupons.pending')}}" class="sidebar-submenu-list__link">@lang('Pending Coupon')</a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif
            <li class="sidebar-menu-list__item {{ Route::is('user.my.wishlist') ? 'active' : '' }}">
                <a href="{{ route('user.my.wishlist') }}" class="sidebar-menu-list__link">
                    <span class="icon"><i class="far fa-heart"></i></span>
                    @lang('My Wishlists')
                </a>
            </li>
            @if (auth()->check() && (auth()->user()->signup_type == 1 || auth()->user()->signup_type == 2))  
            <li class="sidebar-menu-list__item {{ Route::is('user.my.earnings') ? 'active' : '' }}">
                <a href="{{ route('user.my.earnings') }}" class="sidebar-menu-list__link">
                    <span class="icon"><i class="fas fa-dollar-sign"></i></span>
                    @lang('My Earning')
                </a>
            </li>
            <li class="sidebar-menu-list__item {{ Route::is('user.my.payments') ? 'active' : '' }}">
                <a href="{{ route('user.my.payments') }}" class="sidebar-menu-list__link">
                    <span class="icon"><i class="fab fa-cc-visa"></i></span>
                    @lang('Payments')
                </a>
            </li>
            @endif
            {{-- 
            <li class="sidebar-menu-list__item has-dropdown {{ Route::is('user.deposit') || Route::is('user.deposit.history') ? 'active' : '' }}">
                <a href="javascript:void(0)" class="sidebar-menu-list__link">
                <span class="icon"><i class="fas fa-file-invoice-dollar"></i></span>
                <span class="text">@lang('Deposit')</span>
                </a>
                <div class="sidebar-submenu" style=" display: {{  Route::is('user.deposit') || Route::is('user.deposit.history') ? 'block' : '' }};">
                    <ul class="sidebar-submenu-list">
                        <li class="sidebar-submenu-list__item {{ Route::is('user.deposit') ? 'active' : '' }}">
                            <a href="{{route('user.deposit')}}" class="sidebar-submenu-list__link">@lang('Deposit Now')</a>
                        </li>
                        <li class="sidebar-submenu-list__item {{ Route::is('user.deposit.history') ? 'active' : '' }}">
                            <a href="{{route('user.deposit.history')}}" class="sidebar-submenu-list__link">@lang('Deposit Log') </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="sidebar-menu-list__item {{ Route::is('user.transactions') ? 'active' : '' }}">
                <a href="{{route('user.transactions')}}" class="sidebar-menu-list__link">
                <span class="icon"><i class="fas fa-funnel-dollar"></i></span>
                @lang('Transactions')</span>
                </a>
            </li>
            --}}
            <li class="sidebar-menu-list__item has-dropdown {{ Route::is('ticket') || Route::is('ticket.open') ? 'active' : '' }}">
                <a href="javascript:void(0)" class="sidebar-menu-list__link">
                    <span class="icon"><i class="fas fa-headphones"></i></span>
                    @lang('Support')
                </a>
                <div class="sidebar-submenu" style="display: {{ Route::is('ticket') || Route::is('ticket.open') ? 'block' : '' }};">
                    <ul class="sidebar-submenu-list">
                        <li class="sidebar-submenu-list__item {{ Route::is('ticket') ? 'active' : '' }}">
                            <a href="{{ route('ticket') }}" class="sidebar-submenu-list__link">@lang('Support Tickets')</a>
                        </li>
                        <li class="sidebar-submenu-list__item {{ Route::is('ticket.open') ? 'active' : '' }}">
                            <a href="{{ route('ticket.open') }}"
                                class="sidebar-submenu-list__link">@lang('New Ticket')</a>
                        </li>
                    </ul>
                </div>
            </li>            
            
            <li class="sidebar-menu-list__item has-dropdown {{ Route::is('user.change.password') || Route::is('user.profile.setting') || Route::is('user.twofactor') ? 'active' : '' }}">
                <a href="javascript:void(0)" class="sidebar-menu-list__link">
                    <span class="icon"><i class="fas fa-cog"></i></span>
                    @lang('Settings')
                </a>
                <div class="sidebar-submenu" style="display: {{ Route::is('user.change.password') || Route::is('user.profile.setting') || Route::is('user.twofactor') ? 'block' : '' }};">
                    <ul class="sidebar-submenu-list">
                        <li class="sidebar-submenu-list__item {{ Route::is('user.profile.setting') ? 'active' : '' }}">
                            <a href="{{ route('user.profile.setting') }}"
                                class="sidebar-submenu-list__link">@lang('Profile Setting')</a>
                        </li>
                        <li class="sidebar-submenu-list__item {{ Route::is('user.change.password') ? 'active' : '' }}">
                            <a href="{{ route('user.change.password') }}"
                                class="sidebar-submenu-list__link">@lang('Change Password')</a>
                        </li>
                        <li class="sidebar-submenu-list__item {{ Route::is('user.twofactor') ? 'active' : '' }}">
                            <a href="{{ route('user.twofactor') }}"
                                class="sidebar-submenu-list__link">@lang('Two Factor Auth')</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>

        <ul class="sidebar-menu-list border-top pt-4">
            <li class="sidebar-menu-list__item">
                <a href="{{ route('user.logout') }}" class="sidebar-menu-list__link">
                    <span class="icon"><i class="fas fa-sign-out-alt"></i></span>
                    @lang('Sign Out')
                </a>
            </li>
        </ul>
    </div>
</div>