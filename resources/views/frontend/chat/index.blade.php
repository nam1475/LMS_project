@extends('frontend.layouts.master')

@section('content')
<style>
    .chat-list {
        max-height: 500px;
        overflow-y: auto;
    }

    .chat-item {
        display: flex;
        align-items: center;
        padding: 10px;
        cursor: pointer;
    }

    .chat-item:hover {
        background-color: #f5f5f5;
    }

    .chat-details {
        flex: 1;
    }

    .chat-title {
        display: flex;
        align-items: center;
        padding: 10px;
    }

    .chat-message {
        display: flex;
        margin-bottom: 10px;
    }

    .message-avatar {
        width: 40px;
        height: 40px;
        margin-right: 10px;
    }

    .message-content {
        background-color: #f2f2f2;
        padding: 10px;
        border-radius: 10px;
    }

    .sender .message-content {
        background-color: #dcf8c6;
    }

    .card-footer {
        padding: 10px;
    }

    .chat-window {
        max-height: 500px;
        overflow-y: auto;
    }

    .chat-message-container {
        min-height: 400px;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #ffffff;
        margin-bottom: 10px;
    }

    .chat-message.sender {
        margin-bottom: 10px;
        text-align: right;
    }

    .chat-message.receiver {
        margin-bottom: 10px;
        text-align: left;
    }

    .chat-message.receiver .message-content {
        background-color: #ececec; /** Gray */
    }

    .list-group-item.active {
        z-index: 2;
        color: #fff;
        background-color: #4B49AC;
        border-color: #4B49AC;
    }

    .chat-message {
        display: flex;
        align-items: center;
        margin: 10px 0;
    }

    /* .chat-message .message-avatar img {
        width: 40px;
        height: 40px;
    } */

    .chat-message .message-content {
        display: inline-block;
        padding: 10px;
        border-radius: 5px;
        background-color: #f1f1f1;
        margin: 0 10px;
        max-width: 70%;
    }

    .chat-message.sender .message-content {
        background-color: #d1e7dd;
        /* Example color for sender */
        margin-left: auto;
        /* Align right */
    }

    .chat-message.sender {
        flex-direction: row-reverse;
    }

    .chat-message .timestamp {
        font-size: 0.8em;
        color: #888;
    }

    .chat-message.sender .timestamp {
        margin-right: 10px;
    }

    .profile_card {
        display: flex;
        align-items: center;
        padding: 15px;
        margin: 10px 0;
        background-color: #f8f9fa;
        /* Light background color */
        border-radius: 10px;
        /* Rounded corners */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        /* Subtle shadow */
        transition: transform 0.2s;
        /* Animation for hover effect */
    }

    .profile_card:hover {
        transform: translateY(-5px);
        /* Lift the card on hover */
    }

    .profile_img {
        margin-right: 15px;
        border: 2px solid #007bff;
        /* Border color matching badge */
    }

    .chat-details {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .profile_name {
        font-size: 1.1em;
        /* Slightly larger font size */
        font-weight: bold;
        color: #343a40;
        /* Darker text color */
        margin-bottom: 5px;
    }

    .badge-primary {
        background-color: #007bff;
        /* Badge background color */
        color: #fff;
        /* Badge text color */
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 0.9em;
        align-self: flex-start;
        /* Align the badge to the start */
    }

</style>
<!--===========================
                    BREADCRUMB START
                ============================-->
    <section class="wsus__breadcrumb" style="background: url({{ asset(config('settings.site_breadcrumb')) }});">
        <div class="wsus__breadcrumb_overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12 wow fadeInUp">
                        <div class="wsus__breadcrumb_text">
                            <h1> 
                                {{ $user->role == 'student' ? 'Student Chat' : 'Instructor Chat' }}
                            </h1>
                                
                            <ul>
                                <li><a href="{{ url('/') }}">Home</a></li>
                                <li>{{ $user->role == 'student' ? 'Student Chat' : 'Instructor Chat' }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--===========================
                    BREADCRUMB END
                ============================-->

    <section class="wsus__dashboard mt_90 xs_mt_70 pb_120 xs_pb_100">
        <div class="container">
            <div class="row">
                @include($user->role == 'student' ? 'frontend.student-dashboard.sidebar' : 'frontend.instructor-dashboard.sidebar')
                <div class="col-xl-9 col-md-8 wow fadeInRight" style="visibility: visible; animation-name: fadeInRight;">
                    <div class="wsus__dashboard_contant">
                        <div class="main-panel">
                            <input type="hidden" name="current_user" id="current_user" value="{{ $user }}">
                            <div class="content-wrapper">
                                <div class="row">
                                    <div class="col-md-12 grid-margin">
                                        <div class="row">
                                            <br>
                                            <div class="col-md-12 mt-4 grid-margin">
                                                <div class="row">
                                                    {{-- Left column: Chat list --}}
                                                    <div class="col-md-4 col-lg-3">
                                                        <div class="card shadow-sm">
                                                            <div class="card-header bg-primary" >
                                                                <h4 class="mb-0" style="color: white;">Chats</h4>
                                                            </div>
                                                            <div class="list-group chat-list" id="chatList"
                                                                style="max-height: 500px; overflow-y: auto;">
                                                                <ul class="list-group list-group-flush">
                                                                    @if($senders->count() > 0)
                                                                        @foreach ($senders as $sender)
                                                                            <li class="list-group-item d-flex align-items-center chat-item">
                                                                                {{-- @if($user->unreadMessages()->contains($sender->id))
                                                                                    <span id="marked-{{ $sender->id }}" class="position-absolute top-0 start-0 p-2 bg-danger border border-light rounded-circle"></span>
                                                                                @endif --}}
                                                                                <span id="marked-{{ $sender->id }}" 
                                                                                    @class([
                                                                                        'position-absolute top-0 start-0 p-2 bg-danger border border-light rounded-circle' => $user->unreadMessages($sender->id)->exists(),
                                                                                    ]) 
                                                                                ></span>
                                                                                <img src="{{ asset($sender->image) }}"
                                                                                    class="profile_img rounded-circle mr-3 w-25" alt="Profile Picture">
                                                                                <div class="profile_info">
                                                                                    <span class="profile_name">
                                                                                        {{ $sender->name}}
                                                                                    </span>
                                                                                    <span class="sender_id" style="display: none;">
                                                                                        {{ $sender->id}}
                                                                                    </span>
                                                                                </div>
                                                                            </li>
                                                                        @endforeach
                                                                    @else
                                                                        <p class="text-center">No Chats</p>
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Right column: Chat area --}}
                                                    <div class="col-md-8 col-lg-9" id="chat-area">   
                                                        {{-- <div class="card shadow-sm">
                                                            <div class="card-header bg-primary text-white">
                                                                <div class="d-flex align-items-center">
                                                                    <div style="width: 40px; height: 40px;">
                                                                        <img id="chat_img" src="" class="rounded-circle mr-3 img-fluid w-100"
                                                                            alt="Profile Picture">
                                                                    </div>
                                                                    <h4 class="mb-0" id="chat_name" style="color: white;"></h4>
                                                                </div>
                                                            </div>
                
                                                            <div class="card-body chat-window" style="height: 400px; overflow-y: auto;">
                                                                <div id="chat-message-container">
                                                                    <!-- Chat messages will be dynamically loaded here -->
                                                                </div>
                                                            </div>
                
                                                            <div class="card-footer">
                                                                <form id="message-form" data-route="{{ $user->role == 'student' ? route('student.send.message') : route('instructor.send.message') }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="receiver_id" id="receiver_id">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control"
                                                                            placeholder="Type your message here..." id="messageInput"
                                                                            name="message">
                                                                        <button class="btn btn-primary" type="submit"
                                                                            id="send-message-button">Send</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div> --}}
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 grid-margin stretch-card">
                                    <div class="card">

                                    </div>
                                </div>

                            </div>
                            <!-- main-panel ends -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



@endsection

@push('scripts')
    @vite(['resources/js/frontend/chat.js'])
@endpush