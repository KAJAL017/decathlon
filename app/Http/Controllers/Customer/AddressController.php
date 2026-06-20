<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        $addresses = $customer->addresses()->latest('is_default', 'desc')->latest()->get();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'addresses' => $addresses]);
        }

        $isMobile = request()->attributes->get('is_mobile');

        return view($isMobile ? 'customer.mobile.addresses' : 'customer.desktop.addresses', compact('addresses'));
    }

    public function store(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $validated = $request->validate([
            'label' => 'required|string|in:Home,Work,Other',
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'address_line1' => 'required|string|max:500',
            'address_line2' => 'nullable|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'country' => 'required|string|max:50',
            'is_default' => 'boolean',
        ]);

        $validated['customer_id'] = $customer->id;

        if (!empty($validated['is_default']) && $validated['is_default']) {
            $customer->addresses()->where('is_default', true)->update(['is_default' => false]);
        }

        $address = CustomerAddress::create($validated);

        if ($customer->addresses()->count() === 1) {
            $address->update(['is_default' => true]);
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'address' => $address, 'message' => 'Address added successfully.']);
        }

        return redirect()->route('customer.addresses')->with('success', 'Address added successfully.');
    }

    public function update(Request $request, string $id)
    {
        $customer = Auth::guard('customer')->user();

        $address = $customer->addresses()->findOrFail($id);

        $validated = $request->validate([
            'label' => 'required|string|in:Home,Work,Other',
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'address_line1' => 'required|string|max:500',
            'address_line2' => 'nullable|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'country' => 'required|string|max:50',
            'is_default' => 'boolean',
        ]);

        if (!empty($validated['is_default']) && $validated['is_default']) {
            $customer->addresses()->where('is_default', true)->update(['is_default' => false]);
        }

        $address->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'address' => $address, 'message' => 'Address updated successfully.']);
        }

        return redirect()->route('customer.addresses')->with('success', 'Address updated successfully.');
    }

    public function destroy(Request $request, string $id)
    {
        $customer = Auth::guard('customer')->user();

        $address = $customer->addresses()->findOrFail($id);
        $address->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Address deleted successfully.']);
        }

        return redirect()->route('customer.addresses')->with('success', 'Address deleted successfully.');
    }

    public function setDefault(Request $request, string $id)
    {
        $customer = Auth::guard('customer')->user();

        $customer->addresses()->where('is_default', true)->update(['is_default' => false]);
        $customer->addresses()->where('id', $id)->update(['is_default' => true]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Default address updated.']);
        }

        return redirect()->route('customer.addresses')->with('success', 'Default address updated.');
    }
}
