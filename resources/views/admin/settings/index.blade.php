@extends('admin.layouts.app')
@section('title', 'Settings')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Settings</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Settings</h1>
        <p class="page-subtitle">Configure website and delivery settings</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
    @csrf
    <div class="row g-3">
        <div class="col-lg-8">

            {{-- Site Info --}}
            <div class="admin-card mb-3">
                <div class="admin-card-header"><h6 class="admin-card-title"><i class="bi bi-globe me-2"></i>Website Information</h6></div>
                <div class="admin-card-body">
                    <div class="mb-3">
                        <label class="form-label fw-600">Site Name <span class="text-danger">*</span></label>
                        <input type="text" name="site_name" class="form-control @error('site_name') is-invalid @enderror"
                            value="{{ old('site_name', $settings['site_name'] ?? 'SupplyKart') }}" required>
                        @error('site_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-600">Contact Email <span class="text-danger">*</span></label>
                            <input type="email" name="contact_email" class="form-control @error('contact_email') is-invalid @enderror"
                                value="{{ old('contact_email', $settings['contact_email'] ?? '') }}" required>
                            @error('contact_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-600">Contact Phone <span class="text-danger">*</span></label>
                            <input type="text" name="contact_phone" class="form-control @error('contact_phone') is-invalid @enderror"
                                value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}" required>
                            @error('contact_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label fw-600">Business Address <span class="text-danger">*</span></label>
                        <textarea name="contact_address" class="form-control @error('contact_address') is-invalid @enderror" rows="2" required>{{ old('contact_address', $settings['contact_address'] ?? '') }}</textarea>
                        @error('contact_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- Delivery Charges --}}
            <div class="admin-card mb-3">
                <div class="admin-card-header"><h6 class="admin-card-title"><i class="bi bi-truck me-2"></i>Delivery Charge Rules</h6></div>
                <div class="admin-card-body">
                    <div class="alert alert-info d-flex gap-2 mb-3" style="font-size:.85rem;">
                        <i class="bi bi-info-circle-fill mt-1"></i>
                        <div>
                            Delivery charge is applied when order total is <strong>below the free delivery minimum</strong>.
                            Orders at or above the minimum get <strong>FREE delivery</strong>.
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-600">Delivery Charge (₹) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="delivery_charge" class="form-control @error('delivery_charge') is-invalid @enderror"
                                    value="{{ old('delivery_charge', $settings['delivery_charge'] ?? 90) }}" min="0" step="1" required>
                                @error('delivery_charge')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <small class="text-muted">Charged when order total is below minimum.</small>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-600">Free Delivery Minimum (₹) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="free_delivery_min" class="form-control @error('free_delivery_min') is-invalid @enderror"
                                    value="{{ old('free_delivery_min', $settings['free_delivery_min'] ?? 999) }}" min="0" step="1" required>
                                @error('free_delivery_min')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <small class="text-muted">Orders ≥ this value get free delivery.</small>
                        </div>
                    </div>

                    <div class="mt-3 p-3 rounded" style="background:var(--body-bg);font-size:.85rem;">
                        <strong>Preview:</strong>
                        Orders below ₹<span id="prevMin">{{ $settings['free_delivery_min'] ?? 999 }}</span>
                        → Delivery: ₹<span id="prevCharge">{{ $settings['delivery_charge'] ?? 90 }}</span> &nbsp;|&nbsp;
                        Orders ≥ ₹<span id="prevMin2">{{ $settings['free_delivery_min'] ?? 999 }}</span>
                        → <span class="text-success fw-600">FREE Delivery</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- Branding --}}
        <div class="col-lg-4">
            <div class="admin-card mb-3">
                <div class="admin-card-header"><h6 class="admin-card-title"><i class="bi bi-image me-2"></i>Branding</h6></div>
                <div class="admin-card-body">

                    <div class="mb-4">
                        <label class="form-label fw-600">Logo</label>
                        @if(!empty($settings['logo']))
                            <img src="{{ Storage::url($settings['logo']) }}" class="d-block mb-2" style="max-height:60px;">
                        @endif
                        <input type="file" name="logo" class="form-control img-input" data-preview="logoPreview" accept="image/*">
                        <img id="logoPreview" src="" class="mt-2" style="display:none;max-height:60px;">
                        <small class="text-muted d-block mt-1">PNG / JPG / WebP — Max 2MB</small>
                    </div>

                    <div>
                        <label class="form-label fw-600">Favicon</label>
                        @if(!empty($settings['favicon']))
                            <img src="{{ Storage::url($settings['favicon']) }}" class="d-block mb-2" style="max-height:32px;">
                        @endif
                        <input type="file" name="favicon" class="form-control img-input" data-preview="faviconPreview" accept="image/*,.ico">
                        <img id="faviconPreview" src="" class="mt-2" style="display:none;max-height:32px;">
                        <small class="text-muted d-block mt-1">ICO / PNG — Max 512KB</small>
                    </div>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-save me-1"></i>Save Settings
                </button>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
// Live delivery charge preview
function updatePreview() {
    const min    = document.querySelector('[name=free_delivery_min]').value;
    const charge = document.querySelector('[name=delivery_charge]').value;
    document.getElementById('prevMin').textContent    = min;
    document.getElementById('prevMin2').textContent   = min;
    document.getElementById('prevCharge').textContent = charge;
}
document.querySelector('[name=free_delivery_min]').addEventListener('input', updatePreview);
document.querySelector('[name=delivery_charge]').addEventListener('input', updatePreview);
</script>
@endpush
