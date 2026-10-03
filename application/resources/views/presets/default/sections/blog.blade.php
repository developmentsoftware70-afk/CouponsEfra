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
    <style>
        /* Separated Image Card and Text Style */
        .blog-item {
            background: transparent;
            border-radius: 0;
            overflow: visible;
            border: none;
            box-shadow: none !important;
            position: relative;
            margin-bottom: 20px;
        }
        .blog-item__thumb {
            border-radius: 24px; /* Image gets the round styling like Noise deals */
            overflow: hidden;
            position: relative;
            height: 220px; 
            width: 100%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        .blog-item:hover .blog-item__thumb {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }
        .blog-item__thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }
        .blog-item:hover .blog-item__thumb img {
            transform: scale(1.05);
        }
        .blog-item__content {
            padding: 20px 5px 0 5px; /* Text sits below with padding top */
            color: #1e293b;
        }
        .blog-item .text-list {
            margin-bottom: 8px;
            padding: 0;
            list-style: none;
            display: flex;
        }
        .blog-item .text-list__item {
            font-size: 13px;
            color: #64748b;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .blog-item .text-list__item-icon i {
            color: #f43f5e; 
            font-size: 14px;
        }
        .blog-item__title {
            font-size: 16px; 
            line-height: 1.5;
            font-weight: 500; /* Removed heavy bold for a sleeker look */
            margin-bottom: 16px;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            height: 48px; 
            overflow: hidden;
            letter-spacing: 0.2px;
        }
        .blog-item__title-link {
            color: #1e293b;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .blog-item__title-link:hover {
            color: #007bff; 
        }
        .blog-item__content .btn--base {
            padding: 8px 16px !important;
            font-size: 13px !important;
            border-radius: 6px !important; 
            font-weight: 500 !important; /* Sleeker text in button */
            background: #ffffff !important; 
            color: #007bff !important; /* Blue text */
            border: 1px solid #e2e8f0 !important; /* Outlined sleek button */
            box-shadow: none !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            transition: all 0.3s ease;
        }
        .blog-item__content .btn--base:hover {
            background: #f8fafc !important;
            border-color: #cbd5e1 !important;
            color: #0056b3 !important;
        }
    </style>
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
