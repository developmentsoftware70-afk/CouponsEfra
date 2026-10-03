@php 
    $dealCoupon = getContent('deal_coupon.content', true);
    $dealCouponElements = getContent('deal_coupon.element', false);
@endphp

<section class="ads-deal-section section-bg-base">
  <div class="ads-slider">
    @foreach($dealCouponElements as $item)
      @php
          $bgImage = asset('assets/images/bg/deal-bg.png');
          if (!empty($item->data_values->ads_img)) {
              $bgImage = getImage(getFilePath('adsBannerImage') . '/' . $item->data_values->ads_img);
          }
      @endphp

      <a href="{{ $item->data_values->link }}" class="single-slide-link d-block text-decoration-none" target="_blank">
        <div class="single-slide position-relative text-center">
          <img src="{{ $bgImage }}" alt="deal image" class="img-fluid w-100 slide-image">
          {{-- 
          <div class="deal-content text-white text-center position-absolute top-50 start-50 translate-middle">
            <h2 class="title wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
              {{ __($item->data_values->title) }}
            </h2>
            <p class="sub-title wow animate__animated animate__fadeInUp" data-wow-delay="0.3s">
              {!! __($item->data_values->description) !!}
            </p>
            <div class="btn btn--base bg--white text-black deal-btn wow animate__animated animate__fadeInUp" data-wow-delay="0.4s">
              {{ __($item->data_values->button) }}
            </div>
          </div>
           --}}
        </div>
      </a>
    @endforeach
  </div>
</section>
