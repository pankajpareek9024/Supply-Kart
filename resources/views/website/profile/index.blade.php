@extends('website.layouts.app')

@section('content')
<div class="container py-5 animate__animated animate__fadeIn">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white p-3 rounded-pill shadow-sm d-inline-flex border">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted fw-medium"><i class="fa-solid fa-house px-1"></i> Home</a></li>
            <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">My Profile</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="bg-light p-4 text-center border-bottom">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bolder mb-3 shadow-sm" style="width: 80px; height: 80px; font-size: 2rem;">
                        {{ substr($user->owner_name, 0, 1) }}
                    </div>
                    <h5 class="fw-bolder text-dark mb-1">{{ $user->shop_name }}</h5>
                    <p class="text-muted small mb-0">+91 {{ $user->mobile }}</p>
                </div>
                <div class="list-group list-group-flush border-0">
                    <a href="{{ route('profile.index') }}" class="list-group-item list-group-item-action d-flex align-items-center py-3 px-4 active bg-success border-0 fw-bold">
                        <i class="fa-regular fa-user me-3 text-white"></i> My Details
                    </a>
                    <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action d-flex align-items-center py-3 px-4 text-dark fw-medium border-0 hover-bg-light">
                        <i class="fa-solid fa-box me-3 text-muted"></i> Order History
                    </a>
                    <a href="{{ route('logout') }}" class="list-group-item list-group-item-action d-flex align-items-center py-3 px-4 text-danger fw-medium border-0 hover-bg-light">
                        <i class="fa-solid fa-power-off me-3"></i> Logout
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom py-4 px-4 px-md-5">
                    <h4 class="fw-bolder mb-0 text-dark"><i class="fa-solid fa-gear me-2 text-primary-orange"></i>Profile Settings</h4>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    
                    @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center rounded-3 mb-4 shadow-sm border-0" role="alert">
                            <i class="fa-solid fa-circle-check me-2"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Mobile Number (Non-editable)</label>
                                <input type="text" class="form-control bg-light focus-ring-success" value="+91 {{ $user->mobile }}" readonly>
                                <small class="text-muted mt-1 d-block"><i class="fa-solid fa-circle-info me-1"></i> Number cannot be changed</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Shop Name</label>
                                <input type="text" name="shop_name" class="form-control focus-ring-success" value="{{ $user->shop_name }}" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Owner Name</label>
                                <input type="text" name="owner_name" class="form-control focus-ring-success" value="{{ $user->owner_name }}" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark">GST Number</label>
                                <input type="text" name="gst_number" class="form-control focus-ring-success text-uppercase" value="{{ $user->gst_number }}" placeholder="Optional">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark">Complete Delivery Address</label>
                                <textarea name="address" class="form-control focus-ring-success" rows="3" required>{{ $user->address }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark">Geolocation (Lat, Lng)</label>
                                <div class="input-group">
                                    <input type="text" name="lat" id="lat" class="form-control focus-ring-success" value="{{ $user->lat }}" placeholder="Latitude" readonly>
                                    <input type="text" name="lng" id="lng" class="form-control focus-ring-success" value="{{ $user->lng }}" placeholder="Longitude" readonly>
                                    <button class="btn btn-outline-secondary d-flex align-items-center" type="button" id="detectLocationBtn">
                                        <i class="fa-solid fa-location-crosshairs me-2"></i> Detect Location
                                    </button>
                                </div>
                                <small class="text-muted mt-1 d-block" id="locationStatus">Click to get precise GPS coordinates for delivery accuracy.</small>
                            </div>
                        </div>

                        <div class="mt-5 border-top pt-4 text-end">
                            <button type="submit" class="btn btn-green btn-lg px-5 rounded-pill fw-bold shadow-lg shadow-glow">
                                Save Changes <i class="fa-solid fa-floppy-disk ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('detectLocationBtn').addEventListener('click', function() {
    const statusText = document.getElementById('locationStatus');
    
    if (!navigator.geolocation) {
        statusText.textContent = "Geolocation is not supported by your browser";
        statusText.classList.add('text-danger');
        return;
    }

    statusText.textContent = "Locating...";
    statusText.classList.remove('text-danger', 'text-success');

    navigator.geolocation.getCurrentPosition(
        function(position) {
            document.getElementById('lat').value = position.coords.latitude;
            document.getElementById('lng').value = position.coords.longitude;
            statusText.textContent = "Location detected successfully!";
            statusText.classList.add('text-success');
        },
        function(error) {
            statusText.textContent = "Unable to retrieve your location";
            statusText.classList.add('text-danger');
        }
    );
});
</script>
@endpush
@endsection
