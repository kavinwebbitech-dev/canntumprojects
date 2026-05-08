# Image Display Logic - Complete Flow

## Overview
This document explains the complete image display logic for product details page with **AUTO-DEFAULT COLOR & SIZE SELECTION**.

---

## New Behavior: Auto-Default Color & Size

When a user visits a product details page:
1. **First color is automatically selected** (no user click needed)
2. **First size for that color is automatically selected**
3. **Variant images for that combination are shown by default**
4. **Gallery images are NOT shown** (unless variant has no images)

---

## Data Flow

### 1. CONTROLLER: `HomeController.php` → `productDetails()`

**Step 1: Fetch all available colors**
```php
$variantColors = ProductDetail::where('product_id',$id)
    ->whereNotNull('color_id')
    ->distinct()
    ->pluck('color_id');

$colors = Color::whereIn('id',$variantColors)->get();
```

**Step 2: Get color from URL OR use FIRST color as default**
```php
// Use URL color param OR default to FIRST color
$colorId = $request->color ?? ($colors->first()->id ?? null);
```

**Step 3: Fetch sizes based on selected color**
```php
$sizes = collect();
if($colorId){
    $variantSizes = ProductDetail::where('product_id',$id)
        ->where('color_id',$colorId)
        ->whereNotNull('size_id')
        ->distinct()
        ->pluck('size_id');

    $sizes = Size::whereIn('id',$variantSizes)->get();
    
    // Auto-select FIRST size if not in URL
    if(!$sizeId && $sizes->count() > 0){
        $sizeId = $sizes->first()->id;
    }
}
```

**Step 4: Load variant with color + size (always loads because both are auto-set)**
```php
$productDetail = null;
if($colorId && $sizeId){
    $productDetail = ProductDetail::where('product_id',$id)
        ->where('color_id',$colorId)
        ->where('size_id',$sizeId)
        ->first();
}
```

---

## Blade Template: Image Display Logic

### Conditions in Order:

#### **Condition 1: Variant has images (Primary display)**
```php
if ($productDetail && $productDetail->images) {
    // Decode variant images
    $imagesToShow = json_decode($productDetail->images, true);
    
    // SHOW: Variant images (DEFAULT on page load)
}
```

#### **Condition 2: Variant has no images (Fallback)**
```
elseif ($galleryImages->count() > 0) {
    // SHOW: Gallery images
}
```

#### **Condition 3: No gallery images (Last fallback)**
```
else {
    // SHOW: Product main image (fallback)
}
```

---

## User Journey & Image Display

### **State 1: Initial Page Load (No URL params)**
```
URL: /product/1

✓ FIRST color AUTO-SELECTED
✓ FIRST size for that color AUTO-SELECTED
✓ Variant images shown (if exist)
✓ Both color & size marked as checked
✓ Add to Cart ENABLED
✓ NO user clicks required
```

### **State 2: User Selects Different Color**
```
URL: /product/1?color=X (size removed)

✓ Selected color marked as checked
✓ NEW FIRST size for new color AUTO-SELECTED
✓ Variant images updated
✓ Page automatically reloads
```

### **State 3: User Selects Different Size**
```
URL: /product/1?color=X&size=Y

✓ Selected color marked as checked
✓ Selected size marked as checked
✓ Variant images updated
✓ Page automatically reloads
```

---

## Database Schema Reference

### `product_details` table
```
id              INTEGER
product_id      INTEGER (FK)
color_id        INTEGER (FK)
size_id         INTEGER (FK)
price           DECIMAL
quantity        INTEGER
images          JSON (array of image filenames)
```

### `uploads` table (Gallery Images - Fallback)
```
id              INTEGER
product_id      INTEGER (FK)
path            STRING (image path)
```

### `colors` table
```
id              INTEGER
color           STRING
```

### `sizes` table
```
id              INTEGER
name            STRING
```

---

## Complete Code Flow

### Routes
```php
GET /product/{id}                    → productDetails() with auto-default color+size
GET /product/{id}?color=1            → productDetails() with selected color, auto-default size
GET /product/{id}?color=1&size=2     → productDetails() with both selected
```

### Controller Logic
```php
public function productDetails($id, Request $request)
{
    $product = Product::where('deleted',0)->findOrFail($id);

    // Get available colors
    $variantColors = ProductDetail::where('product_id',$id)
        ->whereNotNull('color_id')
        ->distinct()
        ->pluck('color_id');

    $colors = Color::whereIn('id',$variantColors)->get();

    // Default to FIRST color if not in URL
    $colorId = $request->color ?? ($colors->first()->id ?? null);
    $sizeId  = $request->size;

    // Get sizes for selected color
    $sizes = collect();
    if($colorId){
        $variantSizes = ProductDetail::where('product_id',$id)
            ->where('color_id',$colorId)
            ->whereNotNull('size_id')
            ->distinct()
            ->pluck('size_id');

        $sizes = Size::whereIn('id',$variantSizes)->get();
        
        // Default to FIRST size if not in URL
        if(!$sizeId && $sizes->count() > 0){
            $sizeId = $sizes->first()->id;
        }
    }

    // Always load variant (because both color & size are always set)
    $productDetail = null;
    if($colorId && $sizeId){
        $productDetail = ProductDetail::where('product_id',$id)
            ->where('color_id',$colorId)
            ->where('size_id',$sizeId)
            ->first();
    }

    // Always get gallery as fallback
    $galleryImages = Upload::where('product_id',$id)->get();

    return view('frontend.product.details',compact(
        'product',
        'galleryImages',
        'productDetail',
        'colors',
        'sizes',
        'colorId',    // ← Now always set
        'sizeId'      // ← Now always set
    ));
}
```

### Blade Template Color Selection
```blade
@foreach ($colors as $color)
    <input type="radio" class="btn-check" name="color" 
        id="color-{{ $color->id }}" 
        value="{{ $color->id }}" 
        @if ($colorId == $color->id) checked @endif>
    <!-- First color will be checked by default -->
@endforeach
```

### Blade Template Size Selection
```blade
@foreach ($sizes as $size)
    <input type="radio" class="btn-check" name="size" 
        id="size-{{ $size->id }}" 
        value="{{ $size->id }}" 
        @if ($sizeId == $size->id) checked @endif>
    <!-- First size will be checked by default -->
@endforeach
```

### Image Display
```blade
@if ($productDetail && $productDetail->images)
    <!-- Show variant images (will show on initial load) -->
@elseif ($galleryImages->count() > 0)
    <!-- Show gallery images (fallback) -->
@else
    <!-- Show product image (last fallback) -->
@endif
```

---

## Key Advantages

1. **Better UX**: Users see images immediately without clicking
2. **Simpler Logic**: No need to check if user selected = gallery or variant
3. **Always Ready**: Add to Cart button always enabled
4. **Clean URL Flow**:
   - `/product/1` → Auto-defaults
   - `/product/1?color=X` → Auto-select first size
   - `/product/1?color=X&size=Y` → Use selections

---

## Testing Checklist

- [ ] Load product page → First color auto-selected
- [ ] First color page load → First size auto-selected
- [ ] Initial load → Variant images shown (if exist)
- [ ] No variant images → Gallery images shown as fallback
- [ ] Click different color → Auto-select first size, reload
- [ ] Click size → URL includes both color+size
- [ ] Add to Cart → Always visible and working
- [ ] URL with color only → Size auto-selected
- [ ] URL with color+size → Loads exact variant
- [ ] Color option → Always marked with first color checked by default

