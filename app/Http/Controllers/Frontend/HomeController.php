<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use App\Models\Country;
use App\Models\Coupon;
use App\Models\Upload;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use App\Models\Color;
use App\Models\State;
use App\Models\Size;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Services\SmsService;
use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Artisan;

class HomeController extends Controller
{

    // public function searchList(Request $request)
    // {
    //     $user        = Auth::user();
    //     $category    = ProductCategory::where('status', 1)->latest()->get();
    //     $subcategory = ProductSubCategory::where('status', 1)->latest()->get();

    //     $search_text = $request->search_text;

    //     $search = null;
    //     $product = Product::query();


    //     if ($search_text) {
    //         $product     = Product::where('deleted', 0)->where('product_name', 'LIKE', "%{$search_text}%")
    //             ->where('status', 1)
    //             ->latest()->get();
    //         $product_count = Product::where('deleted', 0)->where('status', 1)->count();
    //     } else {
    //         $product     = Product::where('deleted', 0)->where('status', 1)->latest()->get();

    //         $product_count = Product::where('deleted', 0)->where('status', 1)->count();
    //     }

    //     return view('frontend.product.search_product', compact('user', 'category', 'subcategory', 'product', 'product_count'));
    // }
    public function searchList(Request $request)
    {
        $user        = Auth::user();
        $category    = ProductCategory::where('status', 1)->latest()->get();
        $subcategory = ProductSubCategory::where('status', 1)->latest()->get();

        $search_text = $request->search_text;

        $product = Product::where('deleted', 0)
            ->where('status', 1);

        if ($search_text) {
            $product->where('product_name', 'LIKE', "%{$search_text}%");
        }

        $product = $product->latest()->get();
        $product_count = $product->count();

        return view('frontend.product.search_product', compact(
            'user',
            'category',
            'subcategory',
            'product',
            'product_count',
            'search_text'   // ✅ IMPORTANT
        ));
    }



    public function categoryList($id, Request $request)
    {
        $user = Auth::user();
        $category = ProductCategory::where('status', 1)->latest()->get();
        $subcategory = ProductSubCategory::where('status', 1)->latest()->get();

        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $sort_val = $request->sort_val;
        $sort_size = $request->sort_size;

        // Get min and max price from ProductDetail
        $get_product_min_price = ProductDetail::whereHas('product', function ($query) {
            $query->where('deleted', 0)->where('status', 1);
        })->min('price');

        $get_product_max_price = ProductDetail::whereHas('product', function ($query) {
            $query->where('deleted', 0)->where('status', 1);
        })->max('price');

        // Base query for products
        $productQuery = Product::where('deleted', 0)
            ->where('category_id', $id)
            ->where('status', 1);

        // Apply size filter if selected
        if ($sort_size) {
            $productQuery->whereHas('details', function ($query) use ($sort_size) {
                $query->where('size', $sort_size);
            });
        }

        // Apply sorting based on selected criteria
        if ($sort_val) {
            switch ($sort_val) {
                case 'newest':
                    $productQuery->latest();
                    break;
                case 'oldest':
                    $productQuery->oldest();
                    break;
                case 'high_to_low':
                    $productQuery->orderByRaw('(
                        SELECT MIN(price)
                        FROM product_details
                        WHERE product_id = products.id
                    ) DESC');
                    break;
                case 'low_to_high':
                    $productQuery->orderByRaw('(
                        SELECT MIN(price)
                        FROM product_details
                        WHERE product_id = products.id
                    ) ASC');
                    break;
                case 'a_z':
                    $productQuery->orderBy('product_name', 'asc');
                    break;
                case 'z_a':
                    $productQuery->orderBy('product_name', 'desc');
                    break;
            }
        }

        // Get the products
        $product = $productQuery->get();

        $cat_id = $id;

        return view('frontend.product.category', compact(
            'cat_id',
            'user',
            'category',
            'subcategory',
            'product',
            'get_product_min_price',
            'get_product_max_price',
            'min_price',
            'max_price'
        ));
    }



    public function subCategoryList($id, Request $request)
    {
        $user = Auth::user();
        $category = ProductCategory::where('status', 1)->latest()->get();
        $subcategory = ProductSubCategory::where('status', 1)->latest()->get();

        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $sort_val = $request->sort_val;
        $search_text = $request->search_text;
        $selected_categories = $request->selected_categories;

        // Get min and max price from ProductDetail
        $get_product_min_price = ProductDetail::whereHas('product', function ($query) {
            $query->where('deleted', 0)->where('status', 1);
        })->min('price');

        $get_product_max_price = ProductDetail::whereHas('product', function ($query) {
            $query->where('deleted', 0)->where('status', 1);
        })->max('price');

        // Base query for products
        $productQuery = Product::where('deleted', 0)
            ->where('status', 1);

        // Filter by category if selected
        if ($selected_categories) {
            $productQuery->whereIn('category_id', $selected_categories);
        }

        // Filter by subcategory
        $productQuery->where('subcategory', $id);

        // Apply price filter if selected
        if ($min_price || $max_price) {
            $min_price = intval($min_price);
            $max_price = intval($max_price);
            $productQuery->whereBetween('offer_price', [$min_price, $max_price]);
        }

        // Apply search filter if provided
        if ($search_text) {
            $productQuery->where('product_name', 'LIKE', "%{$search_text}%");
        }

        // Apply sorting based on selected criteria
        if ($sort_val) {
            switch ($sort_val) {
                case 'newest':
                    $productQuery->latest();
                    break;
                case 'oldest':
                    $productQuery->oldest();
                    break;
                case 'high_to_low':
                    $productQuery->orderByRaw('(
                        SELECT MIN(price)
                        FROM product_details
                        WHERE product_id = products.id
                    ) DESC');
                    break;
                case 'low_to_high':
                    $productQuery->orderByRaw('(
                        SELECT MIN(price)
                        FROM product_details
                        WHERE product_id = products.id
                    ) ASC');
                    break;
                case 'a_z':
                    $productQuery->orderBy('product_name', 'asc');
                    break;
                case 'z_a':
                    $productQuery->orderBy('product_name', 'desc');
                    break;
            }
        } else {
            $productQuery->latest(); // Default sorting
        }

        // Get the products
        $product = $productQuery->get();

        // Count the number of products after filtering
        $product_count = $productQuery->count();

        $sub_cat_id = $id;

        return view('frontend.product.subcategory', compact(
            'sub_cat_id',
            'user',
            'category',
            'subcategory',
            'product',
            'selected_categories',
            'get_product_min_price',
            'get_product_max_price',
            'product_count',
            'min_price',
            'max_price'
        ));
    }









    public function subCategoryList_old($id, Request $request)
    {
        $user = Auth::user();
        $category = ProductCategory::where('status', 1)->latest()->get();
        $subcategory = ProductSubCategory::where('status', 1)->latest()->get();

        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $sort_val = $request->sort_val;
        $search_text = $request->search_text;
        $selected_categories = $request->selected_categories;

        // Get min and max price from ProductDetail
        $get_product_min_price = ProductDetail::whereHas('product', function ($query) {
            $query->where('deleted', 0)->where('status', 1);
        })->min('price');

        $get_product_max_price = ProductDetail::whereHas('product', function ($query) {
            $query->where('deleted', 0)->where('status', 1);
        })->max('price');

        // Base query for products
        $productQuery = Product::where('deleted', 0)
            ->where('status', 1);

        // Filter by category if selected
        if ($selected_categories) {
            $productQuery->whereIn('category_id', $selected_categories);
        }

        // Filter by subcategory
        $productQuery->where('subcategory', $id);

        // Apply price filter if selected
        if ($min_price || $max_price) {
            $min_price = intval($min_price);
            $max_price = intval($max_price);
            $productQuery->whereBetween('offer_price', [$min_price, $max_price]);
        }

        // Apply search filter if provided
        if ($search_text) {
            $productQuery->where('product_name', 'LIKE', "%{$search_text}%");
        }

        // Apply sorting based on selected criteria
        if ($sort_val) {
            switch ($sort_val) {
                case 'newest':
                    $productQuery->latest();
                    break;
                case 'oldest':
                    $productQuery->oldest();
                    break;
                case 'high_to_low':
                    $productQuery->orderByRaw('(
                        SELECT MIN(price)
                        FROM product_details
                        WHERE product_id = products.id
                    ) DESC');
                    break;
                case 'low_to_high':
                    $productQuery->orderByRaw('(
                        SELECT MIN(price)
                        FROM product_details
                        WHERE product_id = products.id
                    ) ASC');
                    break;
                case 'a_z':
                    $productQuery->orderBy('product_name', 'asc');
                    break;
                case 'z_a':
                    $productQuery->orderBy('product_name', 'desc');
                    break;
            }
        } else {
            $productQuery->latest(); // Default sorting
        }

        // Get the products
        $product = $productQuery->get();

        // Count the number of products after filtering
        $product_count = $productQuery->count();

        $sub_cat_id = $id;

        return view('frontend.product.subcategory', compact(
            'sub_cat_id',
            'user',
            'category',
            'subcategory',
            'product',
            'selected_categories',
            'get_product_min_price',
            'get_product_max_price',
            'product_count',
            'min_price',
            'max_price'
        ));
    }



    public function productDetails($id, Request $request)
    {
        $product = Product::where('deleted', 0)->findOrFail($id);

        // Fetch Reviews for this specific product
        $reviews = \App\Models\Review::where('product_id', $id)
            ->latest()
            ->get();

        $variantColors = ProductDetail::where('product_id', $id)
            ->whereNotNull('color_id')
            ->distinct()
            ->pluck('color_id');

        $colors = Color::whereIn('id', $variantColors)->get();

        $colorId = $request->color ?? ($colors->first()->id ?? null);
        $sizeId  = $request->size;

        $galleryImages = Upload::where('product_id', $id)->get();

        $sizes = collect();
        if ($colorId) {
            $variantSizes = ProductDetail::where('product_id', $id)
                ->where('color_id', $colorId)
                ->whereNotNull('size_id')
                ->distinct()
                ->pluck('size_id');

            $sizes = Size::whereIn('id', $variantSizes)->get();

            if (!$sizeId && $sizes->count() > 0) {
                $sizeId = $sizes->first()->id;
            }
        }

        $productDetail = null;
        if ($colorId && $sizeId) {
            $productDetail = ProductDetail::where('product_id', $id)
                ->where('color_id', $colorId)
                ->where('size_id', $sizeId)
                ->first();
        }
        if (!$colorId && !$sizeId) {
            $productDetail = ProductDetail::where('product_id', $id)
                ->whereNull('color_id')
                ->whereNull('size_id')
                ->first();
        }

        return view('frontend.product.details', compact(
            'product',
            'galleryImages',
            'productDetail',
            'colors',
            'sizes',
            'colorId',
            'sizeId',
            'reviews' // ← Added this
        ));
    }


    public function getProductDetails(Request $request)
    {
        // Fetch product details based on the ID sent via AJAX
        $productId = $request->input('id');
        $product = Product::where('deleted', 0)->find($productId);

        // Fetch product details, images, colors, and sizes
        $productDetails = ProductDetail::where('product_id', $productId)->first();
        $productImages = Upload::where('product_id', $productId)->get();

        $colorIds = ProductDetail::where('product_id', $productId)->distinct()->pluck('color');
        $colors = Color::whereIn('id', $colorIds)->get();

        $sizeIds = ProductDetail::where('product_id', $productId)->distinct()->pluck('size');
        $sizes = Size::whereIn('id', $sizeIds)->get();

        // Return the product details as HTML
        return view('frontend.product.product_details', [
            'product' => $product,
            'productDetails' => $productDetails,
            'productImages' => $productImages,
            'colors' => $colors,
            'sizes' => $sizes
        ])->render();
    }


    public function getSizesForColor(Request $request)
    {
        $colorId = $request->query('color_id');

        // Fetch sizes based on the selected color
        $sizes = Size::where('color_id', $colorId)->get();

        // Assuming the sizes have an id and name field
        return response()->json([
            'sizes' => $sizes,
            'selectedSize' => $request->query('size')
        ]);
    }





    public function showCartTable()
    {
        $cart = session('cart') ?? [];

        if (empty($cart)) {
            return view('frontend.product.cart', compact('cart'));
        }

        $productDetailIds = array_keys($cart);

        // Load ProductDetails with Product relation
        $productDetails = ProductDetail::with('product')
            ->whereIn('id', $productDetailIds)
            ->get()
            ->keyBy('id');

        return view('frontend.product.cart', compact('cart', 'productDetails'));
    }



    public function wishlistList()
    {
        $product = Product::all();

        return view('frontend.product.wishlist', compact('product'));
    }

    public function addToWishlist($id)
    {
        $product = Product::find($id);

        if (!$product) {

            abort(404);
        }

        $wishlist = session()->get('wishlist');

        if (!$wishlist) {

            $wishlist = [
                $id => [
                    "product_name" => $product->product_name,
                    "id" => $product->id,
                    "quantity"     => 1,
                    "offer_price"  => $product->offer_price,
                    "product_img"  => $product->product_img
                ]
            ];

            session()->put('wishlist', $wishlist);

            return redirect()->back()->with('success', 'Product added to wishlist successfully!');
        }

        if (isset($wishlist[$id])) {

            $wishlist[$id]['quantity']++;

            session()->put('wishlist', $wishlist);

            return redirect()->back()->with('success', 'Product added to wishlist successfully!');
        }

        $wishlist[$id] = [
            "product_name" => $product->product_name,
            "quantity" => 1,
            "offer_price" => $product->offer_price,
            "product_img" => $product->product_img
        ];

        session()->put('wishlist', $wishlist);
        if (request()->wantsJson()) {
            return response()->json(['message' => 'Product added to wishlist successfully!']);
        }

        return redirect()->back()->with('success', 'Product added to wishlist successfully!');
    }

    

    public function addToCartBuyOld($id)
    {
        $product = Product::find($id);
        if (!$product) {
            abort(404);
        }

        $cart = session()->get('cart');

        if (!$cart) {
            $cart = [
                $id => [
                    "id" => $product->id,
                    "gst" => $product->gst,
                    "product_name" => $product->product_name,
                    "quantity"     => 1,
                    "offer_price"  => $product->offer_price,
                    "product_img"  => $product->product_img
                ]
            ];
            session()->put('cart', $cart);
        } else {
            if (isset($cart[$id])) {
                $cart[$id]['quantity']++;
            } else {
                $cart[$id] = [
                    "id" => $product->id,
                    "gst" => $product->gst,
                    "product_name" => $product->product_name,
                    "quantity" => 1,
                    "offer_price" => $product->offer_price,
                    "product_img" => $product->product_img
                ];
            }
            session()->put('cart', $cart);
        }

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Product added to cart successfully!']);
        }

        return redirect()->route('show.cart.table')->with('success', 'Product added to cart successfully!');
    }



    public function addToCartBuy(Request $request, $id)
    {
        $product_details = ProductDetail::find($id);
        $product = Product::find($product_details->product_id);

        if (!$product) {
            abort(404);
        }

        // Get clicked image index
        $imageIndex = $request->image_index ?? 0;

        // Decode variant images
        $variantImages = json_decode($product_details->images, true);

        // Get selected image
        $selectedImage = $variantImages[$imageIndex] ?? $product->product_img;

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {

            $cart[$id]['quantity']++;
            $cart[$id]['image_index']    = $imageIndex;
            $cart[$id]['selected_image'] = $selectedImage; // ✅ update image too
        } else {

            $cart[$id] = [
                "id"             => $product_details->id,
                "gst"            => $product->gst,
                "product_name"   => $product->product_name,
                "quantity"       => 1,
                "offer_price"    => $product_details->price - ($product_details->price * ($product->discount / 100)),
                "size"           => $product_details->size,
                "color"          => $product_details->color,
                "size_id"        => $product_details->size_id ?? null,
                "color_id"       => $product_details->color_id ?? null,
                "product_img"    => $selectedImage,
                "discount"       => $product->discount,
                "image_index"    => $imageIndex,
                "selected_image" => $selectedImage,  // ✅ store actual filename
            ];
        }

        session()->put('cart', $cart);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('show.cart.table')->with('success', 'Product added to cart successfully!');
    }

    public function addToCart(Request $request, $id)
    {
        $product_details = ProductDetail::find($id);
        $product = Product::find($product_details->product_id);

        if (!$product) {
            abort(404);
        }

        $imageIndex = $request->image_index ?? 0;

        $variantImages = json_decode($product_details->images, true);

        $selectedImage = $variantImages[$imageIndex] ?? $product->product_img;

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {

            $cart[$id]['quantity']++;
            $cart[$id]['image_index']    = $imageIndex;
            $cart[$id]['selected_image'] = $selectedImage;
            // REPLACE WITH:
        } else {
            $cart[$id] = [
                "id"             => $product_details->id,
                "gst"            => $product->gst,
                "product_name"   => $product->product_name,
                "quantity"       => 1,
                "offer_price"    => $product_details->price - ($product_details->price * ($product->discount / 100)),
                "size"           => $product_details->size,
                "color"          => $product_details->color,
                "size_id"        => $product_details->size_id ?? null,   // ✅ added
                "color_id"       => $product_details->color_id ?? null,  // ✅ added
                "product_img"    => $selectedImage,
                "discount"       => $product->discount,
                "image_index"    => $imageIndex,                         // ✅ added
                "selected_image" => $selectedImage,                      // ✅ store actual filename
            ];
        }

        session()->put('cart', $cart);

        return response()->json(['success' => true]);
    }

    public function removeCartItem(Request $request)
    {
        if ($request->id) {

            $cart = session()->get('cart');

            if (isset($cart[$request->id])) {

                unset($cart[$request->id]);

                session()->put('cart', $cart);
            }

            session()->flash('success', 'Product removed successfully');
        }
    }

    public function clearCart()
    {
        session()->forget('cart');
        return redirect()->back();
    }


    // public function applyCoupon(Request $request)
    // {
    //     dd($request);
    //     $request->validate([
    //         'coupon_code' => 'required|string'
    //     ]);

    //     $coupon = Coupon::where('code', $request->coupon_code)
    //         ->where('status', 1) // active coupon
    //         ->where(function ($q) {
    //             $q->whereNull('expires_at')
    //                 ->orWhere('expires_at', '>=', now());
    //         })
    //         ->first();

    //     if (!$coupon) {
    //         return back()->with('error', 'Invalid or expired coupon!');
    //     }

    //     session()->put('coupon', [
    //         'code' => $coupon->code,
    //         'percentage' => $coupon->percentage
    //     ]);

    //     return back()->with('success', 'Coupon applied successfully!');
    // }
    public function applyCoupon(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to apply coupon');
        }

        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $coupon = Coupon::where('code', $request->coupon_code)
            ->where('status', 1)
            ->first();

        if (!$coupon) {
            return redirect()->back()->with('error', 'Invalid coupon code');
        }

        if ($coupon->user_limit <= 0) {
            return redirect()->back()->with('error', 'This coupon has reached its user limit');
        }

        if ($coupon->expiry_date && \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($coupon->expiry_date))) {
            return redirect()->back()->with('error', 'This coupon has expired');
        }
        session([
            'coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'percentage' => $coupon->percentage,
            ]
        ]);

        return redirect()->back()->with('success', 'Coupon applied successfully');
    }

    public function removeCoupon()
    {
        session()->forget('coupon');
        return redirect()->back()->with('success', 'Coupon removed');
    }






    // public function proceed_to_checkout(Request $request)
    // {
    //     $products = Product::all();
    //     $countries = Country::all();
    //     $userAddress = \App\Models\Address::where('user_id', auth()->user()->id)->get();
    //     $states = State::orderBy('name')->get();

    //     $cart = $request->session()->get('cart', []);

    //     $total_gst = 0;

    //     foreach ($cart as $product) {

    //         $price = $product['offer_price'] - ($product['offer_price'] * ($product['discount'] / 100));

    //         $gst_amount = ($price * $product['gst']) / 100;

    //         $total_gst += $gst_amount * $product['quantity'];
    //     }



    //     // print_r("Total GST: " . number_format($total_gst, 2)); exit();

    //     $coupon = session()->get('coupon', null);
    //     // dd($total_gst);

    //     return view('frontend.product.checkout', compact('products', 'userAddress', 'countries', 'total_gst', 'coupon', 'states'));
    // }
    public function proceed_to_checkout(Request $request)
    {
        $products = Product::all();
        $countries = Country::all();
        $states = State::orderBy('name')->get();

        $userAddress = \App\Models\Address::where('user_id', auth()->user()->id)->get();

        $cart = $request->session()->get('cart', []);

        $subtotal = 0;
        $total_gst = 0;

        foreach ($cart as $product) {

            $price = $product['offer_price'];
            $qty = $product['quantity'];
            $gst = $product['gst']; // product GST %

            $subtotal += $price * $qty;

            $gst_amount = ($price * $gst) / 100;
            $total_gst += $gst_amount * $qty;
        }

        $coupon = session()->get('coupon', null);

        return view(
            'frontend.product.checkout',
            compact(
                'products',
                'userAddress',
                'countries',
                'states',
                'subtotal',
                'total_gst',
                'coupon'
            )
        );
    }



    public function send(Request $request)
    {
        $details = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'mail_subject' => $request->subject,
            'message' => $request->message
        ];

        Mail::to('info@webbitech.com')->send(new ContactFormMail($details));

        return back()->with('success', 'Your message has been sent successfully!');
    }



    public function userWishlistList()
    {
        $product = Product::all();

        return view('frontend.user.wishlist', compact('product'));
    }


    public function moveToCart(Request $request)
    {
        $productId = $request->input('product_id');

        // Retrieve the wishlist and cart from the session
        $wishlist = session()->get('wishlist', []);
        $cart = session()->get('cart', []);

        // Check if the product is in the wishlist
        if (isset($wishlist[$productId])) {
            // Retrieve product details from the database
            $product = Product::find($productId);

            if (!$product) {
                return response()->json(['status' => 'error', 'message' => 'Product not found']);
            }

            // Prepare the cart item data
            $cartItem = [
                "id" => $product->id,
                "gst" => $product->gst,
                "product_name" => $product->product_name,
                "quantity" => 1,
                "offer_price" => $product->offer_price,
                "product_img" => $product->product_img
            ];

            // Add the product to the cart
            if (!$cart) {
                $cart = [
                    $productId => $cartItem
                ];
            } else {
                $cart[$productId] = $cartItem;
            }

            // Remove the product from the wishlist
            unset($wishlist[$productId]);

            // Update the session
            session()->put('wishlist', $wishlist);
            session()->put('cart', $cart);

            return response()->json(['status' => 'success', 'message' => 'Product moved to cart']);
        }

        return response()->json(['status' => 'error', 'message' => 'Product not found in wishlist']);
    }

    public function removeFromWishlist(Request $request)
    {
        $productId = $request->input('product_id');

        // Retrieve the wishlist from the session
        $wishlist = session()->get('wishlist', []);

        // Check if the product is in the wishlist
        if (isset($wishlist[$productId])) {
            // Remove the product from the wishlist
            unset($wishlist[$productId]);

            // Update the session
            session()->put('wishlist', $wishlist);

            return response()->json(['status' => 'success', 'message' => 'Product removed from wishlist']);
        }

        return response()->json(['status' => 'error', 'message' => 'Product not found in wishlist']);
    }


    public function newsletter(Request $request)
    {
        $newsletter = $request->email;
        Mail::send('emails.newsletter_mail', ['newsletter' => $newsletter], function ($message) use ($newsletter) {
            $message->from($newsletter, 'Canntum')
                ->to('info@webbitech.com')
                ->subject('Newsletter Subscription');
        });

        return redirect()->back()->with('message', 'subscribed successfully');
    }
    public function customerCare()
    {
        return view('frontend.customer-care');
    }
}
