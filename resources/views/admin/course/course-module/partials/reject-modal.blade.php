<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Rejection</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <form action="{{ route('admin.reject-approval.send', $id) }}" method="POST">
        {{-- <form action="{{ route('admin.courses.reject-approval.send', $id) }}" method="POST"> --}}
        {{-- <form data-route="{{ route('admin.courses.update-approval', $id) }}" 
            class="reject-approval-status" method="POST"> --}}
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mb-3 add_course_basic_info_imput">
                        <label for="">Message</label>
                        <input type="text" class="form-control" id="message" name="message">
                        <input type="hidden" name="route_type" value="{{ $routeType }}">
                    </div>
                </div>
                
                <div class="form-group text-end">
                    <button type="submit" class="btn btn-primary">Send</button>
                </div>
            </div>
        </form>
    </div>
</div>

    
