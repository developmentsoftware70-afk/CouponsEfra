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
            <div class="user-info bg--img" style="background: url({{ asset($activeTemplateTrue . 'images/bg/user2.jpg') }})">
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
                <a class='{{ Request::routeIs('user.home') ? 'active' : 'aaa' }}'
                    href="{{ route('user.home') }}">@lang('Dashboard')</a>
            </li>
             @endauth
            <li>
                <a class='{{ Request::routeIs('home') ? 'active' : '' }}'
                    href="{{ route('home') }}">@lang('Home')</a>
            </li>
            <li>

                @foreach ($pages as $page)
                    @if ($page->slug != '/')
                        <a class="{{ request()->url() === route('pages', [$page->slug]) ? 'active' : '' }}"
                            aria-current="page" href="{{ route('pages', [$page->slug]) }}">{{ __($page->name) }}
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
                    <a href="{{ route('user.login') }}"> <span>
                        </span>@lang('Login')</a>
                </li>
            @endauth
        </ul>
    </div>
</div>
