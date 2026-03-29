<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice {{ $booking->reference }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap');
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Outfit', sans-serif; font-size: 12px; color: #1a1a1a; line-height: 1.5; }
        .invoice-container { padding: 40px; }

        /* Header */
        .header { display: flex; justify-content: space-between; margin-bottom: 40px; border-bottom: 3px solid #F96015; padding-bottom: 20px; }
        .company-info h1 { font-size: 22px; font-weight: 700; color: #F96015; margin-bottom: 4px; }
        .company-info p { font-size: 11px; color: #666; }
        .invoice-title { text-align: right; }
        .invoice-title h2 { font-size: 28px; font-weight: 800; color: #1a1a1a; letter-spacing: 2px; text-transform: uppercase; }
        .invoice-title .ref { font-size: 13px; color: #F96015; font-weight: 700; margin-top: 4px; }
        .invoice-title .date { font-size: 11px; color: #888; margin-top: 2px; }

        /* Info Grid */
        .info-grid { width: 100%; margin-bottom: 30px; }
        .info-grid td { vertical-align: top; padding: 0; }
        .info-box { background: #fafafa; border: 1px solid #eee; border-radius: 6px; padding: 16px; }
        .info-box h3 { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #999; margin-bottom: 8px; }
        .info-box p { font-size: 12px; color: #333; margin-bottom: 2px; }
        .info-box .name { font-weight: 700; font-size: 14px; color: #1a1a1a; }

        /* Items Table */
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table thead th { background: #1a1a1a; color: #fff; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; padding: 12px 16px; text-align: left; }
        .items-table thead th:last-child, .items-table thead th:nth-child(3), .items-table thead th:nth-child(4) { text-align: right; }
        .items-table tbody td { padding: 14px 16px; border-bottom: 1px solid #f0f0f0; font-size: 12px; }
        .items-table tbody td:last-child, .items-table tbody td:nth-child(3), .items-table tbody td:nth-child(4) { text-align: right; }
        .items-table tbody tr:last-child td { border-bottom: none; }

        /* Totals */
        .totals-section { width: 100%; margin-bottom: 30px; }
        .totals-section td { padding: 0; }
        .totals-box { float: right; width: 280px; }
        .totals-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 12px; }
        .totals-row.grand { border-top: 2px solid #1a1a1a; padding-top: 12px; margin-top: 4px; }
        .totals-row.grand .label, .totals-row.grand .value { font-size: 16px; font-weight: 800; }
        .totals-row.grand .value { color: #F96015; }

        /* Total table fallback for dompdf */
        .total-table { width: 260px; margin-left: auto; }
        .total-table td { padding: 6px 0; font-size: 12px; }
        .total-table .label { color: #666; }
        .total-table .value { text-align: right; font-weight: 600; }
        .total-table .grand td { border-top: 2px solid #1a1a1a; padding-top: 12px; font-size: 16px; font-weight: 800; }
        .total-table .grand .value { color: #F96015; }

        /* Payment Info */
        .payment-info { background: #f8f9fa; border: 1px solid #eee; border-radius: 6px; padding: 16px; margin-bottom: 30px; }
        .payment-info h3 { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #999; margin-bottom: 8px; }
        .payment-row { font-size: 12px; color: #333; margin-bottom: 4px; }
        .payment-row strong { color: #1a1a1a; }
        .badge-paid { display: inline-block; background: #dcfce7; color: #166534; font-size: 10px; font-weight: 700; padding: 3px 10px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; }

        /* Footer */
        .footer { border-top: 1px solid #eee; padding-top: 20px; text-align: center; }
        .footer p { font-size: 10px; color: #999; }
        .footer .thanks { font-size: 13px; font-weight: 700; color: #F96015; margin-bottom: 6px; }

        /* Currency Symbol Fallback */
        .currency { font-family: 'DejaVu Sans', sans-serif; }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <table style="width: 100%; margin-bottom: 30px; border-bottom: 3px solid #F96015; padding-bottom: 20px;">
            <tr>
                <td style="vertical-align: top; width: 60%;">
                    @if($company['logo'])
                        <img src="{{ public_path('storage/'.$company['logo']) }}" style="max-height: 50px; margin-bottom: 10px;">
                    @else
                        <h1 style="font-size: 22px; font-weight: 700; color: #F96015; margin-bottom: 4px;">{{ $company['name'] }}</h1>
                    @endif
                    <div class="company-info">
                        @if($company['logo'])
                            <h1 style="font-size: 16px; font-weight: 700; color: #1a1a1a; margin-bottom: 4px;">{{ $company['name'] }}</h1>
                        @endif
                        @if($company['address'])<p>{{ $company['address'] }}</p>@endif
                        @if($company['phone'])<p>{{ $company['phone'] }}</p>@endif
                        @if($company['email'])<p>{{ $company['email'] }}</p>@endif
                    </div>
                </td>
                <td style="vertical-align: top; text-align: right;">
                    <div class="invoice-title">
                        <h2>Invoice</h2>
                        <div class="ref">{{ $booking->reference }}</div>
                        <div class="date">{{ $booking->created_at->format('F d, Y') }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Customer & Event Info -->
        <table class="info-grid" style="width: 100%; margin-bottom: 30px;">
            <tr>
                <td style="width: 48%; vertical-align: top;">
                    <div class="info-box">
                        <h3>Billed To</h3>
                        <p class="name">{{ $booking->customer->name }}</p>
                        @if($booking->customer->email)<p>{{ $booking->customer->email }}</p>@endif
                        @if($booking->customer->phone)<p>{{ $booking->customer->phone }}</p>@endif
                    </div>
                </td>
                <td style="width: 4%;"></td>
                <td style="width: 48%; vertical-align: top;">
                    <div class="info-box">
                        <h3>Event Details</h3>
                        <p class="name">{{ str($booking->event_type?->value ?? '--')->replace('_', ' ')->title() }}</p>
                        @if($booking->event_date)
                            <p>{{ $booking->event_date->format('F d, Y') }}</p>
                        @endif
                        @if($booking->event_start_time)
                            <p>{{ \Carbon\Carbon::parse($booking->event_start_time)->format('g:i A') }} — {{ \Carbon\Carbon::parse($booking->event_end_time)->format('g:i A') }}</p>
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <!-- Line Items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 10%;">#</th>
                    <th style="width: 40%;">Package</th>
                    <th style="width: 15%;">Qty</th>
                    <th style="width: 17%;">Unit Price</th>
                    <th style="width: 18%;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($booking->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td style="font-weight: 600;">{{ $item->package_name ?? $item->package?->name ?? 'Package' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td><span class="currency">GHc</span>{{ number_format($item->price, 2) }}</td>
                        <td style="font-weight: 600;"><span class="currency">GHc</span>{{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <table class="total-table">
            <tr>
                <td class="label">Subtotal</td>
                <td class="value"><span class="currency">GHc</span>{{ number_format($booking->total_amount, 2) }}</td>
            </tr>
            <tr class="grand">
                <td class="label">Total Due</td>
                <td class="value">GHc{{ number_format($booking->total_amount, 2) }}</td>
            </tr>
        </table>

        <div style="height: 20px;"></div>

        <!-- Payment Info -->
        @if($booking->payment)
            <div class="payment-info">
                <h3>Payment Information</h3>
                <div class="payment-row">
                    <strong>Status:</strong> <span class="badge-paid">Paid</span>
                </div>
                <div class="payment-row">
                    <strong>Method:</strong> {{ str($booking->payment->method?->value ?? 'N/A')->replace('_', ' ')->title() }}
                </div>
                @if($booking->payment->paid_at)
                    <div class="payment-row">
                        <strong>Paid On:</strong> {{ $booking->payment->paid_at->format('F d, Y \\a\\t h:i A') }}
                    </div>
                @endif
                <div class="payment-row">
                    <strong>Reference:</strong> {{ $booking->payment->gateway_reference ?? 'N/A' }}
                </div>
            </div>
        @elseif($bank['name'])
            <div class="payment-info">
                <h3>Bank Transfer Details</h3>
                <div class="payment-row">
                    <strong>Bank:</strong> {{ $bank['name'] }}
                </div>
                <div class="payment-row">
                    <strong>Account Name:</strong> {{ $bank['account_name'] }}
                </div>
                <div class="payment-row">
                    <strong>Account Number:</strong> {{ $bank['account_number'] }}
                </div>
                @if($bank['branch_code'])
                    <div class="payment-row">
                        <strong>Branch Code:</strong> {{ $bank['branch_code'] }}
                    </div>
                @endif
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p class="thanks">Thank you for choosing {{ $company['name'] }}!</p>
            <p>This is a computer-generated invoice and does not require a signature.</p>
            <p>Generated on {{ now()->format('F d, Y \\a\\t h:i A') }}</p>
        </div>
    </div>
</body>
</html>
