<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LaundryHub - Aplikasi Manajemen Laundry Modern</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-color: #0f172a;
            --surface-color: rgba(30, 41, 59, 0.7);
            --surface-border: rgba(255, 255, 255, 0.1);
            --primary: #38bdf8;
            --primary-hover: #0284c7;
            --accent: #818cf8;
            --success: #34d399;
            --warning: #fbbf24;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            background-image: 
                radial-gradient(at 0% 0%, rgba(56, 189, 248, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(129, 140, 248, 0.15) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(15, 23, 42, 1) 0px, transparent 100%);
            color: var(--text-main);
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--surface-border);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(56, 189, 248, 0.3);
            font-size: 1.5rem;
        }

        .logo-text h1 {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(to right, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .logo-text p {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .badges {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .badge {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--surface-border);
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .badge-dot {
            width: 6px;
            height: 6px;
            background-color: var(--success);
            border-radius: 50%;
            box-shadow: 0 0 8px var(--success);
        }

        .main-grid {
            display: grid;
            grid-template-columns: 1fr 1.3fr;
            gap: 2rem;
        }

        @media (max-width: 900px) {
            .main-grid {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background: var(--surface-color);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--surface-border);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
        }

        .card-header {
            margin-bottom: 1.5rem;
        }

        .card-header h2 {
            font-size: 1.3rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 0.25rem;
        }

        .card-header p {
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #e2e8f0;
        }

        .form-control {
            width: 100%;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--surface-border);
            border-radius: 12px;
            padding: 0.85rem 1rem;
            color: #fff;
            font-size: 0.95rem;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2);
        }

        .service-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .service-card {
            border: 2px solid var(--surface-border);
            background: rgba(15, 23, 42, 0.5);
            border-radius: 14px;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }

        .service-card:hover {
            border-color: rgba(56, 189, 248, 0.5);
            transform: translateY(-2px);
        }

        .service-card.selected {
            border-color: var(--primary);
            background: rgba(56, 189, 248, 0.1);
            box-shadow: 0 0 20px rgba(56, 189, 248, 0.15);
        }

        .service-card h4 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .service-card p {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
        }

        .service-card .price-tag {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--primary);
        }

        .weight-input-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .weight-btn {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid var(--surface-border);
            color: #fff;
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .weight-btn:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .calc-preview {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.9), rgba(15, 23, 42, 0.9));
            border: 1px dashed rgba(56, 189, 248, 0.3);
            border-radius: 14px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .calc-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
            color: var(--text-muted);
        }

        .calc-row.total {
            font-size: 1.15rem;
            font-weight: 700;
            color: #fff;
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid var(--surface-border);
        }

        .calc-row.total .price {
            color: var(--success);
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, var(--primary), #0284c7);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 1rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 15px rgba(2, 132, 199, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(2, 132, 199, 0.4);
        }

        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        th {
            text-align: left;
            padding: 0.75rem 1rem;
            color: var(--text-muted);
            border-bottom: 1px solid var(--surface-border);
            font-weight: 500;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        tr:hover td {
            background: rgba(255, 255, 255, 0.02);
        }

        .status-pill {
            display: inline-block;
            padding: 0.25rem 0.65rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-pending {
            background: rgba(251, 191, 36, 0.15);
            color: var(--warning);
            border: 1px solid rgba(251, 191, 36, 0.3);
        }

        .status-in-progress {
            background: rgba(56, 189, 248, 0.15);
            color: var(--primary);
            border: 1px solid rgba(56, 189, 248, 0.3);
        }

        .status-completed {
            background: rgba(52, 211, 153, 0.15);
            color: var(--success);
            border: 1px solid rgba(52, 211, 153, 0.3);
        }

        .toast {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: #1e293b;
            border: 1px solid var(--surface-border);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            display: none;
            align-items: center;
            gap: 0.75rem;
            z-index: 1000;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from { transform: translateY(100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .toast.success {
            border-color: var(--success);
        }

        .toast.error {
            border-color: #ef4444;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--text-muted);
        }

        .empty-state-icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            opacity: 0.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <div class="logo-icon">🧺</div>
                <div class="logo-text">
                    <h1>LaundryHub</h1>
                    <p>Clean • Fast • Reliable</p>
                </div>
            </div>
            <div class="badges">
                <div class="badge">
                    <span class="badge-dot"></span>
                    <span>API v1 Ready</span>
                </div>
                <div class="badge">
                    <span>PHPStan Level 8</span>
                </div>
                <div class="badge">
                    <span>Strict Types 100%</span>
                </div>
            </div>
        </header>

        <div class="main-grid">
            <!-- Form Order Baru -->
            <div class="card">
                <div class="card-header">
                    <h2>Buat Pesanan Baru</h2>
                    <p>Input detail cucian pelanggan untuk kalkulasi instan.</p>
                </div>

                <form id="orderForm">
                    <div class="form-group">
                        <label class="form-label" for="customerName">Nama Pelanggan</label>
                        <input type="text" id="customerName" class="form-control" placeholder="Contoh: Budi Santoso" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tipe Layanan</label>
                        <div class="service-options">
                            <div class="service-card selected" data-type="standar" onclick="selectService('standar')">
                                <h4>Standar</h4>
                                <p>Reguler 24 Jam</p>
                                <div class="price-tag">Rp 10.000 / kg</div>
                            </div>
                            <div class="service-card" data-type="express" onclick="selectService('express')">
                                <h4>Express</h4>
                                <p>Prioritas 6 Jam</p>
                                <div class="price-tag">Rp 20.000 / kg</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="weightKg">Berat Cucian (Minimal 2 kg)</label>
                        <div class="weight-input-container">
                            <button type="button" class="weight-btn" onclick="adjustWeight(-0.5)">-</button>
                            <input type="number" id="weightKg" class="form-control" style="text-align: center; font-weight: 700; font-size: 1.1rem;" value="3.0" step="0.5" min="2" required oninput="updateCalculation()">
                            <button type="button" class="weight-btn" onclick="adjustWeight(0.5)">+</button>
                        </div>
                    </div>

                    <div class="calc-preview">
                        <div class="calc-row">
                            <span>Tarif per Kg</span>
                            <span id="previewRate">Rp 10.000</span>
                        </div>
                        <div class="calc-row">
                            <span>Total Berat</span>
                            <span id="previewWeight">3.0 kg</span>
                        </div>
                        <div class="calc-row total">
                            <span>Estimasi Total</span>
                            <span class="price" id="previewTotal">Rp 30.000</span>
                        </div>
                    </div>

                    <button type="submit" id="btnSubmit" class="btn-submit">Proses Pesanan (POST /api/orders)</button>
                </form>
            </div>

            <!-- Riwayat Pesanan -->
            <div class="card">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h2>Riwayat Pesanan Terbaru</h2>
                        <p>Daftar pesanan yang telah tersimpan di database.</p>
                    </div>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>No. Order</th>
                                <th>Pelanggan</th>
                                <th>Layanan</th>
                                <th>Berat</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="orderTableBody">
                            @forelse ($orders as $order)
                                <tr>
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                    <td>{{ $order->customer?->name ?? 'Anonim' }}</td>
                                    <td>
                                        <span style="text-transform: capitalize;">{{ $order->service_type }}</span>
                                    </td>
                                    <td>{{ number_format((float) $order->weight_kg, 1) }} kg</td>
                                    <td style="font-weight: 600; color: var(--success);">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="status-pill status-{{ strtolower($order->status->value) }}">
                                            {{ $order->status->label() }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr id="emptyRow">
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">📄</div>
                                            <p>Belum ada transaksi tersimpan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast">
        <span id="toastIcon" style="font-size: 1.25rem;">✅</span>
        <span id="toastMessage">Pesanan berhasil dibuat!</span>
    </div>

    <script>
        let selectedService = 'standar';

        function selectService(type) {
            selectedService = type;
            document.querySelectorAll('.service-card').forEach(card => {
                if (card.dataset.type === type) {
                    card.classList.add('selected');
                } else {
                    card.classList.remove('selected');
                }
            });
            updateCalculation();
        }

        function adjustWeight(amount) {
            const input = document.getElementById('weightKg');
            let current = parseFloat(input.value) || 2.0;
            let next = Math.max(2.0, Math.round((current + amount) * 10) / 10);
            input.value = next.toFixed(1);
            updateCalculation();
        }

        function formatRupiah(number) {
            return 'Rp ' + number.toLocaleString('id-ID');
        }

        function updateCalculation() {
            const weight = parseFloat(document.getElementById('weightKg').value) || 0;
            const rate = selectedService === 'express' ? 20000 : 10000;
            const total = Math.round(weight * rate);

            document.getElementById('previewRate').textContent = formatRupiah(rate);
            document.getElementById('previewWeight').textContent = weight.toFixed(1) + ' kg';
            document.getElementById('previewTotal').textContent = formatRupiah(total);
        }

        function showToast(message, isError = false) {
            const toast = document.getElementById('toast');
            const toastIcon = document.getElementById('toastIcon');
            const toastMessage = document.getElementById('toastMessage');

            toastMessage.textContent = message;
            toast.className = 'toast ' + (isError ? 'error' : 'success');
            toastIcon.textContent = isError ? '❌' : '✅';
            toast.style.display = 'flex';

            setTimeout(() => {
                toast.style.display = 'none';
            }, 4000);
        }

        document.getElementById('orderForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const btn = document.getElementById('btnSubmit');
            const customerName = document.getElementById('customerName').value.trim();
            const weight = parseFloat(document.getElementById('weightKg').value);

            if (!customerName) {
                showToast('Nama pelanggan wajib diisi', true);
                return;
            }

            if (weight < 2.0) {
                showToast('Berat cucian minimal 2 kg', true);
                return;
            }

            btn.disabled = true;
            btn.textContent = 'Memproses ke API...';

            try {
                const response = await fetch('/api/orders', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        customer_name: customerName,
                        weight_kg: weight,
                        service_type: selectedService
                    })
                });

                const data = await response.json();

                if (response.ok && data.data) {
                    showToast(`Sukses! Pesanan ${data.data.order_number} berhasil dibuat.`);

                    // Append row to table
                    const tbody = document.getElementById('orderTableBody');
                    const emptyRow = document.getElementById('emptyRow');
                    if (emptyRow) emptyRow.remove();

                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <td><strong>${data.data.order_number}</strong></td>
                        <td>${data.data.customer_name}</td>
                        <td><span style="text-transform: capitalize;">${data.data.service_type}</span></td>
                        <td>${parseFloat(data.data.weight_kg).toFixed(1)} kg</td>
                        <td style="font-weight: 600; color: var(--success);">${formatRupiah(data.data.total_amount)}</td>
                        <td><span class="status-pill status-pending">${data.data.status}</span></td>
                    `;
                    tbody.prepend(newRow);

                    // Reset form
                    document.getElementById('customerName').value = '';
                    document.getElementById('weightKg').value = '3.0';
                    selectService('standar');
                } else {
                    const errorMsg = data.message || (data.errors ? Object.values(data.errors).flat().join(', ') : 'Terjadi kesalahan validasi.');
                    showToast(errorMsg, true);
                }
            } catch (err) {
                showToast('Gagal menghubungi server API: ' + err.message, true);
            } finally {
                btn.disabled = false;
                btn.textContent = 'Proses Pesanan (POST /api/orders)';
            }
        });

        // Initialize calculation
        updateCalculation();
    </script>
</body>
</html>
