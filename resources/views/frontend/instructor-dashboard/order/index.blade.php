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
                            </div>
                        </div>

                        <form action="{{ route('instructor.orders.index') }}" class="wsus__dash_course_searchbox">
                            <div class="input">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search our Courses">
                                <button><i class="far fa-search"></i></button>
                            </div>

                            {{-- <div class="selector">
                                <select class="select_js">
                                    <option value="" disabled selected>Status</option>
                                    <option value="all" @selected(request('status') == 'all')>All</option>
                                    <option value="approved" @selected(request('status') == 'approved')>Approved</option>
                                    <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                                    <option value="rejected" @selected(request('status') == 'rejected')>Rejected</option>
                                </select>
                            </div> --}}

                            {{-- <div>
                                <button type="submit" class="common_btn">
                                    <i class="fas fa-filter"></i>
                                    Filter
                                </button>
                                <a href="{{ route('instructor.coupons.index') }}" class="common_btn">
                                    <i class="fas fa-redo"></i>                                    
                                    Reset
                                </a>
                            </div> --}}
                            
                        </form>

                        <table class="table">
                            <thead>
                                <th>Invoice ID</th>
                                <th>Course</th>
                                <th>Purchase By</th>
                                <th>Price</th>
                                <th>Commission</th>
                                <th>Earning</th>
                                <th>Action</th>

                            </thead>
                            <tbody>
                                @forelse($orderItems as $orderItem)
                                    <tr>
                                        <td>{{ $orderItem->order->invoice_id }}</td>
                                        <td>
                                            <a href="{{ route('courses.show', $orderItem->course->slug) }}">
                                                {{ $orderItem->course->title }}
                                            </a>
                                        </td>
                                        <td>
                                            <div>
                                                {{ $orderItem->order->customer->name }}
                                            </div>
                                            <div class="text-muted">
                                                {{ $orderItem->order->customer->email }}
                                            </div>
                                        </td>
                                        <td>{{ number_format($orderItem->price) }} {{ $orderItem->order->currency }}</td>
                                        <td>{{ $orderItem->commission_rate ?? 0 }}%</td>
                                        <td>{{ number_format(calculateCommission($orderItem->price, $orderItem->commission_rate)) }}
                                            {{ $orderItem->order->currency }}</td>
                                        <td>
                                            <a href="{{ route('instructor.orders.show', $orderItem->order->invoice_id) }}">view</a>
                                        </td>

                                    </tr>
                                @empty

                                    <tr>
                                        <td>No Data Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>

                </div>

            </div>
        </div>
    </section>
@endsection
