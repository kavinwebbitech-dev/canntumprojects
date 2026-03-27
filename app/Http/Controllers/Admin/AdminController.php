<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Section;
use App\Models\ProductSubCategory;
use App\Models\ProductCategory;
use App\Models\BannerImages;
use App\Models\Complaint;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Response;
use Dompdf\Dompdf;
use Mail;
use PDF;

class AdminController extends Controller
{

    public function index(Request $request)
    {

        if (Auth::user() != null && (Auth::user()->user_type == 'admin')) {
            $pendingOrdersCount    = Order::count();
            $newComplaintsCount    = Complaint::count();
            $newReviewsCount       = Review::count();
            $newUsersCount         = User::count();
            $productCount          = Product::count();
            $couponCount           = Coupon::count();
            return view("admin.dashboard", compact(
                'pendingOrdersCount',
                'newComplaintsCount',
                'newReviewsCount',
                'newUsersCount',
                'productCount',
                'couponCount'
            ));
        } else {
            return redirect()->route('admin');
        }
    }


    public function admin_login(Request $request)
    {
        $email    = $request->email;
        $password = $request->password;

        $admin          = User::where('email', $email)->where('user_type', 'admin')->first();

        if ($admin) {
            $hashedPassword = $admin->password;
            if (Hash::check($password, $hashedPassword)) {
                Auth::login($admin);
                return redirect()->route('admin.dashboard')->with('success', 'Login successful..!');;
            } else {
                return redirect()->route('admin')->with('error', 'Invalid password.');
            }
        } else {
            return redirect()->route('admin')->with('error', 'User not Found');
        }
    }



    public function adminProfile(Request $request)
    {
        $user = User::where('user_type', 'admin')->where('id', '1')->first();
        return view('admin.profile', compact('user'));
    }


    public function adminProfileUpdate(Request $request)
    {
        $user = User::where('user_type', 'admin')->where('id', '1')->first();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $fileName);
            $user->image_name = $fileName;
        }

        $user->save();
        if ($user) {
            return redirect()->route('admin.profile')->with('success', 'Profile has been updated successfully')->with('refresh', true);
        }
    }



    public function adminHomeSection(Request $request)
    {
        $section1 = Section::where('id', '1')->first();
        $section2 = Section::where('id', '2')->first();


        $categories = ProductCategory::get();
        $subcategories = ProductSubCategory::where('category_id', $section1->category)->get();

        return view('admin.home_section', compact('section1', 'section2', 'categories', 'subcategories'));
    }


    public function adminHomeSectionUpdate(Request $request)
    {

        // print_r($request->all()); exit();

        if ($request->category_id || $request->subcategory || $request->hasFile('image')) {
            $Section1               = Section::where('id', '1')->first();
            $Section1->category     = $request->category_id;
            $Section1->sub_category = $request->subcategory;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $fileName);
                $Section1->image_name = $fileName;
            }

            $Section1->save();
        }

        if ($request->category_id2 || $request->subcategory2 || $request->hasFile('image2')) {
            $section2               = Section::where('id', '2')->first();
            $section2->category     = $request->category_id2;
            $section2->sub_category = $request->subcategory2;

            if ($request->hasFile('image2')) {
                $image = $request->file('image2');
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $fileName);
                $section2->image_name = $fileName;
            }

            $section2->save();
        }



        if ($Section1 || $section2) {
            return redirect()->route('admin.home.section')->with('success', 'Section has been updated successfully')->with('refresh', true);
        }
    }


    public function admin_logout(Request $request)
    {
        Auth::logout();

        return redirect()->route('admin')->with('success', 'You have been logged out.');
    }



    public function bannerImage(Request $request)
    {
        $banner_images = BannerImages::latest()->get();

        return view('admin.banner.index', compact('banner_images'));
    }


    public function bannerImageDelete($id)
    {
        $banner = BannerImages::findOrFail($id);

        $banner->delete();

        return redirect()->back()->with('danger', 'Banner image deleted successfully.');
    }


    public function bannerImageAdd(Request $request)
    {
        // ── Server-side dimension validation ─────────────────────────────
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $image    = $request->file('image');
            $imgInfo  = getimagesize($image->getRealPath());

            if (!$imgInfo) {
                return response()->json(['message' => 'Could not read the image file.'], 422);
            }

            [$width, $height] = $imgInfo;

            if ($width !== 1920 || $height !== 550) {
                return response()->json([
                    'message' => "Image must be exactly 1920 × 550 px. Uploaded image is {$width} × {$height} px."
                ], 422);
            }

            $fileName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('banner_images'), $fileName);

            $banner          = new BannerImages();
            $banner->image   = $fileName;
            // banner_link removed
            $banner->save();

            return response()->json(['message' => 'Banner image has been added successfully.']);
        }

        return response()->json(['message' => 'Please upload an image.'], 422);
    }


    public function bannerImageUpdate(Request $request)
    {
        $banner = BannerImages::findOrFail($request->id);  // ← uses id from request

        if ($request->hasFile('image')) {
            $image   = $request->file('image');
            $imgInfo = getimagesize($image->getRealPath());

            if (!$imgInfo) {
                return response()->json(['message' => 'Could not read the image file.'], 422);
            }

            [$width, $height] = $imgInfo;

            if ($width !== 1920 || $height !== 550) {
                return response()->json([
                    'message' => "Image must be exactly 1920 × 550 px. Got {$width} × {$height} px."
                ], 422);
            }

            // Delete old file
            $oldPath = public_path('banner_images/' . $banner->image);
            if ($banner->image && file_exists($oldPath)) {
                unlink($oldPath);
            }

            $fileName      = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('banner_images'), $fileName);
            $banner->image = $fileName;
        }

        $banner->save();

        return response()->json(['message' => 'Banner image updated successfully.']);
    }




    public function regUsers(Request $request)
    {
        $users = User::where('user_type', 'user')->get();
        return view('admin.user.index', compact('users'));
    }


    public function regUserPreview($id)
    {
        $user = User::where('user_type', 'user')->where('id', $id)->first();
        return view('admin.user.view', compact('user'));
    }


    public function regUserDelete($id)
    {
        // print_r($request->all()); exit();
        $upload = User::find($id);

        if ($upload) {
            $upload->delete();

            return redirect()->back()->with('success', 'User Deleted successfully')->with('refresh', true);
        } else {
            return redirect()->back()->with('success', 'Something Wrong')->with('refresh', true);
        }
    }

    public function reviews()
    {
        $reviews = Review::get();
        return view('admin.order.review', compact('reviews'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:reviews,id',
            'command' => 'required|string|max:1000',
            'star_count' => 'required|integer|min:1|max:5',
        ]);

        $review = Review::findOrFail($request->id);
        $review->command = $request->command;
        $review->star_count = $request->star_count;
        $review->save();

        return back()->with('success', 'Review updated successfully.');
    }

    public function delete(Request $request)
    {
        $request->validate(['id' => 'required|integer|exists:reviews,id']);
        Review::findOrFail($request->id)->delete();

        return back()->with('danger', 'Review deleted successfully.');
    }
}
