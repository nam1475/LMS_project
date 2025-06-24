<?php

namespace App\Service;

use App\Models\Cart;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Notifications\StudentEnrolledCourse;

class OrderService
{

    static function storeOrder(
        string $transaction_id,
        int $buyer_id,
        string $status,
        float $total_amount,
        float $paid_amount,
        string $currency,
        string $payment_method,
    ) {
        try {
            $order = new Order();
            $order->invoice_id = uniqid();
            $order->buyer_id = $buyer_id;
            $order->status = $status;
            $order->total_amount = session('total_amount') ?? $total_amount;
            $order->subtotal_amount = session('subtotal_amount') ?? $total_amount;
            $order->paid_amount = $paid_amount;
            $order->currency = $currency;
            $order->payment_method = $payment_method;
            $order->transaction_id = $transaction_id;
            $order->has_coupon = session()->has('coupon_code') ? 1 : 0;
            $order->coupon_code = session('coupon_code') ?? null;
            $order->coupon_amount = session('discount_amount') ?? null;
            $order->save();

            /** store order items */
            $cart = Cart::with('course')->where('user_id', $buyer_id);
            $cartItems = $cart->get();
            foreach ($cartItems as $item) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->price = $item->course->discount > 0 ? $item->course->discount : $item->course->price;
                $orderItem->course_id = $item->course->id;
                $orderItem->commission_rate = config('settings.commission_rate');
                $orderItem->save();

                /** store enrollment */
                $enrollment = new Enrollment();
                $enrollment->user_id = $buyer_id;
                $enrollment->course_id = $item->course->id;
                $enrollment->instructor_id = $item->course->instructor_id;
                $enrollment->save();

                /** add commission to instructor wallet */
                $instructorWallet = $item->course->instructor;
                $instructorWallet->wallet += calculateCommission($item->course->discount > 0 ? $item->course->discount : $item->course->price, config('settings.commission_rate'));
                $instructorWallet->save();

                // Notify instructor that a student has enrolled a course
                $item->course->instructor->notify(new StudentEnrolledCourse($item->course, $item->user, $item->course->instructor));
            }

            /** delete cart items */
            $cart->delete();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
