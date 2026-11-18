<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Page;
use App\Models\Store;
use App\Models\Coupon;
use App\Models\Category;
use App\Models\Frontend;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\Subscriber;
use App\Models\SupportMessage;
use App\Models\Slider;
use App\Models\AdminNotification;
use App\Models\Deal;
use App\Models\DealCategory;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Cache;
use App\Models\Order;
use App\Models\DailyMetric;
use App\Models\Click;
use App\Lib\ClientInfo;

class SiteController extends Controller
{
    /**
     * Record real-time impressions for rendered items without depending on clicks.
     */
    protected function recordImpressions($items, $type = 'coupon')
    {
        try {
            if (!$items) return;
            if ($items instanceof \Illuminate\Pagination\LengthAwarePaginator || $items instanceof \Illuminate\Support\Collection) {
                $ids = $items->pluck('id')->filter()->all();
            } elseif (is_array($items)) {
                $ids = array_filter($items);
            } elseif (is_numeric($items)) {
                $ids = [(int)$items];
            } else {
                $ids = [];
            }

            if (!empty($ids)) {
                if ($type === 'coupon') {
                    Coupon::whereIn('id', $ids)->increment('impression_count');
                } elseif ($type === 'deal') {
                    Deal::whereIn('id', $ids)->increment('impression_count');
                }
                DailyMetric::incrementImpressions(count($ids));
            }
        } catch (\Throwable $e) {
            // Failsafe: never break page rendering
        }
    }

    public function index(){
        $reference = @$_GET['reference'];
        if ($reference) {
            session()->put('reference', $reference);
        }
        if (empty($reference)) {
            session()->forget('reference');
        }

        $pageTitle = 'Home';
        $activeTemplate = $this->activeTemplate;
        $sections = Cache::remember("page_home_sections_{$activeTemplate}", 3600, function () use ($activeTemplate) {
            return Page::where('tempname', $activeTemplate)->where('slug', '/')->first();
        });
        $categories = Cache::remember('home_categories_top4', 1800, function () {
            return Category::where('status', 1)->latest()->limit(4)->get();
        });
        $slider = Cache::remember('home_sliders_active', 1800, function () {
            return Slider::where('status', '1')->get();
        });

        // Record real-time impressions for active offers on home page
        $homeCoupons = Coupon::where('status', 1)->latest()->take(12)->pluck('id')->all();
        $homeDeals   = Deal::where('status', 1)->latest()->take(12)->pluck('id')->all();
        $this->recordImpressions($homeCoupons, 'coupon');
        $this->recordImpressions($homeDeals, 'deal');

        return view($this->activeTemplate . 'home', compact('pageTitle','sections','categories','slider'));
    }

    public function pages($slug)
    {
        $page = Page::where('tempname',$this->activeTemplate)->where('slug',$slug)->firstOrFail();
        $pageTitle = $page->name;
        $sections = $page->secs;
        return view($this->activeTemplate . 'pages', compact('pageTitle','sections'));
    }


    public function couponDetails($slug)
    {
        $coupon = Coupon::with(['store', 'category'])
            ->where(function($q) use ($slug) {
                $q->where('slug', $slug);
                if (is_numeric($slug)) {
                    $q->orWhere('id', $slug);
                }
            })
            ->firstOrFail();

        $coupon->increment('impression_count');

        // 301 Canonical redirect if accessed via old numeric ID and slug exists
        if (is_numeric($slug) && !empty($coupon->slug) && $slug != $coupon->slug) {
            return redirect()->route('coupon.details', $coupon->slug, 301);
        }

        $pageTitle = $coupon->title;

        // Fetch related coupons strictly from the SAME category (e.g. Shoes / Footwear)
        // Shows offers from other brands of the same category, prioritizing same store first if any
        $relatedQuery = Coupon::with(['store', 'category'])
            ->where('status', 1)
            ->where('id', '!=', $coupon->id);

        if ($coupon->category_id) {
            $relatedQuery->where('category_id', $coupon->category_id);
        } else {
            $relatedQuery->where('store_id', $coupon->store_id);
        }

        $relatedCoupons = $relatedQuery
            ->orderByRaw('CASE WHEN store_id = ? THEN 0 ELSE 1 END', [$coupon->store_id])
            ->latest()
            ->take(6)
            ->get();

        $general = gs();
        $storeName = $coupon->store ? $coupon->store->name : $general->site_name;
        $couponUrl = route('coupon.details', $coupon->slug ?? $coupon->id);
        $couponImg = $coupon->image
            ? getImage(getFilePath('coupon') . '/' . $coupon->image)
            : ($coupon->store && $coupon->store->image ? getImage(getFilePath('store') . '/' . $coupon->store->image) : null);
        $cleanDesc = trim(preg_replace('/\s+/', ' ', strip_tags(html_entity_decode((string) $coupon->description))));
        $metaDesc  = $cleanDesc ?: "Grab {$coupon->title} from {$storeName}. Verified coupon codes, discount offers & cashback on {$general->site_name}.";

        $seoContents = [
            'title'       => "{$coupon->title} | {$storeName} - {$general->site_name}",
            'description' => $metaDesc,
            'keywords'    => [$storeName . ' coupon', $storeName . ' promo code', $coupon->title, 'cashback offers'],
            'image'       => $couponImg,
            'canonical'   => $couponUrl,
            'schema'      => [
                '@context' => 'https://schema.org',
                '@graph'   => [
                    [
                        '@type'         => 'Offer',
                        'name'          => $coupon->title,
                        'description'   => \Illuminate\Support\Str::limit($metaDesc, 200, ''),
                        'url'           => $couponUrl,
                        'image'         => $couponImg,
                        'availability'  => 'https://schema.org/InStock',
                        'validThrough'  => $coupon->expire_date ? \Carbon\Carbon::parse($coupon->expire_date)->toIso8601String() : null,
                        'seller'        => ['@type' => 'Organization', 'name' => $storeName],
                    ],
                    [
                        '@type'           => 'BreadcrumbList',
                        'itemListElement' => [
                            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Coupons', 'item' => route('coupon')],
                            ['@type' => 'ListItem', 'position' => 3, 'name' => $coupon->title, 'item' => $couponUrl],
                        ],
                    ],
                ],
            ],
        ];

        return view($this->activeTemplate . 'coupon_details', compact('pageTitle', 'coupon', 'relatedCoupons', 'seoContents'));
    }

    public function dealDetails($id)
    {
        $deal = Deal::with('deal_category')->findOrFail($id);
        $deal->increment('impression_count');
        $pageTitle = $deal->title;

        // Find matching store & coupon if available by brand/category name
        $categoryTitle = @$deal->deal_category->title;
        $store = null;
        $coupon = null;
        if ($categoryTitle) {
            $store = Store::where('name', 'like', '%' . trim($categoryTitle) . '%')->first();
            if ($store) {
                $coupon = Coupon::where('store_id', $store->id)->where('status', 1)->latest()->first();
            }
        }
        if (!$coupon && $categoryTitle) {
            $coupon = Coupon::where('title', 'like', '%' . trim($categoryTitle) . '%')->where('status', 1)->latest()->first();
        }

        // Fetch related deals from the same category or brand
        $relatedDeals = Deal::with('deal_category')
            ->where('status', 1)
            ->where('id', '!=', $id)
            ->where('deal_category_id', $deal->deal_category_id)
            ->latest()
            ->take(6)
            ->get();

        if ($relatedDeals->count() < 3) {
            $moreDeals = Deal::with('deal_category')
                ->where('status', 1)
                ->where('id', '!=', $id)
                ->whereNotIn('id', $relatedDeals->pluck('id'))
                ->latest()
                ->take(6 - $relatedDeals->count())
                ->get();
            $relatedDeals = $relatedDeals->concat($moreDeals);
        }

        $general  = gs();
        $dealUrl  = route('deal.details', $deal->id);
        $dealImg  = $deal->image ? getImage(getFilePath('deal') . '/' . $deal->image) : null;
        $dealDesc = trim(preg_replace('/\s+/', ' ', strip_tags(html_entity_decode((string) ($deal->description ?? '')))))
            ?: "Shop {$deal->title} with exclusive cashback and discount offers on {$general->site_name}.";

        $seoContents = [
            'title'       => "{$deal->title} | Best Deal & Cashback - {$general->site_name}",
            'description' => $dealDesc,
            'keywords'    => [$deal->title, $categoryTitle ?: 'deals', 'discount deal', 'cashback'],
            'image'       => $dealImg,
            'canonical'   => $dealUrl,
            'schema'      => [
                '@context' => 'https://schema.org',
                '@graph'   => [
                    [
                        '@type'       => 'Offer',
                        'name'        => $deal->title,
                        'description' => \Illuminate\Support\Str::limit($dealDesc, 200, ''),
                        'url'         => $dealUrl,
                        'image'       => $dealImg,
                        'availability'=> 'https://schema.org/InStock',
                    ],
                    [
                        '@type'           => 'BreadcrumbList',
                        'itemListElement' => [
                            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Deals', 'item' => route('deal')],
                            ['@type' => 'ListItem', 'position' => 3, 'name' => $deal->title, 'item' => $dealUrl],
                        ],
                    ],
                ],
            ],
        ];

        return view($this->activeTemplate . 'deal_details', compact('pageTitle', 'deal', 'store', 'coupon', 'relatedDeals', 'seoContents'));
    }

    public function goStore($id)
    {
        if (!auth()->check()) {
            session()->put('url.intended', url()->current());
            return redirect()->route('user.login');
        }

        $coupon = Coupon::with('store')->findOrFail($id);
        $coupon->increment('click_count');
        DailyMetric::incrementClicks(1);
        
        $clickId = 'clk_' . time() . '_' . strtolower(\Illuminate\Support\Str::random(10));
        $ip = getRealIP();
        $userAgent = request()->userAgent() ?? 'Unknown';
        $osBrowser = ClientInfo::osBrowser();
        $os = $osBrowser['os_platform'] ?? 'Unknown';
        $browser = $osBrowser['browser'] ?? 'Unknown';
        $deviceType = preg_match('/(android|iphone|ipad|mobile|tablet)/i', $userAgent) ? 'mobile' : 'desktop';
        $campaign = request('utm_campaign', request('campaign', 'coupon_campaign'));

        // Create initial tracking order for coupon store visit
        $productImage = $coupon->image ?? @$coupon->store->image ?? null;

        $estAmount = (float)($coupon->price ?: 349.00);
        $estPayout = 0;
        if (!empty($coupon->cashback_amount)) {
            $cb = (float) $coupon->cashback_amount;
            $estPayout = ($cb > 0 && $cb <= 100) ? round(($estAmount * $cb) / 100, 2) : $cb;
        } elseif (preg_match('/Flat\s*₹?\s*(\d+)\s*Cashback/i', $coupon->title, $m)) {
            $estPayout = (float) $m[1];
        } elseif (preg_match('/Extra\s*(\d+(?:\.\d+)?)\s*%\s*Cashback/i', $coupon->title, $m)) {
            $estPayout = round(($estAmount * (float)$m[1]) / 100, 2);
        } else {
            $estPayout = round($estAmount * 0.10, 2);
        }
        if ($estPayout <= 0) $estPayout = 50.00;

        $tempOrder = Order::create([
            'click_id'      => $clickId,
            'user_id'       => auth()->id(),
            'merchant_id'   => $coupon->store_id ?? null,
            'product_name'  => $coupon->title,
            'product_image' => $productImage,
            'amount'        => $estAmount,
            'payout_amount' => $estPayout,
            'currency'      => 'INR',
            'status'        => 'pending',
            'source'        => 'coupon_section',
        ]);

        $options = [
            'click_id'     => $clickId,
            'merchant'     => @$coupon->store->name ?? 'store',
            'campaign'     => $campaign,
            'title'        => $coupon->title,
            'utm_source'   => request('utm_source', auth()->user()->username),
            'utm_medium'   => request('utm_medium', 'affiliate'),
            'utm_term'     => request('utm_term', $coupon->slug ?? $coupon->title),
        ];

        $link = appendTrackingSource($coupon->link, auth()->user(), $tempOrder->id, $options);

        // Store granular click data
        Click::create([
            'click_id'        => $clickId,
            'user_id'         => auth()->id(),
            'coupon_id'       => $coupon->id,
            'store_id'        => $coupon->store_id ?? null,
            'order_id'        => $tempOrder->id,
            'campaign'        => $campaign,
            'utm_source'      => $options['utm_source'],
            'utm_medium'      => $options['utm_medium'],
            'utm_campaign'    => $campaign,
            'utm_term'        => $options['utm_term'],
            'ip_address'      => $ip,
            'device_type'     => $deviceType,
            'browser'         => $browser,
            'os'              => $os,
            'user_agent'      => $userAgent,
            'destination_url' => $coupon->link,
            'final_url'       => $link,
        ]);

        // Render an intermediate tracking page instead of instant redirect
        $pageTitle = "Activating Cashback...";
        return view($this->activeTemplate . 'redirect_page', compact('pageTitle', 'link', 'coupon'));
    }

    public function goDeal($id)
    {
        if (!auth()->check()) {
            session()->put('url.intended', url()->current());
            return redirect()->route('user.login');
        }

        $deal = Deal::findOrFail($id);
        $deal->increment('click_count');
        DailyMetric::incrementClicks(1);

        $clickId = 'clk_' . time() . '_' . strtolower(\Illuminate\Support\Str::random(10));
        $ip = getRealIP();
        $userAgent = request()->userAgent() ?? 'Unknown';
        $osBrowser = ClientInfo::osBrowser();
        $os = $osBrowser['os_platform'] ?? 'Unknown';
        $browser = $osBrowser['browser'] ?? 'Unknown';
        $deviceType = preg_match('/(android|iphone|ipad|mobile|tablet)/i', $userAgent) ? 'mobile' : 'desktop';
        $campaign = request('utm_campaign', request('campaign', 'deal_campaign'));

        // Create initial tracking order for deal visit
        $productImage = $deal->image ?? null;

        $estAmount = (float)($deal->price ?: 349.00);
        $estPayout = round($estAmount * 0.10, 2);
        if ($estPayout <= 0) $estPayout = 50.00;

        $tempOrder = Order::create([
            'click_id'      => $clickId,
            'user_id'       => auth()->id(),
            'merchant_id'   => $deal->store_id ?? null,
            'product_name'  => $deal->title,
            'product_image' => $productImage,
            'amount'        => $estAmount,
            'payout_amount' => $estPayout,
            'currency'      => 'INR',
            'status'        => 'pending',
            'source'        => 'deal_section',
        ]);

        $options = [
            'click_id'     => $clickId,
            'merchant'     => 'deal_store',
            'campaign'     => $campaign,
            'title'        => $deal->title,
            'utm_source'   => request('utm_source', auth()->user()->username),
            'utm_medium'   => request('utm_medium', 'affiliate'),
            'utm_term'     => request('utm_term', $deal->title),
        ];

        $link = appendTrackingSource($deal->link, auth()->user(), $tempOrder->id, $options);

        Click::create([
            'click_id'        => $clickId,
            'user_id'         => auth()->id(),
            'deal_id'         => $deal->id,
            'store_id'        => $deal->store_id ?? null,
            'order_id'        => $tempOrder->id,
            'campaign'        => $campaign,
            'utm_source'      => $options['utm_source'],
            'utm_medium'      => $options['utm_medium'],
            'utm_campaign'    => $campaign,
            'utm_term'        => $options['utm_term'],
            'ip_address'      => $ip,
            'device_type'     => $deviceType,
            'browser'         => $browser,
            'os'              => $os,
            'user_agent'      => $userAgent,
            'destination_url' => $deal->link,
            'final_url'       => $link,
        ]);

        $pageTitle = "Activating Cashback...";
        return view($this->activeTemplate . 'redirect_page', compact('pageTitle', 'link', 'deal'));
    }

    public function goSlider($id)
    {
        if (!auth()->check()) {
            session()->put('url.intended', url()->current());
            return redirect()->route('user.login');
        }

        $slider = \App\Models\Slider::findOrFail($id);
        DailyMetric::incrementClicks(1);

        $clickId = 'clk_' . time() . '_' . strtolower(\Illuminate\Support\Str::random(10));
        $ip = getRealIP();
        $userAgent = request()->userAgent() ?? 'Unknown';
        $osBrowser = ClientInfo::osBrowser();
        $os = $osBrowser['os_platform'] ?? 'Unknown';
        $browser = $osBrowser['browser'] ?? 'Unknown';
        $deviceType = preg_match('/(android|iphone|ipad|mobile|tablet)/i', $userAgent) ? 'mobile' : 'desktop';
        $campaign = request('utm_campaign', request('campaign', 'slider_campaign'));

        // Create initial tracking order for slider visit
        $productImage = $slider->image ?? null;

        $tempOrder = Order::create([
            'click_id'      => $clickId,
            'user_id'       => auth()->id(),
            'merchant_id'   => $slider->store_id ?? null,
            'product_name'  => $slider->title ?? 'Featured Offer',
            'product_image' => $productImage,
            'amount'        => 500.00,
            'payout_amount' => 50.00,
            'currency'      => 'INR',
            'status'        => 'pending',
            'source'        => 'slider_section',
        ]);

        $options = [
            'click_id'     => $clickId,
            'merchant'     => 'featured_store',
            'campaign'     => $campaign,
            'title'        => $slider->title ?? 'Featured Offer',
            'utm_source'   => request('utm_source', auth()->user()->username),
            'utm_medium'   => request('utm_medium', 'affiliate'),
            'utm_term'     => request('utm_term', $slider->title ?? 'offer'),
        ];

        $link = appendTrackingSource($slider->link, auth()->user(), $tempOrder->id, $options);

        Click::create([
            'click_id'        => $clickId,
            'user_id'         => auth()->id(),
            'store_id'        => $slider->store_id ?? null,
            'order_id'        => $tempOrder->id,
            'campaign'        => $campaign,
            'utm_source'      => $options['utm_source'],
            'utm_medium'      => $options['utm_medium'],
            'utm_campaign'    => $campaign,
            'utm_term'        => $options['utm_term'],
            'ip_address'      => $ip,
            'device_type'     => $deviceType,
            'browser'         => $browser,
            'os'              => $os,
            'user_agent'      => $userAgent,
            'destination_url' => $slider->link,
            'final_url'       => $link,
        ]);

        $pageTitle = "Activating Cashback...";
        return view($this->activeTemplate . 'redirect_page', compact('pageTitle', 'link', 'slider'));
    }

    public function contact()
    {
        $pageTitle = "Contact Us";
        return view($this->activeTemplate . 'contact',compact('pageTitle'));
    }


    public function contactSubmit(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required|string|max:255',
            'message' => 'required',
        ]);

        if(!verifyCaptcha()){
            $notify[] = ['error','Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        $request->session()->regenerateToken();

        $random = getNumber();

        $ticket = new SupportTicket();
        $ticket->user_id = auth()->id() ?? 0;
        $ticket->name = $request->name;
        $ticket->email = $request->email;
        $ticket->priority = 2;


        $ticket->ticket = $random;
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = 0;
        $ticket->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->user() ? auth()->user()->id : 0;
        $adminNotification->title = 'A new support ticket has opened ';
        $adminNotification->click_url = urlPath('admin.ticket.view',$ticket->id);
        $adminNotification->save();

        $message = new SupportMessage();
        $message->support_ticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();

        $notify[] = ['success', 'Ticket created successfully!'];

        return to_route('ticket.view', [$ticket->ticket])->withNotify($notify);
    }

    public function policyPages($slug,$id)
    {
        $policy = Frontend::where('id',$id)->where('data_keys','policy_pages.element')->firstOrFail();
        $pageTitle = $policy->data_values->title;
        return view($this->activeTemplate.'policy',compact('policy','pageTitle'));
    }

    public function changeLanguage($lang = null)
    {
        $language = Language::where('code', $lang)->first();
        if (!$language) $lang = 'en';
        session()->put('lang', $lang);
        return back();
    }

    public function blog(){
        $pageTitle = 'Blog';
        $sections = Page::where('tempname',$this->activeTemplate)->where('slug','blog')->firstOrFail();
        $blogs = Frontend::where('data_keys','blog.element')->orderBy('id','desc')->paginate(getPaginate());

        return view($this->activeTemplate.'blog',compact('sections','blogs','pageTitle'));
    }

    public function blogDetails($slug,$id){
        $blog = Frontend::where('id',$id)->where('data_keys','blog.element')->firstOrFail();
        $blogTitle = @$blog->data_values->title ?: 'Blog Details';
        $pageTitle = $blogTitle;
        $latests = Frontend::where('data_keys','blog.element')->orderBy('id','desc')->limit(5)->get();

        $general  = gs();
        $blogUrl  = route('blog.details', [slug($blogTitle), $blog->id]);
        $blogImg  = !empty($blog->data_values->blog_image) ? getImage('assets/images/frontend/blog/' . $blog->data_values->blog_image) : null;
        $blogDesc = trim(preg_replace('/\s+/', ' ', strip_tags(html_entity_decode((string) (@$blog->data_values->description ?? '')))));

        $seoContents = [
            'title'       => "{$blogTitle} - {$general->site_name}",
            'description' => $blogDesc ?: "{$blogTitle} - Read cashback tips, shopping guides and latest offers on {$general->site_name}.",
            'type'        => 'article',
            'image'       => $blogImg,
            'canonical'   => $blogUrl,
            'schema'      => [
                '@context' => 'https://schema.org',
                '@graph'   => [
                    [
                        '@type'         => 'Article',
                        'headline'      => $blogTitle,
                        'description'   => \Illuminate\Support\Str::limit($blogDesc, 200, ''),
                        'image'         => $blogImg,
                        'url'           => $blogUrl,
                        'datePublished' => $blog->created_at ? $blog->created_at->toIso8601String() : null,
                        'dateModified'  => $blog->updated_at ? $blog->updated_at->toIso8601String() : null,
                        'publisher'     => [
                            '@type' => 'Organization',
                            'name'  => $general->site_name,
                            'logo'  => ['@type' => 'ImageObject', 'url' => getImage(getFilePath('logoIcon') . '/logo.png')],
                        ],
                    ],
                    [
                        '@type'           => 'BreadcrumbList',
                        'itemListElement' => [
                            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => route('blog')],
                            ['@type' => 'ListItem', 'position' => 3, 'name' => $blogTitle, 'item' => $blogUrl],
                        ],
                    ],
                ],
            ],
        ];

        return view($this->activeTemplate.'blog_details',compact('blog','pageTitle','latests','seoContents'));
    }

    public function cookieAccept(){
        $general = gs();
        Cookie::queue('gdpr_cookie',$general->site_name , 43200);
        return back();
    }

    public function cookiePolicy(){
        $pageTitle = 'Cookie Policy';
        $cookie = Frontend::where('data_keys','cookie.data')->first();
        return view($this->activeTemplate.'cookie',compact('pageTitle','cookie'));
    }

    public function placeholderImage($size = null){
        $imgWidth = explode('x', $size)[0] ?? 300;
        $imgHeight = explode('x', $size)[1] ?? 300;
        $text = $imgWidth . '×' . $imgHeight;

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$imgWidth.'" height="'.$imgHeight.'">
                    <rect width="100%" height="100%" fill="#1c232f"/>
                    <text x="50%" y="50%" font-family="monospace" font-size="20" fill="#ffffff" dominant-baseline="middle" text-anchor="middle">'.$text.'</text>
                </svg>';

        return response($svg, 200)->header('Content-Type', 'image/svg+xml');
    }
    /*
    public function getModalInfo(Request $request){
        $couponId =  $request->couponId;
        $coupon = Coupon::with('store')->where('id',$couponId)->first();
        $couponImage = getFilePath('coupon') . '/' . $coupon->image;
        $storeImage = getFilePath('store') . '/' . $coupon->store->image;
        $storeName = $coupon->store->name;
        $couponName = $coupon->title;

        return response()->json([
            'storeImage' => $storeImage,
            'storeName' => $storeName,
            'couponImage' => $couponImage,
            'couponName' => $couponName,
        ], 200);
       
    }*/
    public function getModalInfo(Request $request)
    {
        $couponId = $request->couponId;

        $coupon = Coupon::with('store')->find($couponId);

        // Check if coupon exists
        if (!$coupon) {
            return response()->json(['error' => 'Coupon not found'], 404);
        }

        $couponImage = $coupon->image ? asset(getFilePath('coupon') . '/' . $coupon->image) : null;
        $storeImage = $coupon->store && $coupon->store->image ? asset(getFilePath('store') . '/' . $coupon->store->image) : null;
        $storeName = $coupon->store->name ?? null;
        $couponName = $coupon->title ?? null;

        return response()->json([
            'storeImage' => $storeImage,
            'storeName' => $storeName,
            'couponImage' => $couponImage,
            'couponName' => $couponName,
        ], 200);
    }

    // for deal modal
    /* 
    public function getModalDealInfo(Request $request){
        $dealId =  $request->dealId;
        $deal = Deal::with('deal_category')->find($dealId);
        // Check if deal exists
        if (!$deal) {
            return response()->json(['error' => 'Deal not found'], 404);
        }

        $dealImage = $deal->image ? asset(getFilePath('deal') . '/' . $deal->image) : null;
        $dealCatImage = $deal->deal_category && $deal->deal_category->image ? asset(getFilePath('dealCategory') . '/' . $deal->deal_category->image) : null;
        $dealCatName = $deal->deal_category->title ?? null;
        $dealName = $deal->title ?? null;

        return response()->json([
            'dealImage' => $dealImage,
            'dealCatImage' => $dealCatImage,
            'dealCatName' => $dealCatName,
            'dealName' => $dealName,
        ], 200);
    }*/
    public function getModalDealInfo(Request $request)
    {
        $request->validate([
            'dealId' => 'required|integer|exists:deals,id',
        ]);

        $deal = Deal::with('deal_category')->find($request->dealId);

        $dealImage = $deal->image ? asset(getFilePath('deal') . '/' . $deal->image) : null;
        $dealCatImage = $deal->deal_category?->image ? asset(getFilePath('dealCategory') . '/' . $deal->deal_category->image) : null;
        $dealCatName = $deal->deal_category?->title;
        $dealName = $deal->title;

        return response()->json([
            'dealImage' => $dealImage,
            'dealCatImage' => $dealCatImage,
            'dealCatName' => $dealCatName,
            'dealName' => $dealName,
        ]);
    }

    // Exclusive Coupon
    public function exclusiveCoupon()
    {
        $pageTitle = "Exclusive Coupon";
        $exclusiveCoupons = Coupon::with(['category', 'store', 'wishlists'])->where('status', 1)->where('is_exclusive', 1)->latest()->paginate(getPaginate());
        $this->recordImpressions($exclusiveCoupons, 'coupon');
        $categories = Category::where('status', 1)->whereHas('coupons', function($q) {
            $q->where('status', 1)->where('is_exclusive', 1);
        })->latest()->get();
        $stores = Store::where('status', 1)->whereHas('coupons', function($q) {
            $q->where('status', 1)->where('is_exclusive', 1);
        })->latest()->get();
        return view($this->activeTemplate . 'exclusive_coupons',compact('pageTitle', 'exclusiveCoupons', 'categories', 'stores'));
    }
    // coupons
    public function coupons() {
        $pageTitle = "Coupons";
        $coupons = Coupon::with(['category', 'store','wishlists'])->where('status', 1)->latest()->paginate(getPaginate());
        $this->recordImpressions($coupons, 'coupon');
        $categories = Category::where('status', 1)->whereHas('coupons', function($q) {
            $q->where('status', 1);
        })->latest()->get();
        $stores = Store::where('status', 1)->whereHas('coupons', function($q) {
            $q->where('status', 1);
        })->latest()->get();
        $general = gs();
        $baseCouponUrl = route('coupon');
        $canonicalCouponUrl = $coupons->currentPage() > 1 ? $baseCouponUrl . '?page=' . $coupons->currentPage() : $baseCouponUrl;
        $seoContents = [
            'title'       => "Latest Verified Coupons, Promo Codes & Cashback Offers - {$general->site_name}",
            'description' => "Browse all verified coupons, promo codes, discount deals and cashback offers across top Indian brands on {$general->site_name}.",
            'keywords'    => ['coupons', 'promo codes', 'cashback offers', 'discount codes india'],
            'canonical'   => $canonicalCouponUrl,
            'schema'      => [
                '@context'        => 'https://schema.org',
                '@type'           => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Coupons', 'item' => route('coupon')],
                ],
            ],
        ];

        return  view($this->activeTemplate . 'coupons',compact('pageTitle', 'coupons', 'categories', 'stores', 'seoContents'));
    }
    // categories
    public function categories() {
        $pageTitle = "categories";
        $categories = Category::with('coupons')->where('status', 1)->latest()->paginate(getPaginate());
        $stores = Store::where('status', 1)->latest()->paginate(getPaginate(6));
        return  view($this->activeTemplate . 'categories',compact('pageTitle', 'categories'));
    }

    //feature coupon 
    public function featureCoupon()
    {
        $pageTitle = "Featured Coupon";
        $featureCoupons = Coupon::with(['category', 'store','wishlists'])->where('status', 1)->where('is_featured', 1)->latest()->paginate(getPaginate());
        $this->recordImpressions($featureCoupons, 'coupon');
        $categories = Category::where('status', 1)->whereHas('coupons', function($q) {
            $q->where('status', 1)->where('is_featured', 1);
        })->latest()->get();
        $stores = Store::where('status', 1)->whereHas('coupons', function($q) {
            $q->where('status', 1)->where('is_featured', 1);
        })->latest()->get();
      
        return view($this->activeTemplate . 'feature_coupons',compact('pageTitle', 'featureCoupons', 'categories', 'stores'));
    }
    //latest coupon 
    public function latestCoupon()
    {
        $pageTitle = "Latest Coupon";
        $featureCoupons = Coupon::with(['category', 'store','wishlists'])->where('status', 1)->latest()->paginate(getPaginate());
        $this->recordImpressions($featureCoupons, 'coupon');
        $categories = Category::where('status', 1)->whereHas('coupons', function($q) {
            $q->where('status', 1);
        })->latest()->get();
        $stores = Store::where('status', 1)->whereHas('coupons', function($q) {
            $q->where('status', 1);
        })->latest()->get();
      
        return view($this->activeTemplate . 'feature_coupons',compact('pageTitle', 'featureCoupons', 'categories', 'stores'));
    }
    //popular coupon 
    public function popularCoupon()
    {
        $pageTitle = "Popular Coupon";
        $popularCoupons = Coupon::with(['category', 'store','wishlists'])->where('status', 1)->where('is_popular', 1)->latest()->paginate(getPaginate());
        $this->recordImpressions($popularCoupons, 'coupon');
        $categories = Category::where('status', 1)->whereHas('coupons', function($q) {
            $q->where('status', 1)->where('is_popular', 1);
        })->latest()->get();
        $stores = Store::where('status', 1)->whereHas('coupons', function($q) {
            $q->where('status', 1)->where('is_popular', 1);
        })->latest()->get();
      
        return view($this->activeTemplate . 'popular_coupons',compact('pageTitle', 'popularCoupons', 'categories', 'stores'));
    }
    //sale coupon 
    public function saleCoupon()
    {
        $pageTitle = "Sale Coupon";
        $saleCoupons = Coupon::with(['category', 'store','wishlists'])->where('status', 1)->where('is_sale', 1)->latest()->paginate(getPaginate());
        $this->recordImpressions($saleCoupons, 'coupon');
        $categories = Category::where('status', 1)->whereHas('coupons', function($q) {
            $q->where('status', 1)->where('is_sale', 1);
        })->latest()->get();
        $stores = Store::where('status', 1)->whereHas('coupons', function($q) {
            $q->where('status', 1)->where('is_sale', 1);
        })->latest()->get();
      
        return view($this->activeTemplate . 'sale_coupons',compact('pageTitle', 'saleCoupons', 'categories', 'stores'));
    }
    //cashback coupon 
    public function cashbackCoupon()
    {
        $pageTitle = "Cashback Coupon";
        $cashbackCoupons = Coupon::with(['category', 'store','wishlists'])->where('status', 1)->where('is_cashback', 1)->latest()->paginate(getPaginate());
        $this->recordImpressions($cashbackCoupons, 'coupon');
        $categories = Category::where('status', 1)->whereHas('coupons', function($q) {
            $q->where('status', 1)->where('is_cashback', 1);
        })->latest()->get();
        $stores = Store::where('status', 1)->whereHas('coupons', function($q) {
            $q->where('status', 1)->where('is_cashback', 1);
        })->latest()->get();
      
        return view($this->activeTemplate . 'cashback_coupons',compact('pageTitle', 'cashbackCoupons', 'categories', 'stores'));
    }
    //affiliate
    public function affiliate()
    {
        $pageTitle = "Affiliates";
        $coupons = Coupon::with(['category', 'store','wishlists'])->where('status', 1)->where('is_cashback', 0)->latest()->paginate(getPaginate(8), ['*'], 'coupon_page');
        $cashbackCoupons = Coupon::with(['category', 'store','wishlists'])->where('status', 1)->where('is_cashback', 1)->latest()->paginate(getPaginate(8), ['*'], 'cashback_page');
        $categories = Category::where('status', 1)->latest()->get();
        $stores = Store::where('status', 1)->latest()->get();
        $deals = Deal::with(['deal_category'])->where('status', 1)->where('is_affiliate', 1)->latest()->paginate(getPaginate(8), ['*'], 'deal_page');
        $dealCategories = DealCategory::where('status', 1)->latest()->get();
        return view($this->activeTemplate . 'affiliate',compact('pageTitle', 'cashbackCoupons', 'coupons', 'categories', 'stores', 'deals', 'dealCategories'));
    }
     //earning
    public function earning()
    {
        $pageTitle = "Earnings";
        $earningCoupons = Coupon::with(['category', 'store','wishlists'])->where('status', 1)->where('is_earning', 1)->latest()->paginate(getPaginate(8));
        $categories = Category::where('status', 1)->latest()->get();
        $stores = Store::where('status', 1)->latest()->get();
        
        $earningDeals = Deal::with(['deal_category'])->where('status', 1)->where('is_earning', 1)->latest()->paginate(getPaginate(8));
        $dealCategories = DealCategory::where('status', 1)->latest()->get();
        return view($this->activeTemplate . 'earning',compact('pageTitle', 'earningCoupons', 'categories', 'stores', 'earningDeals', 'dealCategories'));
    }
    //feature store 
    public function brands()
    {
        $pageTitle = "Most Popular Brands";
        $rawOrder = "CASE LOWER(TRIM(name))
            WHEN 'amazon' THEN 1000
            WHEN 'flipkart' THEN 950
            WHEN 'hyphen' THEN 900
            WHEN 'bacca bucci' THEN 850
            WHEN 'baccabucci' THEN 850
            WHEN 'myntra' THEN 800
            WHEN 'nykaa' THEN 750
            WHEN 'firstcry' THEN 700
            WHEN 'neemans' THEN 650
            WHEN 'dot & key' THEN 600
            WHEN 'dot and key' THEN 600
            WHEN 'wakefit' THEN 550
            WHEN 'cashify' THEN 500
            WHEN 'healthkart' THEN 450
            WHEN 'mcaffeine' THEN 400
            WHEN 'wow skin' THEN 350
            WHEN 'noice' THEN 300
            WHEN 'noise' THEN 300
            WHEN 'nilkamal furniture' THEN 250
            WHEN 'nilkamal' THEN 250
            WHEN 'the man company' THEN 200
            WHEN 'daily object' THEN 150
            WHEN 'daily objects' THEN 150
            ELSE 10 END DESC, id DESC";
        $stores = Store::with('coupons')->where('status', 1)->orderByDesc('priority_score')->orderByRaw($rawOrder)->paginate(24);
        return view($this->activeTemplate . 'brands', compact('pageTitle', 'stores'));
    }

    public function featureStore()
    {
        $pageTitle = "Top Stores";
        //$featureStore = Store::active()->latest()->paginate(getPaginate(8));
        $featureStore = Store::with('coupons')->latest()->paginate(getPaginate(20));
        return view($this->activeTemplate . 'feature_store',compact('pageTitle', 'featureStore'));
    }

    // exclusive coupon filter
    public function exclusiveCouponFilter(Request $request) {

        $categories = $request->input('categories', []);
        $stores = $request->input('stores', []);
        $search = $request->input('search');


        $query = Coupon::with(['category', 'store','wishlists'])->where('status', 1)->where('is_exclusive', 1);

        if (!empty($categories)) {
            $query->whereIn('category_id', $categories);
        }

        if (!empty($stores)) {
            $query->whereIn('store_id', $stores);
        }
        if (!empty($search)) {
            $lowerSearch = strtolower($search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%$search%")
                  ->orWhereHas('store', function ($sq) use ($search) {
                      $sq->where('name', 'LIKE', "%$search%");
                  })
                  ->orWhereHas('category', function ($cq) use ($search) {
                      $cq->where('name', 'LIKE', "%$search%");
                  });
            })->orderByRaw("CASE 
                WHEN LOWER(title) = ? THEN 1 
                WHEN LOWER(title) LIKE ? THEN 2 
                ELSE 3 
            END", [$lowerSearch, $lowerSearch . '%']);
        }

        $exclusiveCoupons = $query->get();
        $view = View::make($this->activeTemplate.'couponFilter.exclusive_coupon_search', compact('exclusiveCoupons', 'categories', 'stores'))->render();

        return response()->json([
            'html' => $view
        ]);
    }
    //popular deal 
    public function popularDeal()
    {
        $pageTitle = "Popular Deals";
        $deals = Deal::with(['deal_category'])->where('status', 1)->latest()->paginate(getPaginate());
        $this->recordImpressions($deals, 'deal');
        $dealCategories = DealCategory::where('status', 1)->latest()->get();
      
        return view($this->activeTemplate . 'deals', compact('pageTitle', 'deals', 'dealCategories'));
    }
    // deals
    public function deals() {
        $pageTitle = "Deals";
        $deals = Deal::with(['deal_category'])->where('status', 1)->latest()->paginate(getPaginate());
        $this->recordImpressions($deals, 'deal');
        $dealCategories = DealCategory::where('status', 1)->latest()->get();
        return  view($this->activeTemplate . 'deals',compact('pageTitle', 'deals', 'dealCategories'));
    }
    // deal filter
    public function dealFilter(Request $request) {

        $dealCategories = $request->input('dealCategories', []);
        $search = $request->input('search');

        $query = Deal::with(['deal_category'])->where('status', 1);

        if (!empty($dealCategories)) {
            $query->whereIn('deal_category_id', $dealCategories);
        }

        if (!empty($search)) {
            $lowerSearch = strtolower($search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%$search%")
                  ->orWhereHas('deal_category', function($dcq) use ($search) {
                      $dcq->where('title', 'LIKE', "%$search%");
                  });
            })->orderByRaw("CASE 
                WHEN LOWER(title) = ? THEN 1 
                WHEN LOWER(title) LIKE ? THEN 2 
                ELSE 3 
            END", [$lowerSearch, $lowerSearch . '%']);
        }

        $deals = $query->get();
        $this->recordImpressions($deals, 'deal');
        $view = View::make($this->activeTemplate.'dealFilter.deal_search', compact('deals', 'dealCategories'))->render();

        return response()->json([
            'html' => $view
        ]);
    }
    // feature coupon filter
    public function featureCouponFilter(Request $request) {

        $categories = $request->input('categories', []);
        $stores = $request->input('stores', []);
        $search = $request->input('search');



        $query = Coupon::with(['category', 'store','wishlists'])->where('status', 1)->where('is_featured', 1);

        if (!empty($categories)) {
            $query->whereIn('category_id', $categories);
        }

        if (!empty($stores)) {
            $query->whereIn('store_id', $stores);
        }
        if (!empty($search)) {
            $lowerSearch = strtolower($search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%$search%")
                  ->orWhereHas('store', function ($sq) use ($search) {
                      $sq->where('name', 'LIKE', "%$search%");
                  })
                  ->orWhereHas('category', function ($cq) use ($search) {
                      $cq->where('name', 'LIKE', "%$search%");
                  });
            })->orderByRaw("CASE 
                WHEN LOWER(title) = ? THEN 1 
                WHEN LOWER(title) LIKE ? THEN 2 
                ELSE 3 
            END", [$lowerSearch, $lowerSearch . '%']);
        }

        $featureCoupons = $query->get();
        $view = View::make($this->activeTemplate.'couponFilter.feature_coupon_search', compact('featureCoupons', 'categories', 'stores'))->render();

        return response()->json([
            'html' => $view
        ]);
    }

    // coupon filter
    public function couponFilter(Request $request) {

        $categories = $request->input('categories', []);
        $stores = $request->input('stores', []);
        $search = $request->input('search');
        $isCashback = $request->input('is_cashback', 0);

        $query = Coupon::with(['category', 'store','wishlists'])->where('status', 1)->where('is_cashback', $isCashback);

        if (!empty($categories)) {
            $query->whereIn('category_id', $categories);
        }

        if (!empty($stores)) {
            $query->whereIn('store_id', $stores);
        }
        if (!empty($search)) {
            $lowerSearch = strtolower($search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%$search%")
                  ->orWhereHas('store', function ($sq) use ($search) {
                      $sq->where('name', 'LIKE', "%$search%");
                  })
                  ->orWhereHas('category', function ($cq) use ($search) {
                      $cq->where('name', 'LIKE', "%$search%");
                  });
            })->orderByRaw("CASE 
                WHEN LOWER(title) = ? THEN 1 
                WHEN LOWER(title) LIKE ? THEN 2 
                ELSE 3 
            END", [$lowerSearch, $lowerSearch . '%']);
        }

        $coupons = $query->get();
        $this->recordImpressions($coupons, 'coupon');
        $view = View::make($this->activeTemplate.'couponFilter.coupon_search', compact('coupons', 'categories', 'stores'))->render();

        return response()->json([
            'html' => $view
        ]);
    }

    // 
    public function categoryCoupons($id) {
        $category = Category::findOrFail($id);
        $pageTitle =  $category->name;
        $coupons = Coupon::with(['category', 'store','wishlists'])->where('status', 1)->where('category_id', $id)->latest()->paginate(getPaginate());
        $this->recordImpressions($coupons, 'coupon');
        $categories = Category::where('status', 1)->whereHas('coupons', function($q) {
            $q->where('status', 1);
        })->latest()->get();
        $stores = Store::where('status', 1)->whereHas('coupons', function($q) {
            $q->where('status', 1);
        })->latest()->get();
       
        $general = gs();
        $catUrl  = route('category.coupons', $category->id);
        $canonicalCatUrl = $coupons->currentPage() > 1 ? $catUrl . '?page=' . $coupons->currentPage() : $catUrl;
        $catImg  = $category->image ? getImage(getFilePath('category') . '/' . $category->image) : null;
        $seoContents = [
            'title'       => "{$category->name} Coupons, Deals & Cashback Offers - {$general->site_name}",
            'description' => "Explore the latest verified {$category->name} coupons, promo codes, discount deals and extra cashback offers on {$general->site_name}.",
            'keywords'    => [$category->name . ' coupons', $category->name . ' deals', $category->name . ' cashback', $category->name . ' offers'],
            'image'       => $catImg,
            'canonical'   => $canonicalCatUrl,
            'schema'      => [
                '@context'        => 'https://schema.org',
                '@type'           => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Categories', 'item' => route('categories')],
                    ['@type' => 'ListItem', 'position' => 3, 'name' => $category->name, 'item' => $catUrl],
                ],
            ],
        ];

        return  view($this->activeTemplate . 'coupons',compact('pageTitle', 'coupons', 'categories', 'stores', 'seoContents'));

    }

    // 
    public function storeCoupons($id) {
        $store = Store::find($id);
        if (!$store) {
            $store = Store::where('name', 'LIKE', '%' . $id . '%')->first();
        }
        if (!$store) {
            $notify[] = ['error', 'Store not found or currently unavailable'];
            return to_route('brands')->withNotify($notify);
        }
        $id = $store->id;
        $pageTitle =  $store->name;
        $coupons = Coupon::with(['category', 'store','wishlists'])->where('status', 1)->where('store_id', $id)->latest()->paginate(getPaginate());
        $this->recordImpressions($coupons, 'coupon');
        $categories = Category::where('status', 1)->latest()->get();
        $stores = Store::where('status', 1)->latest()->get();

        // Find store's category from its coupons so we can show similar category offers from other brands
        $primaryCategoryId = Coupon::where('store_id', $id)->whereNotNull('category_id')->value('category_id');
        $primaryCategory = $primaryCategoryId ? Category::find($primaryCategoryId) : null;

        $relatedCategoryCoupons = collect();
        if ($primaryCategoryId) {
            $relatedCategoryCoupons = Coupon::with(['category', 'store', 'wishlists'])
                ->where('status', 1)
                ->where('category_id', $primaryCategoryId)
                ->where('store_id', '!=', $id)
                ->latest()
                ->take(6)
                ->get();
            $this->recordImpressions($relatedCategoryCoupons, 'coupon');
        }

        $general  = gs();
        $storeUrl = route('store.coupons', $store->id);
        $canonicalStoreUrl = $coupons->currentPage() > 1 ? $storeUrl . '?page=' . $coupons->currentPage() : $storeUrl;
        $storeImg = $store->image ? getImage(getFilePath('store') . '/' . $store->image) : null;
        $storeDesc = trim(preg_replace('/\s+/', ' ', strip_tags(html_entity_decode((string) ($store->description ?? '')))))
            ?: "Get verified {$store->name} coupons, promo codes, discount deals and extra cashback offers on {$general->site_name}.";

        $seoContents = [
            'title'       => "{$store->name} Coupons, Promo Codes & Cashback Offers - {$general->site_name}",
            'description' => $storeDesc,
            'keywords'    => [$store->name . ' coupons', $store->name . ' promo codes', $store->name . ' cashback', $store->name . ' discount offers'],
            'image'       => $storeImg,
            'canonical'   => $canonicalStoreUrl,
            'schema'      => [
                '@context'        => 'https://schema.org',
                '@type'           => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Brands', 'item' => route('brands')],
                    ['@type' => 'ListItem', 'position' => 3, 'name' => $store->name, 'item' => $storeUrl],
                ],
            ],
        ];

        return view($this->activeTemplate . 'coupons', compact('pageTitle', 'store', 'coupons', 'categories', 'stores', 'primaryCategory', 'relatedCategoryCoupons', 'seoContents'));
    }

    public function singleCouponSearch(Request $request) {
        $pageTitle = "Search Coupon";
        $search = trim($request->search ?? '');
        if(empty($search)) {
            $notify[] = ['error','Invalid Search value'];
            return back()->withNotify($notify);
        }
        $lowerSearch = strtolower($search);
        $query = Coupon::with(['category', 'store','wishlists'])->where('status', 1);
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%$search%")
                  ->orWhereHas('store', function ($sq) use ($search) {
                      $sq->where('name', 'LIKE', "%$search%");
                  });
            })
            ->orderByRaw("CASE WHEN LOWER(title) = ? THEN 1 WHEN LOWER(title) LIKE ? THEN 2 ELSE 3 END", [$lowerSearch, $lowerSearch . '%']);
        }
        $coupons = $query->paginate(getPaginate());
        $categories = Category::where('status', 1)->whereHas('coupons', function($q) {
            $q->where('status', 1);
        })->latest()->get();
        $stores = Store::where('status', 1)->whereHas('coupons', function($q) {
            $q->where('status', 1);
        })->latest()->get();
        return  view($this->activeTemplate . 'coupons',compact('pageTitle', 'coupons', 'categories', 'stores', 'search'));
    }

    public function searchSuggest(Request $request) {
        $search = trim($request->search ?? $request->q ?? '');
        if (empty($search)) {
            return response()->json([
                'status' => 'success',
                'stores' => [],
                'coupons' => []
            ]);
        }

        $lowerSearch = strtolower($search);

        // Stores matching search, exact match first, then starting with, then containing
        $stores = Store::where('status', 1)
            ->where('name', 'LIKE', "%$search%")
            ->orderByRaw("CASE WHEN LOWER(name) = ? THEN 1 WHEN LOWER(name) LIKE ? THEN 2 ELSE 3 END", [$lowerSearch, $lowerSearch . '%'])
            ->take(6)
            ->get()
            ->map(function ($store) {
                return [
                    'id' => $store->id,
                    'name' => $store->name,
                    'image' => getImage(getFilePath('store') . '/' . $store->image),
                    'url' => route('store.coupons', $store->id, false),
                    'tag' => 'in Stores',
                    'subtitle' => 'Visit Store & Earn Cashback',
                ];
            });

        // Coupons matching search title or related store, prefix match first
        $coupons = Coupon::where('status', 1)
            ->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%$search%")
                  ->orWhereHas('store', function ($sq) use ($search) {
                      $sq->where('name', 'LIKE', "%$search%");
                  });
            })
            ->with('store')
            ->orderByRaw("CASE WHEN LOWER(title) = ? THEN 1 WHEN LOWER(title) LIKE ? THEN 2 ELSE 3 END", [$lowerSearch, $lowerSearch . '%'])
            ->take(6)
            ->get()
            ->map(function ($coupon) {
                return [
                    'id' => $coupon->id,
                    'title' => $coupon->title,
                    'type' => $coupon->coupon_type == 1 ? 'Code' : 'Deal',
                    'code' => $coupon->coupon_code,
                    'store_name' => @$coupon->store->name ?? '',
                    'store_image' => @$coupon->store->image ? getImage(getFilePath('store') . '/' . $coupon->store->image) : null,
                    'url' => route('coupon.details', $coupon->slug ?? $coupon->id, false),
                    'name' => @$coupon->store->name ?: $coupon->title,
                    'image' => @$coupon->store->image ? getImage(getFilePath('store') . '/' . $coupon->store->image) : null,
                    'tag' => 'in Products',
                    'subtitle' => $coupon->title,
                ];
            });

        return response()->json([
            'status' => 'success',
            'query' => $search,
            'search_url' => route('single.coupon.search', [], false) . '?search=' . urlencode($search),
            'stores' => $stores,
            'coupons' => $coupons
        ]);
    }

    public function subscribe(Request $request){
        $request->validate([
            'email'=>'required|unique:subscribers|email',
        ]);
        $subscribe=new Subscriber();
        $subscribe->email=$request->email;
        $subscribe->save();
        $notify[] = ['success','You have successfully subscribed to the Newsletter'];
        return back()->withNotify($notify);
    }

    public function landingPage(){
        $pageTitle = 'Landing Page';
        return view('landing',compact('pageTitle'));
    }
    
    // Display all deal categories (paginated)
    public function dealsCategories() {
        $pageTitle = "Deals Store";
        $dealCategories = DealCategory::withCount('deals') // just count deals instead of loading them
            ->where('status', 1)
            ->latest()
            ->paginate(getPaginate());

        return view($this->activeTemplate . 'deals-categories', compact('pageTitle', 'dealCategories'));
    }
    // Show deals by category
    public function categoryDeals($id) {
        $dealCategory = DealCategory::find($id);
        if (!$dealCategory) {
            abort(404); // or redirect back()->with('error', 'Category not found.');
        }

        $pageTitle = $dealCategory->title;

        $deals = Deal::with('deal_category')
            ->where('status', 1)
            ->where('deal_category_id', $id)
            ->latest()
            ->paginate(getPaginate());

        $dealCategories = DealCategory::where('status', 1)->latest()->get();

        return view($this->activeTemplate . 'deals', compact('pageTitle', 'deals', 'dealCategories'));
    }

    /**
     * Dynamic XML Sitemap for Google & Search Engines (/sitemap.xml)
     */
    public function sitemap()
    {
        $xml = Cache::remember('site_sitemap_xml_v2', 3600, function () {
            // Always anchor sitemap URLs to configured APP_URL (never trust incoming Host / X-Forwarded-Host header)
            $baseUrl = rtrim((string) config('app.url'), '/');
            $absUrl  = function (string $relativePath) use ($baseUrl): string {
                return $baseUrl . '/' . ltrim($relativePath, '/');
            };

            $urls = [];

            // 1. Static core pages (omit lastmod rather than emitting sitemap generation time)
            $staticRoutes = [
                ['loc' => $baseUrl . '/',                                   'priority' => '1.0', 'changefreq' => 'daily'],
                ['loc' => $absUrl(route('brands', [], false)),              'priority' => '0.9', 'changefreq' => 'daily'],
                ['loc' => $absUrl(route('coupon', [], false)),              'priority' => '0.9', 'changefreq' => 'daily'],
                ['loc' => $absUrl(route('latest.coupons', [], false)),      'priority' => '0.8', 'changefreq' => 'daily'],
                ['loc' => $absUrl(route('cashback.coupons', [], false)),    'priority' => '0.8', 'changefreq' => 'daily'],
                ['loc' => $absUrl(route('exclusive.coupons', [], false)),   'priority' => '0.8', 'changefreq' => 'daily'],
                ['loc' => $absUrl(route('deal', [], false)),                'priority' => '0.8', 'changefreq' => 'daily'],
                ['loc' => $absUrl(route('categories', [], false)),          'priority' => '0.8', 'changefreq' => 'weekly'],
                ['loc' => $absUrl(route('blog', [], false)),                'priority' => '0.7', 'changefreq' => 'weekly'],
            ];
            foreach ($staticRoutes as $sr) {
                $urls[] = $sr;
            }

            // 2. Active Stores (Brands)
            foreach (Store::where('status', 1)->orderByDesc('priority_score')->get(['id', 'updated_at']) as $st) {
                $urls[] = [
                    'loc'        => $absUrl(route('store.coupons', $st->id, false)),
                    'lastmod'    => $st->updated_at ? $st->updated_at->toAtomString() : null,
                    'changefreq' => 'daily',
                    'priority'   => '0.85',
                ];
            }

            // 3. Active Categories
            foreach (Category::where('status', 1)->get(['id', 'updated_at']) as $cat) {
                $urls[] = [
                    'loc'        => $absUrl(route('category.coupons', $cat->id, false)),
                    'lastmod'    => $cat->updated_at ? $cat->updated_at->toAtomString() : null,
                    'changefreq' => 'weekly',
                    'priority'   => '0.75',
                ];
            }

            // 4. Active Coupons (slug URLs)
            foreach (Coupon::where('status', 1)->latest()->get(['id', 'slug', 'updated_at']) as $cp) {
                $urls[] = [
                    'loc'        => $absUrl(route('coupon.details', $cp->slug ?: $cp->id, false)),
                    'lastmod'    => $cp->updated_at ? $cp->updated_at->toAtomString() : null,
                    'changefreq' => 'daily',
                    'priority'   => '0.8',
                ];
            }

            // 5. Active Deals
            foreach (Deal::where('status', 1)->latest()->get(['id', 'updated_at']) as $dl) {
                $urls[] = [
                    'loc'        => $absUrl(route('deal.details', $dl->id, false)),
                    'lastmod'    => $dl->updated_at ? $dl->updated_at->toAtomString() : null,
                    'changefreq' => 'daily',
                    'priority'   => '0.7',
                ];
            }

            // 6. Blogs
            foreach (Frontend::where('data_keys', 'blog.element')->latest()->get(['id', 'data_values', 'updated_at']) as $bl) {
                $bTitle = @$bl->data_values->title ?: 'blog';
                $urls[] = [
                    'loc'        => $absUrl(route('blog.details', [slug($bTitle), $bl->id], false)),
                    'lastmod'    => $bl->updated_at ? $bl->updated_at->toAtomString() : null,
                    'changefreq' => 'monthly',
                    'priority'   => '0.7',
                ];
            }

            $out  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $out .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
            foreach ($urls as $u) {
                $out .= "  <url>\n";
                $out .= '    <loc>' . htmlspecialchars($u['loc'], ENT_XML1, 'UTF-8') . "</loc>\n";
                if (!empty($u['lastmod'])) {
                    $out .= '    <lastmod>' . $u['lastmod'] . "</lastmod>\n";
                }
                $out .= '    <changefreq>' . $u['changefreq'] . "</changefreq>\n";
                $out .= '    <priority>' . $u['priority'] . "</priority>\n";
                $out .= "  </url>\n";
            }
            $out .= '</urlset>';

            return $out;
        });

        return response($xml, 200)->header('Content-Type', 'application/xml; charset=utf-8');
    }


}
