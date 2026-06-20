<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        $paymentMethods = $customer->paymentMethods()->latest('is_default', 'desc')->latest()->get();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'paymentMethods' => $paymentMethods]);
        }

        $isMobile = request()->attributes->get('is_mobile');

        return view($isMobile ? 'customer.mobile.payments' : 'customer.desktop.payments', compact('paymentMethods'));
    }

    public function store(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $validated = $request->validate([
            'type' => 'required|in:card,upi,wallet',
            'provider' => 'nullable|string|max:50',
            'last_four' => 'nullable|string|max:4',
            'upi_id' => 'nullable|string|max:100',
            'wallet_name' => 'nullable|string|max:50',
            'cardholder_name' => 'nullable|string|max:255',
            'is_default' => 'boolean',
        ]);

        $validated['customer_id'] = $customer->id;

        if (!empty($validated['is_default']) && $validated['is_default']) {
            $customer->paymentMethods()->where('is_default', true)->update(['is_default' => false]);
        }

        $method = CustomerPaymentMethod::create($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'paymentMethod' => $method, 'message' => 'Payment method added.']);
        }

        return redirect()->route('customer.payments')->with('success', 'Payment method added successfully.');
    }

    public function destroy(Request $request, string $id)
    {
        $customer = Auth::guard('customer')->user();

        $customer->paymentMethods()->where('id', $id)->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Payment method removed.']);
        }

        return redirect()->route('customer.payments')->with('success', 'Payment method removed.');
    }

    public function setDefault(Request $request, string $id)
    {
        $customer = Auth::guard('customer')->user();

        $customer->paymentMethods()->where('is_default', true)->update(['is_default' => false]);
        $customer->paymentMethods()->where('id', $id)->update(['is_default' => true]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Default payment method updated.']);
        }

        return redirect()->route('customer.payments')->with('success', 'Default payment method updated.');
    }
}
