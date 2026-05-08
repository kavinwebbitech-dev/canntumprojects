<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Tax Invoice</title>

    <style>
         body {
            font-family: DejaVu Sans, sans-serif !important;
            font-size: 12px;
            color: #001E40;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .no-gap-row .rrow {
            padding: 0 !important;
            text-align: c
        }

        th,
        td {
            border: 1px solid #001E40;
            padding: 6px;
            font-size: 12px;

        }

        .header-title {
            font-weight: bold;
        }

        .sub-title {
            font-weight: bold;
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .section-title {
            background: #f2f2f2;
            font-weight: bold;
        }

        .no-border td {
            border: none;
        }

        .total-box td {
            font-weight: bold;
            background: #f7f7f7;
        }

        .watermark {
            position: fixed;
            top: 30%;
            left: 12%;
            width: 70%;
            opacity: 0.05;
            z-index: 1;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-weight: 600;
            font-size: 12px;
            padding: 10px 0;
            background: #fff;
            font-family: DejaVu Sans, sans-serif;
        }
    </style>
</head>

<body>

    <img src="https://i.ibb.co/8Lh0gzWg/canntum-copy-3.png" class="watermark">

    {{-- HEADER --}}
    <table class="no-border">
        <tr>
            <td width="70%">
                <div class="header-title">CANNTUM EMPORIUM</div>
                377, Cross Cut Rd, Coimbatore<br>
                Tamil Nadu – 641012<br>
                Phone: +91 6374723745
            </td>

            <td width="30%" class="right">
                <div class="header-title">TAX INVOICE</div>
                <strong>Original for Recipient</strong>
            </td>
        </tr>
    </table>

    <br>

    {{-- SHIPPING + INVOICE --}}
    <table>
        <tr class="no-gap-row">
            <td class="rrow" width="50%" valign="top" style="padding-bottom: 10px;">
                <div style="border-bottom: 1px solid #000; margin-bottom: 10px;  padding-bottom: 5px;">
                    <span class="sub-title" style="font-weight: bold; margin-left: 33%;">Shipping Details</span>
                </div>

                <div style="margin: 0 0 10px 10px ;">
                    <strong>{{ $shipping_address->shipping_name ?? 'N/A' }}</strong><br>
                    {{ $shipping_address->shipping_address ?? '' }}<br>
                    {{ $shipping_address->city ?? '' }},
                    {{ $shipping_address->state ?? '' }} -
                    {{ $shipping_address->pincode ?? '' }}<br>

                    Phone: {{ $shipping_address->shipping_phone ?? '' }}<br>
                    Email: {{ $shipping_address->shipping_email ?? '' }}
                </div>
            </td>

            <td class="rrow" width="50%" valign="top" style="padding-bottom: 10px;">
                <div style="border-bottom: 1px solid #000; margin-bottom: 10px; padding-bottom: 5px;">
                    <span class="sub-title" style="font-weight: bold; margin-left: 33%;">Invoice Details</span>
                </div>

                <div style="margin: 0 0 10px 10px ;">
                    Invoice No : <strong>{{ $invoiceNumber }}</strong><br>
                    Invoice Date : {{ date('d-m-Y', strtotime($order->created_at)) }}<br>
                    Payment Mode : {{ strtoupper($order->payment_method) }}<br>
                    Place of Supply : {{ $shipping_address->state ?? '' }}<br>
                    State Code : {{ $shipping_address->state_code ?? '' }}
                </div>
            </td>
        </tr>
    </table>

    <br>

    @php
        $subTotal = 0;
        $totalCGST = 0;
        $totalSGST = 0;
        $totalIGST = 0;

        $stateName = strtolower(trim($shipping_address->state ?? ''));
        $isTamilNadu = $stateName == 'tamil nadu';
    @endphp

    {{-- TABLE --}}
    <table>
        <thead>
            <tr class="section-title center">
                <th>S.No</th>
                <th>Description</th>
                <th>Qty</th>
                <th>Rate</th>
                {{-- <th>Taxable Value</th> --}}
                @if ($isTamilNadu)
                    <th>CGST</th>
                    <th>SGST</th>
                @else
                    <th>IGST</th>
                @endif
                <th>Total</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($order_details as $key => $item)
                @php
                    $gstPercent = (float) ($item->product_gst ?? 18);
                    $price = (float) $item->offer_price;
                    $qty = (int) $item->quantity;

                    // ✅ Total (inclusive)
                    $rowTotal = $price * $qty;

                    // ✅ Extract GST
                    if ($gstPercent > 0) {
                        $baseAmount = $rowTotal / (1 + $gstPercent / 100);
                        $gstAmount = $rowTotal - $baseAmount;
                    } else {
                        $baseAmount = $rowTotal;
                        $gstAmount = 0;
                    }

                    $subTotal += $baseAmount;

                    if ($isTamilNadu) {
                        $cgst = $gstAmount / 2;
                        $sgst = $gstAmount / 2;
                        $totalCGST += $cgst;
                        $totalSGST += $sgst;
                    } else {
                        $igst = $gstAmount;
                        $totalIGST += $igst;
                    }
                @endphp

                <tr>
                    <td class="center">{{ $key + 1 }}</td>
                    <td>{{ $item->productname }}</td>
                    <td class="center">{{ $qty }}</td>
                    <td class="right">{{ number_format($price, 2) }}</td>
                    {{-- <td class="right">{{ number_format($baseAmount, 2) }}</td> --}}

                    @if ($isTamilNadu)
                        <td class="right">{{ number_format($cgst, 2) }}</td>
                        <td class="right">{{ number_format($sgst, 2) }}</td>
                    @else
                        <td class="right">{{ number_format($igst, 2) }}</td>
                    @endif

                    <td class="right"><strong>{{ number_format($rowTotal, 2) }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>

    @php
        $coupon = (float) str_replace(',', '', $order->coupon_discount ?? 0);
        $shippingCharge = (float) ($order->shipping_charge ?? 0);

        $totalTax = $isTamilNadu ? $totalCGST + $totalSGST : $totalIGST;

        $grandTotal = $subTotal + $totalTax + $shippingCharge - $coupon;
    @endphp

    {{-- SUMMARY --}}
    <table width="45%" align="right">
        <tr>
            <td>Taxable Amount</td>
            <td class="right">₹ {{ number_format($subTotal, 2) }}</td>
        </tr>

        @if ($isTamilNadu)
            <tr>
                <td>CGST</td>
                <td class="right">₹ {{ number_format($totalCGST, 2) }}</td>
            </tr>
            <tr>
                <td>SGST</td>
                <td class="right">₹ {{ number_format($totalSGST, 2) }}</td>
            </tr>
        @else
            <tr>
                <td>IGST</td>
                <td class="right">₹ {{ number_format($totalIGST, 2) }}</td>
            </tr>
        @endif

        <tr>
            <td>Coupon Discount (-)</td>
            <td class="right">₹ {{ number_format($coupon, 2) }}</td>
        </tr>

        <tr>
            <td>Shipping Charge</td>
            <td class="right">
                {{ $shippingCharge > 0 ? '₹ ' . number_format($shippingCharge, 2) : 'FREE' }}
            </td>
        </tr>

        <tr class="total-box">
            <td>Grand Total</td>
            <td class="right">₹ {{ number_format($grandTotal, 2) }}</td>
        </tr>
    </table>

    <br><br>

    <table style="margin-top:0px; position :relative; top: 300px; bottom: auto;">
        <tr>
            <td colspan="2" style="font-weight:500; background:#f5f5f5;">
                Terms & Conditions
            </td>
        </tr>

        <tr>
            <td width="5%" class="center">1</td>
            <td>Please refer to our official website for further details.</td>
        </tr>

        <tr>
            <td class="center">2</td>
            <td>Subject to Tamil Nadu jurisdiction.</td>
        </tr>

    </table>

    <div class="footer" style="font-size: 14px">
        This is a computer generated invoice and does not require a signature.
        <br>
        <span class="mt-1" style="color:#b00000;">
            Thank you for shopping with us!
        </span>
    </div>

</body>

</html>
