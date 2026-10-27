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

class UserController extends Controller
{
    public function home()
    {
        $pageTitle = 'Dashboard';
        $user = auth()->user();
        $couponsCount = Coupon::where('user_id',$user->id)->count();
        $wishLists = Wishlist::with('coupon')->where('user_id', $user->id)->latest()->paginate(getPaginate(10));
        $orders = Order::where('user_id', $user->id)->latest()->take(10)->get();
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

        // 2. Pending Payment: Jo payment ke liye apply kiya but abhi approval/payment nahi hui
        $pendingPayouts = (float) PayoutRequest::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'under_review', 'unreviewed'])
            ->sum('amount');
        $legacyPending = (float) EarningReport::where('user_id', $user->id)
            ->where('payment_status', 0)
            ->sum('earning_amount');
        $pendingPaymentAmount = $pendingPayouts + $legacyPending;

        // 3. Disbursed Amount: Jo approval ho gaya matlab nikal gaya hai (paid / confirmed payouts)
        $paidPayouts = (float) PayoutRequest::where('user_id', $user->id)
            ->whereIn('status', ['confirmed', 'paid'])
            ->sum('amount');
        $legacyDisbursed = (float) EarningReport::where('user_id', $user->id)
            ->where('payment_status', 2)
            ->sum('earning_amount');
        $disbursedAmount = $paidPayouts + $legacyDisbursed;

        return view($this->activeTemplate . 'user.dashboard', compact(
            'pageTitle', 'user', 'wishLists', 'orders', 'earningReportList',
            'coupons', 'couponsCount', 'activeCoupon', 'pendingCoupon',
            'storeCount', 'pendingStore', 'activeStore',
            'totalEarningsAmount', 'pendingPaymentAmount', 'disbursedAmount'
        ));
    }

    public function orders()
    {
        $pageTitle = 'My Orders';
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

        $notify[] = ['success', 'Payout request of ₹' . showAmount($payout->amount) . ' submitted successfully! Payment will be processed after 15th ' . now()->addMonthNoOverflow()->format('F') . '.'];
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
    /*
    public function myEarnings()
    {
        $pageTitle = 'My Earnings';
        $user = auth()->user();

        $earningReports = EarningReport::with('user')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(getPaginate());

        $totalEarnings = EarningReport::where('user_id', $user->id)->sum('earning_amount');

        return view($this->activeTemplate . 'user.earnings', compact('pageTitle', 'user', 'earningReports', 'totalEarnings'));
    }*/

    public function myEarnings()
    {
        $pageTitle = 'My Earnings';
        $user = auth()->user();

        // Filter only daily reports
        $earningReports = EarningReport::with('user')
            ->where('user_id', $user->id)
            ->where('report_type', 'Daily') // <-- Filter only Daily reports
            ->latest()
            ->paginate(getPaginate());

        $totalEarnings = EarningReport::where('user_id', $user->id)
            ->where('report_type', 'Daily') // <-- Also apply filter to total
            ->sum('earning_amount');

        return view($this->activeTemplate . 'user.earnings', compact('pageTitle', 'user', 'earningReports', 'totalEarnings'));
    }

    public function myPayments()
    {
        $pageTitle = 'Payments';
        $user = auth()->user();

        // Filter only daily reports
        $paymentReports = EarningReport::with('user')
            ->where('user_id', $user->id)
            ->where('report_type', 'Monthly') // <-- Filter only Daily reports
            ->latest()
            ->paginate(getPaginate());

        $totalPayments = EarningReport::where('user_id', $user->id)
            ->where('report_type', 'Monthly') // <-- Also apply filter to total
            ->sum('earning_amount');

        return view($this->activeTemplate . 'user.payments', compact('pageTitle', 'user', 'paymentReports', 'totalPayments'));
    }

}
