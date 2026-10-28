@php
    $languages = App\Models\Language::all();
    $user = auth()->user();
    $name = $user->fullname ?? $user->firstname ?? $user->username;
    $initial = strtoupper(substr($name, 0, 1));
    $isDashboardHome = Route::is('user.home');
@endphp
<div class="row dashboard-header-row">
    <div class="col-lg-12">
        <div class="dashboard-header-wrap d-flex justify-content-between align-items-center">
            <div class="header-left d-flex align-items-center gap-2">
                @if(!$isDashboardHome)
                    <!-- Back to User Dashboard Button -->
                    <a href="{{ route('user.home') }}" class="dashboard-back-btn" title="@lang('Back to Dashboard')">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                @endif
                <button type="button" class="dashboard-sidebar-toggle-btn dashboard-show-hide" title="@lang('Toggle Menu')">
                    <i class="fas fa-bars dashboard-show-hide"></i>
                </button>
                <div class="header-user-welcome ms-1">
                    <h5 class="page-header-title mb-0">{{ __($pageTitle) }}</h5>
                    @if($isDashboardHome)
                        <span class="user-greeting-sub fs-12 text-muted d-none d-sm-block">Hi, {{ $name }} 👋</span>
                    @else
                        <span class="user-greeting-sub fs-12 text-muted d-none d-sm-block">
                            <a href="{{ route('user.home') }}" class="text-muted"><i class="fas fa-chevron-left me-1 fs-10"></i>@lang('Dashboard')</a>
                        </span>
                    @endif
                </div>
            </div>
            <div class="header-right d-flex align-items-center gap-2">
                @if(!$isDashboardHome)
                    <!-- Direct Dashboard Button on sub-pages -->
                    <a class="dashboard-nav-btn d-flex align-items-center gap-1" href="{{ route('user.home') }}" title="@lang('Go to Dashboard')">
                        <i class="fas fa-th-large"></i>
                        <span class="fs-12 d-none d-sm-inline">@lang('Dashboard')</span>
                    </a>
                @endif

                <!-- User Profile / Settings (Purple Avatar) -->
                <a href="{{ route('user.profile.setting') }}" class="dashboard-profile-btn d-flex align-items-center gap-2" title="@lang('Profile Settings')">
                    <div class="profile-avatar">{{ $initial }}</div>
                    <span class="d-none d-md-inline fw-semibold fs-13 text-dark">{{ $user->username }}</span>
                </a>

                <!-- Home / Store Link -->
                <a class="dashboard-home-link d-flex align-items-center gap-1" href="{{ route('home') }}" title="@lang('Back to Website')">
                    <i class="fas fa-store-alt"></i>
                    <span class="fs-12 d-none d-sm-inline">@lang('Store')</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ====== Responsive breadcrumb (hidden duplicate) ====== -->
<div class="row d-none">
    <div class="col-lg-12">
        <div class="dashboard-header-wrap breadcrumb">
            <div class="d-flex justify-content-between">
                <div class="header-left d-flex align-items-center">
                    <h4 class="title-three mb-0"> {{__($pageTitle)}}</h4>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ====== Responsive breadcrumb end ====== -->