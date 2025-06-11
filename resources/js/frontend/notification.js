import '../echo.js';

const baseUrl = $(`meta[name="base_url"]`).attr('content');
const csrfToken = $(`meta[name="csrf_token"]`).attr('content');

var loader = `
<div class="text-center p-3" style="display:inline">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
`;

var currentUser = JSON.parse($('#current_user').val());

// var pusher = new window.Pusher('b47a559876f1d3cfe9ac', {
//     cluster: 'ap1',
//     authEndpoint: '/broadcasting/auth',
//     encrypted: true
// });

// var channel = pusher.subscribe(`notificationssss.${currentUser.id}`); 

// channel.bind('student.enrolled.course', function (data) {
//     alert(123);
//     if(data.instructor_id == currentUser.id) {
//         let notificationContainer = $('#notification-container');
//         let notificationMessage = $('#notification-message');
//         let message = data.message;
//         let messageTime = data.time || new Date().toLocaleTimeString([], { 
//             hour: '2-digit',
//             minute: '2-digit'
//         });

//         let messageHtml = `
//             <a class="notification-message">
//                 <p><strong>${message}</strong></p>
//                 <div class="timestamp">${messageTime}</div>
//             </a>`;
//         notificationContainer.append(messageHtml);
//     }
// });

// window.Echo.private(`notificationssss.${currentUser.id}`).listen('.student.enrolled.course', (notification) => {
//     alert(123);
//     console.log(456);
//     console.log(notification);
//     if(notification.instructor_id == currentUser.id) {
//         let notificationContainer = $('#notification-container');
//         let message = notification.message;
//         let messageTime = new Date(notification.time).toLocaleString('vi-VN', {
//             day: '2-digit',
//             month: '2-digit',
//             year: 'numeric',
//             hour: '2-digit',
//             minute: '2-digit'
//         });

//         let messageHtml = `
//             <a class="notification-message">
//                 <p><strong>${message}</strong></p>
//                 <div class="timestamp">${messageTime}</div>
//             </a>
//         `;
//         notificationContainer.append(messageHtml);
//     }
// });

$(document).on('click', '.mark-as-read', function (e) {        
    var notificationId = $('.mark-as-read').data('notification-id');
    var redirectUrl = $('.mark-as-read').data('redirect-url');
            
    $.ajax({
        url: `${baseUrl}/${currentUser.role == 'instructor' ? 'instructor' : 'student'}/notifications/${notificationId}/mark-as-read`,
        method: 'POST',
        data: {
            '_token': csrfToken,
        },
        success: function (data) {
            window.location.href = redirectUrl;
        },
        error: function (xhr, status, error) {
            console.log(xhr);
        }
    });
});

$('#notification123').on('click', function() {
    let notificationContainer = $('#notification-container');
    
    $.ajax({
        url: `${baseUrl}/${currentUser.role == 'instructor' ? 'instructor' : 'student'}/notifications/fetch-messages`,
        method: 'GET',
        data: {},
        beforeSend: function () {
            $('#notification-container').html(loader);
        },
        success: function(response) {
            notificationContainer.empty();       
            
            let messageHtml = `
                <div class="p-3 border-bottom fw-bold">Latest Notifications</div>
            `;
            let notifications = response.notifications;
            if(notifications.length != 0){
                
                notifications.forEach(item => {
                    console.log(item);
                    let message = item.data.message;
                    let messageTime = item.data.time;
                    messageHtml += `
                        <div class="d-flex align-items-start notification-item">
                            <div class="icon-success">✔</div>
                            <div class="notification-text">
                                <div>
                                    <p class="fw-bold">${item.data.title}</p>
                                    <a href="javascript:;" class="mark-as-read" 
                                        data-notification-id="${item.id}" data-redirect-url="${item.data.url}">
                                        ${message}
                                    </a>
                                    <div class="timestamp">${messageTime}</div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                messageHtml += `
                    <div class="p-3 border-top fw-bold">
                        <a class="text-center" href="${baseUrl}/${currentUser.role == 'instructor' ? 'instructor' : 'student'}/notifications">
                            All Notifications
                        </a>
                    </div>
                `;
            }
            else{
                messageHtml = `
                    <div class="p-3 fw-bold text-center">No Notifications</div>
                `;
            }
            notificationContainer.append(messageHtml);
        
        },
        error: function(xhr, status, error) {
            console.error('Error fetching messages:', error);
        },

    });

});