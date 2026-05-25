<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PromotionController extends Controller
{
    public function index()
    {
        return view('admin.pages.promotions.index');
    }

    public function list(Request $request)
    {
        $q = Promotion::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(fn($q) => $q->where('name','like',"%$s%")->orWhere('slug','like',"%$s%"));
        }
        if ($request->filled('type'))      $q->where('type', $request->type);
        if ($request->filled('status')) {
            match($request->status) {
                'active'    => $q->running(),
                'scheduled' => $q->active()->where('starts_at','>',now()),
                'expired'   => $q->where('ends_at','<',now()),
                'inactive'  => $q->where('is_active',false),
                default     => null,
            };
        }

        $perPage = $request->get('per_page', 15);
        $items   = $q->orderByDesc('priority')->orderByDesc('created_at')->paginate($perPage);

        return response()->json([
            'success'    => true,
            'data'       => $items->items(),
            'pagination' => [
                'total'        => $items->total(),
                'per_page'     => $items->perPage(),
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), $this->rules());
        if ($v->fails()) return response()->json(['success'=>false,'errors'=>$v->errors()],422);

        $data = $request->all();
        $data['created_by'] = session('admin_id');
        if (empty($data['slug'])) unset($data['slug']);

        $promo = Promotion::create($data);
        \App\Models\ActivityLog::log('created','promotions',"Created promotion: {$promo->name}",['id'=>$promo->id]);

        return response()->json(['success'=>true,'message'=>'Promotion created','data'=>$promo]);
    }

    public function show($id)
    {
        $promo = Promotion::find($id);
        if (!$promo) return response()->json(['success'=>false,'message'=>'Not found'],404);
        return response()->json(['success'=>true,'data'=>$promo]);
    }

    public function update(Request $request, $id)
    {
        $promo = Promotion::find($id);
        if (!$promo) return response()->json(['success'=>false,'message'=>'Not found'],404);

        $v = Validator::make($request->all(), $this->rules($id));
        if ($v->fails()) return response()->json(['success'=>false,'errors'=>$v->errors()],422);

        $data = $request->all();
        if (empty($data['slug'])) unset($data['slug']);
        $promo->update($data);

        \App\Models\ActivityLog::log('updated','promotions',"Updated promotion: {$promo->name}",['id'=>$promo->id]);
        return response()->json(['success'=>true,'message'=>'Promotion updated','data'=>$promo]);
    }

    public function destroy($id)
    {
        $promo = Promotion::find($id);
        if (!$promo) return response()->json(['success'=>false,'message'=>'Not found'],404);
        \App\Models\ActivityLog::log('deleted','promotions',"Deleted promotion: {$promo->name}",['id'=>$promo->id]);
        $promo->delete();
        return response()->json(['success'=>true,'message'=>'Promotion deleted']);
    }

    public function toggleStatus($id)
    {
        $promo = Promotion::find($id);
        if (!$promo) return response()->json(['success'=>false,'message'=>'Not found'],404);
        $promo->is_active = !$promo->is_active;
        $promo->save();
        return response()->json(['success'=>true,'message'=>'Status updated','data'=>$promo]);
    }

    public function bulkAction(Request $request)
    {
        $v = Validator::make($request->all(),[
            'action' => 'required|in:activate,deactivate,delete',
            'ids'    => 'required|array|min:1',
        ]);
        if ($v->fails()) return response()->json(['success'=>false,'errors'=>$v->errors()],422);

        $count = 0;
        foreach ($request->ids as $id) {
            $p = Promotion::find($id); if (!$p) continue;
            match($request->action) {
                'activate'   => ($p->is_active = true)  && $p->save() && $count++,
                'deactivate' => ($p->is_active = false)  && $p->save() && $count++,
                'delete'     => $p->delete() && $count++,
            };
        }
        return response()->json(['success'=>true,'message'=>"$count promotion(s) affected"]);
    }

    private function rules($id = null): array
    {
        return [
            'name'               => 'required|string|max:255',
            'slug'               => 'nullable|string|unique:promotions,slug'.($id ? ','.$id : ''),
            'description'        => 'nullable|string',
            'type'               => 'required|in:percentage,fixed_amount,free_shipping,buy_x_get_y,flash_sale,bundle',
            'discount_value'     => 'nullable|numeric|min:0',
            'max_discount_amount'=> 'nullable|numeric|min:0',
            'buy_quantity'       => 'nullable|integer|min:1',
            'get_quantity'       => 'nullable|integer|min:1',
            'get_discount_percent'=> 'nullable|numeric|min:0|max:100',
            'min_order_amount'   => 'nullable|numeric|min:0',
            'min_quantity'       => 'nullable|integer|min:1',
            'usage_limit'        => 'nullable|integer|min:1',
            'usage_per_user'     => 'nullable|integer|min:1',
            'applies_to'         => 'nullable|in:all,specific_products,specific_categories,specific_brands',
            'applicable_ids'     => 'nullable|array',
            'starts_at'          => 'nullable|date',
            'ends_at'            => 'nullable|date|after_or_equal:starts_at',
            'badge_text'         => 'nullable|string|max:50',
            'badge_color'        => 'nullable|string|max:20',
            'priority'           => 'nullable|integer|min:0',
            'is_active'          => 'boolean',
            'show_countdown'     => 'boolean',
            'show_on_homepage'   => 'boolean',
        ];
    }
}
