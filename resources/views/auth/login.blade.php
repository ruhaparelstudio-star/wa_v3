<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - WhatsApp AI Sales Agent</title>
    <style>
        :root {
            --color-primary: #0f766e;
            --color-primary-strong: #115e59;
            --color-success: #16a34a;
            --color-danger: #dc2626;
            --color-warning: #d97706;
            --color-muted: #64748b;
            --color-text: #0f172a;
            --color-surface: #ffffff;
            --color-surface-subtle: #f8fafc;
            --color-border: #dbe3ef;
            --shadow-panel: 0 20px 60px rgba(15, 23, 42, 0.12);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            color: var(--color-text);
            background: var(--color-surface-subtle);
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            line-height: 1.5;
        }

        .login-shell {
            display: grid;
            min-height: 100vh;
            grid-template-columns: minmax(0, 1fr) minmax(360px, 520px);
        }

        .brand-panel {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 48px;
            padding: 56px;
            background:
                linear-gradient(135deg, rgba(15, 118, 110, 0.95), rgba(15, 23, 42, 0.92)),
                linear-gradient(45deg, rgba(255, 255, 255, 0.1) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.08) 50%, rgba(255, 255, 255, 0.08) 75%, transparent 75%, transparent);
            background-size: auto, 32px 32px;
            color: #ffffff;
        }

        .brand-mark {
            display: inline-flex;
            width: 44px;
            height: 44px;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.36);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.14);
            font-size: 22px;
            font-weight: 800;
        }

        .brand-kicker {
            margin: 28px 0 12px;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.78);
        }

        .brand-panel h1 {
            max-width: 640px;
            margin: 0;
            font-size: clamp(2.25rem, 5vw, 4.8rem);
            line-height: 1;
            letter-spacing: 0;
        }

        .brand-panel p {
            max-width: 620px;
            margin: 22px 0 0;
            color: rgba(255, 255, 255, 0.82);
            font-size: 1.05rem;
        }

        .status-row {
            display: grid;
            max-width: 720px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }

        .status-item {
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 8px;
            padding: 16px;
            background: rgba(255, 255, 255, 0.1);
        }

        .status-label {
            display: block;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.78rem;
        }

        .status-value {
            display: block;
            margin-top: 8px;
            font-size: 1rem;
            font-weight: 700;
        }

        .form-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: var(--color-surface);
        }

        .login-card {
            width: min(100%, 420px);
        }

        .login-card h2 {
            margin: 0;
            font-size: 1.75rem;
            letter-spacing: 0;
        }

        .login-card .subtitle {
            margin: 10px 0 28px;
            color: var(--color-muted);
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-size: 0.9rem;
            font-weight: 700;
        }

        input {
            display: block;
            width: 100%;
            min-height: 46px;
            border: 1px solid var(--color-border);
            border-radius: 8px;
            padding: 10px 13px;
            color: var(--color-text);
            background: var(--color-surface);
            font: inherit;
            transition: border-color 160ms ease, box-shadow 160ms ease;
        }

        input:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.16);
            outline: none;
        }

        .field-error {
            margin: 7px 0 0;
            color: var(--color-danger);
            font-size: 0.86rem;
        }

        .alert {
            margin-bottom: 20px;
            border: 1px solid rgba(220, 38, 38, 0.28);
            border-radius: 8px;
            padding: 12px 14px;
            color: #991b1b;
            background: #fef2f2;
            font-size: 0.92rem;
        }

        .button {
            display: inline-flex;
            width: 100%;
            min-height: 48px;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: 0;
            border-radius: 8px;
            padding: 12px 16px;
            color: #ffffff;
            background: var(--color-primary);
            font: inherit;
            font-weight: 800;
            cursor: pointer;
            transition: background-color 160ms ease, transform 160ms ease;
        }

        .button:hover {
            background: var(--color-primary-strong);
        }

        .button:active {
            transform: translateY(1px);
        }

        .button:disabled {
            cursor: not-allowed;
            opacity: 0.72;
            transform: none;
        }

        .spinner {
            display: none;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.45);
            border-top-color: #ffffff;
            border-radius: 999px;
            animation: spin 800ms linear infinite;
        }

        .button.is-loading .spinner {
            display: inline-block;
        }

        .form-footnote {
            margin-top: 18px;
            color: var(--color-muted);
            font-size: 0.88rem;
            text-align: center;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 900px) {
            .login-shell {
                grid-template-columns: 1fr;
            }

            .brand-panel {
                min-height: 38vh;
                padding: 32px 24px;
            }

            .status-row {
                grid-template-columns: 1fr;
            }

            .form-panel {
                align-items: flex-start;
                padding: 32px 24px 44px;
            }
        }

        @media (max-width: 520px) {
            .brand-panel h1 {
                font-size: 2.15rem;
            }

            .brand-panel p {
                font-size: 0.98rem;
            }

            .form-panel {
                padding-inline: 18px;
            }
        }
    </style>
</head>
<body>
    <main class="login-shell">
        <section class="brand-panel" aria-label="WhatsApp AI Sales Agent">
            <div>
                <div class="brand-mark" aria-hidden="true">W</div>
                <p class="brand-kicker">WhatsApp AI Sales Agent</p>
                <h1>Dashboard operasional untuk tim sales wedding vendor.</h1>
                <p>Masuk untuk memantau percakapan, menjaga respon AI tetap grounded, dan mengelola handoff admin dari satu tempat.</p>
            </div>

            <div class="status-row" aria-label="Dashboard focus">
                <div class="status-item">
                    <span class="status-label">Agent mode</span>
                    <span class="status-value">Controlled workflow</span>
                </div>
                <div class="status-item">
                    <span class="status-label">Tenant data</span>
                    <span class="status-value">Isolated source</span>
                </div>
                <div class="status-item">
                    <span class="status-label">Admin control</span>
                    <span class="status-value">Handoff ready</span>
                </div>
            </div>
        </section>

        <section class="form-panel" aria-label="Login form">
            <div class="login-card">
                <h2>Masuk ke dashboard</h2>
                <p class="subtitle">Gunakan akun tenant admin atau superadmin yang sudah aktif.</p>

                @if ($errors->any())
                    <div class="alert" role="alert">
                        {{ $errors->first() }}
                    </div>
                @else
                    <div class="alert" role="alert" hidden data-error-area>
                        Kredensial belum dapat diverifikasi.
                    </div>
                @endif

                @if (session('status'))
                    <div class="alert" role="status">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="post" action="{{ route('login.store') }}" data-login-form>
                    @csrf

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            inputmode="email"
                            required
                            autofocus
                            aria-describedby="@error('email') email-error @enderror"
                            @error('email') aria-invalid="true" @enderror
                        >
                        @error('email')
                            <p class="field-error" id="email-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            required
                            aria-describedby="@error('password') password-error @enderror"
                            @error('password') aria-invalid="true" @enderror
                        >
                        @error('password')
                            <p class="field-error" id="password-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <button class="button" type="submit" data-submit-button>
                        <span class="spinner" aria-hidden="true"></span>
                        <span data-submit-label>Masuk</span>
                    </button>
                </form>

                <p class="form-footnote">Aktivasi tenant dan reset password akan tersedia pada unit berikutnya.</p>
            </div>
        </section>
    </main>

    <script>
        const form = document.querySelector('[data-login-form]');
        const submitButton = document.querySelector('[data-submit-button]');
        const submitLabel = document.querySelector('[data-submit-label]');
        const errorArea = document.querySelector('[data-error-area]');

        form?.addEventListener('submit', () => {
            submitButton.disabled = true;
            submitButton.classList.add('is-loading');
            submitLabel.textContent = 'Memproses...';
        });
    </script>
</body>
</html>
