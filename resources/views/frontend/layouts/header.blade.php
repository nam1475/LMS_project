   @php
       $topbar = \App\Models\TopBar::first();
       $categories = \App\Models\CourseCategory::whereNull('parent_id')->where('status', 1)->get();
       $customPages = \App\Models\CustomPage::where('status', 1)->where('show_at_nav', 1)->get();
   @endphp
   <!--===========================
        HEADER START
    ============================-->
   <header class="header_3">
       <div class="row">
           <div class="col-xxl-4 col-lg-7 col-md-8 d-none d-md-block">
               <ul class="wsus__header_left d-flex flex-wrap">
                   <li><a href="mailto:{{ $topbar?->email }}"><i class="fas fa-envelope"></i> {{ $topbar?->email }}</a></li>
                   <li><a href="callto:{{ $topbar?->phone }}"><i class="fas fa-phone-alt"></i> {{ $topbar?->phone }}</a>
                   </li>
               </ul>
           </div>
           <div class="col-xxl-5 col-lg-7 d-none d-xxl-block">
               <div class="wsus__header_center">
                   <p> <span>{{ $topbar?->offer_name }}</span> {{ $topbar?->offer_short_description }} <a
                           href="{{ $topbar?->offer_button_url }}">{{ $topbar?->offer_button_text }}</a></p>
               </div>
           </div>
           <div class="col-xxl-3 col-lg-5 col-md-4">

           </div>
       </div>
   </header>
   <!--===========================
        HEADER END
    ============================-->


    <style>
    .dropdown-menu-custom {
      width: 400px;
      border-radius: 1rem;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .notification-item {
      padding: 1rem;
      border-bottom: 1px solid #eee;
    }

    .notification-item:last-child {
      border-bottom: none;
    }

    .icon-success, .icon-error {
      width: 24px;
      height: 24px;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-right: 10px;
      font-weight: bold;
    }

    .icon-success {
      background-color: #d4edda;
      color: #28a745;
    }

    .icon-error {
      background-color: #f8d7da;
      color: #dc3545;
    }

    .notification-text a {
      text-decoration: none;
    }

    .notification-text a:hover {
      text-decoration: underline;
    }
    </style>
   <!--===========================
        MAIN MENU 3 START
    ============================-->
   <nav class="navbar navbar-expand-lg main_menu main_menu_3">
       <a class="navbar-brand" href="{{ url('/') }}">
           <img src="{{ asset(config('settings.site_logo')) }}" alt="EduCore" class="img-fluid">
       </a>
       <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
           aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
           <span class="navbar-toggler-icon"></span>
       </button>
       <div class="collapse navbar-collapse" id="navbarSupportedContent">
           <div class="menu_category">
               <div class="icon">
                   <img src="{{ asset('frontend/assets/images/grid_icon.png') }}" alt="Category" class="img-fluid">
               </div>
               Category
               <ul>
                   @foreach ($categories as $category)
                       <li>
                           <a href="{{ route('courses.index', ['main_category' => $category->slug]) }}">
                               <span>
                                   <img src="{{ asset($category->image) }}" alt="Category" class="img-fluid">
                               </span>
                               {{ $category->name }}
                           </a>
                           @if ($category->subCategories->count() > 0)
                               <ul class="category_sub_menu">
                                   @foreach ($category->subCategories as $subCategory)
                                       <li><a
                                               href="{{ route('courses.index', ['category' => $subCategory->id]) }}">{{ $subCategory->name }}</a>
                                       </li>
                                   @endforeach

                               </ul>
                           @endif
                       </li>
                   @endforeach

               </ul>
           </div>
           <ul class="navbar-nav m-auto">
               <li class="nav-item">
                   <a class="nav-link active" href="{{ url('/') }}">Home</a>
               </li>
               <li class="nav-item">
                   <a class="nav-link" href="{{ route('courses.index') }}">Courses</a>
               </li>
               <li class="nav-item">
                   <a class="nav-link" href="{{ route('about.index') }}">About</a>
               </li>
               {{-- <li class="nav-item">
                   <a class="nav-link" href="{{ route('blog.index') }}">Blogs</a>
               </li>

               <li class="nav-item">
                   <a class="nav-link" href="{{ route('contact.index') }}">contact us</a>
               </li> --}}
               @foreach ($customPages as $page)
                   <li class="nav-item">
                       <a class="nav-link" href="{{ route('custom-page', $page->slug) }}">{{ $page->title }}</a>
                   </li>
               @endforeach
           </ul>

           <div class="right_menu">
               <div class="menu_search_btn">
                   <img src="{{ asset('frontend/assets/images/search_icon.png') }}" alt="Search" class="img-fluid">
               </div>
               @if(auth('web')->user())
                <div class="nav-item dropdown">
                        {{-- Notification --}}
                        <a href="javascript:;" id="notification" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" 
                            aria-label="Open user menu" data-bs-auto-close="outside">
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-bell"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" /><path d="M9 17v1a3 3 0 0 0 6 0v-1" />
                            </svg>
                            @if(auth('web')->user()->unreadNotifications->count() > 0)
                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                    <span class="visually-hidden">New alerts</span>
                                </span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-custom p-0" aria-labelledby="dropdownMenuButton">
                            <div id="notification-container">
                                
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                    </div>
                @endif
                    
                <input type="hidden" id="current_user" value="{{ auth('web')->user() }}">
                
               <ul>
                    @if((auth('web')->user() && auth('web')->user()->role == 'student'))
                    <li>
                        <a class="menu_signin" href="{{ route('cart.index') }}">
                            <span>
                                <img src="{{ asset('frontend/assets/images/cart_icon_black.png') }}" alt="cart"
                                    class="img-fluid">
                            </span>
                            <b class="cart_count">{{ cartCount() }}</b>
                        </a>
                    </li>
                   @endif

                   <li>
                    @if(!auth()->guard('web')->check())
                       <a class="common_btn" href="{{ route('login') }}">Sign in</a>
                    @endif
                    @if(user()?->role == 'student')
                        <a class="common_btn" href="{{ route('student.dashboard') }}">Dashboard</a>
                    @endif
                    @if(user()?->role == 'instructor')
                        <a class="common_btn" href="{{ route('instructor.dashboard') }}">Dashboard</a>
                    @endif
                   </li>
               </ul>
           </div>

       </div>
   </nav>
   <div class="wsus__menu_3_search_area">
       <form action="{{ route('courses.index') }}">
           <input type="text" placeholder="Search School, Online....." name="search">
           <button class="common_btn" type="submit">Search</button>
           <span class="close_search"><i class="far fa-times"></i></span>
       </form>
   </div>
   <!--===========================
        MAIN MENU 3 END
    ============================-->


   <!--============================
        MOBILE MENU START
    ==============================-->
   <div class="mobile_menu_area">
       <div class="mobile_menu_area_top">
           <a class="mobile_menu_logo" href="{{ url('/') }}">
               <img src="{{ asset(config('settings.site_logo')) }}" alt="{{ config('settings.site_title') }}">
           </a>
           <div class="mobile_menu_icon d-block d-lg-none" data-bs-toggle="offcanvas"
               data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions">
               <span class="mobile_menu_icon"><i class="far fa-stream menu_icon_bar"></i></span>
           </div>
       </div>

       <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions">
           <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"><i
                   class="fal fa-times"></i></button>
           <div class="offcanvas-body">

               <ul class="mobile_menu_header d-flex flex-wrap">
                   <li><a href="{{ route('cart.index') }}"><i class="far fa-shopping-basket"></i> <span class="cart_count">{{ cartCount() }}</span></a>
                   </li>
                   <li><a href="{{ route('login') }}"><i class="far fa-user"></i></a></li>
               </ul>

               <form class="mobile_menu_search" action="{{ route('courses.index') }}">
                   <input type="text" placeholder="Search" name="search">
                   <button type="submit"><i class="far fa-search"></i></button>
               </form>

               <div class="mobile_menu_item_area">
                   <nav>
                       <div class="nav nav-tabs" id="nav-tab" role="tablist">
                           <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                               data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                               aria-selected="true">menu</button>
                           <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                               data-bs-target="#nav-profile" type="button" role="tab"
                               aria-controls="nav-profile" aria-selected="false">Categories</button>
                       </div>
                   </nav>
                   <div class="tab-content" id="nav-tabContent">
                       <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                           aria-labelledby="nav-home-tab" tabindex="0">
                           <ul class="main_mobile_menu">
                            <li class="nav-item">
                                <a class="nav-link active" href="{{ url('/') }}">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('courses.index') }}">Courses</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('about.index') }}">About</a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link" href="{{ route('blog.index') }}">Blogs</a>
                            </li>
             
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('contact.index') }}">contact us</a>
                            </li> --}}
                            @foreach ($customPages as $page)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('custom-page', $page->slug) }}">{{ $page->title }}</a>
                                </li>
                            @endforeach
                           </ul>
                       </div>
                       <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab"
                           tabindex="0">
                           <ul class="main_mobile_menu">
                            @foreach ($categories as $category)
                               <li class="mobile_dropdown">
                                   <a href="javascript:;">
                                       <span>
                                           <img src="{{ asset($category->image) }}" alt="Category"
                                               class="img-fluid">
                                       </span>
                                       {{ $category->name }}
                                   </a>
                                   @if ($category->subCategories->count() > 0)
                                   <ul class="inner_menu">
                                       @foreach ($category->subCategories as $subCategory)
                                       <li><a href="{{ route('courses.index', ['category' => $subCategory->id]) }}">{{ $subCategory->name }}</a></li>
                                       @endforeach
                                      
                                   </ul>
                                   @endif
                               </li>
                           @endforeach
                          

                               {{-- @foreach ($categories as $category)
                                   <li class="mobile_dropdown">
                                       <a href="javascript:;">
                                           <span>
                                               <img src="{{ asset($category->image) }}" alt="Category"
                                                   class="img-fluid">
                                           </span>
                                           {{ $category->name }}
                                       </a>
                                       @if ($category->subCategories->count() > 0)
                                           <ul class="category_sub_menu">
                                               @foreach ($category->subCategories as $subCategory)
                                                   <li><a
                                                           href="{{ route('courses.index', ['category' => $subCategory->id]) }}">{{ $subCategory->name }}</a>
                                                   </li>
                                               @endforeach

                                           </ul>
                                       @endif
                                   </li>
                               @endforeach --}}




                           </ul>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </div>
   <!--============================
        MOBILE MENU END
    ==============================-->

@push('scripts')
    @vite(['resources/js/frontend/notification.js'])
@endpush
