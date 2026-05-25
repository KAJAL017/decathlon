<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function index()
    {
        return view('admin.pages.reviews.index');
    }

    public function list(Request $request)
    {
        $q = ProductReview::with(['product:id,name']);

        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($q) use ($s) {
                $q->where('reviewer_name', 'like', "%$s%")
                  ->orWhere('reviewer_email', 'like', "%$s%")
                  ->orWhere('title', 'like', "%$s%")
                  ->orWhere('body', 'like', "%$s%")
                  ->orWhereHas('product', fn($p) => $p->where('name', 'like', "%$s%"));
            });
        }

        if ($request->filled('status'))    $q->where('status', $request->status);
        if ($request->filled('rating'))    $q->where('rating', $request->rating);
        if ($request->filled('product_id')) $q->where('product_id', $request->product_id);
        if ($request->filled('featured'))  $q->where('featured', (bool)$request->featured);
        if ($request->filled('source'))    $q->where('source', $request->source);

        $perPage = (int) $request->get('per_page', 15);
        $items   = $q->orderByDesc('created_at')->paginate($perPage);

        // Stats — filtered by source
        $source = $request->get('source');
        $statsQ = ProductReview::query();
        if ($source) $statsQ->where('source', $source);

        $stats = [
            'total'      => (clone $statsQ)->count(),
            'pending'    => (clone $statsQ)->where('status', 'pending')->count(),
            'approved'   => (clone $statsQ)->where('status', 'approved')->count(),
            'rejected'   => (clone $statsQ)->where('status', 'rejected')->count(),
            'avg_rating' => round((clone $statsQ)->where('status', 'approved')->avg('rating') ?? 0, 1),
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

        $data = $request->all();
        $data['created_by'] = session('admin_id');
        $data['source']     = $data['source'] ?? 'admin';

        $review = ProductReview::create($data);
        \App\Models\ActivityLog::log('created', 'reviews', "Created review by: {$review->reviewer_name}", ['id' => $review->id]);

        return response()->json(['success' => true, 'message' => 'Review created', 'data' => $review->load('product:id,name')]);
    }

    public function show($id)
    {
        $r = ProductReview::with(['product:id,name', 'creator:id,name', 'replier:id,name'])->find($id);
        if (!$r) return response()->json(['success' => false, 'message' => 'Not found'], 404);
        return response()->json(['success' => true, 'data' => $r]);
    }

    public function update(Request $request, $id)
    {
        $r = ProductReview::find($id);
        if (!$r) return response()->json(['success' => false, 'message' => 'Not found'], 404);

        $v = Validator::make($request->all(), $this->rules($id));
        if ($v->fails()) return response()->json(['success' => false, 'errors' => $v->errors()], 422);

        $r->update($request->all());
        \App\Models\ActivityLog::log('updated', 'reviews', "Updated review #{$r->id}", ['id' => $r->id]);

        return response()->json(['success' => true, 'message' => 'Review updated', 'data' => $r->load('product:id,name')]);
    }

    public function destroy($id)
    {
        $r = ProductReview::find($id);
        if (!$r) return response()->json(['success' => false, 'message' => 'Not found'], 404);
        \App\Models\ActivityLog::log('deleted', 'reviews', "Deleted review #{$r->id}", ['id' => $r->id]);
        $r->delete();
        return response()->json(['success' => true, 'message' => 'Review deleted']);
    }

    public function reply(Request $request, $id)
    {
        $r = ProductReview::find($id);
        if (!$r) return response()->json(['success' => false, 'message' => 'Not found'], 404);

        $v = Validator::make($request->all(), ['reply' => 'required|string|max:2000']);
        if ($v->fails()) return response()->json(['success' => false, 'errors' => $v->errors()], 422);

        $r->update([
            'admin_reply' => $request->reply,
            'replied_at'  => now(),
            'replied_by'  => session('admin_id'),
        ]);
        \App\Models\ActivityLog::log('replied', 'reviews', "Replied to review #{$r->id}", ['id' => $r->id]);

        return response()->json(['success' => true, 'message' => 'Reply saved', 'data' => $r]);
    }

    public function toggleFeatured($id)
    {
        $r = ProductReview::find($id);
        if (!$r) return response()->json(['success' => false, 'message' => 'Not found'], 404);
        $r->featured = !$r->featured;
        $r->save();
        return response()->json(['success' => true, 'message' => 'Featured updated', 'data' => $r]);
    }

    public function toggleStatus($id)
    {
        $r = ProductReview::find($id);
        if (!$r) return response()->json(['success' => false, 'message' => 'Not found'], 404);

        $cycle = ['pending' => 'approved', 'approved' => 'rejected', 'rejected' => 'pending', 'spam' => 'pending'];
        $r->status = $cycle[$r->status] ?? 'pending';
        $r->save();

        return response()->json(['success' => true, 'message' => 'Status updated', 'data' => $r]);
    }

    public function bulkAction(Request $request)
    {
        $v = Validator::make($request->all(), [
            'action' => 'required|in:approve,reject,delete,feature,unfeature,spam',
            'ids'    => 'required|array|min:1',
        ]);
        if ($v->fails()) return response()->json(['success' => false, 'errors' => $v->errors()], 422);

        $count = 0;
        foreach ($request->ids as $id) {
            $r = ProductReview::find($id);
            if (!$r) continue;
            match ($request->action) {
                'approve'   => ($r->status = 'approved')  && $r->save() && $count++,
                'reject'    => ($r->status = 'rejected')  && $r->save() && $count++,
                'spam'      => ($r->status = 'spam')      && $r->save() && $count++,
                'feature'   => ($r->featured = true)      && $r->save() && $count++,
                'unfeature' => ($r->featured = false)     && $r->save() && $count++,
                'delete'    => $r->delete() && $count++,
            };
        }

        \App\Models\ActivityLog::log('bulk_action', 'reviews', "Bulk {$request->action} on {$count} reviews");
        return response()->json(['success' => true, 'message' => "{$count} review(s) affected"]);
    }

    private function rules($id = null): array
    {
        return [
            'product_id'        => 'required|exists:products,id',
            'reviewer_name'     => 'required|string|max:255',
            'reviewer_email'    => 'nullable|email|max:255',
            'rating'            => 'required|integer|min:1|max:5',
            'title'             => 'nullable|string|max:255',
            'body'              => 'required|string',
            'status'            => 'nullable|in:pending,approved,rejected,spam',
            'verified_purchase' => 'nullable|boolean',
            'featured'          => 'nullable|boolean',
            'images'            => 'nullable|array',
            'helpful_count'     => 'nullable|integer|min:0',
            'source'            => 'nullable|string|max:50',
        ];
    }
}
