@extends("frontend.layouts.master")

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
                            <h1>{{ auth('web')->user()->role == 'student' ? 'Student' : 'Instructor' }} Dashboard</h1>
                            <ul>
                                <li><a href="{{ url('/') }}">Home</a></li>
                                <li>{{ auth('web')->user()->role == 'student' ? 'Student' : 'Instructor' }} Dashboard</li>
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
            DASHBOARD student START
        ============================-->
    <section class="wsus__dashboard mt_90 xs_mt_70 pb_120 xs_pb_100">
        <div class="container">
            <div class="row">
                @include(auth('web')->user()->role == 'student' ? 'frontend.student-dashboard.sidebar' : 'frontend.instructor-dashboard.sidebar')

                <div class="col-xl-9 col-md-8 wow fadeInRight">
                    <div class="wsus__dashboard_contant">
                        <div class="wsus__dashboard_contant_top">
                            <div class="wsus__dashboard_heading relative">
                                <h5>Enrolled Students</h5>
                            </div>
                        </div>

                        <form action="{{ route('instructor.courses.enrolled-students', $course->id) }}" class="wsus__dash_course_searchbox">
                            <div class="input">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search our Courses">
                                <button><i class="far fa-search"></i></button>
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
                                                        IMAGE
                                                    </th>
                                                    <th class="details">
                                                        NAME
                                                    </th>
                                                    <th class="sale">
                                                        EMAIL
                                                    </th>
                                                </tr>

                                                @forelse ($enrollments as $enrollment) 
                                                    <tr>
                                                        <td class="image">
                                                            <img class="rounded-circle" src="{{ asset($enrollment->student->image) }}" alt="">
                                                        </td>
                                                        <td class="sale">{{ $enrollment->student->name }}</td>
                                                        <td class="sale">
                                                            {{ $enrollment->student->email }}
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">No Student Found!</td>
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
            DASHBOARD student END
        ============================-->
@endsection
