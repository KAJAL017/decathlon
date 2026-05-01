# Duplicate Fields Analysis

## Identified Duplicates

### 1. PRIMARY CATEGORY (CONFIRMED DUPLICATE)
**Location 1 - Basic Info Tab:**
- Field ID: `productCategory`
- Label: "Primary Category"
- Line: ~419

**Location 2 - Organization Tab:**
- Field ID: `productPrimaryCategory`
- Label: "Primary Category"
- Line: ~1050

**ISSUE**: Same field appears twice with different IDs!

---

## Solution Plan

### Option 1: Remove from Basic Info (RECOMMENDED)
**Reason**: Organization tab is specifically for categories, tags, collections, and attributes. It makes more sense to have ALL category-related fields there.

**Changes Needed**:
1. Remove `productCategory` field from Basic Info tab
2. Keep only essential fields in Basic Info: Name, Slug, SKU Prefix, Type, Brand
3. Update any JavaScript that references `productCategory` to use `productPrimaryCategory`

### Option 2: Remove from Organization Tab
**Reason**: Keep it simple in Basic Info for quick access.

**Changes Needed**:
1. Remove `productPrimaryCategory` from Organization tab
2. Keep `productCategory` in Basic Info
3. Rename Organization tab's "Categories" section to only show "Additional Categories"

---

## Recommended Approach: Option 1

### Why?
1. **Better Organization**: All category-related fields in one place
2. **Cleaner Basic Info**: Only truly essential fields
3. **Shopify-like**: Shopify also has categories in Organization section
4. **Less Confusion**: Users know where to find category settings

### Fields Distribution After Fix:

**Basic Info Tab (Essential Only):**
- Product Name *
- Slug (auto-generated)
- SKU Prefix
- Product Type *
- Brand
- Short Description
- Description

**Organization Tab (All Organization Fields):**
- Categories Section:
  - Primary Category
  - Additional Categories
- Tags Section
- Collections Section
- Attributes Section

---

## Other Potential Duplicates to Check

Need to verify if these are duplicated:
- [ ] Brand field
- [ ] Status field
- [ ] Availability field
- [ ] SKU field
- [ ] Any pricing fields

## Next Steps

1. Remove `productCategory` from Basic Info tab
2. Update JavaScript references
3. Test form submission
4. Verify no other duplicates exist
