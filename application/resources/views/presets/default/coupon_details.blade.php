@extends($activeTemplate.'layouts.frontend')
@section('content')
<style>
    body { background-color: #f4f5f8; }
    .go-back-top {
        display: inline-block;
        margin: 20px 0;
        color: #007bff;
        font-weight: 500;
        text-decoration: none;
    }
    .go-back-top:hover { text-decoration: underline; }
    
    .coupon-detail-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 40px;
    }
    .main-col {
        flex: 1;
        min-width: 60%;
    }
    .side-col {
        width: 350px;
    }
    
    /* Main Card */
    .coupon-main-card {
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        margin-bottom: 20px;
    }
    .exclusive-bar {
        background: #e6ff00;
        padding: 5px 20px;
        font-weight: 800;
        font-size: 11px;
        color: #333;
        letter-spacing: 1px;
        text-transform: uppercase;
    }
    .card-header-row {
        display: flex;
        align-items: center;
        padding: 30px;
        border-bottom: 1px solid #eee;
    }
    .exclusive-badge {
        font-weight: 700;
        color: #555;
        font-size: 14px;
        line-height: 1.2;
        padding-right: 20px;
        border-right: 1px solid #eee;
    }
    .store-logo {
        height: 60px;
        width: auto;
        margin: 0 30px;
    }
    .coupon-title {
        font-size: 20px;
        font-weight: 700;
        color: #111;
        margin: 0;
    }
    
    /* Good News Box */
    .good-news-box {
        background: #e8f5e9;
        border: 1px solid #c8e6c9;
        border-radius: 6px;
        padding: 15px 20px;
        margin: 20px 30px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .good-news-box img { width: 30px; }
    .good-news-text h6 { margin: 0 0 3px 0; color: #2e7d32; font-weight: 700; font-size: 15px; }
    .good-news-text p { margin: 0; font-size: 13px; color: #388e3c; }
    
    /* Code Box */
    .code-section {
        text-align: center;
        padding: 20px 30px;
    }
    .copy-instruction {
        font-size: 12px;
        color: #666;
        margin-bottom: 10px;
    }
    .code-box-inner {
        display: inline-flex;
        align-items: center;
        border: 2px dashed #81c784;
        border-radius: 6px;
        padding: 5px 5px 5px 30px;
        background: #fff;
        margin-bottom: 20px;
    }
    .code-text {
        font-size: 26px;
        font-weight: 800;
        letter-spacing: 2px;
        color: #111;
        margin-right: 30px;
    }
    .copy-btn {
        background: #e3f2fd;
        color: #007bff;
        border: none;
        padding: 12px 25px;
        font-weight: 700;
        border-radius: 4px;
        cursor: pointer;
        transition: 0.2s;
    }
    .copy-btn:hover { background: #bbdefb; }
    
    .go-website-link {
        display: block;
        color: #007bff;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        margin-bottom: 20px;
    }
    
    .stats-row {
        display: flex;
        justify-content: center;
        gap: 20px;
        color: #666;
        font-size: 13px;
        padding-bottom: 30px;
    }
    .stats-row span i { margin-right: 5px; }
    .stats-row .verified { color: #28a745; }
    
    .join-channel {
        background: #0088cc;
        color: #fff;
        padding: 15px 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .join-channel h6 { color: #fff; margin: 0 0 5px 0; font-size: 16px; font-weight: 700; }
    .join-channel p { margin: 0; font-size: 13px; opacity: 0.9; }
    .join-btn { background: #fff; color: #0088cc; padding: 8px 20px; border-radius: 20px; font-weight: 700; font-size: 13px; text-decoration: none; }
    
    /* T&C */
    .tnc-box {
        padding: 30px;
        border-top: 1px solid #eee;
    }
    .tnc-box h6 { font-weight: 700; margin-bottom: 15px; font-size: 15px; }
    .tnc-box p { font-size: 13px; color: #555; line-height: 1.6; margin-bottom: 5px; }
    
    .feedback-row {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        padding: 20px 0;
        border-top: 1px solid #eee;
        margin-top: 20px;
    }
    .feedback-row span { font-size: 13px; color: #666; font-weight: 600; }
    .feedback-btn {
        border: 1px solid #ccc;
        background: #fff;
        padding: 5px 15px;
        border-radius: 4px;
        font-size: 13px;
        color: #333;
        cursor: pointer;
    }
    
    /* Subscribe Box */
    .subscribe-card {
        background: #f8f9fa;
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 20px 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .sub-icon { width: 40px; height: 40px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #007bff; font-weight: bold; font-size: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
    .sub-info h6 { margin: 0 0 3px 0; font-weight: 700; font-size: 15px; }
    .sub-info p { margin: 0; font-size: 12px; color: #666; }
    .sub-form { display: flex; gap: 0; }
    .sub-form input { border: 1px solid #ccc; padding: 10px 15px; border-radius: 4px 0 0 4px; outline: none; font-size: 13px; min-width: 200px; }
    .sub-form button { background: #007bff; color: #fff; border: none; padding: 10px 20px; font-size: 13px; font-weight: 700; border-radius: 0 4px 4px 0; cursor: pointer; }
    
    /* Sidebar */
    .sidebar-title { font-size: 12px; font-weight: 800; color: #555; margin-bottom: 15px; letter-spacing: 0.5px; text-transform: uppercase; }
    .related-card {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 15px;
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 10px;
        text-decoration: none;
        transition: 0.2s;
    }
    .related-card:hover { box-shadow: 0 4px 15px rgba(0,0,0,0.05); transform: translateY(-2px); }
    .related-card img { height: 40px; width: auto; object-fit: contain; }
    .related-card-info h6 { margin: 0 0 5px 0; color: #007bff; font-size: 14px; font-weight: 700; }
    .related-card-info p { margin: 0; font-size: 11px; color: #666; line-height: 1.4; }
    
    @media(max-width: 991px) {
        .coupon-detail-wrapper { flex-direction: column; }
        .side-col { width: 100%; }
        .card-header-row { flex-direction: column; text-align: center; }
        .exclusive-badge { border-right: none; border-bottom: 1px solid #eee; padding: 0 0 15px 0; margin-bottom: 15px; }
        .store-logo { margin: 0 0 15px 0; }
        .subscribe-card { flex-direction: column; text-align: center; gap: 15px; }
        .sub-form { width: 100%; flex-direction: column; gap: 10px; }
        .sub-form input { border-radius: 4px; }
        .sub-form button { border-radius: 4px; }
    }
</style>

<div class="container-fluid container-custom">
    <a href="{{ url()->previous() == url()->current() ? route('home') : url()->previous() }}" class="go-back-top">&larr; Go Back</a>
    
    <div class="coupon-detail-wrapper">
        <!-- Main Column -->
        <div class="main-col">
            <div class="coupon-main-card">
                <div class="exclusive-bar">EXCLUSIVE</div>
                <div class="card-header-row">
                    <div class="exclusive-badge">
                        {{ config('app.name') }}<br>EXCLUSIVE
                    </div>
                    <img src="{{ getImage(getFilePath('store').'/'.$coupon->store->image) }}" class="store-logo" alt="{{ $coupon->store->name }}">
                    <h1 class="coupon-title">{{ $coupon->title }}</h1>
                </div>
                
                <div class="good-news-box">
                    <div class="good-news-text">
                        <h6>Good News! Cashback Applicable</h6>
                        <p>You will earn real wallet cashback on this deal. Just click below to activate and shop as usual.</p>
                    </div>
                </div>
                
                <div class="code-section">
                    @if($coupon->code)
                        <div class="copy-instruction">Copy this coupon code on checkout</div>
                        <div class="code-box-inner">
                            <span class="code-text" id="couponCodeText">{{ $coupon->code }}</span>
                            <button class="copy-btn" onclick="copyCouponCode()">COPY CODE</button>
                        </div>
                    @else
                        <div class="copy-instruction">No coupon code required for this deal</div>
                        <div class="code-box-inner" style="justify-content: center; padding: 0;">
                            <button class="copy-btn" style="width: 100%; border-radius: 8px; font-size: 16px; padding: 15px;" onclick="activateDeal()">ACTIVATE DEAL</button>
                        </div>
                    @endif
                    
                    <a href="{{ $coupon->link }}" target="_blank" class="go-website-link">GO TO {{ strtoupper($coupon->store->name) }} WEBSITE &nearr;</a>
                    
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
                <img src="{{ getImage(getFilePath('store').'/'.$rel->store->image) }}" alt="{{ $rel->store->name }}">
                <div class="related-card-info">
                    <h6>{{ $rel->title }}</h6>
                    <p>Exclusive! {{ Str::limit(strip_tags($rel->description), 50) }}</p>
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
