@extends('admin.course.course-module.course-app')

@section('tab_content')
<style>
    .timeline {
        border-left: 1px solid hsl(0, 0%, 90%);
        position: relative;
        list-style: none;
    }

    .timeline .timeline-item {
        position: relative;
        right: -21px;
    }

    .timeline .timeline-item:after {
        position: absolute;
        display: block;
        top: 0;
    }

    .timeline .timeline-item:after {
        background-color: hsl(0, 0%, 90%);
        left: -27px;
        border-radius: 50%;
        height: 11px;
        width: 11px;
        content: "";
    }
</style>

<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
    <div class="add_course_basic_info">
        <!-- Section: Timeline -->
        <section class="">
            <ul class="timeline">
                @foreach($courses as $course)
                    <li class="timeline-item mb-5">
                    <h2 class="fw-bold">
                        <a href="{{ route('admin.courses.edit', 
                                ['id' => $course->id, 'step' => 1] 
                            )}}">
                            {{-- {{ $course->title }} ({{ ($course->is_current && $course->is_published) ? 'Publish' : 'Current revision' }}) --}}
                            {{ $course->message_for_commit ?? $course->title }} -
                                @if($course->is_published)
                                    Published
                                @elseif($course->is_current && !$course->is_published)
                                    Current revision
                                @else
                                    Old draft
                                @endif
                        </a>
                    </h2>
                    <p class="text-muted mb-2 fw-bold">{{ $course->created_at->timezone('Asia/Ho_Chi_Minh')->format('H:i d/m/Y') }}</p>
                    </li>
                @endforeach

            </ul>
        </section>
        <!-- Section: Timeline -->
    </div>
</div>
@endsection