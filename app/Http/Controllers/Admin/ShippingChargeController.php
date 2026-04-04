<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\ShippingCharge;
use Illuminate\Http\Request;

class ShippingChargeController extends Controller
{
    // INDEX
    public function shippingCharge()
    {
        $charges = ShippingCharge::latest()->get();
        return view('admin.shipping.index', compact('charges'));
    }

    // CREATE PAGE
    public function create()
    {
        return view('admin.shipping.create');
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'min_amount' => 'nullable|numeric',
            'max_amount' => 'nullable|numeric',
            'charge' => 'required|numeric',
        ]);

        ShippingCharge::create($request->all());

        return redirect()->route('admin.shipping_charge')
            ->with('success', 'Shipping charge added successfully');
    }

    // EDIT
    public function edit($id)
    {
        $data = ShippingCharge::findOrFail($id);
        return view('admin.shipping.edit', compact('data'));
    }

    // UPDATE
    public function updateShippingCharge(Request $request)
    {
        $data = ShippingCharge::findOrFail($request->id);

        $data->update([
            'min_amount' => $request->min_amount,
            'max_amount' => $request->max_amount,
            'charge' => $request->charge,
            'status' => $request->status ?? 0,
        ]);

        return redirect()->route('admin.shipping_charge')
            ->with('success', 'Updated successfully');
    }

    // DELETE
    public function delete($id)
    {
        ShippingCharge::findOrFail($id)->delete();

        return back()->with('danger', 'Deleted successfully');
    }
}
