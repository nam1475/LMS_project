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
            DASHBOARD COURSE START
        ============================-->
    <section class="wsus__dashboard mt_90 xs_mt_70 pb_120 xs_pb_100">
        <div class="container">
            <div class="row">
                @include('frontend.instructor-dashboard.sidebar')

                <div class="col-xl-9 col-md-8 wow fadeInRight">
                    <div class="wsus__dashboard_contant">
                        <div class="wsus__dashboard_contant_top">
                            <div class="wsus__dashboard_heading relative">
                                <h5>
                                    Reviews
                                </h5>
                            </div>
                        </div>

                        <form action="{{ route('instructor.course-reviews.show', $course->id) }}" class="wsus__dash_course_searchbox">
                            <div class="input">
                                <input type="text" name="search" value="{{ request('search') }}">
                                <button><i class="far fa-search"></i></button>
                            </div>

                            <div style="width: 200px; height: 46px">
                                <select class="select_2" name="rating[]" multiple>
                                    <option value="" disabled>Rating</option>
                                    <option value="all" @selected(request('rating') == 'all')>All</option>
                                    @for ($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" @selected(in_array($i, request('rating')))>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            
                            <div>
                                <button type="submit" class="common_btn">
                                    <i class="fas fa-filter"></i>
                                    Filter
                                </button>
                                <a href="{{ route('instructor.course-reviews.show', $course->id) }}" class="common_btn">
                                    <i class="fas fa-redo"></i>                                    
                                    Reset
                                </a>
                            </div>
                            
                        </form>

                        <div class="wsus__dash_course_table">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th class="image" width="30%">
                                                        USER
                                                    </th>
                                                    <th class="sale" width="15%">
                                                        RATING
                                                    </th>
                                                    <th class="action">
                                                        REVIEW
                                                    </th>
                                                </tr>

                                                @forelse ($reviews as $review)
                                                    <tr>
                                                        <td class="details">
                                                            {{ $review->user->name }}
                                                            <div class="text-muted">{{ $review->user->email }}</div>
                                                        </td>
                                                        <td class="details">
                                                            <p class="rating">
                                                                @for($i = 1; $i <= $review->rating; $i++)
                                                                    <i class="fas fa-star"></i>                                                                
                                                                @endfor
                                                            </p>
                                                        </td>
                                                        <td class="sale">
                                                            {{ $review->review }}
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
                                    <div class="mt-4">
                                        {{ $reviews->links() }}
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
            DASHBOARD COURSE END
        ============================-->
@endsection

@push('scripts')
@endpush
