<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BrevoService;
use App\Models\Setting;
use Exception;

class NewsletterController extends Controller
{
    protected $brevoService;

    public function __construct(BrevoService $brevoService)
    {
        $this->brevoService = $brevoService;
    }

    /**
     * Handle newsletter subscription
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        try {
            $listId = Setting::get('brevo_list_id');
            $listIds = $listId ? [(int)$listId] : [];

            $this->brevoService->addContact(
                $request->email,
                '', // firstName
                '', // lastName
                $listIds
            );

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Thank you for subscribing to our newsletter!'
                ]);
            }

            return back()->with('success', 'Thank you for subscribing to our newsletter!');

        } catch (Exception $e) {
            // Log the error if needed
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong. Please try again later.'
                ], 500);
            }

            return back()->with('error', 'Something went wrong. Please try again later.');
        }
    }
}
