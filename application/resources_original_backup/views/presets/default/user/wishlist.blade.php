@extends($activeTemplate.'layouts.master')
@section('content')
<div class="wishlist-area">
    <div class="account-form">
        <div class="row">
            <div class="col-lg-12">
                <table class="table table--responsive--lg">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Time')</th>
                            <th>@lang('Actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($wishLists as $key =>$item)
                            <tr>
                                <td data-label="@lang('SL')">{{__($key + 1) }}</td>
                                <td data-label="@lang('Name')"><a href="{{ route('coupon') }}">{{__($item->coupon->title) }}</a></td>
                                <td data-label="@lang('Time')">{{ showDateTime($item->created_at) }}</td>

                                <td data-label="@lang('Actions')">
                                    <div class="button--group">
                                        <a href="{{ route('user.wishlist.remove', $item->id) }}"
                                            title="@lang('Delete')" class="btn btn--base btn--sm">
                                            <i class="fas fa-trash"></i>
                                        </a>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td data-label="@lang('Wishlists')" class="text-muted text-center" colspan="100%">{{__($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table><!-- table end -->
                @if ($wishLists->hasPages())
                    <div class="d-flex justify-content-end py-4">
                        {{ paginateLinks($wishLists) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection