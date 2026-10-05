@extends($activeTemplate.'layouts.frontend')
@section('content')
<style>
    body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
    .go-back-top {
        display: inline-block;
        margin: 30px 0 20px;
        color: #4a5568;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .go-back-top:hover { color: #2b6cb0; text-decoration: underline; }
    
    .coupon-detail-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        margin-bottom: 60px;
    }
    .main-col { flex: 1; min-width: 65%; }
    .side-col { width: 350px; }
    
    /* Premium Main Card */
    .coupon-main-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }
    
    .status-bar {
        background: linear-gradient(135deg, #FF6B6B 0%, #FF8E53 100%);
        padding: 12px 25px;
        font-weight: 700;
        font-size: 13px;
        color: #fff;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .status-bar.exclusive { background: linear-gradient(135deg, #6B46C1 0%, #805AD5 100%); }
    .status-bar.featured { background: linear-gradient(135deg, #e53e3e 0%, #dd6b20 100%); }
    .status-bar.popular { background: linear-gradient(135deg, #3182ce 0%, #4299e1 100%); }
    
    .card-header-row {
        display: flex;
        align-items: center;
        padding: 40px 30px;
        border-bottom: 1px solid #f1f5f9;
        background: #ffffff;
    }
    
    .store-logo-wrapper {
        width: 120px;
        height: 120px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px;
        margin-right: 30px;
        background: #fff;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    }
    .store-logo {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    .coupon-title-wrap {
        flex: 1;
    }
    .coupon-title {
        font-size: 26px;
        font-weight: 800;
        color: #1a202c;
        margin: 0 0 15px 0;
        line-height: 1.3;
    }
    .store-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: #edf2f7;
        color: #4a5568;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
    }
    
    /* Good News Box */
    .good-news-box {
        background: linear-gradient(to right, #f0fdf4, #dcfce7);
        border-left: 4px solid #22c55e;
        padding: 20px 30px;
        margin: 30px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .good-news-text h6 { margin: 0 0 5px 0; color: #166534; font-weight: 800; font-size: 16px; }
    .good-news-text p { margin: 0; font-size: 14px; color: #15803d; font-weight: 500;}
    
    /* Code Box */
    .code-section {
        text-align: center;
        padding: 30px 40px 50px;
    }
    .copy-instruction {
        font-size: 16px;
        color: #718096;
        margin-bottom: 25px;
        font-weight: 500;
    }
    .code-box-inner {
        display: inline-flex;
        align-items: center;
        border: 2px dashed #cbd5e0;
        border-radius: 12px;
        padding: 8px 8px 8px 30px;
        background: #f7fafc;
        margin-bottom: 40px;
        transition: all 0.3s ease;
    }
    .code-box-inner:hover {
        border-color: #a0aec0;
        background: #fff;
    }
    .code-text {
        font-size: 28px;
        font-weight: 800;
        letter-spacing: 2px;
        color: #1a202c;
        margin-right: 30px;
        font-family: monospace;
    }
    .copy-btn {
        background: #2b6cb0;
        color: #fff;
        border: none;
        padding: 15px 30px;
        font-weight: 700;
        font-size: 15px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 6px rgba(43, 108, 176, 0.2);
    }
    .copy-btn:hover { background: #2c5282; transform: translateY(-1px); box-shadow: 0 6px 12px rgba(43, 108, 176, 0.3); }
    
    .btn-primary-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #3182ce;
        color: #ffffff !important;
        font-weight: 700;
        font-size: 16px;
        text-decoration: none;
        padding: 16px 30px;
        border-radius: 35px;
        transition: all 0.2s ease;
        box-shadow: 0 4px 10px rgba(49, 130, 206, 0.25);
        border: none;
        cursor: pointer;
    }
    .btn-primary-action:hover {
        background: #2b6cb0;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(49, 130, 206, 0.3);
    }

    .btn-secondary-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        color: #475569 !important;
        font-weight: 700;
        font-size: 16px;
        text-decoration: none;
        padding: 16px 28px;
        border-radius: 35px;
        transition: all 0.2s ease;
        border: 1px solid #cbd5e1;
        cursor: pointer;
        gap: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .btn-secondary-action:hover {
        background: #f1f5f9;
        color: #1e293b !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.04);
    }

    .action-buttons-wrapper {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: stretch;
        gap: 20px;
        margin-bottom: 45px;
    }
    
    .stats-row {
        display: flex;
        justify-content: center;
        gap: 25px;
        color: #718096;
        font-size: 14px;
        font-weight: 500;
    }
    .stats-row span i { margin-right: 8px; color: #a0aec0; }
    
    .join-channel {
        background: linear-gradient(135deg, #0088cc 0%, #005f8f 100%);
        color: #fff;
        padding: 25px 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .join-channel h6 { color: #fff; margin: 0 0 5px 0; font-size: 18px; font-weight: 700; }
    .join-channel p { margin: 0; font-size: 14px; opacity: 0.9; }
    .join-btn { background: #fff; color: #0088cc; padding: 10px 25px; border-radius: 25px; font-weight: 800; font-size: 14px; text-decoration: none; transition: 0.2s; box-shadow: 0 4px 10px rgba(0,0,0,0.1);}
    .join-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(0,0,0,0.2); }
    
    /* T&C */
    .tnc-box {
        padding: 30px;
        background: #faf5ff;
        border-top: 1px solid #f1f5f9;
    }
    .tnc-box h6 { font-weight: 800; margin-bottom: 15px; font-size: 16px; color: #44337a;}
    .tnc-box p { font-size: 14px; color: #4a5568; line-height: 1.7; margin-bottom: 10px; }
    
    .feedback-row {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        padding: 25px 0;
        border-top: 1px solid #f1f5f9;
        margin-top: 25px;
        background: #fff;
    }
    .feedback-row span { font-size: 14px; color: #4a5568; font-weight: 700; }
    .feedback-btn {
        border: 1px solid #e2e8f0;
        background: #fff;
        padding: 8px 20px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        color: #4a5568;
        cursor: pointer;
        transition: 0.2s;
    }
    .feedback-btn:hover { background: #edf2f7; border-color: #cbd5e0; }
    
    /* Subscribe Box */
    .subscribe-card {
        background: #fff;
        border-radius: 16px;
        padding: 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05);
    }
    .sub-icon { width: 50px; height: 50px; background: #ebf8ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #3182ce; font-weight: 800; font-size: 24px; }
    .sub-info h6 { margin: 0 0 5px 0; font-weight: 800; font-size: 18px; color: #1a202c;}
    .sub-info p { margin: 0; font-size: 14px; color: #718096; font-weight: 500;}
    .sub-form { display: flex; gap: 0; box-shadow: 0 4px 6px rgba(0,0,0,0.02); border-radius: 8px;}
    .sub-form input { border: 1px solid #e2e8f0; padding: 12px 20px; border-radius: 8px 0 0 8px; outline: none; font-size: 14px; min-width: 250px; font-weight: 500; }
    .sub-form button { background: #3182ce; color: #fff; border: none; padding: 12px 25px; font-size: 14px; font-weight: 700; border-radius: 0 8px 8px 0; cursor: pointer; transition: 0.2s;}
    .sub-form button:hover { background: #2b6cb0; }
    
    /* Sidebar */
    .sidebar-title { font-size: 14px; font-weight: 800; color: #2d3748; margin-bottom: 20px; letter-spacing: 1px; text-transform: uppercase; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;}
    .related-card {
        background: #fff;
        border: 1px solid #edf2f7;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 15px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .related-card:hover { box-shadow: 0 10px 20px rgba(0,0,0,0.06); transform: translateY(-3px); border-color: #cbd5e0; }
    .related-card-img-wrap { width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; background: #f7fafc; border-radius: 8px; padding: 10px; border: 1px solid #edf2f7; }
    .related-card img { max-width: 100%; max-height: 100%; object-fit: contain; }
    .related-card-info { flex: 1; }
    .related-card-info h6 { margin: 0 0 6px 0; color: #2b6cb0; font-size: 15px; font-weight: 800; line-height: 1.3;}
    .related-card-info p { margin: 0; font-size: 13px; color: #718096; line-height: 1.5; font-weight: 500;}
    
    @media(max-width: 991px) {
        .coupon-detail-wrapper { flex-direction: column; }
        .side-col { width: 100%; }
        .card-header-row { flex-direction: column; text-align: center; }
        .store-logo-wrapper { margin: 0 auto 20px auto; }
        .subscribe-card { flex-direction: column; text-align: center; gap: 20px; }
        .sub-form { width: 100%; flex-direction: column; gap: 10px; box-shadow: none; }
        .sub-form input { border-radius: 8px; width: 100%; }
        .sub-form button { border-radius: 8px; width: 100%; }
    }
</style>

<div class="container-fluid container-custom">
    <a href="{{ url()->previous() == url()->current() ? route('home') : url()->previous() }}" class="go-back-top">&larr; Go Back</a>
    
    <div class="coupon-detail-wrapper">
        <!-- Main Column -->
        <div class="main-col">
            <div class="coupon-main-card">
                @if($coupon->is_exclusive)
                    <div class="status-bar exclusive"><i class="fas fa-star"></i> EXCLUSIVE OFFER</div>
                @elseif($coupon->is_featured)
                    <div class="status-bar featured"><i class="fas fa-fire"></i> FEATURED OFFER</div>
                @elseif($coupon->is_popular)
                    <div class="status-bar popular"><i class="fas fa-heart"></i> POPULAR OFFER</div>
                @else
                    <div class="status-bar"><i class="fas fa-tag"></i> SPECIAL DEAL</div>
                @endif
                
                <div class="card-header-row">
                    <div class="store-logo-wrapper">
                        <img src="{{ getImage(getFilePath('store').'/'.$coupon->store->image) }}" class="store-logo" alt="{{ $coupon->store->name }}">
                    </div>
                    <div class="coupon-title-wrap">
                        <h1 class="coupon-title">{{ $coupon->title }}</h1>
                        <div class="store-badge"><i class="fas fa-store-alt"></i> {{ $coupon->store->name }}</div>
                    </div>
                </div>
                
                <div class="good-news-box">
                    <div class="good-news-text">
                        <h6>Good News! Cashback Applicable</h6>
                        <p>You will earn real wallet cashback on this deal. Just click below to activate and shop as usual.</p>
                    </div>
                </div>
                
                <div class="code-section">
                    @if($coupon->code)
                        <div class="copy-instruction" style="margin-bottom: 20px;">Copy this coupon code on checkout</div>
                    @else
                        <div class="copy-instruction" style="margin-bottom: 20px;">No coupon code required for this deal</div>
                    @endif
                    
                    <div style="display: flex; flex-wrap: wrap; justify-content: center; align-items: center; gap: 15px; margin-bottom: 45px;">
                        @php
                            $finalTargetLink = $coupon->link;
                            if (auth()->check()) {
                                $user = auth()->user();
                                $cleanUsername = str_replace(['-', ' '], '', $user->username);
                                $trackingId = $cleanUsername . '_' . $user->id;
                                
                                if (strpos($finalTargetLink, '?') !== false) {
                                    $finalTargetLink = $finalTargetLink . '&source=' . $trackingId;
                                } else {
                                    $finalTargetLink = $finalTargetLink . '?source=' . $trackingId;
                                }
                            }
                        @endphp

                        @if($coupon->code)
                            <div class="code-box-inner" style="margin-bottom: 0;">
                                <span class="code-text" id="couponCodeText">{{ $coupon->code }}</span>
                                <button class="copy-btn" onclick="copyCouponCode()">COPY CODE</button>
                            </div>
                        @else
                            <button class="copy-btn" style="border-radius: 35px; font-size: 16px; padding: 16px 30px; margin-bottom: 0;" onclick="activateDeal()">ACTIVATE DEAL</button>
                        @endif

                        <a href="{{ route('coupon.redirect', $coupon->id) }}" target="_blank" class="btn-primary-action" style="margin-bottom: 0;">
                            GO TO {{ strtoupper($coupon->store->name) }} WEBSITE &nearr;
                        </a>
                        
                        <button onclick="copyAffiliateLink('{!! $finalTargetLink !!}', this)" class="btn-secondary-action" style="margin-bottom: 0;">
                            <i class="far fa-copy"></i> COPY LINK
                        </button>
                    </div>
                    
                    <div class="stats-row">
                        <span><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($coupon->expire_date)->isPast() ? 'Expired' : 'Ends ' . \Carbon\Carbon::parse($coupon->expire_date)->diffForHumans() }}</span>
                        <span id="clickCountDisplay" data-count="{{ $coupon->click_count }}"><i class="fas fa-users"></i> Used by {{ $coupon->click_count }} (Today)</span>
                    </div>
                </div>
                
                <div class="join-channel">
                    <div>
                        <h6>Don't Miss out on incredible deals and exclusive coupons!</h6>
                        <p>Join our Telegram Channel</p>
                    </div>
                    <a href="#" class="join-btn">JOIN CHANNEL</a>
                </div>
                
                <div class="tnc-box">
                    <h6>T&C's:</h6>
                    <div style="font-size: 13px; color: #555; line-height: 1.6;">
                        {!! $coupon->description !!}
                    </div>
                    
                    <div class="feedback-row">
                        <span>Did this coupon work?</span>
                        <button class="feedback-btn"><i class="far fa-thumbs-up"></i> Yes</button>
                        <button class="feedback-btn"><i class="far fa-thumbs-down"></i> No</button>
                    </div>
                </div>
            </div>
            
            <div class="subscribe-card">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div class="sub-icon">
                        {{ substr(config('app.name'), 0, 1) }}
                    </div>
                    <div class="sub-info">
                        <h6>Subscribe Now</h6>
                        <p>Get The Latest & Best Coupon/Offer Alerts</p>
                    </div>
                </div>
                <form class="sub-form" action="{{route('subscribe')}}" method="POST">
                    @csrf
                    <input type="email" name="email" placeholder="Enter Email" required>
                    <button type="submit">SUBSCRIBE NOW</button>
                </form>
            </div>
        </div>
        
        <!-- Right Sidebar -->
        <div class="side-col">
            <div class="sidebar-title">SIMILAR/RELATED COUPONS</div>
            
            @forelse($relatedCoupons as $rel)
            <a href="{{ route('coupon.details', $rel->id) }}" class="related-card">
                <div class="related-card-img-wrap">
                    <img src="{{ getImage(getFilePath('store').'/'.$rel->store->image) }}" alt="{{ $rel->store->name }}">
                </div>
                <div class="related-card-info">
                    <h6>{{ $rel->title }}</h6>
                    <p>{{ $rel->is_exclusive ? 'Exclusive! ' : '' }}{{ Str::limit(strip_tags($rel->description), 50) }}</p>
                </div>
            </a>
            @empty
            <p style="font-size: 13px; color: #888;">No similar coupons found.</p>
            @endforelse
            
        </div>
    </div>
</div>

<script>
    function activateDeal() {
        // Dynamically increment the count on screen so user sees it change instantly
        let countDisplay = document.getElementById('clickCountDisplay');
        if(countDisplay) {
            let currentCount = parseInt(countDisplay.getAttribute('data-count'));
            currentCount += 1;
            countDisplay.setAttribute('data-count', currentCount);
            countDisplay.innerHTML = '<i class="fas fa-users"></i> Used by ' + currentCount + ' (Today)';
        }

        // Visual feedback
        const btn = document.querySelector('.copy-btn');
        btn.innerText = "ACTIVATING...";
        
        // Open the Store via Laravel Route (to track clicks in Database)
        window.open("{{ route('coupon.redirect', $coupon->id) }}", "_blank");
        
        setTimeout(() => {
            btn.innerText = "ACTIVATE DEAL";
        }, 2000);
    }
    function copyAffiliateLink(link, btnElement) {
        navigator.clipboard.writeText(link).then(() => {
            let originalText = btnElement.innerHTML;
            btnElement.innerHTML = '<i class="fas fa-check" style="color: #48bb78;"></i> COPIED!';
            btnElement.style.background = '#e6fffa';
            
            setTimeout(() => {
                btnElement.innerHTML = originalText;
                btnElement.style.background = '#edf2f7';
            }, 2500);
        });
    }

    function copyCouponCode() {
        const text = document.getElementById('couponCodeText').innerText;
        
        // Dynamically increment the count on screen so user sees it change instantly
        let countDisplay = document.getElementById('clickCountDisplay');
        if(countDisplay) {
            let currentCount = parseInt(countDisplay.getAttribute('data-count'));
            currentCount += 1;
            countDisplay.setAttribute('data-count', currentCount);
            countDisplay.innerHTML = '<i class="fas fa-users"></i> Used by ' + currentCount + ' (Today)';
        }

        // Open the Store via Laravel Route (to track clicks in Database)
        // Doing this BEFORE the promise so browsers don't block the popup!
        window.open("{{ route('coupon.redirect', $coupon->id) }}", "_blank");
        
        navigator.clipboard.writeText(text).then(() => {
            const btn = document.querySelector('.copy-btn');
            btn.innerText = "COPIED!";
            btn.style.background = "#28a745";
            btn.style.color = "#fff";
            
            setTimeout(() => {
                btn.innerText = "COPY CODE";
                btn.style.background = "#e3f2fd";
                btn.style.color = "#007bff";
            }, 3000);
        });
    }
</script>
@endsection
