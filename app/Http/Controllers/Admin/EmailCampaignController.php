<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailCampaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmailCampaignController extends Controller
{
    public function index()
    {
        return view('admin.pages.email-campaigns.index');
    }

    public function list(Request $request)
    {
        $q = EmailCampaign::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(fn($q) => $q->where('name', 'like', "%$s%")->orWhere('subject', 'like', "%$s%"));
        }
        if ($request->filled('type'))   $q->where('type', $request->type);
        if ($request->filled('status')) $q->where('status', $request->status);

        $perPage = (int) $request->get('per_page', 15);
        $items   = $q->orderByDesc('created_at')->paginate($perPage);

        $items->getCollection()->transform(
            fn($c) => tap($c, fn($c) => $c->append(['open_rate', 'click_rate', 'status_color']))
        );

        // Stats
        $stats = [
            'total'         => EmailCampaign::count(),
            'sent'          => EmailCampaign::where('status', 'sent')->count(),
            'scheduled'     => EmailCampaign::where('status', 'scheduled')->count(),
            'draft'         => EmailCampaign::where('status', 'draft')->count(),
            'avg_open_rate' => $this->calcAvgOpenRate(),
        ];

        return response()->json([
            'success'    => true,
            'data'       => $items->items(),
            'pagination' => [
                'total'        => $items->total(),
                'per_page'     => $items->perPage(),
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
            ],
            'stats' => $stats,
        ]);
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), $this->rules());
        if ($v->fails()) return response()->json(['success' => false, 'errors' => $v->errors()], 422);

        $data = $request->only(array_keys($this->rules()));
        $data['created_by'] = session('admin_id');

        // Handle tags as array
        if (isset($data['tags']) && is_string($data['tags'])) {
            $data['tags'] = array_filter(array_map('trim', explode(',', $data['tags'])));
        }

        // Auto-set status
        if (empty($data['status'])) {
            $data['status'] = !empty($data['scheduled_at']) ? 'scheduled' : 'draft';
        }

        $campaign = EmailCampaign::create($data);
        \App\Models\ActivityLog::log('created', 'email_campaigns', "Created campaign: {$campaign->name}", ['id' => $campaign->id]);

        return response()->json([
            'success' => true,
            'message' => 'Campaign created successfully',
            'data'    => $campaign->append(['open_rate', 'click_rate', 'status_color']),
        ]);
    }

    public function show($id)
    {
        $c = EmailCampaign::find($id);
        if (!$c) return response()->json(['success' => false, 'message' => 'Not found'], 404);
        return response()->json(['success' => true, 'data' => $c->append(['open_rate', 'click_rate', 'status_color'])]);
    }

    public function update(Request $request, $id)
    {
        $c = EmailCampaign::find($id);
        if (!$c) return response()->json(['success' => false, 'message' => 'Not found'], 404);

        $v = Validator::make($request->all(), $this->rules($id));
        if ($v->fails()) return response()->json(['success' => false, 'errors' => $v->errors()], 422);

        $data = $request->only(array_keys($this->rules($id)));

        // Handle tags as array
        if (isset($data['tags']) && is_string($data['tags'])) {
            $data['tags'] = array_filter(array_map('trim', explode(',', $data['tags'])));
        }

        // Auto-set status if scheduled_at provided and status is draft
        if (!empty($data['scheduled_at']) && ($c->status === 'draft' || empty($data['status']))) {
            $data['status'] = 'scheduled';
        }

        $c->update($data);
        \App\Models\ActivityLog::log('updated', 'email_campaigns', "Updated campaign: {$c->name}", ['id' => $c->id]);

        return response()->json([
            'success' => true,
            'message' => 'Campaign updated successfully',
            'data'    => $c->fresh()->append(['open_rate', 'click_rate', 'status_color']),
        ]);
    }

    public function destroy($id)
    {
        $c = EmailCampaign::find($id);
        if (!$c) return response()->json(['success' => false, 'message' => 'Not found'], 404);
        \App\Models\ActivityLog::log('deleted', 'email_campaigns', "Deleted campaign: {$c->name}", ['id' => $c->id]);
        $c->delete();
        return response()->json(['success' => true, 'message' => 'Campaign deleted']);
    }

    public function toggleStatus($id)
    {
        $c = EmailCampaign::find($id);
        if (!$c) return response()->json(['success' => false, 'message' => 'Not found'], 404);

        // Toggle between draft <-> paused for non-sent campaigns
        $c->status = match($c->status) {
            'draft'     => 'paused',
            'paused'    => 'draft',
            'scheduled' => 'paused',
            default     => 'draft',
        };
        $c->save();

        return response()->json(['success' => true, 'message' => 'Status updated', 'data' => $c->append(['status_color'])]);
    }

    public function duplicate($id)
    {
        $c = EmailCampaign::find($id);
        if (!$c) return response()->json(['success' => false, 'message' => 'Not found'], 404);

        $new = $c->replicate();
        $new->name               = $c->name . ' (Copy)';
        $new->status             = 'draft';
        $new->total_recipients   = 0;
        $new->sent_count         = 0;
        $new->opened_count       = 0;
        $new->clicked_count      = 0;
        $new->unsubscribed_count = 0;
        $new->bounced_count      = 0;
        $new->sent_at            = null;
        $new->scheduled_at       = null;
        $new->created_by         = session('admin_id');
        $new->save();

        \App\Models\ActivityLog::log('created', 'email_campaigns', "Duplicated campaign: {$new->name}", ['id' => $new->id]);

        return response()->json([
            'success' => true,
            'message' => 'Campaign duplicated successfully',
            'data'    => $new->append(['open_rate', 'click_rate', 'status_color']),
        ]);
    }

    public function bulkAction(Request $request)
    {
        $v = Validator::make($request->all(), [
            'action' => 'required|in:delete,pause,cancel',
            'ids'    => 'required|array|min:1',
        ]);
        if ($v->fails()) return response()->json(['success' => false, 'errors' => $v->errors()], 422);

        $count = 0;
        foreach ($request->ids as $id) {
            $c = EmailCampaign::find($id);
            if (!$c) continue;
            if ($request->action === 'delete')  { $c->delete(); $count++; }
            elseif ($request->action === 'pause')  { $c->status = 'paused';    $c->save(); $count++; }
            elseif ($request->action === 'cancel') { $c->status = 'cancelled'; $c->save(); $count++; }
        }

        return response()->json(['success' => true, 'message' => "$count campaign(s) affected"]);
    }

    private function calcAvgOpenRate(): string
    {
        $sent = EmailCampaign::where('status', 'sent')->where('sent_count', '>', 0)->get();
        if ($sent->isEmpty()) return '0.0%';
        $total = $sent->sum(fn($c) => $c->sent_count > 0 ? ($c->opened_count / $c->sent_count) * 100 : 0);
        return round($total / $sent->count(), 1) . '%';
    }

    private function rules($id = null): array
    {
        return [
            'name'          => 'required|string|max:255',
            'subject'       => 'required|string|max:255',
            'preview_text'  => 'nullable|string|max:255',
            'type'          => 'required|in:newsletter,promotional,abandoned_cart,welcome,re_engagement,product_launch',
            'status'        => 'nullable|in:draft,scheduled,sending,sent,paused,cancelled',
            'audience_type' => 'nullable|in:all,new_customers,inactive_customers,specific_segment',
            'template_id'   => 'nullable|string|max:255',
            'content'       => 'nullable|string',
            'from_name'     => 'nullable|string|max:100',
            'from_email'    => 'nullable|email|max:255',
            'reply_to'      => 'nullable|email|max:255',
            'scheduled_at'  => 'nullable|date',
            'tags'          => 'nullable',
        ];
    }
}
