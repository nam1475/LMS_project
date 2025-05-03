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
                                            {{-- @foreach ($coupon->instructors as $instructor)
                                                <span class="badge bg-blue text-blue-fg">{{ $instructor->name }}</span>
                                            @endforeach --}}
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
                                           
                                            <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                                                class="btn-sm btn-primary">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            
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
