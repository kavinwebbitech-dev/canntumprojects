<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use App\Models\Coupon;
use App\Models\Address;
use App\Models\Upload;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Services\SmsService;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Mail;
use PDF;

class PaymentController extends Controller
{
    public function paymentProcess(Request $request, SmsService $smsService)
    {
        
        $shipping_address_id = $request->shipping_address;
        $shipping_address = Address::find($shipping_address_id);

        date_default_timezone_set('Asia/Kolkata');

        $invoiceNumber = str_pad(Order::count() + 1, 5, '0', STR_PAD_LEFT) . '-' . now()->format('dmY');

        // Cart calculation
        $cartProducts = session()->get('cart', []);
        $gst = 0;
        $total_amount = 0;
        $subtotal = 0;
        foreach ($cartProducts as $data) {
            $price      = (float)$data['offer_price'];
            $qty        = (int)($data['quantity'] ?? 1);
            $discount   = (float)($data['discount'] ?? 0);
            $gstPercent = (float)($data['gst'] ?? 0);

            $discountedPrice = $price - ($price * $discount / 100);
            // $lineSubtotal = $discountedPrice * $qty;
            $lineSubtotal = $price * $qty;
            // dd($lineSubtotal,$qty,$price);
            $subtotal += $lineSubtotal;
            // $lineGst = ($lineSubtotal * $gstPercent) / 100;
            // $gst += $lineGst;
            $total_amount += ($lineSubtotal);
        }

        $shipping_charge = (float)$request->shipping_charge;
        $coupon_discount = (float)$request->coupon_discount;
        $total_amount = ($total_amount + $shipping_charge) - $coupon_discount;
        $payment_method = $request->payment_method;

        // -----------------------------
        // Cash on Delivery (COD)
        // -----------------------------
        if ($payment_method == 'cod') {
            $order = new Order();
            $order->user_id = auth()->user()->id;
            $order->payment_method = $payment_method;
            $order->total_amount = $total_amount;
            $order->shipping_address = $shipping_address_id;
            $order->gst = $gst;
            $order->shipping_charge = $shipping_charge;
            $order->coupon_discount = $coupon_discount;
            $order->payment_order_id = $invoiceNumber;
            $order->coupon_id = session('coupon.id');
            $order->save();
            if ($order->coupon_id) {
                $coupon = Coupon::find($order->coupon_id);
                if ($coupon && $coupon->user_limit > 0) {
                    $coupon->decrement('user_limit');
                }
            }

            foreach ($cartProducts as $productDetails) {
                // $product_details = ProductDetail::find($productDetails['id']);
                // if ($product_details) {
                //     $product_details->decrement('quantity', $productDetails['quantity']);
                // }

                // $product = Product::find($product_details->product_id);
                
                $product_details = ProductDetail::find($productDetails['id']);

                    if (!$product_details) {
                        continue; // skip invalid item
                    }
                
                    // Update stock
                    $product_details->decrement('quantity', $productDetails['quantity']);
                
                    // Now it's safe
                    $product = Product::find($product_details->product_id);
                
                    if (!$product) {
                        continue;
                    }
    
                $ProductDiscountedPrice = $product_details->price - ($product_details->price * ($product->discount / 100));

                $orderDetail = new OrderDetail();
                $orderDetail->user_id = auth()->user()->id;
                $orderDetail->order_id = $order->id;
                $orderDetail->product_id = $product->id;
                $orderDetail->product_detail_id = $product_details->id;
                $orderDetail->quantity = $productDetails['quantity'];
                $orderDetail->productname = $product->product_name;
                $orderDetail->size_id = $productDetails['size_id'] ?? null;
                $orderDetail->color_id = $productDetails['color_id'] ?? null;
                $orderDetail->offer_price = $ProductDiscountedPrice;
                $orderDetail->product_gst = $product->gst;
                $orderDetail->product_discount = $product->discount;
                $orderDetail->image_index = $productDetails['image_index'] ?? 0;
                $orderDetail->selected_image = $productDetails['selected_image'] ?? null;
                $orderDetail->save();
            }

            $order_details = OrderDetail::where('order_id', $order->id)->get();
            $data = [
                'shipping_address' => $shipping_address,
                'order'            => $order,
                'order_details'    => $order_details,
                'total_gst'        => $order->gst,
                'invoiceNumber'    => $invoiceNumber,
            ];

            $smsService->sendSms($shipping_address->shipping_phone, $order->payment_order_id, 'order_confirmed');
    
            $pdf = PDF::loadView('frontend.product.invoice', $data)->setPaper('a4')->setOptions(['isRemoteEnabled' => true]);
            // return $pdf->stream();

            
            
            // if (auth()->user()->email) {

            //     Mail::raw('New Order placed.', function ($message) use ($pdf) {
            //         $message->to(auth()->user()->email)->subject('Invoice')->attachData($pdf->output(), 'invoice.pdf');
            //     });
            // }
            
            // Mail::raw('A new order has been placed.', function ($message) {
            //     $message->to('canntumemporium@gmail.com')->subject('New Order Success');
            // });
            
            // User Email
                try {
                    if (auth()->user()->email) {
                        Mail::raw('New Order placed.', function ($message) use ($pdf) {
                            $message->to(auth()->user()->email)
                                ->subject('Invoice')
                                ->attachData($pdf->output(), 'invoice.pdf');
                        });
                    }
                } catch (\Exception $e) {
                    \Log::error('User Mail Error: ' . $e->getMessage());
                }
                
                // Admin Email
                try {
                    Mail::raw('A new order has been placed.', function ($message) {
                        $message->to('canntumemporium@gmail.com')
                            ->subject('New Order Success');
                    });
                } catch (\Exception $e) {
                    \Log::error('Admin Mail Error: ' . $e->getMessage());
                }
            
            session()->forget(['cart', 'coupon']);
            
            return view('frontend.product.thankyou', compact('order', 'shipping_address'));
        }

        // -----------------------------
        // Razorpay Online Payment
        // -----------------------------
        else if ($payment_method == 'razorpay') {
            $amountInPaise = round($total_amount, 2) * 100;
            $key_id = env('RAZORPAY_KEY');
            $key_secret = env('RAZORPAY_SECRET');

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.razorpay.com/v1/orders",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode([
                    'receipt' => $invoiceNumber,
                    'amount' => (int)$amountInPaise,
                    'currency' => 'INR',
                    'payment_capture' => 1
                ]),
                CURLOPT_HTTPHEADER => [
                    "Authorization: Basic " . base64_encode($key_id . ":" . $key_secret),
                    "Content-Type: application/json"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                return back()->with('error', 'Razorpay Order creation failed.');
            }

            $razorpayOrder = json_decode($response);
            $razorpayOrderId = $razorpayOrder->id;

            // ✅ IMPORTANT: Save order data to SESSION, NOT to Database yet.
            session([
                'razorpay_order_pending' => [
                    'user_id' => auth()->user()->id,
                    'payment_method' => $payment_method,
                    'total_amount' => $total_amount,
                    'shipping_address' => $shipping_address_id,
                    'gst' => $gst,
                    'shipping_charge' => $shipping_charge,
                    'coupon_discount' => $coupon_discount,
                    'payment_order_id' => $invoiceNumber,
                    'razorpay_order_id' => $razorpayOrderId,
                    'coupon_id' => session('coupon.id'),
                ]
            ]);

            // We pass a dummy order object or just the necessary variables to the view
            return view('frontend.product.razorpay_payment', compact('razorpayOrderId', 'total_amount'));
        }
    }

    public function paymentSuccess(Request $request, SmsService $smsService)
    {
        $razorpayPaymentId = $request->input('razorpay_payment_id');
        $razorpayOrderId = $request->input('razorpay_order_id');
        $razorpaySignature = $request->input('razorpay_signature');

        $key_secret = env('RAZORPAY_SECRET');
        $generated_signature = hash_hmac('sha256', $razorpayOrderId . "|" . $razorpayPaymentId, $key_secret);

        if ($generated_signature === $razorpaySignature) {
            
            // ✅ Fetch pending data from session
            $pendingData = session('razorpay_order_pending');
            $cartProducts = session('cart');

            if (!$pendingData) {
                return redirect()->route('product.proceed_to_checkout')->with('error', 'Session expired. Please try again.');
            }

            // ✅ 1. Save Order to Database now that payment is successful
            $order = new Order();
            $order->user_id = $pendingData['user_id'];
            $order->payment_method = $pendingData['payment_method'];
            $order->total_amount = $pendingData['total_amount'];
            $order->shipping_address = $pendingData['shipping_address'];
            $order->gst = $pendingData['gst'];
            $order->shipping_charge = $pendingData['shipping_charge'];
            $order->coupon_discount = $pendingData['coupon_discount'];
            $order->payment_order_id = $pendingData['payment_order_id'];
            $order->razorpay_order_id = $pendingData['razorpay_order_id'];
            $order->razorpay_payment_id = $razorpayPaymentId;
            $order->payment_status = 'Paid';
            $order->coupon_id = $pendingData['coupon_id'];
            $order->save();

            // ✅ 2. Handle Coupon
            if ($order->coupon_id) {
                $coupon = Coupon::find($order->coupon_id);
                if ($coupon && $coupon->user_limit > 0) {
                    $coupon->decrement('user_limit');
                }
            }

            // ✅ 3. Save Order Details & Update Stock
            foreach ($cartProducts as $item) {
                // $product_details = ProductDetail::find($item['id']);
    
                // if ($product_details) {
                //     $product_details->decrement('quantity', $item['quantity']);
                // }

                // $product = Product::find($product_details->product_id);
                
                $product_details = ProductDetail::find($item['id']);

                    if (!$product_details) {
                        continue; // skip invalid item
                    }
                
                    // Update stock
                    $product_details->decrement('quantity', $item['quantity']);
                
                    // Now it's safe
                    $product = Product::find($product_details->product_id);
                
                    if (!$product) {
                        continue;
                    }
    
                $discountedPrice = $product_details->price - ($product_details->price * ($product->discount / 100));

                $orderDetail = new OrderDetail();
                $orderDetail->user_id = auth()->user()->id;
                $orderDetail->order_id = $order->id;
                $orderDetail->product_id = $product->id;
                $orderDetail->product_detail_id = $product_details->id;
                $orderDetail->quantity = $item['quantity'];
                $orderDetail->productname = $product->product_name;
                
                $orderDetail->size_id = $product_details['size_id'] ?? null;
                $orderDetail->color_id = $product_details['color_id'] ?? null;
                $orderDetail->offer_price = $discountedPrice;
                $orderDetail->product_gst = $product->gst;
                $orderDetail->product_discount = $product->discount;
                $orderDetail->image_index = $product_details['image_index'] ?? 0;
                $orderDetail->selected_image = $product_details['selected_image'] ?? null;
                $orderDetail->save();
            }
            
            $order_details = OrderDetail::where('order_id', $order->id)->get();
            $shipping_address = Address::find($order->shipping_address);

            $data = [
                'shipping_address' => $shipping_address,
                'order'            => $order,
                'order_details'    => $order_details,
                'total_gst'        => $order->gst,
                'invoiceNumber'    => $order->payment_order_id,
            ];

            $pdf = PDF::loadView('frontend.product.invoice', $data)
                ->setPaper('a4')
                ->setOptions(['isRemoteEnabled' => true]);

            // ✅ Send Invoice Email ONLY ONCE
            // if (auth()->user()->email) {
            //     Mail::raw('Your order has been placed successfully.', function ($message) use ($pdf) {
            //         $message->to(auth()->user()->email)
            //             ->subject('Invoice')
            //             ->attachData($pdf->output(), 'invoice.pdf');
            //     });
            // }
            
            //  Mail::raw('A new order has been placed.', function ($message) {
            //     $message->to('canntumemporium@gmail.com')->subject('New Order Success');
            // });
            
            // Send invoice to user
            try {
                if (auth()->user()->email) {
                    Mail::raw('Your order has been placed successfully.', function ($message) use ($pdf) {
                        $message->to(auth()->user()->email)
                            ->subject('Invoice')
                            ->attachData($pdf->output(), 'invoice.pdf');
                    });
                }
            } catch (\Exception $e) {
                \Log::error('User Mail Error: ' . $e->getMessage());
            }
            
            // Send notification to admin
            try {
                Mail::raw('A new order has been placed.', function ($message) {
                    $message->to('canntumemporium@gmail.com')
                        ->subject('New Order Success');
                });
            } catch (\Exception $e) {
                \Log::error('Admin Mail Error: ' . $e->getMessage());
            }
            
            
            // ✅ 4. Cleanup and Notifications
            session()->forget(['cart', 'coupon', 'razorpay_order_pending']);


            $smsService->sendSms($shipping_address->shipping_phone, $order->payment_order_id, 'order_confirmed');

            return view('frontend.product.thank_you_online', compact('order'));
        } else {
            return redirect()->route('product.proceed_to_checkout')->with('error', 'Payment verification failed.');
        }
    }

    public function invoice(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');
        $order            = json_decode($request->order);
        $order_details    = OrderDetail::with('orderProductDetail', 'product')->where('order_id', $order->id)->get();

        $shipping_address = Address::where('id', $request->shipping_address)->where('user_id', auth()->id())->first();

        if (!$shipping_address) {
            return redirect()->route('product.proceed_to_checkout')->with('error', 'Shipping address required.');
        }

        $data = [
            'shipping_address' => $shipping_address,
            'order'            => $order,
            'order_details'    => $order_details,
            'total_gst'        => $order->gst,
            'shipping_charge' => $order->shipping_charge,
            'coupon_discount'  => $order->coupon_discount,
            'invoiceNumber'    => $order->payment_order_id,
        ];

        $pdf = PDF::loadView('frontend.product.invoice', $data);
        Mail::raw('Your Invoice', function ($message) use ($pdf) {
            $message->to(auth()->user()->email)->subject('Invoice')->attachData($pdf->output(), 'invoice.pdf');
        });

        return redirect()->route('user.order.history');
    }
}