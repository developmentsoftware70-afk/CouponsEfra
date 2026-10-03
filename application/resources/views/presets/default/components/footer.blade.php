@php
    $links = getContent('policy_pages.element');
    $socialIconElements = getContent('social_icon.element', false);
    $contactUs = getContent('contact_us.content', true);
    $importantLinks = getContent('footer_important_links.element', false, null, true);
    $companyLinks = getContent('footer_company_links.element', false, null, true);
@endphp
    <footer class="footer-area pt-5">
        <div class="footer-top pb-60">
            <div class="container-fluid container-custom">
            
                <div class="row gy-4 justify-content-center">
                    <div class="col-xl-4 col-sm-6">
                        <div class="footer-item">
                            <div class="footer-item__logo wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                                <a href="{{ route('home') }}" class="footer-logo-normal"> 
                                    <img src="{{ getImage(getFilePath('logoIcon') . '/logo.png', '?' . time()) }}" alt="{{ config('app.name') }}">
                                </a>
                            </div>
                            <p class="footer-item__desc wow animate__animated animate__fadeInUp" data-wow-delay="0.3s">{{__($contactUs->data_values->short_description)}}</p>

                            <ul class="social-list wow animate__animated animate__fadeInUp" data-wow-delay="1s">
                                @foreach( $socialIconElements as $item)
                                <li class="social-list__item">
                                    <a href="{{$item->data_values->url}}" class="social-list__link" target="_blank">
                                    @php echo $item->data_values->social_icon; @endphp
                                    </a> 
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div> 
                    <div class="col-xl-2 col-sm-6">
                        <div class="footer-item wow animate__animated animate__fadeInUp">
                            <h5 class="footer-item__title">@lang('Useful Links')</h5>
                            <ul class="footer-menu">
                                @foreach($importantLinks as $link)
                                <li class="footer-menu__item">
                                    <a href="{{url('/').$link->data_values->url}}" class="footer-menu__link">{{$link->data_values->title}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-2 col-sm-6">
                        <div class="footer-item wow animate__animated animate__fadeInUp">
                            <h5 class="footer-item__title">@lang('Links')</h5>
                            <ul class="footer-menu">
                                @foreach($companyLinks as $link)
                                <li class="footer-menu__item"><a href="{{url('/').$link->data_values->url}}" class="footer-menu__link">{{$link->data_values->title}}</a></li>
                                @endforeach
                                @foreach($links as $link)
                                <li class="footer-menu__item"><a href="{{ route('policy.pages', [slug($link->data_values->title), $link->id]) }}"  class="footer-menu__link">{{$link->data_values->title}}</a></li>
                                @endforeach
                                <li class="footer-menu__item"><a href="{{url('/cookie-policy')}}"  class="footer-menu__link">@lang('Cookie Policy')</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-6">
                        <div class="footer-item wow animate__animated animate__fadeInUp">
                            <h5 class="footer-item__title">@lang('Newsletter')</h5>
                            <p class="footer-item__desc">@lang('Stay up-to-date with the latest news, updates, and offers by subscribing to our newsletter.')</p>
                            <div class="subscribe-box">
                                <form  action="{{route('subscribe')}}" method="POST">
                                    @csrf
                                    <input type="text" class="form--control footer-input footer-email" name="email" placeholder="@lang('Enter your mail')...">
                                    <button class="btn btn--base sub-btn" type="submit"><i class="fas fa-arrow-right"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer Top End-->
    
        <!-- bottom Footer -->
        <div class="bottom-footer section-bg py-4">
            <div class="container-fluid container-custom">
                <div class="row text-center gy-2">
                    <div class="col-lg-12">
                        <div class="bottom-footer-text wow animate__animated animate__fadeInUp">
                            @php echo $contactUs->data_values->website_footer;  @endphp
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
  
  <div class="coupon-code-notification"></div>

  <div class="scroll-top">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
      <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 93.9268;"></path>
    </svg>
    <i class="fas fa-arrow-up"></i>
  </div>