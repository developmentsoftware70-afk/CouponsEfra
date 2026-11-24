@extends($activeTemplate.'layouts.master')
@section('content')

@push('style')
<style>
    /* Bulletproof Referral Dashboard Styles */
    .ref-dashboard-wrapper {
        padding-bottom: 40px;
    }

    /* Hero Banner: Deep Navy & Royal Blue Gradient - NO .card or .custom--card conflict */
    .referral-hero-banner {
        background: #0b192c !important;
        background: linear-gradient(135deg, #0b192c 0%, #1e3a8a 55%, #2563eb 100%) !important;
        border-radius: 18px !important;
        padding: 38px 32px !important;
        color: #ffffff !important;
        box-shadow: 0 10px 30px rgba(11, 25, 44, 0.18) !important;
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
        position: relative;
        overflow: hidden;
    }

    .referral-hero-banner::after {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 240px;
        height: 240px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.12) 0%, rgba(255, 255, 255, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .ref-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.18) !important;
        color: #fde047 !important;
        border: 1px solid rgba(253, 224, 71, 0.45) !important;
        padding: 6px 16px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.3px;
    }

    .ref-hero-title {
        color: #ffffff !important;
        font-size: 28px !important;
        font-weight: 800 !important;
        line-height: 1.3 !important;
        margin-top: 14px;
        margin-bottom: 12px;
    }

    .ref-hero-desc {
        color: #e2e8f0 !important;
        font-size: 15px !important;
        line-height: 1.65 !important;
        max-width: 680px;
        margin-bottom: 24px;
    }

    .ref-highlight-gold {
        color: #fde047 !important;
        font-weight: 800 !important;
    }

    /* Inputs: Solid white with dark charcoal bold text */
    .ref-label {
        color: #cbd5e1 !important;
        font-size: 12.5px !important;
        font-weight: 600 !important;
        margin-bottom: 6px;
        display: block;
    }

    .ref-input {
        background: #ffffff !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border: 2px solid #cbd5e1 !important;
        border-radius: 10px 0 0 10px !important;
        padding: 11px 14px !important;
        font-size: 14.5px !important;
    }

    .ref-input:focus {
        border-color: #3b82f6 !important;
        box-shadow: none !important;
        background: #ffffff !important;
        color: #0f172a !important;
    }

    .ref-btn-copy {
        background: #f59e0b !important;
        color: #000000 !important;
        font-weight: 800 !important;
        border: 2px solid #f59e0b !important;
        border-radius: 0 10px 10px 0 !important;
        padding: 11px 20px !important;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .ref-btn-copy:hover {
        background: #d97706 !important;
        border-color: #d97706 !important;
        color: #000000 !important;
    }

    /* Social Share Buttons */
    .ref-share-pill {
        padding: 9px 18px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 700;
        color: #ffffff !important;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        transition: transform 0.15s ease, opacity 0.2s ease;
    }

    .ref-share-pill:hover {
        transform: translateY(-2px);
        opacity: 0.95;
        color: #ffffff !important;
    }

    .share-wa { background: #25D366 !important; }
    .share-tg { background: #0088cc !important; }
    .share-tw { background: #0f172a !important; border: 1px solid rgba(255,255,255,0.25) !important; }

    /* Side Perk Card */
    .ref-side-perk {
        background: rgba(15, 23, 42, 0.45) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        backdrop-filter: blur(8px);
        border-radius: 16px;
        padding: 26px 22px;
        text-align: center;
    }

    /* Stat Cards */
    .ref-white-card {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 14px !important;
        padding: 22px 20px !important;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04) !important;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .ref-white-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.07) !important;
    }

    .ref-stat-title {
        color: #64748b !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 5px;
    }

    .ref-stat-number {
        font-size: 26px !important;
        font-weight: 800 !important;
        color: #0f172a !important;
        margin: 0;
        line-height: 1.2;
    }

    .ref-stat-sub {
        font-size: 11.5px !important;
        color: #94a3b8 !important;
        display: block;
        margin-top: 4px;
    }

    .ref-icon-box {
        width: 52px;
        height: 52px;
        min-width: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    /* Section Card */
    .ref-content-block {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 14px !important;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04) !important;
        overflow: hidden;
    }

    .ref-step-box {
        background: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important;
        padding: 20px !important;
        height: 100%;
    }

    .ref-step-badge {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #2563eb !important;
        color: #ffffff !important;
        font-weight: 800;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Table Styles */
    .ref-custom-table th {
        background: #f1f5f9 !important;
        color: #334155 !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px 18px !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }

    .ref-custom-table td {
        padding: 16px 18px !important;
        vertical-align: middle;
        color: #1e293b !important;
        border-bottom: 1px solid #f1f5f9 !important;
        font-size: 14px !important;
    }

    /* Status Pills */
    .ref-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 700;
    }

    .pill-confirmed {
        background: #dcfce7 !important;
        color: #15803d !important;
        border: 1px solid #bbf7d0 !important;
    }

    .pill-pending {
        background: #fef3c7 !important;
        color: #b45309 !important;
        border: 1px solid #fde68a !important;
    }

    .pill-awaiting {
        background: #f1f5f9 !important;
        color: #475569 !important;
        border: 1px solid #e2e8f0 !important;
    }

    .pill-rejected {
        background: #ffe4e6 !important;
        color: #be123c !important;
        border: 1px solid #fecdd3 !important;
    }
</style>
@endpush

<div class="ref-dashboard-wrapper">
    <div class="row gy-4">
        <!-- 1. Hero Card: Deep Navy Background with Pure Contrast -->
        <div class="col-12">
            <div class="referral-hero-banner" style="background: #0b192c !important; background: linear-gradient(135deg, #0b192c 0%, #1e3a8a 55%, #2563eb 100%) !important; border-radius: 18px !important; padding: 38px 32px !important; color: #ffffff !important; box-shadow: 0 10px 30px rgba(11, 25, 44, 0.18) !important; border: 1px solid rgba(255, 255, 255, 0.12) !important; position: relative; overflow: hidden;">
                <div class="row align-items-center gy-4">
                    <div class="col-lg-7">
                        <div class="ref-badge mb-2" style="background: rgba(255, 255, 255, 0.18) !important; color: #fde047 !important; border: 1px solid rgba(253, 224, 71, 0.45) !important;">
                            <i class="fas fa-gift" style="color: #fde047 !important;"></i> @lang('Refer & Earn Program')
                        </div>
                        <h1 class="ref-hero-title" style="color: #ffffff !important;">
                            @lang('Invite Friends, Get Flat') <span class="ref-highlight-gold" style="color: #fde047 !important;">₹{{ showAmount($rewardAmount) }}</span> @lang('Cash')
                        </h1>
                        <p class="ref-hero-desc" style="color: #e2e8f0 !important;">
                            @lang('Share your referral link with friends. When they sign up and make their first purchase of') 
                            <span class="ref-highlight-gold" style="color: #fde047 !important;">₹{{ showAmount($minPurchase) }}</span> 
                            @lang('or more, you earn flat') 
                            <span class="ref-highlight-gold" style="color: #fde047 !important;">₹{{ showAmount($rewardAmount) }}</span> 
                            @lang('directly into your wallet balance!')
                        </p>

                        <!-- Shareable Link & Code Box -->
                        <div class="row gy-3">
                            <div class="col-sm-5">
                                <label class="ref-label"><i class="fas fa-hashtag me-1"></i> @lang('Your Referral Code')</label>
                                <div class="input-group">
                                    <input type="text" class="form-control ref-input text-center" id="referralCodeInput" value="{{ $user->referral_code }}" readonly style="letter-spacing: 1.5px;">
                                    <button class="btn ref-btn-copy copyBtn" data-target="#referralCodeInput" type="button" title="@lang('Copy Referral Code')">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <label class="ref-label"><i class="fas fa-link me-1"></i> @lang('Your Referral Link')</label>
                                <div class="input-group">
                                    <input type="text" class="form-control ref-input" id="referralLinkInput" value="{{ $user->referral_link }}" readonly>
                                    <button class="btn ref-btn-copy copyBtn" data-target="#referralLinkInput" type="button" title="@lang('Copy Referral Link')">
                                        <i class="fas fa-copy me-1"></i> @lang('Copy')
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- 1-Click Social Share Buttons -->
                        @php
                            $shareText = rawurlencode("Hey! Sign up on CouponsEfra using my invite link and get the highest cashback & coupons on Myntra, Ajio, Flipkart, Swiggy & 500+ top brands! Join now: " . $user->referral_link);
                        @endphp
                        <div class="d-flex flex-wrap align-items-center gap-2 mt-4 pt-1">
                            <span class="text-white small fw-bold me-2"><i class="fas fa-share-alt me-1"></i> @lang('Share Instantly:'):</span>
                            <a href="https://api.whatsapp.com/send?text={{ $shareText }}" target="_blank" class="ref-share-pill share-wa">
                                <i class="fab fa-whatsapp fs-5"></i> @lang('WhatsApp')
                            </a>
                            <a href="https://t.me/share/url?url={{ urlencode($user->referral_link) }}&text={{ $shareText }}" target="_blank" class="ref-share-pill share-tg">
                                <i class="fab fa-telegram-plane fs-5"></i> @lang('Telegram')
                            </a>
                            <a href="https://twitter.com/intent/tweet?text={{ $shareText }}" target="_blank" class="ref-share-pill share-tw">
                                <i class="fab fa-x-twitter fs-5"></i> @lang('Share on X')
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-5 text-center d-none d-lg-block">
                        <div class="ref-side-perk">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; background: rgba(253, 224, 71, 0.15); border: 2px solid rgba(253, 224, 71, 0.45);">
                                <i class="fas fa-hand-holding-usd" style="font-size: 38px; color: #fde047 !important;"></i>
                            </div>
                            <h3 class="text-white fw-bold mb-1">₹{{ showAmount($rewardAmount) }} <span style="color: #fde047 !important;">@lang('Cash')</span></h3>
                            <p class="text-white-50 small mb-2">@lang('Per qualified friend (First order ₹' . (int)$minPurchase . '+)')</p>
                            <div class="p-2 rounded bg-black bg-opacity-25 text-white-50 small">
                                <i class="fas fa-infinity text-warning me-1"></i> @lang('No limit! Refer 10 friends = Earn ₹')<strong>{{ showAmount($rewardAmount * 10) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. 4 Stat Summary Cards (Clean White with High Contrast Labels & Numbers) -->
        <div class="col-sm-6 col-xl-3">
            <div class="ref-white-card">
                <div>
                    <span class="ref-stat-title">@lang('Friends Invited')</span>
                    <h3 class="ref-stat-number text-primary">{{ $totalFriends }}</h3>
                    <span class="ref-stat-sub">@lang('Registered via your link')</span>
                </div>
                <div class="ref-icon-box" style="background: #eff6ff; color: #2563eb;">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="ref-white-card">
                <div>
                    <span class="ref-stat-title">@lang('Qualified (₹' . (int)$minPurchase . '+)')</span>
                    <h3 class="ref-stat-number text-success">{{ $qualifiedReferrals }}</h3>
                    <span class="ref-stat-sub">@lang('Made ₹' . (int)$minPurchase . '+ purchase')</span>
                </div>
                <div class="ref-icon-box" style="background: #ecfdf5; color: #059669;">
                    <i class="fas fa-shopping-bag"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="ref-white-card">
                <div>
                    <span class="ref-stat-title">@lang('Total Earned')</span>
                    <h3 class="ref-stat-number text-success">₹{{ showAmount($totalEarned) }}</h3>
                    <span class="ref-stat-sub">@lang('Confirmed in your wallet')</span>
                </div>
                <div class="ref-icon-box" style="background: #ecfdf5; color: #10b981;">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="ref-white-card">
                <div>
                    <span class="ref-stat-title">@lang('Pending Rewards')</span>
                    <h3 class="ref-stat-number text-warning">₹{{ showAmount($pendingRewards) }}</h3>
                    <span class="ref-stat-sub">@lang('Awaiting return window')</span>
                </div>
                <div class="ref-icon-box" style="background: #fffbeb; color: #d97706;">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>

        <!-- 3. How It Works (3 Clear High-Contrast Step Cards) -->
        <div class="col-12">
            <div class="ref-content-block p-4">
                <h5 class="fw-bold text-dark mb-4 d-flex align-items-center gap-2">
                    <i class="fas fa-info-circle text-primary"></i> @lang('How Does Refer & Earn Work?')
                </h5>
                <div class="row gy-3">
                    <div class="col-md-4">
                        <div class="ref-step-box">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <span class="ref-step-badge">1</span>
                                <h6 class="fw-bold text-dark mb-0">@lang('Invite Friends')</h6>
                            </div>
                            <p class="text-muted small mb-0">
                                @lang('Share your invite link with friends via WhatsApp, Telegram, or social media.')
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="ref-step-box">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <span class="ref-step-badge" style="background: #0284c7 !important;">2</span>
                                <h6 class="fw-bold text-dark mb-0">@lang('Friend Shops Min ₹' . (int)$minPurchase)</h6>
                            </div>
                            <p class="text-muted small mb-0">
                                @lang('Friend creates an account and makes their first purchase of ₹' . (int)$minPurchase . ' or more on any brand.')
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="ref-step-box">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <span class="ref-step-badge" style="background: #16a34a !important;">3</span>
                                <h6 class="fw-bold text-dark mb-0">@lang('Get Flat ₹' . (int)$rewardAmount . ' in Wallet')</h6>
                            </div>
                            <p class="text-muted small mb-0">
                                @lang('Once the brand confirms the order, flat ₹' . (int)$rewardAmount . ' cash is credited to your wallet to withdraw!')
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Referral History & Performance Table -->
        <div class="col-12">
            <div class="ref-content-block">
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-white">
                    <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="fas fa-list text-primary"></i> @lang('My Referral History')
                    </h5>
                    <span class="badge bg-light text-dark px-3 py-2 border rounded-pill fw-bold">
                        {{ $logs->total() }} @lang('Total Friends')
                    </span>
                </div>
                <div class="table-responsive">
                    <table class="table ref-custom-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">@lang('Friend')</th>
                                <th>@lang('Joined Date')</th>
                                <th>@lang('First Purchase')</th>
                                <th>@lang('Status')</th>
                                <th class="text-end pe-4">@lang('Reward')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                @php
                                    $referee = $log->referee;
                                    $maskedEmail = $referee && $referee->email ? substr($referee->email, 0, 2) . '***' . strstr($referee->email, '@') : 'User #' . $log->referee_id;
                                    $username = $referee ? $referee->username : 'Friend';
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 38px; height: 38px; min-width: 38px; background: #eff6ff; border: 1px solid #bfdbfe;">
                                                {{ strtoupper(substr($username, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $username }}</div>
                                                <div class="text-muted" style="font-size: 12px;">{{ $maskedEmail }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ showDateTime($log->created_at, 'd M Y') }}</div>
                                        <div class="text-muted" style="font-size: 11.5px;">{{ diffForHumans($log->created_at) }}</div>
                                    </td>
                                    <td>
                                        @if($log->order_amount > 0)
                                            <span class="fw-bold text-dark">₹{{ showAmount($log->order_amount) }}</span>
                                            @if($log->order)
                                                <div class="text-muted" style="font-size: 11px;">#{{ $log->order->order_number ?: $log->order->id }}</div>
                                            @endif
                                        @else
                                            <span class="text-muted small">@lang('No purchase yet')</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($log->status == 'confirmed')
                                            <span class="ref-status-pill pill-confirmed">
                                                <i class="fas fa-check-circle"></i> @lang('Reward Confirmed')
                                            </span>
                                        @elseif($log->status == 'pending_confirmation')
                                            <span class="ref-status-pill pill-pending">
                                                <i class="fas fa-clock"></i> @lang('Order Placed (Pending Confirmation)')
                                            </span>
                                        @elseif($log->status == 'rejected')
                                            <span class="ref-status-pill pill-rejected">
                                                <i class="fas fa-times-circle"></i> @lang('Order Cancelled')
                                            </span>
                                        @else
                                            <span class="ref-status-pill pill-awaiting">
                                                <i class="fas fa-hourglass-start"></i> @lang('Awaiting First Purchase (Min ₹' . (int)$minPurchase . ')')
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        @if($log->status == 'confirmed')
                                            <span class="fw-bold text-success fs-6">+₹{{ showAmount($log->reward_amount) }}</span>
                                        @elseif($log->status == 'pending_confirmation')
                                            <span class="fw-bold text-warning">₹{{ showAmount($log->reward_amount) }} <small class="text-muted fw-normal">(Pending)</small></span>
                                        @elseif($log->status == 'rejected')
                                            <span class="text-muted text-decoration-line-through">₹{{ showAmount($log->reward_amount) }}</span>
                                        @else
                                            <span class="text-muted fw-semibold">₹{{ showAmount($log->reward_amount) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="py-4">
                                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; background: #f1f5f9; color: #94a3b8;">
                                                <i class="fas fa-users-slash fs-2"></i>
                                            </div>
                                            <h6 class="text-dark fw-bold mb-1">@lang('No Referrals Yet')</h6>
                                            <p class="text-muted small mb-3">@lang('Share your referral link with friends and start earning flat') ₹{{ showAmount($rewardAmount) }} @lang('cash!')</p>
                                            <button class="btn btn-primary fw-semibold copyBtn px-4 py-2" data-target="#referralLinkInput" style="border-radius: 8px;">
                                                <i class="fas fa-share-alt me-1"></i> @lang('Copy Referral Link & Share')
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($logs->hasPages())
                    <div class="p-3 border-top bg-white">
                        {{ paginateLinks($logs) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.copyBtn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var targetSelector = this.getAttribute('data-target');
                var targetInput = document.querySelector(targetSelector);
                if (targetInput) {
                    targetInput.select();
                    targetInput.setSelectionRange(0, 99999);
                    navigator.clipboard.writeText(targetInput.value).then(function () {
                        var originalHtml = btn.innerHTML;
                        btn.innerHTML = '<i class="fas fa-check text-white"></i>';
                        btn.style.background = '#16a34a';
                        btn.style.borderColor = '#16a34a';
                        setTimeout(function () {
                            btn.innerHTML = originalHtml;
                            btn.style.background = '';
                            btn.style.borderColor = '';
                        }, 2000);
                    });
                }
            });
        });
    });
</script>
@endsection
