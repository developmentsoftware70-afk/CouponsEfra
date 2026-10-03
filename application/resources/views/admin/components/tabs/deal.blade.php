<div class="row">
    <div class="col">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.deal.index') ? 'active' : '' }}"
                    href="{{route('admin.deal.index')}}">@lang('Active')
                    @if($allDeal)
                    <span class="badge rounded-pill bg--white text-muted">{{$allDeal}}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.deal.approved') ? 'active' : '' }}"
                    href="{{route('admin.deal.approved')}}">@lang('Approved')
                    @if($approvedDeal)
                    <span class="badge rounded-pill bg--white text-muted">{{$approvedDeal}}</span>
                    @endif
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.deal.pending') ? 'active' : '' }}"
                    href="{{route('admin.deal.pending')}}">@lang('Pending')
                    @if($pendingDeal)
                    <span class="badge rounded-pill bg--white text-muted">{{$pendingDeal}}</span>
                    @endif
                </a>
            </li>
        </ul>
    </div>
</div>