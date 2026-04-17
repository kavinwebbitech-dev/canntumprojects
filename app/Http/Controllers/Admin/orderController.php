<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Upload;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Address;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Response;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Mail;
use PDF;
use App\Mail\OrderStatusMail;
use Exception;
use Dompdf\Options;
use Illuminate\Support\Facades\Log;

class orderController extends Controller
{
    public function orderList(Request $request)
    {
        $orders = Order::orderBy('created_at', 'desc')->get();

        return view('admin.order.index', compact('orders'));
    }
    // public function adminOrderDetails($id)
    // {
    //     $orders = Order::where('id', $id)->first();
    //     $orders_details = OrderDetail::where('order_id', $id)->get();

    //     $calculatedSubtotal = 0;
    //     $calculatedGst = 0;

    //     foreach ($orders_details as $item) {
    //         $price = (float)$item->offer_price;
    //         $qty = (int)$item->quantity;
    //         $gstPercent = (float)($item->product_gst ?? 0);

    //         // Taxable amount (Price * Qty)
    //         $lineTaxable = $price * $qty;
    //         $calculatedSubtotal += $lineTaxable;

    //         // GST amount for this line
    //         $lineGst = ($lineTaxable * $gstPercent) / 100;
    //         $calculatedGst += $lineGst;
    //     }

    //     // Use the stored GST from order table if the calculation is 0, 
    //     // otherwise use the freshly calculated one for accuracy.
    //     $finalGst = ($calculatedGst > 0) ? $calculatedGst : (float)($orders->gst ?? 0);

    //     return view('admin.order.orders_details', compact('orders', 'orders_details', 'calculatedSubtotal', 'finalGst'));
    // }

    public function adminOrderDetails($id)
    {
        $orders = Order::where('id', $id)->first();
        $orders_details = OrderDetail::where('order_id', $id)->get();

        $calculatedSubtotal = 0;
        $calculatedGst = 0;

        // preload products (performance)
        $productIds = $orders_details->pluck('product_id')->toArray();
        $products = \App\Models\Product::whereIn('id', $productIds)->get()->keyBy('id');

        foreach ($orders_details as $item) {

            $offerPrice = (float)$item->offer_price;
            $qty = (int)$item->quantity;
            $gstPercent = (float)($item->product_gst ?? 18);

            // ✅ get original price from product
            $product = $products[$item->product_id] ?? null;
            $originalPrice = (float)($product->orginal_rate ?? 0);

            // ✅ Subtotal (offer price × qty)
            $lineSubtotal = $offerPrice * $qty;
            $calculatedSubtotal += $lineSubtotal;

            // ✅ GST from ORIGINAL PRICE (NO qty multiplication)
            if ($gstPercent > 0 && $originalPrice > 0) {

                $gstPerItem = ($originalPrice * $gstPercent) / (100 + $gstPercent);

                // ❗ NOT multiplying by qty (as per your rule)
                $lineGst = $gstPerItem;
            } else {
                $lineGst = 0;
            }

            $calculatedGst += $lineGst;
        }

        $finalGst = $calculatedGst;

        return view('admin.order.orders_details', compact(
            'orders',
            'orders_details',
            'calculatedSubtotal',
            'finalGst'
        ));
    }

    // public function updateStatus(Request $request)
    // {
    //     $orderId = $request->input('order_id');
    //     $newStatus = $request->input('new_status');

    //     $order = Order::find($orderId);
    //     if (!$order) {
    //         return response()->json(['error' => 'Order not found'], 404);
    //     }

    //     $order->order_status = $newStatus;
    //     $order->save();

    //     return response()->json(['message' => 'Order status updated successfully']);
    // }
    public function updateStatus(Request $request)
    {
        try {

            $orderId = $request->input('order_id');
            $newStatus = $request->input('new_status');

            $order = Order::with('user')->find($orderId);

            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            $order->order_status = $newStatus;
            $order->save();

            if ($order->user && $order->user->email) {
                Mail::to($order->user->email)
                    ->send(new OrderStatusMail($order, 'order'));
                Mail::to('anandhwebbitech@gmail.com')
                    ->send(new OrderStatusMail($order, 'order'));
            }
            return response()->json([
                'status' => true,
                'message' => 'Order status updated successfully'
            ]);
        } catch (Exception $e) {

            Log::error($e);

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while updating order status'
            ], 500);
        }
    }


    // public function updateShippingStatus(Request $request)
    // {
    //     $orderId = $request->input('order_id');
    //     $newStatus = $request->input('new_status');

    //     $order = Order::find($orderId);
    //     if (!$order) {
    //         return response()->json(['error' => 'Order not found'], 404);
    //     }

    //     $order->shipping_status = $newStatus;
    //     $order->save();

    //     return response()->json(['message' => 'Shipping status updated successfully']);
    // }
    public function updateShippingStatus(Request $request)
    {
        try {

            $orderId = $request->input('order_id');
            $newStatus = $request->input('new_status');

            $order = Order::with('user')->find($orderId);

            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            $order->shipping_status = $newStatus;
            $order->save();

            if ($order->user && $order->user->email) {
                Mail::to($order->user->email)
                    ->send(new OrderStatusMail($order, 'shipping'));
                Mail::to('anandhwebbitech@gmail.com')
                    ->send(new OrderStatusMail($order, 'shipping'));
            }
            return response()->json([
                'status' => true,
                'message' => 'Shipping status updated successfully'
            ]);
        } catch (Exception $e) {

            Log::error($e);

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while updating shipping status'
            ], 500);
        }
    }

    // public function adminDownlaodInvoice($id)
    // {
    //     $order            = Order::where('id', $id)->first();
    //     $order_details    = OrderDetail::where('order_id', $order->id)->get();
    //     $shipping_address = Address::where('user_id', $order->user_id)->where('id', $order->shipping_address)->first();

    //     $cart = OrderDetail::where('order_id', $order->id)->get();


    //     $total_gst = $order->gst;

    //     $invoiceNumber = $order->payment_order_id;



    //     $data = [
    //         'shipping_address' => $shipping_address,
    //         'order'            => $order,
    //         'order_details'    => $order_details,
    //         'total_gst'        => $total_gst,
    //         'invoiceNumber'    => $invoiceNumber,
    //     ];

    //     $html = view('frontend.product.invoice', $data)->render();

    //     // Load HTML content
    //     $dompdf = new Dompdf();
    //     $options = $dompdf->getOptions();
    //     $options->set('isHtml5ParserEnabled', true);

    //     // Set CSS styles
    //     $css = '
    //         /* Add your CSS styles here */
    //     ';
    //     $dompdf->loadHtml('<style>' . $css . '</style>' . $html);

    //     $dompdf->setPaper('A4', 'portrait');

    //     $dompdf->render();


    //     $pdfContent = $dompdf->output();

    //     // Sanitize the filename
    //     $filename = preg_replace('/[^A-Za-z0-9_\-]/', '_', $invoiceNumber) . '.pdf';

    //     // Return the response with PDF content
    //     return response($pdfContent)
    //         ->header('Content-Type', 'application/pdf')
    //         ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    // }



    public function adminDownlaodInvoice($id)
    {
        $order = Order::where('id', $id)->first();
        $order_details = OrderDetail::where('order_id', $order->id)->get();
        $shipping_address = Address::where('user_id', $order->user_id)
            ->where('id', $order->shipping_address)
            ->first();

        $total_gst = $order->gst;
        $invoiceNumber = $order->payment_order_id;

        $data = [
            'shipping_address' => $shipping_address,
            'order'            => $order,
            'order_details'    => $order_details,
            'total_gst'        => $total_gst,
            'invoiceNumber'    => $invoiceNumber,
        ];

        $html = view('frontend.product.invoice', $data)->render();

        // ✅ IMPORTANT PART START
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);   // 🔥 THIS IS REQUIRED
        $options->set('chroot', public_path());   // 🔥 VERY IMPORTANT FOR LOCAL IMAGES

        $dompdf = new Dompdf($options);
        // ✅ IMPORTANT PART END

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfContent = $dompdf->output();

        $filename = preg_replace('/[^A-Za-z0-9_\-]/', '_', $invoiceNumber) . '.pdf';

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
