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
                @forelse ($chapters as $chapter)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse-{{ $chapter->id }}" aria-expanded="true"
                                aria-controls="collapse-{{ $chapter->id }}">
                                <span>{{ $chapter->title }}</span>
                            </button>
                            <div class="add_course_content_action_btn">
                                @if($course->is_current && $isCreateDraft)
                                    <div class="dropdown">
                                        <div class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="far fa-plus"></i>
                                        </div>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li class="add_lesson" data-chapter-id="{{ $chapter->id }}"
                                                data-course-id="{{ $chapter->course_id }}" data-is-create-draft="{{ $isCreateDraft }}">
                                                <a class="dropdown-item" href="javascript:;">
                                                    Add Lesson
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                @endif
                                @if($course->is_current && $isCreateDraft)
                                    <a class="edit edit_chapter" data-course-id="{{ $chapter->course_id }}" data-chapter-id="{{ $chapter->id }}" href="#"><i class="far fa-edit"></i></a>
                                    <a class="del delete-item" href="{{ route('instructor.course-content.destory-chapter', $chapter->id) }}"><i class="fas fa-trash-alt"></i></a>
                                @endif
                            </div>
                        </h2>

                        {{-- @if($diff != null && ($diff[$chapter->id]))
                            <h2 class="accordion-header form-control is-invalid">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse-{{ $diff[$chapter->id]['id'] }}" aria-expanded="true"
                                    aria-controls="collapse-{{ $diff[$chapter->id]['id'] }}">
                                    <span>{{ $diff[$chapter->id]['title'] }}</span>
                                </button>
                                <div class="add_course_content_action_btn">
                                    @if($course->is_current && $isCreateDraft)
                                        <div class="dropdown">
                                            <div class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="far fa-plus"></i>
                                            </div>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li class="add_lesson" data-chapter-id="{{ $chapter->id }}"
                                                    data-course-id="{{ $chapter->course_id }}" data-is-create-draft="{{ $isCreateDraft }}">
                                                    <a class="dropdown-item" href="javascript:;">
                                                        Add Lesson
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </h2>
                        @endif --}}

                        {{-- @if(!$course->is_published)
                            @foreach ($original->chapters as $item)
                                @if($chapter->uuid == $item->uuid)
                                    <h2 class="accordion-header form-control is-invalid">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse-{{ $item->id }}" aria-expanded="true"
                                            aria-controls="collapse-{{ $item->id }}">
                                            <span>{{ $item->title }}</span>
                                        </button>
                                    </h2>
                                @endif
                            @endforeach
                        @endif --}}

                        <div id="collapse-{{ $chapter->id }}" class="accordion-collapse collapse"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <ul class="item_list sortable_list">
                                    @foreach($chapter->lessons ?? [] as $lesson)
                                        <li class="" data-lesson-id="{{ $lesson->id }}" data-chapter-id="{{ $chapter->id }}">
                                            <span>{{ $lesson->title }}</span>
                                            <div class="add_course_content_action_btn">
                                                <a class="edit_lesson" data-lesson-id="{{ $lesson->id }}" data-chapter-id="{{ $chapter->id }}"
                                                    data-course-id="{{ $chapter->course_id }}" data-is-create-draft="{{ $isCreateDraft }}" 
                                                    class="edit" href="javascript:;"><i class="far fa-edit"></i></a>
                                                @if($course->is_current && $isCreateDraft)
                                                    <a class="del delete-item" href="{{ route('instructor.course-content.destroy-lesson', $lesson->id) }}"><i class="fas fa-trash-alt"></i></a>
                                                    <a class="arrow dragger" href="javascript:;"><i class="fas fa-arrows-alt"></i></a>
                                                @endif
                                            </div>
                                        </li>
                                        
                                        {{-- @if($diff != null && $diff[$chapter->id]['lessons'])
                                            <ul class="item_list sortable_list form-control is-invalid">
                                                @foreach($chapter->lessons ?? [] as $lesson)
                                                    <li class="" data-lesson-id="{{ $diff[$chapter->id]['lessons'][$lesson->id]['id'] }}" data-chapter-id="{{ $diff[$chapter->id]['id'] }}">
                                                        <span>{{ $diff[$chapter->id]['lessons'][$lesson->id]['title'] }}</span>
                                                    </li>
                                                @endforeach

                                            </ul>
                                        @endif --}}

                                        {{-- @if(!$course->is_published)
                                            @foreach ($chapter->lessons as $item)
                                                @if($lesson->uuid == $item->uuid)
                                                <ul class="item_list sortable_list form-control is-invalid">
                                                    <li class="" data-lesson-id="{{ $diff[$chapter->id]['lessons'][$lesson->id]['id'] }}" data-chapter-id="{{ $diff[$chapter->id]['id'] }}">
                                                        <span>{{ $diff[$chapter->id]['lessons'][$lesson->id]['title'] }}</span>
                                                    </li>
                                                </ul>
                                            @endforeach
                                        @endif --}}
                                    @endforeach

                                </ul>

                            </div>
                        </div>
                    </div>

                    {{-- @if($diff)
                        <div class="accordion-item">
                            <h2 class="accordion-header form-control is-invalid">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse-{{ $diff[$chapter->id]['id'] }}" aria-expanded="true"
                                    aria-controls="collapse-{{ $diff[$chapter->id]['id'] }}">
                                    <span>{{ $diff[$chapter->id]['title'] }}</span>
                                </button>
                                <div class="add_course_content_action_btn">
                                    @if($course->is_current && $isCreateDraft)
                                        <div class="dropdown">
                                            <div class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="far fa-plus"></i>
                                            </div>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li class="add_lesson" data-chapter-id="{{ $chapter->id }}"
                                                    data-course-id="{{ $chapter->course_id }}" data-is-create-draft="{{ $isCreateDraft }}">
                                                    <a class="dropdown-item" href="javascript:;">
                                                        Add Lesson
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                    @if($course->is_current && $isCreateDraft)
                                        <a class="edit edit_chapter" data-course-id="{{ $chapter->course_id }}" data-chapter-id="{{ $chapter->id }}" href="#"><i class="far fa-edit"></i></a>
                                        <a class="del delete-item" href="{{ route('instructor.course-content.destory-chapter', $chapter->id) }}"><i class="fas fa-trash-alt"></i></a>
                                    @endif
                                </div>
                            </h2>

                            <div id="collapse-{{ $diff[$chapter->id]['id'] }}" class="accordion-collapse collapse"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <ul class="item_list sortable_list form-control is-invalid">
                                        @foreach($chapter->lessons ?? [] as $lesson)
                                        <li class="" data-lesson-id="{{ $diff[$chapter->id]['lessons'][$lesson->id]['id'] }}" data-chapter-id="{{ $diff[$chapter->id]['id'] }}">
                                            <span>{{ $diff[$chapter->id]['lessons'][$lesson->id]['title'] }}</span>
                                            <div class="add_course_content_action_btn">
                                                    <a class="edit_lesson" data-lesson-id="{{ $diff[$chapter->id]['lessons'][$lesson->id]['id'] }}" data-chapter-id="{{ $diff[$chapter->id]['id'] }}"
                                                        data-course-id="{{ $chapter->course_id }}" data-is-create-draft="{{ $isCreateDraft }}" class="edit" href="javascript:;"><i class="far fa-edit"></i></a>
                                                    @if($course->is_current && $isCreateDraft)
                                                        <a class="del delete-item" href="{{ route('instructor.course-content.destroy-lesson', $diff[$chapter->id]['lessons'][$lesson->id]['id']) }}"><i class="fas fa-trash-alt"></i></a>
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
