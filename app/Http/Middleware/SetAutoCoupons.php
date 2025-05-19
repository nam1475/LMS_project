<?php

namespace App\Http\Middleware;

use App\Models\Coupon;
use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetAutoCoupons
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Session::has('auto_coupons')) {
            $autoCoupons = Coupon::where('option', 'auto')
                ->where('status', 1)
                ->where('expire_date', '>=', now())
                ->with('courseCategories:id') 
                ->get();

            // $mapping = [];
            // foreach ($autoCoupons as $coupon) {
            //     foreach ($coupon->courseCategories as $category) {
            //         $mapping[$category->id] = $coupon->code;
            //     }
            // }
            // Session::put('auto_coupons', $mapping);
            
            Session::put('auto_coupons', $autoCoupons);
        }

        return $next($request);
    }
}
