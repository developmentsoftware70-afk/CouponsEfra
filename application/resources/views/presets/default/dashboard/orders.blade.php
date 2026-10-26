@extends($activeTemplate . 'layouts.master')
@section('page-title', __('My Orders'))

@section('content')
<div class="container my-5">
    <h2 class="mb-4" style="font-family: 'Inter', sans-serif; font-weight: 700;">{{ __('My Orders') }}</h2>
    <div class="table-responsive">
        <table class="table table-hover align-middle" style="font-family: 'Inter', sans-serif;">
            <thead class="table-light">
                <tr>
                    <th>{{ __('Order ID') }}</th>
                    <th>{{ __('Product') }}</th>
                    <th>{{ __('Amount') }}</th>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('Status') }}</th>
                </tr>
            </thead>
            <tbody id="orders-table-body">
                @forelse($orders as $order)
                    <tr id="order-{{ $order->id }}">
                        <td>#{{ $order->id }}</td>
                        <td class="d-flex align-items-center">
                            @if($order->product_image)
                                <img src="{{ getImage($order->product_image, '50x50') }}" alt="{{ $order->product_name }}" class="me-2 rounded" style="width:50px;height:50px;object-fit:cover;">
                            @endif
                            <span>{{ $order->product_name }}</span>
                        </td>
                        <td>{{ showAmount($order->amount) }} {{ $order->currency }}</td>
                        <td>{{ showDateTime($order->created_at) }}</td>
                        <td>
                            <span class="badge bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}" id="order-status-{{ $order->id }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">{{ __('No orders found.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.16.0/echo.iife.min.js" integrity="sha512-a9tVdPjQjS6N1BvYwY5ZpLk3t3lVJtG9pD/2H5c2i5v9i+U6xC6jZ6y0K6c0Rz1eU5kW2h2j3E1FvR7G+Z6ZQw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Echo (assumes Pusher credentials are set in .env and config/broadcasting.php)
        if (typeof Echo === 'undefined') {
            console.warn('Laravel Echo not loaded. Real‑time updates will not work.');
            return;
        }
        const echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ config('broadcasting.connections.pusher.key') }}',
            cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
            forceTLS: true,
            encrypted: true,
        });

        echo.private('orders.{{ auth()->id() }}')
            .listen('OrderStatusUpdated', (e) => {
                const row = document.getElementById('order-' + e.order_id);
                if (row) {
                    const statusBadge = document.getElementById('order-status-' + e.order_id);
                    if (statusBadge) {
                        statusBadge.textContent = e.status.charAt(0).toUpperCase() + e.status.slice(1);
                        // Update badge color
                        const statusClassMap = {
                            pending: 'warning',
                            processing: 'info',
                            shipped: 'primary',
                            delivered: 'success',
                            cancelled: 'danger',
                            failed: 'danger'
                        };
                        const newClass = 'bg-' + (statusClassMap[e.status] || 'secondary');
                        statusBadge.className = 'badge ' + newClass;
                    }
                } else {
                    // If new order, optionally prepend to table
                    // For brevity, we skip implementation here.
                }
            });
    });
</script>
@endpush
