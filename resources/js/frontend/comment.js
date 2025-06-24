import '../echo';

const baseUrl = $(`meta[name="base_url"]`).attr('content');
const csrfToken = $(`meta[name="csrf_token"]`).attr('content');
const currentUser = JSON.parse($('#current-user').val());

var loader = `
<div class="text-center p-3" style="display:inline">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
`;

var pusher = new window.Pusher('b47a559876f1d3cfe9ac', {
    cluster: 'ap1',
    encrypted: true
});

var channel = pusher.subscribe('course.comment');

// Bind comments
// window.Echo.private('course.comment')
// .listen('.course.comment', function(data) { 
channel.bind('course.comment', function (data) {
    if(data){
        var sender = data.sender;
        if(data.isReplied){
            // let commentContainer = $(`#child-comment-container-${data.commentId}`);
            let replyContainer = $(`#reply-container-${data.commentId}`);
            let html = `
                <div class="course-review-head">    
                    <div class="review-author-thumb">
                        <img src="${sender.image}" alt="img">
                    </div>
                    <div class="review-author-content">
                        <div class="author-name">
                            <h5 class="name">${sender.name}${sender.role == 'instructor' ? ' (Instructor)' : ''}<span>${data.time}</span></h5>
                        </div>
                        <div class="review-content">
                            <p>${data.comment}</p>
                        </div>
                    </div>
                </div>
            `;
            replyContainer.append(html);
            $('#total-replies').text(data.totalComments);
            return;
        }

        let commentContainer = $(`#comment-container-${data.lessonId}`);
        let html = `
            <div class="course-review-head">    
                <div class="review-author-thumb">
                    <img src="${sender.image}" alt="img">
                </div>
                <div class="review-author-content">
                    <div class="author-name">
                        <h5 class="name">${sender.name}${sender.role == 'instructor' ? ' (Instructor)' : ''}<span>${data.time}</span></h5>
                    </div>
                    <div class="review-content">
                        <p>${data.comment}</p>
                    </div>
                </div>
                <div class="text-end ms-3">
                    <div class="mb-1 text-muted" style="font-size: 14px;">
                        <a href="javascript:;" class="mt-2 link-secondary" data-comment-id="${data.comment.id}" id="reply"><i class="fas fa-comments"></i></a>
                    </div>
                </div>
            </div>
        `;
        $(`#total-lesson-comments-${data.lessonId}`).text(data.totalComments);
        $(`#no-comment-${data.lessonId}`).remove();
        commentContainer.append(html);
    }
}); 

 $(function() {
    $('.lesson').on('click', function() {
        let lessonId = $(this).data('lesson-id');
        $('#lesson-id').val(lessonId);
        $('.total-lesson-comments').attr('id', `total-lesson-comments-${lessonId}`);
        $('.comments').attr('id', `comment-container-${lessonId}`);
        let commentContainer = $(`#comment-container-${lessonId}`);

        $.ajax({
            method: 'GET',
            url: `${baseUrl}/${currentUser.role}/course/lesson/${lessonId}/fetch-comments`,
            data: {},
            beforeSend: function() {
                commentContainer.html(loader);
            },
            success: function(response) {
                $('#all-comments').show();
                commentContainer.html('');
                
                var html = ``;
                if(response.comments.length == 0) {
                    html += `
                        <div class="wsus__course_single_reviews text-center" id="no-comment-${lessonId}">
                            <div class="wsus__single_review_text">
                                <h4>No comments!</h4>
                            </div>
                        </div>
                    `;
                    commentContainer.html(html);
                    $(`#total-lesson-comments-${lessonId}`).text(response.comments.length);
                    return;
                }

                response.comments.forEach(function(comment) {
                    var user = comment.commentator;
                    var commentTime = new Date(comment.created_at).toLocaleString('vi-VN', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    html = `
                        <div class="course-review-head">    
                            <div class="review-author-thumb">
                                <img src="${user.image}" alt="img">
                            </div>
                            <div class="review-author-content">
                                <div class="author-name">
                                    <h5 class="name">${user.name}${user.role == 'instructor' ? ' (Instructor)' : ''}<span>${commentTime}</span></h5>
                                </div>
                                <div class="review-content">
                                    <p>${comment.comment}</p>
                                </div>
                            </div>
                            <div class="text-end ms-3">
                                <div class="mb-1 text-muted" style="font-size: 14px;">
                                    <a href="javascript:;" class="mt-2 link-secondary" data-comment-id="${comment.id}" id="reply"><i class="fas fa-comments"></i></a>
                                </div>
                            </div>
                        </div>
                    `;
                    commentContainer.append(html);
                });
                $(`#total-lesson-comments-${lessonId}`).text(response.comments.length);

            },
            error: function(xhr, status, error) {
                console.log(xhr.responseJSON.message);
            }
        });

    });

    // Fetch comments
    $('#pills-comment-tab').on('click', function(e) {
        // e.preventDefault();
        // let lessonId = $('.lesson').data('lesson-id');
        let lessonId = $('#lesson-id').val();
        $('.total-lesson-comments').attr('id', `total-lesson-comments-${lessonId}`);
        $('.comments').attr('id', `comment-container-${lessonId}`);
        let commentContainer = $(`#comment-container-${lessonId}`);
        // let childCommentContainer = $(`#child-comment-container-${commentId}`);
        
        $.ajax({
            method: 'GET',
            url: `${baseUrl}/${currentUser.role}/course/lesson/${lessonId}/fetch-comments`,
            data: {},
            success: function(response) {
                // childCommentContainer.html('');
                commentContainer.html('');
                $('#all-comments').show();
                $('#comment-id').val('');

                var html = ``;
                if(response.comments.length == 0) {
                    html += `
                        <div class="wsus__course_single_reviews text-center" id"no-comment-${lessonId}">
                            <div class="wsus__single_review_text">
                                <h4>No comments!</h4>
                            </div>
                        </div>
                    `;
                    commentContainer.html(html);
                    $(`#total-lesson-comments-${lessonId}`).text(response.comments.length);
                    return;
                }

                response.comments.forEach(function(comment) {
                    var user = comment.commentator;
                    var commentTime = new Date(comment.created_at).toLocaleString('vi-VN', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    html = `
                        <div class="course-review-head">    
                            <input type="hidden" id="user-id" value="${user.id}">
                            <div class="review-author-thumb">
                                <img src="${user.image}" alt="img">
                            </div>
                            <div class="review-author-content">
                                <div class="author-name">
                                    <h5 class="name">${user.name}${user.role == 'instructor' ? ' (Instructor)' : ''}<span>${commentTime}</span></h5>
                                </div>
                                <div class="review-content">
                                    <p>${comment.comment}</p>
                                </div>
                            </div>
                            <div class="text-end ms-3">
                                <div class="mb-1 text-muted" style="font-size: 14px;">
                                    <a href="javascript:;" class="mt-2 link-secondary" data-comment-id="${comment.id}" id="reply"><i class="fas fa-comments"></i></a>
                                </div>
                            </div>
                        </div>
                    `;
                    commentContainer.append(html);
                });
                $(`#total-lesson-comments-${lessonId}`).text(response.comments.length ?? 0);
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseJSON.message);
            }
        });
    });

    $(document).on('click', '#reply', function(e) {
    // $('#reply').on('click', function(e) {
        let lessonId = $('#lesson-id').val();
        let commentId = $(this).data('comment-id');
        // $('.child-comment-container').attr('id', `child-comment-container-${commentId}`);
        $('.comments').attr('id', `comment-container-${commentId}`);
        // let childCommentContainer = $(`#child-comment-container-${commentId}`);
        let childCommentContainer = $(`#comment-container-${commentId}`);
        $(`#comment-id`).val(commentId);
        
        $.ajax({
            method: 'GET',
            url: `${baseUrl}/${currentUser.role}/course/lesson/${lessonId}/fetch-comments`,
            data: {
                comment_id: commentId
            },
            beforeSend: function() {
                childCommentContainer.html(loader);
            },
            success: function(response) {
                $('#all-comments').hide();
                $(`#comment-container-${lessonId}`).html('');
                childCommentContainer.html('');
                
                let userRepliedComment = response.comments;
                let childComments = userRepliedComment.child_comments;
                let userReplied = userRepliedComment.commentator;
                let commentTime = new Date(userRepliedComment.created_at).toLocaleString('vi-VN', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });                                

                var html = ``;
                html = `
                    <div class="container my-4">
                        <!-- Back button -->

                        <!-- Question -->
                        <div class="course-review-head">    
                            <div class="review-author-thumb">
                                <img src="${userReplied.image}" alt="img">
                            </div>
                            <div class="review-author-content">
                                <div class="author-name">
                                    <h5 class="name">${userReplied.name}${userReplied.role == 'instructor' ? ' (Instructor)' : ''}<span>${commentTime}</span></h5>
                                </div>  
                                <div class="review-content">
                                    <p>${userRepliedComment.comment}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Replies title -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 fw-bold">
                                <b id="total-replies">${childComments.length}</b> replies
                            </h6>
                        </div>

                        <!-- Reply 1 -->
                        <div class="ms-4" id="reply-container-${commentId}"> 
                `;
                childComments.forEach(function(comment) {
                    var user = comment.commentator;
                    var commentTime = new Date(comment.created_at).toLocaleString('vi-VN', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    html += `
                            <div class="course-review-head">    
                                <input type="hidden" id="user-id" value="${user.id}">
                                <div class="review-author-thumb">
                                    <img src="${user.image}" alt="img">
                                </div>
                                <div class="review-author-content">
                                    <div class="author-name">
                                        <h5 class="name">${user.name}${user.role == 'instructor' ? ' (Instructor)' : ''}<span>${commentTime}</span></h5>
                                    </div>
                                    <div class="review-content">
                                        <p>${comment.comment}</p>
                                    </div>
                                </div>
                            </div>
                    `;
                });
                html += `
                    </div>
                </div>
                `;
                childCommentContainer.append(html);
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseJSON.message);
            }
        });
        
    });


    // Send comment
    $(document).on('submit', '#send-comment', function(e) {
        e.preventDefault();

        let lessonId = $('#lesson-id').val();
        let commentContainer = $('#comment-container');
        let comment = $('#comment').val();
        let commentId = $('#comment-id').val();
        if (comment == "") {
            notyf.error('Please enter a comment.');
            return;
        }

        $.ajax({
            method: 'POST',
            url: `${baseUrl}/${currentUser.role}/course/lesson/${lessonId}/send-comment`,
            data: {
                '_token' : csrfToken,
                'comment': comment,
                ...(commentId != '' && { comment_id: commentId })
            },
            beforeSend: function() {
                $('#send-comment').html(loader);
            },
            success: function(response) {
                $('#send-comment').html(`
                    <input type="hidden" id="comment-id" value="${response.commentId}">
                    <textarea name="comment" id="comment" cols="30" rows="5" placeholder="Your comment..."></textarea>
                    <button type="submit" class="common_btn">Submit</button>
                `);
                notyf.success(response.message);
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseJSON.message);
            }
        })
    });

});