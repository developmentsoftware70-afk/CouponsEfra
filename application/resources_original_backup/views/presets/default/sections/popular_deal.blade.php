@php
use App\Models\DealCategory;

// Moved filters into the model relationship
$categoryWiseDeals = DealCategory::where('status', 1)
    ->where('is_show', 1)
    ->with('deals')
    ->get();
$bgClass = ['bg-light', 'bg-white'];
@endphp

@foreach($categoryWiseDeals as $index => $category)
    @if($category->deals->count())
        @php $randomBg = $bgClass[$index % 2]; @endphp
        <section class="exclusive-section py-20 {{ $randomBg }}">
            <div class="container-fluid container-custom">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-content">
                            <div class="title-wrap">
                                <h2 class="section-title mb-2 wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                                    {{ $category->title }}
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="slider-wrapper">
                            <div class="deal-slider">
                                @foreach ($category->deals as $item)
                                    @if($item->image)
                                    <div class="slick-slide-inner">
                                        <div class="testimonial-card p-0">
                                            <a href="#" class="getDeal"
                                                data-id="{{ $item->id }}"
                                                data-title="{{ $item->title }}"
                                                data-description="{{ $item->description }}"
                                                data-link="{{ $item->link }}"
                                                data-button_label="{{ $item->button_label ?? __('Grab Deal') }}">
                                                <img loading="lazy" src="{{ getImage(getFilePath('deal') . '/' . $item->image) }}" class="img-fluid" alt="">
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
          
            </div>
        </section>
    @endif
@endforeach
