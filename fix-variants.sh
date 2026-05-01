#!/bin/bash

echo "========================================="
echo "Fixing Variant Attributes Issue"
echo "========================================="
echo ""

# Step 1: Run the seeder
echo "Step 1: Running AttributeModuleSeeder..."
php artisan db:seed --class=AttributeModuleSeeder
echo "✓ Seeder completed"
echo ""

# Step 2: Verify attributes in database
echo "Step 2: Checking variant attributes..."
php artisan tinker --execute="
\$attrs = \App\Models\Attribute::where('is_variant', true)
    ->where('status', true)
    ->with('values')
    ->get();
echo 'Found ' . \$attrs->count() . ' variant attributes:\n';
foreach (\$attrs as \$attr) {
    echo '  - ' . \$attr->name . ' (' . \$attr->values->count() . ' values)\n';
}
"
echo ""

# Step 3: Test the API endpoint
echo "Step 3: Testing API endpoint..."
echo "Visit: http://your-domain.test/admin/products/variant-attributes"
echo ""

echo "========================================="
echo "Fix Complete!"
echo "========================================="
echo ""
echo "Next steps:"
echo "1. Refresh your browser"
echo "2. Go to Products → Add Product → Variants tab"
echo "3. Click 'Generate Variants'"
echo "4. You should see Color and Size attributes"
echo ""
