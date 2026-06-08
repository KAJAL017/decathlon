<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Category;
use App\Models\Setting;
use App\Models\Brand;

class FrontendComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // Global categories for menus (3-level nesting, matching original logic)
        $headerCategories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->where('show_in_menu', true)
            ->orderBy('sort_order')
            ->with(['children' => function ($q) {
                $q->where('is_active', true)
                  ->where('show_in_menu', true)
                  ->orderBy('sort_order')
                  ->with(['children' => function ($q2) {
                      $q2->where('is_active', true)
                         ->where('show_in_menu', true)
                         ->orderBy('sort_order');
                  }]);
            }])
            ->get();

        // All active categories for sidebar filters etc.
        $allCategories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->with('children.children')
            ->orderBy('sort_order')
            ->get();

        // Global settings
        $globalSettings = Setting::all()->pluck('value', 'key');

        // Featured brands
        $featuredBrands = Brand::active()->ordered()->limit(6)->get();

        // All active brands
        $allBrands = Brand::active()->orderBy('name')->get();

        $view->with([
            'headerCategories' => $headerCategories,
            'globalCategories' => $headerCategories, // Alias for backward compatibility
            'categories' => $allCategories,
            'allCategories' => $allCategories,
            'brands' => $allBrands,
            'allBrands' => $allBrands,
            'globalSettings' => $globalSettings,
            'featuredBrands' => $featuredBrands,
        ]);
    }
}
