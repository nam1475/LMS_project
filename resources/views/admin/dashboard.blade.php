@extends('admin.layouts.master')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <h2 class="page-title">
                       Thống kê
                    </h2>
                </div>

            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="row row-cards">
                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span
                                                class="bg-primary text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                                                    <path d="M12 3v3m0 12v3" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                <b>{{ config('settings.currency_icon') }}{{ $todaysOrder }}</b>
                                            </div>
                                            <div class="text-secondary">
                                                Đơn hàng hôm nay
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span
                                                class="bg-primary text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                                                    <path d="M12 3v3m0 12v3" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                <b>{{ config('settings.currency_icon') }}{{ $thisWeekOrders }}</b>
                                            </div>
                                            <div class="text-secondary">
                                                Đơn hàng tuần này
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span
                                                class="bg-primary text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                                                    <path d="M12 3v3m0 12v3" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                <b>{{ config('settings.currency_icon') }}{{ $thisMonthOrders }}</b>
                                            </div>
                                            <div class="text-secondary">
                                                Đơn hàng tháng này
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span
                                                class="bg-primary text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                                                    <path d="M12 3v3m0 12v3" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                <b>{{ config('settings.currency_icon') }}{{ $thisYearOrders }}</b>
                                            </div>
                                            <div class="text-secondary">
                                                Đơn hàng năm nay
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span
                                                class="bg-primary text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                                <i class="ti ti-shopping-cart"></i>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                <b>{{ $totalOrders }}</b>
                                            </div>
                                            <div class="text-secondary">
                                                Tổng số đơn hàng
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span
                                                class="bg-primary text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                                <i class="ti ti-certificate"></i>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                <b>{{ $pendingCourses }}</b>
                                            </div>
                                            <div class="text-secondary">
                                                Khóa học đang chờ duyệt
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span
                                                class="bg-primary text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                                <i class="ti ti-certificate-off"></i>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                <b>{{ $rejectedCourses }}</b>
                                            </div>
                                            <div class="text-secondary">
                                                Khóa học bị từ chối
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span
                                                class="bg-primary text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                                <i class="ti ti-certificate"></i>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                <b>{{ $totalCourses }}</b>
                                            </div>
                                            <div class="text-secondary">
                                                Tổng số khóa học
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <canvas id="orderChart" style="height: 300px"></canvas>
            </div>

            <div class="mt-4">
                <div class="row">
                    <div class="col-4">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Khóa học gần đây</h3>
                            </div>
                            <div class="card-table table-responsive">
                                <table class="table table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>Khóa học</th>
                                            <th>Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentCourses as $course)
                                            <tr>
                                                <td>

                                                    <a href="#" class="ms-1"
                                                        aria-label="Mở trang web"><!-- Download SVG icon from http://tabler-icons.io/i/link -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="icon">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <path d="M9 15l6 -6"></path>
                                                            <path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464">
                                                            </path>
                                                            <path
                                                                d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463">
                                                            </path>
                                                        </svg>
                                                        {{ Str::limit($course->title, 40) }}
                                                    </a>
                                                </td>
                                                <td class="text-secondary">
                                                    @if ($course->is_approved == 'approved')
                                                        <span class="badge bg-success text-white">Đã duyệt</span>
                                                    @elseif($course->is_approved == 'pending')
                                                        <span class="badge bg-warning text-white">Chờ duyệt</span>
                                                    @elseif($course->is_approved == 'rejected')
                                                        <span class="badge bg-danger text-white">Bị từ chối</span>
                                                    @endif

                                                </td>

                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                      <div class="card">
                          <div class="card-header">
                              <h3 class="card-title">Blog gần đây</h3>
                          </div>
                          <div class="card-table table-responsive">
                              <table class="table table-vcenter">
                                  <thead>
                                      <tr>
                                          <th>Tiêu đề</th>
                                          <th>Trạng thái</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @foreach ($recentBlogs as $blog)
                                          <tr>
                                              <td>

                                                  <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="ms-1"
                                                      aria-label="Mở trang web"><!-- Download SVG icon from http://tabler-icons.io/i/link -->
                                                      <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                          height="24" viewBox="0 0 24 24" fill="none"
                                                          stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                          stroke-linejoin="round" class="icon">
                                                          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                          <path d="M9 15l6 -6"></path>
                                                          <path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464">
                                                          </path>
                                                          <path
                                                              d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463">
                                                          </path>
                                                      </svg>
                                                      {{ Str::limit($blog->title, 50) }}
                                                  </a>
                                              </td>
                                              <td class="text-secondary">
                                                  @if ($blog->status == 1)
                                                      <span class="badge bg-success text-white">Hoạt động</span>
                                                  @else
                                                      <span class="badge bg-danger text-white">Không hoạt động</span>
                                                  @endif

                                              </td>

                                          </tr>
                                      @endforeach

                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div>

                    <div class="col-4">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Đơn hàng gần đây</h3>
                            </div>
                            <div class="card-table table-responsive">
                                <table class="table table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>Hóa đơn</th>
                                            <th>Người dùng</th>
                                            <th>Số tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentOrders as $order)
                                            <tr>
                                              <td>
                                                <a href="{{ route('admin.orders.show', $order->id) }}">
                                                  #{{ $order->invoice_id }}
                                                </a>
                                              </td>
                                                <td class="text-start">
                                                    {{ $order->customer->name }} 
                                                </td>
                                              <td>
                                                {{ $order->total_amount }} {{ $order->currency }}
                                              </td>

                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('orderChart').getContext('2d');
        const orderChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                datasets: [{
                        label: 'Số tiền đơn hàng ({{ config('settings.currency_icon') }})',
                        data: @json($monthlyOrderSums),
                        backgroundColor: 'rgba(0, 84, 166, 0.7)',
                        borderColor: 'rgb(0, 84, 166)',
                        borderWidth: 1,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Số lượng đơn hàng',
                        data: @json($monthlyOrderCounts),
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        type: 'line',
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        begainAtZero: true,
                        title: {
                            display: true,
                            text: 'Số tiền đơn hàng ({{ config('settings.currency_icon') }})'
                        },
                        position: 'left'
                    },
                    y1: {
                        begainAtZero: true,
                        title: {
                            display: true,
                            text: 'Số lượng đơn hàng'
                        },
                        position: 'right',
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        })
    </script>
@endpush
