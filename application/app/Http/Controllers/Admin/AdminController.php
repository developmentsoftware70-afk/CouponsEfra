<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\CurlRequest;
use App\Models\AdminNotification;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Store;
use App\Models\User;
use App\Models\UserLogin;
use App\Models\SupportTicket;
use App\Models\PayoutRequest;
use App\Models\DailyMetric;
use App\Models\Order;
use App\Models\Click;
use Illuminate\Support\Facades\DB;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function dashboard()
    {

        $pageTitle = 'Dashboard';

        // Cached widgets (15s) to avoid 13 remote queries on each page load
        $widget = \Illuminate\Support\Facades\Cache::remember('admin_dashboard_widgets_full', 15, function () {
            return [
                'total_users'             => User::count(),
                'verified_users'          => User::where('status', 1)->where('ev', 1)->where('sv', 1)->count(),
                'email_unverified_users'  => User::emailUnverified()->count(),
                'mobile_unverified_users' => User::mobileUnverified()->count(),
                'totalCoupon'             => Coupon::count(),
                'totalCategory'           => Category::count(),
                'totalStore'              => Store::count(),
                'total_conversions'       => Order::whereIn('status', ['confirmed', 'processing', 'shipped', 'delivered'])->count(),
                'total_orders'            => Order::count(),
                'total_clicks'            => (int) DailyMetric::sum('clicks'),
                'total_logins'            => (int) UserLogin::count(),
                'total_payout_amount'     => (float) PayoutRequest::whereIn('status', ['confirmed', 'paid'])->sum('amount'),
                'total_impressions'       => (int) DailyMetric::sum('impressions'),
            ];
        });

        // Key Real-time Metrics: Clicks, Impressions, Payout, Logins (Today, Yesterday, MTD)
        $kpiMetrics = $this->getKpiMetrics();

        $performanceData = $this->getPerformanceData();

        $newTickets = SupportTicket::with('user')->orderBy('created_at', 'desc')->whereStatus(0)->limit(5)->get();
        $topPublishers = $this->getTopPublishersData();
        return view('admin.dashboard', compact('pageTitle', 'widget', 'newTickets', 'kpiMetrics', 'performanceData', 'topPublishers'));
    }

    public function getKpiMetrics()
    {
        return \Illuminate\Support\Facades\Cache::remember('admin_kpi_metrics', 10, function () {
            $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $startOfMonth = Carbon::now()->startOfMonth();

        $todayStr = $today->toDateString();
        $yesterdayStr = $yesterday->toDateString();
        $todayStart = $today->toDateTimeString();
        $yesterdayStart = $yesterday->toDateTimeString();
        $monthStart = $startOfMonth->toDateTimeString();

        // 1. Single consolidated DailyMetric query
        $metricsRow = DailyMetric::where('metric_date', '>=', $startOfMonth->toDateString())
            ->selectRaw("
                SUM(CASE WHEN metric_date = '{$todayStr}' THEN clicks ELSE 0 END) as clicks_today,
                SUM(CASE WHEN metric_date = '{$yesterdayStr}' THEN clicks ELSE 0 END) as clicks_yesterday,
                SUM(clicks) as clicks_mtd,
                SUM(CASE WHEN metric_date = '{$todayStr}' THEN impressions ELSE 0 END) as imp_today,
                SUM(CASE WHEN metric_date = '{$yesterdayStr}' THEN impressions ELSE 0 END) as imp_yesterday,
                SUM(impressions) as imp_mtd
            ")->first();

        $clicksToday = (int) ($metricsRow->clicks_today ?? 0);
        $clicksYesterday = (int) ($metricsRow->clicks_yesterday ?? 0);
        $clicksMtd = (int) ($metricsRow->clicks_mtd ?? 0);
        $impToday = (int) ($metricsRow->imp_today ?? 0);
        $impYesterday = (int) ($metricsRow->imp_yesterday ?? 0);
        $impMtd = (int) ($metricsRow->imp_mtd ?? 0);

        // 2. Single consolidated Order query
        $ordersRow = Order::whereIn('status', ['confirmed', 'processing', 'shipped', 'delivered'])
            ->where('created_at', '>=', $monthStart)
            ->selectRaw("
                COUNT(CASE WHEN created_at >= '{$todayStart}' THEN 1 END) as conv_today,
                COUNT(CASE WHEN created_at >= '{$yesterdayStart}' AND created_at < '{$todayStart}' THEN 1 END) as conv_yesterday,
                COUNT(*) as conv_mtd
            ")->first();

        $conversionsToday = (int) ($ordersRow->conv_today ?? 0);
        $conversionsYesterday = (int) ($ordersRow->conv_yesterday ?? 0);
        $conversionsMtd = (int) ($ordersRow->conv_mtd ?? 0);

        $crToday = $clicksToday > 0 ? round(($conversionsToday / $clicksToday) * 100, 1) : 0;
        $crYesterday = $clicksYesterday > 0 ? round(($conversionsYesterday / $clicksYesterday) * 100, 1) : 0;
        $crMtd = $clicksMtd > 0 ? round(($conversionsMtd / $clicksMtd) * 100, 1) : 0;

        // 3. Single consolidated Payout query
        $payoutRow = PayoutRequest::whereIn('status', ['confirmed', 'paid'])
            ->where('updated_at', '>=', $monthStart)
            ->selectRaw("
                SUM(CASE WHEN updated_at >= '{$todayStart}' THEN amount ELSE 0 END) as pay_today,
                SUM(CASE WHEN updated_at >= '{$yesterdayStart}' AND updated_at < '{$todayStart}' THEN amount ELSE 0 END) as pay_yesterday,
                SUM(amount) as pay_mtd
            ")->first();

        // 4. Single consolidated Login query
        $loginRow = UserLogin::where('created_at', '>=', $monthStart)
            ->whereNotNull('user_id')
            ->selectRaw("
                COUNT(DISTINCT CASE WHEN created_at >= '{$todayStart}' THEN user_id END) as login_today,
                COUNT(DISTINCT CASE WHEN created_at >= '{$yesterdayStart}' AND created_at < '{$todayStart}' THEN user_id END) as login_yesterday,
                COUNT(DISTINCT user_id) as login_mtd
            ")->first();

        return [
            'clicks' => [
                'today'     => $clicksToday,
                'yesterday' => $clicksYesterday,
                'mtd'       => $clicksMtd,
            ],
            'conversions' => [
                'today'     => $conversionsToday,
                'yesterday' => $conversionsYesterday,
                'mtd'       => $conversionsMtd,
            ],
            'conversion_rate' => [
                'today'     => $crToday,
                'yesterday' => $crYesterday,
                'mtd'       => $crMtd,
            ],
            'impressions' => [
                'today'     => $impToday,
                'yesterday' => $impYesterday,
                'mtd'       => $impMtd,
            ],
            'payout' => [
                'today'     => (float) ($payoutRow->pay_today ?? 0),
                'yesterday' => (float) ($payoutRow->pay_yesterday ?? 0),
                'mtd'       => (float) ($payoutRow->pay_mtd ?? 0),
            ],
            'logins' => [
                'today'     => (int) ($loginRow->login_today ?? 0),
                'yesterday' => (int) ($loginRow->login_yesterday ?? 0),
                'mtd'       => (int) ($loginRow->login_mtd ?? 0),
            ],
        ];
        });
    }

    public function realtimeMetrics(Request $request)
    {
        $kpi = $this->getKpiMetrics();
        $preset = $request->input('preset', 'last_7_days');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $chart = $this->getPerformanceData($startDate, $endDate, $preset);

        $widgets = \Illuminate\Support\Facades\Cache::remember('admin_realtime_widgets', 15, function () {
            return [
                'total_users'            => User::count(),
                'verified_users'         => User::where('status', 1)->where('ev', 1)->where('sv', 1)->count(),
                'email_unverified_users' => User::emailUnverified()->count(),
                'totalCoupon'            => Coupon::count(),
                'totalCategory'          => Category::count(),
                'totalStore'             => Store::count(),
                'total_conversions'      => Order::whereIn('status', ['confirmed', 'processing', 'shipped', 'delivered'])->count(),
                'total_orders'           => Order::count(),
                'total_clicks'           => (int) DailyMetric::sum('clicks'),
                'total_logins'           => (int) UserLogin::count(),
                'total_impressions'      => formatShortNumber((int) DailyMetric::sum('impressions')),
                'total_payout_amount'    => '₹ ' . formatShortNumber((float) PayoutRequest::whereIn('status', ['confirmed', 'paid'])->sum('amount')),
            ];
        });

        return response()->json([
            'kpi' => [
                'clicks' => [
                    'today' => formatShortNumber($kpi['clicks']['today']),
                    'yesterday' => formatShortNumber($kpi['clicks']['yesterday']),
                    'mtd' => formatShortNumber($kpi['clicks']['mtd']),
                ],
                'conversions' => [
                    'today' => formatShortNumber($kpi['conversions']['today']),
                    'yesterday' => formatShortNumber($kpi['conversions']['yesterday']),
                    'mtd' => formatShortNumber($kpi['conversions']['mtd']),
                    'cr_today' => $kpi['conversion_rate']['today'] . '%',
                    'cr_yesterday' => $kpi['conversion_rate']['yesterday'] . '%',
                    'cr_mtd' => $kpi['conversion_rate']['mtd'] . '%',
                ],
                'impressions' => [
                    'today' => formatShortNumber($kpi['impressions']['today']),
                    'yesterday' => formatShortNumber($kpi['impressions']['yesterday']),
                    'mtd' => formatShortNumber($kpi['impressions']['mtd']),
                ],
                'payout' => [
                    'today_usd' => '$ ' . convertInrToUsd($kpi['payout']['today']),
                    'today_inr' => '₹ ' . formatShortNumber($kpi['payout']['today']),
                    'yesterday_usd' => '$ ' . convertInrToUsd($kpi['payout']['yesterday']),
                    'yesterday_inr' => '₹ ' . formatShortNumber($kpi['payout']['yesterday']),
                    'mtd_usd' => '$ ' . convertInrToUsd($kpi['payout']['mtd']),
                    'mtd_inr' => '₹ ' . formatShortNumber($kpi['payout']['mtd']),
                ],
                'logins' => [
                    'today' => formatShortNumber($kpi['logins']['today']),
                    'yesterday' => formatShortNumber($kpi['logins']['yesterday']),
                    'mtd' => formatShortNumber($kpi['logins']['mtd']),
                ]
            ],
            'chart' => $chart,
            'widgets' => $widgets,
            'top_publishers' => $this->getTopPublishersData()
        ]);
    }

    public function performanceChart(Request $request)
    {
        $data = $this->getPerformanceData(
            $request->input('start_date'),
            $request->input('end_date'),
            $request->input('preset', 'last_7_days')
        );
        return response()->json($data);
    }

    public function getPerformanceData($startDateStr = null, $endDateStr = null, $preset = 'last_7_days')
    {
        $cacheKey = 'admin_perf_chart_' . $preset . '_' . ($startDateStr ?? '0') . '_' . ($endDateStr ?? '0');
        return \Illuminate\Support\Facades\Cache::remember($cacheKey, 15, function () use ($startDateStr, $endDateStr, $preset) {
            $today = Carbon::today();

        switch ($preset) {
            case 'today':
                $start = $today->copy();
                $end = $today->copy();
                break;
            case 'yesterday':
                $start = $today->copy()->subDay();
                $end = $today->copy()->subDay();
                break;
            case 'last_30_days':
                $start = $today->copy()->subDays(29);
                $end = $today->copy();
                break;
            case 'this_month':
                $start = $today->copy()->startOfMonth();
                $end = $today->copy();
                break;
            case 'last_month':
                $start = $today->copy()->subMonth()->startOfMonth();
                $end = $today->copy()->subMonth()->endOfMonth();
                break;
            case 'custom':
                try {
                    $start = $startDateStr ? Carbon::parse($startDateStr)->startOfDay() : $today->copy()->subDays(6);
                    $end = $endDateStr ? Carbon::parse($endDateStr)->startOfDay() : $today->copy();
                    if ($start->gt($end)) {
                        $temp = $start;
                        $start = $end;
                        $end = $temp;
                    }
                } catch (\Exception $e) {
                    $start = $today->copy()->subDays(6);
                    $end = $today->copy();
                }
                break;
            case 'last_7_days':
            default:
                $preset = 'last_7_days';
                $start = $today->copy()->subDays(6);
                $end = $today->copy();
                break;
        }

        $metrics = DailyMetric::whereBetween('metric_date', [$start->toDateString(), $end->toDateString()])
            ->get()
            ->keyBy(function($item) {
                return Carbon::parse($item->metric_date)->toDateString();
            });

        $payouts = PayoutRequest::whereIn('status', ['confirmed', 'paid'])
            ->whereBetween('updated_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->selectRaw("DATE(updated_at) as date, SUM(amount) as total")
            ->groupBy('date')
            ->pluck('total', 'date');

        $logins = UserLogin::whereNotNull('user_id')
            ->whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->selectRaw("DATE(created_at) as date, COUNT(DISTINCT user_id) as total")
            ->groupBy('date')
            ->pluck('total', 'date');

        $conversions = Order::whereIn('status', ['confirmed', 'processing', 'shipped', 'delivered'])
            ->whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->selectRaw("DATE(created_at) as date, COUNT(*) as total")
            ->groupBy('date')
            ->pluck('total', 'date');

        $dates = [];
        $clicksData = [];
        $conversionsData = [];
        $impressionsData = [];
        $payoutData = [];
        $loginData = [];

        $totalClicks = 0;
        $totalConversions = 0;
        $totalImpressions = 0;
        $totalPayout = 0;
        $totalLogins = 0;

        $curr = $start->copy();
        while ($curr->lte($end)) {
            $d = $curr->toDateString();
            $dates[] = $d;

            $c = isset($metrics[$d]) ? (int) $metrics[$d]->clicks : 0;
            $conv = isset($conversions[$d]) ? (int) $conversions[$d] : 0;
            $i = isset($metrics[$d]) ? (int) $metrics[$d]->impressions : 0;
            $p = isset($payouts[$d]) ? (float) $payouts[$d] : 0.0;
            $l = isset($logins[$d]) ? (int) $logins[$d] : 0;

            $clicksData[] = $c;
            $conversionsData[] = $conv;
            $impressionsData[] = $i;
            $payoutData[] = $p;
            $loginData[] = $l;

            $totalClicks += $c;
            $totalConversions += $conv;
            $totalImpressions += $i;
            $totalPayout += $p;
            $totalLogins += $l;

            $curr->addDay();
        }

        return [
            'preset' => $preset,
            'startDate' => $start->toDateString(),
            'endDate' => $end->toDateString(),
            'rangeLabel' => $start->format('Y-m-d') . ' - ' . $end->format('Y-m-d'),
            'dates' => $dates,
            'clicks' => $clicksData,
            'conversions' => $conversionsData,
            'impressions' => $impressionsData,
            'payout' => $payoutData,
            'logins' => $loginData,
            'totals' => [
                'clicks' => $totalClicks,
                'conversions' => $totalConversions,
                'impressions' => $totalImpressions,
                'payout' => round($totalPayout, 2),
                'logins' => $totalLogins,
            ]
        ];
        });
    }


    public function profile()
    {
        $pageTitle = 'Profile';
        $admin = auth('admin')->user();
        return view('admin.profile', compact('pageTitle', 'admin'));
    }

    public function profileUpdate(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'image' => ['nullable','image',new FileTypeValidate(['jpg','jpeg','png'])]
        ]);
        $user = auth('admin')->user();

        if ($request->hasFile('image')) {
            try {
                $old = $user->image;
                $user->image = fileUploader($request->image, getFilePath('adminProfile'), getFileSize('adminProfile'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        $notify[] = ['success', 'Profile has been updated successfully'];
        return to_route('admin.profile')->withNotify($notify);
    }


    public function password()
    {
        $pageTitle = 'Password Setting';
        $admin = auth('admin')->user();
        return view('admin.profile', compact('pageTitle', 'admin'));
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|min:5|confirmed',
        ]);

        $user = auth('admin')->user();
        if (!Hash::check($request->old_password, $user->password)) {
            $notify[] = ['error', 'Password doesn\'t match!!'];
            return back()->withNotify($notify);
        }
        $user->password = bcrypt($request->password);
        $user->save();
        $notify[] = ['success', 'Password changed successfully.'];
        return to_route('admin.profile')->withNotify($notify);
    }

    public function notifications(){
        $notifications = AdminNotification::orderBy('id','desc')->with('user')->paginate(getPaginate());
        $pageTitle = 'Notifications';
        return view('admin.notifications',compact('pageTitle','notifications'));
    }


    public function notificationRead($id){
        $notification = AdminNotification::findOrFail($id);
        $notification->read_status = 1;
        $notification->save();
        $url = $notification->click_url;
        if ($url == '#') {
            $url = url()->previous();
        }
        return redirect($url);
    }

    public function readAll(){
        AdminNotification::where('read_status',0)->update([
            'read_status'=>1
        ]);
        $notify[] = ['success','Notifications read successfully'];
        return back()->withNotify($notify);
    }

    public function downloadAttachment($fileHash)
    {
        $filePath = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $general = gs();
        $title = slug($general->site_name).'- attachments.'.$extension;
        $mimetype = mime_content_type($filePath);
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }



    public function getTopPublishersData()
    {
        return \Illuminate\Support\Facades\Cache::remember('admin_top_publishers', 15, function () {
            $today = Carbon::today();
            $yesterday = Carbon::yesterday();
            $startOfMonth = Carbon::now()->startOfMonth();

            $todayStart = $today->toDateTimeString();
            $yesterdayStart = $yesterday->toDateTimeString();
            $monthStart = $startOfMonth->toDateTimeString();

            // Consolidated clicks query (1 query for all 3 periods)
            $clickStats = Click::whereNotNull('user_id')
                ->where('created_at', '>=', $monthStart)
                ->selectRaw("
                    user_id,
                    COUNT(CASE WHEN created_at >= '{$todayStart}' THEN 1 END) as clicks_today,
                    COUNT(CASE WHEN created_at >= '{$yesterdayStart}' AND created_at < '{$todayStart}' THEN 1 END) as clicks_yesterday,
                    COUNT(*) as clicks_mtd
                ")
                ->groupBy('user_id')
                ->get()
                ->keyBy('user_id');

            // Consolidated orders query (1 query for all 3 periods)
            $orderStats = Order::whereNotNull('user_id')
                ->where('created_at', '>=', $monthStart)
                ->selectRaw("
                    user_id,
                    COUNT(CASE WHEN created_at >= '{$todayStart}' AND status IN ('confirmed', 'processing', 'shipped', 'delivered') THEN 1 END) as conv_today,
                    COUNT(CASE WHEN created_at >= '{$yesterdayStart}' AND created_at < '{$todayStart}' AND status IN ('confirmed', 'processing', 'shipped', 'delivered') THEN 1 END) as conv_yesterday,
                    COUNT(CASE WHEN status IN ('confirmed', 'processing', 'shipped', 'delivered') THEN 1 END) as conv_mtd,
                    SUM(CASE WHEN created_at >= '{$todayStart}' AND status IN ('confirmed', 'processing', 'shipped', 'delivered') THEN payout_amount ELSE 0 END) as pay_today,
                    SUM(CASE WHEN created_at >= '{$yesterdayStart}' AND created_at < '{$todayStart}' AND status IN ('confirmed', 'processing', 'shipped', 'delivered') THEN payout_amount ELSE 0 END) as pay_yesterday,
                    SUM(CASE WHEN status IN ('confirmed', 'processing', 'shipped', 'delivered') THEN payout_amount ELSE 0 END) as pay_mtd
                ")
                ->groupBy('user_id')
                ->get()
                ->keyBy('user_id');

            $activeUserIds = array_unique(array_merge($clickStats->keys()->toArray(), $orderStats->keys()->toArray()));

            $users = empty($activeUserIds) ? collect() : User::whereIn('id', $activeUserIds)
                ->select('id', 'firstname', 'lastname', 'username')
                ->get()
                ->keyBy('id');

            return [
                'today'     => $this->buildPublishersList($users, $clickStats, $orderStats, 'today'),
                'yesterday' => $this->buildPublishersList($users, $clickStats, $orderStats, 'yesterday'),
                'mtd'       => $this->buildPublishersList($users, $clickStats, $orderStats, 'mtd'),
            ];
        });
    }

    private function buildPublishersList($users, $clickStats, $orderStats, $period)
    {
        $result = [];
        foreach ($users as $user) {
            $userClicks = $clickStats[$user->id] ?? null;
            $userOrders = $orderStats[$user->id] ?? null;

            if ($period === 'today') {
                $c = (int) ($userClicks->clicks_today ?? 0);
                $conv = (int) ($userOrders->conv_today ?? 0);
                $payout = (float) ($userOrders->pay_today ?? 0);
            } elseif ($period === 'yesterday') {
                $c = (int) ($userClicks->clicks_yesterday ?? 0);
                $conv = (int) ($userOrders->conv_yesterday ?? 0);
                $payout = (float) ($userOrders->pay_yesterday ?? 0);
            } else {
                $c = (int) ($userClicks->clicks_mtd ?? 0);
                $conv = (int) ($userOrders->conv_mtd ?? 0);
                $payout = (float) ($userOrders->pay_mtd ?? 0);
            }

            if ($c <= 0 && $conv <= 0 && $payout <= 0) {
                continue;
            }

            $name = $user->firstname || $user->lastname ? trim($user->firstname . ' ' . $user->lastname) : $user->username;

            $result[] = [
                'id'               => $user->id,
                'name'             => "(ID: {$user->id}) " . $name,
                'raw_name'         => $name,
                'url'              => route('admin.users.detail', $user->id),
                'clicks'           => $c,
                'impressions'      => 0,
                'conversions'      => $conv,
                'payout'           => $payout,
                'clicks_formatted' => formatShortNumber($c),
                'payout_formatted' => '₹ ' . formatShortNumber($payout),
            ];
        }

        usort($result, function ($a, $b) {
            if ($b['payout'] != $a['payout']) {
                return $b['payout'] <=> $a['payout'];
            }
            if ($b['conversions'] !== $a['conversions']) {
                return $b['conversions'] <=> $a['conversions'];
            }
            return $b['clicks'] <=> $a['clicks'];
        });

        return array_slice($result, 0, 15);
    }
}
