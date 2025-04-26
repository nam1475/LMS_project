@extends('frontend.layouts.master')


@section('content')
<style>
    .payment-option {
      border-radius: 10px;
      background-color: #e9ecef;
      padding: 20px;
      margin-bottom: 15px;
      position: relative;
    }
    
    .payment-option img, .payment-option svg {
      width: 40px;
      height: 40px;
      margin-bottom: 8px;
    }
    
    .form-check-input {
      position: absolute;
      top: 20px;
      right: 20px;
      width: 20px;
      height: 20px;
    }
    
    .course-image {
      width: 80px;
      height: 80px;
      border-radius: 8px;
    }
    
    .payment-title {
      font-size: 16px;
      color: #495057;
      margin-top: 5px;
    }
    
    .discount-price {
      color: blue;
      font-weight: bold;
    }
    
    .original-price {
      text-decoration: line-through;
      color: #6c757d;
      font-size: 14px;
      margin-left: 10px;
    }
  </style>
<section class="wsus__breadcrumb" style="background: url({{ asset(config('settings.site_breadcrumb')) }});">
    <div class="wsus__breadcrumb_overlay">
        <div class="container">
            <div class="row">
                <div class="col-12 wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
                    <div class="wsus__breadcrumb_text">
                        <h1>Checkout</h1>
                        <ul>
                            <li><a href="#">Home</a></li>
                            <li>Checkout</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="payment pt_95 xs_pt_75 pb_120 xs_pb_100">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-7 wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;"> 
                {{-- <h2 class="mb-3">Checkout course</h2>
                <p class="text-muted mb-4">We are committed to protecting your payment information.</p>
                <h5 class="mb-3">Select a payment methods</h5> --}}
                {{-- <div class="payment_area">
                    <div class="col-md-6">
                        <div class="card border shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <img src="{{ asset('images/vnpay.png') }}" alt="VNPay" class="me-3" style="width: 40px;">
                                <span>VNPay</span>
                                <input type="radio" name="payment_method" value="vnpay" class="ms-auto form-check-input">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <img src="{{ asset('images/vietqr.png') }}" alt="Viet QR" class="me-3" style="width: 40px;">
                                <span>Viet QR</span>
                                <input type="radio" name="payment_method" value="vietqr" class="ms-auto form-check-input" checked>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <img src="{{ asset('images/international-card.png') }}" alt="International payment cards" class="me-3" style="width: 40px;">
                                <span>International payment cards</span>
                                <input type="radio" name="payment_method" value="international" class="ms-auto form-check-input">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <img src="{{ asset('images/domestic-card.png') }}" alt="Domestic payment card" class="me-3" style="width: 40px;">
                                <span>Domestic payment card</span>
                                <input type="radio" name="payment_method" value="domestic" class="ms-auto form-check-input">
                            </div>
                        </div>
                    </div>
                </div> --}}

                <div class="container py-4">
                    <h1 class="mb-2">Checkout course</h1>
                    <p class="text-muted mb-4">We are committed to protecting your payment information.</p>
                    
                    <h4 class="mb-3">Select a payment methods</h4>
                    
                    <div class="payment_area">
                        <div class="row">
                            <div class="col-xl-3 col-6 col-md-4 wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
                                <a href="{{ route('paypal.payment') }}" class="payment_mathod">
                                    <img style="max-width: 100% !important;" src="{{ asset('default-files/paypal-logo.png') }}" alt="payment" class="img-fluid w-100">
                                </a>
                            </div>
                            <div class="col-xl-3 col-6 col-md-4 wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
                                <a href="{{ route('stripe.payment') }}" class="payment_mathod">
                                    <img src="{{ asset('default-files/stripe-logo.png') }}" alt="payment" class="img-fluid w-100">
                                </a>
                            </div>
                            <div class="col-xl-3 col-6 col-md-4 wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
                                <a href="{{ route('razorpay.redirect') }}" class="payment_mathod">
                                    <img src="{{ asset('default-files/razorpay-logo.png') }}" alt="payment" class="img-fluid w-100">
                                </a>
                            </div>
    
                        </div>
                    </div>
                    
                    <h4 class="mt-4 mb-3">Order details</h4>
                    
                    <div class="card mb-3">
                        @foreach ($cart as $item)
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="text-white course-image d-flex align-items-center justify-content-center">
                                        <img src="{{ asset($item->course->thumbnail) }}" alt="product"
                                                    class="img-fluid w-100">
                                    </div>
                                    <div class="ms-3">
                                        <h5 class="mb-1">{{ $item->course->title }}</h5>
                                        <div>
                                            @if($item->course->discount > 0)
                                                <span class="discount-price">đ{{ number_format($item->course->discount) }}</span>
                                                <span class="original-price">đ{{ number_format($item->course->price) }}</span>
                                            @else
                                                <span class="discount-price">đ{{ number_format($item->course->price) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                  </div>

                {{-- <h5 class="mt-4">Order details</h5>
                <div class="d-flex align-items-center mt-3">
                    <img src="{{ asset('images/python-course.png') }}" alt="Python Basics" class="me-3" style="width: 60px; height: 60px; object-fit: cover;">
                    <div>
                        <p class="mb-1">Python Basics - Python Cơ Bản</p>
                        <p class="mb-0 text-primary">đ199,999 <del class="text-muted">399,999</del></p>
                    </div>
                </div> --}}
            </div>
            <div class="col-xl-4 col-lg-5 wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
                <div class="total_payment_price">
                    <h4>Total Cart <span>(0{{ cartCount() }} {{ cartCount() > 1 ? 'courses' : 'course' }})</span></h4>
                    <ul>
                        <li>Subtotal :<span>{{ number_format(cartTotal($couponCode)) }}đ</span></li>
                    </ul>
                    <a href="#" class="common_btn">Now payment</a>
                </div>
            </div>
        </div>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. In sint laboriosam doloribus soluta
                        labore veniam enim deleniti necessitatibus modi. Velit odit sed assumenda eligendi
                        laboriosam.</p>

                    <ul class="modal_iteam">
                        <li>One popular belief, Lorem Ipsum is not simply random.</li>
                        <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                        <li>To popular belief, Lorem Ipsum is not simply random.</li>
                    </ul>

                    <form class="modal_form">
                        <div class="single_form">
                            <label>Enter Something</label>
                            <input type="text" placeholder="Enter Something">
                        </div>
                        <div class="single_form">
                            <label>Enter Something</label>
                            <textarea rows="4" placeholder="Enter Something"></textarea>
                        </div>
                        <div class="single_form">
                            <label>select Something</label>
                            <select class="select_js" style="display: none;">
                                <option value="">Select Something</option>
                                <option value="">Something 1</option>
                                <option value="">Something 2</option>
                                <option value="">Something 3</option>
                            </select><div class="nice-select select_js" tabindex="0"><span class="current">Select Something</span><ul class="list"><li data-value="" class="option selected">Select Something</li><li data-value="" class="option">Something 1</li><li data-value="" class="option">Something 2</li><li data-value="" class="option">Something 3</li></ul></div>
                        </div>
                    </form>

                    <div class="modal-footer">
                        <button type="button" class="modal_closs_btn common_btn" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="common_btn">submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</section>
@endsection
