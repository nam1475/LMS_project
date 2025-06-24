@extends('admin.layouts.master')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Reviews</h3>
                    
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th width="30%">User</th>
                                    <th width="15%">Rating</th>
                                    <th>Review</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reviews  as $review)
                                <tr>
                                    <td>
                                        <div>{{ $review->user->name }}</div>
                                        <div class="text-muted">{{ $review->user->email }}</div>
                                    </td>
                                    <td>
                                        <div class="text-yellow">
                                            @for($i = 1; $i <= $review->rating; $i++)
                                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-star"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z" /></svg>
                                            @endfor
                                        </div>
                                    </td>
                                    <td style="width: 300px">{{ $review->review }}</td>
                                    {{-- <td>
                                        @if($review->status == 1)
                                            <span class="badge bg-lime text-lime-fg">Approved</span>
                                        @else
                                            <span class="badge bg-red text-red-fg">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.reviews.update', $review->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                        <select name="status" class="form-control" onchange="this.form.submit()">
                                            <option @selected($review->status == 0) value="0">Pending</option>
                                            <option @selected($review->status == 1) value="1">Approved</option>
                                        </select>
                                        </form>
                                    </td> --}}
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
                        {{ $reviews->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
