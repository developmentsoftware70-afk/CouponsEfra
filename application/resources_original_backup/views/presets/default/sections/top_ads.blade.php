@php 
    $topAds = getContent('top_ads.content', true);
    $topAdsElements = getContent('top_ads.element', false);
@endphp

<section class="ads-deal-section section-bg-base">
  <div class="ads-slider">
    @foreach($topAdsElements as $item)
      @php
          $bgImage = asset('assets/images/bg/deal-bg.png');
          if (!empty($item->data_values->top_ads_img)) {
              $bgImage = getImage(getFilePath('topAdsBannerImage') . '/' . $item->data_values->top_ads_img);
          }
      @endphp

      <a href="{{ $item->data_values->link }}" class="single-slide-link d-block text-decoration-none" target="_blank" rel="noopener noreferrer">
        <div class="single-slide position-relative text-center">
          <img src="{{ $bgImage }}" alt="Top Ad" class="img-fluid w-100 slide-image">
        </div>
      </a>
    @endforeach
  </div>
</section>
