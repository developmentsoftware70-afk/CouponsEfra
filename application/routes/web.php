<?php

use App\Lib\Router;
use Illuminate\Support\Facades\Route;

Route::get('/clear', function(){
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->group(function () {
    Route::get('/', 'supportTicket')->name('ticket');
    Route::get('/new', 'openSupportTicket')->name('ticket.open');
    Route::post('/create', 'storeSupportTicket')->name('ticket.store');
    Route::get('/view/{ticket}', 'viewTicket')->name('ticket.view');
    Route::post('/reply/{ticket}', 'replyTicket')->name('ticket.reply');
    Route::post('/close/{ticket}', 'closeTicket')->name('ticket.close');
    Route::get('/download/{ticket}', 'ticketDownload')->name('ticket.download');
});



Route::controller('SiteController')->group(function () {
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit');
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');

    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');

    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');

    // blog
    Route::get('/blog', 'blog')->name('blog');;
    Route::get('blog/{slug}/{id}', 'blogDetails')->name('blog.details');

    Route::get('policy/{slug}/{id}', 'policyPages')->name('policy.pages');

    Route::get('placeholder-image/{size}', 'placeholderImage')->name('placeholder.image');
    Route::get('get/modal/info','getModalInfo')->name('get.modal.info');
    // for deal popup
    Route::get('get/modal/deal-info','getModalDealInfo')->name('get.modal.dealInfo');

    // exclusive coupon
    Route::get('/exclusive-coupons', 'exclusiveCoupon')->name('exclusive.coupons');
    Route::get('/coupons', 'coupons')->name('coupon');
   
    // category
    Route::get('/categories', 'categories')->name('categories');

    Route::get('/category/coupons/{id}', 'categoryCoupons')->name('category.coupons');
    Route::get('/store/coupons/{id}', 'storeCoupons')->name('store.coupons');
    // deals and deals category
    Route::get('/deals-categories', 'dealsCategories')->name('deals.categories');
    Route::get('/deals-category/deals/{id}', 'categoryDeals')->name('category.deals');

    // feature coupon
    Route::get('/feature-coupons', 'featureCoupon')->name('feature.coupons');

    // cashback coupon
    Route::get('/cashback-coupons', 'cashbackCoupon')->name('cashback.coupons');

    // affiliate
    Route::get('/affiliates', 'affiliate')->name('affiliate');
    // Earning
    Route::get('/earnings', 'earning')->name('earning');

    // feature store
    Route::get('/top-store', 'featureStore')->name('feature.store');

    // latest coupon
    Route::get('/latest-coupons', 'latestCoupon')->name('latest.coupons');

    // popular coupon
    Route::get('/popular-coupons', 'popularCoupon')->name('popular.coupons');

    // popular coupon
    Route::get('/sale-coupons', 'saleCoupon')->name('sale.coupons');

    // popular deal
    Route::get('/popular-deals', 'popularDeal')->name('popular.deals');
    Route::get('/deals', 'deals')->name('deal');
    Route::get('deal/filter', 'dealFilter')->name('deal.filtered');

    // coupon filter
    Route::get('exclusive/coupon/filter', 'exclusiveCouponFilter')->name('exclusive.coupon.filtered');
    Route::get('/feature/coupon/filter', 'featureCouponFilter')->name('feature.coupon.filtered');
    Route::get('coupon/filter', 'couponFilter')->name('coupon.filtered');

    //Single coupon Search 
    Route::get('coupon/search', 'singleCouponSearch')->name('single.coupon.search');

    // subscriber
    Route::post('/subscribe','subscribe')->name('subscribe');

    Route::get('/coupon-details/{id}', 'couponDetails')->name('coupon.details');
    Route::get('/go/{id}', 'goStore')->name('coupon.redirect');
    Route::get('/{slug}', 'pages')->name('pages');
    Route::get('/', 'index')->name('home');
});



// Google Auth Routes
Route::get('/api/auth/google/redirect', [\App\Http\Controllers\SocialLoginController::class, 'redirect'])->name('google.redirect');
Route::get('/api/auth/google/callback', [\App\Http\Controllers\SocialLoginController::class, 'callback'])->name('google.callback');
