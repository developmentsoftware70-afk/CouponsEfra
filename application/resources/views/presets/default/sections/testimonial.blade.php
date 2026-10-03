@php
    $testimonial = getContent('testimonial.content', true);
    $testimonialElements = getContent('testimonial.element', false);
@endphp

<!-- testimonial section -->
<section class="bg-light py-20">
    <div class="container-fluid container-custom">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-content">
                    <div class="title-wrap">
                        <h2 class="section-title mb-2 wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                            {{__( $testimonial->data_values->heading)}}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .adv-testimonial-card {
                background: #ffffff;
                border-radius: 16px;
                padding: 24px 20px; /* Reduced padding */
                box-shadow: none !important;
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                margin: 15px;
                border: 1px solid #e2e8f0;
                position: relative;
                transition: border-color 0.3s ease;
            }
            .adv-testimonial-card:hover {
                border-color: #cbd5e1;
            }
            .adv-user-avatar {
                width: 70px;
                height: 70px;
                border-radius: 50%;
                object-fit: cover;
                margin: 0 auto 16px auto;
                display: block;
            }
            .adv-review-text {
                font-size: 14px;
                color: #475569;
                line-height: 1.6;
                margin-bottom: 12px;
                font-style: normal;
                font-family: 'Inter', 'Segoe UI', sans-serif;
            }
            .adv-user-info {
                margin-top: auto;
            }
            .adv-user-info h6 {
                margin: 0 0 4px 0;
                font-size: 15px;
                font-weight: 700;
                color: #0f172a;
            }
            .adv-user-info p {
                margin: 0;
                font-size: 13px;
                color: #64748b;
                font-weight: 500;
            }
            /* Equal Height Slider Fix */
            .testimonial-slider .slick-track {
                display: flex !important;
                align-items: stretch !important;
            }
            .testimonial-slider .slick-slide {
                height: auto !important;
                display: flex !important;
                align-items: stretch !important;
            }
            .testimonial-slider .slick-slide > div {
                display: flex;
                flex: 1;
                width: 100%;
                align-items: stretch;
            }
            .testimonial-slider .col-lg-4 {
                display: flex;
                flex: 1;
                width: 100%;
            }
            .adv-testimonial-card {
                flex: 1;
                width: calc(100% - 30px);
                margin: 15px auto;
            }
        </style>

        <div class="row testimonial-slider">
            @foreach( $testimonialElements as $item)
                <div class="col-lg-4">
                    <div class="adv-testimonial-card">
                        <img class="adv-user-avatar" src="{{ getImage(getFilePath('testimonial') . '/' . $item->data_values->testimonial_img) }}" alt="@lang('testimonial-image')">
                        
                        <p class="adv-review-text">
                            @if (strlen(__($item->data_values->description)) > 110)
                            {{ substr(__($item->data_values->description), 0, 110) . '...' }}
                            @else
                                {{ __($item->data_values->description) }}
                            @endif
                        </p>
                        
                        <div class="adv-user-info">
                            <h6>{{ __($item->data_values->name) }}</h6>
                            <p>{{ __($item->data_values->title) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
  </section>
  <!--  exclusive /> -->
  