<!--========================== Header section Start ==========================-->
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');

/* Main Header Container - 1:1 Replica */
.header {
    background: #ffffff !important;
    position: sticky !important; 
    top: 0 !important;
    width: 100% !important;
    z-index: 9999999 !important; /* Extremely high z-index */
    font-family: 'Inter', sans-serif !important;
    border-bottom: 1px solid #eaeaec !important; /* Faint gray bottom line */
    box-shadow: none !important; /* No shadow, just clean border */
    transform: none !important; /* Stop JS from moving it */
    animation: none !important; /* Stop JS animations */
    transition: none !important; /* Stop JS delays */
    overflow: visible !important; /* Ensure children can spill out */
}

.header .container-fluid, 
.header .row, 
.header-wrapper, 
.menu-right-wrapper, 
.menu-right-wrapper ul,
.hero-search-bar {
    overflow: visible !important;
}

.header-wrapper {
    padding: 10px 20px !important;
    display: flex !important;
    flex-wrap: wrap !important;
    align-items: center !important;
    max-width: 1400px !important;
    margin: 0 auto !important;
}

/* Hamburger Menu (Left) */
.sidebar-menu-show-hide {
    order: 0 !important;
    background: transparent !important;
    color: #111111 !important;
    font-size: 24px !important;
    margin-right: 15px !important;
    padding: 0 !important;
    border: none !important;
    cursor: pointer !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: auto !important;
    height: auto !important;
}

/* Logo (Left) */
.header-menu-wrapper {
    order: 1 !important;
    padding: 0 !important;
}
.logo-wrapper {
    min-width: 200px !important;
    display: flex !important;
    align-items: center !important;
}
.logo-wrapper img {
    height: 55px !important; /* Increased logo size */
    width: 200px !important; /* Fixed width to prevent FOUC */
    object-fit: contain !important;
    object-position: left center !important;
}

/* Search Bar & Right Section Container */
.menu-right-wrapper {
    order: 3 !important;
    flex-grow: 1 !important;
    display: flex !important;
    justify-content: flex-end !important;
}
.menu-right-wrapper ul {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    width: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
}

/* Search Bar Center (Soft Gray Rounded Pill) */
.hero-search-bar {
    flex-grow: 1 !important;
    max-width: 650px !important;
    margin-left: 40px !important;
    margin-right: auto !important;
    position: relative !important;
    z-index: 999999 !important;
}
.hero-search-bar form {
    position: relative !important;
    display: flex !important;
    align-items: center !important;
    background: #f4f5f8 !important; 
    border-radius: 50px !important; 
    border: 1px solid #e5e7eb !important;
    height: 44px !important;
    padding: 0 18px !important; /* Safe space inside the pill curve */
    gap: 10px !important; /* Proper distance between icon and text */
    transition: all 0.2s ease !important;
    box-sizing: border-box !important;
    overflow: hidden !important;
}
.hero-search-bar form:focus-within {
    border-color: #cbd5e1 !important;
    background: #ffffff !important;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05) !important;
}
.hero-search-bar button.search-btn {
    position: static !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    color: #64748b !important;
    font-size: 15px !important;
    cursor: pointer !important;
    padding: 0 !important;
    margin: 0 !important;
    width: auto !important;
    height: auto !important;
    flex-shrink: 0 !important;
    line-height: 1 !important;
}
.hero-search-bar button.search-btn i {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 15px !important;
    color: #64748b !important;
}
.hero-search-bar button.search-btn span {
    display: none !important; /* Hide extra text */
}
.hero-search-bar input.form--control {
    background: transparent !important;
    border: none !important;
    font-size: 14px !important;
    color: #1e293b !important;
    width: 100% !important;
    flex: 1 !important;
    height: 100% !important;
    outline: none !important;
    box-shadow: none !important;
    padding: 0 !important; /* Natural starting position */
    margin: 0 !important;
    font-family: 'Inter', sans-serif !important;
    line-height: 44px !important;
}
.hero-search-bar input.form--control::placeholder {
    color: #7b8593 !important;
}

/* Right Side - Login / Signup (Plain Text) */
.login-registration-list__item {
    margin-left: 25px !important;
    position: relative !important;
}
/* Divider Line */
.login-registration-list__item::before {
    content: '' !important;
    position: absolute !important;
    left: -25px !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    height: 20px !important;
    width: 1px !important;
    background: #eaeaec !important; /* Faint gray divider */
}
.login-registration-list__item a {
    background: transparent !important; 
    color: #000000 !important;
    font-weight: 600 !important; /* Bold like image */
    font-size: 14px !important; /* Reverted login font size */
    text-decoration: none !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 8px !important;
    padding: 5px !important;
    border-radius: 0 !important;
    border: none !important;
}
.login-registration-list__item a i {
    font-size: 18px !important; /* Reverted icon size */
    color: #000000 !important;
}
.login-registration-list__item a:hover {
    color: #f37254 !important; /* Orange hover effect */
}
.login-registration-list__item a:hover i {
    color: #f37254 !important;
}

/* Logout text */
.login-registration-list__item a.login-registration-list__link {
    background: transparent !important;
    color: #000000 !important;
}
.login-registration-list__item a.login-registration-list__link:hover {
    color: #f37254 !important;
}

/* Logged in User Text */
.home a {
    color: #000000 !important;
    font-weight: 600 !important;
    font-size: 14px !important; /* Reverted font size */
    padding-right: 15px !important;
}

/* Nav Links */
.nav-links-container {
    padding: 0 20px !important;
}
.nav-links-container .main-menu {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 25px !important;
    margin: 0 !important;
    padding: 0 !important;
}
.nav-links-container .main-menu li {
    list-style: none !important;
}
.nav-links-container .main-menu li a {
    color: #333333 !important;
    font-weight: 500 !important; /* Reverted weight */
    font-size: 14px !important; /* Reverted menu font size */
    padding: 12px 0 !important;
    display: block !important;
    border-bottom: 2px solid transparent !important;
    border-radius: 0 !important;
    transition: all 0.2s !important;
}
.nav-links-container .main-menu li a:hover,
.nav-links-container .main-menu li a.active {
    color: #f37254 !important;
    border-bottom-color: #f37254 !important;
    background: transparent !important;
}

/* Profile Dropdown (Google Style) */
.profile-dropdown {
    position: relative !important;
    margin-left: 20px !important;
    display: flex !important;
    align-items: center !important;
    height: 32px !important; /* Restrict height to exactly the avatar height */
    width: 32px !important; /* Restrict width */
}
/* Invisible bridge to keep hover active while moving mouse down */
.profile-dropdown::after {
    content: '' !important;
    position: absolute !important;
    bottom: -15px !important;
    left: -20px !important;
    width: 80px !important;
    height: 20px !important;
}
.profile-avatar {
    width: 32px !important;
    height: 32px !important;
    border-radius: 50% !important;
    background: #5c3b99 !important; /* Elegant Purple */
    color: white !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 14px !important;
    font-weight: 600 !important;
    cursor: pointer !important;
    user-select: none !important;
}
.profile-dropdown-menu {
    position: absolute !important;
    top: 100% !important;
    margin-top: 10px !important; /* Replaces the top:120% gap */
    right: 0 !important;
    width: 280px !important;
    background: #fff !important;
    border-radius: 12px !important;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
    border: 1px solid #eaeaec !important;
    opacity: 0 !important;
    visibility: hidden !important;
    pointer-events: none !important; /* PREVENT HOVER WHEN CLOSED */
    transform: translateY(10px) !important;
    transition: all 0.3s ease !important;
    z-index: 9999 !important;
    cursor: default !important;
}
.profile-dropdown:hover .profile-dropdown-menu {
    opacity: 1 !important;
    visibility: visible !important;
    pointer-events: auto !important; /* ENABLE HOVER WHEN OPEN */
    transform: translateY(0) !important;
}
.profile-dropdown-header {
    padding: 20px !important;
    text-align: center !important;
    border-bottom: 1px solid #eaeaec !important;
    background: #f8f9fa !important;
    border-radius: 12px 12px 0 0 !important;
}
.profile-dropdown-header .big-avatar {
    width: 60px !important;
    height: 60px !important;
    border-radius: 50% !important;
    background: #5c3b99 !important;
    color: white !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 28px !important;
    font-weight: 600 !important;
    margin: 0 auto 10px !important;
}
.profile-dropdown-header h6 {
    margin: 0 0 5px 0 !important;
    font-size: 16px !important;
    font-weight: 600 !important;
    color: #111 !important;
}
.profile-dropdown-header p {
    margin: 0 !important;
    font-size: 13px !important;
    color: #666 !important;
    word-break: break-all !important;
}
.profile-dropdown-menu ul {
    list-style: none !important;
    padding: 10px 0 !important;
    margin: 0 !important;
    display: block !important;
}
.profile-dropdown-menu ul li {
    padding: 0 !important;
    display: block !important;
    margin: 0 !important;
}
.profile-dropdown-menu ul li a {
    display: flex !important;
    align-items: center !important;
    padding: 10px 20px !important;
    color: #333 !important;
    text-decoration: none !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    transition: background 0.2s !important;
    border: none !important;
}
.profile-dropdown-menu ul li a i {
    width: 24px !important;
    color: #666 !important;
    font-size: 16px !important;
}
.profile-dropdown-menu ul li a:hover {
    background: #f4f5f8 !important;
    color: #111 !important;
}
.profile-dropdown-menu ul li a:hover i {
    color: #f37254 !important;
}
/* Tablet specific adjustments to fit everything on one line (992px - 1250px) */
@media (min-width: 992px) and (max-width: 1250px) {
    .header-wrapper {
        padding: 5px 10px !important;
    }
    .logo-wrapper {
        min-width: 150px !important;
    }
    .logo-wrapper img {
        width: 150px !important;
        height: 45px !important;
    }
    .hero-search-bar {
        margin-left: 10px !important;
        max-width: 250px !important; /* shrink search bar */
    }
    .nav-links-container .main-menu {
        gap: 12px !important; /* smaller gap between links */
    }
    .nav-links-container .main-menu li a {
        font-size: 12px !important; /* smaller font */
    }
    .login-registration-list__item {
        margin-left: 10px !important;
    }
    .login-registration-list__item::before {
        left: -10px !important;
    }
    .login-registration-list__item a {
        font-size: 12px !important;
    }
    .profile-dropdown {
        margin-left: 10px !important;
    }
}

/* Mobile responsive */
@media (min-width: 992px) {
    .sidebar-menu-show-hide {
        display: none !important;
    }
}
@media (max-width: 991px) {
    .header-wrapper {
        padding: 10px 15px !important;
        display: flex !important;
        flex-wrap: wrap !important;
        align-items: center !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
    .sidebar-menu-show-hide {
        order: 1 !important;
        margin-right: 15px !important;
        font-size: 22px !important;
        flex-shrink: 0 !important;
        display: block !important;
    }
    .header-menu-wrapper {
        order: 2 !important;
        flex-grow: 1 !important;
        display: flex !important;
    }
    .logo-wrapper {
        min-width: auto !important;
        flex-grow: 0 !important;
        flex-shrink: 1 !important;
    }
    .logo-wrapper img {
        width: auto !important;
        max-width: 140px !important;
        height: 35px !important;
        object-fit: contain !important;
    }
    .mobile-header-auth {
        order: 3 !important;
        flex-shrink: 0 !important;
        display: flex !important;
        align-items: center !important;
    }
    .mobile-header-auth a {
        padding: 0 !important;
        display: flex !important;
        align-items: center !important;
    }
    .mobile-header-auth a i {
        font-size: 26px !important;
        color: #111 !important;
    }
    .mobile-header-auth .profile-avatar {
        width: 32px !important;
        height: 32px !important;
        border-radius: 50% !important;
        background: #5c3b99 !important;
        color: white !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 14px !important;
        font-weight: 600 !important;
    }
    .menu-right-wrapper {
        order: 4 !important;
        width: 100% !important;
        margin-top: 10px !important;
        flex-grow: 1 !important;
    }
    .menu-right-wrapper ul {
        display: flex !important;
        align-items: center !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .menu-right-wrapper .profile-dropdown,
    .menu-right-wrapper .login-registration-list__item,
    .menu-right-wrapper .nav-links-container,
    .menu-right-wrapper .language {
        display: none !important;
    }
    .hero-search-bar { 
        display: block !important; 
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        list-style: none !important;
        position: relative !important;
    }
    .hero-search-bar form {
        display: flex !important;
        align-items: center !important;
        height: 40px !important;
        border: 1px solid #d1d5db !important;
        width: 100% !important;
        border-radius: 20px !important;
        background: #f4f5f8 !important;
        padding: 0 14px !important;
        gap: 10px !important;
    }
    .hero-search-bar form input {
        font-size: 14px !important;
        padding: 0 !important;
        width: 100% !important;
        background: transparent !important;
        border: none !important;
        outline: none !important;
        flex-grow: 1 !important;
    }
    .hero-search-bar button.search-btn {
        position: static !important;
        width: auto !important;
        background: transparent !important;
        border: none !important;
        color: #64748b !important;
        padding: 0 !important;
        display: flex !important;
        align-items: center !important;
    }
    .hero-search-bar button.search-btn span {
        display: none !important;
    }
}

@media (min-width: 992px) {
    .mobile-header-auth { display: none !important; }
}

/* CashKaro-Style 2-Column Search Recommendation Box */
.search-recommendation-box {
    display: none;
    position: absolute !important;
    top: calc(100% + 8px) !important;
    left: 0 !important;
    width: 680px !important;
    max-width: 92vw !important;
    background: #ffffff !important;
    border: 1px solid #d9e2ec !important;
    border-radius: 16px !important;
    box-shadow: 0 15px 40px rgba(15, 23, 42, 0.16), 0 2px 8px rgba(15, 23, 42, 0.06) !important;
    z-index: 9999999 !important;
    padding: 0 !important;
    overflow: hidden !important;
    text-align: left !important;
}

/* Open on focus, click, or active class */
.hero-search-bar:focus-within .search-recommendation-box,
.search-recommendation-box.active {
    display: block !important;
}

.ck-dropdown-grid {
    display: flex !important;
    min-height: 380px !important;
}

/* Left Column */
.ck-left-col {
    width: 48% !important;
    background: #edf1f6 !important;
    border-right: 1px solid #dbe3ed !important;
    display: flex !important;
    flex-direction: column !important;
}

.ck-col-header {
    padding: 14px 18px !important;
    font-size: 13px !important;
    font-weight: 700 !important;
    color: #1e293b !important;
    background: #e2e8f0 !important;
    border-bottom: 1px solid #cbd5e1 !important;
    letter-spacing: -0.1px !important;
}

.ck-trending-list {
    display: flex !important;
    flex-direction: column !important;
    overflow-y: auto !important;
    max-height: 340px !important;
}

.ck-list-item {
    padding: 10px 18px !important;
    border-bottom: 1px solid #e2e8f0 !important;
    display: block !important;
    text-decoration: none !important;
    transition: background 0.15s ease !important;
}
.ck-list-item:last-child {
    border-bottom: none !important;
}
.ck-list-item:hover {
    background: #dbe4ef !important;
}
.ck-item-title {
    font-size: 13px !important;
    font-weight: 600 !important;
    color: #1e293b !important;
    display: block !important;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    line-height: 1.35 !important;
}
.ck-item-tag {
    font-size: 12px !important;
    font-weight: 600 !important;
    color: #1d4ed8 !important;
    display: block !important;
    margin-top: 1px !important;
}

/* Right Column */
.ck-right-col {
    width: 52% !important;
    background: #ffffff !important;
    padding: 14px !important;
    display: flex !important;
    flex-direction: column !important;
    justify-content: space-between !important;
    gap: 12px !important;
}

/* Big Hero Card */
.ck-hero-card {
    background: #f1f4f9 !important;
    border-radius: 12px !important;
    padding: 24px 16px !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    text-decoration: none !important;
    gap: 12px !important;
    transition: all 0.2s ease !important;
    flex-grow: 1 !important;
}
.ck-hero-card:hover {
    background: #e9eff7 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05) !important;
}
.ck-hero-logo {
    height: 48px !important;
    max-width: 170px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}
.ck-hero-logo img {
    max-height: 100% !important;
    max-width: 100% !important;
    object-fit: contain !important;
}
.ck-hero-cashback {
    font-size: 16px !important;
    font-weight: 800 !important;
    color: #1d4ed8 !important;
    letter-spacing: -0.2px !important;
    text-align: center !important;
}

/* Bottom Picks Grid */
.ck-picks-grid {
    display: grid !important;
    grid-template-columns: 1fr 1fr !important;
    gap: 10px !important;
}
.ck-pick-card {
    background: #f1f4f9 !important;
    border-radius: 10px !important;
    padding: 12px 10px !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    text-decoration: none !important;
    justify-content: space-between !important;
    min-height: 125px !important;
    transition: all 0.2s ease !important;
}
.ck-pick-card:hover {
    background: #e9eff7 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05) !important;
}
.ck-pick-header {
    font-size: 11px !important;
    font-weight: 700 !important;
    color: #1e293b !important;
    text-align: center !important;
    margin-bottom: 6px !important;
}
.ck-pick-logo {
    height: 26px !important;
    max-width: 85px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    margin-bottom: 6px !important;
}
.ck-pick-brand {
    font-size: 18px !important;
    font-weight: 800 !important;
    color: #0f172a !important;
    letter-spacing: -0.5px !important;
    margin: 6px 0 8px 0 !important;
    line-height: 1 !important;
}
.ck-pick-brand.ck-amazon {
    font-family: inherit !important;
    color: #111111 !important;
}
.ck-pick-brand.ck-amazon span {
    color: #ea580c !important;
    font-size: 14px !important;
}
.ck-pick-desc {
    font-size: 11px !important;
    font-weight: 500 !important;
    color: #475569 !important;
    line-height: 1.3 !important;
    text-align: center !important;
    display: -webkit-box !important;
    -webkit-line-clamp: 2 !important;
    -webkit-box-orient: vertical !important;
    overflow: hidden !important;
}

/* Live Search Results (typing state) */
.search-rec-results {
    padding: 16px !important;
}
.search-rec-section {
    margin-bottom: 16px !important;
}
.search-rec-section:last-child {
    margin-bottom: 0 !important;
}
.search-rec-title {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.6px !important;
    color: #64748b !important;
    margin-bottom: 10px !important;
}
.search-rec-title span i {
    margin-right: 6px !important;
}
.search-rec-items-list {
    display: flex !important;
    flex-direction: column !important;
    gap: 4px !important;
}
.search-rec-item {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    padding: 8px 10px !important;
    border-radius: 8px !important;
    text-decoration: none !important;
    transition: background 0.15s ease !important;
}
.search-rec-item:hover {
    background: #f1f5f9 !important;
}
.search-rec-item-left {
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
    flex-grow: 1 !important;
    min-width: 0 !important;
}
.search-rec-item-icon {
    width: 30px !important;
    height: 30px !important;
    border-radius: 6px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    background: #e2e8f0 !important;
    color: #475569 !important;
    font-size: 13px !important;
    flex-shrink: 0 !important;
    overflow: hidden !important;
}
.search-rec-item-icon img {
    max-width: 100% !important;
    max-height: 100% !important;
    object-fit: contain !important;
}
.search-rec-item-info {
    min-width: 0 !important;
    flex-grow: 1 !important;
}
.search-rec-item-title {
    font-size: 13px !important;
    font-weight: 600 !important;
    color: #1e293b !important;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
}
.search-rec-item-sub {
    font-size: 11px !important;
    color: #64748b !important;
}
.search-rec-badge {
    font-size: 10px !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    padding: 3px 8px !important;
    border-radius: 4px !important;
    letter-spacing: 0.5px !important;
    flex-shrink: 0 !important;
}
.search-rec-badge.badge-code {
    background: #dbeafe !important;
    color: #1d4ed8 !important;
}
.search-rec-badge.badge-deal {
    background: #dcfce7 !important;
    color: #15803d !important;
}
.search-rec-badge.badge-store {
    background: #fef3c7 !important;
    color: #b45309 !important;
}
.search-rec-footer-btn {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    margin-top: 10px !important;
    padding: 10px 14px !important;
    background: #f8fafc !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 8px !important;
    color: #334155 !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    text-decoration: none !important;
    transition: all 0.15s ease !important;
}
.search-rec-footer-btn:hover {
    background: #4f46e5 !important;
    border-color: #4f46e5 !important;
    color: #ffffff !important;
}
.search-rec-loading, .search-rec-empty {
    text-align: center !important;
    padding: 20px 16px !important;
    color: #64748b !important;
    font-size: 13px !important;
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
    .search-recommendation-box {
        width: 100% !important;
        max-width: 100% !important;
        left: 0 !important;
        max-height: 80vh !important;
        overflow-y: auto !important;
    }
    .ck-dropdown-grid {
        flex-direction: column !important;
    }
    .ck-left-col, .ck-right-col {
        width: 100% !important;
        border-right: none !important;
    }
    /* ==============================================================
       CASHKARO 1:1 MOBILE DRAWER (TOP-TO-BOTTOM CATEGORIES DRAWER)
       ============================================================== */
    body.drawer-open {
        overflow: hidden !important;
    }

    .sidebar-overlay {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        height: 100dvh !important;
        background: rgba(0, 0, 0, 0.6) !important;
        backdrop-filter: blur(2px) !important;
        -webkit-backdrop-filter: blur(2px) !important;
        z-index: 99999998 !important;
        visibility: hidden !important;
        opacity: 0 !important;
        transition: opacity 0.25s ease, visibility 0.25s ease !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .sidebar-overlay.show {
        visibility: visible !important;
        opacity: 1 !important;
    }

    .sidebar-menu-wrapper {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        bottom: 0 !important;
        width: 300px !important;
        max-width: 82vw !important;
        height: 100vh !important;
        height: 100dvh !important;
        background: #ffffff !important;
        transform: translateX(-105%) !important;
        transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1) !important;
        box-shadow: 6px 0 25px rgba(0, 0, 0, 0.2) !important;
        display: flex !important;
        flex-direction: column !important;
        overflow: hidden !important;
        z-index: 99999999 !important;
        padding: 0 !important;
        margin: 0 !important;
        font-family: 'Inter', sans-serif !important;
    }
    .sidebar-menu-wrapper.show {
        transform: translateX(0) !important;
    }

    /* CashKaro Drawer Top Header */
    .ck-drawer-header {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 16px 20px !important;
        background: #ffffff !important;
        border-bottom: 1px solid #f1f5f9 !important;
        flex-shrink: 0 !important;
        height: 58px !important;
    }
    .ck-drawer-title {
        font-size: 17px !important;
        font-weight: 700 !important;
        color: #111827 !important;
        letter-spacing: -0.2px !important;
    }
    .ck-drawer-close {
        background: transparent !important;
        border: none !important;
        color: #374151 !important;
        font-size: 19px !important;
        cursor: pointer !important;
        padding: 4px !important;
        line-height: 1 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    .ck-drawer-close:hover {
        color: #e11d48 !important;
    }

    /* CashKaro Drawer Scrollable Body */
    .ck-drawer-body {
        flex: 1 !important;
        overflow-y: auto !important;
        -webkit-overflow-scrolling: touch !important;
        padding: 6px 0 30px 0 !important;
    }
    .ck-drawer-nav {
        list-style: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    .ck-drawer-nav li {
        margin: 0 !important;
        padding: 0 !important;
    }
    .ck-nav-item {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 12px 20px !important;
        font-size: 14.5px !important;
        font-weight: 600 !important;
        color: #1f2937 !important;
        text-decoration: none !important;
        transition: background 0.15s ease, color 0.15s ease !important;
        border: none !important;
    }
    .ck-nav-item:hover, .ck-nav-item:active {
        background: #f8fafc !important;
        color: #e11d48 !important;
    }
    .ck-nav-item.active {
        color: #e11d48 !important;
    }
    .ck-nav-left {
        display: flex !important;
        align-items: center !important;
        gap: 14px !important;
    }
    .ck-nav-left i {
        font-size: 16px !important;
        color: #1f2937 !important;
        width: 22px !important;
        text-align: center !important;
    }
    .ck-nav-item.active .ck-nav-left i {
        color: #e11d48 !important;
    }
    .ck-nav-arrow {
        font-size: 12px !important;
        color: #9ca3af !important;
    }
    .ck-drawer-divider {
        height: 1px !important;
        background: #f1f5f9 !important;
        margin: 8px 18px !important;
    }
    .ck-drawer-section-label {
        font-size: 11px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        color: #9ca3af !important;
        padding: 8px 20px 4px 20px !important;
    }
    .ck-drawer-user-card {
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        padding: 12px 20px !important;
        background: #f8fafc !important;
    }
    .ck-drawer-user-avatar {
        width: 42px !important;
        height: 42px !important;
        border-radius: 50% !important;
        background: #e11d48 !important;
        color: #ffffff !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-weight: 700 !important;
        font-size: 16px !important;
        overflow: hidden !important;
        flex-shrink: 0 !important;
    }
    .ck-drawer-user-avatar img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
    }
    .ck-drawer-user-meta h6 {
        margin: 0 !important;
        font-size: 14px !important;
        font-weight: 700 !important;
        color: #111827 !important;
    }
    .ck-drawer-user-meta a {
        font-size: 12px !important;
        color: #e11d48 !important;
        text-decoration: none !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 4px !important;
        margin-top: 2px !important;
    }
</style>
@php
$pages = App\Models\Page::where('tempname', $activeTemplate)->get();
$user = auth()->user();

// Trending Stores for Left List (Flipkart, Amazon, Myntra / top stores)
$trendingStores = App\Models\Store::where('status', 1)
    ->orderByRaw("CASE WHEN name LIKE '%Flipkart%' THEN 1 WHEN name LIKE '%Nilkamal%' THEN 2 WHEN name LIKE '%Bacca%' THEN 3 ELSE 4 END")
    ->take(3)
    ->get();

// Trending Products / Coupons for Left List
$trendingCoupons = App\Models\Coupon::with('store')
    ->where('status', 1)
    ->latest()
    ->take(4)
    ->get();

// Hero Store (Right Column Top Card)
$heroStore = App\Models\Store::where('status', 1)
    ->where('name', 'LIKE', '%Flipkart%')
    ->first() ?? App\Models\Store::where('status', 1)->first();
$heroCoupon = App\Models\Coupon::where('status', 1)
    ->where('store_id', @$heroStore->id)
    ->first();

// Pick of the Day (Right Column Bottom Left)
$pickOfDayStore = App\Models\Store::where('status', 1)
    ->where('id', '!=', @$heroStore->id)
    ->first();
$pickOfDayCoupon = App\Models\Coupon::where('status', 1)
    ->where('store_id', @$pickOfDayStore->id)
    ->first();

// Pick of the Week (Right Column Bottom Right)
$pickOfWeekStore = App\Models\Store::where('status', 1)
    ->whereNotIn('id', array_filter([@$heroStore->id, @$pickOfDayStore->id]))
    ->first();
$pickOfWeekCoupon = App\Models\Coupon::where('status', 1)
    ->where('store_id', @$pickOfWeekStore->id)
    ->first();
@endphp
<div class="header" id="header">
    <div class="container-fluid container-custom position-relative">
        <div class="row">
            <div class="header-wrapper">
                <!-- ham menu -->
                <i class="fas fa-bars sidebar-menu-show-hide"></i>
                <div class="header-menu-wrapper align-items-center d-flex">
                    <div class="logo-wrapper">
                        <a href="{{ route('home') }}" class="logo-normal">
                            <img src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="{{ config('app.name') }}" height="60" fetchpriority="high">
                        </a>
                    </div>
                </div>
                <!-- / logo -->
                {{-- Mobile only: profile/login directly in header row 1 --}}
                @auth
                @php $user = auth()->user(); $name = $user->firstname ?? $user->username; $initial = strtoupper(substr($name, 0, 1)); @endphp
                <div class="mobile-header-auth">
                    <div class="profile-avatar">{{ $initial }}</div>
                </div>
                @else
                <div class="mobile-header-auth">
                    <a href="{{ route('user.login') }}"><i class="fa fa-user"></i></a>
                </div>
                @endauth
                <div class="menu-right-wrapper">
                    <ul>
                        <li class="hero-search-bar" id="heroSearchBarContainer">
                            <form action="{{route('single.coupon.search')}}" method="get" id="headerSearchForm" autocomplete="off">
                                <button class="search-btn" type="submit" aria-label="Search"><i class="fas fa-search"></i></button>
                                <input class="form--control" id="headerSearchInput" name="search" type="text" placeholder="@lang('Search for any brand or product')" autocomplete="off" value="{{ request()->search ?? '' }}">
                            </form>

                            <!-- SMART RECOMMENDATIONS DROPDOWN (CashKaro Exact 2-Column Replica) -->
                            <div class="search-recommendation-box" id="searchRecommendationBox">
                                <!-- Loading Spinner -->
                                <div class="search-rec-loading" id="searchRecLoading" style="display: none;">
                                    <i class="fas fa-spinner fa-spin"></i> @lang('Searching offers & stores...')
                                </div>

                                <!-- Initial Recommendations (Dynamic from User's Database) -->
                                <div class="search-rec-initial" id="searchRecInitial">
                                    <div class="ck-dropdown-grid">
                                        <!-- LEFT COLUMN: Trending Deals (Real Stores & Real Offers from Database) -->
                                        <div class="ck-left-col">
                                            <div class="ck-col-header">
                                                Trending Deals:
                                            </div>
                                            <div class="ck-trending-list">
                                                @foreach($trendingStores as $tStore)
                                                <a href="{{ route('store.coupons', $tStore->id) }}" class="ck-list-item">
                                                    <span class="ck-item-title">{{ $tStore->name }}</span>
                                                    <span class="ck-item-tag">in Stores</span>
                                                </a>
                                                @endforeach

                                                @foreach($trendingCoupons as $tCoupon)
                                                <a href="{{ route('coupon.details', $tCoupon->slug ?? $tCoupon->id) }}" class="ck-list-item">
                                                    <span class="ck-item-title">{{ Str::limit($tCoupon->title, 42) }}</span>
                                                    <span class="ck-item-tag">in Products</span>
                                                </a>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- RIGHT COLUMN: Hero Store + Picks (From Database) -->
                                        <div class="ck-right-col">
                                            <!-- Big Hero Store Card (Top Store from DB, e.g. Flipkart) -->
                                            @if($heroStore)
                                            <a href="{{ route('store.coupons', $heroStore->id) }}" class="ck-hero-card">
                                                <div class="ck-hero-logo">
                                                    <img src="{{ getImage(getFilePath('store').'/'.$heroStore->image) }}" alt="{{ $heroStore->name }}">
                                                </div>
                                                <div class="ck-hero-cashback">
                                                    {{ @$heroCoupon ? Str::limit($heroCoupon->title, 32) : 'Up To 6.5% Cashback' }}
                                                </div>
                                            </a>
                                            @endif

                                            <!-- Bottom 2 Pick Cards -->
                                            <div class="ck-picks-grid">
                                                <!-- Pick of the Day -->
                                                @if($pickOfDayStore)
                                                <a href="{{ route('store.coupons', $pickOfDayStore->id) }}" class="ck-pick-card">
                                                    <div class="ck-pick-header">Pick of the Day</div>
                                                    <div class="ck-pick-logo">
                                                        <img src="{{ getImage(getFilePath('store').'/'.$pickOfDayStore->image) }}" alt="{{ $pickOfDayStore->name }}">
                                                    </div>
                                                    <div class="ck-pick-desc">{{ @$pickOfDayCoupon->title ? Str::limit($pickOfDayCoupon->title, 38) : $pickOfDayStore->name }}</div>
                                                </a>
                                                @endif

                                                <!-- Pick of the Week -->
                                                @if($pickOfWeekStore)
                                                <a href="{{ route('store.coupons', $pickOfWeekStore->id) }}" class="ck-pick-card">
                                                    <div class="ck-pick-header">Pick of the Week</div>
                                                    <div class="ck-pick-logo">
                                                        <img src="{{ getImage(getFilePath('store').'/'.$pickOfWeekStore->image) }}" alt="{{ $pickOfWeekStore->name }}">
                                                    </div>
                                                    <div class="ck-pick-desc">{{ @$pickOfWeekCoupon->title ? Str::limit($pickOfWeekCoupon->title, 38) : $pickOfWeekStore->name }}</div>
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Live Search Results (shown when typing) -->
                                <div class="search-rec-results" id="searchRecResults" style="display: none;">
                                    <!-- Stores Results -->
                                    <div class="search-rec-section" id="searchRecStoresSec" style="display: none;">
                                        <div class="search-rec-title">
                                            <span><i class="fas fa-store text-primary"></i> @lang('Stores')</span>
                                        </div>
                                        <div class="search-rec-items-list" id="searchRecStoresList"></div>
                                    </div>

                                    <!-- Coupons Results -->
                                    <div class="search-rec-section mb-0" id="searchRecCouponsSec" style="display: none;">
                                        <div class="search-rec-title">
                                            <span><i class="fas fa-ticket-alt text-success"></i> @lang('Coupons & Offers')</span>
                                        </div>
                                        <div class="search-rec-items-list" id="searchRecCouponsList"></div>
                                    </div>

                                    <!-- No Results Found -->
                                    <div class="search-rec-empty" id="searchRecEmpty" style="display: none;">
                                        <i class="fas fa-search-minus fa-2x text-muted mb-2"></i>
                                        <p>@lang('No stores or coupons match your search.')</p>
                                        <small class="text-muted">@lang('Press Enter to see all related results')</small>
                                    </div>

                                    <!-- View All Footer -->
                                    <a href="#" id="searchRecViewAllBtn" class="search-rec-footer-btn">
                                        <span>@lang('See all results for') "<strong id="searchRecQueryText"></strong>"</span>
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                        
                        <!-- Moved Nav Links Here -->
                        <li class="nav-links-container">
                            <ul class="main-menu">
                                @if(auth()->check() && auth()->user()->signup_type == 2)
                                    <li><a href="{{ route('affiliate') }}">@lang('Affiliates')</a></li>
                                    <li><a href="{{ route('blog') }}">@lang('Blogs')</a></li>
                                    <li><a href="{{ route('user.my.earnings') }}">@lang('Earning')</a></li>
                                @else
                                    <li class="home">
                                        <a class="{{ Request::routeIs('home') ? 'active' : 'demo-class' }}"
                                            href="{{ route('home') }}">@lang('Home')</a>
                                    </li>
                                    @foreach ($pages as $page)
                                        @if ($page->slug != '/')
                                        <li>
                                            <a class="{{ request()->url() === route('pages', [$page->slug]) ? 'active' : '' }}"
                                                href="{{ route('pages', [$page->slug]) }}">{{ __($page->name) }}
                                            </a>
                                        </li>
                                        @endif
                                    @endforeach
                                    <li><a href="{{ route('user.my.earnings') }}">@lang('Earning')</a></li>
                                @endif
                            </ul>
                        </li>
                        
                        @auth
                        @php
                            $user = auth()->user();
                            $name = $user->firstname ?? $user->username;
                            $initial = strtoupper(substr($name, 0, 1));
                        @endphp
                        <li class="profile-dropdown">
                            <div class="profile-avatar">{{ $initial }}</div>
                            <div class="profile-dropdown-menu">
                                <div class="profile-dropdown-header">
                                    <div class="big-avatar">{{ $initial }}</div>
                                    <h6>{{ $name }}</h6>
                                    <p>{{ $user->email }}</p>
                                    <a href="{{ route('user.home') }}" class="btn btn--base mt-3" style="width: 100%; border-radius: 20px;">@lang('Dashboard')</a>
                                </div>
                                <ul>
                                    <li style="border-top: 1px solid #eaeaec; margin-top: 5px; padding-top: 5px;">
                                        <a href="{{ route('user.logout') }}"><i class="fas fa-sign-out-alt"></i> @lang('Sign out')</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        @else
                        <li class="login-registration-list__item">
                            <a href="{{ route('user.login') }}" class="">
                                <i class="fa fa-user"></i>
                                 <span class="login-text">@lang('Login/Signup')</span>
                            </a>
                        </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CashKaro Style Category Drawer -->
<div class="sidebar-menu-wrapper">
    <!-- Top Bar with Category title and Close (✕) button -->
    <div class="ck-drawer-header">
        <span class="ck-drawer-title">@lang('Category')</span>
        <button type="button" class="ck-drawer-close close-hide-show" aria-label="Close">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Scrollable Drawer Body -->
    <div class="ck-drawer-body">
        @auth
        @php
            $name = $user->fullname ?? $user->firstname ?? $user->username;
            $initial = strtoupper(substr($name, 0, 1));
        @endphp
        <div class="ck-drawer-user-card">
            <div class="ck-drawer-user-avatar">
                @if(!empty($user->image) && file_exists(getFilePath('userProfile') . '/' . $user->image))
                    <img src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, getFileSize('userProfile')) }}" alt="{{ $name }}">
                @else
                    <span>{{ $initial }}</span>
                @endif
            </div>
            <div class="ck-drawer-user-meta">
                <h6>{{ $name }}</h6>
                <a href="{{ route('user.home') }}"><i class="fas fa-th-large"></i> @lang('My Dashboard')</a>
            </div>
        </div>
        <div class="ck-drawer-divider"></div>
        @endauth

        <!-- Main Navigation Links -->
        <ul class="ck-drawer-nav">
            <li>
                <a href="{{ route('home') }}" class="ck-nav-item {{ Request::routeIs('home') ? 'active' : '' }}">
                    <span class="ck-nav-left"><i class="fas fa-home"></i> @lang('Home')</span>
                    <i class="fas fa-chevron-right ck-nav-arrow"></i>
                </a>
            </li>

            @foreach ($pages as $page)
                @if ($page->slug != '/')
                    @php
                        $pName = strtolower($page->name);
                        $pIcon = 'fas fa-tag';
                        if (str_contains($pName, 'coupon')) $pIcon = 'fas fa-ticket-alt';
                        elseif (str_contains($pName, 'cashback')) $pIcon = 'fas fa-coins';
                        elseif (str_contains($pName, 'deal')) $pIcon = 'fas fa-fire';
                        elseif (str_contains($pName, 'affiliate')) $pIcon = 'fas fa-users';
                        elseif (str_contains($pName, 'blog')) $pIcon = 'fas fa-newspaper';
                    @endphp
                    <li>
                        <a href="{{ route('pages', [$page->slug]) }}" class="ck-nav-item {{ request()->url() === route('pages', [$page->slug]) ? 'active' : '' }}">
                            <span class="ck-nav-left"><i class="{{ $pIcon }}"></i> {{ __($page->name) }}</span>
                            <i class="fas fa-chevron-right ck-nav-arrow"></i>
                        </a>
                    </li>
                @endif
            @endforeach

            @if(Route::has('feature.store'))
            <li>
                <a href="{{ route('feature.store') }}" class="ck-nav-item {{ Request::routeIs('feature.store') ? 'active' : '' }}">
                    <span class="ck-nav-left"><i class="fas fa-store"></i> @lang('All Stores')</span>
                    <i class="fas fa-chevron-right ck-nav-arrow"></i>
                </a>
            </li>
            @endif
        </ul>

        <!-- Top Categories from Database (CashKaro replica) -->
        @php
            $drawerCats = App\Models\Category::where('status', 1)->take(8)->get();
        @endphp
        @if($drawerCats && $drawerCats->count() > 0)
            <div class="ck-drawer-divider"></div>
            <div class="ck-drawer-section-label">@lang('Top Categories')</div>
            <ul class="ck-drawer-nav">
                @foreach($drawerCats as $cat)
                    @php
                        $cName = strtolower($cat->name);
                        $cIcon = 'fas fa-shapes';
                        if (str_contains($cName, 'mobile') || str_contains($cName, 'phone')) $cIcon = 'fas fa-mobile-alt';
                        elseif (str_contains($cName, 'laptop') || str_contains($cName, 'computer')) $cIcon = 'fas fa-laptop';
                        elseif (str_contains($cName, 'fashion') || str_contains($cName, 'cloth')) $cIcon = 'fas fa-tshirt';
                        elseif (str_contains($cName, 'shoe') || str_contains($cName, 'footwear')) $cIcon = 'fas fa-shoe-prints';
                        elseif (str_contains($cName, 'beauty') || str_contains($cName, 'cosmetic')) $cIcon = 'fas fa-gem';
                        elseif (str_contains($cName, 'grocery') || str_contains($cName, 'food')) $cIcon = 'fas fa-shopping-basket';
                        elseif (str_contains($cName, 'electronic')) $cIcon = 'fas fa-tv';
                        elseif (str_contains($cName, 'health')) $cIcon = 'fas fa-heartbeat';
                        elseif (str_contains($cName, 'travel')) $cIcon = 'fas fa-plane';
                    @endphp
                    <li>
                        <a href="{{ route('category.coupons', $cat->id) }}" class="ck-nav-item">
                            <span class="ck-nav-left">
                                <i class="{{ $cIcon }}"></i>
                                {{ $cat->name }}
                            </span>
                            <i class="fas fa-chevron-right ck-nav-arrow"></i>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="ck-drawer-divider"></div>

        <!-- Account Section -->
        <ul class="ck-drawer-nav">
            @auth
                <li>
                    <a href="{{ route('user.home') }}" class="ck-nav-item {{ Request::routeIs('user.home') ? 'active' : '' }}">
                        <span class="ck-nav-left"><i class="fas fa-tachometer-alt"></i> @lang('Dashboard')</span>
                        <i class="fas fa-chevron-right ck-nav-arrow"></i>
                    </a>
                </li>
                @if(Route::has('user.my.earnings'))
                <li>
                    <a href="{{ route('user.my.earnings') }}" class="ck-nav-item {{ Request::routeIs('user.my.earnings') ? 'active' : '' }}">
                        <span class="ck-nav-left"><i class="fas fa-wallet"></i> @lang('My Earnings')</span>
                        <i class="fas fa-chevron-right ck-nav-arrow"></i>
                    </a>
                </li>
                @endif
                <li>
                    <a href="{{ route('user.logout') }}" class="ck-nav-item" style="color: #ef4444 !important;">
                        <span class="ck-nav-left"><i class="fas fa-sign-out-alt" style="color: #ef4444 !important;"></i> @lang('Logout')</span>
                        <i class="fas fa-chevron-right ck-nav-arrow"></i>
                    </a>
                </li>
            @else
                <li>
                    <a href="{{ route('user.login') }}" class="ck-nav-item">
                        <span class="ck-nav-left"><i class="fas fa-user-circle"></i> @lang('Login / Signup')</span>
                        <i class="fas fa-chevron-right ck-nav-arrow"></i>
                    </a>
                </li>
            @endauth
        </ul>
    </div>
</div>

<script>
(function() {
    function setupSearchDropdown() {
        var input = document.getElementById('headerSearchInput');
        var box = document.getElementById('searchRecommendationBox');
        var container = document.getElementById('heroSearchBarContainer');
        var initial = document.getElementById('searchRecInitial');
        var results = document.getElementById('searchRecResults');
        var loading = document.getElementById('searchRecLoading');
        var storesSec = document.getElementById('searchRecStoresSec');
        var storesList = document.getElementById('searchRecStoresList');
        var couponsSec = document.getElementById('searchRecCouponsSec');
        var couponsList = document.getElementById('searchRecCouponsList');
        var empty = document.getElementById('searchRecEmpty');
        var viewAllBtn = document.getElementById('searchRecViewAllBtn');
        var queryText = document.getElementById('searchRecQueryText');
        var suggestUrl = "{{ route('search.suggest') }}";
        var debounceTimer = null;

        if (!input || !box) return;

        function showBox(e) {
            if (e && e.stopPropagation) e.stopPropagation();
            box.classList.add('active');
            var val = input.value.trim();
            if (val.length === 0) {
                if (initial) initial.style.display = 'block';
                if (results) results.style.display = 'none';
                if (loading) loading.style.display = 'none';
            } else {
                handleSearch(val);
            }
        }

        function hideBox() {
            box.classList.remove('active');
        }

        input.addEventListener('focus', showBox);
        input.addEventListener('click', showBox);
        input.addEventListener('mousedown', showBox);

        document.addEventListener('click', function(e) {
            if (container && !container.contains(e.target)) {
                hideBox();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideBox();
            }
        });

        input.addEventListener('input', function() {
            var val = input.value.trim();
            clearTimeout(debounceTimer);

            if (val.length === 0) {
                if (loading) loading.style.display = 'none';
                if (results) results.style.display = 'none';
                if (initial) initial.style.display = 'block';
                return;
            }

            if (initial) initial.style.display = 'none';
            if (loading) loading.style.display = 'block';
            if (results) results.style.display = 'none';

            debounceTimer = setTimeout(function() {
                handleSearch(val);
            }, 200);
        });

        function handleSearch(val) {
            fetch(suggestUrl + '?search=' + encodeURIComponent(val))
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    if (loading) loading.style.display = 'none';
                    if (results) results.style.display = 'block';

                    if (queryText) queryText.textContent = val;
                    if (viewAllBtn) viewAllBtn.href = data.search_url;

                    var hasStores = data.stores && data.stores.length > 0;
                    var hasCoupons = data.coupons && data.coupons.length > 0;

                    if (hasStores && storesSec && storesList) {
                        storesSec.style.display = 'block';
                        storesList.innerHTML = data.stores.map(function(store) {
                            return '<a href="' + store.url + '" class="search-rec-item">' +
                                '<div class="search-rec-item-left">' +
                                    '<div class="search-rec-item-icon"><img src="' + store.image + '" alt="' + escapeHtml(store.name) + '"></div>' +
                                    '<div class="search-rec-item-info">' +
                                        '<div class="search-rec-item-title">' + escapeHtml(store.name) + '</div>' +
                                        '<div class="search-rec-item-sub">Store & Coupons</div>' +
                                    '</div>' +
                                '</div>' +
                                '<span class="search-rec-badge badge-store">Store</span>' +
                            '</a>';
                        }).join('');
                    } else if (storesSec) {
                        storesSec.style.display = 'none';
                        if (storesList) storesList.innerHTML = '';
                    }

                    if (hasCoupons && couponsSec && couponsList) {
                        couponsSec.style.display = 'block';
                        couponsList.innerHTML = data.coupons.map(function(coupon) {
                            return '<a href="' + coupon.url + '" class="search-rec-item">' +
                                '<div class="search-rec-item-left">' +
                                    '<div class="search-rec-item-icon"><i class="fas fa-tag"></i></div>' +
                                    '<div class="search-rec-item-info">' +
                                        '<div class="search-rec-item-title">' + escapeHtml(coupon.title) + '</div>' +
                                        '<div class="search-rec-item-sub">' + (coupon.store_name ? escapeHtml(coupon.store_name) + ' • ' : '') + (coupon.code ? escapeHtml(coupon.code) : coupon.type) + '</div>' +
                                    '</div>' +
                                '</div>' +
                                '<span class="search-rec-badge ' + (coupon.type === 'Code' ? 'badge-code' : 'badge-deal') + '">' + coupon.type + '</span>' +
                            '</a>';
                        }).join('');
                    } else if (couponsSec) {
                        couponsSec.style.display = 'none';
                        if (couponsList) couponsList.innerHTML = '';
                    }

                    if (!hasStores && !hasCoupons && empty) {
                        empty.style.display = 'block';
                    } else if (empty) {
                        empty.style.display = 'none';
                    }
                })
                .catch(function() {
                    if (loading) loading.style.display = 'none';
                });
        }

        function escapeHtml(text) {
            if (!text) return '';
            var div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupSearchDropdown);
    } else {
        setupSearchDropdown();
    }

    // CashKaro Drawer open/close controls and background scroll lock
    function initDrawerControls() {
        var ham = document.querySelector('.sidebar-menu-show-hide');
        var sidebar = document.querySelector('.sidebar-menu-wrapper');
        var overlay = document.querySelector('.sidebar-overlay');
        var closeBtns = document.querySelectorAll('.close-hide-show');

        function openDrawer(e) {
            if (e && e.preventDefault) e.preventDefault();
            if (sidebar) sidebar.classList.add('show');
            if (overlay) overlay.classList.add('show');
            document.body.classList.add('drawer-open');
        }

        function closeDrawer(e) {
            if (e && e.preventDefault) e.preventDefault();
            if (sidebar) sidebar.classList.remove('show');
            if (overlay) overlay.classList.remove('show');
            document.body.classList.remove('drawer-open');
        }

        if (ham) ham.addEventListener('click', openDrawer);
        if (overlay) overlay.addEventListener('click', closeDrawer);
        closeBtns.forEach(function(btn) {
            btn.addEventListener('click', closeDrawer);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDrawerControls);
    } else {
        initDrawerControls();
    }
})();
</script>

