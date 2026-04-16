@extends('website.layouts.app')

@section('content')
<div class="container py-5 animate__animated animate__fadeIn">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white p-3 rounded-pill shadow-sm d-inline-flex border">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted fw-medium"><i class="fa-solid fa-house px-1"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-decoration-none text-muted fw-medium">Orders</a></li>
            <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Order {{ $order->order_number }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Main Order Details -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-4 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bolder mb-1 text-dark">Order #{{ $order->order_number }}</h4>
                        <span class="text-muted small">Placed on {{ $order->created_at->format('d M, Y h:i A') }}</span>
                    </div>
                    <div>
                        @php
                            $statusMap = [
                                'pending'          => ['label' => 'Pending',          'class' => 'bg-secondary'],
                                'processing'       => ['label' => 'Processing',       'class' => 'bg-warning text-dark'],
                                'packed'           => ['label' => 'Packed',           'class' => 'bg-info text-dark'],
                                'out_for_delivery' => ['label' => 'Out for Delivery', 'class' => 'bg-primary'],
                                'delivered'        => ['label' => 'Delivered',        'class' => 'bg-success'],
                                'cancelled'        => ['label' => 'Cancelled',        'class' => 'bg-danger'],
                            ];
                            $statusInfo = $statusMap[$order->status] ?? ['label' => ucfirst($order->status), 'class' => 'bg-secondary'];
                        @endphp
                        <span class="badge {{ $statusInfo['class'] }} px-3 py-2 fs-6 rounded-pill">{{ $statusInfo['label'] }}</span>
                    </div>
                </div>

                <!-- Order Progress Tracker -->
                @if($order->status !== 'cancelled')
                <div class="px-4 py-4 bg-light border-bottom">
                    @php
                        $steps = ['pending', 'processing', 'packed', 'out_for_delivery', 'delivered'];
                        $stepLabels = ['Ordered', 'Processing', 'Packed', 'Out for Delivery', 'Delivered'];
                        $currentStep = array_search($order->status, $steps);
                        if($currentStep === false) $currentStep = 0;
                        $progressPercent = count($steps) > 1 ? ($currentStep / (count($steps) - 1)) * 100 : 0;
                    @endphp
                    <div class="d-flex align-items-start justify-content-between position-relative" style="padding: 0 16px;">
                        <!-- Background line -->
                        <div class="position-absolute bg-secondary bg-opacity-25" style="height: 2px; top: 16px; left: 32px; right: 32px; z-index: 0;"></div>
                        <!-- Progress line -->
                        <div class="position-absolute bg-success" style="height: 2px; top: 16px; left: 32px; z-index: 1; width: calc({{ $progressPercent }}% * ((100% - 64px) / 100%)); transition: width 0.5s;"></div>

                        @foreach($steps as $i => $step)
                            @php $isActive = $i <= $currentStep; @endphp
                            <div class="d-flex flex-column align-items-center position-relative" style="z-index: 2; flex: 1;">
                                <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm {{ $isActive ? 'bg-success text-white' : 'bg-white text-muted border' }}" style="width: 32px; height: 32px; font-size: 0.7rem; font-weight: 700; border: 2px solid {{ $isActive ? '#059669' : '#dee2e6' }}; transition: all 0.3s;">
                                    @if($isActive)
                                        <i class="fa-solid fa-check" style="font-size: 0.65rem;"></i>
                                    @else
                                        {{ $i + 1 }}
                                    @endif
                                </div>
                                <span class="mt-2 fw-medium text-center {{ $isActive ? 'text-success' : 'text-muted' }}" style="font-size: 0.62rem; max-width: 64px; line-height: 1.3;">{{ $stepLabels[$i] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th class="py-3 px-4 fw-medium">Product</th>
                                    <th class="py-3 px-4 fw-medium text-center">Unit</th>
                                    <th class="py-3 px-4 fw-medium text-center">Qty</th>
                                    <th class="py-3 px-4 fw-medium text-end">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr class="border-bottom">
                                    <td class="py-4 px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded p-2 me-3 flex-shrink-0" style="width: 60px; height: 60px;">
                                                <img src="{{ $item->product->image_url }}" class="img-fluid h-100 object-fit-contain mix-blend-multiply" alt="{{ $item->product->name }}">
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-0 text-dark">{{ $item->product->name }}</h6>
                                                <small class="text-muted">{{ $item->product->category->name ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-center text-muted fw-medium">{{ $item->product->unit }}</td>
                                    <td class="py-4 px-4 text-center fw-bolder text-dark">{{ $item->quantity }}</td>
                                    <td class="py-4 px-4 text-end fw-bolder text-success">₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white p-4 rounded-bottom-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-bold">₹{{ number_format($order->total_amount - $order->delivery_charge, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <span class="text-muted">Delivery Charge</span>
                        <span class="{{ $order->delivery_charge > 0 ? 'fw-bold text-danger' : 'fw-bold text-success' }}">
                            {{ $order->delivery_charge > 0 ? '₹' . number_format($order->delivery_charge, 2) : 'FREE' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bolder mb-0 text-dark">Total Paid</h5>
                        <h4 class="fw-bolder mb-0 text-success">₹{{ number_format($order->total_amount, 2) }}</h4>
                    </div>
                    <div class="mt-4 text-end d-flex gap-2 justify-content-end">
                        <form action="{{ route('orders.reorder', $order->id) }}" method="POST" class="d-inline reorder-form">
                            @csrf
                            <button type="submit" class="btn btn-primary fw-bold rounded-pill px-4">
                                <i class="fa-solid fa-rotate-right me-2"></i>Reorder Items
                            </button>
                        </form>
                        <a href="{{ route('orders.invoice', $order->id) }}" class="btn btn-outline-success fw-bold rounded-pill px-4">
                            <i class="fa-solid fa-file-invoice-dollar me-2"></i>Download Invoice
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Shipping Info -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-location-dot me-2 text-primary-green"></i>Shipping Details</h5>
                </div>
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-1">{{ $order->customer->shop_name }}</h6>
                    <p class="text-muted small mb-2">{{ $order->customer->owner_name }}</p>
                    <p class="text-muted mb-3 lh-base">{{ $order->shipping_address }}</p>
                    <div class="d-flex align-items-center text-muted">
                        <i class="fa-solid fa-phone me-2 text-success"></i>
                        <span class="fw-medium">+91 {{ $order->customer->mobile }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-wallet me-2 text-primary-green"></i>Payment Details</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-3">
                        <span class="text-muted fw-medium">Method</span>
                        <span class="badge bg-light text-dark border fw-bold px-3 py-2 rounded-pill">
                            <i class="fa-solid {{ $order->payment_method == 'online' ? 'fa-credit-card' : 'fa-money-bill-wave' }} me-1 text-success"></i>
                            {{ $order->payment_method == 'cod' ? 'Cash on Delivery' : 'Online Payment' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted fw-medium">Status</span>
                        @if($order->payment_status == 'paid')
                            <span class="badge bg-success bg-opacity-10 text-success border border-success fw-bold px-3 py-1 rounded-pill">
                                <i class="fa-solid fa-check me-1"></i>Paid
                            </span>
                        @elseif($order->payment_status == 'failed')
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger fw-bold px-3 py-1 rounded-pill">
                                <i class="fa-solid fa-times me-1"></i>Failed
                            </span>
                        @else
                            <span class="badge bg-warning bg-opacity-10 text-dark border border-warning fw-bold px-3 py-1 rounded-pill">
                                <i class="fa-solid fa-clock me-1"></i>Pay on Delivery
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Customer Review Section -->
            @if($order->status === 'delivered')
                @php $review = \App\Models\Review::where('order_id', $order->id)->first(); @endphp
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3 px-4 border-bottom">
                        <h5 class="fw-bold mb-0 text-dark">
                            <i class="fa-solid fa-star me-2 text-warning"></i>
                            {{ $review ? 'Your Review' : 'Rate Experience' }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @if($review)
                            <div class="mb-2">
                                @foreach(range(1, 5) as $i)
                                    <i class="fa-solid fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted opacity-25' }}"></i>
                                @endforeach
                            </div>
                            <p class="text-muted mb-0 fst-italic">"{{ $review->comment }}"</p>
                            <small class="text-muted mt-2 d-block">Submitted on {{ $review->created_at->format('d M, Y') }}</small>
                        @else
                            <form action="{{ route('orders.review', $order->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">RATING</label>
                                    <div class="star-rating d-flex gap-2 fs-4">
                                        @foreach(range(5, 1) as $i)
                                            <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" class="d-none" {{ $i == 5 ? 'checked' : '' }}>
                                            <label for="star{{ $i }}" class="cursor-pointer text-muted"><i class="fa-solid fa-star"></i></label>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">COMMENT (OPTIONAL)</label>
                                    <textarea name="comment" class="form-control" rows="2" placeholder="How was the delivery partner and service?"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary-green text-white w-100 fw-bold rounded-pill py-2">Submit Feedback</button>
                            </form>
                            <style>
                                .star-rating label:hover,
                                .star-rating label:hover ~ label,
                                .star-rating input:checked ~ label {
                                    color: #f59e0b !important;
                                }
                                .star-rating {
                                    display: flex;
                                    flex-direction: row-reverse;
                                    justify-content: flex-end;
                                }
                                .cursor-pointer { cursor: pointer; }
                            </style>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Live Delivery Tracking -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-motorcycle me-2 text-primary-orange"></i>Live Tracking</h5>
                    <span class="badge bg-primary px-3 py-1 rounded-pill">{{ strtoupper(str_replace('_', ' ', $order->status)) }}</span>
                </div>
                <div class="card-body p-4">
                    @if($order->status === 'cancelled')
                        <div class="text-center py-2">
                            <i class="fa-solid fa-circle-xmark fa-2x text-danger opacity-75 mb-3 d-block"></i>
                            <p class="text-muted mb-0 fw-medium">This order has been cancelled.</p>
                        </div>
                    @elseif(in_array($order->status, ['out_for_delivery']))
                        <div class="d-flex align-items-center mb-3 bg-light p-3 rounded-3">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm flex-shrink-0" style="width:48px;height:48px;">
                                <i class="fa-solid fa-person-biking fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-0">{{ $order->delivery_boy_name ?? 'Delivery Executive' }}</h6>
                                <small class="text-muted">Delivery Executive</small>
                            </div>
                        </div>
                        <a href="tel:{{ $order->delivery_boy_phone ?? '' }}" class="btn btn-outline-success fw-bold rounded-pill w-100 mb-3">
                            <i class="fa-solid fa-phone me-2"></i>Call Executive
                        </a>
                        <div class="rounded-3 overflow-hidden border d-flex align-items-center justify-content-center flex-column" style="height:140px; background: linear-gradient(135deg, #d1fae5, #a7f3d0);">
                            <i class="fa-solid fa-map-location-dot fa-2x text-success mb-2"></i>
                            <p class="text-success fw-bold mb-0 small">Live GPS Tracking Active</p>
                            <small class="text-muted">Tracking live location...</small>
                        </div>
                    @elseif($order->status === 'delivered')
                        <div class="text-center py-2">
                            <i class="fa-solid fa-check-circle fa-2x text-success opacity-75 mb-3 d-block"></i>
                            <p class="text-success mb-0 fw-medium">Order Delivered Successfully.</p>
                        </div>
                    @else
                        <div class="text-center py-2">
                            <i class="fa-solid fa-box-open fa-2x text-muted opacity-50 mb-3 d-block"></i>
                            <p class="text-muted mb-0 fw-medium">Tracking available once the order is out for delivery.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
