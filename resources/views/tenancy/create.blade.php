<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buat Tenant - WhatsApp AI Sales Agent</title>
    <style>
        :root {
            --color-primary: #0f766e;
            --color-primary-strong: #115e59;
            --color-danger: #dc2626;
            --color-success: #15803d;
            --color-muted: #64748b;
            --color-text: #0f172a;
            --color-surface: #ffffff;
            --color-surface-subtle: #f8fafc;
            --color-border: #dbe3ef;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            color: var(--color-text);
            background: var(--color-surface-subtle);
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            line-height: 1.5;
        }

        .shell {
            width: min(100% - 32px, 720px);
            margin: 0 auto;
            padding: 48px 0;
        }

        .card {
            border: 1px solid var(--color-border);
            border-radius: 8px;
            padding: 28px;
            background: var(--color-surface);
        }

        .back {
            display: inline-block;
            margin-bottom: 16px;
            color: var(--color-primary);
            font-weight: 800;
            text-decoration: none;
        }

        h1 {
            margin: 0;
            font-size: 1.8rem;
            letter-spacing: 0;
        }

        .subtitle {
            margin: 8px 0 24px;
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
            font: inherit;
        }

        input:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.16);
            outline: none;
        }

        .field-error {
            margin: 7px 0 0;
            color: var(--color-danger);
            font-size: 0.88rem;
        }

        .notice {
            margin-bottom: 18px;
            border: 1px solid rgba(21, 128, 61, 0.28);
            border-radius: 8px;
            padding: 12px 14px;
            color: var(--color-success);
            background: #f0fdf4;
            overflow-wrap: anywhere;
        }

        button {
            width: 100%;
            min-height: 48px;
            border: 0;
            border-radius: 8px;
            padding: 12px 16px;
            color: #ffffff;
            background: var(--color-primary);
            font: inherit;
            font-weight: 800;
            cursor: pointer;
        }

        button:hover {
            background: var(--color-primary-strong);
        }
    </style>
</head>
<body>
    <main class="shell">
        <a class="back" href="{{ route('dashboard') }}">Kembali</a>

        <section class="card">
            <h1>Buat tenant</h1>
            <p class="subtitle">Buat tenant dan akun admin pertama. Link aktivasi akan muncul setelah tenant dibuat.</p>

            @if (session('status'))
                <div class="notice" role="status">
                    {{ session('status') }}
                    @if (session('activation_link'))
                        <br>
                        <strong>Link aktivasi:</strong> {{ session('activation_link') }}
                    @endif
                </div>
            @endif

            <form method="post" action="{{ route('superadmin.tenants.store') }}">
                @csrf

                <div class="form-group">
                    <label for="tenant_name">Nama tenant</label>
                    <input id="tenant_name" name="tenant_name" type="text" value="{{ old('tenant_name') }}" required>
                    @error('tenant_name')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tenant_slug">Slug tenant</label>
                    <input id="tenant_slug" name="tenant_slug" type="text" value="{{ old('tenant_slug') }}">
                    @error('tenant_slug')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="admin_name">Nama admin tenant</label>
                    <input id="admin_name" name="admin_name" type="text" value="{{ old('admin_name') }}" required>
                    @error('admin_name')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="admin_email">Email admin tenant</label>
                    <input id="admin_email" name="admin_email" type="email" value="{{ old('admin_email') }}" required>
                    @error('admin_email')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit">Buat tenant</button>
            </form>
        </section>
    </main>
</body>
</html>
