<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Course;
use Barryvdh\Debugbar\Facades\Debugbar;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    //

    function index(Request $request) : View
    {
      $couponObject = new Coupon();
      $coupon = null;
      if($request->has('coupon_code')){
        $coupon = $couponObject->findCouponByCode($request->coupon_code);
      }
      $coupons = $couponObject->where('status', 1)->where('expire_date', '>=', Carbon::now())->get();
      
      $cart = Cart::with(['course'])->where('user_id', user()->id)->paginate(); 
      return view('frontend.pages.cart', [
        'cart' => $cart,
        'coupons' => $coupons,
        'coupon' => $coupon
      ]);
    }

    function applyCoupon(Request $request)
    {
      $request->validate([
        'coupon_code' => 'required|string|max:255',
      ]);
      $couponObject = new Coupon();
      $courseTotalPrice = $request->total_price;
      $couponCode = $request->coupon_code;
      $coupon = $couponObject->findCouponByCode($request->coupon_code);
      $cart = Cart::with(['course'])->where('user_id', user()->id)->get();
      $courseCategoryIds = collect($cart)->pluck('course.category_id')->unique()->toArray();      
      // Kiểm tra tất cả category_id đều hợp lệ với coupon
      $isCourseCategoriesMatched = collect($courseCategoryIds)->diff(
        $coupon->courseCategories()->pluck('course_categories.id')
      )->isEmpty();
      if(!$coupon){
        notyf()->error('Invalid Coupon Code!');
        return redirect()->back();
      }
      else{
        if($courseTotalPrice < $coupon->minimum_order_amount){
          notyf()->error('Your order amount is less than ' . number_format($coupon->minimum_order_amount) . '!');
          return redirect()->route('cart.index');
        }
        else if(!$isCourseCategoriesMatched){
          notyf()->error('Coupon Code does not match one of your course categories!');
          return redirect()->route('cart.index');
        }
        else{
          notyf()->success('Coupon Code Applied Successfully!');
          return redirect()->route('cart.index', ['coupon_code' => $couponCode]);
        }
      }
    }

    function addToCart(int $id) : Response
    {
      if(!Auth::guard('web')->check()){
        return response(['message' => 'Please Login First!'], 401);
      }

      if(auth('web')->user()->enrollments()->where(['course_id' => $id])->exists()){
        return response(['message' => 'Already Enrolled!'], 401);
      }
      
      if(Cart::where(['course_id' => $id, 'user_id' => Auth::guard('web')->user()->id])->exists()){
          return response(['message' => 'Already Added!'], 401);
      }
      
      if(user()?->role == 'instructor') {
        return response(['message' => 'Please use a user account for add to cart!'], 401);
      }

      $course = Course::findOrFail($id);
      $cart = new Cart();
      $cart->course_id = $course->id;
      $cart->user_id = Auth::guard('web')->user()->id;
      $cart->save();

      $cartCount = cartCount();

      return response(['message' => 'Added Successfully!', 'cart_count' => $cartCount], 200);

    }

    function removeFromCart(int $id) : RedirectResponse
    {
        $cart = Cart::where(['id' => $id, 'user_id' => user()->id])->firstOrFail();
        $cart->delete();
        notyf()->success('Removed Successfully!');
        return redirect()->back();
    }

    
}
