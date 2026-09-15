<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LaundryHub — Modern Clean Garment Care</title>
    
    <!-- Google Fonts: Instrument Serif for editorial display, Plus Jakarta Sans for UI, JetBrains Mono for metrics -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Hallmark Day / Light Editorial Tokens */
            --color-paper: #fbfbfa;
            --color-surface: #ffffff;
            --color-surface-hover: #f8fafc;
            --color-ink: #18181b;
            --color-ink-muted: #71717a;
            --color-ink-subtle: #a1a1aa;
            
            --color-border: #e4e4e7;
            --color-border-subtle: #f4f4f5;
            
            --color-primary: #18181b;
            --color-primary-hover: #27272a;
            --color-accent: #2563eb;
            --color-accent-soft: #eff6ff;
            --color-accent-border: #bfdbfe;
            
            --color-warning-bg: #fefce8;
            --color-warning-ink: #854d0e;
            --color-warning-border: #fef08a;

            --color-success-bg: #f0fdf4;
            --color-success-ink: #166534;
            --color-success-border: #bbf7d0;

            --font-display: "Instrument Serif", Georgia, serif;
            --font-sans: "Plus Jakarta Sans", system-ui, -apple-system, sans-serif;
            --font-mono: "JetBrains Mono", ui-monospace, monospace;

            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.04);
            --shadow-card: 0 1px 3px 0 rgba(0, 0, 0, 0.04), 0 10px 24px -6px rgba(0, 0, 0, 0.04);
            --shadow-float: 0 20px 35px -10px rgba(0, 0, 0, 0.08);

            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 18px;
            --radius-full: 9999px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            overflow-x: clip;
            background-color: var(--color-paper);
            color: var(--color-ink);
            font-family: var(--font-sans);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            line-height: 1.5;
        }

        /* Subtle warm paper noise/gradient */
        body {
            min-height: 100vh;
            background-image: 
                radial-gradient(at 15% 15%, rgba(244, 244, 245, 0.8) 0px, transparent 60%),
                radial-gradient(at 85% 85%, rgba(239, 246, 255, 0.6) 0px, transparent 50%);
            padding: 2.5rem 1.5rem;
        }

        .layout {
            max-width: 1160px;
            margin: 0 auto;
        }

        /* Top Navigation / Brand Bar */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 2rem;
            margin-bottom: 2rem;
            border-bottom: 1px solid var(--color-border);
        }

        .brand-group {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .brand-mark {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-md);
            background: var(--color-primary);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .brand-title {
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--color-ink);
        }

        .brand-subtitle {
            font-size: 0.8rem;
            color: var(--color-ink-muted);
            font-weight: 400;
        }

        .header-meta {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .tag-pill {
            font-family: var(--font-mono);
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.35rem 0.75rem;
            border-radius: var(--radius-full);
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            color: var(--color-ink-muted);
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .tag-indicator {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background-color: #10b981;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
        }

        /* Hero Editorial Section */
        .hero {
            margin-bottom: 2.75rem;
        }

        .hero-eyebrow {
            font-family: var(--font-mono);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--color-accent);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .hero-title {
            font-family: var(--font-display);
            font-size: 3rem;
            line-height: 1.15;
            font-weight: 400;
            color: var(--color-ink);
            letter-spacing: -0.015em;
            max-width: 800px;
            margin-bottom: 0.75rem;
        }

        .hero-desc {
            font-size: 1.05rem;
            color: var(--color-ink-muted);
            max-width: 640px;
            font-weight: 400;
        }

        /* Two-Column Grid */
        .grid-container {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 2rem;
            align-items: start;
        }

        @media (max-width: 960px) {
            .grid-container {
                grid-template-columns: 1fr;
            }
            .hero-title {
                font-size: 2.35rem;
            }
        }

        /* Card Primitive */
        .card {
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow-card);
        }

        .card-header {
            margin-bottom: 1.75rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--color-border-subtle);
        }

        .card-eyebrow {
            font-family: var(--font-mono);
            font-size: 0.7rem;
            color: var(--color-ink-subtle);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.25rem;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--color-ink);
            letter-spacing: -0.015em;
        }

        /* Form Controls */
        .field-group {
            margin-bottom: 1.5rem;
        }

        .field-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--color-ink);
            margin-bottom: 0.5rem;
        }

        .field-hint {
            font-size: 0.75rem;
            color: var(--color-ink-muted);
            font-weight: 400;
            margin-top: 0.25rem;
        }

        .input-text {
            width: 100%;
            height: 46px;
            padding: 0 1rem;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            font-size: 0.95rem;
            color: var(--color-ink);
            background: var(--color-surface);
            outline: none;
            transition: all 0.15s ease;
        }

        .input-text:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(24, 24, 27, 0.08);
        }

        /* Service Cards Selection */
        .service-picker {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.85rem;
        }

        .picker-card {
            border: 1.5px solid var(--color-border);
            background: var(--color-surface);
            border-radius: var(--radius-md);
            padding: 1.15rem;
            cursor: pointer;
            transition: all 0.18s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }

        .picker-card:hover {
            border-color: var(--color-ink-subtle);
            background: var(--color-surface-hover);
        }

        .picker-card.is-active {
            border-color: var(--color-primary);
            background: #ffffff;
            box-shadow: 0 0 0 1px var(--color-primary), 0 4px 14px rgba(0, 0, 0, 0.05);
        }

        .picker-badge {
            font-family: var(--font-mono);
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--color-ink-muted);
            margin-bottom: 0.35rem;
        }

        .picker-name {
            font-size: 1rem;
            font-weight: 600;
            color: var(--color-ink);
            margin-bottom: 0.25rem;
        }

        .picker-rate {
            font-family: var(--font-mono);
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--color-accent);
        }

        /* Weight Numeric Stepper */
        .stepper-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .stepper-btn {
            width: 46px;
            height: 46px;
            border-radius: var(--radius-md);
            border: 1px solid var(--color-border);
            background: var(--color-surface);
            color: var(--color-ink);
            font-size: 1.25rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .stepper-btn:hover {
            background: var(--color-border-subtle);
            border-color: var(--color-ink-subtle);
        }

        .stepper-input {
            flex: 1;
            height: 46px;
            text-align: center;
            font-family: var(--font-mono);
            font-size: 1.15rem;
            font-weight: 600;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            color: var(--color-ink);
            outline: none;
        }

        .stepper-input:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(24, 24, 27, 0.08);
        }

        /* Clean Paper Receipt / Ticket Box */
        .receipt-ticket {
            background: #fafaf9;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            font-family: var(--font-mono);
            position: relative;
        }

        .receipt-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            color: var(--color-ink-muted);
            margin-bottom: 0.4rem;
        }

        .receipt-divider {
            border-top: 1px dashed var(--color-border);
            margin: 0.75rem 0;
        }

        .receipt-total {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--color-ink);
        }

        .receipt-total .amount {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--color-primary);
            font-variant-numeric: tabular-nums;
        }

        /* Primary Action Button */
        .btn-action {
            width: 100%;
            height: 50px;
            background: var(--color-primary);
            color: #ffffff;
            border: none;
            border-radius: var(--radius-md);
            font-size: 0.95rem;
            font-weight: 600;
            letter-spacing: -0.01em;
            cursor: pointer;
            transition: all 0.15s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-action:hover {
            background: var(--color-primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }

        .btn-action:active {
            transform: translateY(0);
        }

        .btn-action:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        /* Orders Ledger Table */
        .ledger-table-wrap {
            overflow-x: auto;
        }

        .ledger-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }

        .ledger-table th {
            text-align: left;
            padding: 0.75rem 0.85rem;
            font-family: var(--font-mono);
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--color-ink-muted);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            border-bottom: 1px solid var(--color-border);
        }

        .ledger-table td {
            padding: 1rem 0.85rem;
            border-bottom: 1px solid var(--color-border-subtle);
            color: var(--color-ink);
        }

        .ledger-table tr:hover td {
            background-color: var(--color-surface-hover);
        }

        .invoice-code {
            font-family: var(--font-mono);
            font-weight: 600;
            color: var(--color-primary);
        }

        .customer-cell {
            font-weight: 500;
        }

        .rate-cell {
            font-family: var(--font-mono);
            font-weight: 600;
            font-variant-numeric: tabular-nums;
            color: var(--color-ink);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.65rem;
            border-radius: var(--radius-full);
            font-size: 0.725rem;
            font-weight: 600;
            font-family: var(--font-mono);
            letter-spacing: 0.02em;
        }

        .badge-pending {
            background: var(--color-warning-bg);
            color: var(--color-warning-ink);
            border: 1px solid var(--color-warning-border);
        }

        .badge-in_progress {
            background: var(--color-accent-soft);
            color: var(--color-accent);
            border: 1px solid var(--color-accent-border);
        }

        .badge-completed {
            background: var(--color-success-bg);
            color: var(--color-success-ink);
            border: 1px solid var(--color-success-border);
        }

        .empty-ledger {
            text-align: center;
            padding: 3.5rem 1rem;
            color: var(--color-ink-muted);
        }

        .empty-ledger-code {
            font-family: var(--font-mono);
            font-size: 0.8rem;
            color: var(--color-ink-subtle);
            margin-top: 0.5rem;
        }

        /* Toast Feedback */
        .toast-banner {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            box-shadow: var(--shadow-float);
            border-radius: var(--radius-md);
            padding: 1rem 1.25rem;
            display: none;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            z-index: 9999;
            animation: popUp 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes popUp {
            from { transform: translateY(16px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .toast-banner.is-success {
            border-left: 4px solid #10b981;
        }

        .toast-banner.is-error {
            border-left: 4px solid #ef4444;
        }
    </style>
</head>
<body>
    <div class="layout">
        <!-- Brand Header -->
        <header class="header">
            <div class="brand-group">
                <div class="brand-mark">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 6h18M7 12h10M10 18h4"/>
                    </svg>
                </div>
                <div>
                    <div class="brand-title">LaundryHub</div>
                    <div class="brand-subtitle">Automated Garment Logistics</div>
                </div>
            </div>

            <div class="header-meta">
                <span class="tag-pill">
                    <span class="tag-indicator"></span>
                    API Live
                </span>
                <span class="tag-pill">PHPStan Level 8</span>
                <span class="tag-pill">Strict Types</span>
            </div>
        </header>

        <!-- Editorial Hero -->
        <section class="hero">
            <div class="hero-eyebrow">Production Order Terminal</div>
            <h2 class="hero-title">Layanan binatu modern dengan kalkulasi tarif instan dan terkalibrasi.</h2>
            <p class="hero-desc">
                Input kuantitas cucian pelanggan untuk menghitung tarif otomatis secara presisi. Mendukung mode Standar dan Express dengan verifikasi status langsung.
            </p>
        </section>

        <!-- Functional Workspace -->
        <main class="grid-container">
            <!-- Order Form Section -->
            <section class="card">
                <div class="card-header">
                    <div class="card-eyebrow">Entry Voucher</div>
                    <h3 class="card-title">Buat Pesanan Baru</h3>
                </div>

                <form id="orderForm">
                    <div class="field-group">
                        <label class="field-label" for="customerName">Nama Lengkap Pelanggan</label>
                        <input type="text" id="customerName" class="input-text" placeholder="Masukkan nama pelanggan..." required autocomplete="off">
                        <div class="field-hint">Pelanggan baru akan otomatis didaftarkan ke sistem.</div>
                    </div>

                    <div class="field-group">
                        <label class="field-label">Pilih Paket Layanan</label>
                        <div class="service-picker">
                            <div class="picker-card is-active" data-service="standar" onclick="setService('standar')">
                                <div class="picker-badge">Reguler • 24 Jam</div>
                                <div class="picker-name">Standar</div>
                                <div class="picker-rate">Rp 10.000<span style="font-size: 0.75rem; font-weight: normal; color: var(--color-ink-muted);"> / kg</span></div>
                            </div>
                            <div class="picker-card" data-service="express" onclick="setService('express')">
                                <div class="picker-badge">Prioritas • 6 Jam</div>
                                <div class="picker-name">Express</div>
                                <div class="picker-rate">Rp 20.000<span style="font-size: 0.75rem; font-weight: normal; color: var(--color-ink-muted);"> / kg</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="weightKg">Berat Timbangan Cucian</label>
                        <div class="stepper-container">
                            <button type="button" class="stepper-btn" onclick="stepWeight(-0.5)">−</button>
                            <input type="number" id="weightKg" class="stepper-input" value="3.0" step="0.5" min="2" required oninput="refreshReceipt()">
                            <button type="button" class="stepper-btn" onclick="stepWeight(0.5)">+</button>
                        </div>
                        <div class="field-hint">Ambang batas penerimaan minimal adalah 2,0 kg.</div>
                    </div>

                    <!-- Receipt Preview Ticket -->
                    <div class="receipt-ticket">
                        <div class="receipt-row">
                            <span>TARIF SATUAN</span>
                            <span id="rateLabel">Rp 10.000</span>
                        </div>
                        <div class="receipt-row">
                            <span>BOBOT TERUKUR</span>
                            <span id="weightLabel">3.0 kg</span>
                        </div>
                        <div class="receipt-divider"></div>
                        <div class="receipt-total">
                            <span>TOTAL BIAYA</span>
                            <span class="amount" id="totalLabel">Rp 30.000</span>
                        </div>
                    </div>

                    <button type="submit" id="submitBtn" class="btn-action">
                        <span>Terbitkan Pesanan</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </button>
                </form>
            </section>

            <!-- Recent Orders Ledger -->
            <section class="card">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: baseline;">
                    <div>
                        <div class="card-eyebrow">Database Ledger</div>
                        <h3 class="card-title">Transaksi Terkini</h3>
                    </div>
                    <span class="tag-pill" style="font-size: 0.65rem;">SQLite Connected</span>
                </div>

                <div class="ledger-table-wrap">
                    <table class="ledger-table">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Pelanggan</th>
                                <th>Layanan</th>
                                <th>Berat</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="ledgerBody">
                            @forelse ($orders as $order)
                                <tr>
                                    <td class="invoice-code">{{ $order->order_number }}</td>
                                    <td class="customer-cell">{{ $order->customer?->name ?? 'Anonim' }}</td>
                                    <td>
                                        <span style="text-transform: capitalize; font-size: 0.8rem; color: var(--color-ink-muted);">
                                            {{ $order->service_type }}
                                        </span>
                                    </td>
                                    <td class="rate-cell">{{ number_format((float) $order->weight_kg, 1) }} kg</td>
                                    <td class="rate-cell">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="status-badge badge-{{ strtolower($order->status->value) }}">
                                            {{ $order->status->label() }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr id="emptyPlaceholder">
                                    <td colspan="6">
                                        <div class="empty-ledger">
                                            <p>Belum ada transaksi tersimpan dalam buku besar.</p>
                                            <div class="empty-ledger-code">LND_REGISTRY_NULL</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <!-- Notification Toast -->
    <div id="toastNotification" class="toast-banner">
        <span id="toastSymbol" style="font-size: 1.1rem;">•</span>
        <span id="toastText">Notifikasi</span>
    </div>

    <script>
        let currentService = 'standar';

        function setService(service) {
            currentService = service;
            document.querySelectorAll('.picker-card').forEach(el => {
                if (el.dataset.service === service) {
                    el.classList.add('is-active');
                } else {
                    el.classList.remove('is-active');
                }
            });
            refreshReceipt();
        }

        function stepWeight(delta) {
            const el = document.getElementById('weightKg');
            let val = parseFloat(el.value) || 2.0;
            let target = Math.max(2.0, Math.round((val + delta) * 10) / 10);
            el.value = target.toFixed(1);
            refreshReceipt();
        }

        function toRupiah(num) {
            return 'Rp ' + Number(num).toLocaleString('id-ID');
        }

        function refreshReceipt() {
            const weight = parseFloat(document.getElementById('weightKg').value) || 0;
            const rate = currentService === 'express' ? 20000 : 10000;
            const grandTotal = Math.round(weight * rate);

            document.getElementById('rateLabel').textContent = toRupiah(rate);
            document.getElementById('weightLabel').textContent = weight.toFixed(1) + ' kg';
            document.getElementById('totalLabel').textContent = toRupiah(grandTotal);
        }

        function triggerToast(text, isError = false) {
            const toast = document.getElementById('toastNotification');
            const symbol = document.getElementById('toastSymbol');
            const label = document.getElementById('toastText');

            toast.className = 'toast-banner ' + (isError ? 'is-error' : 'is-success');
            symbol.textContent = isError ? '⚠' : '✓';
            label.textContent = text;
            toast.style.display = 'flex';

            setTimeout(() => {
                toast.style.display = 'none';
            }, 4500);
        }

        document.getElementById('orderForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const btn = document.getElementById('submitBtn');
            const name = document.getElementById('customerName').value.trim();
            const weight = parseFloat(document.getElementById('weightKg').value);

            if (!name) {
                triggerToast('Nama pelanggan tidak boleh kosong', true);
                return;
            }

            if (weight < 2.0) {
                triggerToast('Berat cucian minimal 2,0 kg', true);
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<span>Menerbitkan Faktur...</span>';

            try {
                const res = await fetch('/api/orders', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        customer_name: name,
                        weight_kg: weight,
                        service_type: currentService
                    })
                });

                const json = await res.json();

                if (res.ok && json.data) {
                    triggerToast(`Pesanan ${json.data.order_number} berhasil diterbitkan.`);

                    // Remove empty placeholder if any
                    const empty = document.getElementById('emptyPlaceholder');
                    if (empty) empty.remove();

                    // Prepend new order to table
                    const tbody = document.getElementById('ledgerBody');
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="invoice-code">${json.data.order_number}</td>
                        <td class="customer-cell">${json.data.customer_name}</td>
                        <td><span style="text-transform: capitalize; font-size: 0.8rem; color: var(--color-ink-muted);">${json.data.service_type}</span></td>
                        <td class="rate-cell">${parseFloat(json.data.weight_kg).toFixed(1)} kg</td>
                        <td class="rate-cell">${toRupiah(json.data.total_amount)}</td>
                        <td><span class="status-badge badge-pending">${json.data.status}</span></td>
                    `;
                    tbody.prepend(tr);

                    // Reset form
                    document.getElementById('customerName').value = '';
                    document.getElementById('weightKg').value = '3.0';
                    setService('standar');
                } else {
                    const errDetail = json.message || (json.errors ? Object.values(json.errors).flat().join(', ') : 'Gagal memproses pesanan.');
                    triggerToast(errDetail, true);
                }
            } catch (err) {
                triggerToast('Gagal terhubung dengan server API.', true);
            } finally {
                btn.disabled = false;
                btn.innerHTML = `
                    <span>Terbitkan Pesanan</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                `;
            }
        });

        // Initialize receipt
        refreshReceipt();
    </script>
</body>
</html>
