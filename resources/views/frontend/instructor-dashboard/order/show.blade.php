@extends('frontend.layouts.master')

@section('content')
    <section class="wsus__breadcrumb" style="background: url({{ asset(config('settings.site_breadcrumb')) }});">
        <div class="wsus__breadcrumb_overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12 wow fadeInUp">
                        <div class="wsus__breadcrumb_text">
                            <h1>Instructor Dashboard</h1>
                            <ul>
                                <li><a href="{{ url('/') }}">Home</a></li>
                                <li>Instructor Dashboard</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="wsus__dashboard mt_90 xs_mt_70 pb_120 xs_pb_100">
        <div class="container">
            <div class="row">
                @include('frontend.instructor-dashboard.sidebar')
                <div class="col-xl-9 col-md-8 wow fadeInRight" style="visibility: visible; animation-name: fadeInRight;">
                    <div class="wsus__dashboard_contant">
                        <div class="wsus__dashboard_contant_top">
                            <div class="wsus__dashboard_heading relative">
                                <h5>Orders</h5>
                                <p>Manage your Orders from here.</p>
                            </div>
                            {{-- <div class="d-flex justify-content-end">
                                <button type="button" class="common_btn" onclick="javascript:window.print();">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="icon">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2">
                                        </path>
                                        <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4"></path>
                                        <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z">
                                        </path>
                                    </svg>
                                    Print Invoice
                                </button>
                            </div> --}}
                        </div>
                        <div class="wsus__dash_course_table">

                            <div class="card card-lg border-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <p class="h3">Company</p>
                                            <address>
                                               {{ config('settings.site_name') }}<br>
                                                {{ config('settings.phone') }}<br>
                                                {{ config('settings.location') }}<br>
                                                
                                            </address>
                                        </div>
                                        <div class="col-6 text-end">
                                            <p class="h3">Client</p>
                                            <address>
                                                {{ $order->customer->name }}<br>
                                                {{ $order->customer->email }}
                                            </address>
                                        </div>
                                        <div class="col-12 my-5">
                                            <h4>Invoice #{{ $order->invoice_id }}</h4>
                                        </div>
                                    </div>
                                    <table class="table table-transparent table-responsive">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 1%"></th>
                                                <th>Product</th>
                                                <th class="text-center" style="width: 1%">Qnt</th>
                                                <th class="text-end" style="width: 4%">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($order->orderItems as $item)
                                            <tr>
                                                <td class="text-center">1</td>
                                                <td>
                                                    <p class="strong mb-1">{{ $item->course->title }}</p>
                                                    <div class="text-secondary">By {{ $item->course->instructor->name }}</div>
                                                </td>
                                                <td class="text-center">
                                                    1
                                                </td>
                                                <td class="text-end">{{ number_format($item->price) }}</td>
                                            </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="3" class="strong text-end">Subtotal</td>
                                                <td class="text-end">{{ number_format($order->total_amount) }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="strong text-end">Paid Amount</td>
                                                <td class="text-end">{{ number_format($order->paid_amount) }} {{ $order->currency }}</td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                    <p class="text-secondary text-center mt-5">Thank you very much for doing business with us. We look
                                        forward to working with
                                        you again!</p>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </section>
@endsection
