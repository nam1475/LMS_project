@extends('admin.layouts.master')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Notifications</h3>
                    <div class="card-actions">
                        {{-- <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus"></i>
                            Add new
                        </a> --}}
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Message</th>
                                    <th>Is Read</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($notifications as $notification)
                                    <tr>
                                        <td>{{ $notification->title }}</td>
                                        <td>
                                            <a class="mark-as-read" data-notification-id="{{ $notification->id }}"
                                                data-redirect-url="{{ $notification->data['url'] }}" 
                                                href="javascript:;">
                                                {{ $notification->data['message'] }}
                                            </a>
                                        </td>
                                        <td>
                                            @if ($notification->read_at)
                                                <span class="badge bg-success text-green-fg">Yes</span>
                                            @else
                                                <span class="badge bg-red text-red-fg">No</span>
                                            @endif      
                                        </td>
                                        <td>
                                            {{-- @if (!$notification->instructor)
                                                <a href="{{ route('admin.notifications.edit', $notification->id) }}"
                                                    class="btn-sm btn-primary">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                            @endif --}}
                                            
                                            <a href="{{ route('admin.notifications.destroy', $notification->id) }}"
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

@push('scripts')
    @vite(['resources/js/admin/notification.js'])
    
@endpush
