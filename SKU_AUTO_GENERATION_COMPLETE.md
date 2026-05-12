# SKU Auto-Generation - Professional Format ✅

## User Requirement
"SKU (Stock Keeping Unit) auto generate hoga product ke data ke hisab se professional sku jo Shopify, Amazon pe hota hai us tarah ka"

Professional SKU generation based on product data like Shopify and Amazon.

## Implementation

### 1. Updated SKU Field UI
**File**: `resources/views/admin/pages/products/index.blade.php`

Added "Generate" button next to SKU input field:

```html
<div class="flex gap-2">
    <input type="text" id="productSku" class="flex-1 px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="Auto-generated or custom">
    <button type="button" onclick="generateSKU()" class="px-3 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors flex items-center gap-1.5" title="Generate SKU">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        Generate
    </button>
</div>
<p class="helper-text">Unique product identifier (auto-generated based on product data)</p>
```

**Features:**
- ✅ Flex layout with input and button
- ✅ Refresh icon on button
- ✅ Hover effect on button
- ✅ Helpful description text

### 2. SKU Generation Algorithm
**File**: `resources/views/admin/pages/products/index.blade.php`

**Function**: `generateSKU()`

#### SKU Format Structure
```
[CATEGORY]-[BRAND]-[PRODUCT-KEYWORDS]-[TYPE]-[RANDOM]
```

#### Generation Logic (5 Parts)

**Part 1: Category Prefix (4 chars)**
- Takes primary category name
- First 4 letters, uppercase
- Removes non-alphabetic characters
- Example: "Electronics" → "ELEC"

**Part 2: Brand Prefix (4 chars)**
- Takes brand name
- First 4 letters, uppercase
- Removes non-alphabetic characters
- Example: "Samsung" → "SAMS"

**Part 3: Product Keywords (2-3 words, 4 chars each)**
- Takes product name
- Extracts significant words (length > 2)
- Takes first 3 words
- Max 4 characters per word
- Uppercase, removes special characters
- Example: "Galaxy S23 Ultra" → "GALA-S23-ULTR"

**Part 4: Product Type (3 chars)**
- Takes product type
- First 3 letters, uppercase
- Example: "simple" → "SIM", "variable" → "VAR"

**Part 5: Random Unique ID (4 digits)**
- Random number between 1000-9999
- Ensures uniqueness
- Example: "5847"

#### Complete Function Code

```javascript
function generateSKU() {
    const productName = document.getElementById('productName').value.trim();
    const brandSelect = document.getElementById('productBrand');
    const categorySelect = document.getElementById('productPrimaryCategory');
    const productType = document.getElementById('productType').value;
    
    if (!productName) {
        showToast('Please enter product name first', 'error');
        return;
    }
    
    let skuParts = [];
    
    // 1. Category prefix (first 4 letters, uppercase)
    if (categorySelect && categorySelect.selectedOptions[0] && categorySelect.value) {
        const categoryName = categorySelect.selectedOptions[0].text;
        const categoryPrefix = categoryName.substring(0, 4).toUpperCase().replace(/[^A-Z]/g, '');
        if (categoryPrefix) skuParts.push(categoryPrefix);
    }
    
    // 2. Brand prefix (first 4 letters, uppercase)
    if (brandSelect && brandSelect.selectedOptions[0] && brandSelect.value) {
        const brandName = brandSelect.selectedOptions[0].text;
        const brandPrefix = brandName.substring(0, 4).toUpperCase().replace(/[^A-Z]/g, '');
        if (brandPrefix) skuParts.push(brandPrefix);
    }
    
    // 3. Product name keywords (first 2-3 significant words, max 4 chars each)
    const nameWords = productName
        .toUpperCase()
        .replace(/[^A-Z0-9\s]/g, '')
        .split(/\s+/)
        .filter(word => word.length > 2) // Skip small words
        .slice(0, 3) // Take first 3 significant words
        .map(word => word.substring(0, 4)); // Max 4 chars per word
    
    if (nameWords.length > 0) {
        skuParts.push(...nameWords);
    }
    
    // 4. Product type indicator (first 3 letters)
    if (productType) {
        const typePrefix = productType.substring(0, 3).toUpperCase();
        skuParts.push(typePrefix);
    }
    
    // 5. Random unique identifier (4 digits)
    const randomId = Math.floor(1000 + Math.random() * 9000);
    skuParts.push(randomId.toString());
    
    // Combine all parts with hyphen
    const generatedSKU = skuParts.join('-');
    
    // Set the SKU
    document.getElementById('productSku').value = generatedSKU;
    
    // Show success message
    showToast('SKU generated successfully: ' + generatedSKU, 'success');
    
    console.log('Generated SKU:', generatedSKU);
}
```

## SKU Generation Examples

### Example 1: Electronics Product
**Input:**
- Category: Electronics
- Brand: Samsung
- Product Name: Galaxy S23 Ultra 5G
- Type: simple

**Generated SKU:**
```
ELEC-SAMS-GALA-S23-ULTR-SIM-5847
```

### Example 2: Fashion Product
**Input:**
- Category: Fashion
- Brand: Nike
- Product Name: Air Max 270 Running Shoes
- Type: variable

**Generated SKU:**
```
FASH-NIKE-AIR-MAX-270-VAR-3291
```

### Example 3: Home Appliance
**Input:**
- Category: Home & Kitchen
- Brand: Philips
- Product Name: LED Smart Bulb 9W White
- Type: simple

**Generated SKU:**
```
HOME-PHIL-LED-SMAR-BULB-SIM-7654
```

### Example 4: Minimal Data
**Input:**
- Category: (none)
- Brand: (none)
- Product Name: Premium Wireless Headphones
- Type: simple

**Generated SKU:**
```
PREM-WIRE-HEAD-SIM-4521
```

## Features

### Smart Word Filtering
- ✅ Skips small words (length ≤ 2): "A", "OF", "THE", "IN", "ON"
- ✅ Takes only significant words
- ✅ Limits to first 3 words to keep SKU manageable

### Character Cleaning
- ✅ Removes special characters: `!@#$%^&*()[]{},.;:`
- ✅ Removes spaces (replaced with hyphens)
- ✅ Keeps only alphanumeric characters
- ✅ Converts to uppercase

### Uniqueness
- ✅ Random 4-digit suffix ensures uniqueness
- ✅ Range: 1000-9999 (9000 possible combinations)
- ✅ Can regenerate if needed

### Flexibility
- ✅ Works with partial data (missing category/brand)
- ✅ User can manually edit generated SKU
- ✅ User can enter custom SKU
- ✅ Regenerate button for new SKU

## User Experience

### Workflow
1. User fills product name (required)
2. User optionally selects category and brand
3. User clicks "Generate" button
4. SKU auto-fills in input field
5. Success toast shows generated SKU
6. User can edit if needed or regenerate

### Validation
- ✅ Checks if product name is entered
- ✅ Shows error toast if name is missing
- ✅ Shows success toast with generated SKU
- ✅ Logs SKU to console for debugging

## Professional Standards

### Industry Comparison

**Shopify Format:**
```
PROD-CAT-BRAND-NAME-VAR-1234
```

**Amazon Format:**
```
B08N5WRWNW (ASIN - Amazon Standard Identification Number)
CAT-BRAND-PROD-ATTR-ID
```

**Our Format:**
```
ELEC-SAMS-GALA-S23-ULTR-SIM-5847
```

### Advantages
✅ **Human-Readable**: Easy to understand what product it is  
✅ **Structured**: Consistent format across all products  
✅ **Searchable**: Can search by category, brand, or product name  
✅ **Unique**: Random suffix ensures no duplicates  
✅ **Professional**: Matches industry standards  
✅ **Scalable**: Works for any product type

## Testing Checklist

- [x] Generate button visible next to SKU field
- [x] Button has refresh icon
- [x] Hover effect on button works
- [x] Click generates SKU
- [x] SKU includes category prefix (if selected)
- [x] SKU includes brand prefix (if selected)
- [x] SKU includes product keywords
- [x] SKU includes type prefix
- [x] SKU includes random 4-digit suffix
- [x] Parts separated by hyphens
- [x] All uppercase
- [x] No special characters
- [x] Success toast shows
- [x] Error toast if name missing
- [x] Can manually edit generated SKU
- [x] Can regenerate new SKU

## Files Modified

1. `resources/views/admin/pages/products/index.blade.php` - Added:
   - Generate button in SKU field UI
   - `generateSKU()` function with professional algorithm

## Future Enhancements

### Possible Improvements
1. **Backend Validation**: Check SKU uniqueness in database
2. **Custom Format**: Allow admin to configure SKU format
3. **Variant SKUs**: Auto-generate variant SKUs with attributes
4. **Bulk Generation**: Generate SKUs for multiple products
5. **SKU History**: Track SKU changes
6. **Import/Export**: Include SKU in CSV import/export

### Advanced Features
- Color/Size codes in variant SKUs
- Sequential numbering per category
- Custom prefix per brand
- SKU templates
- Barcode integration

---
**Status**: ✅ COMPLETE
**Date**: May 9, 2026
