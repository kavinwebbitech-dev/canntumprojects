<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    @if (isset($type) && $type == 'shipping')
        <title>Shipping Status</title>
    @elseif(isset($type) && in_array($type, ['order', 'cancel', 'return']))
        <title>Order Status</title>
    @endif
</head>

<body style="font-family: Arial, sans-serif; background:#f4f6f9; padding:20px;">

    <div style="max-width:600px; margin:auto; background:#ffffff; padding:20px; border-radius:8px;">

        @if (isset($type) && $type == 'shipping')
            <h2 style="text-align:center; margin-bottom:20px;">
                Shipping Status
            </h2>
        @elseif(isset($type) && in_array($type, ['order', 'cancel', 'return']))
            <h2 style="text-align:center; margin-bottom:20px;">
                Order Status
            </h2>
        @endif

        <table width="100%" cellpadding="10" cellspacing="0" border="1"
            style="border-collapse: collapse; text-align:center; font-family: Arial, sans-serif;">

            <tr style="background:#f8f9fa; font-weight:bold;">
                <th>Order ID</th>
                <th>Payment Method</th>
                {{-- <th>Total Amount</th> --}}
                <th>Status</th>
            </tr>

            <tr>
                <td>{{ $order->payment_order_id }}</td>
                <td>{{ strtoupper($order->payment_method) }}</td>
                {{-- <td>₹ {{ number_format($order->total_amount, 2) }}</td> --}}

                <td>

                    @if (!empty($type))

                        {{-- ORDER STATUS --}}
                        @if (in_array($type, ['order', 'cancel', 'return']))

                            @if ($order->order_status == 1)
                                <span
                                    style="background:#198754; color:#fff; padding:5px 10px; border-radius:20px; font-size:11px;">
                                    Order Approved
                                </span>
                            @elseif($order->order_status == 2)
                                <span
                                    style="background:#dc3545; color:#fff; padding:5px 10px; border-radius:20px; font-size:11px;">
                                    Order Cancelled
                                </span>
                            @elseif($order->order_status == 3)
                                <span
                                    style="background:#ffc107; color:#000; padding:5px 10px; border-radius:20px; font-size:11px;">
                                    Order Return Requested
                                </span>
                            @elseif($order->order_status == 4)
                                <span
                                    style="background:#0dcaf0; color:#fff; padding:5px 10px; border-radius:20px; font-size:11px;">
                                    Order Returned
                                </span>
                            @else
                                <span
                                    style="background:#6c757d; color:#fff; padding:5px 10px; border-radius:20px; font-size:11px;">
                                    Order Processing
                                </span>
                            @endif

                        @endif


                        {{-- SPACE BETWEEN BADGES --}}
                        @if (in_array($type, ['order', 'cancel', 'return']) && $type == 'shipping')
                            &nbsp;&nbsp;
                        @endif


                        {{-- SHIPPING STATUS --}}
                        @if ($type == 'shipping')

                            @if ($order->shipping_status == 1)
                                <span
                                    style="background:#6c757d; color:#fff; padding:5px 10px; border-radius:20px; font-size:11px;">
                                    Order Received
                                </span>
                            @elseif($order->shipping_status == 2)
                                <span
                                    style="background:#0d6efd; color:#fff; padding:5px 10px; border-radius:20px; font-size:11px;">
                                    Shipped
                                </span>
                            @elseif($order->shipping_status == 3)
                                <span
                                    style="background:#fd7e14; color:#fff; padding:5px 10px; border-radius:20px; font-size:11px;">
                                    Out for Delivery
                                </span>
                            @elseif($order->shipping_status == 4)
                                <span
                                    style="background:#198754; color:#fff; padding:5px 10px; border-radius:20px; font-size:11px;">
                                    Delivered
                                </span>
                            @else
                                <span
                                    style="background:#6c757d; color:#fff; padding:5px 10px; border-radius:20px; font-size:11px;">
                                    Processing
                                </span>
                            @endif

                        @endif

                    @endif

                </td>
            </tr>

        </table>

        <p style="margin-top:20px; text-align:center;">
            Thank you for shopping with us.
        </p>

    </div>

</body>

</html>
