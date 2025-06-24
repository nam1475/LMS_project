<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Spatie\LaravelPdf\Facades\Pdf;
// use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\Browsershot\Browsershot;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    function index(Request $request) : View
    {
        $orders = Order::with(['customer'])
            ->when($request->has('search') && $request->filled('search'), function($query) use ($request) {
                $query->whereHas('customer', function($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
                })
                ->orWhere('invoice_id', $request->search);
            })
            ->when($request->has('status') && $request->filled('status'), function($query) use ($request) {
                if($request->status == 'all'){
                    return $query;
                }
                $query->where('status', $request->status);
            })
            ->orderBy('created_at', 'desc')->paginate(25);
        return view('admin.order.index', compact('orders'));    
    }

    function show($invoiceId){
        $order = Order::where('invoice_id', $invoiceId)->firstOrFail();
        // return Browsershot::url(route('admin.orders.download-invoice', $order->invoice_id))
        //     ->noSandbox()
        //     ->save(storage_path("app/public/{$order->invoice_id}.pdf"));
        return view('admin.order.show', compact('order')); 
    }

    // function downloadInvoice($invoiceId) : View {
    //     $order = Order::where('invoice_id', $invoiceId)->firstOrFail();
    //     return view('admin.order.show', compact('order')); 
    // }
}
