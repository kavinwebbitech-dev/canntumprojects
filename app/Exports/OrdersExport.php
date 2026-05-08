<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Address;
use App\Models\User;
use App\Models\Product;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{
    protected $from;
    protected $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to   = $to;
    }

    public function collection()
    {

        $orders = Order::whereDate('created_at', '>=', $this->from)
            ->whereDate('created_at', '<=', $this->to)
            ->latest()
            ->get();

        $rows = [];

        foreach ($orders as $order) {

            $user = User::find($order->user_id);

            $address = Address::find($order->shipping_address);

            $stateName = strtolower(trim($address->state ?? ''));

            $isTamilNadu = ($stateName == 'tamil nadu');

            $orderDetails = OrderDetail::where('order_id', $order->id)->get();

            foreach ($orderDetails as $detail) {

                $product = Product::find($detail->product_id);

                $gstPercent = (float)($product->gst ?? 0);

                $qty = (int)$detail->quantity;

                $rate = (float)$detail->offer_price;

                // =========================
                // TOTAL (GST INCLUSIVE)
                // =========================
                $rowTotal = $rate * $qty + $order->shipping_charge;

                // =========================
                // TAXABLE VALUE
                // =========================
                if ($gstPercent > 0) {

                    $taxableValue = $rowTotal / (1 + ($gstPercent / 100));

                    $gstAmount = $rowTotal - $taxableValue;
                } else {

                    $taxableValue = $rowTotal;

                    $gstAmount = 0;
                }

                $cgst = 0;
                $sgst = 0;
                $igst = 0;

                if ($isTamilNadu) {

                    $cgst = $gstAmount / 2;
                    $sgst = $gstAmount / 2;
                } else {

                    $igst = $gstAmount;
                }
                $unitValue = (int)($product->unit_value ?? 1);

                $totalUnits = $qty * $unitValue;

                $unitType = $product->unit_type ?? 'Piece';

                $rows[] = [

                    'Invoice Date' => date('d-m-Y', strtotime($order->created_at)),

                    'Customer Name' => $user->name ?? '-',

                    'Place Of Supply' => $address->state ?? '-',

                    'Payment Method' => strtoupper($order->payment_method ?? '-'),

                    'Invoice Number' => $order->payment_order_id,

                    'HSN Code' => $product->hsn_code ?? '-',
                    
                    'Quantity' => $qty,

                    'Units' => $totalUnits . ' ' . $unitType,

                    'GST Percentage' => $gstPercent . '%',

                    // =========================
                    // TAXABLE VALUE
                    // =========================
                    'Taxable Value' => number_format($taxableValue, 2),

                    'CGST' => number_format($cgst, 2),

                    'SGST' => number_format($sgst, 2),

                    'IGST' => number_format($igst, 2),

                    'Total Amount' => number_format($rowTotal, 2),
                ];
            }
        }

        return collect($rows);
    }

    public function headings(): array
    {
        return [

            'Invoice Date',

            'Customer Name',

            'Place Of Supply',

            'Payment Method',

            'Invoice Number',

            'HSN Code',

            'Quantity',

            'Units',

            'GST Percentage',

            'Taxable Value',

            'CGST',

            'SGST',

            'IGST',

            'Total Amount',
        ];
    }
}
