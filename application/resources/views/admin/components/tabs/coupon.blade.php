<div class="row">
    <div class="col">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.coupon.index') ? 'active' : '' }}"
                    href="{{route('admin.coupon.index')}}">@lang('Active')
                    @if($allCoupon)
                    <span class="badge rounded-pill bg--white text-muted">{{$allCoupon}}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.coupon.approved') ? 'active' : '' }}"
                    href="{{route('admin.coupon.approved')}}">@lang('Approved')
                    @if($approvedCoupon)
                    <span class="badge rounded-pill bg--white text-muted">{{$approvedCoupon}}</span>
                    @endif
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.coupon.pending') ? 'active' : '' }}"
                    href="{{route('admin.coupon.pending')}}">@lang('Pending')
                    @if($pendingCoupon)
                    <span class="badge rounded-pill bg--white text-muted">{{$pendingCoupon}}</span>
                    @endif
                </a>
            </li>
        </ul>
    </div>
</div>