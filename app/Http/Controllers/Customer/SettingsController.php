<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'customer' => $customer]);
        }

        $isMobile = request()->attributes->get('is_mobile');

        return view($isMobile ? 'customer.mobile.settings' : 'customer.desktop.settings', compact('customer'));
    }

    public function update(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $validated = $request->validate([
            'timezone' => 'nullable|string|max:50',
            'language' => 'nullable|string|max:5',
            'accepts_marketing' => 'boolean',
        ]);

        $customer->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Settings updated.']);
        }

        return redirect()->route('customer.settings')->with('success', 'Settings updated successfully.');
    }
}
