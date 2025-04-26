<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $couponCode = $request->coupon_code ?? '';
        $cart = Cart::with(['course'])->where('user_id', auth('web')->user()->id)->get(); 
        return view('frontend.pages.checkout-page', compact('cart', 'couponCode'));
    }
}
