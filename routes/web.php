<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ChangePasswordController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\orderController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\MemberController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\AddressController;
use App\Http\Controllers\Frontend\GoogleAuthController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Services\SmsService;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Route::get('/sendsms', function ( SmsService $smsService) {
//      $phone = '9999999999'; // Replace with the recipient's phone number
//             $otp   = '123456'; // Replace with the OTP you want to send
//             $type  = 'order_confirmed';
//             // dd($phone, $otp, $type);
//             $response = $smsService->sendSms($phone, $otp, $type);
//             dd($response);
// });

Route::get('/', function () {
    return view('frontend.index');
})->name('home');

Route::get('forgot-password-step1', function () {
    return view('frontend.auth.forgot_password_step1');
})->name('forgot_password_step1');

Route::get('login-with-otp', function () {
    return view('frontend.auth.login_with_otp');
})->name('login.otp');

/*Route::get('login-with-otp', function () {
    return view('frontend.auth.login_with_otp');
})->name('login.otp');*/

Route::get('contact', function () {
    return view('frontend.contact');
})->name('contact');
Route::get('/about', function () {
    return view('frontend.about');
})->name('about');

Route::get('gallery', function () {
    return view('frontend.gallery');
})->name('gallery');

Route::get('terms-condition', function () {
    return view('frontend.terms_condition');
})->name('terms_condition');

Route::get('privacy-policy', function () {
    return view('frontend.privacy_policy');
})->name('privacy_policy');

Route::get('faq', function () {
    return view('frontend.faq');
})->name('faq');

Route::get('shipping_return', function () {
    return view('frontend.shipping_return');
})->name('shipping_return');

Route::get('join_us', function () {
    return view('frontend.join_us');
})->name('join_us');

Route::get('disclaimer', function () {
    return view('frontend.disclaimer');
})->name('disclaimer');

Route::get('all-category', function () {
    return view('frontend.all_category');
})->name('all.category');

Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

Route::controller(HomeController::class)->group(function () {
    Route::post('send-message', 'send')->name('contact.send');
});
Route::controller(AuthController::class)->group(function () {
    Route::get('user/login', 'login')->name('user.login');
    Route::post('user/signin', 'signin')->name('signin');
    Route::get('register', [AuthController::class, 'register'])->name('register');
    Route::post('user/signup', [AuthController::class, 'signup'])->name('signup');
    Route::post('user-verification', 'otpvarification')->name('otpvarification');
    Route::get('user/logout', 'logout')->name('user.logout');
    Route::get('user-resend-otp/{user_id}', 'userresendOtp')->name('userresendOtp');
    Route::post('verify-user', 'verify_user')->name('verify.user.otp');
    Route::post('user-verify-otp', 'user_verify_otp')->name('user.otp.verify');
    Route::post('forgot-password-step2', 'forgot_password_step2')->name('forgot_password_step2');
    Route::post('verification_code', 'verification_code')->name('verification_code');
    Route::post('reset-password', 'reset_password')->name('reset_password');
});

Route::post('/update-cart', function (Request $request) {
    $id = $request->input('id');
    $quantity = (int) $request->input('quantity');
    $cart = session('cart', []);

    if (!isset($cart[$id])) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Product not found in cart',
        ], 404);
    }

    if ($quantity < 1) {
        $quantity = 1;
    }

    $cart[$id]['quantity'] = $quantity;

    session(['cart' => $cart]);

    $offerPrice = $cart[$id]['offer_price'] ?? 0;
    $subtotal   = $offerPrice * $quantity;

    $total = 0;
    foreach ($cart as $item) {
        $total += ($item['offer_price'] ?? 0) * ($item['quantity'] ?? 1);
    }

    return response()->json([
        'status'   => 'success',
        'subtotal' => round($subtotal, 2),
        'total'    => round($total, 2),
    ]);
});


Route::post('/remove-from-cart', function (\Illuminate\Http\Request $request) {
    $id = $request->input('id');
    // Remove item from session cart
    session()->forget('cart.' . $id);
    // Calculate total
    $total = 0;
    foreach (session('cart') as $item) {
        $total += $item['offer_price'] * $item['quantity'];
    }
    // Return response indicating success and updated total
    return response()->json([
        'success' => true,
        'total' => $total
    ]);
});

Route::post('/remove-from-wishlist', function (\Illuminate\Http\Request $request) {
    $id = $request->input('id');
    // Remove item from session wishlist
    session()->forget('wishlist.' . $id);
    // Calculate total
    $total = 0;
    foreach (session('wishlist') as $item) {
        $total += $item['offer_price'] * $item['quantity'];
    }
    // Return response indicating success and updated total
    return response()->json([
        'success' => true,
        'total' => $total
    ]);
});

Route::controller(HomeController::class)->group(function () {
    Route::post('/newsletter-subscribe', 'newsletter')->name('newsletter.subscribe');
    Route::get('product/search', 'searchList')->name('search.product');
    Route::get('product/category/{id}', 'categoryList')->name('category.list');
    Route::get('product/subcategory/{id}', 'subCategoryList')->name('subcategory.list');
    Route::get('get-product-details', 'getProductDetails')->name('get-product-details');
    Route::get('cart', 'showCartTable')->name('show.cart.table');
    Route::get('add-to-cart/{id}', 'addToCart')->name('addto.cart');
    Route::delete('remove-from-cart', 'removeCartItem')->name('remove.cart.item');
    Route::get('clear-cart', 'clearCart')->name('clear.cart');
    Route::get('add-to-cart-buy-product/{id}', 'addToCartBuy')->name('addto.cart.buy');
    Route::get('proceed-to-checkout', 'proceed_to_checkout')->name('product.proceed_to_checkout');
    Route::get('wishlist', 'wishlistList')->name('show.wishlist.list');
    Route::get('add-to-wishlist/{id}', 'addToWishlist')->name('addto.wishlist');
    Route::get('user-wishlist', 'userWishlistList')->name('user.whislist.list');
    Route::get('product-details/{id}', 'productDetails')->name('product.details.show');
    Route::post('apply-coupon', 'applyCoupon')->name('apply.coupon');
    Route::post('remove-coupon', 'removeCoupon')->name('remove.coupon');
    Route::post('wishlist/move-to-cart', 'moveToCart')->name('wishlist.moveToCart');
    Route::post('wishlist/remove', 'removeFromWishlist')->name('wishlist.remove');
    Route::get('get-size', 'getSize')->name('api.get-sizes');
    Route::get('customer-care', 'customerCare')->name('customer.care');
});

Route::middleware(['user.auth'])->group(function () {
    Route::controller(MemberController::class)->group(function () {
        Route::get('user/dashboard', 'dashboard')->name('user.dashboard');
        Route::get('user/edit-profile', 'editProfile')->name('user.profile.edit');
        Route::post('user/update-profile', 'updateProfile')->name('user.profile.update');
        Route::post('update-profile-image', 'updateProfileImg')->name('update.profile.image');
        Route::post('user/upload-image', 'upload')->name('image.upload');
        Route::get('user/change-password', 'changePassword')->name('change.password');
        Route::post('user/update-password', 'updatePassword')->name('user.update.password');
        Route::get('user/my-address', 'userAddress')->name('user.address');
        Route::get('user/delete-address/{id}', 'deleteAddress')->name('user.address.delete');
        Route::get('user/order-history', 'orderHistory')->name('user.order.history');
        Route::get('user/fetch-products', 'fetchProducts')->name('fetch.products');
        Route::post('user/add-review', 'addReview')->name('add.review');
        Route::post('/order/status/update', 'updateStatus')->name('user.order.status');
        Route::get('/forgot/password', 'forgotPassword')->name('forgot.password');
        Route::get('user/order-details/{id}', 'userOrderDetails')->name('user.order.details');
        Route::post('user/order/enquiry/store', 'storeEnquiry')->name('user.order.enquiry.store');
        Route::get('user/enquiry/messages/{orderId}', 'getEnquiryMessages')->name('user.enquiry.messages');
    });

    Route::controller(AddressController::class)->group(function () {
        Route::post('user/add-address', 'addNewAddress')->name('user.addNewAddress');
        Route::post('user/set-address', 'setDefault')->name('user.setDefault');
        Route::get('/states-by-country/{country}', [AddressController::class, 'getStates']);
        Route::post('get-cities', 'getCities')->name('getCities');
        Route::post('get-updateAddress', 'updateAddress')->name('user.updateAddress');
        Route::post('order-confirm', 'orderConfirm')->name('order.confirm');
        Route::get('/user/address/{id}', 'getAddress')->name('user.getAddress');
    });

    Route::controller(PaymentController::class)->group(function () {
        Route::post('payment-process', 'paymentProcess')->name('payment.process');
        Route::get('payment-invoice', 'invoice')->name('invoice');
        Route::post('payment-success', 'paymentSuccess')->name('payment.success');
    });
});

Route::get('/admin', function () {
    return view('admin.login');
})->name('admin');
Route::get('/invoice', function () {
    return view('admin.login');
})->name('invoice');


Route::controller(AdminController::class)->group(function () {
    Route::post('admin/login', 'admin_login')->name('admin.login');
    Route::get('admin/logout', 'admin_logout')->name('admin.logout');
});

Route::middleware(['admin.auth'])->group(function () {
    Route::controller(AdminController::class)->group(function () {
        Route::get('admin/dashboard', 'index')->name('admin.dashboard');
        Route::any('admin/profile', 'adminProfile')->name('admin.profile');
        Route::put('admin/profile-update', 'adminProfileUpdate')->name('admin.profile.update');
        Route::any('admin/home-section', 'adminHomeSection')->name('admin.home.section');
        Route::put('admin/home-section-update', 'adminHomeSectionUpdate')->name('admin.home.section.update');
        Route::get('admin/users', 'regUsers')->name('admin.users');
        Route::get('admin/user-show/{id}', 'regUserPreview')->name('admin.user.preview');
        Route::get('admin/users-delete/{id}', 'regUserDelete')->name('admin.user.delete');
        Route::get('admin/banner-image', 'bannerImage')->name('admin.banner.show');
        Route::put('admin/banner-image-update', 'bannerImageUpdate')->name('banner.update');
        Route::post('admin/banner-image-add', 'bannerImageAdd')->name('banner.add');
        Route::get('admin/banner-image-delete/{id}', 'bannerImageDelete')->name('banner_images.delete');
        Route::get('admin/reviews', 'reviews')->name('review');
        Route::post('/admin/reviews/update', 'update')->name('admin.reviews.update');
        Route::post('/admin/reviews/delete', 'delete')->name('admin.reviews.delete');
    });

    Route::controller(CountryController::class)->group(function () {
        Route::get('admin/country/index', 'index')->name('admin.country.index');
    });

    Route::controller(StateController::class)->group(function () {
        Route::get('admin/state/index', 'index')->name('admin.state.index');
    });

    Route::controller(CityController::class)->group(function () {
        Route::get('admin/city/index', 'index')->name('admin.city.index');
    });

    Route::controller(ProductController::class)->group(function () {
        Route::get('/size-condition', 'sizeCondition')->name('size.condition.all');
        Route::get('/color-condition', 'colorCondition')->name('color.condition.all');
        // category
        Route::get('/product-category', 'productCategory')->name('admin.product.category');
        Route::get('/product-category-add', 'productCategoryAdd')->name('admin.product.category.add');
        Route::post('/product-category-store', 'productCategoryStore')->name('admin.product.category.store');
        Route::get('/product-category-delete/{id}', 'productCategoryDelete')->name('product.category.delete');
        Route::get('/product-category-edit/{id}', 'productCategoryEdit')->name('admin.product.category.edit');
        Route::post('/product-category-update', 'productCategoryUpdate')->name('admin.product.category.update');
        Route::get('/product-category-img-delete/{id}/{name}', 'productCategoryImgDelete')->name('product.category.imagedelete');
        Route::post('crop-image-upload-ajax', 'AjaxCrop')->name('crop-image-upload-ajax');
        // subcategory
        Route::get('/product-subcategory', 'productSubCategory')->name('admin.product.subcategory');
        Route::get('/product-subcategory-add', 'productSubCategoryAdd')->name('admin.product.subcategory.add');
        Route::post('/product-subcategory-store', 'productSubCategoryStore')->name('admin.product.subcategory.store');
        Route::get('/product-subcategory-delete/{id}', 'productSubCategoryDelete')->name('product.subcategory.delete');
        Route::get('/product-subcategory-edit/{id}', 'productSubCategoryEdit')->name('admin.product.subcategory.edit');
        Route::post('/product-subcategory-update', 'productSubCategoryUpdate')->name('admin.product.subcategory.update');
        Route::get('/product-subcategory-img-delete/{id}/{name}', 'productSubCategoryImgDelete')->name('product.subcategory.imagedelete');
        Route::post('crop-image-upload-ajax-subcat', 'AjaxCropSubcat')->name('crop-image-upload-ajax-subcat');

        //product
        Route::get('/product', 'product')->name('admin.product');
        Route::get('/product-add', 'productAdd')->name('admin.product.add');
        Route::get('/get-subcategories/{id}', 'getSubcategories')->name('get-subcategories');
        Route::post('/product-store', 'productStore')->name('admin.product.store');
        Route::get('/product-view/{id}', 'productView')->name('product.preview');
        Route::get('/product-edit/{id}', 'productEdit')->name('product.edit');
        Route::post('/product-update', 'productUpdate')->name('product.update');
        Route::get('/product-delete/{id}', 'productDelete')->name('product.delete');

        //coupon code
        Route::get('/coupon-code', 'coupon_code')->name('admin.coupon_code');
        Route::get('/product-coupon_code-add', 'coupon_code_add')->name('admin.product.coupon_code.add');
        Route::post('/product-coupon_code-store', 'coupon_code_store')->name('admin.product.coupon_code.store');
        Route::get('/product-coupon_code-delete/{id}', 'coupon_code_delete')->name('product.coupon_code.delete');
        Route::get('/product-coupon_code-edit/{id}', 'coupon_code_edit')->name('admin.product.coupon_code.edit');
        Route::post('/product-coupon_code-update', 'coupon_code_update')->name('admin.product.coupon_code.update');
        //color code
        Route::get('/color', 'color')->name('admin.color');
        Route::get('/product-color-add', 'color_add')->name('admin.product.color.add');
        Route::post('/product-color-store', 'color_store')->name('admin.product.color.store');
        Route::get('/product-color-delete/{id}', 'color_delete')->name('product.color.delete');
        Route::get('/product-color-edit/{id}', 'color_edit')->name('admin.product.color.edit');
        Route::post('/product-color-update', 'color_update')->name('admin.product.color.update');

        //size code
        Route::get('/size', 'size')->name('admin.size');
        Route::get('/product-size-add', 'size_add')->name('admin.product.size.add');
        Route::post('/product-size-store', 'size_store')->name('admin.product.size.store');
        Route::get('/product-size-delete/{id}', 'size_delete')->name('product.size.delete');
        Route::get('/product-size-edit/{id}', 'size_edit')->name('admin.product.size.edit');
        Route::post('/product-size-update', 'size_update')->name('admin.product.size.update');
        Route::post('/admin-product-image-remove', 'deleteProductImage')->name('image.delete');
        Route::post('/admin-product-image-delete', 'deleteImage')->name('admin.product.image.delete');
        Route::post('/admin-product-update', 'adminProductUpdate')->name('admin.product.update');

        // gallery
        Route::get('/admin-gallery', 'productGallery')->name('admin.product.gallery');
        Route::get('/admin-gallery-add', 'productGalleryAdd')->name('admin.product.gallery.add');
        Route::post('/admin-gallery-store', 'productGalleryStore')->name('admin.product.gallery.store');
        Route::get('/admin-gallery-delete/{id}', 'productGalleryDelete')->name('product.gallery.delete');
        Route::get('/admin-gallery-edit/{id}', 'productGalleryEdit')->name('admin.product.gallery.edit');
        Route::post('/admin-gallery-update', 'productGalleryUpdate')->name('admin.product.gallery.update');
        Route::post('/admin-gallery-image-delete', 'productGalleryImgDelete')->name('admin.gallery.image.delete');
        Route::post('crop-image-upload-ajax-gallery', 'AjaxCropGallery')->name('crop-image-upload-ajax-gallery');
    });

    Route::controller(BlogController::class)->group(function () {
        // category
        Route::get('/blog-category', 'blogCategory')->name('admin.blog.category');
        Route::post('/blog-category-add', 'blogCategoryAdd')->name('admin.blog.category.add');
        Route::get('/blog-category-delete/{id}', 'blogCategoryDelete')->name('blog.category.delete');
        Route::get('/blog-category-edit/{id}', 'blogCategoryEdit')->name('admin.blog.category.edit');
        Route::post('/blog-category-update', 'blogCategoryUpdate')->name('admin.blog.category.update');

        // sub category
        Route::get('/blog-subcategory', 'blogSubCategory')->name('admin.blog.subcategory');
        Route::post('/blog-subcategory-add', 'blogSubCategoryAdd')->name('admin.blog.subcategory.add');
        Route::get('/blog-subcategory-delete/{id}', 'blogSubCategoryDelete')->name('blog.subcategory.delete');
        Route::get('/blog-subcategory-edit/{id}', 'blogSubCategoryEdit')->name('admin.blog.subcategory.edit');
        Route::post('/blog-subcategory-update', 'blogSubCategoryUpdate')->name('admin.blog.subcategory.update');

        //blog
        Route::get('/blog', 'blog')->name('admin.blog');
        Route::get('/blog-add', 'blogAdd')->name('admin.blog.add');
        Route::post('/blog-store', 'blogStore')->name('admin.blog.store');
        Route::get('/blog-view/{id}', 'blogView')->name('blog.preview');
        Route::get('/blog-edit/{id}', 'blogEdit')->name('blog.edit');
        Route::post('/blog-update', 'blogUpdate')->name('blog.update');
        Route::get('/blog-delete/{id}', 'blogDelete')->name('blog.delete');
    });

    Route::controller(ChangePasswordController::class)->group(function () {
        Route::get('/admin-change-password', 'showChangePasswordForm')->name('password.change');
        Route::post('/change-password', 'changePassword')->name('password.update');
    });

    Route::controller(orderController::class)->group(function () {
        Route::get('/admin-all-orders', 'orderList')->name('admin.order.list');
        Route::get('/admin-order-details/{id}', 'adminOrderDetails')->name('admin.order.detail');
        Route::post('/update-order-status', 'updateStatus')->name('update.order.status');
        Route::post('/update-shipping-status', 'updateShippingStatus')->name('update.shipping.status');
        Route::get('/admin-download-invoice/{id}', 'adminDownlaodInvoice')->name('admin.download.invoice');
    });
    Route::resource('admin/complaints', ComplaintController::class)->names([
        'index' => 'admin.complaints.index'
    ]);
    Route::post('admin/complaints/reply', [ComplaintController::class, 'reply'])->name('complaints.reply');

    // Route::prefix('complaints')->group(function () {

    //     Route::get('/', [ComplaintController::class, 'index'])->name('admin.complaints.index');

    //     Route::get('/view/{id}', [ComplaintController::class, 'show'])
    //         ->name('complaints.show');

    //     Route::post('/reply', [ComplaintController::class, 'reply'])
    //         ->name('complaints.reply');
});
