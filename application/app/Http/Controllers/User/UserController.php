<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Lib\GoogleAuthenticator;
use App\Models\Coupon;
use App\Models\Form;
use App\Models\Deposit;
use App\Models\Store;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\EarningReport;
use App\Models\User;
use App\Models\Order;
use App\Models\PayoutRequest;
use App\Models\AdminNotification;
use Illuminate\Pagination\LengthAwarePaginator;

class UserController extends Controller
{
    public function home()
    {
        $pageTitle = 'Dashboard';
        $user = auth()->user();
        $couponsCount = Coupon::where('user_id',$user->id)->count();
        $wishLists = Wishlist::with('coupon')->where('user_id', $user->id)->latest()->paginate(getPaginate(10));
        $orders = Order::where('user_id', $user->id)->latest()->take(3)->get();
        $totalOrdersCount = Order::where('user_id', $user->id)->count();
        $earningReportList = EarningReport::with('user')->where('user_id', $user->id)->latest()->paginate(getPaginate(10));
        $pendingCoupon = Coupon::where('status', 0)->where('user_id',$user->id)->count();
        $activeCoupon = Coupon::where('status', 1)->where('user_id',$user->id)->count();
        $storeCount = Store::where('user_id',$user->id)->count();
        $activeStore = Store::where('status', 1)->where('user_id',$user->id)->count();
        $pendingStore = Store::where('status', 0)->where('user_id',$user->id)->count();
        $coupons = Coupon::with(['category', 'store'])->where('user_id',$user->id)->latest()->paginate(getPaginate(10));
        // Real Calculations for the 3 Dashboard Cards:
        // 1. Total Earning: Jitna kamaya (confirmed cashback from orders + reports)
        $confirmedCashback = (float) Order::where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->sum('payout_amount');
        $legacyEarnings = (float) EarningReport::where('user_id', $user->id)->sum('earning_amount');
        $totalEarningsAmount = $confirmedCashback + $legacyEarnings;

        // 2. Pending Amount: Jo abhi pending hai (pending orders cashback + pending payouts + reports)
        $pendingOrdersCashback = (float) Order::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'unreviewed'])
            ->sum('payout_amount');
        $pendingPayouts = (float) PayoutRequest::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'under_review', 'unreviewed'])
            ->sum('amount');
        $legacyPending = (float) EarningReport::where('user_id', $user->id)
            ->where('payment_status', 0)
            ->sum('earning_amount');
        $pendingPaymentAmount = $pendingOrdersCashback + $pendingPayouts + $legacyPending;

        // 3. Disbursed Amount: Jo approval ho gaya matlab nikal gaya hai (paid / confirmed payouts)
        $paidPayouts = (float) PayoutRequest::where('user_id', $user->id)
            ->whereIn('status', ['confirmed', 'paid'])
            ->sum('amount');
        $legacyDisbursed = (float) EarningReport::where('user_id', $user->id)
            ->where('payment_status', 2)
            ->sum('earning_amount');
        $disbursedAmount = $paidPayouts + $legacyDisbursed;

        return view($this->activeTemplate . 'user.dashboard', compact(
            'pageTitle', 'user', 'wishLists', 'orders', 'totalOrdersCount', 'earningReportList',
            'coupons', 'couponsCount', 'activeCoupon', 'pendingCoupon',
            'storeCount', 'pendingStore', 'activeStore',
            'totalEarningsAmount', 'pendingOrdersCashback', 'pendingPaymentAmount', 'disbursedAmount'
        ));
    }

    public function orders()
    {
        $pageTitle = 'Visited Orders';
        $user = auth()->user();
        $orders = Order::where('user_id', $user->id)->latest()->paginate(getPaginate(10));
        return view($this->activeTemplate . 'user.orders', compact('pageTitle', 'orders'));
    }

    public function payouts()
    {
        $pageTitle = 'Payouts & Withdrawals';
        $user = auth()->user();

        // 1. Total Confirmed Cashback from orders
        $confirmedOrders = Order::where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->get();

        $totalConfirmedCashback = $confirmedOrders->sum('payout_amount');
        $pendingCashback = (float) Order::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'unreviewed'])
            ->sum('payout_amount');

        // 2. Net-45 (15th of next month) eligibility calculation
        $eligibleCashback = 0;
        $upcomingCashback = 0;
        $nextEligibleDate = null;

        foreach ($confirmedOrders as $order) {
            $payoutDate = \Carbon\Carbon::parse($order->created_at)->addMonthNoOverflow()->startOfMonth()->addDays(14);
            if (now()->startOfDay()->gte($payoutDate->startOfDay())) {
                $eligibleCashback += (float)$order->payout_amount;
            } else {
                $upcomingCashback += (float)$order->payout_amount;
                if (!$nextEligibleDate || $payoutDate->lt($nextEligibleDate)) {
                    $nextEligibleDate = $payoutDate;
                }
            }
        }

        // 3. Amount already requested or paid
        $alreadyRequested = PayoutRequest::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'under_review', 'confirmed', 'paid'])
            ->sum('amount');

        $availableBalance = max(0, $totalConfirmedCashback - $alreadyRequested);

        // Current Month Cycle & Payable Date
        $currentMonthCycle  = now()->format('F Y');
        $currentPayableDate = now()->addMonthNoOverflow()->startOfMonth()->addDays(14);

        // Payout history
        $payoutRequests = PayoutRequest::where('user_id', $user->id)->latest()->paginate(getPaginate(10));

        return view($this->activeTemplate . 'user.payouts', compact(
            'pageTitle',
            'user',
            'totalConfirmedCashback',
            'pendingCashback',
            'eligibleCashback',
            'upcomingCashback',
            'nextEligibleDate',
            'availableBalance',
            'alreadyRequested',
            'currentMonthCycle',
            'currentPayableDate',
            'payoutRequests'
        ));
    }

    public function payoutRequest(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'amount'              => 'required|numeric|min:1',
            'payment_method'      => 'required|in:bank_transfer,upi',
            'account_holder_name' => 'required_if:payment_method,bank_transfer|nullable|string|max:100',
            'bank_name'           => 'required_if:payment_method,bank_transfer|nullable|string|max:100',
            'account_number'      => 'required_if:payment_method,bank_transfer|nullable|string|max:50',
            'ifsc_code'           => 'required_if:payment_method,bank_transfer|nullable|string|max:20',
            'upi_id'              => 'required_if:payment_method,upi|nullable|string|max:100',
        ]);

        $totalConfirmedCashback = Order::where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->sum('payout_amount');

        $alreadyRequested = PayoutRequest::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'under_review', 'confirmed', 'paid'])
            ->sum('amount');

        $availableBalance = max(0, $totalConfirmedCashback - $alreadyRequested);

        if ($availableBalance > 0 && $request->amount > $availableBalance) {
            $notify[] = ['error', 'Insufficient balance. You can request up to ₹' . showAmount($availableBalance)];
            return back()->withNotify($notify)->withInput();
        }

        $monthCycle  = now()->format('F Y');
        $payableDate = now()->addMonthNoOverflow()->startOfMonth()->addDays(14)->toDateString();

        $payout = PayoutRequest::create([
            'user_id'             => $user->id,
            'amount'              => $request->amount,
            'payment_method'      => $request->payment_method,
            'account_holder_name' => $request->account_holder_name,
            'bank_name'           => $request->bank_name,
            'account_number'      => $request->account_number,
            'ifsc_code'           => $request->ifsc_code,
            'upi_id'              => $request->upi_id,
            'status'              => 'pending',
            'month_cycle'         => $monthCycle,
            'eligible_at'         => $payableDate,
        ]);

        try {
            $adminNotification = new AdminNotification();
            $adminNotification->user_id = $user->id;
            $adminNotification->title = 'New Payout Request: ₹' . showAmount($payout->amount) . ' from ' . $user->username;
            $adminNotification->click_url = urlPath('admin.payouts.index');
            $adminNotification->save();
        } catch (\Exception $e) {}

        // Auto-save user's default payout account for future payouts
        $user->payout_method = $request->payment_method;
        if ($request->payment_method === 'bank_transfer') {
            $user->bank_name           = $request->bank_name;
            $user->bank_account_name   = $request->account_holder_name;
            $user->bank_account_number = $request->account_number;
            $user->bank_ifsc           = strtoupper($request->ifsc_code);
        } else {
            $user->upi_id              = $request->upi_id;
        }
        $user->save();

        $notify[] = ['success', 'Payout request of ₹' . showAmount($payout->amount) . ' submitted successfully! Payment will be processed after 15th ' . now()->addMonthNoOverflow()->format('F') . '.'];
        return back()->withNotify($notify);
    }

    public function updatePayoutAccount(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'payment_method'      => 'required|in:bank_transfer,upi',
            'account_holder_name' => 'required_if:payment_method,bank_transfer|nullable|string|max:100',
            'bank_name'           => 'required_if:payment_method,bank_transfer|nullable|string|max:100',
            'account_number'      => 'required_if:payment_method,bank_transfer|nullable|string|max:50',
            'ifsc_code'           => 'required_if:payment_method,bank_transfer|nullable|string|max:20',
            'upi_id'              => 'required_if:payment_method,upi|nullable|string|max:100',
        ]);

        $user->payout_method = $request->payment_method;
        if ($request->payment_method === 'bank_transfer') {
            $user->bank_name           = $request->bank_name;
            $user->bank_account_name   = $request->account_holder_name;
            $user->bank_account_number = $request->account_number;
            $user->bank_ifsc           = strtoupper($request->ifsc_code);
        } else {
            $user->upi_id              = $request->upi_id;
        }
        $user->save();

        // Also update any pending/unreviewed payout requests with the new details if chosen
        if ($request->filled('apply_to_pending') && $request->apply_to_pending == '1') {
            $pendingPayouts = PayoutRequest::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'under_review', 'unreviewed'])
                ->get();
            foreach ($pendingPayouts as $p) {
                $p->payment_method      = $request->payment_method;
                $p->account_holder_name = $request->account_holder_name;
                $p->bank_name           = $request->bank_name;
                $p->account_number      = $request->account_number;
                $p->ifsc_code           = strtoupper($request->ifsc_code);
                $p->upi_id              = $request->upi_id;
                $p->save();
            }
        }

        $notify[] = ['success', 'Your default payout account details have been successfully updated!'];
        return back()->withNotify($notify);
    }

    public function updatePayoutRequest(Request $request, $id)
    {
        $user = auth()->user();
        $payout = PayoutRequest::where('user_id', $user->id)->findOrFail($id);

        if (!in_array(strtolower($payout->status), ['pending', 'under_review', 'unreviewed'])) {
            $notify[] = ['error', 'This payout request has already been processed or cancelled and cannot be edited.'];
            return back()->withNotify($notify);
        }

        $request->validate([
            'payment_method'      => 'required|in:bank_transfer,upi',
            'account_holder_name' => 'required_if:payment_method,bank_transfer|nullable|string|max:100',
            'bank_name'           => 'required_if:payment_method,bank_transfer|nullable|string|max:100',
            'account_number'      => 'required_if:payment_method,bank_transfer|nullable|string|max:50',
            'ifsc_code'           => 'required_if:payment_method,bank_transfer|nullable|string|max:20',
            'upi_id'              => 'required_if:payment_method,upi|nullable|string|max:100',
        ]);

        $payout->payment_method      = $request->payment_method;
        $payout->account_holder_name = $request->account_holder_name;
        $payout->bank_name           = $request->bank_name;
        $payout->account_number      = $request->account_number;
        $payout->ifsc_code           = strtoupper($request->ifsc_code);
        $payout->upi_id              = $request->upi_id;
        $payout->save();

        // Also update default account on user profile
        $user->payout_method = $request->payment_method;
        if ($request->payment_method === 'bank_transfer') {
            $user->bank_name           = $request->bank_name;
            $user->bank_account_name   = $request->account_holder_name;
            $user->bank_account_number = $request->account_number;
            $user->bank_ifsc           = strtoupper($request->ifsc_code);
        } else {
            $user->upi_id              = $request->upi_id;
        }
        $user->save();

        $notify[] = ['success', 'Payout request #' . $payout->id . ' account details updated successfully!'];
        return back()->withNotify($notify);
    }

    public function depositHistory(Request $request)
    {
        $pageTitle = 'Deposit History';
        $deposits = auth()->user()->deposits();
        if ($request->search) {
            $deposits = $deposits->where('trx',$request->search);
        }
        $deposits = $deposits->with(['gateway'])->orderBy('id','desc')->paginate(getPaginate());
        return view($this->activeTemplate.'user.deposit_history', compact('pageTitle', 'deposits'));
    }

    public function show2faForm()
    { 
        $general = gs();
        $ga = new GoogleAuthenticator();
        $user = auth()->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . $general->site_name, $secret);
        $pageTitle = '2FA Setting';
        return view($this->activeTemplate.'user.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user,$request->code,$request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts = 1;
            $user->save();
            $notify[] = ['success', 'Google authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }

    public function disable2fa(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $user = auth()->user();
        $response = verifyG2fa($user,$request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts = 0;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }

    public function transactions(Request $request)
    {
        $pageTitle = 'Transactions';
        $remarks = Transaction::distinct('remark')->orderBy('remark')->get('remark');
        $transactions = Transaction::where('user_id',auth()->id());

        if ($request->search) {
            $transactions = $transactions->where('trx',$request->search);
        }

        if ($request->type) {
            $transactions = $transactions->where('trx_type',$request->type);
        }

        if ($request->remark) {
            $transactions = $transactions->where('remark',$request->remark);
        }

        $transactions = $transactions->orderBy('id','desc')->paginate(getPaginate());
        return view($this->activeTemplate.'user.transactions', compact('pageTitle','transactions','remarks'));
    }

    public function attachmentDownload($fileHash)
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

    public function userData()
    {
        $user = auth()->user();
        if ($user->reg_step == 1) {
            return to_route('user.home');
        }
        $pageTitle = 'User Data';
        return view($this->activeTemplate.'user.user_data', compact('pageTitle','user'));
    }

    public function userDataSubmit(Request $request)
    {
        $user = auth()->user();
        if ($user->reg_step == 1) {
            return to_route('user.home');
        }
        $request->validate([
            'firstname'=>'required',
            'lastname'=>'required',
        ]);
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->address = [
            'country'=>@$user->address->country,
            'address'=>$request->address,
            'state'=>$request->state,
            'zip'=>$request->zip,
            'city'=>$request->city,
        ];
        $user->reg_step = 1;
        $user->save();

        $notify[] = ['success','Registration process completed successfully'];
        return to_route('user.home')->withNotify($notify);

    }

    public function addWishlist(Request $request) {
        $couponId = $request->couponId;
        if (auth()->check()) {
            $userId = auth()->id();
            $wishlist = Wishlist::where('user_id', $userId)->where('coupon_id', $couponId)->first();
            if (isset($wishlist)) {
                return response()->json(['error' => 'Coupon already exists in your wishlist']);
            }
            $wishlist = new Wishlist();
            $wishlist->user_id = $userId;
            $wishlist->coupon_id = $couponId;
            $wishlist->save();

            return response()->json([
                'message' => 'Coupon added to wishlist',
            ], 200);
        }
        return response()->json(['error' => 'Please log in to your account']);
    }

    public function removeWishlist($id) {
        $wishlist = Wishlist::findOrFail($id);
        $wishlist->delete();
        $notify[] = ['success','Wishlist remove has been successfully'];
        return back()->withNotify($notify);
    }

    public function myWishlist()
    {
        $pageTitle = 'Wishlists';
        $user = auth()->user();
        $wishLists = Wishlist::with('coupon')->where('user_id', $user->id)->latest()->paginate(getPaginate());
        return view($this->activeTemplate . 'user.wishlist', compact('pageTitle', 'user', 'wishLists'));
    }
    public function myEarnings(Request $request)
    {
        $pageTitle = 'My Earnings';
        $user = auth()->user();

        // 1. All Orders for this user (with store loaded)
        $orders = Order::with('store')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // 2. All EarningReports for this user (if any)
        $reports = EarningReport::where('user_id', $user->id)
            ->latest()
            ->get();

        // 3. Totals
        $confirmedCashback = (float) $orders->where('status', 'confirmed')->sum('payout_amount');
        $pendingCashback = (float) $orders->whereIn('status', ['pending', 'unreviewed'])->sum('payout_amount');
        $reportsEarnings = (float) $reports->sum('earning_amount');
        $reportsPending = (float) $reports->where('payment_status', 0)->sum('earning_amount');

        $totalEarnings = $confirmedCashback + $reportsEarnings;
        $pendingEarnings = $pendingCashback + $reportsPending;

        $requestedPayouts = (float) PayoutRequest::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'under_review', 'confirmed', 'paid'])
            ->sum('amount');
        $availableBalance = max(0, $totalEarnings - $requestedPayouts);

        // 4. "Kaha Kaha Se Kitne Paise Kamaye" - Store / Brand Breakdown
        $storeBreakdown = collect();

        foreach ($orders as $order) {
            $storeName = $order->store_name;
            $storeLogo = $order->store_logo;
            $payout = (float) $order->payout_amount;
            $amount = (float) $order->amount;
            $isConfirmed = strtolower($order->status) === 'confirmed';

            if (!$storeBreakdown->has($storeName)) {
                $storeBreakdown->put($storeName, [
                    'name' => $storeName,
                    'logo' => $storeLogo,
                    'confirmed_earned' => 0.0,
                    'pending_earned' => 0.0,
                    'total_earned' => 0.0,
                    'total_sale' => 0.0,
                    'order_count' => 0,
                    'store_id' => $order->merchant_id,
                ]);
            }

            $item = $storeBreakdown->get($storeName);
            if ($isConfirmed) {
                $item['confirmed_earned'] += $payout;
            } else {
                $item['pending_earned'] += $payout;
            }
            $item['total_earned'] += $payout;
            $item['total_sale'] += $amount;
            $item['order_count'] += 1;
            if (empty($item['logo']) && $storeLogo) {
                $item['logo'] = $storeLogo;
            }
            $storeBreakdown->put($storeName, $item);
        }

        foreach ($reports as $rep) {
            $cName = $rep->compaign_name ?: ($rep->deal_type ?: 'Campaign Bonus');
            $earned = (float) $rep->earning_amount;
            $sale = (float) $rep->sale_amount;
            $isConfirmed = in_array((int)$rep->payment_status, [1, 2]);

            if (!$storeBreakdown->has($cName)) {
                $storeBreakdown->put($cName, [
                    'name' => $cName,
                    'logo' => null,
                    'confirmed_earned' => 0.0,
                    'pending_earned' => 0.0,
                    'total_earned' => 0.0,
                    'total_sale' => 0.0,
                    'order_count' => 0,
                    'store_id' => null,
                ]);
            }

            $item = $storeBreakdown->get($cName);
            if ($isConfirmed) {
                $item['confirmed_earned'] += $earned;
            } else {
                $item['pending_earned'] += $earned;
            }
            $item['total_earned'] += $earned;
            $item['total_sale'] += $sale;
            $item['order_count'] += 1;
            $storeBreakdown->put($cName, $item);
        }

        $storeBreakdown = $storeBreakdown->sortByDesc('total_earned')->values();
        $totalStoresCount = $storeBreakdown->count();

        // 5. Total Unified Earnings History Feed
        $historyList = collect();

        foreach ($orders as $order) {
            $historyList->push((object)[
                'type' => 'order',
                'ref_id' => $order->order_number ?? ('#' . $order->id),
                'store_name' => $order->store_name,
                'store_logo' => $order->store_logo,
                'product_name' => $order->product_name,
                'sale_amount' => (float) $order->amount,
                'earning_amount' => (float) $order->payout_amount,
                'currency' => $order->currency ?? 'INR',
                'status' => strtolower($order->status),
                'status_label' => ucfirst($order->status),
                'source' => $order->source ? ucwords(str_replace('_', ' ', $order->source)) : 'Store Cashback',
                'created_at' => $order->created_at,
                'download_file' => null,
            ]);
        }

        foreach ($reports as $rep) {
            $stLabel = match((int)$rep->payment_status) {
                1 => 'Approved',
                2 => 'Disbursed',
                default => 'Pending'
            };
            $historyList->push((object)[
                'type' => 'report',
                'ref_id' => 'REP-' . $rep->id,
                'store_name' => $rep->compaign_name ?: 'Campaign Deal',
                'store_logo' => null,
                'product_name' => $rep->deal_type ? ($rep->deal_type . ' Campaign') : 'Affiliate Campaign',
                'sale_amount' => (float) $rep->sale_amount,
                'earning_amount' => (float) $rep->earning_amount,
                'currency' => 'INR',
                'status' => in_array((int)$rep->payment_status, [1, 2]) ? 'confirmed' : 'pending',
                'status_label' => $stLabel,
                'source' => $rep->deal_type ?: 'Campaign Bonus',
                'created_at' => $rep->created_at,
                'download_file' => $rep->report_file ? getImage(getFilePath('earningReport') . '/' . $rep->report_file) : null,
            ]);
        }

        // Sort by date descending
        $historyList = $historyList->sortByDesc('created_at')->values();

        // Filters
        $filterStatus = $request->query('status', 'all');
        $filterStore = $request->query('store');
        $filterSearch = $request->query('search');

        if ($filterStatus && $filterStatus !== 'all') {
            $historyList = $historyList->filter(function($item) use ($filterStatus) {
                if ($filterStatus === 'confirmed') {
                    return $item->status === 'confirmed' || $item->status === 'delivered';
                }
                if ($filterStatus === 'pending') {
                    return $item->status === 'pending' || $item->status === 'unreviewed';
                }
                return $item->status === $filterStatus;
            })->values();
        }

        if ($filterStore) {
            $historyList = $historyList->filter(function($item) use ($filterStore) {
                return strcasecmp($item->store_name, $filterStore) === 0;
            })->values();
        }

        if ($filterSearch) {
            $q = strtolower(trim($filterSearch));
            $historyList = $historyList->filter(function($item) use ($q) {
                return str_contains(strtolower($item->store_name), $q) ||
                       str_contains(strtolower($item->product_name), $q) ||
                       str_contains(strtolower($item->ref_id), $q);
            })->values();
        }

        // Pagination
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = getPaginate(15);
        $currentItems = $historyList->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $earningsHistory = new LengthAwarePaginator($currentItems, $historyList->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'query' => $request->query(),
        ]);

        return view($this->activeTemplate . 'user.earnings', compact(
            'pageTitle', 'user', 'totalEarnings', 'pendingEarnings',
            'availableBalance', 'totalStoresCount', 'storeBreakdown',
            'earningsHistory', 'filterStatus', 'filterStore', 'filterSearch'
        ));
    }

    public function myPayments()
    {
        $pageTitle = 'Payments & Payout Account';
        $user = auth()->user();

        // 1. Payout Requests
        $payoutRequests = PayoutRequest::where('user_id', $user->id)->latest()->paginate(getPaginate(10));

        // 2. Balances
        $totalConfirmedCashback = (float) Order::where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->sum('payout_amount');

        $pendingCashback = (float) Order::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'unreviewed'])
            ->sum('payout_amount');

        $alreadyRequested = (float) PayoutRequest::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'under_review', 'confirmed', 'paid'])
            ->sum('amount');

        $availableBalance = max(0, $totalConfirmedCashback - $alreadyRequested);

        $currentMonthCycle  = now()->format('F Y');
        $currentPayableDate = now()->addMonthNoOverflow()->startOfMonth()->addDays(14);

        return view($this->activeTemplate . 'user.payments', compact(
            'pageTitle',
            'user',
            'payoutRequests',
            'availableBalance',
            'totalConfirmedCashback',
            'pendingCashback',
            'currentMonthCycle',
            'currentPayableDate'
        ));
    }

}
