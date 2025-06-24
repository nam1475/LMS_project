@extends('admin.course.course-module.course-app')

@section('tab_content')
    <div class="tab-pane fade show active mt-4" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">
        <form action="" class="course-form more_info_form">
            @csrf
            <input type="hidden" name="id" value="{{ request()?->id }}">
            <input type="hidden" name="current_step" value="3">
            <input type="hidden" name="next_step" value="4">
        </form>
        <div class="add_course_content">
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
                            </h2>
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
                                                        class="edit" href="javascript:;"><i class="ti ti-edit"></i></a>
                                                </div>
                                            </li>
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
                                                                class="edit" href="javascript:;"><i class="ti ti-edit"></i></a>
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

                @empty
                    <p   class="text-center">No Data Found</p>
                @endforelse

            </div>
        </div>
    </div>
@endsection
