<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login</title>

    <!-- Simple mobile-first styles -->
    <style>
        :root{
            --bg:#f7f7f9;
            --card:#ffffff;
            --primary:#0366d6;
            --muted:#6b7280;
            --danger:#ef4444;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
        }
        html,body{height:100%;}
        body{
            margin:0;
            background:linear-gradient(180deg, #f3f4f6 0%, var(--bg) 100%);
            display:flex;
            align-items:center;
            justify-content:center;
            padding:24px;
        }
        .wrap{
            width:100%;
            max-width:420px;
        }
        .card{
            background:var(--card);
            border-radius:12px;
            box-shadow:0 6px 24px rgba(16,24,40,0.08);
            padding:20px;
        }
        h1{
            margin:0 0 6px 0;
            font-size:20px;
            font-weight:600;
        }
        p.lead{
            margin:0 0 18px 0;
            color:var(--muted);
            font-size:13px;
        }
        label{display:block;font-size:13px;margin-bottom:6px;color:#111827}
        input[type="email"],
        input[type="password"]{
            width:100%;
            box-sizing:border-box;
            padding:12px 14px;
            border-radius:8px;
            border:1px solid #e6e6e9;
            font-size:15px;
            outline:none;
        }
        input[type="email"]:focus,
        input[type="password"]:focus{
            border-color: rgba(3,102,214,0.3);
            box-shadow:0 0 0 4px rgba(3,102,214,0.06);
        }
        .field + .field{margin-top:12px}
        .error{
            margin-top:8px;
            font-size:13px;
            color:var(--danger);
        }
        .actions{
            margin-top:18px;
            display:flex;
            gap:8px;
            align-items:center;
            justify-content:space-between;
        }
        button.primary{
            background:var(--primary);
            color:white;
            padding:10px 14px;
            border-radius:8px;
            border:0;
            font-weight:600;
            flex:1;
        }
        .link{
            font-size:13px;
            color:var(--primary);
            text-decoration:none;
        }
        .meta{
            margin-top:12px;
            display:flex;
            align-items:center;
            gap:8px;
            font-size:13px;
            color:var(--muted);
        }
        .checkbox{
            display:flex;
            align-items:center;
            gap:8px;
            font-size:13px;
            color:var(--muted);
        }
        .divider{
            height:1px;background:#eef2f6;margin:18px 0;border-radius:1px;
        }
        .socials{display:flex;gap:10px}
        .socials a{
            flex:1;
            text-align:center;
            padding:10px 8px;
            border-radius:8px;
            text-decoration:none;
            font-weight:600;
            font-size:14px;
            color:#111827;
            background:#f8fafc;
            border:1px solid #eef2f6;
        }
        .status{
            padding:10px;
            background:#f0f9ff;
            border-radius:8px;
            color:#0366d6;
            font-size:13px;
            margin-bottom:12px;
        }

        @media (max-width:420px){
            .card{padding:16px}
        }
    </style>
</head>
<body>
    <main class="wrap">
        <section class="card" aria-labelledby="login-title">
            <h1 id="login-title">Sign in to your account</h1>
            <p class="lead">Enter your credentials to continue.</p>

            @if (session('status'))
                <div class="status">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('mobilelogin') }}">
                @csrf

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" />
                    @error('email')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password" />
                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="actions">
                    <label class="checkbox">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} />
                        Remember me
                    </label>

                    <div style="margin-left:auto">
                        @if (Route::has('password.request'))
                            {{-- <a class="link" href="{{ route('password.request') }}">Forgot?</a> --}}
                        @endif
                    </div>
                </div>

                <div style="margin-top:14px; display:flex; gap:10px;">
                    <button type="submit" class="primary">Sign in</button>
                </div>
            </form>

            <div class="divider" aria-hidden="true"></div>

            <div style="display:flex;flex-direction:column;gap:10px;">
                <div style="font-size:13px;color:var(--muted);text-align:center">Or continue with</div>
                <div class="socials">
                    {{-- <a href="{{ route('social.redirect', 'google') ?? '#' }}">Google</a>
                    <a href="{{ route('social.redirect', 'facebook') ?? '#' }}">Facebook</a> --}}
                </div>
            </div>

            <div class="meta" style="justify-content:center;margin-top:16px">
                <span>Don't have an account?</span>
                @if (Route::has('register'))
                    {{-- <a class="link" href="{{ route('register') }}">Sign up</a> --}}
                @endif
            </div>
        </section>
    </main>
</body>
</html>
