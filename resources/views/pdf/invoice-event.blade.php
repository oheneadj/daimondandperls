<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice {{ $booking->reference }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #1a1a1a; line-height: 1.5; }
        .wrap { padding: 40px; }

        /* Header */
        .header-table { width: 100%; border-bottom: 3px solid #18542A; padding-bottom: 20px; margin-bottom: 32px; }
        .company-name { font-size: 22px; font-weight: 700; color: #18542A; margin-bottom: 4px; }
        .company-meta { font-size: 11px; color: #666; line-height: 1.7; }
        .invoice-label { font-size: 30px; font-weight: 800; color: #1a1a1a; letter-spacing: 2px; text-transform: uppercase; }
        .invoice-ref { font-size: 13px; color: #18542A; font-weight: 700; margin-top: 4px; }
        .invoice-date { font-size: 11px; color: #999; margin-top: 3px; }

        /* Info boxes */
        .info-box { background: #fafafa; border: 1px solid #eee; border-radius: 6px; padding: 16px; height: 100%; }
        .info-box-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #aaa; margin-bottom: 8px; }
        .info-box-name { font-weight: 700; font-size: 14px; color: #1a1a1a; margin-bottom: 3px; }
        .info-box-meta { font-size: 11px; color: #555; line-height: 1.8; }

        /* Event highlight box */
        .event-box { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; padding: 16px; }
        .event-box .info-box-label { color: #15803d; }
        .event-type { font-size: 16px; font-weight: 800; color: #15803d; margin-bottom: 6px; }
        .event-meta-row { font-size: 11px; color: #166534; margin-bottom: 3px; }
        .event-meta-row span { color: #555; }

        /* Badge */
        .badge { display: inline-block; font-size: 10px; font-weight: 700; padding: 3px 10px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge-event { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-paid { background: #dcfce7; color: #166534; }

        /* Divider label */
        .section-divider { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #aaa; margin-bottom: 12px; padding-bottom: 6px; border-bottom: 1px solid #f0f0f0; }

        /* Items table */
        .items { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .items thead th { background: #18542A; color: #fff; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; padding: 11px 14px; text-align: left; }
        .items thead th.right { text-align: right; }
        .items tbody td { padding: 13px 14px; border-bottom: 1px solid #f0f0f0; font-size: 12px; vertical-align: top; }
        .items tbody td.right { text-align: right; }
        .items tbody tr:last-child td { border-bottom: none; }
        .item-name { font-weight: 600; color: #1a1a1a; }
        .item-desc { font-size: 10px; color: #888; margin-top: 2px; }

        /* Totals */
        .total-table { width: 260px; margin-left: auto; margin-bottom: 28px; }
        .total-table td { padding: 6px 0; font-size: 12px; }
        .total-table .lbl { color: #666; }
        .total-table .val { text-align: right; font-weight: 600; }
        .total-table .grand td { border-top: 2px solid #18542A; padding-top: 12px; font-size: 16px; font-weight: 800; }
        .total-table .grand .val { color: #18542A; }

        /* Payment block */
        .payment-block { background: #f8f9fa; border: 1px solid #eee; border-radius: 6px; padding: 16px; margin-bottom: 28px; }
        .block-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #aaa; margin-bottom: 10px; }
        .prow { font-size: 12px; color: #333; margin-bottom: 4px; }
        .prow strong { color: #1a1a1a; }

        /* Note block */
        .note-block { background: #fffbeb; border: 1px solid #fde68a; border-radius: 6px; padding: 14px 16px; margin-bottom: 28px; font-size: 11px; color: #92400e; }
        .note-block strong { font-size: 9px; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 4px; color: #b45309; }

        /* Footer */
        .footer { border-top: 1px solid #eee; padding-top: 18px; text-align: center; }
        .footer-thanks { font-size: 13px; font-weight: 700; color: #18542A; margin-bottom: 5px; }
        .footer-meta { font-size: 10px; color: #aaa; }
    </style>
</head>
<body>
<div class="wrap">

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td style="vertical-align:top; width:60%;">
                @if($company['logo'])
                    <img src="{{ public_path('storage/'.$company['logo']) }}" style="max-height:48px; margin-bottom:8px; display:block;">
                    <div style="font-size:15px; font-weight:700; color:#1a1a1a; margin-bottom:4px;">{{ $company['name'] }}</div>
                @else
                    <div class="company-name">{{ $company['name'] }}</div>
                @endif
                <div class="company-meta">
                    @if($company['address']){{ $company['address'] }}<br>@endif
                    @if($company['phone']){{ $company['phone'] }}<br>@endif
                    @if($company['email']){{ $company['email'] }}@endif
                </div>
            </td>
            <td style="vertical-align:top; text-align:right;">
                <div class="invoice-label">Invoice</div>
                <div class="invoice-ref">{{ $booking->reference }}</div>
                <div class="invoice-date">{{ $booking->created_at->format('F d, Y') }}</div>
                <div style="margin-top:8px;"><span class="badge badge-event">Event Catering</span></div>
            </td>
        </tr>
    </table>

    <!-- Billed To + Event Details (side by side) -->
    <table style="width:100%; margin-bottom:24px;">
        <tr>
            <td style="width:48%; vertical-align:top;">
                <div class="info-box">
                    <div class="info-box-label">Billed To</div>
                    <div class="info-box-name">{{ $booking->customer->name }}</div>
                    <div class="info-box-meta">
                        @if($booking->customer->email){{ $booking->customer->email }}<br>@endif
                        @if($booking->customer->phone){{ $booking->customer->phone }}@endif
                    </div>
                </div>
            </td>
            <td style="width:4%;"></td>
            <td style="width:48%; vertical-align:top;">
                <div class="event-box">
                    <div class="info-box-label" style="color:#15803d;">Event Details</div>
                    <div class="event-type">
                        @if($booking->event_type?->value === 'other' && $booking->event_type_other)
                            {{ $booking->event_type_other }}
                        @else
                            {{ str($booking->event_type?->value ?? 'Event')->replace('_',' ')->title() }}
                        @endif
                    </div>
                    @if($booking->event_date)
                        <div class="event-meta-row">📅 <span>{{ $booking->event_date->format('l, F d, Y') }}</span></div>
                    @endif
                    @if($booking->event_start_time)
                        <div class="event-meta-row">🕐 <span>
                            {{ \Carbon\Carbon::parse($booking->event_start_time)->format('g:i A') }}
                            @if($booking->event_end_time) — {{ \Carbon\Carbon::parse($booking->event_end_time)->format('g:i A') }}@endif
                        </span></div>
                    @endif
                    @if($booking->event_location)
                        <div class="event-meta-row">📍 <span>{{ $booking->event_location }}</span></div>
                    @endif
                    @if($booking->pax)
                        <div class="event-meta-row">👥 <span>{{ number_format($booking->pax) }} guests</span></div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- Booking Status -->
    <div style="margin-bottom:24px;">
        <table style="width:100%;">
            <tr>
                <td style="width:48%; vertical-align:top;">
                    <div class="info-box">
                        <div class="info-box-label">Booking Status</div>
                        <div class="info-box-meta">
                            <strong>Status:</strong> {{ str($booking->status->value)->replace('_',' ')->title() }}<br>
                            <strong>Payment:</strong> {{ str($booking->payment_status->value)->replace('_',' ')->title() }}<br>
                            <strong>Booked On:</strong> {{ $booking->created_at->format('F d, Y') }}
                        </div>
                    </div>
                </td>
                @if($booking->delivery_location)
                    <td style="width:4%;"></td>
                    <td style="width:48%; vertical-align:top;">
                        <div class="info-box">
                            <div class="info-box-label">Delivery / Venue Address</div>
                            <div class="info-box-meta">{{ $booking->delivery_location }}</div>
                        </div>
                    </td>
                @endif
            </tr>
        </table>
    </div>

    <!-- Line Items -->
    <div class="section-divider">Catering Packages</div>
    <table class="items">
        <thead>
            <tr>
                <th style="width:8%;">#</th>
                <th style="width:46%;">Package</th>
                <th class="right" style="width:14%;">Qty</th>
                <th class="right" style="width:16%;">Unit Price</th>
                <th class="right" style="width:16%;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($booking->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div class="item-name">{{ $item->package_name ?? $item->package?->name ?? 'Package' }}</div>
                        @if($item->package?->categories->isNotEmpty())
                            <div class="item-desc">{{ $item->package->categories->pluck('name')->join(', ') }}</div>
                        @endif
                        @if($item->package?->serving_size)
                            <div class="item-desc">Serves: {{ $item->package->serving_size }}</div>
                        @endif
                    </td>
                    <td class="right">{{ $item->quantity }}</td>
                    <td class="right">GHc {{ number_format($item->price, 2) }}</td>
                    <td class="right" style="font-weight:600;">GHc {{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <table class="total-table">
        <tr>
            <td class="lbl">Subtotal</td>
            <td class="val">GHc {{ number_format($booking->total_amount, 2) }}</td>
        </tr>
        <tr class="grand">
            <td class="lbl">Total Due</td>
            <td class="val">GHc {{ number_format($booking->total_amount, 2) }}</td>
        </tr>
    </table>

    <!-- Payment Info -->
    @if($booking->payment)
        <div class="payment-block">
            <div class="block-label">Payment Information</div>
            <div class="prow"><strong>Status:</strong> <span class="badge badge-paid">Paid</span></div>
            <div class="prow"><strong>Method:</strong> {{ str($booking->payment->method?->value ?? 'N/A')->replace('_',' ')->title() }}</div>
            @if($booking->payment_channel)
                <div class="prow"><strong>Network:</strong>
                    @if($booking->payment_channel === '13') MTN MoMo
                    @elseif($booking->payment_channel === '6') Telecel
                    @elseif($booking->payment_channel === '7') AT Money
                    @else {{ $booking->payment_channel }}
                    @endif
                </div>
            @endif
            @if($booking->payer_number)<div class="prow"><strong>Paid Via:</strong> {{ $booking->payer_number }}</div>@endif
            @if($booking->payment->paid_at)<div class="prow"><strong>Paid On:</strong> {{ $booking->payment->paid_at->format('F d, Y \a\t h:i A') }}</div>@endif
            <div class="prow"><strong>Reference:</strong> {{ $booking->payment->gateway_reference ?? $booking->payment_reference ?? 'N/A' }}</div>
        </div>
    @elseif($bank['name'])
        <div class="payment-block">
            <div class="block-label">Bank Transfer Details</div>
            <div class="prow"><strong>Bank:</strong> {{ $bank['name'] }}</div>
            <div class="prow"><strong>Account Name:</strong> {{ $bank['account_name'] }}</div>
            <div class="prow"><strong>Account Number:</strong> {{ $bank['account_number'] }}</div>
            @if($bank['branch_code'])<div class="prow"><strong>Branch Code:</strong> {{ $bank['branch_code'] }}</div>@endif
        </div>
    @else
        <div class="note-block">
            <strong>Payment Note</strong>
            A 50% deposit is required to confirm this event booking. Please contact us to arrange payment.
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div class="footer-thanks">Thank you for choosing {{ $company['name'] }}!</div>
        <div class="footer-meta">This is a computer-generated invoice and does not require a signature.</div>
        <div class="footer-meta">Generated on {{ now()->format('F d, Y \a\t h:i A') }}</div>
    </div>

</div>
</body>
</html>
