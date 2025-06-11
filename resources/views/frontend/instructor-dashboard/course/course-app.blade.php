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
                            <h1>{{ @$title }}</h1>
                            <ul>
                                <li><a href="#">Home</a></li>
                                <li>{{ @$title }}</li>
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


    <!--=============================
                DASHBOARD ADD COURSE START
            ==============================-->
    <section class="wsus__dashboard mt_90 xs_mt_70 pb_120 xs_pb_100">
        <div class="container">
            <div class="row">
                @include('frontend.instructor-dashboard.sidebar')

                <div class="col-xl-9 col-md-8 wow fadeInRight">
                    <div class="wsus__dashboard_contant">
                        <div class="wsus__dashboard_contant_top d-flex justify-content-between">
                            <div class="wsus__dashboard_heading relative">
                                <h5>{{ @$title }}</h5>
                                {{-- <p>Manage your courses and its update like live, draft and insight.</p> --}}
                            </div>
                        </div>

                        <div class="dashboard_add_courses">
                            @if(@!$isCommits)
                                <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                    {{-- <li class="nav-item" role="presentation ">
                                        <a href="" class="nav-link course-tab {{ request('step') == 0 ? 'active' : '' }}" data-step="0">Commits</a>
                                    </li> --}}
                                    <li class="nav-item" role="presentation ">
                                        <a href="" class="nav-link course-tab {{ request('step') == 1 ? 'active' : '' }}" data-step="1">Basic Infos</a>
                                    </li>
                                    {{-- <li class="nav-item" role="presentation">
                                        <a href="" class="nav-link course-tab {{ request('step') == 2 ? 'active' : '' }}" data-step="2">More Info</a>
                                    </li> --}}
                                    <li class="nav-item" role="presentation">
                                        <a href="" class="nav-link course-tab {{ request('step') == 2 ? 'active' : '' }}" data-step="2">Course Contents</a>
                                    </li>
                                    @if(@$course->is_current && @$isCreateDraft)
                                        <li class="nav-item" role="presentation">
                                            <a href="" class="nav-link course-tab {{ request('step') == 3 ? 'active' : '' }}" data-step="3" >Finish</a>
                                        </li>
                                    @endif
                                </ul>
                            @endif
                                
                            @yield('course_content') 
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
                DASHBOARD ADD COURSE END
            ==============================-->
@endsection

@push('header_scripts')
    @vite(['resources/js/frontend/course.js'])
@endpush
