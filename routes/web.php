<?php

use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Frontend\CourseReviewController;
use App\Http\Controllers\Frontend\ChatController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\CouponController;
use App\Http\Controllers\Frontend\LessonCommentController;
use App\Http\Controllers\Frontend\CourseContentController;
use App\Http\Controllers\Frontend\CourseController;
use App\Http\Controllers\Frontend\CoursePageController;
use App\Http\Controllers\Frontend\EnrolledCourseController;
use App\Http\Controllers\Frontend\FrontendContactController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\HeroController;
use App\Http\Controllers\Frontend\InstructorDashboardController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\StudentDashboardController;
use App\Http\Controllers\Frontend\StudentOrderController;
use App\Http\Controllers\Frontend\WithdrawController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Payment\VnPayController;
use Illuminate\Support\Facades\Broadcast;

// Broadcast
Broadcast::routes(['middleware' => ['auth:web']]);

//VNPay

Route::get('/payment', [VnPayController::class, 'createPayment'])->name('payment.create');
Route::get('/vnpay-return', [VnPayController::class, 'vnpayReturn'])->name('vnpay.return');

// Route to redirect to Google's OAuth page
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');

// Route to handle the callback from Google
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

/**
 * ------------------------------------------------------
 * Frontend Routes
 * ------------------------------------------------------
 */

//  Route::middleware(['set_auto_coupons'])->group(function () {
   Route::get('/', [FrontendController::class, 'index'])->name('home');
   Route::get('/courses', [CoursePageController::class, 'index'])->name('courses.index');
   Route::get('/courses/{slug}', [CoursePageController::class, 'show'])->name('courses.show');
   Route::get('/courses/{id}/reviews', [CoursePageController::class, 'getReviews'])->name('courses.reviews');


   Route::middleware(['auth:web', 'check_role:student', 'verified'])->group(function () {
      Route::get('cart', [CartController::class, 'index'])->name('cart.index');
      Route::post('add-to-cart/{course}', [CartController::class, 'addToCart'])->name('add-to-cart');
      Route::get('remove-from-cart/{id}', [CartController::class, 'removeFromCart'])->name('remove-from-cart');
      Route::post('cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('apply-coupon');
      Route::get('cart/remove-coupon', [CartController::class, 'removeCoupon'])->name('coupon.remove');
      
      /** Payment Routes */
      Route::get('checkout', CheckoutController::class)->name('checkout.index');
   });
   /** Cart routes */


   //Route::get('paypal/payment', [PaymentController::class, 'payWithPaypal'])->name('paypal.payment');
   //Route::get('paypal/success', [PaymentController::class, 'paypalSuccess'])->name('paypal.success');
   //Route::get('paypal/cancel', [PaymentController::class, 'paypalCancel'])->name('paypal.cancel');

   /** Stripe Routes */
   Route::get('stripe/payment', [PaymentController::class, 'payWithStripe'])->name('stripe.payment');
   Route::get('stripe/success', [PaymentController::class, 'stripeSuccess'])->name('stripe.success');
   Route::get('stripe/cancel', [PaymentController::class, 'stripeCancel'])->name('stripe.cancel');
   /** Razorpay Routes */
   Route::get('razorpay/redirect', [PaymentController::class, 'razorpayRedirect'])->name('razorpay.redirect');
   Route::post('razorpay/payment', [PaymentController::class, 'payWithRazorpay'])->name('razorpay.payment');

   Route::get('order-success', [PaymentController::class, 'orderSuccess'])->name('order.success');
   Route::get('order-failed', [PaymentController::class, 'orderFailed'])->name('order.failed');

   Route::post('newsletter-subscribe', [FrontendController::class, 'subscribe'])->name('newsletter.subscribe');
   /** about route */
   Route::get('about', [FrontendController::class, 'about'])->name('about.index');

   /** Contact route */
   Route::get('contact', [FrontendContactController::class, 'index'])->name('contact.index');
   Route::post('contact', [FrontendContactController::class, 'sendMail'])->name('send.contact');

   /** Review Routes */
   Route::post('review', [CoursePageController::class, 'storeReview'])->name('review.store');

   /** Custom page Routes */
   Route::get('page/{slug}', [FrontendController::class, 'customPage'])->name('custom-page');

   /** Blog Routes */
   Route::get('blog', [BlogController::class, 'index'])->name('blog.index');
   Route::get('blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
   Route::post('blog/comment/{id}', [BlogController::class, 'storeComment'])->name('blog.comment.store');

   
   
   /**
    * ------------------------------------------------------
    * Student Routes
    * ------------------------------------------------------
    */
    Route::group(['middleware' => ['auth:web', 'verified', 'check_role:student'], 'prefix' => 'student', 'as' => 'student.'], function() {
       Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
       Route::get('/become-instructor', [StudentDashboardController::class, 'becomeInstructor'])->name('become-instructor');
       Route::post('/become-instructor/{user}', [StudentDashboardController::class, 'becomeInstructorUpdate'])->name('become-instructor.update');
       
       /** Profile Routes */
       Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
       Route::post('profile/update', [ProfileController::class, 'profileUpdate'])->name('profile.update');
       Route::post('profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
       Route::post('profile/update-social', [ProfileController::class, 'updateSocial'])->name('profile.update-social');
       
       /** Enroll Courses Routes */
      Route::get('enrolled-courses', [EnrolledCourseController::class, 'index'])->name('enrolled-courses.index');
      Route::post('course/enroll-free-course/{id}', [EnrolledCourseController::class, 'enrollFreeCourse'])->name('course.enroll-free-course');
      Route::get('course-player/{slug}', [EnrolledCourseController::class, 'playerIndex'])->name('course-player.index');
      Route::get('get-lesson-content', [EnrolledCourseController::class, 'getLessonContent'])->name('get-lesson-content');
      Route::post('update-watch-history', [EnrolledCourseController::class, 'updateWatchHistory'])->name('update-watch-history');
      Route::post('update-lesson-completion', [EnrolledCourseController::class, 'updateLessonCompletion'])->name('update-lesson-completion');
      Route::get('file-download/{id}', [EnrolledCourseController::class, 'fileDownload'])->name('file-download');
      
      /** Course Comments */
      // Route::get('course/{id}/fetch-comments', [LessonCommentController::class, 'fetchComments'])->name('course.fetch-comments');
      // Route::post('course/{id}/send-comment', [LessonCommentController::class, 'sendComment'])->name('course.send-comment');
      // Route::post('course/{id}/delete-comment', [LessonCommentController::class, 'deleteComment'])->name('course.delete-comment');
      
      /** Lesson comments */
      Route::get('course/lesson/{id}/fetch-comments', [LessonCommentController::class, 'fetchComments'])->name('course.lesson.fetch-comments');
      Route::post('course/lesson/{id}/send-comment', [LessonCommentController::class, 'sendComment'])->name('course.lesson.send-comment');
      Route::post('course/lesson/{id}/delete-comment', [LessonCommentController::class, 'deleteComment'])->name('course.lesson.delete-comment');
      
      /** Certificate Routes */
      Route::get('certificate/{course}/download', [CertificateController::class, 'download'])->name('certificate.download');

      /** Review Routes */
      Route::get('review', [StudentDashboardController::class, 'review'])->name('review.index');
      Route::delete('review/{id}', [StudentDashboardController::class, 'reviewDestroy'])->name('review.destroy');

      Route::get('orders', [StudentOrderController::class, 'index'])->name('orders.index');
      Route::get('orders/{invoice_id}', [StudentOrderController::class, 'show'])->name('orders.show');

      /** Chat Routes */
      Route::get('chats', [ChatController::class, 'index'])->name('chats.index');
      // Route::post('/chats/mark-as-not-read', [ChatController::class, 'markAsNotRead']);
      Route::get('chats/fetch-messages', [ChatController::class, 'fetchMessages'])->name('fetch.messages');
      Route::post('chats/send-message', [ChatController::class, 'sendMessage'])->name('send.message');

      /** Notification Routes */
      Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
      Route::get('notifications/fetch-messages', [NotificationController::class, 'fetchMessages'])->name('notifications.fetch.messages');
      Route::post('notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
      Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');  
      
   });
   
   // Route::middleware(['auth:web'])->group(function () {
      //    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
      //    Route::get('notifications/fetch-messages', [NotificationController::class, 'fetchMessages'])->name('notifications.fetch.messages');
      //    Route::post('notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
      //    Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');  
      // });
      
   /**
    * ------------------------------------------------------
    * Instructor Routes
    * ------------------------------------------------------
    */
   Route::group(['middleware' => ['auth:web', 'verified', 'check_role:instructor'], 'prefix' => 'instructor', 'as' => 'instructor.'], function() {
      Route::get('/dashboard', [InstructorDashboardController::class, 'index'])->name('dashboard');
      
      /** Notification Routes */
      Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
      Route::get('notifications/fetch-messages', [NotificationController::class, 'fetchMessages'])->name('notifications.fetch.messages');
      Route::post('notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
      Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');  
      
      /** Chat Routes */
      Route::get('chats', [ChatController::class, 'index'])->name('chats.index');
      Route::get('chats/fetch-messages', [ChatController::class, 'fetchMessages'])->name('fetch.messages');
      Route::post('chats/send-message', [ChatController::class, 'sendMessage'])->name('send.message');

      /** Lesson Comment Routes */
      Route::get('course/lesson/{id}/fetch-comments', [LessonCommentController::class, 'fetchComments'])->name('course.lesson.fetch-comments');
      Route::post('course/lesson/{id}/send-comment', [LessonCommentController::class, 'sendComment'])->name('course.lesson.send-comment');
      Route::post('course/lesson/{id}/delete-comment', [LessonCommentController::class, 'deleteComment'])->name('course.lesson.delete-comment');
      Route::get('get-lesson-content', [EnrolledCourseController::class, 'getLessonContent'])->name('get-lesson-content');

      /** Profile Routes */
      Route::get('profile', [ProfileController::class, 'instructorIndex'])->name('profile.index');
      Route::post('profile/update', [ProfileController::class, 'profileUpdate'])->name('profile.update');
      Route::post('profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
      Route::post('profile/update-social', [ProfileController::class, 'updateSocial'])->name('profile.update-social');
      Route::post('profile/update-gateway-info', [ProfileController::class, 'updateGatewayInfo'])->name('profile.update-gateway-info');

      /** Notification Routes */
      // Route::get('notifications/fetch-messages', [NotificationController::class, 'fetchMessages'])->name('notifications.fetch.messages')->middleware('auth:web');
      // Route::post('notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');


      /** Course Reviews Routes */
      Route::get('course-reviews', [CourseReviewController::class, 'index'])->name('course-reviews.index');
      Route::get('course-reviews/{id}', [CourseReviewController::class, 'show'])->name('course-reviews.show');

      /** Course Routes */
      Route::get('courses', [CourseController::class, 'index'])->name('courses.index');
      Route::get('courses/create-basic-info', [CourseController::class, 'create'])->name('courses.create');
      Route::post('courses/create', [CourseController::class, 'storeBasicInfo'])->name('courses.store-basic-info');
      Route::get('courses/{id}/edit', [CourseController::class, 'edit'])->name('courses.edit');
      Route::post('courses/update', [CourseController::class, 'update'])->name('courses.update');
      Route::delete('courses/{id}', [CourseController::class, 'destroy'])->name('courses.destroy');
      Route::get('courses/{id}/create-content', [CourseController::class, 'createContent'])->name('course-content.create');
      
      Route::get('course-content/{course}/create-chapter', [CourseContentController::class, 'createChapterModal'])->name('course-content.create-chapter');
      Route::post('course-content/{course}/create-chapter', [CourseContentController::class, 'storeChapter'])->name('course-content.store-chapter');
      Route::get('course-content/{chapter}/edit-chapter', [CourseContentController::class, 'editChapterModal'])->name('course-content.edit-chapter');
      Route::post('course-content/{chapter}/edit-chapter', [CourseContentController::class, 'updateChapterModal'])->name('course-content.update-chapter');
      Route::delete('course-content/{chapter}/chapter', [CourseContentController::class, 'destroyChapter'])->name('course-content.destory-chapter');
   
      Route::get('course-content/create-lesson', [CourseContentController::class, 'createLesson'])->name('course-content.create-lesson');
      Route::post('course-content/create-lesson', [CourseContentController::class, 'storeLesson'])->name('course-content.store-lesson');

      Route::get('course-content/edit-lesson', [CourseContentController::class, 'editLesson'])->name('course-content.edit-lesson');
      Route::post('course-content/{id}/update-lesson', [CourseContentController::class, 'updateLesson'])->name('course-content.update-lesson');
      Route::delete('course-content/{id}/lesson', [CourseContentController::class, 'destroyLesson'])->name('course-content.destroy-lesson');

      Route::post('course-chapter/{chapter}/sort-lesson', [CourseContentController::class, 'sortLesson'])->name('course-chapter.sort-lesson');
      Route::get('course-content/{course}/sort-chapter', [CourseContentController::class, 'sortChapter'])->name('course-content.sort-chpater');
      Route::post('course-content/{course}/sort-chapter', [CourseContentController::class, 'updateSortChapter'])->name('course-content.update-sort-chpater');

      Route::get('courses/{id}/commits', [CourseController::class, 'showCommits'])->name('courses.commits');

      /** Enrolled Students */
      Route::get('courses/{id}/enrolled-students', [CourseController::class, 'enrolledStudents'])->name('courses.enrolled-students');
      
      /** Enroll course for instructor */
      Route::get('course-player/{slug}', [EnrolledCourseController::class, 'playerIndex'])->name('course-player.index');

      /** Orders Routes */
      Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
      Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');

      /** Withdrawal routes */
      Route::get('withdrawals', [WithdrawController::class, 'index'])->name('withdraw.index');
      Route::get('withdrawals/request-payout', [WithdrawController::class, 'requestPayoutIndex'])->name('withdraw.request-payout');
      Route::post('withdrawals/request-payout', [WithdrawController::class, 'requestPayout'])->name('withdraw.request-payout.create');

      /** Coupons Routes */
      Route::resource('coupons', CouponController::class);
   });





   /** lfm Routes */
   Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
         \UniSharp\LaravelFilemanager\Lfm::routes();
   });

// });




require __DIR__.'/auth.php';

require __DIR__.'/admin.php';
