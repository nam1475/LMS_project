<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Lession</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <form action="{{ @$editMode == true ?
        route('instructor.course-content.update-lesson', $lesson->id) :
        route('instructor.course-content.store-lesson') }}" method="POST">
            @csrf
            <input type="hidden" name="course_id" value="{{ $course->id }}">
            <input type="hidden" name="chapter_id" value="{{ $chapterId }}">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mb-3 add_course_basic_info_imput">
                        <label for="">Title</label>
                        <input type="text" class="form-control" name="title" value="{{ @$lesson->title }}">
                        @isset($diff['title'])
                            <x-input-text-field-change isChange="{{ $diff['title'] }}"
                                value="{{ $diff['title'] }}" />
                        @endisset
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="">Source</label>
                        <select name="source" class="add_course_basic_info_imput storage">
                            <option value=""></option>
                            @foreach(config('course.video_sources') as $source => $name)
                            <option @selected(@$lesson->storage == $source) value="{{ $source }}">{{ $name }}</option>
                            @endforeach
                            @isset($diff['storage'])
                                <x-input-text-field-change isChange="{{ $diff['storage'] }}"
                                    value="{{ $diff['storage'] }}" />
                            @endisset
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    {{-- @: Bỏ qua lỗi khi biến không tồn tại (suppress error)  
                    $lesson?->storage: Bỏ qua lỗi khi biến null
                    --}}
                    <div class="add_course_basic_info_imput upload_source {{ @$lesson->storage == 'upload' ? '' : 'd-none' }}">
                        <label for="#">Path</label>
                        <div class="input-group">
                            <span class="input-group-btn">
                              <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                                <i class="fa fa-picture-o"></i> Choose
                              </a>
                            </span>
                            <input id="thumbnail" class="form-control source_input" type="text" name="file" value="{{ @$lesson->file_path }}" >
                          </div>
                    </div>
                    <div class="add_course_basic_info_imput external_source {{ @$lesson->storage != 'upload' ? '' : 'd-none' }}">
                        <label for="#">Path</label>
                        <input type="text" name="url" class="source_input" value="{{ @$lesson->file_path }}">
                    </div>
                    @isset($diff['file_path'])
                        <x-input-text-field-change isChange="{{ $diff['file_path'] }}"
                                value="{{ $diff['file_path'] }}" />
                    @endisset


                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3 ">
                        <label for="">File Type</label>
                        <select name="file_type" id="file_type" class="add_course_basic_info_imput">
                            <option value=""></option>
                            @foreach(config('course.file_types') as $source => $name)
                            <option @selected(@$lesson->file_type == $source) value="{{ $source }}">{{ $name }}</option>
                            @endforeach
                            @isset($diff['file_type'])
                                <x-input-text-field-change isChange="{{ $diff['file_type'] }}"
                                    value="{{ $diff['file_type'] }}" />
                            @endisset
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    {{-- <div class="form-group mb-3 add_course_basic_info_imput duration d-none {{ !in_array(@$lesson->file_type, ['doc', 'pdf', 'file']) ? '' : 'd-none' }}"> --}}
                    <div class="form-group mb-3 add_course_basic_info_imput" id="duration">
                        <label for="">Duration (Minutes)</label>
                        <i class="far fa-question-circle" id="duration_tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Leave blank if file type is not video, audio"></i>
                        <input type="text" id="duration_input" name="duration" value="{{ @$lesson->duration }}">
                        @isset($diff['duration'])
                            <x-input-text-field-change isChange="{{ $diff['duration'] }}"
                                value="{{ $diff['duration'] }}" />
                        @endisset
                    </div>
                </div>


                <div class="col-xl-6">
                    <div class="add_course_more_info_checkbox">
                        <div class="form-check" style="width: 200px">
                            <input @checked(@$lesson->is_preview === 1) class="form-check-input" type="checkbox" name="is_preview" value="1" id="preview">
                            @isset($diff['is_preview'])
                                <x-checkbox-field-change isChange="{{ $diff['is_preview'] }}" value="{{ $diff['is_preview'] }}" />
                            @endisset
                            <label class="form-check-label" for="preview">Is Preview</label>
                        </div>
                        <div class="form-check" style="width: 200px">
                            <input @checked(@$lesson->downloadable === 1) class="form-check-input" type="checkbox" name="downloadable" value="1" id="downloadable">
                            @isset($diff['downloadable'])
                                <x-checkbox-field-change isChange="{{ $diff['downloadable'] }}" value="{{ $diff['downloadable'] }}" />
                            @endisset
                            <label class="form-check-label" for="downloadable">Downloadable</label>
                        </div>


                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label for="">Description</label>
                        <textarea name="description" class="add_course_basic_info_imput" id="" cols="30" rows="10">{!! @$lesson->description !!}</textarea>
                        @isset($diff['description'])
                            <x-input-text-field-change isChange="{{ $diff['description'] }}"
                                value="{{ $diff['description'] }}" />
                        @endisset
                    </div>
                </div>

                @if($course->is_current && $isCreateDraft)
                    <div class="form-group text-end">
                        <button type="submit" class="btn btn-primary">{{ @$editMode ? 'Update' : 'Create' }}</button>
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>

<script>
    $('#lfm').filemanager('file');
    
    $(function () {
        const exampleEl = $('#duration_tooltip');
        const tooltip = new bootstrap.Tooltip(exampleEl);
    })
</script>
    
