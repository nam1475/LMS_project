@extends('admin.layouts.master')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Coupons</h3>
                    <div class="card-actions">
                        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus"></i>
                            Add new
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.coupons.index') }}" class="d-flex align-items-center justify-content-between mb-3">
                        <div class="form-floating">
                            <input type="text" value="{{ request('search') }}" class="form-control" name="search" id="floatingInput" placeholder="name@example.com">
                            <label for="floatingInput">Search</label>
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
                            <select class="select2" name="type">
                                <option value="" disabled selected>Type</option>
                                <option value="all" @selected(request('type') == 'all')>All</option>
                                <option value="percent" @selected(request('type') == 'percent')>Percent</option>
                                <option value="fixed" @selected(request('type') == 'fixed')>Fixed</option>
                            </select>
                        </div>

                        <div>
                            <select class="select2" name="is_approved">
                                <option value="" disabled selected>Is Approved</option>
                                <option value="all" @selected(request('is_approved') == 'all')>All</option>
                                <option value="approved" @selected(request('is_approved') == 'approved')>Approved</option>
                                <option value="pending" @selected(request('is_approved') == 'pending')>Pending</option>
                                <option value="rejected" @selected(request('is_approved') == 'rejected')>Rejected</option>
                            </select>
                        </div>


                        <div>
                            <select class="select2" name="status">
                                <option value="" disabled selected>Status</option>
                                <option value="all" @selected(request('status') == 'all')>All</option>
                                <option value="1" @selected(request('status') == '1')>Active</option>
                                <option value="0" @selected(request('status') == '0')>Inactive</option>
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
                            <button type="submit" class="btn btn-primary">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-filter"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 3h-16a1 1 0 0 0 -1 1v2.227l.008 .223a3 3 0 0 0 .772 1.795l4.22 4.641v8.114a1 1 0 0 0 1.316 .949l6 -2l.108 -.043a1 1 0 0 0 .576 -.906v-6.586l4.121 -4.12a3 3 0 0 0 .879 -2.123v-2.171a1 1 0 0 0 -1 -1z" /></svg>                            
                                Filter
                            </button>
                            <a href="{{ route('admin.coupons.index') }}" class="btn btn-primary">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-restore"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3.06 13a9 9 0 1 0 .49 -4.087" /><path d="M3 4.001v5h5" /><path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /></svg>
                                Reset
                            </a>
                        </div>
                    </form>
                    
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Type</th>
                                    <th>Value</th>
                                    <th>Minimum Order Amount</th>
                                    <th>Course Categories</th>
                                    <th>Created By Instructor</th>
                                    <th>Expire Date</th>
                                    <th>Status</th>
                                    <th>Approve</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($coupons as $coupon)
                                    <tr>
                                        <td>{{ $coupon->code }}</td>
                                        <td>{{ $coupon->type }}</td>
                                        <td>
                                            {{ $coupon->type == 'percent' ? $coupon->value . '%' : number_format($coupon->value) . 'đ' }}
                                        </td>
                                        <td>{{ number_format($coupon->minimum_order_amount) . 'đ' }}</td>
                                        <td>
                                            @foreach ($coupon->courseCategories as $category)
                                                <span class="badge bg-blue text-blue-fg">{{ $category->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if ($coupon->instructor)
                                                <span class="badge bg-blue text-blue-fg">{{ $coupon->instructor->name }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $coupon->expire_date }}</td>
                                        <td>
                                            @if ($coupon->status == 1)
                                               <span class="badge bg-lime text-lime-fg">Active</span> 
                                            @else 
                                               <span class="badge bg-red text-red-fg">Inactive</span> 
                                            @endif
                                        </td>
                                        <td>
                                            <select name="" class="form-control update-approval-status" data-id="{{ $coupon->id }}" 
                                                data-route="{{ route('admin.coupons.update-approval', $coupon->id) }}"
                                            >
                                                <option @selected($coupon->is_approved == 'pending') value="pending">Pending</option>
                                                <option @selected($coupon->is_approved == 'approved') value="approved">Approved</option>
                                                <option @selected($coupon->is_approved == 'rejected') value="rejected">Rejected</option>
                                            </select>
                                        </td>
                                        <td>
                                            {{-- @if (!$coupon->instructor) --}}
                                                <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                                                    class="btn-sm btn-primary">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                            {{-- @endif --}}
                                            
                                            <a href="{{ route('admin.coupons.destroy', $coupon->id) }}"
                                                class="text-red delete-item">
                                                <i class="ti ti-trash-x"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No Data Found!</td>
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
