@extends($activeTemplate.'layouts.master')
@section('content')
<div class="row gy-4">
    <!-- Referral Banner & Share Box -->
    <div class="col-12">
        <div class="card custom--card border-0 shadow-sm" style="border-radius: 16px; background: linear-gradient(135deg, #0d3b66 0%, #0047ff 100%); color: #fff;">
            <div class="card-body p-4 p-md-5">
                <div class="row align-items-center gy-4">
                    <div class="col-lg-7">
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-3" style="background: rgba(255,255,255,0.18); font-size: 13px; font-weight: 600;">
                            <i class="fas fa-gift text-warning"></i> @lang('Refer & Earn Program')
                        </div>
                        <h2 class="text-white fw-bold mb-2">@lang('Invite Friends, Get Flat') ₹{{ showAmount($rewardAmount) }} @lang('Cash')</h2>
                        <p class="text-white-50 mb-4" style="font-size: 15px; line-height: 1.6;">
                            @lang('Share your referral link with friends. When they sign up and make their first purchase of') <strong>₹{{ showAmount($minPurchase) }}</strong> @lang('or more, you earn flat') <strong>₹{{ showAmount($rewardAmount) }}</strong> @lang('directly into your wallet!')
                        </p>

                        <!-- Shareable Link & Code Box -->
                        <div class="row gy-3">
                            <div class="col-sm-5">
                                <label class="text-white-50 small mb-1">@lang('Your Referral Code')</label>
                                <div class="input-group">
                                    <input type="text" class="form-control fw-bold text-center bg-white text-dark" id="referralCodeInput" value="{{ $user->referral_code }}" readonly style="border-radius: 8px 0 0 8px; font-size: 15px; letter-spacing: 1px;">
                                    <button class="btn btn-warning fw-semibold copyBtn" data-target="#referralCodeInput" type="button" style="border-radius: 0 8px 8px 0;">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <label class="text-white-50 small mb-1">@lang('Your Referral Link')</label>
                                <div class="input-group">
                                    <input type="text" class="form-control bg-white text-dark" id="referralLinkInput" value="{{ $user->referral_link }}" readonly style="border-radius: 8px 0 0 8px; font-size: 13.5px;">
                                    <button class="btn btn-warning fw-semibold copyBtn" data-target="#referralLinkInput" type="button" style="border-radius: 0 8px 8px 0;">
                                        <i class="fas fa-copy me-1"></i> @lang('Copy')
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- 1-Click Social Share Buttons -->
                        @php
                            $shareText = rawurlencode("Hey! Use my invite link on CouponsEfra to get the highest cashback & coupons on top brands like Myntra, Ajio, Flipkart & more! Sign up here: " . $user->referral_link);
                        @endphp
                        <div class="d-flex flex-wrap align-items-center gap-2 mt-4 pt-2">
                            <span class="text-white-50 small me-2">@lang('Share via:'):</span>
                            <a href="https://api.whatsapp.com/send?text={{ $shareText }}" target="_blank" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center gap-2 px-3 py-2" style="background: #25D366; border-radius: 8px;">
                                <i class="fab fa-whatsapp fs-6"></i> @lang('WhatsApp')
                            </a>
                            <a href="https://t.me/share/url?url={{ urlencode($user->referral_link) }}&text={{ $shareText }}" target="_blank" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center gap-2 px-3 py-2" style="background: #0088cc; border-radius: 8px;">
                                <i class="fab fa-telegram-plane fs-6"></i> @lang('Telegram')
                            </a>
                            <a href="https://twitter.com/intent/tweet?text={{ $shareText }}" target="_blank" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center gap-2 px-3 py-2" style="background: #111; border-radius: 8px;">
                                <i class="fab fa-x-twitter fs-6"></i> @lang('X')
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-5 text-center d-none d-lg-block">
                        <div class="p-4 rounded-4" style="background: rgba(255,255,255,0.08); backdrop-filter: blur(8px);">
                            <i class="fas fa-hand-holding-usd text-warning" style="font-size: 72px;"></i>
                            <h4 class="text-white fw-bold mt-3 mb-1">₹{{ showAmount($rewardAmount) }} @lang('Per Referral')</h4>
                            <p class="text-white-50 small mb-0">@lang('No limit on invites! Invite 10 friends and earn flat') <strong>₹{{ showAmount($rewardAmount * 10) }}</strong>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4 Stats Cards -->
    <div class="col-sm-6 col-xl-3">
        <div class="card custom--card border-0 shadow-sm h-100" style="border-radius: 14px;">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold">@lang('Total Friends Invited')</span>
                    <h3 class="fw-bold mb-0 text-primary mt-1">{{ $totalFriends }}</h3>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: rgba(0, 71, 255, 0.1); color: #0047ff;">
                    <i class="fas fa-users fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card custom--card border-0 shadow-sm h-100" style="border-radius: 14px;">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold">@lang('Qualified Referrals (₹' . (int)$minPurchase . '+)')</span>
                    <h3 class="fw-bold mb-0 text-success mt-1">{{ $qualifiedReferrals }}</h3>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: rgba(16, 185, 129, 0.1); color: #10b981;">
                    <i class="fas fa-shopping-bag fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card custom--card border-0 shadow-sm h-100" style="border-radius: 14px;">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold">@lang('Total Earned (Confirmed)')</span>
                    <h3 class="fw-bold mb-0 text-success mt-1">₹{{ showAmount($totalEarned) }}</h3>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: rgba(16, 185, 129, 0.1); color: #10b981;">
                    <i class="fas fa-wallet fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card custom--card border-0 shadow-sm h-100" style="border-radius: 14px;">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold">@lang('Pending Rewards')</span>
                    <h3 class="fw-bold mb-0 text-warning mt-1">₹{{ showAmount($pendingRewards) }}</h3>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                    <i class="fas fa-clock fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <div class="col-12">
        <div class="card custom--card border-0 shadow-sm" style="border-radius: 14px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4"><i class="fas fa-info-circle text-primary me-2"></i>@lang('How Does Refer & Earn Work?')</h5>
                <div class="row gy-3">
                    <div class="col-md-4">
                        <div class="p-3 border rounded-3 h-100" style="background: #f8fafc;">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <span class="badge bg-primary rounded-circle px-2 py-1 fw-bold">1</span>
                                <h6 class="fw-bold mb-0">@lang('Invite Friends')</h6>
                            </div>
                            <p class="text-muted small mb-0">@lang('Share your link via WhatsApp, Telegram, or social media with friends and family.')</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded-3 h-100" style="background: #f8fafc;">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <span class="badge bg-primary rounded-circle px-2 py-1 fw-bold">2</span>
                                <h6 class="fw-bold mb-0">@lang('Friend Shops Min ₹' . (int)$minPurchase)</h6>
                            </div>
                            <p class="text-muted small mb-0">@lang('Your friend signs up and completes their first purchase of ₹' . (int)$minPurchase . ' or more on any brand.')</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded-3 h-100" style="background: #f8fafc;">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <span class="badge bg-success rounded-circle px-2 py-1 fw-bold">3</span>
                                <h6 class="fw-bold mb-0">@lang('Get Flat ₹' . (int)$rewardAmount . ' in Wallet')</h6>
                            </div>
                            <p class="text-muted small mb-0">@lang('Once the order return window passes and brand confirms, flat ₹' . (int)$rewardAmount . ' is credited to your wallet balance!')</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Referral History & Performance Table -->
    <div class="col-12">
        <div class="card custom--card border-0 shadow-sm" style="border-radius: 14px;">
            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-list text-primary me-2"></i>@lang('My Referral History')
                </h5>
                <span class="badge bg-light text-dark px-3 py-2 border rounded-pill">{{ $logs->total() }} @lang('Total Friends')</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">@lang('Friend')</th>
                                <th>@lang('Joined On')</th>
                                <th>@lang('Order Value')</th>
                                <th>@lang('Referral Status')</th>
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
                                            <div class="avatar-sm rounded-circle bg-light d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 36px; height: 36px; border: 1px solid #e2e8f0;">
                                                {{ strtoupper(substr($username, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-dark">{{ $username }}</div>
                                                <div class="text-muted small">{{ $maskedEmail }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small fw-semibold">{{ showDateTime($log->created_at, 'd M Y') }}</div>
                                        <div class="text-muted" style="font-size: 11.5px;">{{ diffForHumans($log->created_at) }}</div>
                                    </td>
                                    <td>
                                        @if($log->order_amount > 0)
                                            <span class="fw-bold text-dark">₹{{ showAmount($log->order_amount) }}</span>
                                            @if($log->order)
                                                <div class="text-muted" style="font-size: 11px;">#{{ $log->order->order_number ?: $log->order->id }}</div>
                                            @endif
                                        @else
                                            <span class="text-muted small">@lang('No order yet')</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($log->status == 'confirmed')
                                            <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill">
                                                <i class="fas fa-check-circle me-1"></i> @lang('Reward Confirmed')
                                            </span>
                                        @elseif($log->status == 'pending_confirmation')
                                            <span class="badge bg-warning-subtle text-warning px-2 py-1 rounded-pill">
                                                <i class="fas fa-clock me-1"></i> @lang('Order Placed (Pending Confirmation)')
                                            </span>
                                        @elseif($log->status == 'rejected')
                                            <span class="badge bg-danger-subtle text-danger px-2 py-1 rounded-pill">
                                                <i class="fas fa-times-circle me-1"></i> @lang('Order Cancelled')
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-muted px-2 py-1 rounded-pill">
                                                <i class="fas fa-hourglass-start me-1"></i> @lang('Awaiting First Purchase (Min ₹' . (int)$minPurchase . ')')
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        @if($log->status == 'confirmed')
                                            <span class="fw-bold text-success">+₹{{ showAmount($log->reward_amount) }}</span>
                                        @elseif($log->status == 'pending_confirmation')
                                            <span class="fw-semibold text-warning">₹{{ showAmount($log->reward_amount) }} (Pending)</span>
                                        @elseif($log->status == 'rejected')
                                            <span class="text-muted text-decoration-line-through">₹{{ showAmount($log->reward_amount) }}</span>
                                        @else
                                            <span class="text-muted small">₹{{ showAmount($log->reward_amount) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="fas fa-users-slash text-muted mb-3" style="font-size: 48px; opacity: 0.4;"></i>
                                            <h6 class="text-muted fw-bold">@lang('No Referrals Yet')</h6>
                                            <p class="text-muted small mb-3">@lang('Share your link with friends and start earning flat') ₹{{ showAmount($rewardAmount) }} @lang('on their first purchase!')</p>
                                            <button class="btn btn-sm btn-primary copyBtn" data-target="#referralLinkInput">
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
                    <div class="card-footer bg-transparent border-0 py-3">
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
                        btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                        btn.classList.add('btn-success');
                        setTimeout(function () {
                            btn.innerHTML = originalHtml;
                            btn.classList.remove('btn-success');
                        }, 2000);
                    });
                }
            });
        });
    });
</script>
@endsection
