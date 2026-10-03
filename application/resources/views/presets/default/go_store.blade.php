@extends($activeTemplate.'layouts.master')
@section('content')
<style>
    body, html {
        background-color: #f4f5f8; /* Light gray background */
        height: 100%;
        margin: 0;
        font-family: 'Inter', sans-serif;
    }
    .redirect-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding: 20px;
    }
    .redirect-header {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        padding: 15px 0;
        text-align: center;
        background: #fff;
        border-bottom: 1px solid #eaeaea;
    }
    .redirect-header img {
        height: 40px;
    }
    .redirect-card {
        background: #fff;
        padding: 40px 50px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        text-align: center;
        max-width: 500px;
        width: 100%;
        margin-top: 60px;
    }
    .success-icon {
        color: #28a745;
        font-size: 24px;
        margin-right: 8px;
    }
    .success-text {
        color: #28a745;
        font-weight: 700;
        font-size: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 5px;
    }
    .sub-text {
        color: #6c757d;
        font-size: 14px;
        margin-bottom: 30px;
    }
    .code-box {
        border: 2px dashed #a5d6a7;
        padding: 15px;
        font-size: 28px;
        font-weight: 800;
        color: #111;
        letter-spacing: 2px;
        border-radius: 8px;
        margin-bottom: 40px;
        background: #f1f8e9;
    }
    .loader-text {
        color: #555;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 15px;
    }
    .spinner {
        width: 20px;
        height: 20px;
        border: 3px solid #e0e0e0;
        border-top-color: #007bff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    @keyframes spin { 
        to { transform: rotate(360deg); } 
    }
    .continue-btn {
        color: #007bff;
        font-weight: 600;
        text-decoration: none;
        font-size: 15px;
    }
    .continue-btn:hover {
        text-decoration: underline;
    }
    .go-back-link {
        margin-top: 30px;
        color: #888;
        font-size: 14px;
        text-decoration: none;
    }
    .go-back-link:hover {
        text-decoration: underline;
    }
    
    /* Hide the default header/footer for the redirect page */
    #header, .footer, .dashboard-top-header { display: none !important; }
    .dashboard-container-wrapper { padding: 0 !important; }
    #loading { display: none !important; } /* Hide the default preloader */
</style>

<div class="redirect-header">
    <a href="{{ route('home') }}">
        <img src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="{{ config('app.name') }}">
    </a>
</div>

<div class="redirect-wrapper">
    <div class="redirect-card">
        <div class="success-text">
            <i class="fas fa-check-circle success-icon"></i> Code Successfully Copied!
        </div>
        <p class="sub-text">Paste this code on checkout & carry on shopping</p>
        
        <div class="code-box">
            {{ $coupon->code }}
        </div>
        
        <div class="loader-text">
            <div class="spinner"></div> Opening {{ $coupon->store->name ?? 'Store' }}...
        </div>
        
        <a href="{{ $coupon->link }}" class="continue-btn" id="continueLink">Continue to store</a>
    </div>
    
    <a href="javascript:window.close();" class="go-back-link">Go Back</a>
</div>

<script>
    // Copy code to clipboard on load just to be sure
    const code = "{{ $coupon->code }}";
    navigator.clipboard.writeText(code).catch(err => console.log('Clipboard copy failed: ', err));

    // Redirect after 3 seconds
    setTimeout(function() {
        window.location.href = "{{ $coupon->link }}";
    }, 3000);
</script>
@endsection
