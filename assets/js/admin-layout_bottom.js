window.AdminToast = {
    success: function (message, title) {
        return Swal.fire({
            icon: 'success',
            title: title || 'Success',
            text: message,
            confirmButtonText: 'OK',
            confirmButtonColor: '#001be4',
            buttonsStyling: true,
            customClass: { popup: 'swal-premium' }
        });
    },
    error: function (message, title) {
        return Swal.fire({
            icon: 'error',
            title: title || 'Error',
            text: message,
            confirmButtonText: 'OK',
            confirmButtonColor: '#001be4'
        });
    },
    confirm: function (message, title) {
        return Swal.fire({
            icon: 'warning',
            title: title || 'Are you sure?',
            text: message,
            showCancelButton: true,
            confirmButtonText: 'Yes, proceed',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#001be4',
            cancelButtonColor: '#6b7280',
            reverseButtons: true
        });
    }
};

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-confirm]').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            if (btn.dataset.swalConfirmed === '1') return;
            e.preventDefault();
            e.stopPropagation();
            var msg = btn.dataset.confirm || 'Are you sure you want to delete this item?';
            Swal.fire({
                icon: 'warning',
                title: 'Are you sure?',
                text: msg,
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                reverseButtons: true
            }).then(function (result) {
                if (result.isConfirmed) {
                    btn.dataset.swalConfirmed = '1';
                    var form = btn.closest('form');
                    if (form) {
                        form.submit();
                    } else {
                        btn.click();
                    }
                }
            });
        });
    });

    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (form.dataset.swalConfirmed === '1') return;
            e.preventDefault();
            var msg = form.dataset.confirm || 'Are you sure?';
            Swal.fire({
                icon: 'warning',
                title: 'Are you sure?',
                text: msg,
                showCancelButton: true,
                confirmButtonText: 'Yes, proceed',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                reverseButtons: true
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.dataset.swalConfirmed = '1';
                    form.submit();
                }
            });
        });
    });
});