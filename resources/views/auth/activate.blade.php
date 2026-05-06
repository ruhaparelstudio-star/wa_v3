<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aktivasi Tenant - WhatsApp AI Sales Agent</title>
    <style>
        :root {
            --color-primary: #0f766e;
            --color-primary-strong: #115e59;
            --color-danger: #dc2626;
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
            display: grid;
            min-height: 100vh;
            margin: 0;
            place-items: center;
            color: var(--color-text);
            background: var(--color-surface-subtle);
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            line-height: 1.5;
        }

        .activation-card {
            width: min(100% - 32px, 440px);
            border: 1px solid var(--color-border);
            border-radius: 8px;
            padding: 32px;
            background: var(--color-surface);
            box-shadow: 0 18px 50px rgba(15, 23, 42, 0.1);
        }

        .brand {
            margin: 0 0 8px;
            color: var(--color-primary);
            font-size: 0.84rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        h1 {
            margin: 0;
            font-size: 1.8rem;
            letter-spacing: 0;
        }

        .subtitle {
            margin: 10px 0 26px;
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
        }

        input:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.16);
            outline: none;
        }

        .field-error,
        .alert {
            color: var(--color-danger);
            font-size: 0.88rem;
        }

        .field-error {
            margin: 7px 0 0;
        }

        .alert {
            margin: 0 0 18px;
            border: 1px solid rgba(220, 38, 38, 0.28);
            border-radius: 8px;
            padding: 12px 14px;
            background: #fef2f2;
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
    <main class="activation-card">
        <p class="brand">WhatsApp AI Sales Agent</p>
        <h1>Aktivasi tenant</h1>
        <p class="subtitle">Buat password untuk mengaktifkan akun tenant admin.</p>

        @if ($errors->has('token'))
            <div class="alert" role="alert">{{ $errors->first('token') }}</div>
        @endif

        <form method="post" action="{{ route('activation.store', ['token' => $token]) }}">
            @csrf

            <div class="form-group">
                <label for="name">Nama admin</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name">
                @error('name')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" autocomplete="new-password" required>
                @error('password')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
            </div>

            <button type="submit">Aktifkan akun</button>
        </form>
    </main>
</body>
</html>
