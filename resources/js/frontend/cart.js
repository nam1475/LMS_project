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

   /** Notify when apply wrong coupon */
   $('.apply_coupon').on('submit', function (e) {
       e.preventDefault();
       var form = $(this)[0];
       var data = new FormData(form);
       console.log(data);
       console.log(123);
       
       

    //    let couponCode = $('#coupon_code').val();
    //    $.ajax({
    //        method: "POST",
    //        url: base_url + "/apply-coupon",
    //        data: {
    //            _token: csrf_token,
    //            coupon_code: couponCode
    //        },
    //        beforeSend: function() {
    //            $('.apply_coupon').text('Applying...');
    //        },
    //        success: function(data) {
    //            notyf.success(data.message);
    //            $('.apply_coupon').text('Apply');
    //        },
    //        error: function(xhr, status, error) {
    //            console.log(xhr);
    //            let errorMessage = xhr.responseJSON.message;
    //            notyf.error(errorMessage);

    //            $('.apply_coupon').text('Apply');
    //        }
    //    });
   });

});


