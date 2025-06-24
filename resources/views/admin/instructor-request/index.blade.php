@extends('admin.layouts.master')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Instructor Requests</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.instructor-requests.index') }}" class="d-flex align-items-center justify-content-between mb-3">
                      <div class="form-floating">
                          <input type="text" value="{{ request('search') }}" class="form-control" name="search" id="floatingInput" placeholder="name@example.com">
                          <label for="floatingInput">Search</label>
                      </div>

                      <div class="selector">
                        <select class="select2" name="status">
                            <option value="" disabled selected>Status</option>
                            <option value="all" @selected(request('status') == 'all')>All</option>
                            <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                            <option value="rejected" @selected(request('status') == 'rejected')>Rejected</option>
                        </select>
                      </div>

                      <div>
                        <button type="submit" class="btn btn-primary">
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-filter"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 3h-16a1 1 0 0 0 -1 1v2.227l.008 .223a3 3 0 0 0 .772 1.795l4.22 4.641v8.114a1 1 0 0 0 1.316 .949l6 -2l.108 -.043a1 1 0 0 0 .576 -.906v-6.586l4.121 -4.12a3 3 0 0 0 .879 -2.123v-2.171a1 1 0 0 0 -1 -1z" /></svg>                            
                            Filter
                        </button>
                        <a href="{{ route('admin.instructor-requests.index') }}" class="btn btn-primary">
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-restore"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3.06 13a9 9 0 1 0 .49 -4.087" /><path d="M3 4.001v5h5" /><path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /></svg>
                            Reset
                        </a>
                      </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                          <thead>
                            <tr>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Message For Rejection</th>
                              <th>Document</th>
                              <th>Created At</th>
                              <th>Status</th>
                              <th>Action</th>
                              <th class="w-1"></th>
                            </tr>
                          </thead>
                          <tbody>
                            @forelse($instructorsRequests as $instructor)
                            <tr>
                              <td>{{ $instructor->name }}</td>
                              <td>
                                {{ $instructor->email }}
                              </td>
                              <td>{{ $instructor->message_for_rejection }}</td>
                              <td>
                                <a href="{{ route('admin.instructor-doc-download', $instructor->id) }}"  target="_blank" class="text-muted">
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-download"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg>
                                </a>
                              </td>
                              <td>{{ $instructor->created_at->format('d-m-Y') }}</td>
                              <td>
                                @if ($instructor->approve_status === 'pending')
                                  <span class="badge bg-yellow text-yellow-fg">Pending</span> 
                                @elseif($instructor->approve_status === 'rejected')
                                  <span class="badge bg-red text-yellow-fg">Rejected</span> 
                                @endif
                              </td>
                              <td >
                                {{-- <form method="POST" action="{{ route('admin.instructor-requests.update', $instructor->id) }}" class="status-{{ $instructor->id }}">
                                    @csrf
                                    @method('PUT') --}}
                                    {{-- <select name="status" class="form-control" onchange="$('.status-{{ $instructor->id }}').submit()"> --}}
                                    {{-- <select name="status" class="form-control update-approval-status" data-id="{{ $course->id }}"
                                            data-route="{{ route('admin.courses.update-approval', $course->id) }}" data-type=""> --}}
                                    @if($instructor->approve_status === 'pending')
                                      <select name="status" class="form-control update-approval-status" data-id="{{ $instructor->id }}"
                                              data-route="{{ route('admin.instructor-requests.update', $instructor->id) }}" 
                                              data-route-type="instructor_request">
                                          <option @selected($instructor->approve_status === 'pending') value="pending">Pending</option>
                                          <option @selected($instructor->approve_status === 'approved') value="approved">Approve</option>
                                          <option @selected($instructor->approve_status === 'rejected') value="rejected">Reject</option>
                                      </select>
                                    @endif
                                {{-- </form> --}}
                              </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No Data Available!</td>
                              </tr>
                            @endforelse
                          </tbody>
                        </table>
                      </div>
                </div>
            </div>
        </div>
    </div>
@endsection
