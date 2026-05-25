<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AttributeGroup;
use App\Models\Attribute;
use App\Models\AttributeValue;

class AttributeModuleSeeder extends Seeder
{
    public function run(): void
    {
        // ─────────────────────────────────────────────────────────────────
        // FLOOR 1 — Appearance Attributes (Color, Pattern, Style)
        // ─────────────────────────────────────────────────────────────────
        $appearance = AttributeGroup::firstOrCreate(
            ['slug' => 'appearance'],
            ['name' => 'Appearance', 'description' => 'Visual attributes like color, pattern and style', 'sort_order' => 1, 'status' => true]
        );

        $this->seedAttribute($appearance->id, [
            'name' => 'Color', 'slug' => 'color', 'type' => 'color',
            'display_type' => 'color_swatch', 'is_variant' => true, 'is_filterable' => true,
            'is_required' => false, 'is_searchable' => false, 'sort_order' => 1,
        ], [
            ['value' => 'Black',       'slug' => 'black',       'color_code' => '#000000', 'sort_order' => 1],
            ['value' => 'White',       'slug' => 'white',       'color_code' => '#FFFFFF', 'sort_order' => 2],
            ['value' => 'Red',         'slug' => 'red',         'color_code' => '#E63946', 'sort_order' => 3],
            ['value' => 'Blue',        'slug' => 'blue',        'color_code' => '#1D3557', 'sort_order' => 4],
            ['value' => 'Navy Blue',   'slug' => 'navy-blue',   'color_code' => '#003049', 'sort_order' => 5],
            ['value' => 'Sky Blue',    'slug' => 'sky-blue',    'color_code' => '#48CAE4', 'sort_order' => 6],
            ['value' => 'Green',       'slug' => 'green',       'color_code' => '#2D6A4F', 'sort_order' => 7],
            ['value' => 'Lime Green',  'slug' => 'lime-green',  'color_code' => '#80B918', 'sort_order' => 8],
            ['value' => 'Yellow',      'slug' => 'yellow',      'color_code' => '#FFD60A', 'sort_order' => 9],
            ['value' => 'Orange',      'slug' => 'orange',      'color_code' => '#FB8500', 'sort_order' => 10],
            ['value' => 'Pink',        'slug' => 'pink',        'color_code' => '#FF6B9D', 'sort_order' => 11],
            ['value' => 'Purple',      'slug' => 'purple',      'color_code' => '#7B2D8B', 'sort_order' => 12],
            ['value' => 'Grey',        'slug' => 'grey',        'color_code' => '#8D99AE', 'sort_order' => 13],
            ['value' => 'Dark Grey',   'slug' => 'dark-grey',   'color_code' => '#4A4E69', 'sort_order' => 14],
            ['value' => 'Brown',       'slug' => 'brown',       'color_code' => '#6D4C41', 'sort_order' => 15],
            ['value' => 'Beige',       'slug' => 'beige',       'color_code' => '#D4A574', 'sort_order' => 16],
            ['value' => 'Multicolor',  'slug' => 'multicolor',  'color_code' => null,      'sort_order' => 17],
        ]);

        $this->seedAttribute($appearance->id, [
            'name' => 'Pattern', 'slug' => 'pattern', 'type' => 'select',
            'display_type' => 'dropdown', 'is_variant' => false, 'is_filterable' => true,
            'is_required' => false, 'is_searchable' => false, 'sort_order' => 2,
        ], [
            ['value' => 'Solid',      'slug' => 'solid',      'sort_order' => 1],
            ['value' => 'Striped',    'slug' => 'striped',    'sort_order' => 2],
            ['value' => 'Checkered',  'slug' => 'checkered',  'sort_order' => 3],
            ['value' => 'Printed',    'slug' => 'printed',    'sort_order' => 4],
            ['value' => 'Camouflage', 'slug' => 'camouflage', 'sort_order' => 5],
            ['value' => 'Gradient',   'slug' => 'gradient',   'sort_order' => 6],
            ['value' => 'Floral',     'slug' => 'floral',     'sort_order' => 7],
            ['value' => 'Abstract',   'slug' => 'abstract',   'sort_order' => 8],
        ]);

        // ─────────────────────────────────────────────────────────────────
        // FLOOR 2 — Size & Fit Attributes
        // ─────────────────────────────────────────────────────────────────
        $sizeFit = AttributeGroup::firstOrCreate(
            ['slug' => 'size-fit'],
            ['name' => 'Size & Fit', 'description' => 'Sizing, dimensions and fit information', 'sort_order' => 2, 'status' => true]
        );

        $this->seedAttribute($sizeFit->id, [
            'name' => 'Clothing Size', 'slug' => 'clothing-size', 'type' => 'select',
            'display_type' => 'dropdown', 'is_variant' => true, 'is_filterable' => true,
            'is_required' => true, 'is_searchable' => false, 'sort_order' => 1,
        ], [
            ['value' => 'XS',   'slug' => 'xs',   'sort_order' => 1],
            ['value' => 'S',    'slug' => 's',    'sort_order' => 2],
            ['value' => 'M',    'slug' => 'm',    'sort_order' => 3],
            ['value' => 'L',    'slug' => 'l',    'sort_order' => 4],
            ['value' => 'XL',   'slug' => 'xl',   'sort_order' => 5],
            ['value' => 'XXL',  'slug' => 'xxl',  'sort_order' => 6],
            ['value' => '3XL',  'slug' => '3xl',  'sort_order' => 7],
            ['value' => '4XL',  'slug' => '4xl',  'sort_order' => 8],
        ]);

        $this->seedAttribute($sizeFit->id, [
            'name' => 'Shoe Size (UK)', 'slug' => 'shoe-size-uk', 'type' => 'select',
            'display_type' => 'dropdown', 'is_variant' => true, 'is_filterable' => true,
            'is_required' => true, 'is_searchable' => false, 'sort_order' => 2,
        ], [
            ['value' => 'UK 4',   'slug' => 'uk-4',   'sort_order' => 1],
            ['value' => 'UK 5',   'slug' => 'uk-5',   'sort_order' => 2],
            ['value' => 'UK 6',   'slug' => 'uk-6',   'sort_order' => 3],
            ['value' => 'UK 7',   'slug' => 'uk-7',   'sort_order' => 4],
            ['value' => 'UK 8',   'slug' => 'uk-8',   'sort_order' => 5],
            ['value' => 'UK 9',   'slug' => 'uk-9',   'sort_order' => 6],
            ['value' => 'UK 10',  'slug' => 'uk-10',  'sort_order' => 7],
            ['value' => 'UK 11',  'slug' => 'uk-11',  'sort_order' => 8],
            ['value' => 'UK 12',  'slug' => 'uk-12',  'sort_order' => 9],
        ]);

        $this->seedAttribute($sizeFit->id, [
            'name' => 'Fit Type', 'slug' => 'fit-type', 'type' => 'select',
            'display_type' => 'dropdown', 'is_variant' => false, 'is_filterable' => true,
            'is_required' => false, 'is_searchable' => false, 'sort_order' => 3,
        ], [
            ['value' => 'Regular Fit',  'slug' => 'regular-fit',  'sort_order' => 1],
            ['value' => 'Slim Fit',     'slug' => 'slim-fit',     'sort_order' => 2],
            ['value' => 'Loose Fit',    'slug' => 'loose-fit',    'sort_order' => 3],
            ['value' => 'Compression',  'slug' => 'compression',  'sort_order' => 4],
        ]);

        $this->seedAttribute($sizeFit->id, [
            'name' => 'Weight', 'slug' => 'weight', 'type' => 'number',
            'display_type' => 'dropdown', 'is_variant' => false, 'is_filterable' => false,
            'is_required' => false, 'is_searchable' => false, 'unit' => 'kg', 'sort_order' => 4,
        ], []);

        // ─────────────────────────────────────────────────────────────────
        // FLOOR 3 — Material & Care Attributes
        // ─────────────────────────────────────────────────────────────────
        $material = AttributeGroup::firstOrCreate(
            ['slug' => 'material-care'],
            ['name' => 'Material & Care', 'description' => 'Fabric composition and washing instructions', 'sort_order' => 3, 'status' => true]
        );

        $this->seedAttribute($material->id, [
            'name' => 'Material', 'slug' => 'material', 'type' => 'multiselect',
            'display_type' => 'checkbox', 'is_variant' => false, 'is_filterable' => true,
            'is_required' => false, 'is_searchable' => true, 'sort_order' => 1,
        ], [
            ['value' => 'Cotton',          'slug' => 'cotton',          'sort_order' => 1],
            ['value' => 'Polyester',       'slug' => 'polyester',       'sort_order' => 2],
            ['value' => 'Nylon',           'slug' => 'nylon',           'sort_order' => 3],
            ['value' => 'Spandex',         'slug' => 'spandex',         'sort_order' => 4],
            ['value' => 'Merino Wool',     'slug' => 'merino-wool',     'sort_order' => 5],
            ['value' => 'Gore-Tex',        'slug' => 'gore-tex',        'sort_order' => 6],
            ['value' => 'Recycled Polyester','slug'=>'recycled-polyester','sort_order'=> 7],
            ['value' => 'Polypropylene',   'slug' => 'polypropylene',   'sort_order' => 8],
            ['value' => 'Fleece',          'slug' => 'fleece',          'sort_order' => 9],
            ['value' => 'Down',            'slug' => 'down',            'sort_order' => 10],
            ['value' => 'Rubber',          'slug' => 'rubber',          'sort_order' => 11],
            ['value' => 'EVA Foam',        'slug' => 'eva-foam',        'sort_order' => 12],
            ['value' => 'Leather',         'slug' => 'leather',         'sort_order' => 13],
            ['value' => 'Mesh',            'slug' => 'mesh',            'sort_order' => 14],
        ]);

        $this->seedAttribute($material->id, [
            'name' => 'Care Instructions', 'slug' => 'care-instructions', 'type' => 'multiselect',
            'display_type' => 'checkbox', 'is_variant' => false, 'is_filterable' => false,
            'is_required' => false, 'is_searchable' => false, 'sort_order' => 2,
        ], [
            ['value' => 'Machine Wash 30°', 'slug' => 'machine-wash-30', 'sort_order' => 1],
            ['value' => 'Hand Wash Only',   'slug' => 'hand-wash-only',  'sort_order' => 2],
            ['value' => 'Do Not Tumble Dry','slug' => 'no-tumble-dry',   'sort_order' => 3],
            ['value' => 'Do Not Iron',      'slug' => 'no-iron',         'sort_order' => 4],
            ['value' => 'Dry Clean Only',   'slug' => 'dry-clean-only',  'sort_order' => 5],
        ]);

        // ─────────────────────────────────────────────────────────────────
        // FLOOR 4 — Technical Specifications
        // ─────────────────────────────────────────────────────────────────
        $techSpecs = AttributeGroup::firstOrCreate(
            ['slug' => 'technical-specs'],
            ['name' => 'Technical Specs', 'description' => 'Performance and technical features', 'sort_order' => 4, 'status' => true]
        );

        $this->seedAttribute($techSpecs->id, [
            'name' => 'Waterproof Rating', 'slug' => 'waterproof-rating', 'type' => 'select',
            'display_type' => 'dropdown', 'is_variant' => false, 'is_filterable' => true,
            'is_required' => false, 'is_searchable' => false, 'sort_order' => 1,
        ], [
            ['value' => 'Not Waterproof',  'slug' => 'not-waterproof',  'sort_order' => 1],
            ['value' => 'Water Resistant', 'slug' => 'water-resistant', 'sort_order' => 2],
            ['value' => 'Waterproof',      'slug' => 'waterproof',      'sort_order' => 3],
            ['value' => '5000mm',          'slug' => '5000mm',          'sort_order' => 4],
            ['value' => '10000mm',         'slug' => '10000mm',         'sort_order' => 5],
            ['value' => '20000mm',         'slug' => '20000mm',         'sort_order' => 6],
        ]);

        $this->seedAttribute($techSpecs->id, [
            'name' => 'Gender', 'slug' => 'gender', 'type' => 'select',
            'display_type' => 'radio', 'is_variant' => false, 'is_filterable' => true,
            'is_required' => false, 'is_searchable' => false, 'sort_order' => 2,
        ], [
            ['value' => 'Men',    'slug' => 'men',    'sort_order' => 1],
            ['value' => 'Women',  'slug' => 'women',  'sort_order' => 2],
            ['value' => 'Unisex', 'slug' => 'unisex', 'sort_order' => 3],
            ['value' => 'Kids',   'slug' => 'kids',   'sort_order' => 4],
            ['value' => 'Boys',   'slug' => 'boys',   'sort_order' => 5],
            ['value' => 'Girls',  'slug' => 'girls',  'sort_order' => 6],
        ]);

        $this->seedAttribute($techSpecs->id, [
            'name' => 'Skill Level', 'slug' => 'skill-level', 'type' => 'select',
            'display_type' => 'radio', 'is_variant' => false, 'is_filterable' => true,
            'is_required' => false, 'is_searchable' => false, 'sort_order' => 3,
        ], [
            ['value' => 'Beginner',      'slug' => 'beginner',      'sort_order' => 1],
            ['value' => 'Intermediate',  'slug' => 'intermediate',  'sort_order' => 2],
            ['value' => 'Advanced',      'slug' => 'advanced',      'sort_order' => 3],
            ['value' => 'Professional',  'slug' => 'professional',  'sort_order' => 4],
        ]);

        $this->seedAttribute($techSpecs->id, [
            'name' => 'Terrain', 'slug' => 'terrain', 'type' => 'multiselect',
            'display_type' => 'checkbox', 'is_variant' => false, 'is_filterable' => true,
            'is_required' => false, 'is_searchable' => false, 'sort_order' => 4,
        ], [
            ['value' => 'Road',       'slug' => 'road',       'sort_order' => 1],
            ['value' => 'Trail',      'slug' => 'trail',      'sort_order' => 2],
            ['value' => 'Track',      'slug' => 'track',      'sort_order' => 3],
            ['value' => 'Indoor',     'slug' => 'indoor',     'sort_order' => 4],
            ['value' => 'Outdoor',    'slug' => 'outdoor',    'sort_order' => 5],
            ['value' => 'Snow',       'slug' => 'snow',       'sort_order' => 6],
            ['value' => 'Water',      'slug' => 'water',      'sort_order' => 7],
            ['value' => 'All Terrain','slug' => 'all-terrain','sort_order' => 8],
        ]);

        $this->seedAttribute($techSpecs->id, [
            'name' => 'UV Protection', 'slug' => 'uv-protection', 'type' => 'select',
            'display_type' => 'dropdown', 'is_variant' => false, 'is_filterable' => true,
            'is_required' => false, 'is_searchable' => false, 'sort_order' => 5,
        ], [
            ['value' => 'None',       'slug' => 'no-uv',    'sort_order' => 1],
            ['value' => 'UPF 15',     'slug' => 'upf-15',   'sort_order' => 2],
            ['value' => 'UPF 30',     'slug' => 'upf-30',   'sort_order' => 3],
            ['value' => 'UPF 50',     'slug' => 'upf-50',   'sort_order' => 4],
            ['value' => 'UPF 50+',    'slug' => 'upf-50-plus','sort_order'=> 5],
        ]);

        // ─────────────────────────────────────────────────────────────────
        // FLOOR 5 — Equipment Specific Attributes
        // ─────────────────────────────────────────────────────────────────
        $equipment = AttributeGroup::firstOrCreate(
            ['slug' => 'equipment'],
            ['name' => 'Equipment', 'description' => 'Attributes specific to sports equipment', 'sort_order' => 5, 'status' => true]
        );

        $this->seedAttribute($equipment->id, [
            'name' => 'Ball Size', 'slug' => 'ball-size', 'type' => 'select',
            'display_type' => 'dropdown', 'is_variant' => true, 'is_filterable' => true,
            'is_required' => false, 'is_searchable' => false, 'sort_order' => 1,
        ], [
            ['value' => 'Size 1', 'slug' => 'size-1', 'sort_order' => 1],
            ['value' => 'Size 2', 'slug' => 'size-2', 'sort_order' => 2],
            ['value' => 'Size 3', 'slug' => 'size-3', 'sort_order' => 3],
            ['value' => 'Size 4', 'slug' => 'size-4', 'sort_order' => 4],
            ['value' => 'Size 5', 'slug' => 'size-5', 'sort_order' => 5],
        ]);

        $this->seedAttribute($equipment->id, [
            'name' => 'Frame Material', 'slug' => 'frame-material', 'type' => 'select',
            'display_type' => 'dropdown', 'is_variant' => false, 'is_filterable' => true,
            'is_required' => false, 'is_searchable' => false, 'sort_order' => 2,
        ], [
            ['value' => 'Aluminum',     'slug' => 'aluminum',     'sort_order' => 1],
            ['value' => 'Carbon Fiber', 'slug' => 'carbon-fiber', 'sort_order' => 2],
            ['value' => 'Steel',        'slug' => 'steel',        'sort_order' => 3],
            ['value' => 'Titanium',     'slug' => 'titanium',     'sort_order' => 4],
            ['value' => 'Composite',    'slug' => 'composite',    'sort_order' => 5],
        ]);

        $this->seedAttribute($equipment->id, [
            'name' => 'Capacity', 'slug' => 'capacity', 'type' => 'select',
            'display_type' => 'dropdown', 'is_variant' => true, 'is_filterable' => true,
            'is_required' => false, 'is_searchable' => false, 'unit' => 'L', 'sort_order' => 3,
        ], [
            ['value' => '5L',  'slug' => '5l',  'sort_order' => 1],
            ['value' => '10L', 'slug' => '10l', 'sort_order' => 2],
            ['value' => '15L', 'slug' => '15l', 'sort_order' => 3],
            ['value' => '20L', 'slug' => '20l', 'sort_order' => 4],
            ['value' => '30L', 'slug' => '30l', 'sort_order' => 5],
            ['value' => '40L', 'slug' => '40l', 'sort_order' => 6],
            ['value' => '50L', 'slug' => '50l', 'sort_order' => 7],
            ['value' => '60L', 'slug' => '60l', 'sort_order' => 8],
            ['value' => '70L', 'slug' => '70l', 'sort_order' => 9],
        ]);

        $this->command->info('✅ Attributes seeded — ' . AttributeGroup::count() . ' groups, ' . Attribute::count() . ' attributes, ' . AttributeValue::count() . ' values across 5 floors.');
    }

    // ─────────────────────────────────────────────────────────────────────
    // Helper: seed one attribute + its values
    // ─────────────────────────────────────────────────────────────────────
    private function seedAttribute(int $groupId, array $attrData, array $values): Attribute
    {
        $attribute = Attribute::firstOrCreate(
            ['slug' => $attrData['slug']],
            array_merge($attrData, ['group_id' => $groupId, 'status' => true])
        );

        foreach ($values as $v) {
            AttributeValue::firstOrCreate(
                ['attribute_id' => $attribute->id, 'slug' => $v['slug']],
                array_merge($v, ['attribute_id' => $attribute->id, 'status' => true])
            );
        }

        return $attribute;
    }
}
