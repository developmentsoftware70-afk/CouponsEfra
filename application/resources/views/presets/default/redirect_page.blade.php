<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle }} - {{ config('app.name') }}</title>
    <!-- Include FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background-color: #FAFAFA;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .redirect-container {
            background: #ffffff;
            width: 90%;
            max-width: 700px;
            border-radius: 24px;
            padding: 80px 60px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.03), 0 2px 10px rgba(0,0,0,0.02);
            text-align: center;
        }

        .loader {
            width: 70px;
            height: 70px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3182ce;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 50px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        h2 {
            font-size: 34px;
            font-weight: 700;
            color: #1a202c;
            margin: 0 0 30px 0;
            letter-spacing: -0.5px;
        }
        
        p {
            font-size: 19px;
            color: #718096;
            line-height: 1.8;
            margin: 0 auto 70px;
            max-width: 550px;
            font-weight: 400;
        }

        .transfer-box {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 100px;
            margin-bottom: 70px;
        }

        .logo-wrapper {
            background: #ffffff;
            width: 190px;
            height: 95px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #edf2f7;
            box-shadow: 0 6px 15px rgba(0,0,0,0.03);
        }

        .logo-wrapper img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        /* Minimalist animated dots */
        .animated-dots {
            display: flex;
            gap: 15px;
        }

        .dot {
            width: 12px;
            height: 12px;
            background-color: #cbd5e0;
            border-radius: 50%;
            animation: bounce 1.4s infinite ease-in-out both;
        }

        .dot:nth-child(1) { animation-delay: -0.32s; }
        .dot:nth-child(2) { animation-delay: -0.16s; }
        .dot:nth-child(3) { animation-delay: 0s; }

        @keyframes bounce {
            0%, 80%, 100% { transform: scale(0); background-color: #cbd5e0; }
            40% { transform: scale(1); background-color: #3182ce; }
        }

        .manual-link {
            font-size: 15px;
            color: #a0aec0;
            margin-top: 30px;
        }
        .manual-link a {
            color: #4a5568;
            text-decoration: underline;
            font-weight: 500;
            transition: color 0.2s;
        }
        .manual-link a:hover {
            color: #1a202c;
        }

        .copy-link-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            color: #4a5568;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            margin-top: 25px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .copy-link-btn:hover {
            background: #edf2f7;
            color: #2d3748;
        }
        .copy-link-btn i {
            color: #a0aec0;
        }
        
        @media(max-width: 768px) {
            .redirect-container { padding: 60px 30px; }
            h2 { font-size: 26px; }
            p { font-size: 17px; margin-bottom: 50px; }
            .transfer-box { flex-direction: column; gap: 40px; }
            .animated-dots { transform: rotate(90deg); }
        }
    </style>
</head>
<body>

    <div class="redirect-container">
        
        <div class="loader"></div>
        
        <h2>One moment, please.</h2>
        
        @if($coupon->is_cashback == 1)
            <p>We are safely routing you to <strong>{{ $coupon->store->name ?? 'the store' }}</strong>.<br>Shop normally and your cashback will be tracked automatically.</p>
        @else
            <p>We are safely transferring you to <strong>{{ $coupon->store->name ?? 'the store' }}</strong>.<br>Grab your deal and enjoy shopping!</p>
        @endif
        
        <div class="transfer-box">
            <div class="logo-wrapper">
                <img src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="{{ config('app.name') }}">
            </div>
            
            <div class="animated-dots">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>
            
            <div class="logo-wrapper">
                @if(isset($coupon->store->image))
                    <img src="{{ getImage(getFilePath('store').'/'.$coupon->store->image) }}" alt="{{ $coupon->store->name }}">
                @else
                    <span style="font-weight: 600; font-size: 20px; color: #4a5568;">{{ $coupon->store->name ?? 'STORE' }}</span>
                @endif
            </div>
        </div>
        
        <div class="manual-link">
            Taking too long? <a href="{{ $link }}">Click here to proceed instantly</a>
        </div>
        
        <button class="copy-link-btn" onclick="copyFinalLink()" id="copyBtn">
            <i class="far fa-copy"></i> Copy Store Link
        </button>
        
    </div>
    
    <script>
        function copyFinalLink() {
            var finalLink = "{!! $link !!}";
            navigator.clipboard.writeText(finalLink).then(function() {
                var btn = document.getElementById('copyBtn');
                btn.innerHTML = '<i class="fas fa-check" style="color: #48bb78;"></i> Copied!';
                btn.style.borderColor = '#48bb78';
                
                setTimeout(function() {
                    btn.innerHTML = '<i class="far fa-copy"></i> Copy Store Link';
                    btn.style.borderColor = '#e2e8f0';
                }, 2000);
            });
        }

        setTimeout(function() {
            window.location.href = "{!! $link !!}";
        }, 4000); // Increased to 4s to give them time to click copy if they want
    </script>

</body>
</html>
