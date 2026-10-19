@extends($activeTemplate.'layouts.frontend')
@section('content')

@push('style')
<style>
    /* ========================================================
       UNIFIED BLOG CARD SYSTEM (Image & Text in ONE Connected Card)
       ======================================================== */
    .unified-blog-card {
        background: #ffffff;
        border: 1px solid #eaedf2;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .unified-blog-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.08);
        border-color: #cbd5e1;
    }

    /* Thumbnail sits flush at the top of the card */
    .unified-blog-card__thumb {
        position: relative;
        width: 100%;
        aspect-ratio: 16 / 9;
        overflow: hidden;
        background-color: #f1f5f9;
        border-radius: 0 !important;
    }

    .unified-blog-card__thumb a {
        display: block;
        width: 100%;
        height: 100%;
    }

    .unified-blog-card__thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        border-radius: 0 !important;
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .unified-blog-card:hover .unified-blog-card__thumb img {
        transform: scale(1.05);
    }

    /* Card Content directly connected to the image */
    .unified-blog-card__content {
        padding: 20px 22px;
        background: #ffffff;
        display: flex;
        flex-direction: column;
        flex: 1;
        border-top: 1px solid #f1f5f9;
    }

    .unified-blog-card__meta {
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 10px;
    }

    .unified-blog-card__meta i {
        color: #ef4444;
        font-size: 14px;
    }

    .unified-blog-card__title {
        font-size: 16.5px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.45;
        margin-bottom: 16px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 2.9rem;
    }

    .unified-blog-card__title a {
        color: inherit;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .unified-blog-card:hover .unified-blog-card__title a {
        color: #ef4444;
    }

    .unified-blog-card__footer {
        margin-top: auto;
        padding-top: 6px;
    }

    .btn-blog-details {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: #0f172a;
        color: #ffffff !important;
        font-size: 13px;
        font-weight: 600;
        padding: 8px 18px;
        border-radius: 8px;
        border: none;
        text-decoration: none !important;
        transition: all 0.2s ease;
        width: fit-content;
    }

    .btn-blog-details:hover {
        background: #ef4444;
        color: #ffffff !important;
        gap: 10px;
    }
</style>
@endpush

<section class="blog py-40">
    <div class="container-fluid container-custom">
        <div class="row gy-4 justify-content-center">
            @foreach($blogs as $item)
                @php
                    $rawTitle = @$item->data_values->title ?? 'Blog';
                    $cleanTitle = html_entity_decode(strip_tags($rawTitle));
                    $blogImage = @$item->data_values->blog_image ?? null;
                    $detailUrl = route('blog.details', ['slug' => slug($cleanTitle), 'id' => $item->id]);
                @endphp

                <div class="col-lg-4 col-md-6">
                    <article class="unified-blog-card">
                        <!-- Top Image (Seamlessly connected to card frame) -->
                        <div class="unified-blog-card__thumb">
                            <a href="{{ $detailUrl }}" tabindex="-1" aria-hidden="true">
                                @if($blogImage)
                                    <img src="{{ getImage(getFilePath('blog') . '/thumb_' . $blogImage) }}" alt="{{ $cleanTitle }}" loading="lazy">
                                @else
                                    <img src="{{ asset('assets/images/default.png') }}" alt="{{ $cleanTitle }}" loading="lazy">
                                @endif
                            </a>
                        </div>

                        <!-- Card Body (Together with image inside one solid card) -->
                        <div class="unified-blog-card__content">
                            <div class="unified-blog-card__meta">
                                <i class="fas fa-calendar-alt"></i>
                                <span>{{ showDateTime($item->created_at, 'M d, Y') }}</span>
                            </div>

                            <h4 class="unified-blog-card__title">
                                <a href="{{ $detailUrl }}" title="{{ $cleanTitle }}">
                                    {{ $cleanTitle }}
                                </a>
                            </h4>

                            <div class="unified-blog-card__footer">
                                <a href="{{ $detailUrl }}" class="btn-blog-details">
                                    <span>@lang('Details')</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                </div>
            @endforeach

            <div class="col-sm-12">
                <nav>
                    <ul class="pagination mt-3 justify-content-center">
                        @if ($blogs->hasPages())
                            <div class="py-4">
                                {{ paginateLinks($blogs) }}
                            </div>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</section>

@if(isset($sections->secs) && $sections->secs != null)
    @foreach(json_decode($sections->secs) as $sec)
        @include($activeTemplate.'sections.'.$sec)
    @endforeach
@endif

@endsection