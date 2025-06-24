<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PaymentSettingController extends Controller
{
    //
    function index() : View
    {
        return view('admin.payment-setting.index');     
    }

    function vnpaySetting(Request $request) : RedirectResponse 
    {
        $validatedData = $request->validate([
            'vnpay_tmn_code' => ['required'],
            'vnpay_hash_secret' => ['required'],
            'vnpay_url' => ['required'],
            'vnpay_status' => ['required'],
        ]);
        
        foreach($validatedData as $key => $value) {
            PaymentSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Cache::forget('gatewaySettings');

        notyf()->success("Update Successfully!");

        return redirect()->back();
    }

    // function stripeSetting(Request $request) : RedirectResponse 
    // {
    //     $validatedData = $request->validate([
    //         'stripe_status' => ['required', 'in:active,inactive'],
    //         'stripe_currency' => ['required'],
    //         'stripe_rate' => ['required'],
    //         'stripe_publishable_key' => ['required'],
    //         'stripe_secret' => ['required'],
    //     ]);
        
    //     foreach($validatedData as $key => $value) {
    //         PaymentSetting::updateOrCreate(['key' => $key], ['value' => $value]);
    //     }

    //     Cache::forget('gatewaySettings');

    //     notyf()->success("Update Successfully!");

    //     return redirect()->back();
    // }

}
