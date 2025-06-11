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
            DASHBOARD notification START
        ============================-->
    <section class="wsus__dashboard mt_90 xs_mt_70 pb_120 xs_pb_100">
        <div class="container">
            <div class="row">
                @include(auth('web')->user()->role == 'student' ? 'frontend.student-dashboard.sidebar' : 'frontend.instructor-dashboard.sidebar')

                <div class="col-xl-9 col-md-8 wow fadeInRight">
                    <div class="wsus__dashboard_contant">
                        <div class="wsus__dashboard_contant_top">
                            <div class="wsus__dashboard_heading relative">
                                <h5>Notifications</h5>
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
                                                        TITLE
                                                    </th>
                                                    <th class="details">
                                                        MESSAGE
                                                    </th>
                                                    <th class="sale">
                                                        IS READ
                                                    </th>
                                                </tr>

                                                @forelse ($notifications as $notification)
                                                    <tr>
                                                        <td class="sale">{{ $notification->data['title'] }}</td>
                                                        <td class="sale">
                                                            <a class="mark-as-read" data-notification-id="{{ $notification->id }}"
                                                                data-redirect-url="{{ $notification->data['url'] }}" 
                                                                href="javascript:;">
                                                                {{ $notification->data['message'] }}
                                                            </a>
                                                        </td>
                                                        <td class="status">
                                                            @if ($notification->read_at)
                                                                <p class="active">Yes</p>
                                                            @else
                                                                <p class="inactive">No</p>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">No Notification Found!</td>
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
            DASHBOARD notification END
        ============================-->
@endsection
