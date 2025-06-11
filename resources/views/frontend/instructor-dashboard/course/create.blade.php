@extends('frontend.instructor-dashboard.course.course-app')

@section('course_content')
<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
    <div class="add_course_basic_info">
        <form action="{{ route('instructor.courses.store-basic-info') }}" method="post" class="basic_info_form course-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="current_step" value="1">
            <input type="hidden" name="next_step" value="2">
            <div class="row">
                <div class="col-xl-12">
                    <div class="add_course_basic_info_imput">
                        <label for="#">Title *</label>
                        <input type="text" placeholder="Title" name="title">
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="add_course_basic_info_imput">
                        <label for="#">Seo description</label>
                        <input type="text" placeholder="Seo description" name="seo_description">
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="add_course_basic_info_imput">
                        <label for="#">Thumbnail *</label>
                        {{-- <input type="file" name="thumbnail"> --}}
                        <div class="">
                            <div class="mb-4 img">
                                <img id="selectedAvatar" src=""
                                    class="img-fluid w-50" alt="Image" />
                            </div>
                            <div>
                                <div data-mdb-ripple-init class="btn btn-primary btn-rounded">
                                    <label class="form-label text-white m-1" for="thumbnail-input">Choose file</label>
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
                            <option value=""> Please Select </option>
                            <option value="upload"> Upload </option>
                            <option value="youtube"> Youtube </option>
                            <option value="vimeo"> Vimeo </option>
                            <option value="external_link"> External Link </option>
                        </select>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="add_course_basic_info_imput upload_source">
                        <label for="#">Demo Video Source</label>
                        <div class="input-group">
                            <span class="input-group-btn">
                              <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                                <i class="fa fa-picture-o"></i> Choose
                              </a>
                            </span>
                            <input id="thumbnail" class="form-control source_input" type="text" name="demo_video_source" >
                          </div>
                    </div>
                    <div class="add_course_basic_info_imput external_source d-none">
                        <label for="#">Demo Video Source</label>
                        <input type="text" name="demo_video_source" class="source_input">
                    </div>
    
    
                </div>
                <div class="col-xl-6">
                    <div class="add_course_basic_info_imput">
                        <label for="#">Price ({{ config('settings.default_currency') }})*</label>
                        <input type="number" placeholder="Price" name="price">
                        <p>Put 0 for free</p>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="add_course_basic_info_imput">
                        <label for="#">Discount Price ({{ config('settings.default_currency') }})</label>
                        <input type="number" placeholder="Price" name="discount">
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="add_course_basic_info_imput mb-0">
                        <label for="#">Description</label>
                        <textarea rows="8" placeholder="Description" name="description" class="editor"></textarea>
                        {{-- <button type="submit" class="common_btn mt_20">Save</button> --}}
                    </div>
                </div>

                {{-- More info --}}
                <div class="col-xl-6">
                    <div class="add_course_more_info_input">
                        <label for="#">Capacity</label>
                        <input type="text" placeholder="Capacity" name="capacity">
                        <p>leave blank for unlimited</p>
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
                            <input class="form-check-input" type="checkbox" name="qna" value="1" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">Q&A</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="certificate" value="1" id="flexCheckDefault2">
                            <label class="form-check-label" for="flexCheckDefault2">Completion Certificate</label>
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
                                        <option value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                   @endforeach
                                </optgroup>
                                @endif
                            @endforeach
                            
                        </select>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="add_course_more_info_radio_box">
                        <h3>Level</h3>
                        @foreach($levels as $level)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="{{ $level->id }}" name="level" id="id-{{ $level->id }}">
                            <label class="form-check-label" for="id-{{ $level->id }}">
                                {{ $level->name }}
                            </label>
                        </div>
                        @endforeach
                        
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="add_course_more_info_radio_box">
                        <h3>Language</h3>
                        @foreach($languages as $language)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="language"
                                value="{{ $language->id }}"
                                id="id-{{ $language->id }}">
                            <label class="form-check-label" for="id-{{ $language->id }}">
                                {{ $language->name }}
                            </label> 
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="add_course_more_info_input">
                        <label for="#">Message for Reviewer</label>
                        <textarea rows="7" placeholder="Message for Reviewer" name="message_for_reviewer"></textarea>
                    </div>
                </div>
                <div class="col-xl-12">
                    <button type="submit" class="common_btn">Save</button>
                </div>
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