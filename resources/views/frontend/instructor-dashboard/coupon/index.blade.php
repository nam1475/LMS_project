@extends('frontend.layouts.master')

@section('content')
    <!--===========================
                        BREADCRUMB START
                    ============================-->
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
    <!--===========================
                                    BREADCRUMB END
                                ============================-->


    <!--===========================
            DASHBOARD coupon START
        ============================-->
    <section class="wsus__dashboard mt_90 xs_mt_70 pb_120 xs_pb_100">
        <div class="container">
            <div class="row">
                @include('frontend.instructor-dashboard.sidebar')

                <div class="col-xl-9 col-md-8 wow fadeInRight">
                    <div class="wsus__dashboard_contant">
                        <div class="wsus__dashboard_contant_top">
                            <div class="wsus__dashboard_heading relative">
                                <h5>Coupons</h5>
                                <p>Manage your coupons and its update like live, draft and insight.</p>
                                <a class="common_btn" href="{{ route('instructor.coupons.create') }}">+ add coupon</a>
                            </div>
                        </div>

                        {{-- <form action="#" class="wsus__dash_coupon_searchbox">
                            <div class="input">
                                <input type="text" placeholder="Search our coupons">
                                <button><i class="far fa-search"></i></button>
                            </div>
                            <div class="selector">
                                <select class="select_js">
                                    <option value="">Choose</option>
                                    <option value="">Choose 1</option>
                                    <option value="">Choose 2</option>
                                </select>
                            </div>
                        </form> --}}

                        <div class="wsus__dash_course_table">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th class="image">
                                                        CODE
                                                    </th>
                                                    <th class="details">
                                                        TYPE
                                                    </th>
                                                    <th class="sale">
                                                        VALUE
                                                    </th>
                                                    <th class="status">
                                                        MIN ORDER AMOUNT
                                                    </th>
                                                    <th class="action">
                                                        COURSE CATEGORIES
                                                    </th>
                                                    <th class="action">
                                                        EXPIRE DATE
                                                    </th>
                                                    <th class="status">
                                                        STATUS
                                                    </th>
                                                    <th class="status">
                                                        APPROVE
                                                    </th>
                                                    <th class="action">
                                                        ACTION
                                                    </th>
                                                </tr>

                                                @forelse ($coupons as $coupon)
                                                    <tr>
                                                        <td class="sale">{{ $coupon->code }}</td>
                                                        <td class="sale">{{ $coupon->type }}</td>
                                                        <td class="sale">
                                                            {{ $coupon->type == 'percent' ? $coupon->value . '%' : number_format($coupon->value) . 'đ' }}
                                                        </td>
                                                        <td class="sale">{{ number_format($coupon->minimum_order_amount) . 'đ' }}</td>
                                                        <td class="badge-blue">
                                                            @foreach ($coupon->courseCategories as $category)
                                                                <p class="">{{ $category->name }}</p>
                                                            @endforeach
                                                        </td>
                                                        <td class="sale">{{ $coupon->expire_date }}</td>
                                                        <td class="status">
                                                            @if ($coupon->status == '1')
                                                                <p class="active">Active</p>
                                                            @else
                                                                <p class="inactive">Inactive</p>
                                                            @endif
                                                        </td>
                                                        <td class="status">
                                                            @if ($coupon->is_approved == 'approved')
                                                                <p class="active">Approved</p>
                                                            @elseif ($coupon->is_approved == 'pending')
                                                                <p class="pending">Pending</p>
                                                            @elseif ($coupon->is_approved == 'rejected')
                                                                <p class="inactive">Rejected</p>
                                                            @endif
                                                        </td>

                                                        <td class="action">
                                                            <a class="edit"
                                                                href="{{ route('instructor.coupons.edit', $coupon->id) }}"><i
                                                                    class="far fa-edit"></i></a>
                                                            <a class="del delete-item" href="{{ route('instructor.coupons.destroy', $coupon->id) }}"><i
                                                                    class="fas fa-trash-alt"></i></a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">No Data Found!</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--===========================
            DASHBOARD coupon END
        ============================-->
@endsection
