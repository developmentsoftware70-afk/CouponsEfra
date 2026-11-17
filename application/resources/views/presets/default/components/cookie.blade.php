@php
    // Auto-accept GDPR cookie in backend
    if (!\Cookie::get('gdpr_cookie')) {
        \Cookie::queue('gdpr_cookie', config('app.name', 'CouponsEfra'), 43200);
    }
@endphp
<script>
    // Automatically mark cookies as accepted in browser and permanently hide
    try {
        document.cookie = "gdpr_cookie=1; path=/; max-age=31536000";
        var ckCard = document.querySelector('.cookies-card');
        if (ckCard) {
            ckCard.style.display = 'none';
            ckCard.remove();
        }
    } catch(e) {}
</script>
