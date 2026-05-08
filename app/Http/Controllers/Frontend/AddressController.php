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
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use App\Models\Product;
use App\Services\SmsService;

class AddressController extends Controller
{
    public function addNewAddress(Request $request)
    {

        $request->validate([
            'country' => 'required|string',
            'state'   => 'required|string',
            'city'    => 'required|string',
            'pincode' => 'required|digits:6',
        ], [
            'country.required' => 'Country is required.',
            'state.required'   => 'State is required.',
            'pincode.required' => 'Pincode is required.',
            'city.required'    => 'City is required.',
        ]);


        $user = Auth::user();

        if ($request->make_default) {
            Address::where('user_id', $user->id)
                ->update(['make_default' => 0]);
        }

        Address::create([
            'user_id'          => $user->id,
            'shipping_name'    => $request->fname . ' ' . $request->lname,
            'shipping_email'   => $request->email,
            'shipping_phone'   => $request->phone,
            'shipping_address' => $request->address,
            'country'          => $request->country,
            'state_code'          => $request->state_code,
            'state'            => $request->state,
            'city'             => $request->city,
            'pincode'          => $request->pincode,
            'make_default'     => $request->make_default == 1 ? 1 : 0,
        ]);


        return back()->with('addsuccess', 'Address has been added successfully!');
    }


    public function updateAddress(Request $request)
    {
        $request->validate([
            'id'      => 'required|exists:addresses,id',
            'country' => 'required|string',
            'state'   => 'required|string',
            'city'    => 'required|string',
            'pincode' => 'required|digits:6',
        ]);

        $user = Auth::user();

        $address = Address::where('user_id', $user->id)
            ->where('id', $request->id)
            ->first();

        if (!$address) {
            return back()->with('adderror', 'Address not found.');
        }

        $makeDefault = $request->make_default == 1 ? 1 : 0;

        // ✅ If checked → remove other defaults
        if ($makeDefault == 1) {
            Address::where('user_id', $user->id)
                ->where('id', '!=', $address->id)
                ->update(['make_default' => 0]);
        }

        $address->update([
            'shipping_name'    => trim($request->fname . ' ' . $request->lname),
            'shipping_email'   => $request->email,
            'shipping_phone'   => $request->phone,
            'shipping_address' => $request->address,
            'country'          => $request->country,
            'state_code'       => $request->state_code,
            'state'            => $request->state,
            'city'             => $request->city,
            'pincode'          => $request->pincode,
            'make_default'     => $makeDefault,
        ]);

        return redirect()->back()->with('addsuccess', 'Address updated successfully!');
    }


    public function setDefault(Request $request)
    {
        $user        = Auth::user();

        $addresses = Address::where('user_id', $user->id)->get();

        foreach ($addresses as $address) {
            $address->make_default = 0;
            $address->save();
        }


        $change_address               = Address::where('user_id', $user->id)->where('id', $request->address)->first();
        $change_address->make_default = 1;
        $change_address->save();

        return redirect()->back()->with('addsuccess', 'Address has been Changed successfully!');
    }

    public function orderConfirm(Request $request)
    {
        $authUserId = Auth::id();
        $address = Address::where('user_id', $authUserId)->where('make_default', 1)->first();

        if (is_null($address)) {
            return redirect()->back()->with('error', 'Please select a default shipping address before proceeding.');
        }

        $amount          = $request->total_amount;
        $shippingCharge  = $request->shipping_charge;
        $gst             = $request->gst;
        $coupon_discount = $request->coupon_discount;


        return view('frontend.product.payment', compact('amount', 'address', 'shippingCharge', 'gst', 'coupon_discount'));
    }
}
