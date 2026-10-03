@php
    $blog = getContent('blog.content',true);
    $blogElements = getContent('blog.element',false,3);
    $firstAd = App\Models\Ad::first();
@endphp
<!-- ==================== Blog Start Here ==================== -->
<section class="blog section-bg-before bg-light py-20">
    <div class="container-fluid container-custom">
        {{-- 
        @if($firstAd)
        <div class="long-add-wrap">
           <div class="long-add-wrap--thumb">
                <a href="{{@$firstAd->link}}" target="_blank">
                    <img src="{{ getImage(getFilePath('adImage') . '/' . @$firstAd->image) }}" alt="">
                </a>
           </div>
        </div>
        @else
        @endif
         --}}
    @if(request()->route()->uri == '/')
    <div class="row">
      <div class="col-lg-12">
          <div class="section-content">
              <div class="title-wrap">
                  <h2 class="section-title mb-2 wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                      {{__($blog->data_values->heading)}}
                  </h2>
                  {{-- 
                  <a href="{{route('blog')}}">@lang("View More")</a> --}}
              </div>
          </div>
      </div>
  </div>
    @endif
        <div class="row gy-4 justify-content-center">
            @foreach($blogElements as $item)
            <div class="col-lg-4 col-md-6">
                <div class="blog-item wow animate__animated animate__fadeInUp">
                    <div class="blog-item__thumb">
                        <a href="{{ route('blog.details', ['slug' => slug($item->data_values->title), 'id' => $item->id])}}" class="blog-item__thumb-link">
                            @php
                                $blogImage = @$item->data_values->blog_image;
                            @endphp
                            
                            @if($blogImage)
                                <img src="{{ getImage(getFilePath('blog') . '/thumb_' . $blogImage) }}" alt="image">
                            @else
                                <img src="{{ asset('assets/images/default.png') }}" alt="image">
                            @endif
                        </a>
                    </div>
                    <div class="blog-item__content">
                        <ul class="text-list inline">
                            <li class="text-list__item"> <span class="text-list__item-icon"><i class="fas fa-calendar-alt"></i></span> {{showDateTime($item->created_at)}}</li>
                        </ul>
                        <h4 class="blog-item__title">
                            <a href="{{ route('blog.details', ['slug' => slug($item->data_values->title), 'id' => $item->id])}}" class="blog-item__title-link">
                                @if(strlen(__($item->data_values->title)) >50)
                                {{substr( __($item->data_values->title), 0,50).'...' }}
                                @else
                                {{__($item->data_values->title)}}
                                @endif
                            </a>
                        </h4>
                        <a href="{{ route('blog.details', ['slug' => slug($item->data_values->title), 'id' => $item->id])}}" class="btn btn--base">
                            @lang('Read More')
                            <span class="btn--simple__icon">
                            <i class="fas fa-arrow-right"></i>
                        </span>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- ==================== Blog End Here ==================== -->
