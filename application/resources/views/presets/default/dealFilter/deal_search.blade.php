<div class="row gy-4">
                    <style>
                    .vertical-coupon-card {
                        position: relative;
                        border-radius: 12px;
                        overflow: hidden;
                        margin-bottom: 24px;
                        height: 220px;
                        display: block;
                        background: #f8f9fa;
                        border: 1px solid #eaeaea;
                    }
                    .vertical-card-img {
                        width: 100%;
                        height: 100%;
                    }
                    .vertical-card-img img {
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                    }
                    .vertical-grab-btn {
                        position: absolute;
                        bottom: 12px;
                        right: 12px;
                        background: #0053c8;
                        color: #fff;
                        padding: 8px 16px;
                        border-radius: 6px;
                        font-weight: 600;
                        text-decoration: none;
                        font-size: 14px;
                        z-index: 2;
                        transition: background 0.2s;
                    }
                    .vertical-grab-btn:hover {
                        background: #003a8c;
                        color: #fff;
                    }
                    </style>
                    @forelse($deals as $index => $item)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="vertical-coupon-card wow animate__animated animate__fadeInUp" data-wow-delay="{{ 0.2 + ($index * 0.1) }}s">
                            <div class="vertical-card-img">
                                @if($item->image)
                                    <img src="{{ getImage(getFilePath('deal') . '/' . @$item->image) }}" alt="">
                                @else
                                    <img src="{{ getImage(getFilePath('dealCategory') . '/' . @$item->dealCategory->image) }}" alt="">
                                @endif
                            </div>
                            
                            <a href="javascript:void(0)" class="vertical-grab-btn getDeal" 
                                data-id="{{ $item->id }}" data-title="{{ $item->title }}" 
                                data-description="{{ $item->description }}" data-link="{{ $item->link }}"  
                                data-button_label="{{ $item->button_label ?? __('Grab Deal') }}">
                                {{ $item->button_label ?? __('Grab Deal') }}
                            </a>
                        </div>
                    </div>
    @empty
    <p class="text-center">{{__($emptyMessage ?? 'No deals found')}}</p>
    @endforelse
</div>

<!-- Modal (keep once) -->
<div class="modal fade" id="dealModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="btn-wrap">
                <button data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body justify-content-center align-items-center p-1">
                <div class="container">
                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <a href="#" class="dealAnchor text-center" target="_blank">
                                <div class="logo-thumb">
                                    <img class="dealImage img-fluid w-100" src="{{ asset('assets/images/general/logo.png') }}" alt="modal-image">
                                </div>
                            </a>
                            <div class="d-flex gap-2 mt-3">
                                <a href="#" class="btn btn--base dealAnchor copy-link-btn w-100">@lang('Copy Link')</a>
                                <input type="text" class="dealLink visually-hidden" name="link">
                                <a href="#" class="btn btn--base dealBtn w-100" target="_blank"><span class="buttonLabel"></span></a>
                            </div>
                            <div class="footer-item social-wrapper mt-4">
                                <p class="mb-3">@lang('Share this deal now.')</p>
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
                                {{-- <h5 class="dealTitle mt-3"></h5> --}}
                                <div class="dealDescription mt-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('script')
    <script>
        (function($) {
            "use strict";
            // Event delegation for dynamically added content
            $(document).on('click', '.getDeal', function(e) {
                e.preventDefault();
                const modal = $('#dealModal');

                const title = $(this).data('title');
                const description = $(this).data('description');
                const link = $(this).data('link');
                const button_label = $(this).data('button_label');
                const dealId = $(this).data('id');

                modal.find('.buttonLabel').text(button_label ?? '{{ __("Grab Deal") }}');
                modal.find('.dealDescription').html(description);
                modal.find('.dealAnchor').attr('href', link);
                modal.find('.dealBtn').attr('href', link);

                modal.find('input[name=link]').val(link);
                modal.find('.dealLink').val(link);

                // Update share links
                $('.share-link[data-social="facebook"]').attr('href', 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(link));
                $('.share-link[data-social="twitter"]').attr('href', 'https://twitter.com/intent/tweet?url=' + encodeURIComponent(link));
                $('.share-link[data-social="linkedin"]').attr('href', 'https://www.linkedin.com/shareArticle?url=' + encodeURIComponent(link));

                getDealInfo(dealId);
                modal.modal('show');
            });
            // Fix: Use delegated binding for copy link button
            $(document).on('click', '.copy-link-btn', function(e) {
                e.preventDefault();
                const link = $('.dealLink').val();

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

            function getDealInfo(dealId) {
                $.ajax({
                    url: '{{ route('get.modal.dealInfo') }}',
                    type: 'get',
                    data: { dealId: dealId },
                    success: function(response) {
                        if (response.dealImage) {
                            $('.dealImage').attr('src', response.dealImage);
                        } else if (response.dealCatImage) {
                            $('.dealImage').attr('src', response.dealCatImage);
                        } else {
                            console.error('Deal image not found');
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching deal info:', error);
                    }
                });
            }
           
        })(jQuery);
    </script>
@endpush