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
                            <h1>Our Courses</h1>
                            <ul>
                                <li><a href="{{ url('/') }}">Home</a></li>
                                <li>Our Courses</li>
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
        COURSES PAGE START
    ============================-->
    <section class="wsus__courses mt_120 xs_mt_100 pb_120 xs_pb_100">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-4 col-md-8 order-2 order-lg-1 wow fadeInLeft">
                    <div class="wsus__sidebar">
                        <form action="{{ route('courses.index') }}">
                            <div class="wsus__sidebar_search">
                                <input type="text" placeholder="Search Course" name="search" value="{{ request()->search ?? '' }}">
                                <button type="submit">
                                    <img src="{{ asset('frontend/assets/images/search_icon.png') }}" alt="Search" class="img-fluid">
                                </button>
                            </div>

                            <div class="wsus__sidebar_category">
                                <h3>Categories</h3>
                                <ul class="categoty_list">
                                    @foreach($categories as $category)
                                    <li class="active">{{ $category->name }}
                                        <div class="wsus__sidebar_sub_category">
                                            @foreach($category->subCategories as $subCategory)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="{{ $subCategory->id }}"
                                                    id="category-{{ $subCategory->id }}" name="category[]" @checked(
                                                    is_array(request()->category) ?
                                                    in_array($subCategory->id, request()->category ?? []):
                                                    $subCategory->id == request()->category
                                                    )>
                                                <label class="form-check-label" for="category-{{ $subCategory->id }}">
                                                    {{ $subCategory->name }}
                                                </label>
                                            </div>
                                            @endforeach
                                          
                                        </div>
                                    </li>
                                    @endforeach
                                    
                                </ul>
                            </div>

                            <div class="wsus__sidebar_course_lavel">
                                <h3>Difficulty Level</h3>
                                @foreach($levels as $level)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $level->id }}" name="level[]" id="level-{{ $level->id }}" @checked(in_array($level->id, request()->level ?? [])) >
                                    <label class="form-check-label" for="level-{{ $level->id }}">
                                        {{ $level->name }}
                                    </label>
                                </div>
                                @endforeach
                                
                            </div>

                            <div class="wsus__sidebar_course_lavel rating">
                                <h3>Rating</h3>
                                @for ($i = 5; $i >= 1; $i--)
                                    <div class="form-check">
                                        <input class="form-check-input" name="rating" @checked(request()->rating == $i) type="radio" value="{{ $i }}" id="rating{{ $i }}">
                                        <label class="form-check-label" for="rating{{ $i }}">
                                            <i class="fas fa-star"></i> {{ $i }} star {{ $i == 5 ? '' : 'or above' }}
                                        </label>
                                    </div>
                                @endfor
                            </div>

                          

                            <div class="wsus__sidebar_course_lavel duration">
                                <h3>Language</h3>
                                @foreach($languages as $language)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $language->id }}" name="language[]" id="language-{{ $language->id }}" @checked(in_array($language->id, request()->language ?? []))>
                                    <label class="form-check-label" for="language-{{ $language->id }}">
                                        {{ $language->name }}
                                    </label>
                                </div>
                                @endforeach
                                
                            </div>

                            <div class="wsus__sidebar_course_lavel rating">
                                <h3>Price</h3>
                                {{-- <div class="range_slider"></div> --}}
                                <div class="form-check">
                                    <input class="form-check-input" name="price" @checked(request()->price == 'paid') type="radio" value="paid" id="paid">
                                    <label class="form-check-label" for="paid">
                                        Paid
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" name="price" @checked(request()->price == 'free') type="radio" value="free" id="free">
                                    <label class="form-check-label" for="free">
                                        Free 
                                    </label>
                                </div>
                            </div>

                            <div class="wsus__sidebar_course_lavel rating">
                                <h3>Price range</h3>
                                {{-- <div class="range_slider"></div> --}}
                                <div class="form-check">
                                    <input class="form-check-input" name="price_range" @checked(request()->price_range == 'asc') type="radio" value="asc" id="low_to_high">
                                    <label class="form-check-label" for="low_to_high">
                                        Low to High
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" name="price_range" @checked(request()->price_range == 'desc') type="radio" value="desc" id="high_to_low">
                                    <label class="form-check-label" for="high_to_low">
                                        High to Low
                                    </label>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xl-6">
                                    <button type="submit" class="common_btn">Filter</button>
                                </div>
                                <div class="col-xl-6">
                                    <a href="{{ route('courses.index') }}" class="common_btn">Reset</a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-8 order-lg-1">
                    <div class="wsus__page_courses_header wow fadeInUp">
                        <p>Showing <span>1-{{ $courses->count() }}</span> Of <span>{{ $courses->total() }}</span> Results</p>
                        
                        <form action="{{ route('courses.index') }}">
                            <select class="select_js" name="order" onchange="this.form.submit()">
                                <option value="" disabled selected>Date</option>
                                <option value="desc" @selected(request()->order == 'desc')>New to Old</option>
                                <option value="asc" @selected(request()->order == 'asc')>Old to New</option>
                            </select>
                        </form>

                    </div>
                    <div class="row">
                        @forelse($courses as $course)
                        <div class="col-xl-4 col-md-6">
                            <div class="wsus__single_courses_3">
                                <div class="wsus__single_courses_3_img">
                                    <img src="{{ asset($course->thumbnail) }}" alt="Courses" class="img-fluid">
                                    
                                    <span class="time"><i class="far fa-clock"></i> {{ convertMinutesToHours($course->duration) }}</span>
                                </div>
                                <div class="wsus__single_courses_text_3">
                                    <div class="rating_area">
                                        <!-- <a href="#" class="category">Design</a> -->
                                        <p class="rating">
                                            @php
                                            $avgRating = round($course->reviews()->avg('rating'), 2);
                                            $fullStars = floor($avgRating);       // Số sao đầy
                                            $halfStar = ($avgRating - $fullStars) >= 0.5 ? 1 : 0; // Có nửa sao không
                                            $emptyStars = 5 - $fullStars - $halfStar; // Số sao rỗng
                                            @endphp

                                            @for($i = 1; $i <= $fullStars; $i++)
                                            <i class="fas fa-star"></i>
                                            @endfor
                                            @if($halfStar)
                                            <i class="fas fa-star-half-alt"></i>
                                            @endif
                                            @for($i = 1; $i <= $emptyStars; $i++)
                                            <i class="far fa-star"></i>
                                            @endfor
                                            
                                            <span>({{ number_format($avgRating, 1) }} Rating)</span>
                                        </p>
                                    </div>
    
                                    <a class="title" href="{{ route('courses.show', $course->slug) }}">{{ $course->title }}</a>
                                    <ul>
                                        <li>{{ $course->chapters->flatMap->lessons->count() }} Lessons</li>
                                        <li>{{ $course->enrollments()->count() }} Student</li>
                                    </ul>
                                    <a class="author" href="#">
                                        <div class="img">
                                            <img src="{{ asset($course->instructor->image) }}" alt="Author" class="img-fluid">
                                        </div>
                                        <h4>{{ $course->instructor->name }}</h4>
                                    </a>
                                </div>
                                <div class="wsus__single_courses_3_footer">
                                    @php
                                        $user = auth('web')->user();
                                    @endphp
                                    @if($user && $user->role == 'student')
                                        @php
                                            $courseEnrolled = $user ? App\Models\Enrollment::where(['user_id' => $user->id, 'course_id' => $course->id])->exists() : false;
                                            $isCourseAddedToCart = $user ? App\Models\Cart::where(['user_id' => $user->id, 'course_id' => $course->id])->exists() : false;
                                        @endphp
                                        @if ($isCourseAddedToCart)
                                            <a class="common_btn" href="{{ route('cart.index') }}" >Go to cart <i class="far fa-arrow-right"></i></a>
                                        @elseif($courseEnrolled || $course->price == 0)
                                            {{-- <a class="common_btn" href="{{ route('student.course-player.index', ['slug' => $course->slug, array_filter(['is+' => $coupon ? $coupon->code : null])]) }}">Go to course</a> --}}
                                            <a href="javascript:;" class="common_btn go-to-course-enrolled" data-is-free="{{ $course->price == 0 }}" 
                                                data-course="{{ $course }}" data-user-id="{{ $user->id }}">Go to course</a>
                                        @elseif(!$courseEnrolled && !$isCourseAddedToCart)
                                            <a class="common_btn add_to_cart" data-course-id="{{ $course->id }}" href="" >Add to Cart <i class="far fa-arrow-right"></i></a>
                                        @endif
                                    @endif
                                    <p>
                                        @php
                                            $coursePrice = $course->price;
                                        @endphp
                                        @if($course->discount > 0)
                                            <del>{{ config('settings.currency_icon') }}{{ number_format($course->price) }}</del> {{ config('settings.currency_icon') }}{{ number_format($course->discount) }}
                                        @else   
                                            {{ config('settings.currency_icon') }}{{ number_format($coursePrice) }}
                                        @endif
                                    </>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-center fs-5 fw-bold">No data Found</p>
                        @endforelse
                    </div>
                    <div class="wsus__pagination mt_50 wow fadeInUp">
                        {{ $courses->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--===========================
        COURSES PAGE END
    ============================-->
@endsection

@push('scripts')
    @vite(['resources/js/frontend/course.js'])
    
@endpush
