@extends('frontend.layouts.master')

@section('content')
<style>
    .card-custom {
        max-width: 400px;
        margin: 40px auto;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
    }
    .price {
        font-weight: bold;
    }
    .discount {
        color: red;
    }
    .card-custom a{
        width: 100%;
        text-align: center;
    }
</style>
    <!--===========================
        BREADCRUMB START
    ============================-->
    <section class="wsus__breadcrumb" style="background: url({{ asset(config('settings.site_breadcrumb')) }});">
        <div class="wsus__breadcrumb_overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12 wow fadeInUp">
                        <div class="wsus__breadcrumb_text">
                            <h1>Shopping Cart</h1>
                            <ul>
                                <li><a href="#">Home</a></li>
                                <li>Shopping Cart</li>
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
        CART VIEW START
    ============================-->
    <section class="wsus__cart_view mt_120 xs_mt_100 pb_120 xs_pb_100">
        @if(count($cart) > 0)
        <div class="container">
            <div class="row">
                <div class="col-12 wow fadeInUp">
                    <div class="cart_list">
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="pro_img">Product</th>

                                        <th class="pro_name"></th>

                                        <th class="pro_tk">Price</th>

                                        <th class="pro_icon">Remove</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($cart as $item)
                                    <tr>
                                        <td class="pro_img">
                                            <img src="{{ asset($item->course->thumbnail) }}" alt="product"
                                                class="img-fluid w-100">
                                        </td>

                                        <td class="pro_name">
                                            <a href="{{ route('courses.show', $item->course->slug) }}">{{ $item->course->title }}</a>
                                        </td>
                                        <td class="pro_tk">
                                            @if($item->course->discount > 0)
                                                <del><h6>đ{{ number_format($item->course->price) }}</h6></del> <h6>đ{{ number_format($item->course->discount) }}</h6>
                                            @else
                                                <h6>đ{{ number_format($item->course->price) }}</h6>
                                            @endif
                                        </td>
                                        <td class="pro_icon">
                                            <a href="{{ route('remove-from-cart', $item->id) }}"><i class="fal fa-times" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                    @empty
                                    <p>No data Found</p>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-end mt-4">
                <div class="col-xxl-4 col-md-7 col-lg-6 wow fadeInUp"
                    style="visibility: visible; animation-name: fadeInUp;">
                        <?php
                            $originalPrice = cartTotal();
                            $totalPriceWithCoupon = 0;
                            
                        ?>
                    <div class="card card-custom">
                        <h5 class="mb-4 fw-bold">Summary</h5>
                
                        <div class="d-flex justify-content-between mb-2">
                            <span>Original Price</span>
                            <span class="price text-muted">đ{{ number_format($originalPrice) }}</span>
                        </div>
                
                        <hr>
                        
                        @if($coupon)
                            @php
                                $totalPriceWithCoupon = cartTotal($coupon->code);
                                $discountAmount = $originalPrice - $totalPriceWithCoupon;
                            @endphp
                            <div class="d-flex justify-content-between mb-1">
                                <span>Coupon code<br><small class="text-muted">({{ $coupon->code }})</small></span>
                                <span class="discount">
                                    - đ{{ number_format($discountAmount) }}
                                    <span class="pro_icon ">
                                        <a href="{{ route('cart.index') }}"><i class="fal fa-times text-secondary" aria-hidden="true"></i></a>
                                    </span>
                                </span>
                            </div>
                        @else
                            <button type="button" id="show_coupon_modal" class="common_btn">
                                Coupons
                            </button>

                            <div class="modal fade" id="coupon_modal" tabindex="-1" aria-labelledby="couponModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold" id="couponModalLabel">Your coupon code</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            {{-- <form action="{{ route('cart.index') }}" method="GET" class="d-flex mb-4"> --}}
                                            <form method="POST" action="{{ route('apply-coupon') }}" class="d-flex mb-4">
                                            {{-- <form method="POST" data-route="{{ route('apply-coupon') }}" data-total-price="{{ $totalPrice }}" class="d-flex mb-4"> --}}
                                                @csrf
                                                <input type="text" name="coupon_code" id="coupon_code_input" class="form-control me-2" placeholder="Enter your coupon code" value="{{ request('coupon_code') }}">
                                                <input type="hidden" name="original_price" value="{{ $originalPrice }}">
                                                <x-input-error :messages="$errors->get('coupon_code')" class="mt-2" />
                                                <button type="submit" class="btn btn-primary">Apply</button>
                                            </form>
                                            <h6 class="fw-bold mb-3">Coupon code can be applied</h6>
                                            @foreach ($coupons as $c)
                                                <div class="row p-3 border rounded coupon_code_card">
                                                    <div class="col-5">
                                                        <img src="{{ asset(config('settings.site_logo')) }}" alt="EduCore" class="img-fluid">
                                                    </div>
                                                    <div class="col-7">
                                                        <h6 id="coupon_code" data-code="{{ $c->code }}">{{ $c->code }}</h6>
                                                        <p class="mb-1 fw-bold">Discount {{ $c->type == 'percent' ? $c->value . '%' : 'đ' . number_format($c->value) }}</p>
                                                        <p class="mb-1 text-muted">Valid course categories: {{ $c->courseCategories()->pluck('name')->implode(', ') }}</p>
                                                        <p class="mb-1 text-muted">Min order's value {{ 'đ' . number_format($c->minimum_order_amount) }}</p>
                                                        <p class="mb-0 text-muted">Expire date: {{ $c->expire_date }} - 23:59</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <nav>
                                                <ul class="pagination mb-0">
                                                    {{-- <li class="page-item disabled">
                                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">&lt;</a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a class="page-link" href="#"></a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a class="page-link" href="#">&gt;</a>
                                                    </li> --}}
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold">Total:</span>
                            <span class="fw-bold fs-5">đ{{ number_format($totalPriceWithCoupon == 0 ? $originalPrice : $totalPriceWithCoupon) }}</span>
                        </div>

                        {{-- Nếu $coupon->code rỗng/null thì sẽ bị loại bỏ  --}}
                        <a class="common_btn" href="{{ route('checkout.index', array_filter(['coupon_code' => $coupon ? $coupon->code : null])) }}" >
                            Buy now
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="container text-center">
           <img style="width: 180px !important" src="{{ asset('default-files/empty-cart.png') }}" alt="">
           <h6 class="mt-2">Your cart is empty</h6>
           <a href="{{ route('home') }}" class="common_btn mt-3">Go Home</a>
        </div>
        @endif
    </section>
    <!--===========================
        CART VIEW END
    ============================-->
    

@endsection

{{-- @push('header_scripts')
    @vite(['resources/js/frontend/cart.js'])
@endpush --}}
