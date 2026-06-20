<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function dashboard()
    {
        $customer = Auth::guard('customer')->user();
        return view('patient.pages.dashboard', compact('customer'));
    }

    public function appointments()
    {
        $customer = Auth::guard('customer')->user();
        return view('patient.pages.appointments', compact('customer'));
    }

    public function virtualCare()
    {
        $customer = Auth::guard('customer')->user();
        return view('patient.pages.virtual-care', compact('customer'));
    }

    public function medicalRecords()
    {
        $customer = Auth::guard('customer')->user();
        return view('patient.pages.medical-records', compact('customer'));
    }

    public function prescriptions()
    {
        $customer = Auth::guard('customer')->user();
        return view('patient.pages.prescriptions', compact('customer'));
    }

    public function payments()
    {
        $customer = Auth::guard('customer')->user();
        return view('patient.pages.payments', compact('customer'));
    }

    public function messages()
    {
        $customer = Auth::guard('customer')->user();
        return view('patient.pages.messages', compact('customer'));
    }

    public function notifications()
    {
        $customer = Auth::guard('customer')->user();
        return view('patient.pages.notifications', compact('customer'));
    }

    public function profile()
    {
        $customer = Auth::guard('customer')->user();
        return view('patient.pages.profile', compact('customer'));
    }

    public function updateProfile(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|max:255|unique:customers,email,' . $customer->id,
            'phone'      => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender'     => 'nullable|in:male,female,other',
            'blood_group' => 'nullable|string|max:5',
            'address'    => 'nullable|string|max:500',
            'city'       => 'nullable|string|max:100',
            'state'      => 'nullable|string|max:100',
            'zip_code'   => 'nullable|string|max:10',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'insurance_provider' => 'nullable|string|max:255',
            'insurance_id' => 'nullable|string|max:100',
        ]);

        $customer->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Profile updated successfully.']);
        }

        return redirect()->route('patient.profile')->with('success', 'Profile updated successfully.');
    }

    public function settings()
    {
        $customer = Auth::guard('customer')->user();
        return view('patient.pages.settings', compact('customer'));
    }

    public function updateSettings(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $validated = $request->validate([
            'email_notifications' => 'nullable|boolean',
            'sms_notifications'   => 'nullable|boolean',
            'appointment_reminders' => 'nullable|boolean',
            'marketing_emails'    => 'nullable|boolean',
        ]);

        $customer->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Settings updated.']);
        }

        return redirect()->route('patient.settings')->with('success', 'Settings updated.');
    }
}
