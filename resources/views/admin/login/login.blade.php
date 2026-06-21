@php
    $info = \App\Helper\admin\siteInformation::siteInfo();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$info['site_name']}} | Admin Login</title>
    <link rel="icon" href="{{url('/')}}/frontend/images/favicon.png" type="image/png" sizes="16x16">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{url('/')}}/admin/plugins/fontawesome-free/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: #0b0e1a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Deep Navy Background with Animated Orbs ───── */
        .login-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            background: radial-gradient(ellipse at 50% 0%, #111827 0%, #0b0e1a 70%);
        }

        .login-bg::before {
            content: '';
            position: absolute;
            top: -60%;
            left: -40%;
            width: 180%;
            height: 180%;
            background:
                radial-gradient(ellipse at 25% 40%, rgba(255, 122, 0, 0.08) 0%, transparent 55%),
                radial-gradient(ellipse at 75% 25%, rgba(251, 191, 36, 0.05) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 85%, rgba(255, 122, 0, 0.04) 0%, transparent 55%);
            animation: bgDrift 25s ease-in-out infinite alternate;
        }

        @keyframes bgDrift {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(-4%, -3%) rotate(4deg); }
        }

        .login-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.018) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.018) 1px, transparent 1px);
            background-size: 64px 64px;
            opacity: 0.7;
        }

        /* Floating orbs */
        .login-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            will-change: transform;
        }

        .login-orb-1 {
            width: 500px; height: 500px;
            background: rgba(255, 122, 0, 0.09);
            top: 5%; left: 5%;
            animation: orbA 18s ease-in-out infinite alternate;
        }

        .login-orb-2 {
            width: 380px; height: 380px;
            background: rgba(251, 191, 36, 0.07);
            bottom: 10%; right: 8%;
            animation: orbB 15s ease-in-out infinite alternate;
        }

        .login-orb-3 {
            width: 300px; height: 300px;
            background: rgba(255, 122, 0, 0.05);
            top: 45%; left: 55%;
            transform: translate(-50%, -50%);
            animation: orbC 20s ease-in-out infinite alternate;
        }

        @keyframes orbA {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, -25px) scale(1.08); }
        }
        @keyframes orbB {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(-25px, 20px) scale(0.95); }
        }
        @keyframes orbC {
            0% { transform: translate(-50%, -50%) scale(1); }
            100% { transform: translate(-45%, -55%) scale(1.1); }
        }

        /* Particle dots */
        .particle {
            position: absolute;
            width: 2px;
            height: 2px;
            background: rgba(255, 122, 0, 0.3);
            border-radius: 50%;
            animation: particleFloat linear infinite;
        }

        @keyframes particleFloat {
            0% { transform: translateY(100vh) scale(0); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-10vh) scale(1); opacity: 0; }
        }

        /* ── Login Container ──────────────────────────── */
        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            padding: 24px;
            animation: wrapperReveal 0.7s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes wrapperReveal {
            from { opacity: 0; transform: translateY(32px) scale(0.96); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* ── Brand Area ───────────────────────────────── */
        .login-brand {
            text-align: center;
            margin-bottom: 28px;
        }

        .login-brand h1 {
            font-size: 2rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }

        .login-brand p {
            font-size: 0.88rem;
            color: #7c85a0;
            font-weight: 400;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .login-brand .brand-divider {
            width: 40px;
            height: 3px;
            background: linear-gradient(90deg, transparent, #ff7a00, transparent);
            margin: 16px auto 0;
            border-radius: 2px;
        }

        /* ── Login Card — Deep Glass ──────────────────── */
        .login-card {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(32px) saturate(200%);
            -webkit-backdrop-filter: blur(32px) saturate(200%);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 24px;
            padding: 40px 36px;
            box-shadow:
                0 8px 40px rgba(0, 0, 0, 0.5),
                0 0 0 1px rgba(255, 255, 255, 0.03),
                inset 0 1px 0 rgba(255, 255, 255, 0.05),
                inset 0 -1px 0 rgba(0, 0, 0, 0.2);
            animation: cardSlide 0.6s 0.15s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes cardSlide {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-card h2 {
            font-size: 1.15rem;
            font-weight: 600;
            color: #e8eaf0;
            text-align: center;
            margin-bottom: 4px;
        }

        .login-card .login-subtitle {
            font-size: 0.84rem;
            color: #7c85a0;
            text-align: center;
            margin-bottom: 30px;
        }

        /* ── Floating Label Inputs ────────────────────── */
        .field-group {
            margin-bottom: 22px;
            position: relative;
        }

        .field-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .field-wrapper .field-icon {
            position: absolute;
            left: 16px;
            color: #4b5574;
            font-size: 0.88rem;
            z-index: 2;
            transition: color 0.3s ease;
            pointer-events: none;
        }

        .field-wrapper input {
            width: 100%;
            padding: 14px 16px 14px 44px;
            background: rgba(255, 255, 255, 0.035);
            border: 1.5px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            color: #e8eaf0;
            font-family: 'Inter', sans-serif;
            font-size: 0.92rem;
            font-weight: 400;
            transition: all 0.3s ease;
            outline: none;
        }

        .field-wrapper input::placeholder {
            color: #4b5574;
            transition: opacity 0.3s ease;
        }

        .field-wrapper input:focus::placeholder {
            opacity: 0.4;
        }

        .field-wrapper input:focus {
            border-color: #ff7a00;
            background: rgba(255, 122, 0, 0.05);
            box-shadow: 0 0 0 4px rgba(255, 122, 0, 0.1), 0 0 20px rgba(255, 122, 0, 0.05);
        }

        .field-wrapper input:focus ~ .field-icon,
        .field-wrapper .field-wrapper:focus-within .field-icon {
            color: #ff9a3c;
        }

        .field-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: #7c85a0;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: color 0.3s ease;
        }

        .field-group:focus-within .field-label {
            color: #ff9a3c;
        }

        /* Password toggle */
        .toggle-password {
            position: absolute;
            right: 14px;
            background: none;
            border: none;
            color: #4b5574;
            cursor: pointer;
            padding: 6px;
            font-size: 0.88rem;
            transition: all 0.25s ease;
            z-index: 2;
            border-radius: 8px;
        }

        .toggle-password:hover {
            color: #ff9a3c;
            background: rgba(255, 122, 0, 0.08);
        }

        /* Error text */
        .error-text {
            color: #ef4444;
            font-size: 0.78rem;
            margin-top: 6px;
            display: block;
            min-height: 18px;
            font-weight: 500;
        }

        /* ── Submit Button — Shine Effect ──────────────── */
        .login-submit {
            width: 100%;
            padding: 14px 24px;
            background: linear-gradient(135deg, #ff7a00, #e56c00);
            color: #fff;
            border: none;
            border-radius: 14px;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(255, 122, 0, 0.3);
            position: relative;
            overflow: hidden;
            margin-top: 10px;
            letter-spacing: 0.2px;
        }

        .login-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .login-submit:hover::before {
            left: 100%;
        }

        .login-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 36px rgba(255, 122, 0, 0.5);
            background: linear-gradient(135deg, #ff9a3c, #ff7a00);
        }

        .login-submit:active {
            transform: translateY(0);
            box-shadow: 0 2px 12px rgba(255, 122, 0, 0.3);
        }

        .login-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        .login-submit .btn-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .login-submit .spinner {
            display: none;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        .login-submit.loading .spinner { display: inline-block; }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Messages ─────────────────────────────────── */
        .alert {
            border-radius: 12px !important;
            border: none !important;
            font-size: 0.84rem;
            padding: 12px 16px !important;
            margin-bottom: 18px;
            font-weight: 500;
        }

        /* ── Footer ───────────────────────────────────── */
        .login-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 0.78rem;
            color: #4b5574;
            letter-spacing: 0.3px;
        }

        .login-footer span {
            color: #7c85a0;
        }

        /* ── Premium Login Loader ─────────────────────── */
        .login-loader-overlay {
            position: fixed;
            inset: 0;
            z-index: 99999;
            background: rgba(5, 10, 20, 0.88);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.4s ease, visibility 0.4s ease;
        }

        .login-loader-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .loader-brand {
            position: relative;
            width: 80px;
            height: 80px;
            margin-bottom: 28px;
        }

        .loader-ring-outer {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 2.5px solid transparent;
            border-top-color: #ff7a00;
            border-right-color: rgba(251, 191, 36, 0.4);
            animation: loaderSpin 1.2s linear infinite;
        }

        .loader-ring-inner {
            position: absolute;
            inset: 10px;
            border-radius: 50%;
            border: 2px solid transparent;
            border-top-color: rgba(255, 122, 0, 0.5);
            border-right-color: #ff7a00;
            animation: loaderSpin 1.8s linear infinite reverse;
        }

        .loader-glow {
            position: absolute;
            inset: -20px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 122, 0, 0.18) 0%, transparent 70%);
            animation: loaderGlow 2s ease-in-out infinite alternate;
        }

        .loader-text-brand {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 0.72rem;
            font-weight: 800;
            color: #ff7a00;
            letter-spacing: 1px;
            z-index: 1;
        }

        @keyframes loaderSpin {
            to { transform: rotate(360deg); }
        }

        @keyframes loaderGlow {
            0% { transform: scale(0.85); opacity: 0.4; }
            100% { transform: scale(1.15); opacity: 1; }
        }

        .loader-text-primary {
            font-size: 1rem;
            font-weight: 600;
            color: #e8eaf0;
            margin-bottom: 6px;
            letter-spacing: 0.3px;
        }

        .loader-text-secondary {
            font-size: 0.82rem;
            color: #7c85a0;
            font-weight: 400;
        }

        .loader-dots::after {
            content: '';
            animation: loaderDots 1.5s steps(4, end) infinite;
        }

        @keyframes loaderDots {
            0% { content: ''; }
            25% { content: '.'; }
            50% { content: '..'; }
            75% { content: '...'; }
        }

        @media (max-width: 480px) {
            .loader-brand { width: 64px; height: 64px; }
            .loader-text-brand { font-size: 0.6rem; }
        }

        /* ── Responsive ───────────────────────────────── */
        @media (max-width: 480px) {
            .login-wrapper { padding: 16px; }
            .login-card { padding: 32px 22px; border-radius: 20px; }
            .login-brand h1 { font-size: 1.6rem; }
        }

        @media (max-height: 700px) {
            .login-brand { margin-bottom: 18px; }
            .login-brand .brand-divider { display: none; }
            .login-card { padding: 28px 28px; }
            .field-group { margin-bottom: 16px; }
        }
    </style>
</head>
<body>
    <!-- Premium Login Loader -->
    <div class="login-loader-overlay" id="loginLoader" role="status" aria-live="polite">
        <div class="loader-brand">
            <div class="loader-glow"></div>
            <div class="loader-ring-outer"></div>
            <div class="loader-ring-inner"></div>
            <div class="loader-text-brand">MAAC</div>
        </div>
        <div class="loader-text-primary">Loading Dashboard<span class="loader-dots"></span></div>
        <div class="loader-text-secondary">Please wait<span class="loader-dots"></span></div>
    </div>

    <!-- Animated Background -->
    <div class="login-bg">
        <div class="login-grid"></div>
        <div class="login-orb login-orb-1"></div>
        <div class="login-orb login-orb-2"></div>
        <div class="login-orb login-orb-3"></div>
    </div>

    <div class="login-wrapper">
        <!-- Brand -->
        <div class="login-brand">
            <h1>MAAC</h1>
            <p>Admin Control Panel</p>
            <div class="brand-divider"></div>
        </div>

        <!-- Login Card -->
        <div class="login-card">
            <h2>Welcome back</h2>
            <p class="login-subtitle">Sign in to your admin dashboard</p>

            @include('messages')

            <div id="message_print"></div>

            <form method="post" action="{{route('admin_login_check')}}" id="login_form" autocomplete="off">
                @csrf

                <div class="field-group">
                    <label class="field-label" for="user_name">Email Address</label>
                    <div class="field-wrapper">
                        <span class="field-icon"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email" id="user_name" placeholder="admin@example.com" autofocus>
                    </div>
                    <span class="error-text email_error"></span>
                </div>

                <div class="field-group">
                    <label class="field-label" for="password">Password</label>
                    <div class="field-wrapper">
                        <span class="field-icon"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" id="password" placeholder="Enter your password">
                        <button type="button" class="toggle-password" onclick="togglePassword()" tabindex="-1" aria-label="Toggle password visibility">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    <span class="error-text password_error"></span>
                </div>

                <button type="submit" class="login-submit" id="loginBtn">
                    <span class="btn-content">
                        <span class="spinner"></span>
                        <span class="btn-text">Signing In...</span>
                    </span>
                </button>
            </form>
        </div>

        <div class="login-footer">
            Powered by <span>MAAC Durgapur</span>
        </div>
    </div>

    <!-- Particles (injected via JS) -->
    <script>
        (function() {
            var bg = document.querySelector('.login-bg');
            var count = window.innerWidth < 768 ? 12 : 20;
            for (var i = 0; i < count; i++) {
                var p = document.createElement('div');
                p.className = 'particle';
                p.style.left = (Math.random() * 100) + '%';
                p.style.animationDuration = (8 + Math.random() * 12) + 's';
                p.style.animationDelay = (Math.random() * 10) + 's';
                p.style.width = p.style.height = (1 + Math.random() * 2) + 'px';
                p.style.opacity = 0.15 + Math.random() * 0.25;
                bg.appendChild(p);
            }
        })();
    </script>

    <script src="{{url('/')}}/admin/plugins/jquery/jquery.min.js"></script>
    <script src="{{url('/')}}/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{url('/')}}/admin/plugins/toastr/toastr.min.js"></script>
    <link rel="stylesheet" href="{{ url('/') }}/admin/plugins/toastr/toastr.min.css">
    <script>
        function togglePassword() {
            var input = document.getElementById('password');
            var icon = document.getElementById('toggleIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function showLoader() {
            var overlay = document.getElementById('loginLoader');
            if (overlay) {
                overlay.classList.add('active');
            }
        }

        function hideLoader() {
            var overlay = document.getElementById('loginLoader');
            if (overlay) {
                overlay.classList.remove('active');
            }
        }

        $(document).ready(function () {
            $('#login_form').on('submit', function (e) {
                e.preventDefault();
                var btn = $('#loginBtn');
                btn.addClass('loading').prop('disabled', true);
                showLoader();

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: new FormData(this),
                    processData: false,
                    dataType: 'json',
                    contentType: false,
                    beforeSend: function () {
                        $(document).find('span.error-text').text('');
                    },
                    success: function (data) {
                        if (data.status == 400) {
                            hideLoader();
                            btn.removeClass('loading').prop('disabled', false);
                            $.each(data.error, function (key, value) {
                                $('span.' + key + '_error').text(value[0]);
                            });
                        } else if (data.status == 200) {
                            toastr.success(data.message);
                            setTimeout(function() {
                                window.location.href = '{{route('admin::dashboard')}}';
                            }, 100);
                        }
                        if (data.status == 201) {
                            hideLoader();
                            btn.removeClass('loading').prop('disabled', false);
                            toastr.error(data.message);
                        }
                    },
                    error: function(xhr) {
                        hideLoader();
                        btn.removeClass('loading').prop('disabled', false);
                        if (xhr.status === 419) {
                            toastr.warning('Session expired. Refreshing page...');
                            setTimeout(function() {
                                window.location.reload();
                            }, 500);
                        } else {
                            toastr.error('A network error occurred. Please try again.');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
