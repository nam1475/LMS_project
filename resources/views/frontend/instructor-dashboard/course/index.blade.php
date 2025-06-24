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
                                    Courses <i class="far fa-question-circle"
                                        data-bs-toggle="tooltip" data-bs-placement="top" 
                                        data-bs-title="Once your course is published, any further edits to that course 
                                        (e.g. change course title, adding chapters,...) will require creating a new draft and waiting for admin approval."></i>
                                </h5>
                                <p>Manage your courses and its update like live, draft and insight.</p>
                                <a class="common_btn" href="{{ route('instructor.courses.create', ['step' => 1]) }}">+ add course</a>
                            </div>
                        </div>

                        <form action="{{ route('instructor.courses.index') }}" class="wsus__dash_course_searchbox">
                            <div class="input">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search our Courses">
                                <button><i class="far fa-search"></i></button>
                            </div>

                            {{-- <div class="selector">
                                <select class="select_js filter-status">
                                    <option value="" disabled selected>Status</option>
                                    <option value="all" @selected(request('status') == 'all')>All</option>
                                    <option value="approved" @selected(request('status') == 'approved')>Approved</option>
                                    <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                                    <option value="rejected" @selected(request('status') == 'rejected')>Rejected</option>
                                </select>
                            </div> --}}

                            <div style="width: 200px; height: 46px">
                                <select class="select_2" name="course_categories[]" multiple >
                                    <option value="" disabled>Select Course Categories</option>
                                    @foreach($courseCategories as $category)
                                        @if($category->subCategories->isNotEmpty())
                                            <optgroup label="{{ $category->name }}">
                                            @foreach($category->subCategories as $subCategory)
                                                <option value="{{ $subCategory->id }}" @selected(in_array($subCategory->id, request('course_categories', [])))>
                                                    {{ $subCategory->name }}
                                                </option>
                                            @endforeach
                                            </optgroup>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <select class="select_js" name="is_approved">
                                    <option value="" disabled selected>Is Approved</option>
                                    <option value="all" @selected(request('is_approved') == 'all')>All</option>
                                    <option value="approved" @selected(request('is_approved') == 'approved')>Approved</option>
                                    <option value="pending" @selected(request('is_approved') == 'pending')>Pending</option>
                                    <option value="rejected" @selected(request('is_approved') == 'rejected')>Rejected</option>
                                </select>
                            </div>


                            <div>
                                <select class="select_js" name="is_published">
                                    <option value="" disabled selected>Is Published</option>
                                    <option value="all" @selected(request('is_published') == 'all')>All</option>
                                    <option value="1" @selected(request('is_published') == '1')>Published</option>
                                    <option value="0" @selected(request('is_published') == '0')>Draft</option>
                                </select>
                            </div>
                            
                            <div>
                                <button type="submit" class="common_btn">
                                    <i class="fas fa-filter"></i>
                                    Filter
                                </button>
                                <a href="{{ route('instructor.courses.index') }}" class="common_btn">
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
                                                    <th class="image">
                                                        COURSES
                                                    </th>
                                                    <th class="details">
                                                        
                                                    </th>
                                                    <th class="sale">
                                                        TOTAL DURATION
                                                    </th>
                                                    <th class="sale">
                                                        CATEGORY
                                                    </th>
                                                    <th class="sale">
                                                        STUDENT
                                                    </th>
                                                    <th class="status">
                                                        IS PUBLISH
                                                    </th>
                                                    <th class="status">
                                                        STATUS
                                                    </th>
                                                    <th class="action">
                                                        ACTION
                                                    </th>
                                                </tr>

                                                @forelse ($courses as $course)
                                                    {{-- @if(!$course->draft) --}}
                                                        <tr>
                                                            <td class="image">
                                                                <div class="image_category">
                                                                    <img src="{{ asset($course->thumbnail) }}" alt="img"
                                                                        class="img-fluid w-100">
                                                                </div>
                                                            </td>
                                                            <td class="details">
                                                                {{-- <p class="rating">
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        @if ($i <= $course->reviews()->avg('rating'))
                                                                            <i class="fas fa-star"></i>
                                                                        @else
                                                                            <i class="far fa-star"></i>
                                                                        @endif
                                                                    @endfor

                                                                    <span>({{ number_format($course->reviews()->avg('rating'), 2) ?? 0 }}
                                                                        Rating)</span>
                                                                </p> --}}
                                                                <a class="title" href="{{ route('instructor.courses.edit', 
                                                                        ['id' => $course->id, 'step' => 1] 
                                                                        + ((!$course->is_published && $course->is_current) ? ['is_create_draft' => true] : ['is_create_draft' => false])
                                                                    ); }}">
                                                                    {{ $course->title }}
                                                                </a>
                                                                @if($course->message_for_rejection)
                                                                    <p class="">
                                                                        Rejected: {{ $course->message_for_rejection }}
                                                                    </p>
                                                                @endif

                                                            </td>
                                                            <td class="status">
                                                                {{ convertMinutesToHours($course->duration) }}
                                                            </td>
                                                            <td class="status">
                                                                @if($course->category)
                                                                    <p class="draft w-100">{{ $course->category->name }}</p>
                                                                @endif
                                                            </td>
                                                            <td class="sale">
                                                                <p>{{ $course->enrollments()->count() }}</p>
                                                            </td>
                                                            <td class="status">
                                                                @if ($course->is_published)
                                                                    <p class="active">Publish</p>
                                                                @elseif (!$course->is_published && $course->is_current)
                                                                    <p class="draft">Draft</p>
                                                                @elseif(!$course->is_published && !$course->is_current)
                                                                    <p class="pending">Old draft</p>
                                                                @endif
                                                            </td>
                                                            <td class="status">
                                                                @if ($course->is_approved == 'approved')
                                                                    <p class="active">Approved</p>
                                                                @elseif ($course->is_approved == 'pending')
                                                                    <p class="pending">Pending</p>
                                                                @elseif ($course->is_approved == 'rejected')
                                                                    <p class="inactive">Rejected</p>
                                                                @endif
                                                            </td>
                                                            <td class="action">
                                                                @if($course->is_published)
                                                                    <a class="enroll-course" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Enroll course" 
                                                                        href="{{ route('instructor.course-player.index', $course->slug) }}">
                                                                        <i class="far fa-bars"></i></a>
                                                                    <a class="enrolled-students" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Enrolled students" 
                                                                        href="{{ route('instructor.courses.enrolled-students', $course->id) }}">
                                                                        <i class="far fa-user-graduate"></i></a>
                                                                @endif
                                                                <a class="commit" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Commits" href="{{ route('instructor.courses.commits', $course->id) }}">
                                                                    <i class="far fa-code-branch"></i></a>
                                                                    
                                                                @if($course->is_current && $course->is_published)
                                                                    <a class="draft" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Create draft" href="{{ route('instructor.courses.edit', [
                                                                            'id' => $course->id, 'step' => 1, 'is_create_draft' => true]) }}"
                                                                    >
                                                                            <i class="far fa-copy"></i></a>
                                                                @endif
                                                                <a class="edit"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ $course->is_published ? 'View' : 'Edit' }}" href="{{ route('instructor.courses.edit', 
                                                                            ['id' => $course->id, 'step' => 1] 
                                                                            + ((!$course->is_published && $course->is_current) ? ['is_create_draft' => true] : ['is_create_draft' => false])
                                                                        ); }}">
                                                                        <i class="far fa-{{ $course->is_published ? 'eye' : 'edit' }}"></i>
                                                                </a>
                                                                <a class="del delete-item" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" href="{{ route('instructor.courses.destroy', $course->id) }}"><i
                                                                    class="fas fa-trash-alt"></i></a>
                                                                
                                                            </td>
                                                        </tr>
                                                    {{-- @endif --}}
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">No Data Found!</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-4">
                                        {{ $courses->links() }}
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
    @vite(['resources/js/frontend/course.js'])

    <script>
    </script>
@endpush
