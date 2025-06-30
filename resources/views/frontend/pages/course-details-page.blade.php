@extends('frontend.layouts.master')
@push('meta')
    <meta property="og:title" content="{{ $course->title }}">
    <meta property="og:description" content="{{ $course->seo_description }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset($course->thumbnail) }}">
    <meta property="og:type" content="Course">
@endpush
@section('content')
<style>
    .icon-circle {
      width: 24px;
      height: 24px;
      background-color: var(--colorPrimary); /* Màu primary */
      color: #fff;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 14px;
    }

  </style>  
    <!--===========================
                BREADCRUMB START
                ============================-->
    
    <section class="wsus__breadcrumb course_details_breadcrumb"
        style="background: url({{ asset(config('settings.site_breadcrumb')) }});">
        <div class="wsus__breadcrumb_overlay">
            <div class="container">
                

                <div class="row">
                    <div class="col-12 wow fadeInUp">
                        <div class="wsus__breadcrumb_text">
                            <p class="rating">
                                @for($i = 1; $i <= 5; $i++) 
                                @if($i <= $course->reviews()->avg('rating'))
                                <i class="fas fa-star"></i>
                                @else
                                <i class="far fa-star"></i>
                                @endif
                                @endfor
                                <span>({{ number_format($course->reviews()->avg('rating'), 2) ?? 0 }} Reviews)</span>

                            </p>
                            <h1>{{ $course->title }}</h1>
                            <ul class="list">
                                <li>
                                    <span><img src="{{ asset($course->instructor->image) }}" alt="user"
                                            class="img-fluid"></span>
                                    By {{ $course->instructor->name }}
                                </li>
                                <li>
                                    <span><img src="{{ asset('frontend/assets/images/globe_icon_blue.png') }}"
                                            alt="Globe" class="img-fluid"></span>
                                    {{ $course->category->name }}
                                </li>
                                <li>
                                    <span><img src="{{ asset('frontend/assets/images/calendar_blue.png') }}" alt="Calendar"
                                            class="img-fluid"></span>
                                    Last updated {{ date('d/M/Y', strtotime($course->updated_at)) }}
                                </li>
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
                COURSES DETAILS START
            ============================-->
    <section class="wsus__courses_details pb_120 xs_pb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 wow fadeInLeft">
                    <div class="wsus__courses_details_area mt_40">

                        <ul class="nav nav-pills mb_40" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                    aria-selected="true">Overview</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-profile" type="button" role="tab"
                                    aria-controls="pills-profile" aria-selected="false">Curriculum</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-contact" type="button" role="tab"
                                    aria-controls="pills-contact" aria-selected="false">Instructor</button>
                                
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-disabled-tab2" data-bs-toggle="pill"
                                    data-bs-target="#pills-disabled2" type="button" role="tab"
                                    aria-controls="pills-disabled2" aria-selected="false">Reviews</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                aria-labelledby="pills-home-tab" tabindex="0">
                                <div class="wsus__courses_overview box_area">
                                    <h3>Course Description</h3>
                                    <p>{!! $course->description !!}</p>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                aria-labelledby="pills-profile-tab" tabindex="0">
                                <div class="wsus__courses_curriculum box_area">
                                    <h3>Course Curriculum</h3>
                                    <div class="accordion" id="accordionExample">
                                        @foreach($course->chapters as $chapter)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#collapse-{{ $chapter->id }}" aria-expanded="true"
                                                    aria-controls="collapse-{{ $chapter->id }}">
                                                    {{ $chapter->title }}
                                                </button>
                                            </h2>
                                            <div id="collapse-{{ $chapter->id }}" class="accordion-collapse collapse"
                                                data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <ul>
                                                        @foreach($chapter->lessons as $lesson)
                                                        <li class="{{ $lesson->is_preview == 1 ? 'active' : '' }}">
                                                            <p>{{ $lesson->title }}</p>
                                                            @if($lesson->is_preview == 1)
                                                            <a href="{{ $lesson->file_path }}" data-autoplay="true" data-vbtype="video" class="right_text venobox vbox-item">Preview</a>
                                                            @else
                                                            <span class="right_text">{{ convertMinutesToHours($lesson->duration) }}</span>
                                                            @endif
                                                        </li>
                                                        @endforeach

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-contact" role="tabpanel"
                                aria-labelledby="pills-contact-tab" tabindex="0">
                                <div class="wsus__courses_instructor box_area">
                                    <h3>Instructor Details</h3>
                                    <div class="row align-items-center">
                                        <div class="col-lg-4 col-md-6">
                                            <div class="wsus__courses_instructor_img">
                                                <img src="{{ asset($course->instructor->image) }}" alt="Instructor"
                                                    class="img-fluid">
                                            </div>
                                        </div>
                                        <div class="col-lg-8 col-md-6">
                                            <div class="wsus__courses_instructor_text">
                                                <h4>{{ $course->instructor->name }}</h4>
                                                @if ($user && $user->role == 'student')
                                                    {{-- <button type="button" id="show-chat-modal" class="common_btn"> --}}
                                                    <button type="button" id="chat-with-instructor" data-receiver-image="{{ asset($course->instructor->image) }}" 
                                                            data-receiver-name="{{ $course->instructor->name }}" data-receiver-id="{{ $course->instructor->id }}" 
                                                            data-route="{{ route('student.fetch.messages') }}"
                                                            class="common_btn">
                                                        Chat now
                                                    </button>
                                                @endif

                                                {{-- <div class="modal fade" id="chat-modal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title fw-bold" id="chatModalLabel">Chat with {{ $course->instructor->name }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form method="POST" id="chat-form" data-route="{{ route('student.send.message') }}" class="d-flex mb-4">
                                                                    @csrf
                                                                    <input type="text" name="message" id="message-input" class="form-control me-2" placeholder="Enter your message" value="">
                                                                    <input type="hidden" name="receiver_id" id="receiver-id" value="{{ $course->instructor->id }}">
                                                                    <button type="submit" id="send-message-button" class="btn btn-primary">Send</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> --}}

                                                <p class="designation">{{ $course->instructor->headline }}</p>
                                                <ul class="list">
                                                    @php
                                                        $coursesId = $course->instructor->courses()->pluck('id')->toArray();
                                                        $reviewsCount = \App\Models\Review::whereIn('course_id', $coursesId)->count();
                                                        $avgRating = \App\Models\Review::whereIn('course_id', $coursesId)->avg('rating');
                                                    @endphp
                                                    <li><i class="fas fa-star"></i> <b> {{ $reviewsCount }} Reviews</b></li>
                                                    <li><strong>{{ number_format($avgRating, 1) ?? 0 }} Ratings</strong></li>
                                                    <li>
                                                        <span><img src="{{ asset('frontend/assets/images/book_icon.png') }}" alt="book"
                                                                class="img-fluid"></span>
                                                        {{ $course->instructor->courses()->count() }} Courses
                                                    </li>
                                                    <li>
                                                        <span><img src="{{ asset('frontend/assets/images/user_icon_gray.png') }}" alt="user"
                                                                class="img-fluid"></span>
                                                        {{ $course->instructor->students()->count() }} Students
                                                    </li>
                                                </ul>
                                               
                                                <p class="description">
                                                    {{ $course->instructor->bio }}
                                                </p>
                                                <ul class="link d-flex flex-wrap">
                                                    @if($course->instructor->facebook)
                                                    <li><a href="{{ $course->instructor->facebook }}"><i class="fab fa-facebook-f"></i></a></li>
                                                    @endif
                                                    @if($course->instructor->x)
                                                    <li><a href="{{ $course->instructor->x }}"><i class="fab fa-twitter"></i></a></li>
                                                    @endif
                                                    @if($course->instructor->linkedin)
                                                    <li><a href="{{ $course->instructor->linkedin }}"><i class="fab fa-linkedin-in"></i></a></li>
                                                    @endif
                                                    @if($course->instructor->website)
                                                    <li><a href="{{ $course->instructor->website }}"><i class="fas fa-link"></i></a></li>
                                                    @endif
                                                    @if($course->instructor->github)
                                                    <li><a href="{{ $course->instructor->github }}"><i class="fab fa-github"></i></a></li>
                                                    @endif


                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="tab-pane fade" id="pills-disabled2" role="tabpanel"
                                aria-labelledby="pills-disabled-tab2" tabindex="0">
                                <div class="wsus__courses_review box_area">
                                    {{-- <h3>Reviews</h3> --}}
                                    <div class="row align-items-center mb_50">
                                        <div class="col-xl-4 col-md-6">
                                            <div class="total_review">
                                                <h2>{{ number_format($course->reviews()->avg('rating'), 1) ?? 0 }}</h2>
                                                <p>
                                                   @for($i = 1; $i <= number_format($course->reviews()->avg('rating'), 1) ?? 0; $i++)
                                                    <i class="fas fa-star"></i>
                                                   @endfor
                                                   
                                                </p>
                                                <h4>{{ $course->reviews()->count() }} Ratings</h4>
                                            </div>
                                        </div>
                                        <div class="col-xl-8 col-md-6">
                                            <div class="review_bar">
                                                @foreach ($ratingPercentages as $rating => $percentage)
                                                    <div class="review_bar_single">
                                                        <p>{{ $rating }} <i class="fas fa-star"></i></p>
                                                        <div id="bar{{ $rating }}" class="barfiller">
                                                            <div class="tipWrap">
                                                                <span class="tip"></span>
                                                            </div>
                                                            <span class="fill" data-percentage="{{ $percentage }}"></span>
                                                        </div>
                                                        {{-- <span class="qnty">{{ $course->reviews()->where('rating', $rating)->count() }}</span> --}}
                                                        <span class="qnty">{{ $percentage }}%</span>
                                                    </div>
                                                @endforeach
                                               
                                            </div>
                                        </div>
                                    </div>

                                    <div class="reviews">
                                        <h3>Reviews</h3>
                                        <div>
                                            {{-- <select data-course-id="{{ $course->id }}" class="select_2" id="filter-rating">
                                                <option value="">Rating</option>
                                                @foreach($ratingPercentages as $rating => $percentage)
                                                <option value="{{ $rating }}">{{ $rating }}</option>
                                                @endforeach
                                            </select> --}}

                                            <input type="radio" data-course-id="{{ $course->id }}" class="btn-check filter-rating" 
                                                value="all" name="options-base" id="all-reviews" autocomplete="off" checked>
                                            <label class="btn" for="all-reviews">All <i class="fas fa-star"></i></label> 
                                            @foreach($ratingPercentages as $rating => $percentage)
                                                <input type="radio" data-course-id="{{ $course->id }}" class="btn-check filter-rating" 
                                                    value="{{ $rating }}" name="options-base" id="{{ $rating }}-star" autocomplete="off">
                                                <label class="btn" for="{{ $rating }}-star">{{ $rating }} <i class="fas fa-star"></i></label>
                                            @endforeach

                                        </div>

                                    </div>

                                    <div class="reviews-containter">
                                        @foreach($reviews as $review)
                                            <div class="wsus__course_single_reviews">
                                                <div class="wsus__single_review_img">
                                                    <img src="{{ asset($review->user->image) }}" alt="user" class="img-fluid">
                                                </div>
                                                <div class="wsus__single_review_text">
                                                    <h4>{{ $review->user->name }}</h4>
                                                    <h6> {{ $review->created_at->format('d/m/Y') }}
                                                        <span>
                                                            @for($i = 1; $i <= $review->rating; $i++)
                                                            <i class="fas fa-star"></i>
                                                            @endfor
                                                            
                                                        </span>
                                                    </h6>
                                                    <p>{{ $review->review }}</p>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>

                                    {{-- <div>
                                        {{ $reviews->links() }}
                                    </div> --}}
                                
                                </div>

                                @if($courseEnrolled && !$isReviewed)
                                    <div class="wsus__courses_review_input box_area mt_40">
                                        <h3>Write a Review</h3>
                                        <p class="short_text">Your email address will not be published. Required fields are
                                            marked *</p>
                                        <div class="select_rating d-flex flex-wrap">Your Rating:
                                            <ul id="starRating" data-stars="5"></ul>
                                        </div>
                                        <form action="{{ route('review.store') }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <input type="hidden" name="rating" value="" id="rating">
                                                <input type="hidden" name="course" value="{{ $course->id }}">
                                                <div class="col-xl-12">
                                                    <textarea rows="7" placeholder="Review" name="review"></textarea>
                                                </div>
                                                <div class="col-12 mt-3">
                                                    <button type="submit" class="common_btn">Submit</button>    
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                @elseif($isReviewed)
                                    <div class="alert alert-info mt-3 text-center" role="alert">You already reviewed this course</div>
                                @elseif(!$courseEnrolled)
                                    <div class="alert alert-info mt-3 text-center" role="alert">You have to enroll this course to write a review</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-8 wow fadeInRight">
                    <div class="wsus__courses_sidebar">
                        <div class="wsus__courses_sidebar_video">
                            <img src="{{ asset($course->thumbnail) }}" alt="Video" class="img-fluid">
                            @if ($course->demo_video_source != null)
                                <a class="play_btn venobox vbox-item" data-autoplay="true" data-vbtype="{{ in_array($course->demo_video_storage, ['youtube', 'external_link']) ? 'video' : 'iframe' }}"
                                    href="{{ asset($course->demo_video_source) }}">
                                    <img src="{{ asset('frontend/assets/images/play_icon_white.png') }}" alt="Play"
                                        class="img-fluid">
                                </a>
                            @endif
                        </div>
                        <h3 class="wsus__courses_sidebar_price">
                            @php
                                $coursePrice = $course->price;
                            @endphp
                            @if($course->discount > 0)
                                Price: <del>{{ config('settings.currency_icon') }}{{ number_format($coursePrice) }}</del>{{ number_format($course->discount) }}
                            @elseif($coursePrice <= 0)
                                FREE
                            @else
                                Price: {{ config('settings.currency_icon') }}{{ number_format($coursePrice) }}
                            @endif
                        </h3>
                      
                        <div class="wsus__courses_sidebar_list_info">
                            <ul>
                                <li>
                                    <p>
                                        <span><img src="{{ asset('frontend/assets/images/clock_icon_black.png') }}"
                                                alt="clock" class="img-fluid"></span>
                                        Course Duration
                                    </p>
                                    {{ convertMinutesToHours($course->duration) }}
                                </li>
                                <li>
                                    <p>
                                        <span><img src="{{ asset('frontend/assets/images/network_icon_black.png') }}"
                                                alt="network" class="img-fluid"></span>
                                        Skill Level
                                    </p>
                                    {{ $course->level->name }}
                                </li>
                                <li>
                                    <p>
                                        <span><img src="{{ asset('frontend/assets/images/user_icon_black_2.png') }}"
                                                alt="User" class="img-fluid"></span>
                                        Student Enrolled
                                    </p>
                                    {{ $course->enrollments()->count() }}
                                </li>
                                <li>
                                    <p>
                                        <span><img src="{{ asset('frontend/assets/images/language_icon_black.png') }}"
                                                alt="Language" class="img-fluid"></span>
                                        Language
                                    </p>
                                    {{ $course->language->name }}
                                </li>
                            </ul>
                            @if ($isCourseAddedToCart)
                                <a class="common_btn" href="{{ route('cart.index') }}" >Go to cart <i class="far fa-arrow-right"></i></a>
                            @elseif($courseEnrolled || $course->price == 0)
                                <div class="mt-2">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div class="icon-circle me-2">i</div>
                                        <div class="">
                                            <div class="fw-bold">You purchased this course on</div>
                                            <div class="fw-bold">{{ $courseEnrolled->created_at->format('M d, Y') }}</div>
                                        </div>
                                    </div>

                                    <a class="common_btn" href="{{ route('student.course-player.index', $course->slug) }}">Go to course</a>
                                    
                                </div>
                            @elseif(!$courseEnrolled && !$isCourseAddedToCart && ($user && $user->role == 'student'))
                                <a class="common_btn add_to_cart" data-course-id="{{ $course->id }}" href="" >Add to Cart <i class="far fa-arrow-right"></i></a>
                            @endif
                            
                            {{-- <a class="common_btn" href="{{ route('checkout.index', array_filter(['coupon_code' => $couponCode])) }}">
                                Buy now
                            </a> --}}
                        </div>
                        
                        <div class="wsus__courses_sidebar_share_area">
                            <span>Share:</span>
                            <ul>
                                <li class="ez-facebook"><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                <li class="ez-linkedin"><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                                <li class="ez-x"><a href="#"><i class="fab fa-twitter"></i></a></li>
                                <li class="ez-reddit"><a href="#"><i class="fab fa-reddit"></i></a></li>
                            </ul>
                        </div>
                        <div class="wsus__courses_sidebar_info">    
                            <h3>This Course Includes</h3>
                            <ul>
                                <li>
                                    <span><img src="{{ asset('frontend/assets/images/video_icon_black.png') }}"
                                            alt="video" class="img-fluid"></span>
                                    {{ convertMinutesToHours($course->duration) }} Video Lectures
                                </li>
                                {{-- @if ($course->certificate)
                                    <li>
                                        <span><img src="{{ asset('frontend/assets/images/certificate_icon_black.png') }}"
                                                alt="Certificate" class="img-fluid"></span>
                                        Certificate of Completion
                                    </li>
                                @endif --}}
                                <li>
                                    <span><img src="{{ asset('frontend/assets/images/life_time_icon.png') }}"
                                            alt="Certificate" class="img-fluid"></span>
                                    Course Lifetime Access
                                </li>
                            </ul>
                            
                        </div>
                        
                        <div class="wsus__courses_sidebar_instructor">
                            <div class="image_area d-flex flex-wrap align-items-center">
                                <div class="img">
                                    <img src="{{ asset($course->instructor->image) }}" alt="Instructor" class="img-fluid">
                                </div>
                                <div class="text">
                                    <h3>{{ $course->instructor->name }}</h3>
                                    <p><span>Instructor</span></p>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

   
    <!--===========================
                COURSES DETAILS END
            ============================-->
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/gh/shakilahmed0369/ez-share/dist/ez-share.min.js"></script>
@vite(['resources/js/frontend/review.js'])
<script>
    const csrfToken = $(`meta[name="csrf_token"]`).attr('content');
    const baseUrl = $(`meta[name="base_url"]`).attr('content');

    $(function() {
        // Khi tab được kích hoạt (đã mở)
        $('button[data-bs-toggle="pill"]').on('shown.bs.tab', function (e) {
            const target = $(e.target).data('bs-target'); // lấy id tab content hiện tại

            if (target === '#pills-disabled2') {
                // Khởi tạo lại barfiller cho các thanh trong tab Reviews
                $('#bar1').barfiller({});
                $('#bar2').barfiller({});
                $('#bar3').barfiller({});
                $('#bar4').barfiller({});
                $('#bar5').barfiller({});
            }
        });


        $('#starRating li').on('click', function() {
            var $starRating = $('#starRating').find('.active').length;

            $('#rating').val($starRating);
        });

        $('#show-chat-modal').on('click', function (e) {
            e.preventDefault();
            $('#chat-modal').modal('show');
        });

        // Send message
        $('#chat-form').on('submit', function (e) {
            e.preventDefault();
            var message = $('#message-input').val();
            var receiverId = $('#receiver-id').val();
            var route = $('#message-form').data('route');

            $.ajax({
                url: route,
                type: "POST",
                data: {
                    _token: $('input[name="_token"]').val(),
                    receiver_id: receiverId,
                    message: message
                },
                beforeSend: function() {
                    $('#send-message-button').text('Sending...').attr('disabled', true);
                },
                success: function (response) {
                    notyf.success(response.message);
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                },
                error: function (error) {
                    notyf.error(error.responseJSON.message);
                }
            });

        
        });

        $('#chat-with-instructor').on('click', function (e) {
            e.preventDefault();
            $('#openChat').trigger('click');
            // $('#receiver-id').val($(this).data('instructor-id'));

            var currentUser = JSON.parse($('#current_user').val());
            let profileImage = $(this).data('receiver-image');
            let profileName = $(this).data('receiver-name');
            let receiverId = $(this).data('receiver-id');
            let route = $(this).data('route');
            var chatArea = $('#chat-area');

            $.ajax({
                url: route,
                method: 'GET',
                data: { 
                    receiver_id: receiverId,
                },
                beforeSend: function () {
                    // $('#chat-area').html(loader);
                },
                success: function(response) {
                    chatArea.empty();

                    if(response.isRead){
                        let marked = $('#marked-' + response.receiverId);
                        marked.addClass('d-none');
                    }
                    // console.log(response.messages);

                    let chatAreaHtml = `
                        <div class="card shadow-sm" style="width: 100%; height: 100%;">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center p-2">
                                    <strong>${profileName}</strong>
                                    <button id="closeChat" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div> 
                            </div>
                        
                            <div class="card-body chat-window" style="width: 100%; height: 80%;">
                                <div class="chat-message-container" id="chat-message-container">
                        `;
                        
                        if(response.messages.length > 0){
                            response.messages.forEach(function(message) {
                                let isSender = message.sender_id == currentUser.id;
                                let userAvatar = isSender ? currentUser.image : profileImage;
                                let messageTime = new Date(message.created_at).toLocaleString('vi-VN', {
                                    day: '2-digit',
                                    month: '2-digit',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                });
    
                                chatAreaHtml += `
                                    <div class="chat-message ${isSender ? 'sender' : 'receiver'}">
                                        <div class="message-avatar">
                                            <img src="${userAvatar}" class="rounded-circle avatar" alt="User Avatar">
                                        </div>
                                        <div class="message-content">
                                            <p>${message.message}</p>
                                            <div class="timestamp">${messageTime}</div>
                                        </div>
                                    </div>
                                        
                                    `;
                            });
                        }
                        // else{
                        //     chatAreaHtml += `
                        //         <p class="text-center mt-3 fw-bold">No Messages Yet</p>
                        //     `;
                        // }

                    chatAreaHtml += `
                                </div>
                            </div>

                            <div class="card-footer">
                                <form id="message-form" method="POST">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="receiver_id" id="receiver_id" value="${response.receiverId}">
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                            placeholder="Type your message here..." id="messageInput"
                                            name="message">
                                        <button class="btn btn-primary" type="submit"
                                            id="send-message-button">Send</button>
                                    </div>
                                </form>
                            </div>
                        </div> 
                    `;

                    chatArea.append(chatAreaHtml);

                    // Scroll to the bottom of the chat container
                    let chatWindow = $('.chat-window');
                    $('.chat-window').scrollTop(chatWindow[0].scrollHeight);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching messages:', error);
                },
            });
        });
    })
</script>
@endpush
