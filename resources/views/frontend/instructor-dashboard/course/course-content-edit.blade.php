@extends('frontend.instructor-dashboard.course.course-app')

@section('course_content')

    <div class="tab-pane fade show active" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">
        <form action="" class="course-form more_info_form">
            @csrf
            <input type="hidden" name="id" value="{{ request()?->id }}">
            <input type="hidden" name="is_current" id="is_current" value="{{ $course->is_current }}">
            <input type="hidden" name="is_create_draft" id="is_create_draft" value={{ $isCreateDraft }}>
            <input type="hidden" name="current_step" value="2">
            <input type="hidden" name="next_step" value="3">
            
        </form>
        <div class="add_course_content">
            <div class="add_course_content_btn_area d-flex flex-wrap justify-content-between">
                @if($course->is_current && $isCreateDraft)
                    <a class="common_btn dynamic-modal-btn" href="#" data-id="{{ $course->id }}"> Add New Chapter</a>
                    <a class="common_btn sort_chapter_btn" data-id="{{ $course->id }}" href="javascript:;">Sort Chapter</a>
                @endif
            </div>
            <div class="accordion" id="accordionExample">
                {{-- @forelse ($diff as $chapter => $chapter['chapter_draft']) --}}
                @forelse ($diff as $chapter)
                    <div class="accordion-item">
                        @if($chapter['chapter_draft'] != null)
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse-{{ $chapter['chapter_draft']['id'] ?? $chapter['diff']['id'] }}" aria-expanded="true"
                                    aria-controls="collapse-{{ $chapter['chapter_draft']['id'] ?? $chapter['diff']['id'] }}">
                                    <span>{{ $chapter['chapter_draft']['title'] ?? $chapter['diff']['title'] }}</span>
                                </button>
                                <div class="add_course_content_action_btn">
                                    @if($course->is_current && $isCreateDraft)
                                        <div class="dropdown">
                                            <div class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="far fa-plus"></i>
                                            </div>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li class="add_lesson" data-chapter-id="{{ $chapter['chapter_draft']['id'] }}"
                                                    data-course-id="{{ $chapter['chapter_draft']['course_id'] }}" data-is-create-draft="{{ $isCreateDraft }}">
                                                    <a class="dropdown-item" href="javascript:;">
                                                        Add Lesson
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                    @if($course->is_current && $isCreateDraft)
                                        <a class="edit edit_chapter" data-course-id="{{ $chapter['chapter_draft']['course_id'] ?? $chapter['diff']['course_id'] }}" data-chapter-id="{{ $chapter['chapter_draft']['id'] }}" href="#"><i class="far fa-edit"></i></a>
                                        <a class="del delete-item" href="{{ route('instructor.course-content.destory-chapter', $chapter['chapter_draft']['id'] ?? $chapter['diff']['id']) }}"><i class="fas fa-trash-alt"></i></a>
                                    @endif
                                </div>
                            </h2>

                            {{-- Nếu có chapter khác nhau giữa bản nháp và chính --}}
                            @if(($chapter['diff']['title']))
                                <h2 class="accordion-header form-control is-invalid">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-{{ $chapter['diff']['id'] }}" aria-expanded="true"
                                        aria-controls="collapse-{{ $chapter['diff']['id'] }}">
                                        <span>{{ $chapter['diff']['title'] }}</span>
                                    </button>
                                </h2>
                            @endif
                        @else
                            @if(($chapter['diff']['title']))
                                <h2 class="accordion-header form-control is-invalid">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-{{ $chapter['diff']['id'] }}" aria-expanded="true"
                                        aria-controls="collapse-{{ $chapter['diff']['id'] }}">
                                        <span>{{ $chapter['diff']['title'] }}</span>
                                    </button>
                                </h2>
                            @endif
                        @endif

                        <div id="collapse-{{ $chapter['chapter_draft']['id'] ?? $chapter['diff']['id'] }}" class="accordion-collapse collapse"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <ul class="item_list sortable_list">
                                    {{-- Lessons --}}
                                    @foreach($chapter['lessons'] ?? [] as $lesson)
                                        @if($lesson['lesson_draft'] != null)
                                            <li class="" data-lesson-id="{{ $lesson['lesson_draft']['id'] ?? $lesson['diff']['id'] }}" 
                                                data-chapter-id="{{ $chapter['chapter_draft']['id'] ?? $chapter['diff']['id'] }}">
                                                <span>{{ $lesson['lesson_draft']['title'] ?? $lesson['diff']['title'] }}</span>
                                                <div class="add_course_content_action_btn">
                                                    <a class="edit_lesson" data-lesson-id="{{ $lesson['lesson_draft']['id'] ?? $lesson['diff']['id'] }}" 
                                                        data-chapter-id="{{ $chapter['chapter_draft']['id'] ?? $chapter['diff']['id'] }}"
                                                        data-course-id="{{ $chapter['chapter_draft']['course_id'] ?? $chapter['diff']['course_id'] }}" 
                                                        data-is-create-draft="{{ $isCreateDraft }}" 
                                                        class="edit" href="javascript:;"><i class="far fa-edit"></i></a>
                                                    @if($course->is_current && $isCreateDraft)
                                                        <a class="del delete-item" 
                                                        href="{{ route('instructor.course-content.destroy-lesson', $lesson['lesson_draft']['id'] ?? $lesson['diff']['id']) }}">
                                                        <i class="fas fa-trash-alt"></i></a>
                                                        <a class="arrow dragger" href="javascript:;"><i class="fas fa-arrows-alt"></i></a>
                                                    @endif
                                                </div>
                                            </li>
                                            {{-- Nếu có lesson khác nhau giữa bản nháp và chính --}}
                                            @if($lesson['diff']['title'] != null)
                                                <ul class="item_list sortable_list form-control is-invalid">
                                                    <li class="" data-lesson-id="{{ $lesson['diff']['id'] }}" 
                                                        data-chapter-id="{{ $chapter['chapter_draft']['id'] ?? $chapter['diff']['id'] }}">
                                                        <span>{{ $lesson['diff']['title'] }}</span>
                                                    </li>
                                                </ul>
                                            @endif
                                        @else
                                            @if($lesson['diff']['title'] != null)
                                                <ul class="item_list sortable_list form-control is-invalid">
                                                    <li class="" data-lesson-id="{{ $lesson['diff']['id'] }}" 
                                                        data-chapter-id="{{ $chapter['chapter_draft']['id'] ?? $chapter['diff']['id'] }}">
                                                        <span>{{ $lesson['diff']['title'] }}</span>
                                                        <div class="add_course_content_action_btn">
                                                            <a class="edit_lesson" data-lesson-id="{{ $lesson['lesson_draft']['id'] ?? $lesson['diff']['id'] }}" 
                                                                data-chapter-id="{{ $chapter['chapter_draft']['id'] ?? $chapter['diff']['id'] }}"
                                                                data-course-id="{{ $chapter['chapter_draft']['course_id'] ?? $chapter['diff']['course_id'] }}" 
                                                                data-is-create-draft="{{ $isCreateDraft }}" 
                                                                class="edit" href="javascript:;"><i class="far fa-edit"></i></a>
                                                            @if($course->is_current && $isCreateDraft)
                                                                <a class="del delete-item" 
                                                                href="{{ route('instructor.course-content.destroy-lesson', $lesson['lesson_draft']['id'] ?? $lesson['diff']['id']) }}">
                                                                <i class="fas fa-trash-alt"></i></a>
                                                                <a class="arrow dragger" href="javascript:;"><i class="fas fa-arrows-alt"></i></a>
                                                            @endif
                                                        </div>
                                                    </li>
                                                </ul>
                                            @endif
                                        @endif


                                    @endforeach

                                </ul>

                            </div>
                        </div>
                    </div>

                    {{-- @if($diff)
                        <div class="accordion-item">
                            <h2 class="accordion-header form-control is-invalid">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse-{{ $chapter['id'] }}" aria-expanded="true"
                                    aria-controls="collapse-{{ $chapter['id'] }}">
                                    <span>{{ $chapter['id']]['title'] }}</span>
                                </button>
                                <div class="add_course_content_action_btn">
                                    @if($course->is_current && $isCreateDraft)
                                        <div class="dropdown">
                                            <div class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="far fa-plus"></i>
                                            </div>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li class="add_lesson" data-chapter-id="{{ $chapter['id'] }}"
                                                    data-course-id="{{ $chapter['course_id'] }}" data-is-create-draft="{{ $isCreateDraft }}">
                                                    <a class="dropdown-item" href="javascript:;">
                                                        Add Lesson
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                    @if($course->is_current && $isCreateDraft)
                                        <a class="edit edit_chapter" data-course-id="{{ $chapter['course_id'] }}" data-chapter-id="{{ $chapter['id'] }}" href="#"><i class="far fa-edit"></i></a>
                                        <a class="del delete-item" href="{{ route('instructor.course-content.destory-chapter', $chapter['id']) }}"><i class="fas fa-trash-alt"></i></a>
                                    @endif
                                </div>
                            </h2>

                            <div id="collapse-{{ $chapter['id'] }}" class="accordion-collapse collapse"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <ul class="item_list sortable_list form-control is-invalid">
                                        @foreach($chapter['lessons'] ?? [] as $lesson)
                                        <li class="" data-lesson-id="{{ $chapter['id']]['lessons'][$lesson->id]['id'] }}" data-chapter-id="{{ $chapter['id'] }}">
                                            <span>{{ $chapter['id']]['lessons'][$lesson->id]['title'] }}</span>
                                            <div class="add_course_content_action_btn">
                                                    <a class="edit_lesson" data-lesson-id="{{ $chapter['id']]['lessons'][$lesson->id]['id'] }}" data-chapter-id="{{ $chapter['id'] }}"
                                                        data-course-id="{{ $chapter['course_id'] }}" data-is-create-draft="{{ $isCreateDraft }}" class="edit" href="javascript:;"><i class="far fa-edit"></i></a>
                                                    @if($course->is_current && $isCreateDraft)
                                                        <a class="del delete-item" href="{{ route('instructor.course-content.destroy-lesson', $chapter['id']]['lessons'][$lesson->id]['id']) }}"><i class="fas fa-trash-alt"></i></a>
                                                        <a class="arrow dragger" href="javascript:;"><i class="fas fa-arrows-alt"></i></a>
                                                    @endif
                                                </div>
                                        </li>
                                        @endforeach

                                    </ul>

                                </div>
                            </div>
                        </div>
                    @endif --}}
                @empty
                    <p   class="text-center">No Data Found</p>
                @endforelse

            </div>
        </div>
    </div>
@endsection
