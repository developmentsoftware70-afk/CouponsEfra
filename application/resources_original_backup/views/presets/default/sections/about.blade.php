@php
  $about = getContent('about.content',true);
@endphp
<section class="py-20 bg-white">
    <div class="container-fluid container-custom">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-content">
                    <div class="title-wrap">
                        <h2 class="section-title mb-2 wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                            {{ __($about->data_values->heading) }}
                        </h2>
                    </div>
                </div>
                <div class="desc_area">{!! __($about->data_values->description) !!}</div>
            </div>
        </div>
        
    </div>
</section>
