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

        // User Info
        $widget['total_users']             = User::count();
        $widget['verified_users']          = User::where('status', 1)->where('ev', 1)->where('sv', 1)->count();
        $widget['email_unverified_users']  = User::emailUnverified()->count();
        $widget['mobile_unverified_users'] = User::mobileUnverified()->count();

        $widget['totalCoupon']   = Coupon::count();
        $widget['totalCategory'] = Category::count();
        $widget['totalStore']    = Store::count();
        $widget['total_conversions'] = Order::whereIn('status', ['confirmed', 'processing', 'shipped', 'delivered'])->count();
        $widget['total_orders']      = Order::count();

        // Key Real-time Metrics: Clicks, Impressions, Payout, Logins (Today, Yesterday, MTD)
        $kpiMetrics = $this->getKpiMetrics();

        // Fallbacks and extra widgets
        $widget['total_clicks']        = (int) DailyMetric::sum('clicks');
        $widget['total_logins']        = (int) UserLogin::count();
        $widget['total_payout_amount'] = (float) PayoutRequest::whereIn('status', ['confirmed', 'paid'])->sum('amount');
        $widget['total_impressions']   = (int) DailyMetric::sum('impressions');

        $performanceData = $this->getPerformanceData();

        $newTickets = SupportTicket::with('user')->orderBy('created_at', 'desc')->whereStatus(0)->limit(5)->get();
        $topPublishers = $this->getTopPublishersData();
        return view('admin.dashboard', compact('pageTitle', 'widget', 'newTickets', 'kpiMetrics', 'performanceData', 'topPublishers'));
    }

    public function getKpiMetrics()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $startOfMonth = Carbon::now()->startOfMonth();

        $clicksToday = (int) (DailyMetric::where('metric_date', $today->toDateString())->value('clicks') ?? 0);
        $clicksYesterday = (int) (DailyMetric::where('metric_date', $yesterday->toDateString())->value('clicks') ?? 0);
        $clicksMtd = (int) DailyMetric::where('metric_date', '>=', $startOfMonth->toDateString())->sum('clicks');

        $conversionsToday = Order::whereIn('status', ['confirmed', 'processing', 'shipped', 'delivered'])->whereDate('created_at', $today)->count();
        $conversionsYesterday = Order::whereIn('status', ['confirmed', 'processing', 'shipped', 'delivered'])->whereDate('created_at', $yesterday)->count();
        $conversionsMtd = Order::whereIn('status', ['confirmed', 'processing', 'shipped', 'delivered'])->where('created_at', '>=', $startOfMonth)->count();

        $crToday = $clicksToday > 0 ? round(($conversionsToday / $clicksToday) * 100, 1) : 0;
        $crYesterday = $clicksYesterday > 0 ? round(($conversionsYesterday / $clicksYesterday) * 100, 1) : 0;
        $crMtd = $clicksMtd > 0 ? round(($conversionsMtd / $clicksMtd) * 100, 1) : 0;

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
                'today'     => (int) (DailyMetric::where('metric_date', $today->toDateString())->value('impressions') ?? 0),
                'yesterday' => (int) (DailyMetric::where('metric_date', $yesterday->toDateString())->value('impressions') ?? 0),
                'mtd'       => (int) DailyMetric::where('metric_date', '>=', $startOfMonth->toDateString())->sum('impressions'),
            ],
            'payout' => [
                'today'     => (float) PayoutRequest::whereIn('status', ['confirmed', 'paid'])->whereDate('updated_at', $today)->sum('amount'),
                'yesterday' => (float) PayoutRequest::whereIn('status', ['confirmed', 'paid'])->whereDate('updated_at', $yesterday)->sum('amount'),
                'mtd'       => (float) PayoutRequest::whereIn('status', ['confirmed', 'paid'])->where('updated_at', '>=', $startOfMonth)->sum('amount'),
            ],
            'logins' => [
                'today'     => (int) UserLogin::where('created_at', '>=', $today)->whereNotNull('user_id')->distinct('user_id')->count('user_id'),
                'yesterday' => (int) UserLogin::whereBetween('created_at', [$yesterday, $today])->whereNotNull('user_id')->distinct('user_id')->count('user_id'),
                'mtd'       => (int) UserLogin::where('created_at', '>=', $startOfMonth)->whereNotNull('user_id')->distinct('user_id')->count('user_id'),
            ],
        ];
    }

    public function realtimeMetrics(Request $request)
    {
        $kpi = $this->getKpiMetrics();
        $preset = $request->input('preset', 'last_7_days');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $chart = $this->getPerformanceData($startDate, $endDate, $preset);

        $widgets = [
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
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $startOfMonth = Carbon::now()->startOfMonth();

        return [
            'today'     => $this->fetchPublishersForPeriod($today, null),
            'yesterday' => $this->fetchPublishersForPeriod($yesterday, $today),
            'mtd'       => $this->fetchPublishersForPeriod($startOfMonth, null),
        ];
    }

    private function fetchPublishersForPeriod($start, $end = null)
    {
        $clickQuery = Click::select('user_id', DB::raw('count(*) as clicks_count'))
            ->whereNotNull('user_id');
        if ($end) {
            $clickQuery->whereBetween('created_at', [$start, $end]);
        } else {
            $clickQuery->where('created_at', '>=', $start);
        }
        $clicksByUser = $clickQuery->groupBy('user_id')->pluck('clicks_count', 'user_id')->toArray();

        $orderQuery = Order::select(
                'user_id',
                DB::raw('count(*) as total_orders'),
                DB::raw("count(case when status in ('confirmed', 'processing', 'shipped', 'delivered') then 1 end) as conversions_count"),
                DB::raw("sum(case when status in ('confirmed', 'processing', 'shipped', 'delivered') then payout_amount else 0 end) as total_payout")
            )->whereNotNull('user_id');
        if ($end) {
            $orderQuery->whereBetween('created_at', [$start, $end]);
        } else {
            $orderQuery->where('created_at', '>=', $start);
        }
        $ordersByUser = $orderQuery->groupBy('user_id')->get()->keyBy('user_id')->toArray();

        $activeUserIds = array_unique(array_merge(array_keys($clicksByUser), array_keys($ordersByUser)));

        if (empty($activeUserIds)) {
            $users = User::select('id', 'firstname', 'lastname', 'username')
                ->where('status', 1)
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get();

            $result = [];
            foreach ($users as $user) {
                $name = $user->firstname || $user->lastname ? trim($user->firstname . ' ' . $user->lastname) : $user->username;
                $result[] = [
                    'id'               => $user->id,
                    'name'             => "(ID: {$user->id}) " . $name,
                    'raw_name'         => $name,
                    'url'              => route('admin.users.detail', $user->id),
                    'clicks'           => 0,
                    'impressions'      => 0,
                    'conversions'      => 0,
                    'payout'           => 0,
                    'clicks_formatted' => '0',
                    'payout_formatted' => '₹ 0',
                ];
            }
            return $result;
        }

        $users = User::select('id', 'firstname', 'lastname', 'username')
            ->whereIn('id', $activeUserIds)
            ->get();

        $result = [];
        foreach ($users as $user) {
            $c = $clicksByUser[$user->id] ?? 0;
            $orderData = $ordersByUser[$user->id] ?? null;
            $conv = $orderData['conversions_count'] ?? 0;
            $payout = (float) ($orderData['total_payout'] ?? 0);
            $name = $user->firstname || $user->lastname ? trim($user->firstname . ' ' . $user->lastname) : $user->username;

            $result[] = [
                'id'               => $user->id,
                'name'             => "(ID: {$user->id}) " . $name,
                'raw_name'         => $name,
                'url'              => route('admin.users.detail', $user->id),
                'clicks'           => $c,
                'impressions'      => 0,
                'conversions'      => (int) $conv,
                'payout'           => $payout,
                'clicks_formatted' => formatShortNumber($c),
                'payout_formatted' => '₹ ' . formatShortNumber($payout),
            ];
        }

        usort($result, function ($a, $b) {
            if ($b['conversions'] !== $a['conversions']) {
                return $b['conversions'] <=> $a['conversions'];
            }
            if ($b['clicks'] !== $a['clicks']) {
                return $b['clicks'] <=> $a['clicks'];
            }
            return $b['payout'] <=> $a['payout'];
        });

        return array_slice($result, 0, 10);
    }
}
