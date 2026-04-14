@extends('delivery.layouts.app')
@section('title', 'Order Details')

@section('content')
<div class="mb-4">
    <a href="{{ route('delivery.orders.index') }}" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left"></i> Back to Tasks
    </a>
</div>

<div class="card mb-4 border-0">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bolder mb-0">#{{ $order->order_number }}</h4>
            <span class="status-badge status-{{ $order->status }}" id="order-status-badge">
                {{ str_replace('_', ' ', $order->status) }}
            </span>
        </div>

        <div class="row g-4 pt-3 border-top">
            <div class="col-12">
                <label class="text-muted small text-uppercase fw-600 d-block mb-1">Customer</label>
                <div class="fw-bold fs-5">{{ $order->customer->name ?? 'N/A' }}</div>
                <div class="d-flex align-items-center gap-2 mt-2">
                    <a href="tel:{{ $order->customer->mobile ?? '' }}" class="btn btn-primary btn-sm rounded-pill px-3">
                        <i class="bi bi-telephone-fill me-1"></i> Call
                    </a>
                    <a href="https://wa.me/{{ $order->customer->mobile ?? '' }}" class="btn btn-success btn-sm rounded-pill px-3">
                        <i class="bi bi-whatsapp me-1"></i> WhatsApp
                    </a>
                </div>
            </div>

            <div class="col-12">
                <label class="text-muted small text-uppercase fw-600 d-block mb-1">Shipping Address</label>
                <div class="fw-semibold">{{ $order->shipping_address }}</div>
                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($order->shipping_address) }}" target="_blank" class="text-primary text-decoration-none small mt-1 d-block">
                    <i class="bi bi-map"></i> View on Google Maps
                </a>
            </div>

            <div class="col-6">
                <label class="text-muted small text-uppercase fw-600 d-block mb-1">Payment Method</label>
                <div class="fw-bold {{ $order->payment_method === 'cod' ? 'text-danger' : 'text-success' }}">
                    {{ strtoupper($order->payment_method) }}
                </div>
            </div>

            <div class="col-6 text-end">
                <label class="text-muted small text-uppercase fw-600 d-block mb-1">Total Amount</label>
                <div class="fw-bold fs-5">₹{{ number_format($order->total_amount, 2) }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Order Status Actions -->
<div class="mb-4" id="status-actions">
    @if($order->status === 'assigned')
        <form action="{{ route('delivery.orders.update-status', $order->id) }}" method="POST" class="ajax-form">
            @csrf
            <input type="hidden" name="status" value="picked_up">
            <button type="submit" class="btn btn-primary btn-lg w-100 py-3 shadow-sm rounded-pill">
                <i class="bi bi-bag-check me-2"></i> Mark as Picked Up
            </button>
        </form>
    @elseif($order->status === 'picked_up')
        <form action="{{ route('delivery.orders.update-status', $order->id) }}" method="POST" class="ajax-form">
            @csrf
            <input type="hidden" name="status" value="out_for_delivery">
            <button type="submit" class="btn btn-warning btn-lg w-100 py-3 text-white shadow-sm rounded-pill">
                <i class="bi bi-bicycle me-2"></i> Start Delivery
            </button>
        </form>
    @elseif($order->status === 'out_for_delivery')
        <style>
        .slide-container {
            width: 100%;
            height: 65px;
            background-color: #e2e8f0;
            border-radius: 50px;
            position: relative;
            overflow: hidden;
            box-shadow: inset 0px 3px 6px rgba(0,0,0,0.1);
        }
        .slide-text {
            position: absolute;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 700;
            color: #64748b;
            letter-spacing: 1px;
            z-index: 1;
            pointer-events: none;
            user-select: none;
            text-transform: uppercase;
        }
        .slide-thumb {
            width: 55px;
            height: 55px;
            background-color: #10b981;
            border-radius: 50px;
            position: absolute;
            top: 5px;
            left: 5px;
            z-index: 3;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 24px;
            cursor: grab;
            box-shadow: 0px 4px 6px rgba(0,0,0,0.2);
            transition: transform 0.1s;
        }
        .slide-thumb:active {
            cursor: grabbing;
        }
        .slide-fill {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 0;
            background-color: #d1fae5;
            z-index: 2;
            border-radius: 50px 0 0 50px;
        }
        </style>
        <div class="slide-container" id="slideContainer">
            <div class="slide-fill" id="slideFill"></div>
            <div class="slide-text">Slide to Deliver »</div>
            <div class="slide-thumb" id="slideThumb">
                <i class="bi bi-chevron-double-right"></i>
            </div>
        </div>
    @elseif($order->status === 'delivered')
        <div class="alert alert-success text-center py-3 rounded-4 shadow-sm border-0">
            <i class="bi bi-check-circle-fill me-2"></i> Order Delivered Successfully!
            <br>
            <span class="fw-bold">Payment: </span>
            @if($order->payment_status === 'paid')
                <span class="badge bg-success">Paid</span>
            @else
                <span class="badge bg-warning">Pending</span>
            @endif
        </div>
    @elseif($order->status === 'cancelled')
        <div class="alert alert-danger text-center py-3 rounded-4 shadow-sm border-0">
            <i class="bi bi-x-circle-fill me-2"></i> Order Cancelled
        </div>
    @endif

    @if(in_array($order->status, ['assigned', 'picked_up', 'out_for_delivery']))
        <button type="button" class="btn btn-outline-danger w-100 py-3 mt-3 shadow-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
            <i class="bi bi-x-circle me-2"></i> Cancel Order
        </button>
    @endif
</div>

<!-- Delivery Confirmation Modal -->
<div class="modal fade" id="confirmDeliveryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered px-3">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-success text-white border-0 py-3">
                <h5 class="modal-title fw-bolder">Payment Confirmation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="mb-4">
                    <div class="bg-success-subtle p-3 rounded-circle d-inline-flex">
                        <i class="bi bi-cash-coin text-success fs-1"></i>
                    </div>
                </div>
                
                @if($order->payment_method === 'cod')
                    <div class="mb-2 text-uppercase text-muted small fw-bold tracking-wider">Amount to Collect</div>
                    <h1 class="fw-bolder text-dark mb-4 display-5">₹{{ number_format($order->total_amount, 2) }}</h1>
                    
                    <div class="alert alert-warning border-0 rounded-3 mb-4 small text-start">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Please ensure you have received the exact cash amount from <strong>{{ $order->customer->name ?? 'the customer' }}</strong>.
                    </div>

                    <form action="{{ route('delivery.orders.update-status', $order->id) }}" method="POST" class="ajax-form">
                        @csrf
                        <input type="hidden" name="status" value="delivered">
                        <button type="submit" class="btn btn-success btn-lg w-100 py-3 rounded-pill fw-bold shadow-sm">
                            <i class="bi bi-check2-circle me-2"></i> Collect Cash & Deliver
                        </button>
                    </form>
                @else
                    <div class="mb-2 text-uppercase text-muted small fw-bold tracking-wider">Payment Status</div>
                    <h2 class="fw-bolder text-success mb-3">PAID ONLINE</h2>
                    
                    <div class="alert alert-success-subtle border-0 rounded-3 mb-4 small text-start">
                        <i class="bi bi-check-circle-fill me-2 text-success"></i>
                        Payment was already received online. Just hand over the order!
                    </div>

                    <form action="{{ route('delivery.orders.update-status', $order->id) }}" method="POST" class="ajax-form">
                        @csrf
                        <input type="hidden" name="status" value="delivered">
                        <button type="submit" class="btn btn-success btn-lg w-100 py-3 rounded-pill fw-bold shadow-sm">
                            <i class="bi bi-truck me-2"></i> Confirm Delivery
                        </button>
                    </form>
                @endif
                
                <button type="button" class="btn btn-link text-muted text-decoration-none mt-3 fw-bold" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered px-3">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-danger text-white border-0 py-3">
                <h5 class="modal-title fw-bolder">Cancel Order</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="mb-4">
                    <div class="bg-danger-subtle p-3 rounded-circle d-inline-flex">
                        <i class="bi bi-exclamation-triangle text-danger fs-1"></i>
                    </div>
                </div>
                
                <h4 class="fw-bolder text-dark mb-3">Are you sure?</h4>
                <p class="text-muted small mb-4">This action will cancel the order and notify the admin immediately.</p>
                
                <form action="{{ route('delivery.orders.update-status', $order->id) }}" method="POST" class="ajax-form text-start">
                    @csrf
                    <input type="hidden" name="status" value="cancelled">
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold">Reason for Cancellation (Optional)</label>
                        <textarea name="comment" class="form-control rounded-3" rows="3" placeholder="e.g. Customer not responding, address not found..."></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-danger btn-lg w-100 py-3 rounded-pill fw-bold shadow-sm mb-2">
                        <i class="bi bi-x-circle me-2"></i> Confirm Cancellation
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-lg w-100 py-3 rounded-pill fw-bold" data-bs-dismiss="modal">Go Back</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Order items -->
<div class="card border-0 mb-4 overflow-hidden">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="fw-bold mb-0">Order Items</h6>
    </div>
    <div class="card-body p-0">
        @foreach($order->items as $item)
        <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
            <div>
                <div class="fw-bold text-dark">{{ $item->product->name ?? 'Product' }}</div>
                <small class="text-muted">{{ $item->quantity }} x ₹{{ number_format($item->price, 2) }}</small>
            </div>
            <div class="fw-bold">₹{{ number_format($item->price * $item->quantity, 2) }}</div>
        </div>
        @endforeach
    </div>
</div>

<!-- Timeline -->
<div class="card border-0">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="fw-bold mb-0">Status History</h6>
    </div>
    <div class="card-body">
        <div class="timeline ps-3 border-start">
            @forelse($order->statusHistory as $history)
            <div class="position-relative mb-4">
                <div class="position-absolute translate-middle-x" style="left: -17px; top: 0;">
                    <i class="bi bi-circle-fill text-primary" style="font-size: 10px;"></i>
                </div>
                <div class="small fw-bold text-uppercase">{{ str_replace('_', ' ', $history->status) }}</div>
                <div class="small text-muted">{{ $history->created_at->format('d M, h:i A') }}</div>
                @if($history->comment)
                <div class="small text-muted mt-1 italic">"{{ $history->comment }}"</div>
                @endif
            </div>
            @empty
            <p class="text-muted small">No history available.</p>
            @endforelse
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    window.location.reload_on_success = true;

    // Slide to Deliver Logic
    const container = document.getElementById('slideContainer');
    const thumb = document.getElementById('slideThumb');
    const fill = document.getElementById('slideFill');
    
    if (container && thumb && fill) {
        let isDragging = false;
        let startX = 0;
        
        function getEventX(e) {
            return e.type.includes('mouse') ? e.pageX : e.touches[0].pageX;
        }

        function startDrag(e) {
            isDragging = true;
            startX = getEventX(e);
            thumb.style.transition = 'none';
            fill.style.transition = 'none';
        }
        
        function stopDrag() {
            if (!isDragging) return;
            isDragging = false;
            
            const containerWidth = container.offsetWidth;
            const thumbWidth = thumb.offsetWidth;
            const maxVal = containerWidth - thumbWidth - 10;
            
            let currentTransform = thumb.style.transform;
            let match = currentTransform.match(/translateX\(([-\d.]+)px\)/);
            let x = match ? parseFloat(match[1]) : 0;
            
            if (x >= maxVal - 10) {
                // Success: Trigger the modal
                thumb.style.transform = `translateX(${maxVal}px)`;
                fill.style.width = `100%`;
                
                let modal = new bootstrap.Modal(document.getElementById('confirmDeliveryModal'));
                modal.show();
                
                // Reset slider after a short delay smoothly
                setTimeout(() => {
                    thumb.style.transition = 'transform 0.4s ease';
                    fill.style.transition = 'width 0.4s ease';
                    thumb.style.transform = `translateX(0px)`;
                    fill.style.width = `0px`;
                }, 800);
            } else {
                // Revert: Did not slide fully
                thumb.style.transition = 'transform 0.3s ease';
                fill.style.transition = 'width 0.3s ease';
                thumb.style.transform = `translateX(0px)`;
                fill.style.width = `0px`;
            }
        }
        
        function moveDrag(e) {
            if (!isDragging) return;
            
            let currentX = getEventX(e);
            let diff = currentX - startX;
            
            const containerWidth = container.offsetWidth;
            const thumbWidth = thumb.offsetWidth;
            const maxVal = containerWidth - thumbWidth - 10;
            
            if (diff < 0) diff = 0;
            if (diff > maxVal) diff = maxVal;
            
            thumb.style.transform = `translateX(${diff}px)`;
            fill.style.width = `${diff + thumbWidth/2}px`;
        }
        
        thumb.addEventListener('mousedown', startDrag);
        document.addEventListener('mouseup', stopDrag);
        document.addEventListener('mousemove', moveDrag);
        
        thumb.addEventListener('touchstart', startDrag, {passive: true});
        document.addEventListener('touchend', stopDrag);
        document.addEventListener('touchmove', moveDrag, {passive: true});
    }
</script>
@endpush
