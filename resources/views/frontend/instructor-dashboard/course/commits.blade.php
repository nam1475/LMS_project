@extends('frontend.instructor-dashboard.course.course-app')

@section('course_content')
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
            {{-- <input type="hidden" name="is_create_draft" id="is_create_draft" value={{ $isCreateDraft }}> --}}
            {{-- <input type="hidden" name="current_step" value="0">
            <input type="hidden" name="next_step" value="1"> --}}
            @foreach($courses as $course)
                <li class="timeline-item mb-5">
                <h5 class="fw-bold">
                    <a href="{{ route('instructor.courses.edit', 
                        ['id' => $course->id, 'step' => 1] 
                        + ((!$course->is_published && $course->is_current) ? ['is_create_draft' => true] : ['is_create_draft' => false])
                    ); }}">
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
                </h5>
                <p class="text-muted mb-2 fw-bold">{{ $course->created_at->timezone('Asia/Ho_Chi_Minh')->format('H:i d/m/Y') }}</p>
                @if($course->message_for_rejection)
                    <p class="text-muted">
                        Rejected: {{ $course->message_for_rejection }}
                    </p>
                @endif
                </li>
            @endforeach

        </ul>
        </section>
        <!-- Section: Timeline -->
    </div>
</div>
@endsection