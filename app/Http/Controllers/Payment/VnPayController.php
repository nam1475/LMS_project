<?php

namespace App\Http\Controllers\Payment;

use App\Service\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VnPayController extends \App\Http\Controllers\Controller
{
    public function createPayment(Request $request)
    {
        try{
            DB::beginTransaction();
            
            // Fetch cart items for the authenticated user
            $user = \Illuminate\Support\Facades\Auth::user();
            $cartItems = \App\Models\Cart::with('course')->where('user_id', $user->id)->get();
            $cartTotal = 0;

            foreach ($cartItems as $item) {
                $cartTotal += $item->course->discount > 0 ? $item->course->discount : $item->course->price;
            }

            if(session()->has('discount_amount')) {
                $cartTotal = $cartTotal - session()->get('discount_amount');
            }

            if ($cartTotal <= 0) {
                return redirect()->route('checkout.index')->with('error', 'Your cart is empty.');
            }
            
            /** Chỉ nên gọi env() trong các file config, nếu gọi trong controller, class thông thường,... nó 
             * sẽ trả về null nếu đã chạy php artisan config:cache trước đó. */
            $vnp_TmnCode = config('gateway_settings.vnpay_tmn_code');
            $vnp_HashSecret = config('gateway_settings.vnpay_hash_secret');
            $vnp_Url = config('gateway_settings.vnpay_url');
            $vnp_Returnurl = config('vnpay.vnp_returnUrl');

            $vnp_TxnRef = rand(1,100000); // Order ID
            $vnp_OrderInfo = 'Pay for the order ' . $user->email . ' | Quantity: ' . $cartItems->count();
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
            DB::commit();

            return redirect($vnp_Url);
        }catch(\Exception $e){
            DB::rollBack();
            Log::error('VNPay error:' . $e);
            return redirect()->route('checkout.index')->with('error', 'VNPay error!');
        }
    }


    public function vnpayReturn(Request $request)
    {
        try{
            DB::beginTransaction();
            $vnp_HashSecret = config('vnpay.vnp_hashSecret');
            $inputData = $request->all();
            $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';

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
                $user = Auth::user('web');

                $mainAmount = $request->vnp_Amount / 100;
                $paidAmount = session('subtotal_amount') ?? $request->vnp_Amount / 100;
                $currency = 'VND';

                // Store order and enrollments using OrderService
                OrderService::storeOrder(
                    $request->vnp_TransactionNo ?? $request->vnp_TxnRef,
                    $user->id,
                    'approved',
                    $mainAmount,
                    $paidAmount,
                    $currency,
                    'vnpay',
                );

                session()->forget('total_amount');
                session()->forget('coupon_code');
                session()->forget('discount_amount');
                session()->forget('subtotal_amount');

                notyf()->success('Payment successful! You are now enrolled in the course.');

                DB::commit();
                return redirect()->route('order.success');
            } else {
                // Payment failed
                notyf()->error('Payment failed! Please try againnnnnnn.');
                return redirect()->route('order.failed');
            }
        }catch(\Exception $e){
            DB::rollBack();
            Log::error('VNPay error:' . $e);
            notyf()->error('Payment failed! Please try again.');
            return redirect()->route('order.failed');
        }
    }
}
