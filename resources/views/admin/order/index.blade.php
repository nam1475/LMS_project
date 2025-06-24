@extends('admin.layouts.master')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Orders</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.index') }}" class="d-flex align-items-center justify-content-between mb-3">
                        <div class="form-floating">
                            <input type="text" value="{{ request('search') }}" class="form-control" name="search" id="floatingInput" placeholder="name@example.com">
                            <label for="floatingInput">Search</label>
                        </div>

                        <div>
                            <select class="select2" name="status">
                                <option value="" disabled selected>Status</option>
                                <option value="all" @selected(request('status') == 'all')>All</option>
                                <option value="approved" @selected(request('status') == 'approved')>Approved</option>
                                <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                            </select>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-filter"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 3h-16a1 1 0 0 0 -1 1v2.227l.008 .223a3 3 0 0 0 .772 1.795l4.22 4.641v8.114a1 1 0 0 0 1.316 .949l6 -2l.108 -.043a1 1 0 0 0 .576 -.906v-6.586l4.121 -4.12a3 3 0 0 0 .879 -2.123v-2.171a1 1 0 0 0 -1 -1z" /></svg>                            
                                Filter
                            </button>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-restore"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3.06 13a9 9 0 1 0 .49 -4.087" /><path d="M3 4.001v5h5" /><path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /></svg>
                                Reset
                            </a>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Invoice ID</th>
                                    <th>Name</th>
                                    <th>Amount</th>
                                    <th>Discount by Coupon</th>
                                    <th>Paid Amount</th>
                                    <th>Currency</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                <tr>
                                    <td>
                                        {{-- {{ $loop->iteration }} --}}
                                        {{ $order->invoice_id }}
                                    </td>
                                <td>
                                    <div>
                                        {{ $order->customer->name }}
                                    </div>
                                    <small>{{ $order->customer->email }}</small>
                                </td>
                                <td>
                                    {{ number_format($order->total_amount) }}
                                </td>
                                <td>{{ number_format($order->coupon_amount) }}</td>
                                <td>
                                    {{ number_format($order->paid_amount) }}
                                </td>
                                <td>
                                    {{ $order->currency }}
                                </td>
                                <td>
                                    @if($order->status == 'pending')
                                    <span class="badge bg-yellow text-yellow-fg">{{ $order->status }}</span>
                                    @else
                                    <span class="badge bg-green text-green-fg">{{ $order->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->invoice_id) }}" class="btn-sm btn-primary">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    
                                </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No Data Found!</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
