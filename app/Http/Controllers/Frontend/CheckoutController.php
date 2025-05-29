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
        $cart = Cart::with(['course'])->where('user_id', auth('web')->user()->id); 
        $isCartEmpty = $cart->count() == 0;
        if($isCartEmpty){
            notyf()->error('Cant checkout, your cart is empty!');
            return redirect()->route('cart.index');
        }
        $cartItems = $cart->get();
        $couponCode = session('coupon_code');
        $discountAmount = session('discount_amount');
        $subtotalAmount = session('subtotal_amount');
        $originalAmount = cartTotal();
        return view('frontend.pages.checkout-page', compact('cartItems', 'couponCode', 'discountAmount', 'subtotalAmount', 'originalAmount'));
    }
}
