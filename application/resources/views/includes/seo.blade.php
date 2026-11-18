@if($seo)
    @php
        $siteTitle       = $general->siteName(__($pageTitle));
        $metaTitle       = !empty($seoContents['title']) ? $seoContents['title'] : $siteTitle;
        $metaDescription = !empty($seoContents['description'])
            ? \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags(html_entity_decode($seoContents['description'])))), 160, '')
            : ($seo->description ?? $general->seo_description ?? '');
        $metaKeywords    = !empty($seoContents['keywords'])
            ? (is_array($seoContents['keywords']) ? implode(', ', $seoContents['keywords']) : $seoContents['keywords'])
            : (is_array($seo->keywords ?? null) ? implode(', ', $seo->keywords) : ($seo->keywords ?? ''));
        $metaImage       = !empty($seoContents['image'])
            ? $seoContents['image']
            : getImage(getFilePath('seo') . '/' . $seo->image);
        $metaType        = !empty($seoContents['type']) ? $seoContents['type'] : 'website';
        $rawCanonical    = !empty($seoContents['canonical']) ? $seoContents['canonical'] : url()->current();
        $reqPage         = filter_var(request()->query('page'), FILTER_VALIDATE_INT);
        if ($reqPage && $reqPage > 1 && !str_contains($rawCanonical, 'page=')) {
            $metaCanonical = $rawCanonical . (str_contains($rawCanonical, '?') ? '&' : '?') . 'page=' . $reqPage;
        } else {
            $metaCanonical = $rawCanonical;
        }
        $metaRobots      = !empty($seoContents['robots']) ? $seoContents['robots'] : 'index, follow';
        $socialTitle     = !empty($seoContents['social_title']) ? $seoContents['social_title'] : $metaTitle;
        $socialDesc      = !empty($seoContents['social_description']) ? $seoContents['social_description'] : ($metaDescription ?: ($seo->social_description ?? ''));
        $socialImageSize = explode('x', getFileSize('seo'));
        $imgExt          = pathinfo(parse_url($metaImage, PHP_URL_PATH) ?: '', PATHINFO_EXTENSION) ?: 'png';
    @endphp

    <meta name="title" content="{{ $metaTitle }}">
    <meta name="description" content="{{ $metaDescription }}">
    @if($metaKeywords)
        <meta name="keywords" content="{{ $metaKeywords }}">
    @endif
    <meta name="robots" content="{{ $metaRobots }}">
    <link rel="canonical" href="{{ $metaCanonical }}">
    <link rel="shortcut icon" href="{{ getImage(getFilePath('logoIcon') . '/favicon.png') }}" type="image/x-icon">

    {{-- Apple Stuff --}}
    <link rel="apple-touch-icon" href="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="{{ $metaTitle }}">

    {{-- Google / Search Engine Tags --}}
    <meta itemprop="name" content="{{ $metaTitle }}">
    <meta itemprop="description" content="{{ $metaDescription }}">
    <meta itemprop="image" content="{{ $metaImage }}">

    {{-- Open Graph / Facebook Meta Tags --}}
    <meta property="og:site_name" content="{{ $general->site_name }}">
    <meta property="og:type" content="{{ $metaType }}">
    <meta property="og:title" content="{{ $socialTitle }}">
    <meta property="og:description" content="{{ $socialDesc }}">
    <meta property="og:image" content="{{ $metaImage }}">
    <meta property="og:image:type" content="image/{{ $imgExt }}">
    @if(count($socialImageSize) >= 2 && empty($seoContents['image']))
        <meta property="og:image:width" content="{{ $socialImageSize[0] }}">
        <meta property="og:image:height" content="{{ $socialImageSize[1] }}">
    @endif
    <meta property="og:url" content="{{ $metaCanonical }}">

    {{-- Twitter Meta Tags --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $socialTitle }}">
    <meta name="twitter:description" content="{{ $socialDesc }}">
    <meta name="twitter:image" content="{{ $metaImage }}">

    {{-- Global Organization & WebSite SearchBox Schema (JSON-LD) on Homepage --}}
    @if(request()->is('/'))
        @php
            $homeSchema = [
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        '@type' => 'Organization',
                        'name'  => $general->site_name,
                        'url'   => url('/'),
                        'logo'  => getImage(getFilePath('logoIcon') . '/logo.png'),
                    ],
                    [
                        '@type' => 'WebSite',
                        'name'  => $general->site_name,
                        'url'   => url('/'),
                        'potentialAction' => [
                            '@type'       => 'SearchAction',
                            'target'      => route('single.coupon.search') . '?search={search_term_string}',
                            'query-input' => 'required name=search_term_string',
                        ],
                    ],
                ],
            ];
        @endphp
        <script type="application/ld+json">{!! json_encode($homeSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
    @endif

    {{-- Page-Specific Structured Data (Offer / Article / BreadcrumbList) --}}
    @if(!empty($seoContents['schema']))
        <script type="application/ld+json">{!! json_encode($seoContents['schema'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
    @endif
@endif
