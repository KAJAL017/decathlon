<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'customer' => $customer]);
        }

        $isMobile = request()->attributes->get('is_mobile');

        return view($isMobile ? 'customer.mobile.profile' : 'customer.desktop.profile', compact('customer'));
    }

    public function update(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:15|unique:customers,phone,' . $customer->id,
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
        ]);

        $customer->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Profile updated successfully.', 'customer' => $customer->fresh()]);
        }

        return redirect()->route('customer.profile')->with('success', 'Profile updated successfully.');
    }

    public function updateAvatar(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($customer->avatar && file_exists(public_path('uploads/customers/' . $customer->avatar))) {
            unlink(public_path('uploads/customers/' . $customer->avatar));
        }

        $file = $request->file('avatar');
        $filename = 'avatar_' . $customer->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/customers'), $filename);

        $customer->update(['avatar' => $filename]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Avatar updated.', 'avatar' => $filename]);
        }

        return redirect()->route('customer.profile')->with('success', 'Avatar updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $customer->password)) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Current password is incorrect.'], 422);
            }
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $customer->update(['password' => Hash::make($request->password)]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Password updated successfully.']);
        }

        return redirect()->route('customer.profile')->with('success', 'Password updated successfully.');
    }
}
