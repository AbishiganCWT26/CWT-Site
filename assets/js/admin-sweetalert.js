/**
 * admin-sweetalert.js
 * Centralized SweetAlert2 helper library & event delegator for CWT Admin.
 */

(function () {
    'use strict';

    // Helper to safely clean up the backdrop and container when any alert closes
    function cleanupSwalBackdrop() {
        document.body.classList.remove('swal2-shown');
        document.body.classList.remove('swal2-height-auto');
        document.body.style.overflow = '';
        var containers = document.querySelectorAll('.swal2-container');
        containers.forEach(function (c) {
            c.style.display = 'none';
            c.classList.remove('swal2-backdrop-show');
            c.classList.add('swal2-backdrop-hide');
        });
    }

    // Standard SweetAlert2 default configuration
    window.SwalDefaults = {
        customClass: {
            popup: 'cwt-swal'
        },
        buttonsStyling: true,
        showDenyButton: false,   // Never show deny button unless explicitly requested
        showCancelButton: false, // Default false for simple alerts
        confirmButtonColor: '#001be4',
        cancelButtonColor: '#94a3b8',
        reverseButtons: true,
        allowOutsideClick: true,
        allowEscapeKey: true,
        didOpen: function (popup) {
            if (popup) popup.style.margin = 'auto';
        },
        didClose: function () {
            cleanupSwalBackdrop();
        }
    };

    /**
     * Show Success Alert (Single OK button or auto-close with timer)
     */
    window.showSuccessAlert = function (title, text, timer) {
        if (typeof timer === 'undefined') timer = 2800;
        if (!window.Swal) {
            alert((title ? title + '\n' : '') + (text || ''));
            return Promise.resolve();
        }
        return Swal.fire(Object.assign({}, window.SwalDefaults, {
            icon: 'success',
            title: title || 'Success',
            text: text || '',
            showConfirmButton: true,
            confirmButtonText: 'OK',
            confirmButtonColor: '#001be4',
            showCancelButton: false,
            showDenyButton: false,
            showCloseButton: true,
            timer: timer,
            timerProgressBar: !!timer
        })).then(function (res) {
            cleanupSwalBackdrop();
            return res;
        });
    };

    /**
     * Show Error Alert (Single OK button)
     */
    window.showErrorAlert = function (title, text) {
        if (!window.Swal) {
            alert((title ? title + '\n' : '') + (text || 'An unexpected error occurred.'));
            return Promise.resolve();
        }
        return Swal.fire(Object.assign({}, window.SwalDefaults, {
            icon: 'error',
            title: title || 'Error Occurred',
            text: text || 'An unexpected error occurred. Please try again.',
            showConfirmButton: true,
            confirmButtonText: 'OK',
            confirmButtonColor: '#dc2626',
            showCancelButton: false,
            showDenyButton: false,
            showCloseButton: true
        })).then(function (res) {
            cleanupSwalBackdrop();
            return res;
        });
    };

    /**
     * Show Warning Alert (Single OK button)
     */
    window.showWarningAlert = function (title, text) {
        if (!window.Swal) {
            alert((title ? title + '\n' : '') + (text || ''));
            return Promise.resolve();
        }
        return Swal.fire(Object.assign({}, window.SwalDefaults, {
            icon: 'warning',
            title: title || 'Warning',
            text: text || '',
            showConfirmButton: true,
            confirmButtonText: 'OK',
            confirmButtonColor: '#d97706',
            showCancelButton: false,
            showDenyButton: false,
            showCloseButton: true
        })).then(function (res) {
            cleanupSwalBackdrop();
            return res;
        });
    };

    /**
     * Show Info Alert (Single OK button)
     */
    window.showInfoAlert = function (title, text) {
        if (!window.Swal) {
            alert((title ? title + '\n' : '') + (text || ''));
            return Promise.resolve();
        }
        return Swal.fire(Object.assign({}, window.SwalDefaults, {
            icon: 'info',
            title: title || 'Notice',
            text: text || '',
            showConfirmButton: true,
            confirmButtonText: 'OK',
            confirmButtonColor: '#001be4',
            showCancelButton: false,
            showDenyButton: false,
            showCloseButton: true
        })).then(function (res) {
            cleanupSwalBackdrop();
            return res;
        });
    };

    /**
     * Show Loading Alert
     */
    window.showLoadingAlert = function (title, text) {
        if (!window.Swal) return;
        Swal.fire(Object.assign({}, window.SwalDefaults, {
            title: title || 'Processing...',
            text: text || 'Please wait while we complete the request.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            showCancelButton: false,
            showDenyButton: false,
            didOpen: function () {
                Swal.showLoading();
            }
        }));
    };

    /**
     * Backward-compatible cwtAlert
     */
    window.cwtAlert = function (title, text, icon) {
        icon = icon || 'info';
        if (icon === 'success') return window.showSuccessAlert(title, text);
        if (icon === 'error') return window.showErrorAlert(title, text);
        if (icon === 'warning') return window.showWarningAlert(title, text);
        return window.showInfoAlert(title, text);
    };

    function parseConfirmArgs(arg1, arg2, defaults) {
        var opts = Object.assign({}, defaults || {});
        var callback = null;

        if (typeof arg1 === 'function') {
            callback = arg1;
        } else if (typeof arg1 === 'string') {
            opts.text = arg1;
            if (typeof arg2 === 'function') {
                callback = arg2;
            } else if (typeof arg2 === 'string') {
                opts.title = arg2;
            }
        } else if (typeof arg1 === 'object' && arg1 !== null) {
            Object.assign(opts, arg1);
            if (typeof arg2 === 'function') {
                callback = arg2;
            }
        }

        return { opts: opts, callback: callback };
    }

    /**
     * General Confirmation Dialog (Two buttons: Confirm & Cancel)
     */
    window.confirmAction = function (arg1, arg2) {
        var parsed = parseConfirmArgs(arg1, arg2, {
            title: 'Are you sure?',
            text: 'This action cannot be undone.',
            confirmText: 'Confirm',
            cancelText: 'Cancel',
            icon: 'warning'
        });
        var opts = parsed.opts;
        var callback = parsed.callback;

        if (!window.Swal) {
            var confirmed = confirm((opts.title ? opts.title + '\n' : '') + (opts.text || ''));
            var mockRes = { isConfirmed: confirmed, isDismissed: !confirmed };
            if (confirmed && typeof callback === 'function') callback(mockRes);
            return Promise.resolve(mockRes);
        }

        var customClass = { popup: 'cwt-swal' };
        if (opts.danger) customClass.popup += ' cwt-danger-dialog';
        else if (opts.warning) customClass.popup += ' cwt-warning-dialog';

        var promise = Swal.fire(Object.assign({}, window.SwalDefaults, {
            customClass: customClass,
            title: opts.title || 'Are you sure?',
            text: opts.text || '',
            html: opts.html || undefined,
            icon: opts.icon || 'warning',
            showConfirmButton: true,
            confirmButtonText: opts.confirmText || 'Confirm',
            confirmButtonColor: opts.confirmColor || (opts.danger ? '#dc2626' : (opts.warning ? '#d97706' : '#001be4')),
            showCancelButton: true,
            cancelButtonText: opts.cancelText || 'Cancel',
            cancelButtonColor: '#ffffff',
            showDenyButton: false, // Absolutely NO third button
            showCloseButton: true,
            focusCancel: true
        })).then(function (result) {
            cleanupSwalBackdrop();
            if (result.isConfirmed && typeof callback === 'function') {
                callback(result);
            }
            return result;
        });

        return promise;
    };

    /**
     * Backward-compatible cwtConfirm
     */
    window.cwtConfirm = function (arg1, arg2) {
        return window.confirmAction(arg1, arg2);
    };

    /**
     * Specific Confirmation: Delete (Cancel | Yes, Delete)
     */
    window.confirmDelete = function (arg1, arg2) {
        var parsed = parseConfirmArgs(arg1, arg2, {
            title: 'Are you sure?',
            text: 'This will permanently delete the record and its images.',
            confirmText: 'Yes, Delete',
            cancelText: 'Cancel',
            icon: 'warning',
            danger: true
        });
        parsed.opts.danger = true;
        return window.confirmAction(parsed.opts, parsed.callback);
    };

    /**
     * Specific Confirmation: Suspend / Deactivate (Cancel | Yes, Suspend)
     */
    window.confirmSuspend = function (arg1, arg2) {
        var parsed = parseConfirmArgs(arg1, arg2, {
            title: 'Suspend this item?',
            text: 'Are you sure you want to deactivate or suspend this item?',
            confirmText: 'Yes, Suspend',
            cancelText: 'Cancel',
            icon: 'warning',
            warning: true
        });
        parsed.opts.warning = true;
        return window.confirmAction(parsed.opts, parsed.callback);
    };

    /**
     * Specific Confirmation: Image Removal (Cancel | Yes, Remove)
     */
    window.confirmImageRemoval = function (arg1, arg2) {
        var parsed = parseConfirmArgs(arg1, arg2, {
            title: 'Remove this image?',
            text: 'The existing image file will be permanently removed.',
            confirmText: 'Yes, Remove',
            cancelText: 'Cancel',
            icon: 'warning',
            danger: true
        });
        parsed.opts.danger = true;
        return window.confirmAction(parsed.opts, parsed.callback);
    };

    /**
     * Trigger Flash Alert from session data
     */
    window.triggerFlashAlert = function (flash) {
        if (!flash) return;
        var message = flash.message || flash.text || '';
        if (!message) return;
        var type = flash.type || 'info';
        var title = flash.title || '';

        var flashKey = type + ':' + title + ':' + message;
        if (window._lastTriggeredFlash === flashKey) return;
        window._lastTriggeredFlash = flashKey;

        if (type === 'success') {
            window.showSuccessAlert(title || 'Success', message);
        } else if (type === 'error' || type === 'danger') {
            window.showErrorAlert(title || 'Error', message);
        } else if (type === 'warning') {
            window.showWarningAlert(title || 'Warning', message);
        } else {
            window.showInfoAlert(title || 'Notice', message);
        }
    };

    function initSweetAlert() {
        // 1. Check for Flash messages passed via window.CWT_FLASH or #cwt-flash-data
        if (window.CWT_FLASH) {
            window.triggerFlashAlert(window.CWT_FLASH);
        } else {
            var el = document.getElementById('cwt-flash-data');
            if (el) {
                try {
                    var parsed = JSON.parse(el.textContent || '{}');
                    window.triggerFlashAlert(parsed);
                } catch (e) { }
            }
        }

        // 2. Normalize and fire legacy URL query param messages (e.g. ?msg=updated, ?saved=1)
        var urlParams = new URLSearchParams(window.location.search);
        var msgParam = urlParams.get('msg');
        var savedParam = urlParams.get('saved');

        if (!window.CWT_FLASH && (msgParam || savedParam)) {
            var msg = msgParam || '';
            var title = 'Success';
            var text = '';
            var type = 'success';

            if (savedParam === '1') {
                text = 'Changes saved successfully.';
            } else if (msg === 'added') {
                text = 'Record added successfully.';
            } else if (msg === 'updated' || msg === 'success') {
                text = 'Record updated successfully.';
            } else if (msg === 'deleted') {
                text = 'Record deleted successfully.';
            } else if (msg === 'error_required') {
                title = 'Required Fields Missing';
                text = 'Please fill in all required fields and try again.';
                type = 'warning';
            } else if (msg === 'error_upload') {
                title = 'Upload Failed';
                text = 'Could not upload file. Ensure it is a valid JPG, PNG, GIF, WebP or SVG under 5MB.';
                type = 'error';
            } else if (msg === 'error_too_large') {
                title = 'File Too Large';
                text = 'The uploaded file exceeds the maximum allowed size (5MB).';
                type = 'error';
            } else if (msg === 'error_db') {
                title = 'Database Error';
                text = 'Could not save changes to database. Please try again.';
                type = 'error';
            } else if (msg.length > 0) {
                text = msg.replace(/_/g, ' ');
                text = text.charAt(0).toUpperCase() + text.slice(1) + '.';
            }

            if (text) {
                window.triggerFlashAlert({ type: type, title: title, message: text });
                if (window.history && window.history.replaceState) {
                    urlParams.delete('msg');
                    urlParams.delete('type');
                    urlParams.delete('saved');
                    var newSearch = urlParams.toString();
                    var newUrl = window.location.pathname + (newSearch ? '?' + newSearch : '') + window.location.hash;
                    window.history.replaceState(null, '', newUrl);
                }
            }
        }

        // 3. Delegate [data-confirm] clicks to SweetAlert2
        document.body.addEventListener('click', function (e) {
            var target = e.target.closest('[data-confirm], [data-confirm-delete], [data-confirm-suspend], [data-confirm-remove]');
            if (!target) return;

            if (target.dataset.swalConfirmed === 'true') {
                delete target.dataset.swalConfirmed;
                return;
            }

            e.preventDefault();
            e.stopPropagation();

            var confirmMsg = target.getAttribute('data-confirm') || target.getAttribute('data-confirm-delete') || '';
            var isDelete = target.hasAttribute('data-confirm-delete') ||
                (target.classList.contains('btn-danger') || target.classList.contains('crew-delete') || /delete/i.test(confirmMsg));
            var isSuspend = target.hasAttribute('data-confirm-suspend') || /deactivate|suspend|inactive/i.test(confirmMsg);
            var isRemove = target.hasAttribute('data-confirm-remove') || /remove/i.test(confirmMsg);

            var promise;
            if (isSuspend) {
                promise = window.confirmSuspend({
                    text: confirmMsg || 'Are you sure you want to suspend this item?'
                });
            } else if (isRemove) {
                promise = window.confirmImageRemoval({
                    text: confirmMsg || 'Are you sure you want to remove this image?'
                });
            } else {
                promise = window.confirmDelete({
                    text: confirmMsg || 'This will permanently delete the record and its images.'
                });
            }

            promise.then(function (result) {
                if (result.isConfirmed) {
                    target.dataset.swalConfirmed = 'true';
                    var form = target.closest('form');
                    if (target.tagName === 'A' && target.href) {
                        window.location.href = target.href;
                    } else if (form) {
                        if (target.name) {
                            var hidden = document.createElement('input');
                            hidden.type = 'hidden';
                            hidden.name = target.name;
                            hidden.value = target.value;
                            form.appendChild(hidden);
                        }
                        form.submit();
                    } else {
                        target.click();
                    }
                }
            });
        }, true);

        // 4. Intercept status toggle deactivations (toggle forms where is_active is set to 0)
        document.body.addEventListener('submit', function (e) {
            var form = e.target;
            var actionInput = form.querySelector('input[name="action"][value="toggle"]');
            var activeInput = form.querySelector('input[name="is_active"]');

            if (actionInput && activeInput && activeInput.value === '0') {
                if (form.dataset.swalConfirmed === 'true') {
                    delete form.dataset.swalConfirmed;
                    return;
                }
                e.preventDefault();
                window.confirmSuspend({
                    title: 'Deactivate this item?',
                    text: 'This item will be hidden from the public website.'
                }, function () {
                    form.dataset.swalConfirmed = 'true';
                    form.submit();
                });
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSweetAlert);
    } else {
        initSweetAlert();
    }

})();
