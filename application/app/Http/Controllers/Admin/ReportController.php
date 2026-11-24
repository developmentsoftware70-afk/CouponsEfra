<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use App\Models\Transaction;
use App\Models\UserLogin;
use App\Models\User;
use App\Models\Click;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function transaction(Request $request)
    {
        $pageTitle = 'Transaction Logs';

        $remarks = Transaction::distinct('remark')->orderBy('remark')->get('remark');

        $transactions = Transaction::with('user')->orderBy('id','desc');
        if ($request->search) {
            $search = request()->search;
            $transactions = $transactions->where(function ($q) use ($search) {
                $q->where('trx', 'like', "%$search%")->orWhereHas('user', function ($user) use ($search) {
                    $user->where('username', 'like', "%$search%");
                });
            });
        }

        if ($request->type) {
            $transactions = $transactions->where('trx_type',$request->type);
        }

        if ($request->remark) {
            $transactions = $transactions->where('remark',$request->remark);
        }

        //date search
        if($request->date) {
            $date = explode('-',$request->date);
            $request->merge([
                'start_date'=> trim(@$date[0]),
                'end_date'  => trim(@$date[1])
            ]);
            $request->validate([
                'start_date'    => 'required|date_format:m/d/Y',
                'end_date'      => 'nullable|date_format:m/d/Y'
            ]);
            if($request->end_date) {
                $endDate = Carbon::parse($request->end_date)->addHours(23)->addMinutes(59)->addSecond(59);
                $transactions   = $transactions->whereBetween('created_at', [Carbon::parse($request->start_date), $endDate]);
            }else{
                $transactions   = $transactions->whereDate('created_at', Carbon::parse($request->start_date));
            }
        }

        $transactions = $transactions->paginate(getPaginate());
        return view('admin.reports.transactions', compact('pageTitle', 'transactions','remarks'));
    }

    public function loginHistory(Request $request)
    {
        $pageTitle = 'User Login History';
        $query = $this->buildLoginQuery($request);

        $browsers  = UserLogin::whereNotNull('browser')->distinct()->pluck('browser')->filter()->values();
        $osList    = UserLogin::whereNotNull('os')->distinct()->pluck('os')->filter()->values();
        $countries = UserLogin::whereNotNull('country')->distinct()->pluck('country')->filter()->values();
        $users     = User::orderBy('username')->get(['id', 'username', 'firstname', 'lastname', 'email', 'mobile']);

        $totalLogins = (clone $query)->count();
        $loginLogs = $query->paginate(getPaginate());
        return view('admin.reports.logins', compact('pageTitle', 'loginLogs', 'browsers', 'osList', 'countries', 'users', 'totalLogins'));
    }

    /**
     * Export login history filtered by specific user, date range, browser, OS, columns, etc.
     */
    public function exportLoginHistory(Request $request)
    {
        $query = $this->buildLoginQuery($request);
        $filename = 'login_history_export_' . date('Y-m-d_H-i-s') . '.csv';

        $availableColumns = [
            'sr_no'      => 'Sr. No.',
            'user_id'    => 'User ID',
            'username'   => 'Username',
            'full_name'  => 'Full Name',
            'email'      => 'Email',
            'mobile'     => 'Mobile',
            'click_id'   => 'Click ID',
            'sub_id'     => 'Sub ID',
            'ip'         => 'IP Address',
            'city'       => 'City',
            'country'    => 'Country',
            'latitude'   => 'Latitude',
            'longitude'  => 'Longitude',
            'browser'    => 'Browser',
            'os'         => 'OS',
            'login_time' => 'Login Date & Time',
        ];

        // If specific columns requested, filter to those; otherwise export all
        $selectedColumns = $request->input('columns', array_keys($availableColumns));
        if (!is_array($selectedColumns) || empty($selectedColumns)) {
            $selectedColumns = array_keys($availableColumns);
        }

        $csvHeaders = [];
        foreach ($selectedColumns as $colKey) {
            if (isset($availableColumns[$colKey])) {
                $csvHeaders[] = $availableColumns[$colKey];
            }
        }

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        return response()->stream(function () use ($query, $selectedColumns, $csvHeaders) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

            fputcsv($handle, $csvHeaders);

            $srNo = 1;
            $query->chunk(500, function ($logs) use ($handle, &$srNo, $selectedColumns) {
                foreach ($logs as $log) {
                    $fullName = trim((@$log->user->firstname ?? '') . ' ' . (@$log->user->lastname ?? ''));
                    $mobile   = !empty(@$log->user->mobile) ? ((@$log->user->dial_code ? '+' . @$log->user->dial_code . ' ' : '') . @$log->user->mobile) : '';

                    $dataMap = [
                        'sr_no'      => $srNo++,
                        'user_id'    => $log->user_id ? ('#' . $log->user_id) : 'N/A',
                        'username'   => @$log->user->username ?? 'Deleted User',
                        'full_name'  => $fullName ?: '—',
                        'email'      => @$log->user->email ?? '—',
                        'mobile'     => $mobile ?: '—',
                        'click_id'   => $log->click_id ?? '—',
                        'sub_id'     => $log->sub_id ?? '—',
                        'ip'         => $log->user_ip ?? '—',
                        'city'       => $log->city ?? '—',
                        'country'    => $log->country ?? '—',
                        'latitude'   => $log->latitude ?? '—',
                        'longitude'  => $log->longitude ?? '—',
                        'browser'    => $log->browser ?? 'Unknown',
                        'os'         => $log->os ?? 'Unknown',
                        'login_time' => $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : '—',
                    ];

                    $row = [];
                    foreach ($selectedColumns as $colKey) {
                        $row[] = $dataMap[$colKey] ?? '';
                    }
                    fputcsv($handle, $row);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Brand-wise user clicks report & analytics.
     */
    public function brandClicks(Request $request)
    {
        $pageTitle = 'Brand-Wise User Clicks Report';
        $query = $this->buildBrandClicksQuery($request);

        // Top summary metrics
        $totalClicks = (clone $query)->count();
        $totalConverted = (clone $query)->whereNotNull('order_id')->count();
        $crRate = $totalClicks > 0 ? round(($totalConverted / $totalClicks) * 100, 1) : 0;

        // Top clicked brand in this query
        $topStoreId = (clone $query)->reorder()->whereNotNull('store_id')->selectRaw('store_id, COUNT(*) as c')->groupBy('store_id')->orderByDesc('c')->value('store_id');
        $topStore = $topStoreId ? Store::find($topStoreId) : null;

        // Stores & Users list for filter dropdowns
        $stores = Store::orderBy('name')->get(['id', 'name', 'image']);
        $users  = User::orderBy('username')->get(['id', 'username', 'email']);

        $clicks = $query->paginate(getPaginate());
        return view('admin.reports.brand_clicks', compact('pageTitle', 'clicks', 'stores', 'users', 'totalClicks', 'totalConverted', 'crRate', 'topStore'));
    }

    /**
     * Export brand clicks report to CSV with filters applied.
     */
    public function exportBrandClicks(Request $request)
    {
        $query = $this->buildBrandClicksQuery($request);
        $filename = 'brand_clicks_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        return response()->stream(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

            fputcsv($handle, [
                'Sr. No.',
                'Click ID',
                'Sub ID',
                'User ID',
                'Username',
                'User Email',
                'Brand / Store',
                'Campaign / Offer',
                'Device Type',
                'Browser',
                'OS',
                'IP Address',
                'Converted (Order Placed)',
                'Order ID',
                'Destination URL',
                'Clicked Date & Time',
            ]);

            $srNo = 1;
            $query->chunk(500, function ($logs) use ($handle, &$srNo) {
                foreach ($logs as $log) {
                    fputcsv($handle, [
                        $srNo++,
                        $log->click_id,
                        $log->sub_id ?: ('sub_' . substr($log->click_id, 4)),
                        $log->user_id ? ('#' . $log->user_id) : 'Guest',
                        @$log->user->username ?? 'Deleted / Guest',
                        @$log->user->email ?? '—',
                        @$log->store->name ?? (@$log->coupon->store->name ?? 'Direct Store'),
                        $log->campaign ?? (@$log->coupon->title ?? 'General Offer'),
                        ucfirst($log->device_type ?? 'desktop'),
                        $log->browser ?? 'Unknown',
                        $log->os ?? 'Unknown',
                        $log->ip_address ?? '—',
                        $log->order_id ? 'YES (Converted)' : 'NO (Click Only)',
                        $log->order_id ? ('#' . $log->order_id) : '—',
                        $log->destination_url ?? '—',
                        $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : '—',
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Query builder for brand clicks filtering.
     */
    protected function buildBrandClicksQuery(Request $request)
    {
        $clicks = Click::orderBy('id', 'desc')->with(['user', 'store', 'coupon', 'deal', 'order']);

        if ($request->filled('user_id')) {
            $userId = trim($request->user_id);
            if (is_numeric($userId)) {
                $clicks->where('user_id', (int) $userId);
            } else {
                $clicks->whereHas('user', function ($q) use ($userId) {
                    $q->where('username', $userId)->orWhere('email', $userId);
                });
            }
        }

        if ($request->filled('store_id')) {
            $clicks->where('store_id', (int) $request->store_id);
        }

        if ($request->filled('device_type')) {
            $clicks->where('device_type', trim($request->device_type));
        }

        if ($request->filled('converted')) {
            if ($request->converted === 'yes') {
                $clicks->whereNotNull('order_id');
            } elseif ($request->converted === 'no') {
                $clicks->whereNull('order_id');
            }
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end   = Carbon::parse($request->end_date)->endOfDay();
            $clicks->whereBetween('created_at', [$start, $end]);
        } elseif ($request->filled('start_date')) {
            $clicks->whereDate('created_at', '>=', Carbon::parse($request->start_date));
        } elseif ($request->filled('end_date')) {
            $clicks->whereDate('created_at', '<=', Carbon::parse($request->end_date));
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $clicks->where(function ($q) use ($search) {
                $q->where('click_id', 'like', "%$search%")
                    ->orWhere('sub_id', 'like', "%$search%")
                    ->orWhere('ip_address', 'like', "%$search%")
                    ->orWhere('destination_url', 'like', "%$search%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('username', 'like', "%$search%")
                            ->orWhere('email', 'like', "%$search%")
                            ->orWhere('id', $search);
                    })
                    ->orWhereHas('store', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%$search%");
                    });
            });
        }

        return $clicks;
    }

    public function loginIpHistory($ip)
    {
        $pageTitle = 'Login by - ' . $ip;
        $loginLogs = UserLogin::where('user_ip',$ip)->orderBy('id','desc')->with('user')->paginate(getPaginate());
        $users     = User::orderBy('username')->get(['id', 'username', 'firstname', 'lastname', 'email', 'mobile']);
        return view('admin.reports.logins', compact('pageTitle', 'loginLogs','ip', 'users'));
    }

    public function notificationHistory(Request $request){
        $pageTitle = 'Notification History';
        $logs = NotificationLog::orderBy('id','desc');
        $search = $request->search;
        if ($search) {
            $logs = $logs->whereHas('user', function ($user) use ($search) {
                $user->where('username', 'like',"%$search%");
            });
        }
        $logs = $logs->with('user')->paginate(getPaginate());
        return view('admin.reports.notification_history', compact('pageTitle','logs'));
    }

    public function emailDetails($id){
        $pageTitle = 'Email Details';
        $email = NotificationLog::findOrFail($id);
        return view('admin.reports.email_details', compact('pageTitle','email'));
    }

    /**
     * Reusable query builder for login history filtering.
     */
    protected function buildLoginQuery(Request $request)
    {
        $loginLogs = UserLogin::orderBy('id', 'desc')->with('user');

        if ($request->filled('user_id')) {
            $userId = trim($request->user_id);
            if (is_numeric($userId)) {
                $loginLogs->where('user_id', (int) $userId);
            } else {
                $loginLogs->whereHas('user', function ($q) use ($userId) {
                    $q->where('username', $userId)->orWhere('email', $userId);
                });
            }
        }

        if ($request->filled('email')) {
            $email = trim($request->email);
            $loginLogs->whereHas('user', function ($q) use ($email) {
                $q->where('email', 'like', "%$email%");
            });
        }

        if ($request->filled('mobile')) {
            $mobile = trim($request->mobile);
            $loginLogs->whereHas('user', function ($q) use ($mobile) {
                $q->where('mobile', 'like', "%$mobile%");
            });
        }

        if ($request->filled('click_id')) {
            $loginLogs->where('click_id', 'like', '%' . trim($request->click_id) . '%');
        }

        if ($request->filled('sub_id')) {
            $loginLogs->where('sub_id', 'like', '%' . trim($request->sub_id) . '%');
        }

        if ($request->filled('browser')) {
            $loginLogs->where('browser', trim($request->browser));
        }

        if ($request->filled('os')) {
            $loginLogs->where('os', trim($request->os));
        }

        if ($request->filled('country')) {
            $loginLogs->where('country', 'like', '%' . trim($request->country) . '%');
        }
        if ($request->filled('city')) {
            $loginLogs->where('city', 'like', '%' . trim($request->city) . '%');
        }

        if ($request->filled('ip')) {
            $loginLogs->where('user_ip', trim($request->ip));
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end   = Carbon::parse($request->end_date)->endOfDay();
            $loginLogs->whereBetween('created_at', [$start, $end]);
        } elseif ($request->filled('start_date')) {
            $loginLogs->whereDate('created_at', '>=', Carbon::parse($request->start_date));
        } elseif ($request->filled('end_date')) {
            $loginLogs->whereDate('created_at', '<=', Carbon::parse($request->end_date));
        }

        if ($request->filled('period')) {
            switch ($request->period) {
                case 'today':
                    $loginLogs->whereDate('created_at', Carbon::today());
                    break;
                case 'yesterday':
                    $loginLogs->whereDate('created_at', Carbon::yesterday());
                    break;
                case '7days':
                    $loginLogs->where('created_at', '>=', Carbon::now()->subDays(7));
                    break;
                case '30days':
                    $loginLogs->where('created_at', '>=', Carbon::now()->subDays(30));
                    break;
                case 'this_month':
                    $loginLogs->where('created_at', '>=', Carbon::now()->startOfMonth());
                    break;
            }
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $loginLogs->where(function ($q) use ($search) {
                $q->whereHas('user', function ($query) use ($search) {
                    $query->where('username', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('mobile', 'like', "%$search%")
                        ->orWhere('firstname', 'like', "%$search%")
                        ->orWhere('lastname', 'like', "%$search%")
                        ->orWhere('id', $search);
                })
                ->orWhere('user_ip', 'like', "%$search%")
                ->orWhere('click_id', 'like', "%$search%")
                ->orWhere('sub_id', 'like', "%$search%")
                ->orWhere('city', 'like', "%$search%")
                ->orWhere('country', 'like', "%$search%")
                ->orWhere('browser', 'like', "%$search%")
                ->orWhere('os', 'like', "%$search%");
            });
        }

        return $loginLogs;
    }
}
