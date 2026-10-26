<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PayoutRequest;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = 'Payout Requests';

        $query = PayoutRequest::with('user')->latest();

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('amount', 'like', "%$search%")
                  ->orWhere('account_number', 'like', "%$search%")
                  ->orWhere('upi_id', 'like', "%$search%")
                  ->orWhere('account_holder_name', 'like', "%$search%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('username', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                  });
            });
        }

        if ($request->status && $request->status != 'all') {
            if ($request->status == 'confirmed') {
                $query->whereIn('status', ['confirmed', 'paid']);
            } elseif ($request->status == 'cancelled') {
                $query->whereIn('status', ['cancelled', 'declined']);
            } elseif ($request->status == 'under_review') {
                $query->whereIn('status', ['under_review', 'unreviewed']);
            } else {
                $query->where('status', $request->status);
            }
        }

        $payouts = $query->paginate(getPaginate());

        // Summary counts for top tabs (4 core statuses: Pending, Confirmed, Under Review, Cancelled)
        $totalCount       = PayoutRequest::count();
        $pendingCount     = PayoutRequest::where('status', 'pending')->count();
        $underReviewCount = PayoutRequest::whereIn('status', ['under_review', 'unreviewed'])->count();
        $confirmedCount   = PayoutRequest::whereIn('status', ['confirmed', 'paid'])->count();
        $cancelledCount   = PayoutRequest::whereIn('status', ['cancelled', 'declined'])->count();

        return view('admin.payout.index', compact(
            'pageTitle', 
            'payouts', 
            'totalCount', 
            'pendingCount', 
            'underReviewCount', 
            'confirmedCount', 
            'cancelledCount'
        ));
    }

    /**
     * Update individual payout request status (Pending, Confirmed, Under Review, Cancelled)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'         => 'required|in:pending,confirmed,under_review,cancelled',
            'transaction_id' => 'nullable|string|max:100',
            'admin_feedback' => 'nullable|string|max:255',
        ]);

        $payout = PayoutRequest::findOrFail($id);

        if (in_array(strtolower($payout->status), ['confirmed', 'paid', 'approved'])) {
            $notify[] = ['error', 'This payout has already been paid and locked. You cannot modify a completed payment.'];
            return back()->withNotify($notify);
        }

        $payout->status         = $request->status;
        $payout->admin_feedback = $request->admin_feedback;

        if ($request->filled('transaction_id')) {
            $payout->transaction_id = $request->transaction_id;
        }

        if ($request->status === 'confirmed') {
            $payout->paid_at = now();
        }

        $payout->save();

        $statusName = match($request->status) {
            'confirmed'    => 'Confirmed',
            'under_review' => 'Under Review',
            'cancelled'    => 'Cancelled',
            default        => 'Pending',
        };

        $notify[] = ['success', "Payout #{$payout->id} status updated to {$statusName} successfully."];
        return back()->withNotify($notify);
    }

    /**
     * Bulk Action: Move multiple selected users to "under_review", "cancelled", or "confirmed"
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids'      => 'required|array|min:1',
            'ids.*'    => 'integer|exists:payout_requests,id',
            'action'   => 'required|in:under_review,cancelled,confirmed',
            'feedback' => 'nullable|string|max:255',
        ]);

        $ids = $request->ids;

        // Strictly exclude already paid/confirmed payouts so they are never touched
        $eligiblePayouts = PayoutRequest::whereIn('id', $ids)
            ->whereNotIn('status', ['confirmed', 'paid', 'approved'])
            ->get();

        if ($eligiblePayouts->isEmpty()) {
            $notify[] = ['error', 'Selected payout(s) are already paid/confirmed and locked.'];
            return back()->withNotify($notify);
        }

        $validIds = $eligiblePayouts->pluck('id')->toArray();

        if ($request->action === 'under_review') {
            PayoutRequest::whereIn('id', $validIds)->update(['status' => 'under_review']);
            $notify[] = ['success', count($validIds) . ' payout request(s) moved to Under Review.'];
        } elseif ($request->action === 'cancelled') {
            PayoutRequest::whereIn('id', $validIds)->update([
                'status'         => 'cancelled',
                'admin_feedback' => $request->feedback ?? 'Cancelled by Admin'
            ]);
            $notify[] = ['success', count($validIds) . ' payout request(s) cancelled.'];
        } elseif ($request->action === 'confirmed') {
            PayoutRequest::whereIn('id', $validIds)->update([
                'status'  => 'confirmed',
                'paid_at' => now()
            ]);
            $notify[] = ['success', count($validIds) . ' payout request(s) confirmed and paid.'];
        }

        return back()->withNotify($notify);
    }

    /**
     * Pay single user with UTR reference
     */
    public function paySingle(Request $request, $id)
    {
        $request->validate([
            'transaction_id' => 'required|string|max:100',
            'admin_feedback' => 'nullable|string|max:255',
        ]);

        $payout = PayoutRequest::findOrFail($id);

        if (in_array(strtolower($payout->status), ['confirmed', 'paid', 'approved'])) {
            $notify[] = ['error', 'This payout has already been paid and locked. You cannot modify a completed payment.'];
            return back()->withNotify($notify);
        }

        $payout->status         = 'confirmed';
        $payout->transaction_id = $request->transaction_id;
        $payout->admin_feedback = $request->admin_feedback;
        $payout->paid_at        = now();
        $payout->save();

        $notify[] = ['success', 'Payment of ₹' . showAmount($payout->amount) . ' Confirmed with Transaction ID: ' . $request->transaction_id];
        return back()->withNotify($notify);
    }
}
