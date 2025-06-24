<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class StudentOrderController extends Controller
{

    function index() : View
    {
        $orders = Order::where('buyer_id', user()->id)->orderBy('created_at', 'desc')->paginate(25);
        return view('frontend.student-dashboard.order.index', compact('orders'));     
    }

    function show($invoiceId) : View 
    {
        $order = Order::where(['buyer_id' => user()->id, 'invoice_id' => $invoiceId])->firstOrFail();
        return view('frontend.student-dashboard.order.show', compact('order'));     
    }
}
