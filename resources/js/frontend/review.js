const base_url = $(`meta[name="base_url"]`).attr('content');

var loader = `
<div class="modal-content text-center p-3" style="display:inline">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
`;

$(function() {
    $('.filter-rating').on('change', function() {
        let reviewContainter = $('.reviews-containter');
        let courseId = $(this).data('course-id');        

        $.ajax({
            url: `${base_url}/courses/${courseId}/reviews`,
            type: 'GET',
            data: {
                rating: $(this).val(),
            },
            beforeSend: function() {
                reviewContainter.html(loader);
            },
            success: function(data) {
                let html = ``;
                
                if(data.reviews.length == 0) {
                    html += `
                        <div class="wsus__course_single_reviews text-center">
                            <div class="wsus__single_review_text">
                                <h4>No reviews</h4>
                            </div>
                        </div>
                    `;
                    reviewContainter.html(html);
                    return;
                }

                data.reviews.forEach(function(item) {
                    let dateTime = new Date(item.created_at).toLocaleString('vi-VN', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                    });
                    html += `
                        <div class="wsus__course_single_reviews">
                            <div class="wsus__single_review_img">
                                <img src="${base_url}${item.user.image}" alt="user" class="img-fluid">
                            </div>
                            <div class="wsus__single_review_text">
                                <h4>${item.user.name}</h4>
                                <h6>${dateTime}
                                    <span>
                                    `;
                                    for (var i = 0; i < item.rating; i++) {
                                        html += `<i class="fas fa-star"></i>`;
                                    }
                        html += `
                                    </span>
                                </h6>
                                <p>${item.review}</p>
                            </div>
                        </div>
                    `;
                })
                reviewContainter.html(html);
            }
        });

    });
});