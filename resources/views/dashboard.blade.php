<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - WhatsApp AI Sales Agent</title>
    <style>
        :root {
            --color-primary: #0f766e;
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
        }

        .shell {
            width: min(100% - 32px, 880px);
            margin: 0 auto;
            padding: 48px 0;
        }

        .topbar,
        .panel {
            border: 1px solid var(--color-border);
            border-radius: 8px;
            background: var(--color-surface);
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 16px 18px;
        }

        .brand {
            margin: 0;
            color: var(--color-primary);
            font-weight: 800;
        }

        .panel {
            margin-top: 18px;
            padding: 24px;
        }

        h1 {
            margin: 0;
            font-size: 1.8rem;
            letter-spacing: 0;
        }

        p {
            color: var(--color-muted);
        }

        a,
        button {
            display: inline-flex;
            min-height: 40px;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 8px;
            padding: 9px 14px;
            color: #ffffff;
            background: var(--color-primary);
            font: inherit;
            font-weight: 800;
            text-decoration: none;
            cursor: pointer;
        }

        form {
            margin: 0;
        }
    </style>
</head>
<body>
    <main class="shell">
        <div class="topbar">
            <p class="brand">WhatsApp AI Sales Agent</p>
            <form method="post" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>

        <section class="panel">
            <h1>Dashboard</h1>
            <p>Masuk sebagai {{ auth()->user()->name }} dengan role {{ auth()->user()->role }}.</p>

            @if (auth()->user()->isSuperadmin())
                <a href="{{ route('superadmin.tenants.create') }}">Buat tenant</a>
            @endif
        </section>
    </main>
</body>
</html>
