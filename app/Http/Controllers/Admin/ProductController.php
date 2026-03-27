<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Upload;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\Coupon;
use App\Models\Color;
use App\Models\Size;
use App\Models\Gallery;
use App\Models\GalleryImage;
use Illuminate\Support\Facades\Response;
use Dompdf\Dompdf;
use Mail;
use PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{

    //category

    public function productCategory(Request $request)
    {
        $category = ProductCategory::latest()->get();
        return view('admin.product.category.index', compact('category'));
    }

    public function productCategoryAdd(Request $request)
    {
        return view('admin.product.category.add');
    }

    public function productCategoryStore(Request $request)
    {
        $category                 = new ProductCategory();
        $category->name           = $request->name;
        $category->category_image = $request->filetext2;
        $category->status         = $request->status;
        $category->save();
        if ($category) {
            return redirect()->route('admin.product.category')->with('success', 'Product category has been added successfully');
        }
    }

    public function productCategoryEdit($id)
    {
        $category = ProductCategory::findOrFail($id);
        return view('admin.product.category.edit', compact('category'));
    }


    public function productCategoryUpdate(Request $request)
    {
        $category                 = ProductCategory::where('id', $request->product_id)->first();
        $category->name           = $request->name;

        if ($request->filetext2 != '' && $request->filetext2 != null) {
            $category->category_image = $request->filetext2;
        }
        $category->status         = $request->status;
        $category->save();
        if ($category) {
            return redirect()->route('admin.product.category')->with('success', 'Product category has been updated successfully');
        }
    }

    public function productCategoryImgDelete($id, $name)
    {
        $category_image = ProductCategory::where('id', $id)->where('category_image', $name)->first();
        if ($category_image) {
            if ($category_image) {
                $category_image->category_image = null;
                $category_image->save();
            }
        }

        $imagePath = public_path('product_images/category_images/' . $name);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        return redirect()->back();
    }


    //sub-category

    public function productSubCategory(Request $request)
    {
        $category = ProductSubCategory::latest()->get();
        return view('admin.product.subcategory.index', compact('category'));
    }

    public function productSubCategoryAdd(Request $request)
    {
        return view('admin.product.subcategory.add');
    }

    public function productSubCategoryStore(Request $request)
    {
        $subcategory                 = new ProductSubCategory();
        $subcategory->name           = $request->name;
        $subcategory->category_id    = $request->category_id;
        $subcategory->subcategory_image = $request->filetext2;
        $subcategory->status         = $request->status;
        $subcategory->save();
        if ($subcategory) {
            return redirect()->route('admin.product.subcategory')->with('success', 'Product subcategory has been added successfully');
        }
    }

    public function productSubCategoryEdit($id)
    {
        $subcategory = ProductSubCategory::findOrFail($id);
        return view('admin.product.subcategory.edit', compact('subcategory'));
    }

    public function productSubCategoryImgDelete($id, $name)
    {
        $subcategory_images = ProductSubCategory::where('id', $id)->where('subcategory_image', $name)->first();
        if ($subcategory_images) {
            if ($subcategory_images) {
                $subcategory_images->subcategory_image = null;
                $subcategory_images->save();
            }
        }


        $imagePath = public_path('product_images/subcategory_images/' . $name);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        return redirect()->back();
    }

    public function productSubCategoryUpdate(Request $request)
    {
        $category                 = ProductSubCategory::where('id', $request->subcategory_id)->first();
        $category->category_id    = $request->category_id;
        $category->name           = $request->name;


        if ($request->filetext2 != '' && $request->filetext2 != null) {
            $category->subcategory_image = $request->filetext2;
        }
        $category->status         = $request->status;
        $category->save();
        if ($category) {
            return redirect()->route('admin.product.subcategory')->with('success', 'Product subcategory has been updated successfully');
        }
    }



    //product

    public function product(Request $request)
    {
        $product = Product::where('deleted', 0)->latest()->get();
        return view('admin.product.index', compact('product'));
    }

    public function productAdd(Request $request)
    {
        $product = Product::where('deleted', 0)->latest()->get();
        return view('admin.product.add', compact('product'));
    }

    public function getSubcategories($id)
    {
        $subcategories = ProductSubCategory::where("category_id", $id)->pluck("name", "id");
        return response()->json($subcategories);
    }


    public function sizeCondition(Request $request)
    {
        $data['condition_types'] = Size::get();
        return response()->json($data);
    }


    public function colorCondition(Request $request)
    {
        $data['color_condition'] = Color::get();
        return response()->json($data);
    }


    // public function productStore(Request $request)
    // {
    //     $request->validate([
    //         'category_id'    => 'required|integer|exists:product_categories,id',
    //         'product_name'   => 'required|string|max:255',
    //         'actual_price'   => 'required|numeric|min:0',
    //         'offer_price'    => 'required|numeric|min:0|lte:actual_price',
    //         'discount'       => 'nullable|numeric|min:0|max:100',
    //         'gst'            => 'nullable|numeric|min:0|max:100',
    //         'description'    => 'nullable|string',

    //         'file1'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
    //         'gallery_images' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // ✅ FIXED: Separate validation
    //     ]);

    //     try {
    //         date_default_timezone_set('Asia/Kolkata');

    //         // ========================
    //         // 1. CREATE PRODUCT
    //         // ========================
    //         $product = new Product();
    //         $product->category_id = $request->category_id;
    //         $product->subcategory = $request->subcategory;
    //         $product->product_name = $request->product_name;
    //         $product->orginal_rate = $request->actual_price;
    //         $product->offer_price  = $request->offer_price;
    //         $product->description  = $request->description;
    //         $product->discount     = $request->discount;
    //         $product->gst          = $request->gst;

    //         // ✅ No main quantity (variant-based)
    //         $product->quantity = 0;

    //         $product->best_sellers    = $request->has('best_sellers') ? 1 : 0;
    //         $product->new_arrival     = $request->has('new_arrival') ? 1 : 0;
    //         $product->trending_tshirt = $request->has('trending_tshirt') ? 1 : 0;
    //         $product->trending_this_week = $request->has('trending_this_week') ? 1 : 0;

    //         // ========================
    //         // 2. MAIN IMAGE
    //         // ========================
    //         if ($request->hasFile('file1')) {
    //             $file = $request->file('file1');
    //             $fileName = date('YmdHis') . '_' . uniqid() . '.' . $file->extension();
    //             $file->move(public_path('product_images'), $fileName);
    //             $product->product_img = $fileName;
    //         }

    //         $product->save();

    //         // ========================
    //         // 3. PRODUCT GALLERY (OPTIONAL)
    //         // ========================
    //         if ($request->hasFile('file2')) {
    //             foreach ($request->file('file2') as $image) {
    //                 $imageName = time() . '_' . uniqid() . '.' . $image->extension();
    //                 $image->move(public_path('product_images'), $imageName);

    //                 Upload::create([
    //                     'name' => $imageName,
    //                     'path' => 'product_images/' . $imageName,
    //                     'product_id' => $product->id
    //                 ]);
    //             }
    //         }


    //         $gallery_file_name = null;
    //         if ($request->hasFile('gallery_images')) {
    //             $gFile = $request->file('gallery_images');
    //             $gallery_file_name = date('YmdHis') . '_' . uniqid() . '.' . $gFile->extension();
    //             $gFile->move(public_path('gallery_images'), $gallery_file_name);
    //         }
    //         // ========================
    //         // 4. VARIANTS SAVE 🔥
    //         // ========================
    //         foreach ($request->variants as $index => $variant) {

    //             $variant_images = [];


    //             // ===== VARIANT IMAGES =====
    //             if ($request->hasFile("variants.{$index}.images")) {
    //                 foreach ($request->file("variants.{$index}.images") as $img) {
    //                     $imgName = date('YmdHis') . '_' . uniqid() . '.' . $img->extension();
    //                     $img->move(public_path('variant_images'), $imgName);
    //                     $variant_images[] = $imgName;
    //                 }
    //             }

    //             // ===== SAVE VARIANT =====
    //             $productDetail = new ProductDetail();
    //             $productDetail->product_id = $product->id;
    //             $productDetail->color_id   = $variant['color_id'];
    //             $productDetail->size_id    = $variant['size_id'];
    //             $productDetail->price      = $product->orginal_rate;

    //             // ✅ FIXED: correct quantity
    //             $productDetail->quantity   = $variant['quantity'];

    //             $productDetail->images         = json_encode($variant_images);
    //             $productDetail->gallery_images = $gallery_file_name;

    //             $productDetail->save();
    //         }

    //         return redirect()->route('admin.product')
    //             ->with('success', 'Product added successfully ✅');
    //     } catch (\Exception $e) {
    //         return back()->withInput()->withErrors([
    //             'error' => 'Error: ' . $e->getMessage()
    //         ]);
    //     }
    // }
   

public function productStore(Request $request)
{
    // ========================
    // ✅ VALIDATION WITH FIELD NAMES
    // ========================
    $request->validate([
        'category_id'    => 'required|integer|exists:product_categories,id',
        'product_name'   => 'required|string|max:255',
        'actual_price'   => 'required|numeric|min:0',
        'offer_price'    => 'required|numeric|min:0|lte:actual_price',
        'discount'       => 'nullable|numeric|min:0|max:100',
        'gst'            => 'nullable|numeric|min:0|max:100',
        'description'    => 'nullable|string',

        // ✅ VARIANTS REQUIRED
        // 'variants' => 'required|array|min:1',
        // 'variants.*.color_id' => 'required|exists:colors,id',
        // 'variants.*.size_id'  => 'required|exists:sizes,id',
        'variants.*.quantity' => 'required|integer|min:0',

        'file1' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        'gallery_images' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
    ], [
        // ✅ CUSTOM ERROR MESSAGES
        'category_id.required' => 'Category is required',
        'product_name.required' => 'Product name is required',
        'actual_price.required' => 'Actual price is required',
        'offer_price.required' => 'Offer price is required',

        'variants.required' => 'At least one variant is required',

        // 'variants.*.color_id.required' => 'Color is required',
        // 'variants.*.size_id.required'  => 'Size is required',
        'variants.*.quantity.required' => 'Quantity is required',

        'variants.*.quantity.integer' => 'Quantity must be number',
    ]);

    try {

        DB::beginTransaction(); // 🔥 TRANSACTION START

        date_default_timezone_set('Asia/Kolkata');

        // ========================
        // 1. CREATE PRODUCT
        // ========================
        $product = new Product();
        $product->category_id = $request->category_id;
        $product->subcategory = $request->subcategory;
        $product->product_name = $request->product_name;
        $product->orginal_rate = $request->actual_price;
        $product->offer_price  = $request->offer_price;
        $product->description  = $request->description;
        $product->discount     = $request->discount;
        $product->gst          = $request->gst;
        $product->quantity     = 0;

        $product->best_sellers    = $request->has('best_sellers') ? 1 : 0;
        $product->new_arrival     = $request->has('new_arrival') ? 1 : 0;
        $product->trending_tshirt = $request->has('trending_tshirt') ? 1 : 0;
        $product->trending_this_week = $request->has('trending_this_week') ? 1 : 0;

        // ========================
        // 2. MAIN IMAGE
        // ========================
        if ($request->hasFile('file1')) {
            $file = $request->file('file1');
            $fileName = date('YmdHis') . '_' . uniqid() . '.' . $file->extension();
            $file->move(public_path('product_images'), $fileName);
            $product->product_img = $fileName;
        }

        $product->save();

        // ========================
        // 3. PRODUCT GALLERY
        // ========================
        if ($request->hasFile('file2')) {
            foreach ($request->file('file2') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->extension();
                $image->move(public_path('product_images'), $imageName);

                Upload::create([
                    'name' => $imageName,
                    'path' => 'product_images/' . $imageName,
                    'product_id' => $product->id
                ]);
            }
        }

        $gallery_file_name = null;
        if ($request->hasFile('gallery_images')) {
            $gFile = $request->file('gallery_images');
            $gallery_file_name = date('YmdHis') . '_' . uniqid() . '.' . $gFile->extension();
            $gFile->move(public_path('gallery_images'), $gallery_file_name);
        }

        // ========================
        // 4. VARIANTS SAVE 🔥
        // ========================
        foreach ($request->variants as $index => $variant) {

            // ✅ EXTRA SAFETY (WILL TRIGGER ROLLBACK)
            if (
                // empty($variant['color_id']) ||
                // empty($variant['size_id']) ||
                !isset($variant['quantity'])
            ) {
                throw new \Exception("Variant data missing at row " . ($index + 1));
            }

            $variant_images = [];

            // ===== VARIANT IMAGES =====
            if ($request->hasFile("variants.{$index}.images")) {
                foreach ($request->file("variants.{$index}.images") as $img) {
                    $imgName = date('YmdHis') . '_' . uniqid() . '.' . $img->extension();
                    $img->move(public_path('variant_images'), $imgName);
                    $variant_images[] = $imgName;
                }
            }

            // ===== SAVE VARIANT =====
            $productDetail = new ProductDetail();
            $productDetail->product_id = $product->id;
            $productDetail->color_id   = $variant['color_id'];
            $productDetail->size_id    = $variant['size_id'];
            $productDetail->price      = $product->orginal_rate;
            $productDetail->quantity   = $variant['quantity'];
            $productDetail->images         = json_encode($variant_images);
            $productDetail->gallery_images = $gallery_file_name;

            $productDetail->save();
        }

        DB::commit(); // ✅ SUCCESS

        return redirect()->route('admin.product')
            ->with('success', 'Product added successfully ✅');

    } catch (\Exception $e) {

        DB::rollBack(); // ❌ ROLLBACK EVERYTHING

        return back()->withInput()->withErrors([
            'error' => $e->getMessage()
        ]);
    }
}

    public function adminProductUpdate(Request $request)
    {
        $request->validate([
            'product_id'     => 'required|integer|exists:products,id',
            'category_id'    => 'required|integer|exists:product_categories,id',
            'product_name'   => 'required|string|max:255',
            'actual_price'   => 'required|numeric|min:0',
            'offer_price'    => 'required|numeric|min:0|lte:actual_price',
            'variants'       => 'required|array|min:1',
            'file1'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'gallery_images' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        try {
            date_default_timezone_set('Asia/Kolkata');
            $product = Product::findOrFail($request->product_id);

            $product->update([
                'category_id'     => $request->category_id,
                'subcategory'     => $request->subcategory,
                'product_name'    => $request->product_name,
                'orginal_rate'    => $request->actual_price,
                'offer_price'     => $request->offer_price,
                'discount'        => $request->discount,
                'gst'             => $request->gst,
                'description'     => $request->description,
                'best_sellers'    => $request->has('best_sellers') ? 1 : 0,
                'new_arrival'     => $request->has('new_arrival') ? 1 : 0,
                'trending_tshirt' => $request->has('trending_tshirt') ? 1 : 0,
            ]);

            // 2. Handle Main Thumbnail (file1)
            if ($request->hasFile('file1')) {
                if ($product->product_img && file_exists(public_path('product_images/' . $product->product_img))) {
                    @unlink(public_path('product_images/' . $product->product_img));
                }
                $file = $request->file('file1');
                $fileName = date('YmdHis') . '_' . uniqid() . '.' . $file->extension();
                $file->move(public_path('product_images'), $fileName);
                $product->product_img = $fileName;
                $product->save();
            }

            // 3. Handle Global Gallery Image
            // Fetch the existing gallery image from the first available detail row
            $existingGallery = ProductDetail::where('product_id', $product->id)
                ->whereNotNull('gallery_images')
                ->value('gallery_images');

            // IF REMOVE BUTTON CLICKED: Delete physical file and reset variable
            if ($request->remove_global_gallery == 1) {
                if ($existingGallery && file_exists(public_path('gallery_images/' . $existingGallery))) {
                    @unlink(public_path('gallery_images/' . $existingGallery));
                }
                $existingGallery = null;
            }

            // IF NEW FILE UPLOADED: Delete old one and save new one
            if ($request->hasFile('gallery_images')) {
                if ($existingGallery && file_exists(public_path('gallery_images/' . $existingGallery))) {
                    @unlink(public_path('gallery_images/' . $existingGallery));
                }
                $gFile = $request->file('gallery_images');
                $existingGallery = date('YmdHis') . '_' . uniqid() . '.' . $gFile->extension();
                $gFile->move(public_path('gallery_images'), $existingGallery);
            }

            // 4. Update Variants
            $oldVariants = ProductDetail::where('product_id', $product->id)->get()->keyBy(fn($v) => $v->color_id . '-' . $v->size_id);

            // Delete old rows to re-insert fresh data
            ProductDetail::where('product_id', $product->id)->delete();

            foreach ($request->variants as $index => $variant) {
                $key = $variant['color_id'] . '-' . $variant['size_id'];
                $final_variant_images = [];

                // Handle existing variant thumbnails
                if (isset($oldVariants[$key])) {
                    $oldImgs = json_decode($oldVariants[$key]->images, true) ?? [];
                    $removedImgs = $request->input("variants.{$index}.removed_images", []);
                    foreach ($oldImgs as $img) {
                        if (!in_array($img, (array)$removedImgs)) {
                            $final_variant_images[] = $img;
                        } 
                    }
                }

                if ($request->hasFile("variants.{$index}.images")) {
                    foreach ($request->file("variants.{$index}.images") as $img) {
                        $imgName = date('YmdHis') . '_' . uniqid() . '.' . $img->extension();
                        $img->move(public_path('variant_images'), $imgName);
                        $final_variant_images[] = $imgName;
                    }
                }

                // Create new record
                $detail = new ProductDetail();
                $detail->product_id     = $product->id;
                $detail->color_id       = $variant['color_id'];
                $detail->size_id        = $variant['size_id'];
                $detail->price          = $product->orginal_rate;
                $detail->quantity       = $variant['quantity'];
                $detail->images         = json_encode(array_slice($final_variant_images, 0, 4));
                $detail->gallery_images = $existingGallery; // Shared global image
                $detail->save();
            }

            return redirect()->route('admin.product')->with('success', 'Product updated successfully ✅');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function productEdit($id)
    {
        $product = Product::findOrFail($id);

        $categories = ProductCategory::get();
        $subcategories = ProductSubCategory::where('category_id', $product->category_id)->get();

        // Load variants with relations
        $productVariants = ProductDetail::with(['colordetails', 'sizedetails'])
            ->where('product_id', $product->id)
            ->get();

        return view('admin.product.edit', compact(
            'product',
            'categories',
            'subcategories',
            'productVariants'
        ));
    }



    public function deleteImage(Request $request)
    {
        // print_r($request->all()); exit();
        $upload = Upload::find($request->id);

        if ($upload) {
            // Construct the full image path
            $imagePath = public_path('product_images/' . $upload->name);

            // Check if the file exists before attempting to delete it
            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete the image file from the server
            }

            // Delete the image record from the database
            $upload->delete();

            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => 'Image not found.'], 404);
        }
    }




    public function productDelete($id)
    {
        $product = Product::findOrFail($id);
        $product->deleted = 1;
        $product->save();
        return redirect()->back()->with('success', 'Product deleted successfully');
    }
    //coupon code
    public function coupon_code(Request $request)
    {
        $coupon_code = Coupon::latest()->get();
        return view('admin.product.coupon_code.index', compact('coupon_code'));
    }

    public function coupon_code_add(Request $request)
    {
        return view('admin.product.coupon_code.add');
    }

    public function coupon_code_store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons,code',
            'percentage' => 'required|numeric',
            'status' => 'required|boolean',
            'user_limit' => 'nullable|integer|min:1',
            'expiry_date' => 'nullable|date',
        ]);

        $coupon_code = new Coupon();
        $coupon_code->code         = $request->code;
        $coupon_code->percentage   = $request->percentage;
        $coupon_code->status       = $request->status;
        $coupon_code->user_limit   = $request->user_limit ?? 1;
        $coupon_code->expiry_date  = $request->expiry_date;
        $coupon_code->save();

        return redirect()->route('admin.coupon_code')->with('success', 'Coupon code has been added successfully');
    }


    public function coupon_code_edit($id)
    {
        $coupon_code = Coupon::findOrFail($id);
        return view('admin.product.coupon_code.edit', compact('coupon_code'));
    }


    public function coupon_code_update(Request $request)
    {
        $coupon_code                 = Coupon::where('id', $request->coupon_id)->first();
        $coupon_code->code           = $request->code;
        $coupon_code->percentage     = $request->percentage;
        $coupon_code->status         = $request->status;
        $coupon_code->user_limit   = $request->user_limit ?? 1;
        $coupon_code->expiry_date  = $request->expiry_date;
        $coupon_code->save();
        if ($coupon_code) {
            return redirect()->route('admin.coupon_code')->with('success', 'Coupon code has been updated successfully');
        }
    }

    public function coupon_code_delete($id)
    {
        $coupon_code                 = Coupon::where('id', $id)->first();
        $coupon_code->delete();
        if ($coupon_code) {
            return redirect()->route('admin.coupon_code')->with('success', 'Coupon code has been deleted successfully');
        }
    }



    //color code

    public function color(Request $request)
    {
        $color_code = Color::latest()->get();
        return view('admin.product.color.index', compact('color_code'));
    }

    public function color_add(Request $request)
    {
        return view('admin.product.color.add');
    }

    public function color_store(Request $request)
    {
        $color_code                 = new Color();
        $color_code->code           = $request->code;
        $color_code->color          = $request->color;
        $color_code->save();
        if ($color_code) {
            return redirect()->route('admin.color')->with('success', 'Color code has been added successfully');
        }
    }

    public function color_edit($id)
    {
        $color_code = Color::findOrFail($id);
        return view('admin.product.color.edit', compact('color_code'));
    }


    public function color_update(Request $request)
    {
        $color_code                 = Color::where('id', $request->coupon_id)->first();
        $color_code->code           = $request->code;
        $color_code->color          = $request->color;
        $color_code->save();
        if ($color_code) {
            return redirect()->route('admin.color')->with('success', 'Color code has been updated successfully');
        }
    }

    public function color_delete($id)
    {
        $color_code                 = Color::where('id', $id)->first();
        $color_code->delete();
        if ($color_code) {
            return redirect()->route('admin.color')->with('success', 'Color code has been deleted successfully');
        }
    }




    //size code

    public function size(Request $request)
    {
        $size = Size::latest()->get();
        return view('admin.product.size.index', compact('size'));
    }

    public function size_add(Request $request)
    {
        return view('admin.product.size.add');
    }

    public function size_store(Request $request)
    {
        $size = new Size();
        $size->name = $request->name;
        $size->value = $request->value;
        $size->status = $request->status ?? 1;
        $size->save();

        return redirect()->route('admin.size')
            ->with('success', 'Size has been added successfully');
    }

    public function size_edit($id)
    {
        $size = Size::findOrFail($id);
        return view('admin.product.size.edit', compact('size'));
    }

    public function size_update(Request $request)
    {
        $size = Size::findOrFail($request->size_id);

        $size->name = $request->name;
        $size->value = $request->value;
        $size->status = $request->status;

        $size->save();

        return redirect()->route('admin.size')
            ->with('success', 'Size has been updated successfully');
    }

    public function size_delete($id)
    {
        $size                 = Size::where('id', $id)->first();
        $size->delete();
        if ($size) {
            return redirect()->route('admin.size')->with('success', 'Size has been deleted successfully');
        }
    }






    public function AjaxCrop(Request $request)
    {
        if (isset($request->image)) {
            $data = $request->image;
            $image_array_1 = explode(";", $data);

            $image_array_2 = explode(",", $image_array_1[1]);
            $image_data = base64_decode($image_array_2[1]);

            $imageName = Carbon::now()->format('YmdHis') . '.jpg';

            $image_path =  ('public/product_images/category_images') . '/' . $imageName;

            file_put_contents($image_path, $image_data);

            return response()->json(['image_url' =>  asset('public/product_images/category_images/' . $imageName), 'image_name' => $imageName]);
        }
    }


    public function AjaxCropSubcat(Request $request)
    {
        if (isset($request->image)) {
            $data = $request->image;
            $image_array_1 = explode(";", $data);

            $image_array_2 = explode(",", $image_array_1[1]);
            $image_data = base64_decode($image_array_2[1]);

            $imageName = Carbon::now()->format('YmdHis') . '.jpg';

            $image_path =  ('public/product_images/subcategory_images') . '/' . $imageName;

            file_put_contents($image_path, $image_data);

            return response()->json(['image_url' =>  asset('public/product_images/subcategory_images/' . $imageName), 'image_name' => $imageName]);
        }
    }


    public function productCategoryDelete($id)
    {
        $category                 = ProductCategory::where('id', $id)->first();
        $category->delete();
        if ($category) {
            return redirect()->route('admin.product.category')->with('success', 'category has been deleted successfully');
        }
    }


    public function productSubCategoryDelete($id)
    {
        $sub_category                 = ProductSubCategory::where('id', $id)->first();
        $sub_category->delete();
        if ($sub_category) {
            return redirect()->route('admin.product.subcategory')->with('success', 'product category code has been deleted successfully');
        }
    }




    //gallery

    public function productGallery(Request $request)
    {
        $gallery = Gallery::latest()->get();
        return view('admin.gallery.index', compact('gallery'));
    }

    public function productGalleryAdd(Request $request)
    {
        return view('admin.gallery.add');
    }

    public function productGalleryStore(Request $request)
    {
        $imagePaths = [];
        $galleryImages      = $request->file('file2');


        $productGallery = new Gallery();
        $productGallery->name = $request->name;
        $productGallery->save();

        if ($galleryImages) {
            $imageIds = [];

            foreach ($galleryImages as $index => $image) {

                $imageName = Carbon::now()->format('YmdHis') . '_' . $image->getClientOriginalName();

                $image->move(public_path('gallery_images'), $imageName);

                $imageModel = new GalleryImage();

                $imageModel->image       = $imageName;

                $imageModel->gallery_id = $productGallery->id;
                $imageModel->save();

                $imageIds[] = $imageModel->id;
            }
        }

        if ($productGallery) {
            return redirect()->route('admin.product.gallery')->with('success', 'Gallery has been added successfully');
        }
    }

    public function productGalleryEdit($id)
    {
        $gallery = Gallery::findOrFail($id);
        return view('admin.gallery.edit', compact('gallery'));
    }


    public function productGalleryUpdate(Request $request)
    {
        $productGallery = Gallery::findOrFail($request->product_id);


        $galleryImages      = $request->file('file2');

        $productGallery->name = $request->name;
        $productGallery->save();

        if ($galleryImages) {
            $imageIds = [];

            foreach ($galleryImages as $index => $image) {

                $imageName = Carbon::now()->format('YmdHis') . '_' . $image->getClientOriginalName();

                $image->move(public_path('gallery_images'), $imageName);

                $imageModel = new GalleryImage();

                $imageModel->image       = $imageName;

                $imageModel->gallery_id = $productGallery->id;
                $imageModel->save();

                $imageIds[] = $imageModel->id;
            }
        }


        return redirect()->route('admin.product.gallery')->with('success', 'gallery has been updated successfully');
    }

    public function productGalleryImgDelete(Request $request)
    {
        $upload = GalleryImage::find($request->id);

        if ($upload) {
            $imagePath = public_path('gallery_images/' . $upload->image);

            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete the image file from the server
            }

            $upload->delete();

            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => 'Image not found.'], 404);
        }
    }


    public function productGalleryDelete($id)
    {
        $gallery = Gallery::findOrFail($id);

        $productImages = GalleryImage::where('gallery_id', $gallery->id)->get();
        foreach ($productImages as $image) {
            if ($image->image) {
                $filePath = public_path('storage/gallery_images/' . $image->image); // Assuming images are stored in storage/app/public/gallery_images
                if (file_exists($filePath)) {
                    unlink($filePath); // Delete the file from the public storage directory
                } else {
                    Log::error("Image file not found for image ID: {$image->id}");
                }
                $image->delete(); // Delete the image record from the database
            } else {
                Log::error("Image filename is null for image ID: {$image->id}");
            }
        }

        $gallery->delete(); // Delete the gallery record from the database

        return redirect()->back()->with('success', 'Product deleted successfully');
    }



    public function deleteProductImage(Request $request)
    {
        $product_id = $request->input('product_id');
        $imageName = $request->input('image');

        // Fetch the product detail
        $productDetail = ProductDetail::where('product_id', $product_id)->first();

        if ($productDetail) {
            // Decode the existing images
            $images = json_decode($productDetail->images, true);

            // Remove the selected image from the array
            if (($key = array_search($imageName, $images)) !== false) {
                unset($images[$key]);

                // Re-encode and update the database
                $productDetail->images = json_encode(array_values($images));
                $productDetail->save();

                // Delete the image file from the storage
                $imagePath = public_path('product_images/' . $imageName);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }

                return response()->json(['success' => true]);
            }
        }

        return response()->json(['success' => false]);
    }
}
