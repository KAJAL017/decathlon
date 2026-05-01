<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AttributeGroup;
use App\Models\Attribute;
use App\Models\AttributeValue;

class AttributeModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Attribute Groups
        $groups = [
            [
                'name' => 'General',
                'slug' => 'general',
                'description' => 'Basic product information and general attributes',
                'sort_order' => 1,
                'status' => true,
            ],
            [
                'name' => 'Size & Fit',
                'slug' => 'size-fit',
                'description' => 'Size, dimensions, and fitting information',
                'sort_order' => 2,
                'status' => true,
            ],
            [
                'name' => 'Technical Specs',
                'slug' => 'technical-specs',
                'description' => 'Technical specifications and features',
                'sort_order' => 3,
                'status' => true,
            ],
            [
                'name' => 'Material & Care',
                'slug' => 'material-care',
                'description' => 'Material composition and care instructions',
                'sort_order' => 4,
                'status' => true,
            ],
        ];

        foreach ($groups as $groupData) {
            AttributeGroup::firstOrCreate(
                ['slug' => $groupData['slug']],
                $groupData
            );
        }

        // Get created groups
        $generalGroup = AttributeGroup::where('slug', 'general')->first();
        $sizeFitGroup = AttributeGroup::where('slug', 'size-fit')->first();
        $techSpecsGroup = AttributeGroup::where('slug', 'technical-specs')->first();
        $materialGroup = AttributeGroup::where('slug', 'material-care')->first();

        // Create Attributes
        $attributes = [
            // General Group
            [
                'group_id' => $generalGroup->id,
                'name' => 'Color',
                'slug' => 'color',
                'type' => 'color',
                'display_type' => 'color_swatch',
                'is_variant' => true,
                'is_filterable' => true,
                'is_required' => false,
                'is_searchable' => false,
                'sort_order' => 1,
                'status' => true,
            ],
            [
                'group_id' => $generalGroup->id,
                'name' => 'Brand',
                'slug' => 'brand',
                'type' => 'select',
                'display_type' => 'dropdown',
                'is_variant' => false,
                'is_filterable' => true,
                'is_required' => false,
                'is_searchable' => true,
                'sort_order' => 2,
                'status' => true,
            ],
            [
                'group_id' => $generalGroup->id,
                'name' => 'Gender',
                'slug' => 'gender',
                'type' => 'select',
                'display_type' => 'radio',
                'is_variant' => false,
                'is_filterable' => true,
                'is_required' => false,
                'is_searchable' => false,
                'sort_order' => 3,
                'status' => true,
            ],
            
            // Size & Fit Group
            [
                'group_id' => $sizeFitGroup->id,
                'name' => 'Size',
                'slug' => 'size',
                'type' => 'select',
                'display_type' => 'dropdown',
                'is_variant' => true,
                'is_filterable' => true,
                'is_required' => true,
                'is_searchable' => false,
                'sort_order' => 1,
                'status' => true,
            ],
            [
                'group_id' => $sizeFitGroup->id,
                'name' => 'Weight',
                'slug' => 'weight',
                'type' => 'number',
                'display_type' => 'dropdown',
                'is_variant' => false,
                'is_filterable' => true,
                'is_required' => false,
                'is_searchable' => false,
                'unit' => 'kg',
                'sort_order' => 2,
                'status' => true,
            ],
            
            // Technical Specs Group
            [
                'group_id' => $techSpecsGroup->id,
                'name' => 'Waterproof',
                'slug' => 'waterproof',
                'type' => 'boolean',
                'display_type' => 'checkbox',
                'is_variant' => false,
                'is_filterable' => true,
                'is_required' => false,
                'is_searchable' => false,
                'sort_order' => 1,
                'status' => true,
            ],
            
            // Material & Care Group
            [
                'group_id' => $materialGroup->id,
                'name' => 'Material',
                'slug' => 'material',
                'type' => 'multiselect',
                'display_type' => 'checkbox',
                'is_variant' => false,
                'is_filterable' => true,
                'is_required' => false,
                'is_searchable' => true,
                'sort_order' => 1,
                'status' => true,
            ],
            [
                'group_id' => $materialGroup->id,
                'name' => 'Pattern',
                'slug' => 'pattern',
                'type' => 'select',
                'display_type' => 'dropdown',
                'is_variant' => false,
                'is_filterable' => true,
                'is_required' => false,
                'is_searchable' => false,
                'sort_order' => 2,
                'status' => true,
            ],
        ];

        foreach ($attributes as $attrData) {
            $attribute = Attribute::firstOrCreate(
                ['slug' => $attrData['slug']],
                $attrData
            );

            // Create Attribute Values
            $this->createAttributeValues($attribute);
        }
    }

    private function createAttributeValues($attribute)
    {
        $values = [];

        switch ($attribute->slug) {
            case 'color':
                $values = [
                    ['value' => 'Red', 'slug' => 'red', 'color_code' => '#FF0000', 'sort_order' => 1],
                    ['value' => 'Blue', 'slug' => 'blue', 'color_code' => '#0000FF', 'sort_order' => 2],
                    ['value' => 'Green', 'slug' => 'green', 'color_code' => '#00FF00', 'sort_order' => 3],
                    ['value' => 'Black', 'slug' => 'black', 'color_code' => '#000000', 'sort_order' => 4],
                    ['value' => 'White', 'slug' => 'white', 'color_code' => '#FFFFFF', 'sort_order' => 5],
                    ['value' => 'Yellow', 'slug' => 'yellow', 'color_code' => '#FFFF00', 'sort_order' => 6],
                    ['value' => 'Orange', 'slug' => 'orange', 'color_code' => '#FFA500', 'sort_order' => 7],
                    ['value' => 'Purple', 'slug' => 'purple', 'color_code' => '#800080', 'sort_order' => 8],
                ];
                break;

            case 'brand':
                $values = [
                    ['value' => 'Nike', 'slug' => 'nike', 'sort_order' => 1],
                    ['value' => 'Adidas', 'slug' => 'adidas', 'sort_order' => 2],
                    ['value' => 'Puma', 'slug' => 'puma', 'sort_order' => 3],
                    ['value' => 'Reebok', 'slug' => 'reebok', 'sort_order' => 4],
                    ['value' => 'Under Armour', 'slug' => 'under-armour', 'sort_order' => 5],
                ];
                break;

            case 'gender':
                $values = [
                    ['value' => 'Men', 'slug' => 'men', 'sort_order' => 1],
                    ['value' => 'Women', 'slug' => 'women', 'sort_order' => 2],
                    ['value' => 'Unisex', 'slug' => 'unisex', 'sort_order' => 3],
                    ['value' => 'Kids', 'slug' => 'kids', 'sort_order' => 4],
                ];
                break;

            case 'size':
                $values = [
                    ['value' => 'XS', 'slug' => 'xs', 'sort_order' => 1],
                    ['value' => 'S', 'slug' => 's', 'sort_order' => 2],
                    ['value' => 'M', 'slug' => 'm', 'sort_order' => 3],
                    ['value' => 'L', 'slug' => 'l', 'sort_order' => 4],
                    ['value' => 'XL', 'slug' => 'xl', 'sort_order' => 5],
                    ['value' => 'XXL', 'slug' => 'xxl', 'sort_order' => 6],
                ];
                break;

            case 'waterproof':
                $values = [
                    ['value' => 'Yes', 'slug' => 'yes', 'sort_order' => 1],
                    ['value' => 'No', 'slug' => 'no', 'sort_order' => 2],
                ];
                break;

            case 'material':
                $values = [
                    ['value' => 'Cotton', 'slug' => 'cotton', 'sort_order' => 1],
                    ['value' => 'Polyester', 'slug' => 'polyester', 'sort_order' => 2],
                    ['value' => 'Leather', 'slug' => 'leather', 'sort_order' => 3],
                    ['value' => 'Wool', 'slug' => 'wool', 'sort_order' => 4],
                    ['value' => 'Silk', 'slug' => 'silk', 'sort_order' => 5],
                    ['value' => 'Nylon', 'slug' => 'nylon', 'sort_order' => 6],
                ];
                break;

            case 'pattern':
                $values = [
                    ['value' => 'Solid', 'slug' => 'solid', 'sort_order' => 1],
                    ['value' => 'Striped', 'slug' => 'striped', 'sort_order' => 2],
                    ['value' => 'Checkered', 'slug' => 'checkered', 'sort_order' => 3],
                    ['value' => 'Floral', 'slug' => 'floral', 'sort_order' => 4],
                    ['value' => 'Printed', 'slug' => 'printed', 'sort_order' => 5],
                ];
                break;
        }

        foreach ($values as $valueData) {
            $valueData['attribute_id'] = $attribute->id;
            $valueData['status'] = true;
            
            AttributeValue::firstOrCreate(
                [
                    'attribute_id' => $attribute->id,
                    'slug' => $valueData['slug']
                ],
                $valueData
            );
        }
    }
}
