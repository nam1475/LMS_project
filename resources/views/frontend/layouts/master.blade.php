<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
    <meta name="base_url" content="{{ url('/') }}">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    @stack('meta')
    <title>EduCore - Online Courses & Education HTML Template</title>
    <link rel="icon" type="image/png" href="{{ asset(config('settings.site_favicon')) }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/animated_barfiller.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/venobox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/scroll_button.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/pointer.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/jquery.calendar.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/range_slider.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/startRating.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/video_player.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/jquery.simple-bar-graph.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/sticky_menu.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/jquery-ui.min.css') }}">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">


    <link rel=" stylesheet" href="{{ asset('frontend/assets/css/spacing.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/responsive.css') }}">
    
    @vite(['resources/css/frontend.css', 'resources/js/frontend/frontend.js'])
    <!--dynamic js-->
    @stack('header_scripts')
    <style>
        .chat-list {
            max-height: 500px;
            overflow-y: auto;
        }

        .chat-item {
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
            /* min-height: 400px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #ffffff;
            margin-bottom: 10px; */
            width: 100%; 
            height: 100%;
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
            background-color: #ececec;
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
</head>

<body class="home_3">

    @php
        $user = auth('web')->user();
        if($user){
            $contacts = App\Models\Chat::where('receiver_id', $user->id)->orWhere('sender_id', $user->id)
                    ->pluck($user->role == 'student' ? 'receiver_id' : 'sender_id')->unique()->toArray();
            // Fetch users based on the role
            if ($user->role == 'instructor') {
                $senders = App\Models\User::where('role', 'student')->whereIn('id', $contacts)->orderBy('updated_at', 'desc')->get();
            } else {
                $senders = App\Models\User::where('role', 'instructor')->whereIn('id', $contacts)->orderBy('updated_at', 'desc')->get();
            }
        }
    @endphp
    @include('frontend.layouts.header')


    @yield('content')


    @include('frontend.layouts.footer')



    <!-- Modal -->
    <div class="modal fade" id="dynamic-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog  modal-dialog-centered modal-lg dynamic-modal-content">
            
        </div>
    </div>


    <!--================================
        SCROLL BUTTON START
    =================================-->
    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>
    <!--================================
        SCROLL BUTTON END
    =================================-->

    <!-- Trigger Button -->
    @if($user)
        <div class="position-fixed m-4" style="right: 90px; bottom: 20px;">
            <button id="openChat" class="btn btn-primary rounded-circle shadow" style="width: 60px; height: 60px;">
                <i class="fas fa-comment-dots fa-lg"></i>
            </button>
        </div>

        <!-- Chat Popup -->
        <div id="chatPopup" class="position-fixed bottom-0 end-0 m-4 shadow border rounded bg-white d-none" style="width: 800px; height: 600px; z-index: 1050;">
            <div class="d-flex h-100">
                <!-- Sidebar -->
                <div class="border-end bg-light" style="width: 400px;">
                {{-- <div class="p-3 border-bottom">
                    <input type="text" id="search-chat-input" class="form-control form-control-sm" placeholder="Tìm theo tên">
                </div> --}}
                
                <input type="hidden" id="current_user" value="{{ $user }}">

                <ul class="list-group list-group-flush" id="chat-list">
                    @if($senders->count() > 0)
                        @foreach ($senders as $sender)      
                            @php
                                $latestMessage = App\Models\Chat::where('receiver_id', $sender->id)
                                    ->orWhere('sender_id', $sender->id)->latest()->first();
                            @endphp 
                            <li class="list-group-item d-flex align-items-center justify-content-between chat-item" 
                                id="chat-item-{{ $sender->id }}">
                                <div class="d-flex align-items-center">
                                    <span id="marked-{{ $sender->id }}" 
                                        @class([
                                            'position-absolute top-0 start-0 p-2 bg-danger border border-light rounded-circle' => $user->unreadMessages($sender->id)->exists(),
                                        ]) 
                                    ></span>
                                    <img src="{{ asset($sender->image) }}" class="profile_img rounded-circle me-2 w-25" alt="Profile Picture">
                                    <div>
                                    <div class="fw-bold profile_name">{{ $sender->name }}</div>
                                        <span class="sender_id" style="display: none;">
                                            {{ $sender->id}}
                                        </span>
                                    <small class="text-muted">{{ $latestMessage->sender_id != $sender->id ? 'You: ' : '' }}{{ $latestMessage->message }}</small>
                                    </div>
                                </div>
                                <p class="text-end">{{ $latestMessage->created_at->isToday() ? $latestMessage->created_at->timezone('Asia/Ho_Chi_Minh')->format('H:i') : $latestMessage->created_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y') }}</p>
                            </li>
                        @endforeach
                    @else
                        <p class="text-center mt-3 fw-bold">No Chats</p>
                    @endif
                    <!-- Repeat contacts here -->
                </ul>
                </div>

                <!-- Chat Area -->
                {{-- <div class="flex-grow-1 d-flex flex-column" id="chat-area"> --}}
                <div class="" style="width: 100%; height: 100%;" id="chat-area">
                    <!-- Header -->
                    <div class="d-flex justify-content-end align-items-center p-2 border-bottom">
                        <button id="closeChat" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="flex-grow-1 d-flex flex-column justify-content-center align-items-center text-center px-3" style="height: 400px">
                        {{-- <img src="https://cdn-icons-png.flaticon.com/512/4712/4712027.png" class="mb-3 w-50"> --}}
                        <h6>Welcome to Chat</h6>
                        <p class="text-muted">Select a contact to start a conversation</p>
                    </div>

                    <!-- Chat Input -->
                    {{-- <div class="border-top p-2">
                        <div class="input-group">
                        <input type="text" class="form-control" placeholder="Nhập nội dung tin nhắn">
                        <button class="btn btn-outline-secondary"><i class="fas fa-paper-plane"></i></button>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    @endif


    <!--jquery library js-->
    <script src="{{ asset('frontend/assets/js/jquery-3.7.1.min.js') }}"></script>
    <!--bootstrap js-->
    <script src="{{ asset('frontend/assets/js/bootstrap.bundle.min.js') }}"></script>
    <!--font-awesome js-->
    <script src="{{ asset('frontend/assets/js/Font-Awesome.js') }}"></script>
    <!--marquee js-->
    <script src="{{ asset('frontend/assets/js/jquery.marquee.min.js') }}"></script>
    <!--slick js-->
    <script src="{{ asset('frontend/assets/js/slick.min.js') }}"></script>
    <!--countup js-->
    <script src="{{ asset('frontend/assets/js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/jquery.countup.min.js') }}"></script>
    <!--venobox js-->
    <script src="{{ asset('frontend/assets/js/venobox.min.js') }}"></script>
    <!--nice-select js-->
    <script src="{{ asset('frontend/assets/js/jquery.nice-select.min.js') }}"></script>
    <!--Scroll Button js-->
    <script src="{{ asset('frontend/assets/js/scroll_button.js') }}"></script>
    <!--pointer js-->
    <script src="{{ asset('frontend/assets/js/pointer.js') }}"></script>
    <!--range slider js-->
    <script src="{{ asset('frontend/assets/js/range_slider.js') }}"></script>
    <!--barfiller js-->
    <script src="{{ asset('frontend/assets/js/animated_barfiller.js') }}"></script>
    <!--calendar js-->
    <script src="{{ asset('frontend/assets/js/jquery.calendar.js') }}"></script>
    <!--starRating js-->
    <script src="{{ asset('frontend/assets/js/starRating.js') }}"></script>
    <!--Bar Graph js-->
    <script src="{{ asset('frontend/assets/js/jquery.simple-bar-graph.min.js') }}"></script>
    <!--select2 js-->
    <script src="{{ asset('frontend/assets/js/select2.min.js') }}"></script>
    <!--Video player js-->
    <script src="{{ asset('frontend/assets/js/video_player.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/video_player_youtube.js') }}"></script>
    <!--wow js-->
    <script src="{{ asset('frontend/assets/js/wow.min.js') }}"></script>

    <!--jquery ui-->
    <script src="{{ asset('frontend/assets/js/jquery-ui.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script src="{{ asset('admin/assets/dist/libs/tinymce/tinymce.min.js') }}" defer></script>

    <!--main/custom js-->
    <script src="{{ asset('frontend/assets/js/main.js') }}"></script>

    {{-- @vite(['resources/js/global.js']) --}}

    <!--dynamic js-->
    @stack('scripts')

    @vite(['resources/js/frontend/chat.js'])
    

    <script>
        var notyf = new Notyf({
            duration: 5000,
            dismissible: true
        });

        $(document).ready(function () {
            $('#openChat').click(function () {
                $('#chatPopup').removeClass('d-none').hide().fadeIn();
            });

            $(document).on('click', '#closeChat', function () {
                $('#chatPopup').fadeOut();
            }); 

            // $(document).on('input', '#search-chat-input', function () {
            //     let keyword = $(this).val().toLowerCase();
            //     console.log(keyword);
                
            //     $('#chat-list .chat-item').each(function () {
            //         let name = $(this).find('.profile_name').text().toLowerCase();
            //         console.log(name);

            //         if (name.includes(keyword)) {
            //             console.log(123);
            //             $(this).show();
            //         } else {
            //             $(this).hide();
            //         }
            //     });
                
            // });
        });


        @if ($errors->any())
            @foreach ($errors->all() as $error)
                notyf.error("{{ $error }}");
            @endforeach
        @endif
    </script>

    {{-- @include('frontend.alert.alert') --}}

    

</body>

</html>
