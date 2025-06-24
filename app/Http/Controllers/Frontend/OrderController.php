<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //

    function index(Request $request) : View
    {
        $orderItems = OrderItem::with(['course', 'order.customer'])
            ->when($request->has('search') && $request->filled('search'), function($query) use ($request) {
                $query->whereHas('course', function($query) use ($request) {
                    $query->where('title', 'like', '%' . $request->search . '%');
                })
                ->orWhereHas('order.customer', function($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%');
                })
                ->orWhereHas('order', function($query) use ($request) {
                    $query->where('invoice_id', $request->search);
                });
            })
            ->whereHas('course', function($query) {
                $query->where('instructor_id', user()->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return view('frontend.instructor-dashboard.order.index', compact('orderItems'));     
    }

    function show(string $invoiceId) : View 
    {
        $order = Order::with(['customer', 'orderItems'])->where('invoice_id', $invoiceId)->firstOrFail();
        return view('frontend.instructor-dashboard.order.show', compact('order'));     
    }
}
