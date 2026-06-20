<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function index(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        $tickets = $customer->supportTickets()
            ->with(['replies', 'order'])
            ->latest()
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'tickets' => $tickets]);
        }

        $isMobile = request()->attributes->get('is_mobile');

        return view($isMobile ? 'customer.mobile.support.index' : 'customer.desktop.support.index', compact('tickets'));
    }

    public function store(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'category' => 'required|in:order_issue,return_refund,payment,delivery,product,account,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'order_id' => 'nullable|exists:orders,id',
        ]);

        $ticket = SupportTicket::create([
            ...$validated,
            'customer_id' => $customer->id,
            'ticket_number' => SupportTicket::generateTicketNumber(),
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'ticket' => $ticket, 'message' => 'Support ticket created.']);
        }

        return redirect()->route('customer.support.show', $ticket->ticket_number)
            ->with('success', 'Support ticket created successfully.');
    }

    public function show(Request $request, string $ticketNumber)
    {
        $customer = Auth::guard('customer')->user();

        $ticket = $customer->supportTickets()
            ->with([
                'replies.customer',
                'replies.admin',
                'order',
                'order.items',
            ])
            ->where('ticket_number', $ticketNumber)
            ->firstOrFail();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'ticket' => $ticket]);
        }

        $isMobile = request()->attributes->get('is_mobile');

        return view($isMobile ? 'customer.mobile.support.show' : 'customer.desktop.support.show', compact('ticket'));
    }

    public function reply(Request $request, string $ticketNumber)
    {
        $customer = Auth::guard('customer')->user();

        $ticket = $customer->supportTickets()
            ->where('ticket_number', $ticketNumber)
            ->firstOrFail();

        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        SupportReply::create([
            'ticket_id' => $ticket->id,
            'customer_id' => $customer->id,
            'message' => $request->message,
            'is_staff' => false,
        ]);

        $ticket->update(['last_reply_at' => now()]);

        if ($ticket->status === 'waiting_customer') {
            $ticket->update(['status' => 'open']);
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Reply sent.']);
        }

        return redirect()->route('customer.support.show', $ticketNumber)
            ->with('success', 'Reply sent successfully.');
    }
}
