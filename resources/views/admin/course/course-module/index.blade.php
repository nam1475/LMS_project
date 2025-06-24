@extends('admin.layouts.master')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header ">
                    <h3 class="card-title">Courses</h3>
                    {{-- <div class="card-actions">
                        <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 5l0 14"></path>
                                <path d="M5 12l14 0"></path>
                            </svg>
                            Add new
                        </a>
                    </div> --}}
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.courses.index') }}" class="d-flex align-items-center justify-content-between mb-3">
                        <div class="form-floating">
                            <input type="text" value="{{ request('search') }}" class="form-control" name="search" id="floatingInput" placeholder="name@example.com">
                            <label for="floatingInput">Search</label>
                        </div>

                        <div class="selector">
                            <select class="select2" name="status">
                                <option value="" disabled selected>Status</option>
                                <option value="all" @selected(request('status') == 'all')>All</option>
                                <option value="approved" @selected(request('status') == 'approved')>Approved</option>
                                <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                                <option value="rejected" @selected(request('status') == 'rejected')>Rejected</option>
                            </select>
                        </div>

                        <div>
                            <select class="select2" name="is_published">
                                <option value="" disabled selected>Is Published</option>
                                <option value="all" @selected(request('is_published') == 'all')>All</option>
                                <option value="1" @selected(request('is_published') == '1')>Published</option>
                                <option value="0" @selected(request('is_published') == '0')>Draft</option>
                            </select>
                        </div>
                        
                        <div>
                            <select class="select2" name="instructor_id">
                                <option value="" disabled selected>Instructor</option>
                                <option value="all" @selected(request('instructor_id') == 'all')>All</option>
                                @foreach ($instructors as $instructor)
                                    <option value="{{ $instructor->id }}" @selected(request('instructor_id') == $instructor->id)>
                                        {{ $instructor->name }}
                                    </option>
                                    
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <select class="select2" name="course_categories[]" multiple>
                                <option value="" disabled>Select Course Categories</option>
                                @foreach($courseCategories as $category)
                                    @if($category->subCategories->isNotEmpty())
                                        <optgroup label="{{ $category->name }}">
                                        @foreach($category->subCategories as $subCategory)
                                            <option value="{{ $subCategory->id }}" @selected(in_array($subCategory->id, request('course_categories', [])))>
                                                {{ $subCategory->name }}
                                            </option>
                                        @endforeach
                                        </optgroup>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-filter"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 3h-16a1 1 0 0 0 -1 1v2.227l.008 .223a3 3 0 0 0 .772 1.795l4.22 4.641v8.114a1 1 0 0 0 1.316 .949l6 -2l.108 -.043a1 1 0 0 0 .576 -.906v-6.586l4.121 -4.12a3 3 0 0 0 .879 -2.123v-2.171a1 1 0 0 0 -1 -1z" /></svg>                            
                                Filter
                            </button>
                            <a href="{{ route('admin.courses.index') }}" class="btn btn-primary">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-restore"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3.06 13a9 9 0 1 0 .49 -4.087" /><path d="M3 4.001v5h5" /><path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /></svg>
                                Reset
                            </a>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Commit message</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Instructor</th>
                                    <th>Message For Rejection</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Is Published</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($courses as $course)
                                <tr>
                                    <td>{{ $course->message_for_commit }}</td>
                                    <td>{{ $course->title }}</td>
                                    <td>
                                        @if($course->category)
                                            <span class="badge bg-blue text-blue-fg">
                                                {{ $course->category->name }}</td>
                                            </span>
                                        @endif
                                    <td>{{ $course->instructor->name }}</td>
                                    <td>{{ $course->message_for_rejection }}</td>
                                    <td>{{ $course->created_at->timezone('Asia/Ho_Chi_Minh')->format('H:i d/m/Y') }}</td>
                                    <td>{{ $course->updated_at->timezone('Asia/Ho_Chi_Minh')->format('H:i d/m/Y') }}</td>
                                    <td>
                                        @if($course->is_published)
                                            <span class="badge bg-green text-green-fg">Publish</span>
                                        @elseif(!$course->is_published && $course->is_current)
                                            <span class="badge bg-secondary text-secondary-fg">Draft</span>
                                        @endif
                                    </td>
                                    <td>
                                    @if(($course->is_approved == 'approved' && !$course->is_current) || ($course->is_published && $course->is_current))
                                        <span class="badge bg-green text-green-fg">Approved</span>
                                    @elseif($course->is_approved == 'rejected')
                                        <span class="badge bg-danger text-danger-fg">Rejected</span>
                                    @else
                                        <select name="" class="form-control update-approval-status" data-id="{{ $course->id }}"
                                            data-route="{{ route('admin.courses.update-approval', $course->id) }}" 
                                            data-route-type="course">
                                            <option @selected($course->is_approved == 'pending') value="pending">Pending</option>
                                                <option @selected($course->is_approved == 'approved') value="approved">Approved</option>
                                                <option @selected($course->is_approved == 'rejected') value="rejected">Rejected</option>
                                        </select>
                                    @endif
                                    </td>
                                    <td>
                                        
                                        <a href="{{ route('admin.courses.commits', $course->id) }}" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Commits" class="btn-sm btn-primary">
                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-git-merge"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 18m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M7 6m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 12m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M7 8l0 8" /><path d="M7 8a4 4 0 0 0 4 4h4" /></svg>
                                        </a>
                                        <a href="{{ route('admin.courses.edit', ['id' => $course->id, 'step' => 1]) }}" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View" class="btn-sm btn-primary">
                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-eye"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                        </a>
                                        <a href="{{ route('admin.course-levels.destroy', $course->id) }}" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" class="text-red delete-item">
                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7h16" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /><path d="M10 12l4 4m0 -4l-4 4" /></svg>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No Data Found!</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $courses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('header_scripts')
    @vite(['resources/js/admin/course.js'])
@endpush
