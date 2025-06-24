const csrf_token = $(`meta[name="csrf_token"]`).attr('content');
const baseUrl = $(`meta[name="base_url"]`).attr('content');

var loader = `
<div class="modal-content text-center p-3" style="display:inline">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
`;

$(function () {
    $(document).on('click', '.mark-as-read', function (e) {        
        var notificationId = $('.mark-as-read').data('notification-id');
        var redirectUrl = $('.mark-as-read').data('redirect-url');
        console.log(notificationId, redirectUrl);
        
        $.ajax({
            url: baseUrl + '/admin/notifications/' + notificationId + '/mark-as-read',
            method: 'POST',
            data: {
                '_token': csrf_token,
            },
            success: function (data) {
                window.location.href = redirectUrl;
                
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseJSON.message);
            }
        });
    });
    
    $('#notification').on('click', function(e) {        
        let notificationContainer = $('#notification-container');
    
        $.ajax({
            url: baseUrl + '/admin/notifications/fetch-messages',
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
                if(notifications.length > 0){
                    notifications.forEach(item => {
                        let message = item.data.message;
                        let messageTime = item.data.time;
                        messageHtml += `
                            <div class="d-flex align-items-start notification-item">
                                <div class="icon-success">✔</div>
                                <div class="notification-text">
                                    <div>
                                        <p class="fw-bold m-0">${item.data.title}</p> 
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
                            <a class="text-center" href="${baseUrl}/admin/notifications">
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
                console.error(xhr.responseJSON.message);
            },

        });

    });
});