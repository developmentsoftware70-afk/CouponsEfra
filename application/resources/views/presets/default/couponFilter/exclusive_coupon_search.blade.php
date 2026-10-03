<div class="row gy-4">
    @forelse($exclusiveCoupons as $index => $item)
    <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="coupon-card wow animate__animated animate__fadeInUp" data-wow-delay="{{ 0.2 + ($index * 0.1) }}s">
            <div class="card-thumb">
                @if($item->image)
                <img src="{{ getImage(getFilePath('coupon') . '/' . @$item->image) }}" class="img-fluid" alt="@lang('Coupon Image')">
                @else
                <img src="{{ getImage(getFilePath('store') . '/' . @$item->store->image) }}" alt="@lang('Store Image')">
                @endif
                <div class="card-ribbon-wrap">
                    <div class="ex-cta">
                        @if($item->is_exclusive == 1)
                        <p>@lang('Exclusive')</p>
                        @endif
                    </div>
                    <button class="fav-cta addToWishList" data-id="{{ $item->id }}">
                        @if (auth()->check() && $item->wishlists->count() > 0)
                            <i class="fas fa-heart"></i>
                        @else
                            <i class="far fa-heart"></i>
                        @endif
                    </button>
                </div>
            </div>
            <div class="card-content-wrap">
                <p class="card-title">{{__($item->title)}}</p>
                <a href="javascript:void(0)" class="btn btn--base w-100 getCoupon" data-id="{{$item->id}}" data-title="{{$item->title}}" data-code="{{$item->code}}" data-description="{{$item->description}}" data-link="{{$item->link}}">{{ $item->code ? __('Get Code') : __('Get Deal') }}</a>
                    <p class="card-action">{!! isExpired($item->id) !!}</p>
            </div>
        </div>
    </div>
    @empty
    <p class="text-center h4">{{__($emptyMessage)}}</p>
    @endforelse
</div>
<!-- Modal (keep once) -->
<div class="modal fade" id="couponModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="btn-wrap">
                <button data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body justify-content-center align-items-center p-1">
                <div class="container">
                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <a href="#" class="couponName text-center" target="_blank">
                                <div class="logo-thumb">
                                    <img class="couponImage img-fluid w-100" src="{{ asset('assets/images/general/logo.png') }}" alt="">
                                </div>
                            </a>
                            <div class="d-flex gap-2 mt-3">
                                <a href="" class="btn btn--base copy-btn copytext w-100" id="copyBoard-feature" target="_blank">@lang('Copy Code')</a>
                                <input type="text" class="coponCodeCopyFeature visually-hidden" name="code">
                                <a href="" class="btn btn--base couponName w-100" id="copyLink" target="_blank">@lang('Copy Link')</a>
                                <input type="text" class="couponLinkCopyFeature visually-hidden" name="link">
                                <a href="#" class="btn btn--base couponName w-100" target="_blank">@lang('Grab Deal')</a>
                            </div>
                            <div class="footer-item social-wrapper mt-4">
                                <p class="mb-3">@lang('Share this coupon now.')</p>
                                <ul class="social-list">
                                    <li class="social-list__item">
                                        <a href="#" class="social-list__link share-link" target="_blank" data-social="facebook"><i class="fab fa-facebook-f"></i></a>
                                    </li>
                                    <li class="social-list__item">
                                        <a href="#" class="social-list__link share-link" target="_blank" data-social="twitter"><i class="fab fa-twitter"></i></a>
                                    </li>
                                    <li class="social-list__item">
                                        <a href="#" class="social-list__link share-link" target="_blank" data-social="linkedin"><i class="fab fa-linkedin-in"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-7 mb-3 scroll_section_popup">
                            <div class="p-2">
                                {{-- <p class="title"></p> --}}
                                <div class="description mt-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function ($) {
        "use strict";
        // .getCoupon.on('click', function() {
            var modal = $('#couponModal');

            modal.find('.title').text($(this).data('title'));
            modal.find('.description').html($(this).data('description'));
            modal.find('input[name=code]').val($(this).data('code'));
            modal.find('.couponName').attr('href', $(this).data('link'));
            modal.find('.storeName').attr('href', $(this).data('link'));
            modal.find('.copytext').attr('href', $(this).data('link'));

            modal.find('input[name=link]').val($(this).data('link'));
            modal.find('.couponLinkCopyFeature').val($(this).data('link'));

            var couponId = $(this).data('id');
            getCouponInfo(couponId)

            var shareLink = $(this).data('link');
            // Update the href attribute of the share links
            $('.share-link[data-social="facebook"]').attr('href',
                'https://www.facebook.com/sharer/sharer.php?u=' + shareLink);
            $('.share-link[data-social="twitter"]').attr('href',
                'https://twitter.com/intent/tweet?url=' + shareLink);
            $('.share-link[data-social="linkedin"]').attr('href',
                'https://www.linkedin.com/shareArticle?url=' + shareLink);

            modal.modal('show');
        });

        $('#copyBoard-feature').off('click').on('click', function(e) {
            e.preventDefault();

            var copyText = document.querySelector(".coponCodeCopyFeature");

            if (copyText) {
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                document.execCommand("copy");
                copyText.blur();

                var notification = $('.coupon-code-notification');
                if (notification.length > 0) {
                    notification.text('Copied Code').fadeIn().delay(1000).fadeOut(100);
                } else {
                    alert('Copied: ' + copyText.value);
                }

                $(this).addClass('copied');

                var href = $(this).attr('href');
                if (href && href !== '#') {
                    setTimeout(function() {
                        window.open(href, '_blank');
                    }, 2000);
                }
            } else {
                alert('Copy target not found.');
            }
        });

        $('#copyLink').on('click', function(e) {
            e.preventDefault();

            const link = $('.couponLinkCopyFeature').val();
            if (!link) return alert('Link not found.');

            navigator.clipboard.writeText(link).then(function () {
                const notification = $('.coupon-code-notification');
                notification.length
                    ? notification.text('Link Copied').fadeIn().delay(1000).fadeOut(100)
                    : alert('Link Copied: ' + link);
            }).catch(function (err) {
                alert('Failed to copy: ' + err);
            });
        });

        // get modal into product info
        function getCouponInfo(couponId) {
            $.ajax({
                url: '{{ route('get.modal.info') }}',
                type: 'get',
                data: {
                    couponId: couponId,
                },
                success: function(response) {
                    if (response.couponImage) {
                        $('.couponImage').attr('src', response.couponImage);
                    } else if (response.storeImage) {
                        $('.couponImage').attr('src', response.storeImage);
                    } else {
                        console.error('store image could not found');
                    }

                },
                error: function(error) {
                    console.error('Error fetching product info:', error);
                }
            });
        }
        // add to wishlist
        $(document).on('click', '.addToWishList', function() {
            'use strict';
            var isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
            if (isLoggedIn) {
                var couponId = $(this).data('id');
                var button = $(this);
                $.ajax({
                    url: '{{ route('user.wishlist.add') }}',
                    type: 'get',
                    data: {
                        couponId: couponId,
                    },
                    success: function(response) {
                        if (response.hasOwnProperty('message')) {
                            Toast.fire({
                                icon: 'success',
                                title: response.message
                            });
                            var heartIcon = button.find('i');
                            heartIcon.removeClass('far fa-heart').addClass('fas fa-heart');
                        } else {
                            Toast.fire({
                                icon: 'warning',
                                title: response.error
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = 'Error occurred while adding the Coupon to wishlist.';
                        Toast.fire({
                            icon: 'error',
                            title: errorMessage
                        });
                    }
                });
            } else {
                var errorMessage = 'Please log in to add items to your wishlist.';
                Toast.fire({
                    icon: 'warning',
                    title: errorMessage
                });
            }
        });
        // end wishlist
      
    })(jQuery);
</script>
