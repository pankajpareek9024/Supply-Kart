/**
 * SupplyKart Admin Panel — admin.js
 */

/* ── CSRF Setup ─────────────────────────────────────────────── */
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

/* ── Toastr Config ──────────────────────────────────────────── */
toastr.options = {
    positionClass    : 'toast-top-right',
    timeOut          : 3500,
    closeButton      : true,
    progressBar      : true,
    newestOnTop      : true,
    preventDuplicates: true,
};

/* ── Sidebar Toggle ─────────────────────────────────────────── */
$(function () {
    const sidebar  = $('#adminSidebar');
    const overlay  = $('#sidebarOverlay');
    const mainArea = $('#adminMain');

    $('#sidebarToggle').on('click', function () {
        const isCollapsed = sidebar.hasClass('open');
        if (window.innerWidth <= 992) {
            sidebar.toggleClass('open');
            overlay.toggleClass('show');
        } else {
            // Desktop: shrink sidebar
            if (sidebar.css('width') === '260px') {
                sidebar.css('width', '0');
                mainArea.css('margin-left', '0');
            } else {
                sidebar.css('width', '260px');
                mainArea.css('margin-left', '260px');
            }
        }
    });

    overlay.on('click', function () {
        sidebar.removeClass('open');
        overlay.removeClass('show');
    });

    // Live clock
    function updateClock() {
        const now = new Date();
        $('#topbarClock').text(now.toLocaleTimeString('en-IN', { hour: '2-digit', minute: '2-digit' }));
    }
    updateClock();
    setInterval(updateClock, 1000);
});

/* ── DataTables Default Init ────────────────────────────────── */
$(function () {
    if ($.fn.DataTable) {
        $('.datatable').DataTable({
            responsive    : true,
            pageLength    : 15,
            lengthMenu    : [10, 15, 25, 50, 100],
            language: {
                search      : '',
                searchPlaceholder: 'Search...',
                lengthMenu  : 'Show _MENU_ entries',
                info        : 'Showing _START_–_END_ of _TOTAL_ entries',
                emptyTable  : 'No data available',
            },
            dom: "<'row align-items-center'<'col-sm-6'l><'col-sm-6 text-end'f>>" +
                 "<'row'<'col-12'tr>>" +
                 "<'row align-items-center mt-2'<'col-sm-6'i><'col-sm-6'p>>",
        });
    }
});

/* ── Generic AJAX Helpers ───────────────────────────────────── */

/**
 * Toggle active/inactive status via AJAX.
 * Usage: <button class="toggle-status-btn" data-url="..." data-id="...">
 */
$(document).on('click', '.toggle-status-btn', function () {
    const btn = $(this);
    const url = btn.data('url');

    $.post(url, {}, function (res) {
        if (res.success) {
            toastr.success(res.message);
            const badge = btn.closest('tr').find('.status-badge');
            if (res.is_active) {
                badge.removeClass('inactive').addClass('active').text('Active');
                btn.html('<i class="bi bi-toggle-on text-success fs-5"></i>');
            } else {
                badge.removeClass('active').addClass('inactive').text('Inactive');
                btn.html('<i class="bi bi-toggle-off text-muted fs-5"></i>');
            }
        } else {
            toastr.error(res.message || 'Failed to update status.');
        }
    }).fail(function () {
        toastr.error('Network error. Please try again.');
    });
});

/**
 * Delete confirmation + AJAX delete.
 * Usage: <button class="delete-btn" data-url="..." data-name="Category Name">
 */
$(document).on('click', '.delete-btn', function () {
    const btn  = $(this);
    const url  = btn.data('url');
    const name = btn.data('name') || 'this item';

    if (!confirm(`Are you sure you want to delete "${name}"? This action cannot be undone.`)) return;

    $.ajax({
        url     : url,
        type    : 'DELETE',
        success : function (res) {
            if (res.success) {
                toastr.success(res.message);
                btn.closest('tr').fadeOut(400, function () { $(this).remove(); });
            } else {
                toastr.error(res.message || 'Could not delete.');
            }
        },
        error: function (xhr) {
            const msg = xhr.responseJSON?.message || 'Delete failed.';
            toastr.error(msg);
        }
    });
});

/* ── Category AJAX Modal ────────────────────────────────────── */
$(function () {
    const catModal = $('#categoryModal');
    if (!catModal.length) return;

    // Open Add modal
    $('#btnAddCategory').on('click', function () {
        $('#categoryForm')[0].reset();
        $('#categoryId').val('');
        $('#categoryPreview').hide();
        $('#catModalLabel').text('Add Category');
        catModal.modal('show');
    });

    // Open Edit modal
    $(document).on('click', '.edit-category-btn', function () {
        const url = $(this).data('url');
        $.get(url, function (res) {
            if (!res.success) return toastr.error('Failed to load category.');
            const c = res.category;
            $('#categoryId').val(c.id);
            $('#catName').val(c.name);
            $('#catStatus').prop('checked', c.is_active);
            if (c.image) {
                $('#categoryPreview').attr('src', '/storage/' + c.image).show();
            } else {
                $('#categoryPreview').hide();
            }
            $('#catModalLabel').text('Edit Category');
            catModal.modal('show');
        });
    });

    // Submit form
    $('#categoryForm').on('submit', function (e) {
        e.preventDefault();
        const id  = $('#categoryId').val();
        const url = id
            ? `/admin/categories/${id}`
            : `/admin/categories`;

        const fd = new FormData(this);

        $.ajax({
            url        : url,
            type       : 'POST',
            data       : fd,
            processData: false,
            contentType: false,
            success    : function (res) {
                if (res.success) {
                    toastr.success(res.message);
                    catModal.modal('hide');
                    setTimeout(() => location.reload(), 800);
                } else {
                    toastr.error('Something went wrong.');
                }
            },
            error: function (xhr) {
                showValidationErrors(xhr);
            }
        });
    });

    // Image preview
    $('#catImage').on('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => $('#categoryPreview').attr('src', e.target.result).show();
            reader.readAsDataURL(file);
        }
    });
});

/* ── Delivery Boy AJAX Modal ────────────────────────────────── */
$(function () {
    const dbModal = $('#deliveryBoyModal');
    if (!dbModal.length) return;

    $('#btnAddDeliveryBoy').on('click', function () {
        $('#deliveryBoyForm')[0].reset();
        $('#deliveryBoyId').val('');
        $('#dbModalLabel').text('Add Delivery Boy');
        dbModal.modal('show');
    });

    $(document).on('click', '.edit-db-btn', function () {
        const url = $(this).data('url');
        $.get(url, function (res) {
            if (!res.success) return toastr.error('Failed to load.');
            const b = res.boy;
            $('#deliveryBoyId').val(b.id);
            $('#dbName').val(b.name);
            $('#dbPhone').val(b.phone);
            $('#dbAddress').val(b.address);
            $('#dbStatus').prop('checked', b.is_active);
            $('#dbModalLabel').text('Edit Delivery Boy');
            dbModal.modal('show');
        });
    });

    $('#deliveryBoyForm').on('submit', function (e) {
        e.preventDefault();
        const id  = $('#deliveryBoyId').val();
        const url = id ? `/admin/delivery-boys/${id}` : `/admin/delivery-boys`;
        const fd  = new FormData(this);

        $.ajax({
            url: url, type: 'POST', data: fd, processData: false, contentType: false,
            success: function (res) {
                if (res.success) {
                    toastr.success(res.message);
                    dbModal.modal('hide');
                    setTimeout(() => location.reload(), 800);
                }
            },
            error: function (xhr) { showValidationErrors(xhr); }
        });
    });
});

/* ── Validation Error Helper ────────────────────────────────── */
function showValidationErrors(xhr) {
    if (xhr.status === 422) {
        const errors = xhr.responseJSON?.errors || {};
        const msgs   = Object.values(errors).flat();
        toastr.error(msgs.join('<br>'), 'Validation Error');
    } else {
        toastr.error('An unexpected error occurred.');
    }
}

/* ── Image Preview Generic ──────────────────────────────────── */
$(document).on('change', '.img-input', function () {
    const previewId = $(this).data('preview');
    const file      = this.files[0];
    if (file && previewId) {
        const reader = new FileReader();
        reader.onload = e => $(`#${previewId}`).attr('src', e.target.result).show();
        reader.readAsDataURL(file);
    }
});
