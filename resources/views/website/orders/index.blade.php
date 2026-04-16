@extends('website.layouts.app')

@section('content')
<div class="container py-5 animate__animated animate__fadeIn">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white p-3 rounded-pill shadow-sm d-inline-flex border">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted fw-medium"><i class="fa-solid fa-house px-1"></i> Home</a></li>
            <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">My Orders</li>
        </ol>
    </nav>

    <h2 class="fw-bolder mb-4 text-dark"><i class="fa-solid fa-box-open me-2 text-primary-orange"></i>Order History</h2>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center rounded-3 mb-4 shadow-sm border-0" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if($orders->isEmpty())
        <div class="card border-0 shadow-sm rounded-5 overflow-hidden mt-4">
            <div class="card-body p-5 text-center my-5">
                <i class="fa-solid fa-boxes-packing text-muted position-relative z-1 mb-4" style="font-size: 6rem; opacity: 0.5;"></i>
                <h3 class="fw-bolder text-dark mb-3">No Orders Yet</h3>
                <p class="text-muted fs-5 mb-5" style="max-width: 500px; margin: 0 auto;">You haven't placed any wholesale orders with us yet. Start exploring our catalog.</p>
                <a href="{{ route('products.list') }}" class="btn btn-green btn-lg px-5 py-3 rounded-pill fw-bold shadow-lg shadow-glow">Browse Products <i class="fa-solid fa-arrow-right ms-2"></i></a>
            </div>
        </div>
    @else
        <div class="table-responsive shadow-sm rounded-4 border-0">
            <table class="table table-hover table-borderless align-middle mb-0 bg-white">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="py-3 px-4 fw-bolder">Order ID</th>
                        <th class="py-3 px-4 fw-bolder">Date</th>
                        <th class="py-3 px-4 fw-bolder">Amount</th>
                        <th class="py-3 px-4 fw-bolder">Status</th>
                        <th class="py-3 px-4 fw-bolder text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr class="border-bottom">
                        <td class="py-3 px-4 fw-bold text-dark">{{ $order->order_number }}</td>
                        <td class="py-3 px-4 text-muted">{{ $order->created_at->format('d M, Y h:i A') }}</td>
                        <td class="py-3 px-4 fw-bolder text-success">₹{{ number_format($order->total_amount, 2) }}</td>
                        <td class="py-3 px-4">
                            @php
                                $statusMap = [
                                    'pending'          => ['label' => 'Pending',          'class' => 'bg-secondary bg-opacity-10 text-secondary border-secondary'],
                                    'processing'       => ['label' => 'Processing',       'class' => 'bg-warning bg-opacity-10 text-dark border-warning'],
                                    'packed'           => ['label' => 'Packed',           'class' => 'bg-info bg-opacity-10 text-info border-info'],
                                    'out_for_delivery' => ['label' => 'Out for Delivery', 'class' => 'bg-primary bg-opacity-10 text-primary border-primary'],
                                    'delivered'        => ['label' => 'Delivered',        'class' => 'bg-success bg-opacity-10 text-success border-success'],
                                    'cancelled'        => ['label' => 'Cancelled',        'class' => 'bg-danger bg-opacity-10 text-danger border-danger'],
                                ];
                                $si = $statusMap[$order->status] ?? ['label' => ucfirst($order->status), 'class' => 'bg-secondary bg-opacity-10 text-secondary border-secondary'];
                            @endphp
                            <span class="badge {{ $si['class'] }} border px-3 py-2 rounded-pill fw-bold">{{ $si['label'] }}</span>
                        </td>
                        <td class="py-3 px-4 text-end">
                            <form action="{{ route('orders.reorder', $order->id) }}" method="POST" class="d-inline reorder-form">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold me-2">Reorder</button>
                            </form>
                            <a href="{{ route('orders.details', $order->id) }}" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold">View Details</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
