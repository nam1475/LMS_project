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
                            <h1>Become a Instructor</h1>
                            <ul>
                                <li><a href="{{ url('/') }}">Home</a></li>
                                <li>Become a Instructor</li>
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
                    DASHBOARD OVERVIEW START
                ============================-->
    <section class="wsus__dashboard mt_90 xs_mt_70 pb_120 xs_pb_100">
        <div class="container">
            <div class="row">
                @include('frontend.student-dashboard.sidebar')
                <div class="col-xl-9 col-md-8">
                    <div class="wsus__dashboard_contant">
                        <div class="wsus__dashboard_contant_top">
                            <div class="wsus__dashboard_heading relative">
                                <h5>Courses</h5>
                                <p>Manage your courses and its update like live, draft and insight.</p>
                            </div>
                        </div>


                        <div class="wsus__dash_course_table">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th class="image">
                                                        COURSES
                                                    </th>
                                                    <th class="details">

                                                    </th>
                                                    
                                                    <th class="action">
                                                        ACTION
                                                    </th>
                                                </tr>
                                                @forelse($enrollments as $enrollment)
                                                <tr>
                                                    <td class="image">
                                                        <div class="image_category">
                                                            <img src="{{ asset($enrollment->course->thumbnail) }}" alt="img" class="img-fluid w-100">
                                                        </div>
                                                    </td>
                                                    <td class="details">
                                                        <p class="rating">
                                                            @php
                                                                $avgRating = round($enrollment->course->reviews()->avg('rating'), 2);
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
                                                        <a class="title" href="{{ route("student.course-player.index", $enrollment->course->slug) }}">
                                                            {{ $enrollment->course->title }}
                                                            
                                                        </a>
                                                        <div class="text-muted">By {{ $enrollment->course->instructor->name }}</div>

                                                        @php
                                                            $lessonCount = \App\Models\CourseChapterLession::withoutGlobalScopes()->where('course_id', $enrollment->course->id)->count();
                                                            $watchedLessonIds = \App\Models\WatchHistory::where(['user_id' => user()->id, 'course_id' => $enrollment->course->id, 'is_completed' => 1])
                                                                ->pluck('lesson_id')->toArray();
                                                            $progress = $lessonCount ? round((count($watchedLessonIds) / $lessonCount) * 100) : 0;
                                                        @endphp
                                                        <div class="progress" role="progressbar" aria-label="Example with label" aria-valuenow="" aria-valuemin="0" aria-valuemax="100">
                                                            <div class="progress-bar" style="width: {{ $progress }}%">{{ $progress }}%</div>
                                                        </div>

                                                        {{-- @php
                                                            $watchedLessonCount = \App\Models\WatchHistory::where(['user_id' => user()->id, 'course_id' => $enrollment->course->id, 'is_completed' => 1])->count();
                                                            $lessonCount = $enrollment->course->lessons()->count();
                                                        @endphp --}}
                                                        {{-- Certificate --}}
                                                        {{-- @if($lessonCount == $watchedLessonCount)
                                                            <a target="_blank" href="{{ route('student.certificate.download', $enrollment->course->id) }}" class="btn btn-sm btn-warning">
                                                                Download Certificate
                                                            </a>
                                                        @endif --}}
                                                    </td>
                                                    
                                                    
                                                    <td class="">
                                                        <a class="common_btn" href="{{ route("student.course-player.index", $enrollment->course->slug) }}" >Watch Course</a>
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
                    DASHBOARD OVERVIEW END
                ============================-->
@endsection
