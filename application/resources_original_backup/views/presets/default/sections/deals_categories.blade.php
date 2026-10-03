@php
    $dealsCategory = getContent('deals_categories.content', true);
    $dealsCategoryElements = App\Models\DealCategory::with('deals')
        ->where('is_show', 1)
        ->where('status', 1)
        ->latest()
        ->limit(8)
        ->get();
@endphp

<section class="top-collections top-stores second py-20">
    <div class="container-fluid container-custom">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-content">
                    <div class="title-wrap">
                        <h2 class="section-title mb-2 wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                            {{ __($dealsCategory->data_values->heading) }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="slider-wrapper">
            <div class="store-slider">
                @foreach($dealsCategoryElements as $index => $item)
                    <div class="slick-slide-inner">
                        <a href="{{ route('category.deals', $item->id) }}">
                        <div class="single-box h-100 transition position-relative text-center gap-3 gap-md-4 p1-2nd-bg-color cus-border border b-eighth p-3 p-md-4 rounded-3 wow animate__animated animate__fadeInUp" data-wow-delay="{{ 0.2 + ($index * 0.1) }}s">

                            <div class="thumb-area rounded-circle deal_store_area">
                                <img class="rounded-circle img-fluid"
                                     src="{{ getImage(getFilePath('dealCategory') . '/' . $item->image) }}"
                                     alt="{{ __($item->title) }}">
                            </div>
                            <div class="d-grid gap-2 text-center">
                                <h4 class="n15-color text-nowrap fw-bold">
                                    <a href="{{ route('category.deals', $item->id) }}" class="title-cat">
                                        {{ Str::limit(__($item->title), 20) }}
                                    </a>
                                </h4>
                                <span class="n15-color fs-eight">
                                    {{ $item->deals->count() }} @lang('deals')
                                </span>
                            </div>

                        </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="row pt-2">
            <div class="col-lg-12 text-end">
                <a href="{{ route('deals.categories') }}">@lang('View More')</a>
            </div>
        </div>
    </div>
</section>
