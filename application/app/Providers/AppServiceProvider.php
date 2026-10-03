<?php

namespace App\Providers;

use App\Models\AdminNotification;
use App\Models\Deposit;
use App\Models\Coupon;
use App\Models\Deal;
use App\Models\Frontend;
use App\Models\Language;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $general = gs();
        $activeTemplate = activeTemplate();
        $viewShare['general'] = $general;
        $viewShare['activeTemplate'] = $activeTemplate;
        $viewShare['activeTemplateTrue'] = activeTemplate(true);
        $viewShare['language'] = Language::all();
        $viewShare['emptyMessage'] = 'No data';
        view()->share($viewShare);
        
        view()->composer('admin.components.tabs.user', function ($view) {
            $view->with([
                'bannedUsersCount'          => User::banned()->count(),
                'emailUnverifiedUsersCount' => User::emailUnverified()->count(),
                'mobileUnverifiedUsersCount'=> User::mobileUnverified()->count(),

            ]);
        });
        
        view()->composer('admin.components.sidenav', function ($view) {
            $view->with([
                'bannedUsersCount'           => User::banned()->count(),
                'emailUnverifiedUsersCount' => User::emailUnverified()->count(),
                'mobileUnverifiedUsersCount'   => User::mobileUnverified()->count(),
                'pendingTicketCount'         => SupportTicket::whereIN('status', [0,2])->count(),
                'pendingDepositsCount'    => Deposit::pending()->count(),
            ]);
        });
        view()->composer('admin.components.tabs.deposit', function ($view) {
            $view->with([
                'pendingDepositsCount'    => Deposit::pending()->count(),
            ]);
        });
        
            
        view()->composer('admin.components.tabs.ticket', function ($view) {
            $view->with([
                'pendingTicketCount'         => SupportTicket::whereIN('status', [0,2])->count(),
            ]);
        });

        view()->composer('admin.components.topnav', function ($view) {
            $view->with([
                'adminNotifications'=>AdminNotification::where('read_status',0)->with('user')->orderBy('id','desc')->take(10)->get(),
                'adminNotificationCount'=>AdminNotification::where('read_status',0)->count(),
            ]);
        });

        view()->composer('admin.components.tabs.coupon', function ($view) {
            $view->with([
                'pendingCoupon'=> Coupon::where('status',0)->count(),
                'approvedCoupon'=> Coupon::where('status',1)->count(),
                'allCoupon'=> Coupon::count(),
               
            ]);
        });
        view()->composer('admin.components.tabs.deal', function ($view) {
            $view->with([
                'pendingDeal'=> Deal::where('status',0)->count(),
                'approvedDeal'=> Deal::where('status',1)->count(),
                'allDeal'=> Deal::count(),
               
            ]);
        });
        view()->composer('includes.seo', function ($view) {
            $seo = Frontend::where('data_keys', 'seo.data')->first();
            $view->with([
                'seo' => $seo ? $seo->data_values : $seo,
            ]);
        });

        if($general->force_ssl){
            \URL::forceScheme('https');
        }


        Paginator::useBootstrapFour();
    }
}
