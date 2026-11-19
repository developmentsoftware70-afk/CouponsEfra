<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\ReferralLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ReferralController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = 'Referral Program - User Logs';

        $query = ReferralLog::with(['referrer', 'referee', 'order'])->latest();

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by referrer or referee username / email
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereHas('referrer', function ($sub) use ($search) {
                    $sub->where('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('referee', function ($sub) use ($search) {
                    $sub->where('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        $logs = $query->paginate(getPaginate(20));

        // Admin stat cards
        $totalReferrals     = ReferralLog::count();
        $confirmedRewards   = ReferralLog::where('status', 'confirmed')->sum('reward_amount');
        $pendingRewards     = ReferralLog::where('status', 'pending_confirmation')->sum('reward_amount');
        $awaitingPurchases  = ReferralLog::where('status', 'pending_purchase')->count();

        return view('admin.referral.index', compact(
            'pageTitle',
            'logs',
            'totalReferrals',
            'confirmedRewards',
            'pendingRewards',
            'awaitingPurchases'
        ));
    }

    public function settings()
    {
        $pageTitle = 'Referral Program Settings';
        $general   = gs();

        return view('admin.referral.settings', compact('pageTitle', 'general'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'referral_reward_amount' => 'required|numeric|min:0',
            'referral_min_purchase'  => 'required|numeric|min:0',
        ]);

        $general = gs();
        $general->referral_status        = $request->referral_status ? 1 : 0;
        $general->referral_reward_amount = $request->referral_reward_amount;
        $general->referral_min_purchase  = $request->referral_min_purchase;
        $general->save();

        Cache::forget('GeneralSetting');

        $notify[] = ['success', 'Referral settings updated successfully'];
        return back()->withNotify($notify);
    }
}
