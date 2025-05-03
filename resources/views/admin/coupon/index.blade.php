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
                                            @if (!$coupon->instructor)
                                                <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                                                    class="btn-sm btn-primary">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                            @endif
                                            
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
