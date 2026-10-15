@php
    $links = getContent('policy_pages.element');
    $socialIconElements = getContent('social_icon.element', false);
    $contactUs = getContent('contact_us.content', true);
    $importantLinks = getContent('footer_important_links.element', false, null, true);
    $companyLinks = getContent('footer_company_links.element', false, null, true);
@endphp
<style>
/* ========================================================
   FOOTER - CLEAN MODERN LIGHT THEME STYLING
   ======================================================== */
footer.footer-area {
    background-color: #f8fafc !important;
    color: #475569 !important;
    position: relative;
    border-top: 1px solid #e2e8f0 !important;
    padding-top: 0 !important;
}

footer.footer-area .footer-top {
    padding-top: 35px !important;
    padding-bottom: 35px !important;
}

/* Logo */
footer.footer-area .footer-item__logo {
    margin-bottom: 18px;
}

footer.footer-area .footer-item__logo a img {
    max-height: 44px;
    width: auto;
    display: inline-block;
    mix-blend-mode: multiply;
}

/* Headings */
footer.footer-area .footer-item__title {
    color: #0f172a !important;
    font-size: 1.12rem;
    font-weight: 700;
    margin-bottom: 20px;
    letter-spacing: -0.2px;
    position: relative;
}

footer.footer-area .footer-item__title::after {
    display: none !important;
}

/* Description / Paragraphs */
footer.footer-area .footer-item__desc {
    color: #64748b !important;
    font-size: 14.5px;
    line-height: 1.7;
    margin-bottom: 22px;
    padding-right: 20px;
}

/* Links */
footer.footer-area .footer-menu {
    list-style: none;
    padding: 0;
    margin: -4px 0;
}

footer.footer-area .footer-menu__item {
    padding: 6px 0;
}

footer.footer-area .footer-menu__link {
    color: #475569 !important;
    font-size: 14.5px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.25s ease;
    display: inline-block;
}

footer.footer-area .footer-menu__link:hover {
    color: #2563eb !important;
    transform: translateX(4px);
}

/* Social icons */
footer.footer-area .social-list {
    display: flex;
    gap: 10px;
    padding: 0;
    margin: 12px 0 0 0;
    list-style: none;
}

footer.footer-area .social-list__item {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.25s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05) !important;
}

footer.footer-area .social-list__item:hover {
    background: #2563eb !important;
    border-color: #2563eb !important;
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25) !important;
}

footer.footer-area .social-list__link,
footer.footer-area .social-list__link i {
    color: #475569 !important;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: color 0.2s ease;
}

footer.footer-area .social-list__item:hover .social-list__link,
footer.footer-area .social-list__item:hover .social-list__link i {
    color: #ffffff !important;
}

/* Newsletter subscribe box */
footer.footer-area .subscribe-box {
    margin-top: 14px;
    width: 100%;
}

footer.footer-area .subscribe-box form {
    position: relative;
    display: flex;
    align-items: center;
    width: 100%;
}

footer.footer-area .subscribe-box input.form--control.footer-input {
    width: 100% !important;
    background: #ffffff !important;
    border: 1px solid #cbd5e1 !important;
    border-radius: 50px !important;
    color: #0f172a !important;
    padding: 12px 55px 12px 20px !important;
    font-size: 14px;
    outline: none;
    transition: all 0.25s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04) !important;
}

footer.footer-area .subscribe-box input.form--control.footer-input::placeholder {
    color: #94a3b8 !important;
    font-size: 14px;
}

footer.footer-area .subscribe-box input.form--control.footer-input:focus {
    background: #ffffff !important;
    border-color: #2563eb !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
    color: #0f172a !important;
}

footer.footer-area .subscribe-box .sub-btn {
    position: absolute;
    right: 5px;
    top: 50%;
    transform: translateY(-50%);
    width: 38px;
    height: 38px;
    border-radius: 50% !important;
    background: #2563eb !important;
    border: none !important;
    color: #ffffff !important;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 !important;
    cursor: pointer;
    transition: all 0.25s ease;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.3) !important;
}

footer.footer-area .subscribe-box .sub-btn:hover {
    background: #1d4ed8 !important;
    transform: translateY(-50%) scale(1.06);
    box-shadow: 0 4px 10px rgba(37, 99, 235, 0.4) !important;
}

footer.footer-area .subscribe-box .sub-btn i {
    color: #ffffff !important;
    font-size: 14px;
}

/* Bottom Footer (Copyright) */
footer.footer-area .bottom-footer {
    background: #f1f5f9 !important;
    border-top: 1px solid #e2e8f0 !important;
    padding: 18px 0 !important;
}

footer.footer-area .bottom-footer-text,
footer.footer-area .bottom-footer-text *,
footer.footer-area .bottom-footer-text p,
footer.footer-area .bottom-footer-text a {
    color: #64748b !important;
    font-size: 13.5px;
    margin: 0;
    text-decoration: none;
    font-weight: 500;
}

footer.footer-area .bottom-footer-text a:hover {
    color: #2563eb !important;
}
</style>

    <footer class="footer-area">
        <div class="footer-top">
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