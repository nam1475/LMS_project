<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VnPayController extends \App\Http\Controllers\Controller
{
    public function createPayment(Request $request)
    {
        // Fetch cart items for the authenticated user
        $user = \Illuminate\Support\Facades\Auth::user();
        $cartItems = \App\Models\Cart::with('course')->where('user_id', $user->id)->get();
        $cartTotal = 0;

        foreach ($cartItems as $item) {
            $cartTotal += $item->course->discount > 0 ? $item->course->discount : $item->course->price;
        }

        if ($cartTotal <= 0) {
            return redirect()->route('checkout.index')->with('error', 'Your cart is empty.');
        }

        $vnp_TmnCode = env('VNP_TMN_CODE');
        $vnp_HashSecret = env('VNP_HASH_SECRET');
        $vnp_Url = env('VNP_URL');
        $vnp_Returnurl = env('VNP_RETURN_URL');

        $vnp_TxnRef = rand(1,100000); // Order ID
        $vnp_OrderInfo = 'Thanh toán đơn hàng cho ' . $user->email . ' | Số lượng: ' . $cartItems->count();
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $cartTotal * 100; // Amount in VND * 100
        $vnp_Locale = 'vn';
        $vnp_BankCode = $request->input('bank_code', '');
        $vnp_IpAddr = $request->ip();

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        if ($vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = [];
        $hashdata = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query[] = urlencode($key) . "=" . urlencode($value);
        }
        $vnp_Url = $vnp_Url . "?" . implode('&', $query);
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= '&vnp_SecureHash=' . $vnpSecureHash;

        return redirect($vnp_Url);
    }


    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = env('VNP_HASH_SECRET');
        $inputData = $request->all();
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';

        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        // Debug log
        Log::info('VNPay Return', [
        'input' => $inputData,
        'vnp_SecureHash' => $vnp_SecureHash,
        'calculated_hash' => $secureHash,
        'response_code' => $request->vnp_ResponseCode,
        ]);

        if (strtoupper($secureHash) === strtoupper($vnp_SecureHash) && $request->vnp_ResponseCode == '00') {
            // Payment successful

            // Enroll user in courses
            $user = \Illuminate\Support\Facades\Auth::user();
            $cartItems = \App\Models\Cart::with('course')->where('user_id', $user->id)->get();

            foreach ($cartItems as $item) {
                // Avoid duplicate enrollments
                $alreadyEnrolled = \App\Models\Enrollment::where('user_id', $user->id)
                    ->where('course_id', $item->course->id)
                    ->exists();

                if (!$alreadyEnrolled) {
                    \App\Models\Enrollment::create([
                        'user_id' => $user->id,
                        'course_id' => $item->course->id,
                        'instructor_id' => $item->course->instructor_id,
                        'have_access' => true,
                    ]);
                }
            }

            // Clear the cart after enrollment
            \App\Models\Cart::where('user_id', $user->id)->delete();

            return redirect()->route('order.success')->with('success', 'Thanh toán thành công! Bạn đã được ghi danh vào khóa học.');
        } else {
            // Payment failed
            return redirect()->route('order.failed')->with('error', 'Thanh toán thất bại!');
        }
    }
}
