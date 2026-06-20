<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function index()
    {
        return view('admin.pages.coupons.index');
    }

    public function list(Request $request)
    {
        $q = Coupon::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(fn($q) => $q->where('code','like',"%$s%")->orWhere('name','like',"%$s%"));
        }
        if ($request->filled('type'))   $q->where('discount_type', $request->type);
        if ($request->filled('status')) {
            match($request->status) {
                'active'    => $q->active(),
                'scheduled' => $q->where('is_active',true)->where('starts_at','>',now()),
                'expired'   => $q->where('expires_at','<',now()),
                'exhausted' => $q->whereNotNull('usage_limit')->whereColumn('used_count','>=','usage_limit'),
                'inactive'  => $q->where('is_active',false),
                default     => null,
            };
        }

        $perPage = $request->get('per_page', 15);
        $items   = $q->orderByDesc('created_at')->paginate($perPage);

        // Append computed status
        $items->getCollection()->transform(fn($c) => tap($c, fn($c) => $c->append(['status','discount_label'])));

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

        $data = $request->only([
            'code', 'description', 'discount_type', 'discount_value', 'max_discount_amount',
            'buy_quantity', 'get_quantity', 'get_discount_percent', 'min_order_amount',
            'min_quantity', 'usage_limit', 'usage_per_user', 'applies_to',
            'customer_eligibility', 'starts_at', 'expires_at',
            'combine_with_other_coupons', 'combine_with_promotions', 'is_active',
        ]);
        $data['created_by'] = session('admin_id');
        if (empty($data['code'])) $data['code'] = \App\Models\Coupon::generateCode();

        $coupon = Coupon::create($data);
        \App\Models\ActivityLog::log('created','coupons',"Created coupon: {$coupon->code}",['id'=>$coupon->id]);

        return response()->json(['success'=>true,'message'=>'Coupon created','data'=>$coupon->append(['status','discount_label'])]);
    }

    public function show($id)
    {
        $c = Coupon::find($id);
        if (!$c) return response()->json(['success'=>false,'message'=>'Not found'],404);
        return response()->json(['success'=>true,'data'=>$c->append(['status','discount_label'])]);
    }

    public function update(Request $request, $id)
    {
        $c = Coupon::find($id);
        if (!$c) return response()->json(['success'=>false,'message'=>'Not found'],404);

        $v = Validator::make($request->all(), $this->rules($id));
        if ($v->fails()) return response()->json(['success'=>false,'errors'=>$v->errors()],422);

        $data = $request->only([
            'code', 'description', 'discount_type', 'discount_value', 'max_discount_amount',
            'buy_quantity', 'get_quantity', 'get_discount_percent', 'min_order_amount',
            'min_quantity', 'usage_limit', 'usage_per_user', 'applies_to',
            'customer_eligibility', 'starts_at', 'expires_at',
            'combine_with_other_coupons', 'combine_with_promotions', 'is_active',
        ]);
        $c->update($data);
        \App\Models\ActivityLog::log('updated','coupons',"Updated coupon: {$c->code}",['id'=>$c->id]);

        return response()->json(['success'=>true,'message'=>'Coupon updated','data'=>$c->append(['status','discount_label'])]);
    }

    public function destroy($id)
    {
        $c = Coupon::find($id);
        if (!$c) return response()->json(['success'=>false,'message'=>'Not found'],404);
        \App\Models\ActivityLog::log('deleted','coupons',"Deleted coupon: {$c->code}",['id'=>$c->id]);
        $c->delete();
        return response()->json(['success'=>true,'message'=>'Coupon deleted']);
    }

    public function toggleStatus($id)
    {
        $c = Coupon::find($id);
        if (!$c) return response()->json(['success'=>false,'message'=>'Not found'],404);
        $c->is_active = !$c->is_active;
        $c->save();
        return response()->json(['success'=>true,'message'=>'Status updated','data'=>$c->append(['status'])]);
    }

    public function generateCode()
    {
        return response()->json(['success'=>true,'code'=>Coupon::generateCode()]);
    }

    public function bulkAction(Request $request)
    {
        $v = Validator::make($request->all(),[
            'action'=>'required|in:activate,deactivate,delete',
            'ids'   =>'required|array|min:1',
        ]);
        if ($v->fails()) return response()->json(['success'=>false,'errors'=>$v->errors()],422);

        $count = 0;
        foreach ($request->ids as $id) {
            $c = Coupon::find($id); if (!$c) continue;
            match($request->action) {
                'activate'   => ($c->is_active=true)  && $c->save() && $count++,
                'deactivate' => ($c->is_active=false) && $c->save() && $count++,
                'delete'     => $c->delete() && $count++,
            };
        }
        return response()->json(['success'=>true,'message'=>"$count coupon(s) affected"]);
    }

    private function rules($id = null): array
    {
        return [
            'code'                       => 'nullable|string|max:50|unique:coupons,code'.($id?','.$id:''),
            'name'                       => 'required|string|max:255',
            'description'                => 'nullable|string',
            'discount_type'              => 'required|in:percentage,fixed_amount,free_shipping,buy_x_get_y',
            'discount_value'             => 'nullable|numeric|min:0',
            'max_discount_amount'        => 'nullable|numeric|min:0',
            'buy_quantity'               => 'nullable|integer|min:1',
            'get_quantity'               => 'nullable|integer|min:1',
            'get_discount_percent'       => 'nullable|numeric|min:0|max:100',
            'min_order_amount'           => 'nullable|numeric|min:0',
            'min_quantity'               => 'nullable|integer|min:1',
            'usage_limit'                => 'nullable|integer|min:1',
            'usage_per_user'             => 'nullable|integer|min:1',
            'applies_to'                 => 'nullable|in:all,specific_products,specific_categories,specific_brands',
            'customer_eligibility'       => 'nullable|in:all,new_customers,specific_customers',
            'starts_at'                  => 'nullable|date',
            'expires_at'                 => 'nullable|date|after_or_equal:starts_at',
            'combine_with_other_coupons' => 'boolean',
            'combine_with_promotions'    => 'boolean',
            'is_active'                  => 'boolean',
        ];
    }
}
