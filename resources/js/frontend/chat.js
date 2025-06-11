import '../echo';

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

// Initialize Pusher
// var pusher = new window.Pusher('b47a559876f1d3cfe9ac', {
//     cluster: 'ap1',
//     authEndpoint: '/broadcasting/auth', // Automatically redirect to route in channels.php to authenticate user  
//     encrypted: true
// });

// Subscribe to the private channel based on the receiverId
// var channel = pusher.subscribe('private-chat.' + currentUser.id); // Use dynamic receiverId

/* Bind to the 'student-instructor-message' event */
window.Echo.private('chat.' + currentUser.id)
    .listen('.student.instructor.message', function(data) { 
// channel.bind('student-instructor-message', function (data) {
    var chatWindow = $('.chat-window');
    let chatMessageContainer = $('#chat-message-container');
    let senderId = data.sender_id;
    let message = data.message;
    let senderName = data.sender_name;
    let senderImage = data.sender_image; // Default image
    let currentUserChatWith = $('#receiver_id').val();
    let messageTime = data.time;

    // Marked as not read message
    let marked = $('#marked-' + senderId);
    marked.toggleClass('position-absolute top-0 start-0 p-2 bg-danger border border-light rounded-circle');

    // Check if the logged-in user is the receiver before displaying the message
    if (senderId == currentUserChatWith) {
        let messageHtml = `
            <div class="chat-message receiver"> 
                <div class="message-avatar">
                    <img src="${senderImage}" class="rounded-circle img-fluid w-100" alt="${senderName} Avatar">
                </div>
                <div class="message-content">
                    <p>${message}</p>
                    <div class="timestamp">${messageTime}</div>
                </div>
            </div>`;

        // Append message to chat container
        chatMessageContainer.append(messageHtml);

        // Scroll to the bottom of the chat container
        chatWindow.scrollTop(chatWindow[0].scrollHeight);
    }

});

// Event listener for chat list items   
$('.chat-item').on('click', function() {
    let profileImage = $(this).find('.profile_img').attr('src');
    let profileName = $(this).find('.profile_name').text();
    let receiverId = $(this).find('.sender_id').text();
    var chatArea = $('#chat-area');

    $.ajax({
        url: currentUser.role == 'instructor' ? baseUrl + '/instructor/chats/fetch-messages' : baseUrl + '/student/chats/fetch-messages',
        method: 'GET',
        data: { 
            receiver_id: receiverId,
        },
        beforeSend: function () {
            $('#chat-area').html(loader);
        },
        success: function(response) {
            chatArea.empty();

            if(response.isRead){
                let marked = $('#marked-' + response.receiverId);
                marked.addClass('d-none');
            }

            let chatAreaHtml = `
                <div class="card shadow-sm">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex align-items-center">
                                <div style="width: 40px; height: 40px;">
                                    <img id="chat_img" src="${profileImage}" class="rounded-circle mr-3 img-fluid w-100"
                                        alt="Profile Picture">
                                </div>
                                <h4 class="mb-0" id="chat_name" style="color: white;">Chatting with ${profileName}</h4>
                            </div>
                        </div>
                
                    <div class="card-body chat-window" style="height: 400px; overflow-y: auto;">
                        <div class="chat-message-container" id="chat-message-container">
                `;

                response.messages.forEach(function(message) {
                    let isSender = message.sender_id == currentUser.id;
                    let userAvatar = isSender ? currentUser.image : profileImage;
                    let userName = isSender ? currentUser.name : profileName;

                    let messageTime = new Date(message.created_at).toLocaleString('vi-VN', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    chatAreaHtml += `
                            <div class="chat-message ${isSender ? 'sender' : 'receiver'}">
                                <div class="message-avatar">
                                    <img src="${userAvatar}" class="rounded-circle avatar" alt="User Avatar">
                                </div>
                                <div class="message-content">
                                    <p>${message.message}</p>
                                    <div class="timestamp">${messageTime}</div>
                                </div>
                            </div>
                            
                        `;
                });

            chatAreaHtml += `
                        </div>
                    </div>

                    <div class="card-footer">
                        <form id="message-form" method="POST">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="receiver_id" id="receiver_id" value="${response.receiverId}">
                            <div class="input-group">
                                <input type="text" class="form-control"
                                    placeholder="Type your message here..." id="messageInput"
                                    name="message">
                                <button class="btn btn-primary" type="submit"
                                    id="send-message-button">Send</button>
                            </div>
                        </form>
                    </div>
                </div> 
            `;

            chatArea.append(chatAreaHtml);

            // Scroll to the bottom of the chat container
            let chatWindow = $('.chat-window');
            $('.chat-window').scrollTop(chatWindow[0].scrollHeight);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching messages:', error);
        },
    });

});


// Event listener for the message form
$(document).on('submit', '#message-form', function(e) {
    e.preventDefault();
    
    let message = $('#messageInput').val().trim();
    let receiverId = $('#receiver_id').val();
    let chatWindow = $('.chat-window');
    let chatMessageContainer = $('#chat-message-container');
    let route = $('#message-form').data('route');

    if (message === "") {
        notyf.error('Please enter a message.');
        return;
    }

    $.ajax({
        type: "POST",
        url: currentUser.role == 'instructor' ? baseUrl + '/instructor/chats/send-message' : baseUrl + '/student/chats/send-message',
        data: {
            _token: csrfToken,
            message: message,
            receiver_id: receiverId
        },
        beforeSend: function() {
            // Disable the send button and change its text to "Sending..." for 1s
            $('#send-message-button').text('Sending...').attr('disabled', true);
            setTimeout(function() {}, 1000);
        },
        success: function(response) {
            if (response.success) {
                if(response.isRead){
                    let marked = $('#marked-' + receiverId);
                    marked.addClass('d-none');
                }

                $('#messageInput').val(''); // Clear the input

                let userAvatar = currentUser.image;

                let messageTime = new Date(response.chat.created_at).toLocaleString('vi-VN', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });

                let messageHtml = `
                    <div class="chat-message sender">
                        <div class="message-avatar">
                            <img src="${userAvatar}" class="rounded-circle img-fluid w-100 " alt="User Avatar">
                        </div>
                        <div class="message-content">
                            <p>${message}</p>
                            <div class="timestamp">${messageTime}</div>
                        </div>
                    </div>`;

                chatMessageContainer.append(messageHtml);

                chatWindow.scrollTop(chatWindow[0].scrollHeight);
            } 
        },
        error: function(xhr) {
            console.error('Error:', xhr.responseJSON.message);
        },
        complete: function() {
            $('#send-message-button').text('Send').attr('disabled', false);
        }
    });
});


// Mark as read when click on input message


// Show notification when someone sends a message

