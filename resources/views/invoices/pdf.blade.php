<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #1f2937;
            background: #ffffff;
            padding: 0;
        }

        /* ── Header Bar ─────────────────────────────── */
        .header {
            background-color: #1d4ed8;
            color: #ffffff;
            padding: 28px 40px;
        }

        .header-table {
            width: 100%;
        }

        .header-brand {
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .header-sub {
            font-size: 10px;
            color: #bfdbfe;
            margin-top: 3px;
        }

        .header-inv-label {
            font-size: 10px;
            color: #bfdbfe;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header-inv-number {
            font-size: 20px;
            font-weight: bold;
            font-family: 'Courier New', Courier, monospace;
            margin-top: 2px;
        }

        .header-date {
            font-size: 10px;
            color: #bfdbfe;
            margin-top: 4px;
        }

        /* ── Status Badge ───────────────────────────── */
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 6px;
        }

        .status-issued    { background-color: #fef3c7; color: #92400e; }
        .status-paid      { background-color: #d1fae5; color: #065f46; }
        .status-cancelled { background-color: #fee2e2; color: #991b1b; }

        /* ── Body ───────────────────────────────────── */
        .body {
            padding: 32px 40px;
        }

        /* ── Parties ────────────────────────────────── */
        .parties-table {
            width: 100%;
            margin-bottom: 28px;
        }

        .party-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #9ca3af;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .party-name {
            font-size: 13px;
            font-weight: bold;
            color: #111827;
        }

        .party-detail {
            font-size: 11px;
            color: #6b7280;
            margin-top: 2px;
        }

        /* ── Divider ────────────────────────────────── */
        .divider {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin: 20px 0;
        }

        /* ── Order Detail Table ─────────────────────── */
        .section-title {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #9ca3af;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        .items-table thead tr {
            background-color: #f9fafb;
        }

        .items-table th {
            padding: 8px 12px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            border-bottom: 1px solid #e5e7eb;
        }

        .items-table th.right { text-align: right; }

        .items-table td {
            padding: 12px;
            font-size: 12px;
            color: #374151;
            border-bottom: 1px solid #f3f4f6;
        }

        .items-table td.right { text-align: right; }

        .item-desc-main {
            font-weight: 600;
            color: #111827;
        }

        .item-desc-sub {
            font-size: 10px;
            color: #9ca3af;
            margin-top: 2px;
        }

        /* ── Totals ─────────────────────────────────── */
        .totals-table {
            width: 260px;
            margin-left: auto;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 5px 8px;
            font-size: 12px;
            color: #374151;
        }

        .totals-table td.label { color: #6b7280; }
        .totals-table td.value { text-align: right; }

        .totals-divider {
            border-top: 1px solid #d1d5db;
        }

        .totals-grand td {
            font-size: 14px;
            font-weight: bold;
            color: #111827;
            padding-top: 8px;
        }

        /* ── Payment Box ────────────────────────────── */
        .payment-box {
            margin-top: 28px;
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 6px;
            padding: 14px 16px;
        }

        .payment-box-title {
            font-size: 10px;
            font-weight: bold;
            color: #1e40af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .payment-box-text {
            font-size: 11px;
            color: #1d4ed8;
        }

        /* ── Footer ─────────────────────────────────── */
        .footer {
            margin-top: 40px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
        }
    </style>
</head>
<body>

    {{-- ── Header ────────────────────────────────────────────────── --}}
    <div class="header">
        <table class="header-table">
            <tr>
                <td style="width:50%;">
                    <div class="header-brand">FuelApp B2B</div>
                    <div class="header-sub">Sistem Supply Chain Bahan Bakar</div>
                </td>
                <td style="width:50%; text-align:right;">
                    <div class="header-inv-label">Invoice</div>
                    <div class="header-inv-number">{{ $invoice->invoice_number }}</div>
                    <div class="header-date">
                        Diterbitkan: {{ $invoice->issued_at->format('d M Y') }}
                    </div>
                    <div>
                        @php
                            $badgeClass = match($invoice->status->value) {
                                'issued'    => 'status-issued',
                                'paid'      => 'status-paid',
                                'cancelled' => 'status-cancelled',
                                default     => 'status-issued',
                            };
                        @endphp
                        <span class="status-badge {{ $badgeClass }}">
                            {{ $invoice->status->label() }}
                        </span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ── Body ─────────────────────────────────────────────────── --}}
    <div class="body">

        {{-- Parties --}}
        <table class="parties-table">
            <tr>
                <td style="width:48%; vertical-align:top;">
                    <div class="party-label">Dari</div>
                    <div class="party-name">Depo Bahan Bakar</div>
                    <div class="party-detail">Diterbitkan oleh: {{ $invoice->issuer->name }}</div>
                    <div class="party-detail">admin@fuelapp.com</div>
                </td>
                <td style="width:4%;"></td>
                <td style="width:48%; vertical-align:top;">
                    <div class="party-label">Kepada</div>
                    <div class="party-name">{{ $invoice->order->company->name }}</div>
                    <div class="party-detail">{{ $invoice->order->company->email }}</div>
                    @if($invoice->order->company->phone)
                        <div class="party-detail">{{ $invoice->order->company->phone }}</div>
                    @endif
                    @if($invoice->order->company->npwp)
                        <div class="party-detail">NPWP: {{ $invoice->order->company->npwp }}</div>
                    @endif
                    @if($invoice->order->company->address)
                        <div class="party-detail">{{ $invoice->order->company->address }}</div>
                    @endif
                </td>
            </tr>
        </table>

        <hr class="divider">

        {{-- Order Detail --}}
        <div class="section-title">Rincian Pesanan</div>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width:40%;">Deskripsi</th>
                    <th class="right" style="width:18%;">Volume</th>
                    <th class="right" style="width:20%;">Harga / Liter</th>
                    <th class="right" style="width:22%;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="item-desc-main">{{ $invoice->order->fuel_type }}</div>
                        <div class="item-desc-sub">
                            No. Pesanan: {{ $invoice->order->order_number }}<br>
                            Lokasi: {{ $invoice->order->delivery_location }}<br>
                            Jadwal: {{ $invoice->order->scheduled_at->format('d M Y') }}
                        </div>
                    </td>
                    <td class="right">
                        {{ number_format($invoice->order->volume_liters, 0, ',', '.') }} L
                    </td>
                    <td class="right">
                        Rp {{ number_format($invoice->order->unit_price, 0, ',', '.') }}
                    </td>
                    <td class="right" style="font-weight:600;">
                        Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Totals --}}
        <table class="totals-table">
            <tr>
                <td class="label">Subtotal</td>
                <td class="value">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">PPN {{ number_format($invoice->ppn_rate * 100, 0) }}%</td>
                <td class="value">Rp {{ number_format($invoice->ppn_amount, 0, ',', '.') }}</td>
            </tr>
            <tr class="totals-divider">
                <td colspan="2" style="padding:0; height:1px;"></td>
            </tr>
            <tr class="totals-grand">
                <td class="label" style="color:#111827;">Total</td>
                <td class="value">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
            </tr>
        </table>

        {{-- Payment Info --}}
        @if($invoice->status->value === 'issued')
        <div class="payment-box">
            <div class="payment-box-title">Informasi Pembayaran</div>
            <div class="payment-box-text">
                Silakan lakukan pembayaran sebesar
                <strong>Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</strong>
                sesuai dengan nomor virtual account yang diberikan.<br>
                Status pesanan akan diperbarui otomatis setelah pembayaran dikonfirmasi.
            </div>
        </div>
        @elseif($invoice->status->value === 'paid')
        <div class="payment-box" style="background-color:#f0fdf4; border-color:#bbf7d0;">
            <div class="payment-box-title" style="color:#166534;">Pembayaran Lunas</div>
            <div class="payment-box-text" style="color:#15803d;">
                Invoice ini telah dibayar lunas pada
                {{ $invoice->paid_at?->format('d M Y, H:i') }}.
                Terima kasih atas kepercayaan Anda.
            </div>
        </div>
        @endif

        {{-- Footer --}}
        <div class="footer">
            <p>Dokumen ini digenerate secara otomatis oleh sistem FuelApp B2B.</p>
            <p style="margin-top:3px;">
                Invoice {{ $invoice->invoice_number }} &bull;
                {{ $invoice->issued_at->format('d M Y, H:i') }} &bull;
                Diterbitkan oleh {{ $invoice->issuer->name }}
            </p>
        </div>

    </div>

</body>
</html>
