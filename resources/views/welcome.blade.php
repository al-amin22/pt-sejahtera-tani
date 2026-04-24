<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PT Sejahtera Tani</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #07111f;
            --panel: rgba(10, 19, 34, 0.78);
            --panel-border: rgba(148, 163, 184, 0.18);
            --text: #e5eefb;
            --muted: #98a6ba;
            --brand: #7dd3fc;
            --brand-2: #34d399;
            --shadow: 0 24px 80px rgba(0, 0, 0, 0.35);
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(125, 211, 252, 0.18), transparent 30%),
                radial-gradient(circle at top right, rgba(52, 211, 153, 0.14), transparent 28%),
                linear-gradient(160deg, #030712 0%, #0f172a 50%, #111827 100%);
            color: var(--text);
            min-height: 100vh;
        }

        .container {
            width: min(1120px, calc(100% - 32px));
            margin: 0 auto;
            padding: 32px 0 56px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 52px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .logo {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--brand), var(--brand-2));
            box-shadow: var(--shadow);
        }

        .brand h1 { margin: 0; font-size: 1.05rem; letter-spacing: 0.02em; }
        .brand p { margin: 4px 0 0; color: var(--muted); font-size: 0.92rem; }

        .actions { display: flex; gap: 12px; flex-wrap: wrap; }
        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 18px;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }
        .button:hover { transform: translateY(-1px); }
        .button.primary {
            background: linear-gradient(135deg, #38bdf8, #34d399);
            color: #04111f;
            box-shadow: 0 12px 30px rgba(52, 211, 153, 0.24);
        }
        .button.secondary {
            background: rgba(15, 23, 42, 0.75);
            color: var(--text);
            border: 1px solid var(--panel-border);
        }

        .hero {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 24px;
            align-items: stretch;
        }

        .card {
            background: var(--panel);
            border: 1px solid var(--panel-border);
            border-radius: 28px;
            box-shadow: var(--shadow);
            backdrop-filter: blur(18px);
        }

        .hero-main { padding: 42px; }
        .eyebrow {
            display: inline-flex;
            gap: 8px;
            align-items: center;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(125, 211, 252, 0.12);
            color: #bfeaff;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .hero h2 {
            font-size: clamp(2.2rem, 5vw, 4.2rem);
            line-height: 1.02;
            margin: 20px 0 18px;
            max-width: 12ch;
        }
        .hero p {
            font-size: 1.03rem;
            line-height: 1.8;
            color: var(--muted);
            max-width: 62ch;
            margin: 0;
        }

        .stats {
            margin-top: 28px;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }
        .stat {
            padding: 16px;
            border-radius: 20px;
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid var(--panel-border);
        }
        .stat strong { display: block; font-size: 1.2rem; margin-bottom: 4px; }
        .stat span { color: var(--muted); font-size: 0.9rem; }

        .hero-side { padding: 28px; display: grid; gap: 16px; }
        .feature {
            padding: 20px;
            border-radius: 20px;
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid var(--panel-border);
        }
        .feature h3 { margin: 0 0 8px; font-size: 1rem; }
        .feature p { margin: 0; color: var(--muted); font-size: 0.94rem; line-height: 1.7; }

        .footer {
            margin-top: 24px;
            color: var(--muted);
            text-align: center;
            font-size: 0.92rem;
        }

        @media (max-width: 900px) {
            .hero, .stats { grid-template-columns: 1fr; }
            .topbar { flex-direction: column; align-items: flex-start; }
        }

        @media (max-width: 640px) {
            .hero-main, .hero-side { padding: 22px; }
            .container { width: min(100% - 20px, 1120px); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="topbar">
            <div class="brand">
                <div class="logo"></div>
                <div>
                    <h1>PT Sejahtera Tani</h1>
                    <p>Operational management and reporting platform</p>
                </div>
            </div>

            <div class="actions">
                @if (Route::has('login'))
                    <a class="button secondary" href="{{ route('login') }}">Login</a>
                @endif
                @if (Route::has('register'))
                    <a class="button primary" href="{{ route('register') }}">Register</a>
                @endif
            </div>
        </div>

        <section class="hero">
            <div class="card hero-main">
                <div class="eyebrow">Portfolio-ready Laravel application</div>
                <h2>Modern internal system for production, finance, and staff workflows.</h2>
                <p>
                    This project presents a polished business-style interface for managing operational records,
                    transaction data, employee attendance, and production reporting in one centralized platform.
                </p>

                <div class="stats">
                    <div class="stat">
                        <strong>3 Roles</strong>
                        <span>Admin, staff, and finance access control</span>
                    </div>
                    <div class="stat">
                        <strong>6 Modules</strong>
                        <span>Users, employees, attendance, transactions, accounts, and production</span>
                    </div>
                    <div class="stat">
                        <strong>PDF Export</strong>
                        <span>Reporting support for operational documents</span>
                    </div>
                </div>
            </div>

            <aside class="card hero-side">
                <div class="feature">
                    <h3>What makes it portfolio-ready</h3>
                    <p>Clear structure, role-based navigation, clean documentation, and a professional landing page.</p>
                </div>
                <div class="feature">
                    <h3>Business focus</h3>
                    <p>Designed to show real-world data management flows, not just a starter template.</p>
                </div>
                <div class="feature">
                    <h3>Next improvement targets</h3>
                    <p>Add seeders, screenshots, tests, and consistent validation to finish the portfolio polish.</p>
                </div>
            </aside>
        </section>

        <div class="footer">Laravel 11 • PT Sejahtera Tani portfolio edition</div>
    </div>
</body>
</html>
