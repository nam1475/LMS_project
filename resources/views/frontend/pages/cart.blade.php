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
            <div class="row justify-content-between mt-4">
                <div class="col-xxl-7 col-md-5 col-lg-6 wow fadeInUp"
                    style="visibility: visible; animation-name: fadeInUp;">
                        <form action="{{ route('cart.index') }}" class="">  
                            <input type="text" name="coupon_code" value="{{ request('coupon_code') }}" class="col-md-4 border border-2" style="text-transform: uppercase;" placeholder="Enter coupon code">
                            <button type="submit" class="common_btn">Apply Code</button>
                        </form>
                </div>
                <div class="col-xxl-4 col-md-7 col-lg-6 wow fadeInUp"
                    style="visibility: visible; animation-name: fadeInUp;">
                        <?php
                            $originalPrice = cartTotal();
                            $totalPrice = cartTotal($couponCode);
                            $discountAmount = $originalPrice - $totalPrice;
                        ?>
                    <div class="card card-custom">
                        <h5 class="mb-4 fw-bold">Summary</h5>
                
                        <div class="d-flex justify-content-between mb-2">
                            <span>Original Price</span>
                            <span class="price text-muted">đ{{ number_format($originalPrice) }}</span>
                        </div>
                
                        <hr>
                        
                        @if($couponCode)
                            <div class="d-flex justify-content-between mb-1">
                                <span>Coupon code<br><small class="text-muted">({{ $couponCode }})</small></span>
                                <span class="discount">
                                    - đ{{ number_format($discountAmount) }}
                                    <span class="pro_icon ">
                                        <a href="{{ route('cart.index') }}"><i class="fal fa-times text-secondary" aria-hidden="true"></i></a>
                                    </span>
                                </span>
                            </div>
                            <hr>
                        @endif
                
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold">Total:</span>
                            <span class="fw-bold fs-5">đ{{ number_format($totalPrice) }}</span>
                        </div>

                        {{-- Nếu $couponCode rỗng/null thì sẽ bị loại bỏ  --}}
                        <a class="common_btn" href="{{ route('checkout.index', array_filter(['coupon_code' => $couponCode])) }}" >
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
