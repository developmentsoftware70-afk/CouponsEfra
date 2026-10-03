@extends($activeTemplate.'layouts.frontend')
@section('content')
@php
       $secondAd = App\Models\Ad::skip(1)->first();
@endphp
<!-- ==================== Blog  Blog Details Start Here ==================== -->
<section class="blog-detials py-20">
    <div class="container-fluid container-custom">
        <div class="row gy-5 justify-content-center">
            <div class="col-lg-8">
                <div class="blog-details">

                    <div class="blog-item">
                        <div class="blog-item__thumb">
                            <img src="{{getImage(getFilePath('blog').'/'. $blog->data_values->blog_image)}}" alt="blog-img">
                        </div>

                        <div class="blog-item__content pt-2">
                            <ul class="text-list inline">
                                <li class="text-list__item"> <span class="text-list__item-icon"><i class="fas fa-calendar-alt"></i></span>{{showDateTime($blog->created_at)}}</li>
                            </ul>
                        </div>
                    </div>
                   <div class="blog-details__content">
                        <div class="wyg">
                            <h3 class="blog-details__title mb-4">{{__($blog->data_values->title)}}</h3>
                            <p class="blog-details__desc">
                                @php
                                echo $blog->data_values->description;
                                @endphp
                            </p>
                            <div class="blog-details__share d-flex align-items-center flex-wrap">
                                <h5 class="social-share__title mb-0 me-sm-3 me-1 d-inline-block">@lang('Share This')</h5>
                                <ul class="social-list blog-details-social-wrapper">
                                    <li class="social-list__item" data-bs-toggle="tooltip" data-bs-placement="top" title="Facebook"> <a class="social-list__link" target="_blank" href="https://www.facebook.com/share.php?u={{ Request::url() }}&title={{slug(@$element->data_values->title)}}"><i class="lab la-facebook-f"></i></a> </li>
                                    <li class="social-list__item" data-bs-toggle="tooltip" data-bs-placement="top" title="Linkedin"> <a class="social-list__link" target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&url={{ Request::url() }}&title={{slug(@$element->data_values->title)}}&source=behands"><i class="lab la-linkedin-in"></i></a> </li>
                                    <li class="social-list__item" data-bs-toggle="tooltip" data-bs-placement="top" title="Twitter"> <a class="social-list__link" target="_blank" href="https://twitter.com/intent/tweet?status={{slug(@$element->data_values->title)}}+{{ Request::url() }}"><i class="lab la-twitter"></i></a> </li>
                                </ul>
                            </div>
                        </div>
                   </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- ============================= Blog Details Sidebar Start ======================== -->
                <div class="blog-sidebar-wrapper">
                    @if($secondAd)
                    <div class="blog-sidebar text-center">
                        <!-- ad image start -->
                        <div class="sidebar-add-wrap position-relative">
                        
                            <div class="long-add-wrap--thumb">
                                <a href="{{@$secondAd->link}}" target="_blank">
                                    <img src="{{ getImage(getFilePath('adImage') . '/' . @$secondAd->image) }}" alt="">
                                </a>
                            </div>
                        </div>
                         <!-- ad image end -->
                    </div>
                    @else
                    @endif

                    <div class="blog-sidebar">
                        <h5 class="blog-sidebar__title">@lang('Latests')</h5>
                        @foreach ($latests as $item)
                        <div class="latest-blog">
                            <div class="latest-blog__thumb">
                                <a href="{{ route('blog.details', ['slug' => slug($item->data_values->title), 'id' => $item->id])}}"> <img src="{{getImage(getFilePath('blog').'/'. $item->data_values->blog_image)}}" alt="latest blog"></a>
                            </div>
                            <div class="latest-blog__content">
                                <h6 class="latest-blog__title">
                                    <a href="{{ route('blog.details', ['slug' => slug($item->data_values->title), 'id' => $item->id])}}">
                                        @if(strlen(__($item->data_values->title)) >50)
                                        {{substr( __($item->data_values->title), 0,50).'...' }}
                                        @else
                                        {{__($item->data_values->title)}}
                                        @endif
                                    </a>
                                </h6>
                                <span class="latest-blog__date">{{showDateTime($item->created_at)}}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <!-- ============================= Blog Details Sidebar End ======================== -->
            </div>
        </div>
    </div>
</section>
<!-- ==================== Blog Details End Here ==================== -->
@endsection

@push('style')
    <style>
        .wyg h1,
        .wyg h2,
        .wyg h3,
        .wyg h4 {
            color: hsl(var(--black));
        }
        .wyg p {
            color: hsl(var(--black));
        }
        .wyg ul {
            margin: 35px;
        }
        .wyg ul li {
            list-style-type: disc;
            color: hsl(var(--black));
            font-family: var(--body-font);
        }
        .wyg ul ol {
            list-style-type: auto;
            color: hsl(var(--black));
            font-family: var(--body-font);
        }
    </style>
@endpush