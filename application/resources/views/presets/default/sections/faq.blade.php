@php
  $faq = getContent('faq.content',true);
  $faqElement = getContent('faq.element',false);
@endphp
<!-- ==================== Accordion Start Here ==================== -->
<section class="accordion-area section-bg py-20 bg--img">
    <div class="container-fluid container-custom">
        <div class="row">
          <div class="col-lg-12">
              <div class="section-content">
                  <div class="title-wrap">
                      <h2 class="section-title mb-2 wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                          {{__($faq->data_values->heading)}}
                      </h2>
                  </div>
              </div>
          </div>
      </div>
        <div class="row gy-4 align-items-center justify-content-center">
            <div class="col-lg-6">
                <div class="accordion custom--accordion  wow animate__animated animate__fadeInUp" id="accordionExample">
                    @foreach ($faqElement as $item)
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="heading{{ $loop->index }}">
                        <button class="accordion-button {{ $loop->index == 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $loop->index }}" aria-expanded="{{$loop->index == 0 ? 'true': 'false'}}">
                            {{__(@$item->data_values->question)}}
                        </button>
                      </h2>
                      <div id="collapse{{ $loop->index }}" class="accordion-collapse collapse {{$loop->index == 0 ? 'show': ''}}" aria-labelledby="heading{{ $loop->index }}" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            {{ strLimit(strip_tags(@$item->data_values->answer),350) }}
                        </div>
                      </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-6">
                <div class="faq-image-wrapper wow animate__animated animate__fadeInUp">
                   <div class="thumb">
                        <img src="{{ getImage(getFilePath('faq') . '/' . $faq->data_values->faq_image) }}" alt="@lang('faq-image')">
                   </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ==================== Accordion End Here ==================== -->
