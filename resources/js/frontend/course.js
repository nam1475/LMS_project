const csrf_token = $(`meta[name="csrf_token"]`).attr('content');
const base_url = $(`meta[name="base_url"]`).attr('content');
const basic_info_url = base_url + '/instructor/courses/create';
const update_url = base_url + '/instructor/courses/update';

var notyf = new Notyf({
    duration: 5000,
    dismissible: true
});

var loader = `
<div class="modal-content text-center p-3" style="display:inline">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
`;


// Filter status
$('.filter-status').on('change', function () {
    let status = $(this).val();
    let url = base_url + '/instructor/courses?status=' + status;
    window.location.href = url;
});

//course tab navigation
$('.course-tab').on('click', function (e) {
    e.preventDefault();
    let step = $(this).data('step');
    $('.course-form').find('input[name=next_step]').val(step);
    let isCurrent = $('#is_current').val();
    let isCreateDraft = $('#is_create_draft').val();
    let courseId = $('.course-form').find('input[name=id]').val();
    if(step == 0){
        window.location.href = base_url + '/instructor/courses/' + courseId + '/edit?step=0';
    }
    if(isCurrent == 1 && isCreateDraft == 1){
        $('.course-form').trigger('submit');
    }
    else{
        window.location.href = base_url + '/instructor/courses/' + courseId + '/edit?step=' + step + '&is_create_draft=' + isCreateDraft;
    }
    
});


$('.basic_info_form').on('submit', function (e) {
    e.preventDefault();

    let formData = new FormData(this);
    $.ajax({
        method: "POST",
        url: basic_info_url,
        data: formData,
        contentType: false,
        processData: false,
        beforeSend: function () {

        },
        success: function (data) {
            if (data.status == 'success') {
                notyf.success(data.message);
                setTimeout(function () {
                    window.location.href = data.redirect
                }, 1000);
            }
        },
        error: function (xhr, status, error) {
            console.log(xhr);
            let errors = xhr.responseJSON.errors;
            $.each(errors, function (key, value) {
                notyf.error(value[0]);
            })

        },
        complete: function () { }
    })

});

$('.basic_info_update_form').on('submit', function (e) {
    e.preventDefault();

    let formData = new FormData(this);
    $.ajax({
        method: "POST",
        url: update_url,
        data: formData,
        contentType: false,
        processData: false,
        beforeSend: function () {

        },
        success: function (data) {
            if (data.status == 'success') {
                notyf.success(data.message);
                setTimeout(function () {
                    window.location.href = data.redirect
                }, 1000);
            }
        },
        error: function (xhr, status, error) {
            let errors = xhr.responseJSON.errors;
            $.each(errors, function (key, value) {
                notyf.error(value[0]);
            })
        },
        complete: function () { }
    })

});

$('.more_info_form').on('submit', function (e) {
    e.preventDefault();

    let formData = new FormData(this);
    $.ajax({
        method: "POST",
        url: update_url,
        data: formData,
        contentType: false,
        processData: false,
        beforeSend: function () {

        },
        success: function (data) {
            if (data.status == 'success') {
                notyf.success(data.message);
                setTimeout(function () {
                    window.location.href = data.redirect
                }, 1000);
            }
        },
        error: function (xhr, status, error) {
            let errors = xhr.responseJSON.errors;
            $.each(errors, function (key, value) {
                notyf.error(value[0]);
            })
        },
        complete: function () { }
    })

});


// Code khi DOM đã sẵn sàng
$(document).ready(function () {
    // show hide path input depending on source
    $(document).on('change', '.storage', function () {
        let value = $(this).val();
        $('.source_input').val('');
        if (value == 'upload') {
            $('.upload_source').removeClass('d-none');
            $('.external_source').addClass('d-none');
        } else {
            $('.upload_source').addClass('d-none');
            $('.external_source').removeClass('d-none');
        }
    });

    // function toogleDurationInput() {
    //     let value = $('#file_type').val();
    //     console.log(value);
    //     if (['video', 'audio'].includes(value)) {
    //         $('#duration').removeClass('d-none');
    //         $('#duration_input').attr('required', true);
    //     } else {
    //         $('#duration').addClass('d-none');
    //         $('#duration_input').removeAttr('required');
    //     }
    // }

    // toogleDurationInput();

    // $(document).on('change', '#file_type', function () {
        // toogleDurationInput();
        // console.log(123);
        // let value = $(this).val();
        // if (['video', 'audio'].includes(value)) {
        //     $('#duration').removeClass('d-none');
        //     $('#duration_input').attr('required', true);
        // } else {
        //     $('#duration').addClass('d-none');
        //     $('#duration_input').removeAttr('required');
        // }
    // });
    
});

/** Course Contents */

$('.dynamic-modal-btn').on('click', function (e) {
    e.preventDefault();
    $('#dynamic-modal').modal("show");

    let course_id = $(this).data('id');

    $.ajax({
        method: 'GET',
        url: base_url + '/instructor/course-content/:id/create-chapter'.replace(':id', course_id),
        data: {},
        beforeSend: function () {
            $('.dynamic-modal-content').html(loader);
        },
        success: function (data) {
            $('.dynamic-modal-content').html(data);
        },
        error: function (xhr, status, error) {

        }
    })
});

$('.edit_chapter').on('click', function (e) {
    e.preventDefault();
    $('#dynamic-modal').modal("show");

    let chapter_id = $(this).data('chapter-id');

    $.ajax({
        method: 'GET',
        url: base_url + '/instructor/course-content/:id/edit-chapter'.replace(':id', chapter_id),
        data: {},
        beforeSend: function () {
            $('.dynamic-modal-content').html(loader);
        },
        success: function (data) {
            $('.dynamic-modal-content').html(data);
        },
        error: function (xhr, status, error) {

        }
    })
});



$('.add_lesson').on('click', function() {

    $('#dynamic-modal').modal("show");

    let courseId = $(this).data('course-id');
    let chapterId = $(this).data('chapter-id');
    let isCreateDraft = $(this).data('is-create-draft');

    $.ajax({
        method: 'GET',
        url: base_url + '/instructor/course-content/create-lesson',
        data: {
            'course_id': courseId,
            'chapter_id': chapterId,
            'is_create_draft': isCreateDraft
        },
        beforeSend: function () {
            $('.dynamic-modal-content').html(loader);
        },
        success: function (data) {
            $('.dynamic-modal-content').html(data);
        },
        error: function (xhr, status, error) {

        }
    })
});

$('.edit_lesson').on('click', function() {

    $('#dynamic-modal').modal("show");

    let courseId = $(this).data('course-id');
    let chapterId = $(this).data('chapter-id');
    let lessonId = $(this).data('lesson-id');
    let isCreateDraft = $(this).data('is-create-draft');

    $.ajax({
        method: 'GET',
        url: base_url + '/instructor/course-content/edit-lesson',
        data: {
            'course_id': courseId,
            'chapter_id': chapterId,
            'lesson_id': lessonId,
            'is_create_draft': isCreateDraft
        },
        beforeSend: function () {
            $('.dynamic-modal-content').html(loader);
        },
        success: function (data) {
            $('.dynamic-modal-content').html(data);
        },
        error: function (xhr, status, error) {

        }
    })
});

if($('.sortable_list li').length) {
    $('.sortable_list').sortable({
        items: "li",
        containment: "parent",
        cursor: "move",
        handle: ".dragger",
        update: function(event, ui) {
            let orderIds = $(this).sortable("toArray", {
                attribute: "data-lesson-id",
            });

            let chapterId = ui.item.data("chapter-id");

            $.ajax({
                method: 'POST',
                url: base_url + `/instructor/course-chapter/${chapterId}/sort-lesson`,
                data: {
                    _token: csrf_token,
                    order_ids: orderIds
                },
                success: function(data) {
                    notyf.success(data.message);
                },
                error: function(xhr, status, error) {
                    notyf.error(data.error);
                }
            })

        }
    });
}

$('.sort_chapter_btn').on('click', function() {
    $('#dynamic-modal').modal("show");
    let courseId = $(this).data('id');
    $.ajax({
        method: 'GET',
        url: base_url + `/instructor/course-content/${courseId}/sort-chapter`,
        data: {
        },
        beforeSend: function () {
            $('.dynamic-modal-content').html(loader);
        },
        success: function (data) {
            $('.dynamic-modal-content').html(data);
        },
        error: function (xhr, status, error) {
            notyf.error(error);
        }
    })
});

