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

class SiteController extends Controller
{
    public function index(){
        $reference = @$_GET['reference'];
        if ($reference) {
            session()->put('reference', $reference);
        }
        if (empty($reference)) {
            session()->forget('reference');
        }

        $pageTitle = 'Home';
        $sections = Page::where('tempname',$this->activeTemplate)->where('slug','/')->first();
        $categories =  Category::where('status',1)->latest()->limit(4)->get();
        $slider =  Slider::all()->where('status',1);
        return view($this->activeTemplate . 'home', compact('pageTitle','sections','categories','slider'));
    }

    public function pages($slug)
    {
        $page = Page::where('tempname',$this->activeTemplate)->where('slug',$slug)->firstOrFail();
        $pageTitle = $page->name;
        $sections = $page->secs;
        return view($this->activeTemplate . 'pages', compact('pageTitle','sections'));
    }


    public function couponDetails($id)
    {
        $coupon = Coupon::with('store')->findOrFail($id);
        $pageTitle = $coupon->title;
        // Fetch 3 related coupons from the same store
        $relatedCoupons = Coupon::where('store_id', $coupon->store_id)
            ->where('id', '!=', $id)
            ->where('status', 1)
            ->take(3)
            ->get();
        return view($this->activeTemplate . 'coupon_details', compact('pageTitle', 'coupon', 'relatedCoupons'));
    }

    public function goStore($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->increment('click_count');
        
        // Instant redirect to the actual store
        return redirect($coupon->link);
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
        $pageTitle = "Blog Details";
        $latests = Frontend::where('data_keys','blog.element')->orderBy('id','desc')->limit(5)->get();
        return view($this->activeTemplate.'blog_details',compact('blog','pageTitle','latests'));
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
        $categories = Category::where('status', 1)->latest()->get();
        $stores = Store::where('status', 1)->latest()->get();
        return view($this->activeTemplate . 'exclusive_coupons',compact('pageTitle', 'exclusiveCoupons', 'categories', 'stores'));
    }
    // coupons
    public function coupons() {
        $pageTitle = "Coupons";
        $coupons = Coupon::with(['category', 'store','wishlists'])->where('status', 1)->latest()->paginate(getPaginate());
        $categories = Category::where('status', 1)->latest()->get();
        $stores = Store::where('status', 1)->latest()->get();
        return  view($this->activeTemplate . 'coupons',compact('pageTitle', 'coupons', 'categories', 'stores'));
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
        $categories = Category::where('status', 1)->latest()->get();
        $stores = Store::where('status', 1)->latest()->get();
      
        return view($this->activeTemplate . 'feature_coupons',compact('pageTitle', 'featureCoupons', 'categories', 'stores'));
    }
    //latest coupon 
    public function latestCoupon()
    {
        $pageTitle = "Latest Coupon";
        $featureCoupons = Coupon::with(['category', 'store','wishlists'])->where('status', 1)->latest()->paginate(getPaginate());
        $categories = Category::where('status', 1)->latest()->get();
        $stores = Store::where('status', 1)->latest()->get();
      
        return view($this->activeTemplate . 'feature_coupons',compact('pageTitle', 'featureCoupons', 'categories', 'stores'));
    }
    //popular coupon 
    public function popularCoupon()
    {
        $pageTitle = "Popular Coupon";
        $popularCoupons = Coupon::with(['category', 'store','wishlists'])->where('status', 1)->where('is_popular', 1)->latest()->paginate(getPaginate());
        $categories = Category::where('status', 1)->latest()->get();
        $stores = Store::where('status', 1)->latest()->get();
      
        return view($this->activeTemplate . 'popular_coupons',compact('pageTitle', 'popularCoupons', 'categories', 'stores'));
    }
    //sale coupon 
    public function saleCoupon()
    {
        $pageTitle = "Sale Coupon";
        $saleCoupons = Coupon::with(['category', 'store','wishlists'])->where('status', 1)->where('is_sale', 1)->latest()->paginate(getPaginate());
        $categories = Category::where('status', 1)->latest()->get();
        $stores = Store::where('status', 1)->latest()->get();
      
        return view($this->activeTemplate . 'sale_coupons',compact('pageTitle', 'saleCoupons', 'categories', 'stores'));
    }
    //cashback coupon 
    public function cashbackCoupon()
    {
        $pageTitle = "Cashback Coupon";
        $cashbackCoupons = Coupon::with(['category', 'store','wishlists'])->where('status', 1)->where('is_cashback', 1)->latest()->paginate(getPaginate());
        $categories = Category::where('status', 1)->latest()->get();
        $stores = Store::where('status', 1)->latest()->get();
      
        return view($this->activeTemplate . 'cashback_coupons',compact('pageTitle', 'cashbackCoupons', 'categories', 'stores'));
    }
    //affiliate
    public function affiliate()
    {
        $pageTitle = "Affiliates";
        $coupons = Coupon::with(['category', 'store','wishlists'])->where('status', 1)->where('is_cashback', 0)->latest()->paginate(getPaginate(8));
        $cashbackCoupons = Coupon::with(['category', 'store','wishlists'])->where('status', 1)->where('is_cashback', 1)->latest()->paginate(getPaginate(8));
        $categories = Category::where('status', 1)->latest()->get();
        $stores = Store::where('status', 1)->latest()->get();
        $deals = Deal::with(['deal_category'])->where('status', 1)->where('is_affiliate', 1)->latest()->paginate(getPaginate(8));
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
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%$search%");  
            });
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
        $pageTitle = "Popular Deal";
        $popularDeals = Deal::with(['deal_category'])->where('status', 1)->where('is_popular', 1)->latest()->paginate(getPaginate());
        $dealCategories = DealCategory::where('status', 1)->latest()->get();
      
        return view($this->activeTemplate . 'popular_deals',compact('pageTitle', 'popularDeals', 'dealCategories'));
    }
    // deals
    public function deals() {
        $pageTitle = "Deals";
        $deals = Deal::with(['deal_category'])->where('status', 1)->latest()->paginate(getPaginate());
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
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%$search%");  
            });
        }

        $deals = $query->get();
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
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%$search%");  
            });
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

        $query = Coupon::with(['category', 'store','wishlists'])->where('status', 1);

        if (!empty($categories)) {
            $query->whereIn('category_id', $categories);
        }

        if (!empty($stores)) {
            $query->whereIn('store_id', $stores);
        }
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%$search%");  
            });
        }

        $coupons = $query->get();
        $view = View::make($this->activeTemplate.'couponFilter.coupon_search', compact('coupons', 'categories', 'stores'))->render();

        return response()->json([
            'html' => $view
        ]);
    }

    // 
    public function categoryCoupons($id) {
        $category = Category::find($id);
        $pageTitle =  $category->name;
        $coupons = Coupon::with(['category', 'store','wishlists'])->where('status', 1)->where('category_id', $id)->latest()->paginate(getPaginate());
        $categories = Category::where('status', 1)->latest()->get();
        $stores = Store::where('status', 1)->latest()->get();
       
        return  view($this->activeTemplate . 'coupons',compact('pageTitle', 'coupons', 'categories', 'stores'));

    }

    // 
    public function storeCoupons($id) {
        $store = Store::find($id);
        $pageTitle =  $store->name;
        $coupons = Coupon::with(['category', 'store','wishlists'])->where('status', 1)->where('store_id', $id)->latest()->paginate(getPaginate());
        $categories = Category::where('status', 1)->latest()->get();
        $stores = Store::where('status', 1)->latest()->get();
        
        return  view($this->activeTemplate . 'coupons',compact('pageTitle', 'coupons', 'categories', 'stores'));

    }

    public function singleCouponSearch(Request $request) {
        $pageTitle = "Search Coupon";
        $search =$request->search;
        if(empty( $search)) {
            $notify[] = ['error','Invalid Search value'];
            return back()->withNotify($notify);
        }
        $query = Coupon::with(['category', 'store','wishlists'])->where('status', 1);
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%$search%");  
            });
        }
        $coupons = $query->paginate(getPaginate());
        $categories = Category::where('status', 1)->latest()->get();
        $stores = Store::where('status', 1)->latest()->get();
        return  view($this->activeTemplate . 'coupons',compact('pageTitle', 'coupons', 'categories', 'stores'));
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


}
