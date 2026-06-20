<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RewardController extends Controller
{
    public function index(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        $rewards = $customer->getOrCreateRewards();
        $transactions = $rewards->transactions()->latest()->paginate(15);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'rewards' => $rewards, 'transactions' => $transactions]);
        }

        $isMobile = request()->attributes->get('is_mobile');

        return view($isMobile ? 'customer.mobile.rewards' : 'customer.desktop.rewards', compact('rewards', 'transactions'));
    }

    public function redeem(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        $rewards = $customer->getOrCreateRewards();

        $request->validate([
            'points' => 'required|integer|min:1|max:' . $rewards->points_balance,
            'purpose' => 'required|string|max:255',
        ]);

        $rewards->redeem($request->points, 'Redeemed: ' . $request->purpose);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $request->points . ' points redeemed successfully.',
                'balance' => $rewards->fresh()->points_balance,
            ]);
        }

        return redirect()->route('customer.rewards')->with('success', 'Points redeemed successfully.');
    }
}
