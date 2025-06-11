@extends('frontend.instructor-dashboard.course.course-app')

@section('course_content')
<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
    <div class="add_course_basic_info">
        <form action="{{ route('instructor.courses.update') }}" method="post" class="more_info_form course-form"
            enctype="multipart/form-data" novalidate>
            @csrf
            <input type="hidden" name="id" value="{{ $course->id }}">
            <input type="hidden" name="is_current" id="is_current" value={{ $course->is_current }}>
            <input type="hidden" name="is_create_draft" id="is_create_draft" value={{ $isCreateDraft }}>
            <input type="hidden" name="current_step" value="1">
            <input type="hidden" name="next_step" value="2">
            <div class="row">
                <div class="col-xl-12">
                    <div class="add_course_basic_info_imput">
                        <label for="#">Title *</label>
                        <input type="text" placeholder="Title" name="title" value="{{ $course->title }}">
                        <x-input-text-field-change isChange="{{ $diff['title'] }}"
                            value="{{ $diff['title'] }}" />
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="add_course_basic_info_imput">
                        <label for="#">Seo description</label>
                        <input type="text" placeholder="Seo description" name="seo_description"
                            value="{{ $course->seo_description }}">
                        <x-input-text-field-change isChange="{{ $diff['seo_description'] }}"
                            value="{{ $diff['seo_description'] }}" />
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="add_course_basic_info_imput">
                        <label for="#">Thumbnail *</label>
                        {{-- <input type="file" name="thumbnail" value="{{ $course->thumbnail }}"> --}}
                        <div class="">
                            @if($diff['thumbnail'])
                                <div class="mb-4 img">
                                    <img src="{{ asset($diff['thumbnail']) }}"
                                        class="img-fluid w-50 form-control is-invalid" alt="Image" />
                                </div>
                            @endif
                            <div class="mb-4 img">
                                <img id="selectedAvatar" src="{{ asset($course->thumbnail) }}"
                                    class="img-fluid w-50" alt="Image" />
                                <input type="hidden" id="thumbnail-display-default" value="{{$course->thumbnail }}">
                            </div>
                            <div>
                                <div data-mdb-ripple-init class="btn btn-primary btn-rounded">
                                    <label class="form-label text-white m-1" for="thumbnail-input">Choose file</label>
                                    {{-- <input type="file" class="form-control d-none" name="thumbnail" id="thumbnail-input" 
                                         onchange="displaySelectedImage(event, 'selectedAvatar')"/> --}}
                                    <input type="file" class="form-control d-none" name="thumbnail" id="thumbnail-input" 
                                        />
                                </div>
                                <div class="btn btn-danger btn-rounded d-none" id="remove-thumbnail">
                                    <label class="form-label text-white m-1" >Remove file</label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">

                    <div class="add_course_basic_info_imput">
                        <label for="#">Demo Video Storage <b>(optional)</b></label>
                        <select class="storage" name="demo_video_storage">
                            <option value=""></option>
                            <option @selected($course->demo_video_storage == 'upload') value="upload"> Upload </option>
                            <option @selected($course->demo_video_storage == 'youtube') value="youtube"> Youtube
                            </option>
                            <option @selected($course->demo_video_storage == 'vimeo') value="vimeo"> Vimeo </option>
                            <option @selected($course->demo_video_storage == 'external_link') value="external_link">
                                External Link </option>
                        </select>
                        <x-input-text-field-change isChange="{{ $diff['demo_video_storage'] }}"
                            value="{{ $diff['demo_video_storage'] }}" />
                    </div>
                </div>
                <div class="col-xl-6">
                    <div
                        class="add_course_basic_info_imput upload_source {{ $course->demo_video_storage == 'upload' ? '' : 'd-none' }}">
                        <label for="#">Demo Video Source</label>
                        <div class="input-group">
                            <span class="input-group-btn">
                                <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                                    <i class="fa fa-picture-o"></i> Choose
                                </a>
                            </span>
                            <input id="thumbnail" class="form-control source_input" type="text" name="file"
                                value="{{ $course->demo_video_source }}">
                        </div>
                    </div>
                    <div
                        class="add_course_basic_info_imput external_source {{ $course->demo_video_storage != 'upload' ? '' : 'd-none' }}">
                        <label for="#">Demo Video Source</label>
                        <input type="text" name="url" class="source_input" value="{{ $course->demo_video_source }}">
                    </div>
                    <x-input-text-field-change isChange="{{ $diff['demo_video_source'] }}"
                            value="{{ $diff['demo_video_source'] }}" />

                </div>

                <div class="col-xl-6">
                    <div class="add_course_basic_info_imput">
                        <label for="#">Price ({{ config('settings.default_currency') }})*</label>
                        <input type="text" placeholder="Price" name="price" value="{{ $course->price }}">
                        <p>Put 0 for free</p>
                        <x-input-text-field-change isChange="{{ $diff['price'] }}"
                            value="{{ $diff['price'] }}" />
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="add_course_basic_info_imput">
                        <label for="#">Discount Price ({{ config('settings.default_currency') }})</label>
                        <input type="text" placeholder="Price" name="discount" value="{{ $course->discount }}">
                        <x-input-text-field-change isChange="{{ $diff['discount'] }}"
                            value="{{ $diff['discount'] }}" />
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="add_course_basic_info_imput mb-0">
                        <label for="#">Description</label>
                        <textarea rows="8" placeholder="Description" name="description"
                            class="editor">{!! $course->description !!}</textarea>
                        <x-input-text-field-change isChange="{{ $diff['description'] }}"
                            value="{{ $diff['description'] }}" />
                    </div>
                </div>

                {{-- More info --}}
                <div class="col-xl-6">
                    <div class="add_course_more_info_input">
                        <label for="#">Capacity</label>
                        <input type="text" placeholder="Capacity" name="capacity" value="{{ $course?->capacity }}">
                        <p>leave blank for unlimited</p>
                        <x-input-text-field-change isChange="{{ $diff['capacity'] }}"
                            value="{{ $diff['capacity'] }}" />
                    </div>
                </div>
                {{-- <div class="col-xl-6">
                    <div class="add_course_more_info_input">
                        <label for="#">Course Duration (Minutes)*</label>
                        <input type="text" placeholder="300" name="duration" value="{{ $course->duration }}">
                    </div>
                </div> --}}
                <div class="col-xl-6">
                    <div class="add_course_more_info_checkbox">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="qna" @checked($course?->qna === 1) value="1" id="flexCheckDefault">
                            <x-checkbox-field-change isChange="{{ $diff['qna'] }}" value="{{ $diff['qna'] }}" />
                            <label class="form-check-label" for="flexCheckDefault">Q&A</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" @checked($course?->certificate === 1) name="certificate" value="1" id="flexCheckDefault2">
                            <label class="form-check-label" for="flexCheckDefault2">Completion Certificate</label>
                            <x-checkbox-field-change isChange="{{ $diff['certificate'] }}" value="{{ $diff['certificate'] }}" />
                        </div>
                        
                    </div>
                </div>
                <div class="col-12">
                    <div class="add_course_more_info_input">
                        <label for="#">Category *</label>
                        <select class="select_2" name="category">
                            <option value=""> Please Select </option>
                            @foreach($categories as $category)
                                @if($category->subCategories->isNotEmpty())
                                <optgroup label="{{ $category->name }}">
                                   @foreach($category->subCategories as $subCategory) 
                                        <option @selected($course?->category_id == $subCategory->id) value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                   @endforeach
                                </optgroup>
                                @endif
                            @endforeach
                        </select>
                        
                        @if($diff['category_id'])
                            <select>
                                <option value="" disabled> Please Select </option>
                                @foreach($categories as $category)
                                    @if($category->subCategories->isNotEmpty())
                                    <optgroup label="{{ $category->name }}" disabled>
                                    @foreach($category->subCategories as $subCategory) 
                                            <option disabled @selected($course?->category_id == $diff['category_id']) value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                    @endforeach
                                    </optgroup>
                                    @endif
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="add_course_more_info_radio_box">
                        <h3>Level</h3>
                        @foreach($levels as $level)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" @checked($level->id == $course?->course_level_id) value="{{ $level->id }}" name="level" id="id-{{ $level->id }}">
                            <label class="form-check-label" for="id-{{ $level->id }}">
                                {{ $level->name }}
                            </label>
                        </div>
                        @endforeach

                        @if($diff['course_level_id'])
                            @foreach($levels as $level)
                            <div class="form-check">
                                <input class="form-control is-invalid disabled" disabled type="radio" @checked($level->id == $diff['course_level_id']) value="{{ $level->id }}">
                                <label class="form-check-label" for="id-{{ $level->id }}">
                                    {{ $level->name }}
                                </label>
                            </div>
                            @endforeach
                        @endif
                        
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="add_course_more_info_radio_box">
                        <h3>Language</h3>
                        @foreach($languages as $language)
                        <div class="form-check">
                            <input class="form-check-input" @checked($language->id == $course?->course_language_id) type="radio" name="language"
                                value="{{ $language->id }}"
                                id="id-{{ $language->id }}">
                            <label class="form-check-label" for="id-{{ $language->id }}">
                                {{ $language->name }}
                            </label>
                        </div>
                        @endforeach

                        @if($diff['course_language_id'])
                            @foreach($languages as $language)
                            <div class="form-check">
                                <input class="form-check-input" disabled @checked($language->id == $course?->course_language_id) type="radio">
                                <label class="form-check-label" for="id-{{ $language->id }}">
                                    {{ $language->name }}
                                </label>
                            </div>
                            @endforeach
                        @endif

                    </div>
                </div>

                <div class="col-xl-12">
                    <div class="add_course_more_info_input">
                        <label for="#">Message for Reviewer</label>
                        <textarea rows="7" placeholder="Message for Reviewer" name="message_for_reviewer">{!! @$course?->message_for_reviewer !!}</textarea>
                        <x-input-text-field-change isChange="{{ $diff['message_for_reviewer'] }}" value="{{ $diff['message_for_reviewer'] }}" />
                    </div>
                </div>
                @if($course->is_current && $isCreateDraft)
                    <div class="col-xl-12">
                        <button type="submit" class="common_btn">Save</button>
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    
    <script>
        $('#lfm').filemanager('file');

    </script>
    @vite(['resources/js/upload.js'])
@endpush