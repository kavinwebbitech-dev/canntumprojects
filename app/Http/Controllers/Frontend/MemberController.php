<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use App\Models\Address;
use App\Models\Country;
use App\Models\Order;
use App\Models\Complaint;
use App\Models\OrderDetail;
use App\Models\Review;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusMail;
use Illuminate\Support\Facades\Log;

class MemberController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        return view('frontend.user.dashboard', compact('user'));
    }



    public function editProfile(Request $request)
    {
        return view('frontend.user.edit_profile');
    }

    public function updateProfile(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'phone' => 'required|digits_between:10,12',
        ]);

        $userId = auth()->id();   // logged-in user id

        $user = User::find($userId);

        if (!$user) {
            return back()->with('error', 'User not found');
        }

        $user->phone = $request->phone;
        $user->save();

        return redirect('user/dashboard')->with('success', 'Mobile number updated successfully');
    }

    // public function updateProfile(Request $request)
    // {
    //     // print_r($request->all()); exit();
    //     $user_id = $request->user_id;
    //     $user                 = User::where('id', $user_id)->first();
    //     $user->name           = $request->name;
    //     $user->last_name      = $request->last_name;
    //     $user->phone          = $request->phone;
    //     $user->email          = $request->email;
    //     $user->door_no_street = $request->door_no_street;
    //     $user->landmark       = $request->landmark;
    //     $user->city           = $request->city;
    //     $user->state          = $request->state;
    //     $user->pincode        = $request->pincode;
    //     $user->save();

    //     return redirect()->route('user.dashboard')->with('success', 'Profile has been updated successfully!');
    // }


    public function changePassword(Request $request)
    {
        return view('frontend.user.change_password');
    }
    public function forgotPassword()
    {
        $user = auth()->user();
        return view('frontend.auth.forgot_password', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'The current password is incorrect.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Password changed successfully.');
    }


    public function userAddress(Request $request)
    {
        $countries = Country::all();
        $states = \App\Models\State::orderBy('name')->get();
        $userAddress = \App\Models\Address::where('user_id', auth()->user()->id)->get();
        return view('frontend.user.address', compact('countries', 'userAddress', 'states'));
    }


    public function deleteAddress($id)
    {
        $address = Address::find($id);
        if ($address) {
            $address->delete();
        }

        return redirect()->back()->with('error', 'Address has been deleted successfully.');
    }


    // public function orderHistory(Request $request)
    // {
    //     $order_type = $request->order_type;
    //     $order_date = $request->order_date;

    //     if($order_type != 0 && $order_date != 0)
    //     {
    //         if($order_date == 'last_days')
    //         {
    //             $startDate = Carbon::now()->subDays(30);
    //             $endDate = Carbon::now(); 

    //             $orders = Order::where('user_id', auth()->user()->id)
    //                            ->where('shipping_status', $order_type)
    //                            ->whereBetween('created_at', [$startDate, $endDate])
    //                            ->get();
    //         }
    //         else if($order_date == 'last_three_month')
    //         {
    //             $currentDate = Carbon::now();
    //             $threeMonthsAgo = $currentDate->subMonths(3);

    //             $orders = Order::where('user_id', auth()->user()->id)
    //                            ->where('shipping_status', $order_type)
    //                            ->where('created_at', '>=', $threeMonthsAgo)
    //                            ->get();
    //         }
    //         else if($order_date == 'year')
    //         {
    //             $currentYear = Carbon::now()->year;

    //             $orders = Order::where('user_id', auth()->user()->id)
    //                            ->where('shipping_status', $order_type)
    //                            ->whereYear('created_at', $currentYear)
    //                            ->get();
    //         }
    //         else
    //         {
    //             $orders      = Order::where('user_id',auth()->user()->id)->where('shipping_status',$order_type)->get();
    //         }
    //     }

    //     else if($order_type !=0 && $order_date == 0)
    //     {

    //             $orders      = Order::where('user_id',auth()->user()->id)->where('shipping_status',$order_type)->get();

    //     }

    //     else if($order_type == 0 && $order_date != 0)
    //     {
    //         if($order_date == 'last_days')
    //         {
    //             $startDate = Carbon::now()->subDays(30);
    //             $endDate = Carbon::now(); 

    //             $orders = Order::where('user_id', auth()->user()->id)
    //                            ->whereBetween('created_at', [$startDate, $endDate])
    //                            ->get();
    //         }
    //         else if($order_date == 'last_three_month')
    //         {
    //             $currentDate = Carbon::now();
    //             $threeMonthsAgo = $currentDate->subMonths(3);

    //             $orders = Order::where('user_id', auth()->user()->id)
    //                            ->where('created_at', '>=', $threeMonthsAgo)
    //                            ->get();
    //         }
    //         else if($order_date == 'year')
    //         {
    //             $currentYear = Carbon::now()->year;

    //             $orders = Order::where('user_id', auth()->user()->id)
    //                            ->whereYear('created_at', $currentYear)
    //                            ->get();
    //         }
    //         else
    //         {
    //             $orders      = Order::where('user_id',auth()->user()->id)->where('shipping_status',$order_type)->latest()->get();
    //         }
    //     }
    //     else
    //     {
    //         $orders      = Order::where('user_id',auth()->user()->id)->latest()->get();
    //     }


    //     return view('frontend.user.order_history',compact('orders'));
    // }

    public function orderHistory(Request $request)
    {
        $order_type = $request->order_type;
        $order_date = $request->order_date;

        $orders = Order::where('user_id', auth()->user()->id);
        if ($order_type != 0) {
            $orders = $orders->where('shipping_status', $order_type);
        }
        if ($order_date != 0) {
            if ($order_date == 'last_days') {
                $startDate = Carbon::now()->subDays(30);
                $orders = $orders->whereBetween('created_at', [$startDate, Carbon::now()]);
            } elseif ($order_date == 'last_three_month') {
                $orders = $orders->where('created_at', '>=', Carbon::now()->subMonths(3));
            } elseif ($order_date == 'year') {
                $orders = $orders->whereYear('created_at', Carbon::now()->year);
            }
        }
        $orders = $orders->orderBy('created_at', 'desc')->take(10)->get();

        
        return view('frontend.user.order_history', compact('orders'));
    }



    public function fetchProducts(Request $request)
    {
        // Fetch products based on the order ID passed in the request
        $orderId = $request->input('order_id');
        $orderDetails = OrderDetail::where('order_id', $orderId)->get();

        // Assuming products are associated with order details
        $products = $orderDetails->map(function ($orderDetail) {
            return $orderDetail->product;
        });

        // Return the products as JSON response
        return response()->json($products);
    }


    public function userOrderDetails($id)
    {
        $orders            = Order::where('id', $id)->first();
        $orders_details    = OrderDetail::where('order_id', $id)->get();
        $orderDetailsCount = OrderDetail::where('order_id', $id)->count();
        $shippingAddress   = Address::where('user_id', auth()->user()->id)->where('id', $orders->shipping_address)->first();

        $totalSubtotal = $orders_details->sum(function ($item) {
            return $item->offer_price * $item->quantity;
        });
        
        $shipping_charge = $orders->shipping_charge ?? 0;

        return view('frontend.user.order_details', compact('orders_details', 'orders', 'orderDetailsCount', 'shippingAddress', 'totalSubtotal', 'shipping_charge'));
    }

    public function addReview(Request $request)
    {
        $rate     = $request->rate_value;
        $commant  = $request->comment;
        $order_id = $request->order_id;
        $user_id  = auth()->user()->id;

        // ✅ Query by order_id column, not primary key id
        $product = OrderDetail::where('order_id', $order_id)->first();

        // ✅ Guard against null before accessing properties
        if (!$product) {
            return redirect()->back()->with('error', 'Order details not found.');
        }

        $review              = new Review();
        $review->user_id     = $user_id;
        $review->product_id  = $product->product_id;
        $review->order_id    = $product->order_id;
        $review->star_count  = $rate;
        $review->command     = $commant;
        $review->save();

        if ($review) {
            return redirect()->back()->with('success', 'Thankyou for your review.');
        }
    }





    public function updateStatus(Request $request)
    {
        // ✅ Validate OUTSIDE try-catch so ValidationException 
        //    returns proper errors to the user, not 'Something went wrong'
        $rules = [
            'order_id' => 'required|exists:orders,id',
            'type'     => 'required|in:cancel,return',
            'remark'   => $request->type === 'return'
                ? 'required|string|max:500'
                : 'nullable|string|max:500',
        ];

        $request->validate($rules);

        try {
            $order = Order::where('id', $request->order_id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            // ========== CANCEL ==========
            if ($request->type === 'cancel') {

                if ($order->shipping_status >= 2) {
                    return back()->with('error', 'Shipped orders cannot be cancelled.');
                }

                $order->order_status  = 2;
                $order->cancelled_at  = now();
            }

            // ========== RETURN ==========
            if ($request->type === 'return') {

                if ($order->shipping_status != 4) {
                    return back()->with('error', 'Return allowed only after delivery.');
                }

                $order->order_status          = 3;
                $order->return_requested_at   = now();
            }

            $order->remark = $request->remark;
            $order->save();
        } catch (\Exception $e) {
            Log::error('Order Status Update Error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong.');
        }


        try {
            if ($order->user && $order->user->email) {
                Mail::to($order->user->email)
                    ->send(new OrderStatusMail($order, $request->type));
                Mail::to('anandhwebbitech@gmail.com')
                    ->send(new OrderStatusMail($order, $request->type));
            }
        } catch (\Exception $e) {
            Log::error('Order Status Mail Error: ' . $e->getMessage());
            // ✅ Don't return error — order was saved, just mail failed
        }

        return back()->with('success', 'Order Status Updated Successfully');
    }
    public function updateProfileImg(Request $request)
    {
        // print_r($request->all()); exit();
        $user_id = auth()->user()->id;
        $user = User::where('id', $user_id)->first();
        $image = $request->file('image');
        if ($image) {
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('profile_images'), $imageName);
            $user->image_name = $imageName;
            $user->image_path = 'profile_images/' . $imageName;

            $user->save();

            return redirect()->back()->with('success', 'profile image has been updated successfully!');
        }

        return redirect()->back()->with('danger', 'please upload your profile image!');
    }

    public function storeEnquiry(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'remark'   => 'required'
        ]);

        Complaint::create([
            'order_id' => $request->order_id,
            'user_id'  => auth()->id(),
            'user_name' => auth()->user()->name,
            'remark'   => $request->remark,
        ]);

        return back()->with('success', 'Your enquiry has been sent successfully.');
    }

    public function getEnquiryMessages($orderId)
    {
        // Get all messages for this order (chat)
        $messages = Complaint::where('order_id', $orderId)
            ->where('user_id', auth()->id()) // 🔐 safety
            ->orderBy('id', 'ASC')
            ->get();

        // ✅ Mark admin replies as READ when user opens chat
        Complaint::where('order_id', $orderId)
            ->where('user_id', auth()->id())
            ->where('status', 'replied')
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        return view('frontend.user.enquiry.chat', compact('messages'));
    }
}
