/** variables */
const base_url = $(`meta[name="base_url"]`).attr('content');
const csrf_token= $(`meta[name="csrf_token"]`).attr('content');

/** reusable functions */
function addToCart(courseId) {
    $.ajax({
        method: "POST",
        url: base_url + "/add-to-cart/" + courseId,
        data: {
            _token: csrf_token
        },
        beforeSend: function() {
            $('.add_to_cart').text('Adding...');
        },
        success: function(data) {
            $('.cart_count').html(data.cart_count);
            notyf.success(data.message);

            $('.add_to_cart').text('Add To Cart');
        },
        error: function(xhr, status, error) {
            console.log(xhr);
            let errorMessage = xhr.responseJSON.message;
            notyf.error(errorMessage);

            $('.add_to_cart').text('Add To Cart');
        }

    });
}

/** On Dom Load */
$(function() {
   /** add course into cart */
   $('.add_to_cart').on('click', function (e) {
       e.preventDefault();
        let courseId = $(this).data('course-id');
       addToCart(courseId);
   })

   /** Aplly coupon */
//    $('.apply_coupon_form').on('submit', function (e) {
//         e.preventDefault();
//         var routeName = $(this).data('route');
//         var totalPrice = $(this).data('total-price');
//         let couponCode = $('#coupon_code').val();
//         let discountAmount = $('#discount_amount').val();
//         console.log(discountAmount);
        
//         $.ajax({
//             method: "POST",
//             url: routeName,
//             data: {
//                 _token: csrf_token,
//                 coupon_code: couponCode,
//                 total_price: totalPrice
//             },
//             beforeSend: function() {
//                 // $('.apply_coupon_form').text('Applying...');
//             },
//             success: function(data) {
//                 notyf.success(data.message);
//             //    $('.apply_coupon_form').text('Apply');
//                 // window.href = data.redirect;
//             },
//             error: function(xhr, status, error) {
//                 console.log(xhr);
//                 let errorMessage = xhr.responseJSON.message;
//                 notyf.error(errorMessage);

//             //    $('.apply_coupon_form').text('Apply');
//             }
//        });
//    });
});

$(function() {
    /** Show modal coupon code */
    $('#show_coupon_modal').on('click', function (e) {
        e.preventDefault();
        $('#coupon_modal').modal('show');
    });

    /** Click coupon code */
    $('.coupon_code_card').on('click', function (e) {
        e.preventDefault();
        let couponCode = $(this).find('#coupon_code').data('code');
        $('#coupon_code_input').val(couponCode);
    }); 
});

$(function() {
    var itemsPerPage = 2;
    var items = $('.coupon_code_card');
    var totalItems = items.length;
    var totalPages = Math.ceil(totalItems / itemsPerPage);
    // var pageItem = $('.page-item');
    var currentPage = 1;

    function renderPagination() {
        $('.pagination').empty();

        // $('.pagination').append(`
        //     <li class="page-item">
        //         <a class="page-link" id="prev">&lt;</a>
        //     </li>
        // `);
        
        // Tạo nút phân trang
        for (let i = 1; i <= totalPages; i++) {
            $('.pagination').append(`
                <li class="page-item">
                    <a class="page-link ${i == currentPage ? 'active' : ''}">${i}</a> 
                </li>
            `);
        }

        // $('.pagination').append(`
        //     <li class="page-item">
        //         <a class="page-link" id="next">&gt;</a> 
        //     </li>
        // `);
    }

    function showPage(page) {
        currentPage = page;
        var start = (page - 1) * itemsPerPage;
        var end = start + itemsPerPage;
        
        items.hide();
        items.slice(start, end).show();
        renderPagination();
    }
    
    showPage(1);

    // Sự kiện khi click nút phân trang
    $('.pagination').on('click', '.page-link', function(){
        var page = parseInt($(this).text());
        showPage(page);
    });

    // $('.pagination').on('click', '#prev', function(){
    //     if (currentPage > 1) {
    //         showPage(currentPage - 1);
    //     }
    // });

    // $('.pagination').on('click', '#next', function(){        
    //     if (currentPage < totalPages) {
    //         showPage(currentPage + 1);
    //     }
    // });

});


