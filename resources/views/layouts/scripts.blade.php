<!-- CSRF setup and common JavaScript utilities -->
<script>
    // CSRF setup for AJAX calls
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Format date helper
    function formatDate(dateString) {
        if (window.moment) {
            return dateString ? moment(dateString).format('DD/MM/YYYY') : 'N/A';
        }
        return dateString || 'N/A';
    }

    // Debounce function to prevent multiple rapid calls
    function debounce(func, wait = 300) {
        let timeout;
        return function (...args) {
            const context = this;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }

    // Toast notification setup
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });

    // Form validation helper
    function validateForm(formId) {
        let isValid = true;
        $(`#${formId} [required]`).each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        return isValid;
    }
    
    // URL mapping for permissions
    const permissionToRoute = {
        'xem_thanh_vien': '/trung-lao',
        'diem_danh': '/trung-lao',
        'to_chuc_su_kien': '/thanh-nien',
        // Add more mappings as needed
    };
    
    // Initialize common components when document is ready
    $(document).ready(function() {
        // Initialize tooltips if bootstrap is available
        if ($.fn.tooltip) {
            $('[data-toggle="tooltip"]').tooltip();
        }
        
        // Initialize Select2 if available
        if ($.fn.select2) {
            $('.select2').select2({
                theme: 'bootstrap4',
                placeholder: "Chọn...",
                allowClear: true
            });
        }
        
        // Initialize date pickers if available
        if ($.fn.datetimepicker) {
            $('.date-picker').datetimepicker({
                format: 'L',
                locale: 'vi'
            });
        }
        
        // Add required indicator to labels
        $('input[required], select[required], textarea[required]').each(function() {
            const id = $(this).attr('id');
            $(`label[for="${id}"]`).addClass('required-field');
        });
        
        // Handle auto-dismissing alerts
        setTimeout(function() {
            $('.alert-dismissible.fade.show').fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
            });
        }, 4000);
    });
    
    // Confirmation dialog helper
    function confirmDelete(url, title = 'Xác nhận xóa?', text = 'Bạn không thể hoàn tác hành động này!') {
        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
        return false;
    }
</script>