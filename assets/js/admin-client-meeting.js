document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.cm-copy-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            var text = btn.getAttribute('data-copy') || '';
            var done = function () {
                var icon = btn.querySelector('i');
                var original = icon.className;
                icon.className = 'fa-solid fa-check';
                btn.classList.add('copied');
                if (window.Swal) {
                    Swal.fire(Object.assign({}, window.SwalDefaults, {
                        icon: 'success', title: 'Copied!', text: 'Email copied to clipboard.',
                        timer: 1500, showConfirmButton: false, position: 'center'
                    }));
                }
                setTimeout(function () {
                    icon.className = original;
                    btn.classList.remove('copied');
                }, 1500);
            };
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(done).catch(function () { fallbackCopy(text, done); });
            } else {
                fallbackCopy(text, done);
            }
        });
    });

    function fallbackCopy(text, cb) {
        var ta = document.createElement('textarea');
        ta.value = text;
        ta.style.position = 'fixed';
        ta.style.opacity = '0';
        document.body.appendChild(ta);
        ta.select();
        try { document.execCommand('copy'); cb(); } catch (e) { }
        document.body.removeChild(ta);
    }

    var markAllForm = document.getElementById('markAllForm');
    if (markAllForm) {
        markAllForm.addEventListener('submit', function (e) {
            e.preventDefault();
            window.cwtConfirm({ title: 'Mark all as read?', text: 'All unread meetings will be marked as read.', confirmText: 'Yes, mark all', icon: 'question' }).then(function (r) {
                if (r.isConfirmed) markAllForm.submit();
            });
        });
    }

    document.querySelectorAll('[data-delete-meeting]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = btn.getAttribute('data-id');
            var name = btn.getAttribute('data-name');
            Swal.fire(Object.assign({}, window.SwalDefaults, {
                title: 'Delete meeting?',
                html: '<p style="margin-bottom:12px;">To delete <strong>' + name + '</strong>\'s meeting request, enter the admin password.</p>' +
                    '<input type="password" id="swal-admin-pass" class="swal2-input" placeholder="Admin Password" autocomplete="off">',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                focusConfirm: false,
                didOpen: function () {
                    var input = document.getElementById('swal-admin-pass');
                    if (input) {
                        input.addEventListener('keydown', function (e) {
                            if (e.key === 'Enter') { e.preventDefault(); Swal.clickConfirm(); }
                        });
                        setTimeout(function () { input.focus(); }, 100);
                    }
                },
                preConfirm: function () {
                    var pass = document.getElementById('swal-admin-pass').value;
                    if (!pass) { Swal.showValidationMessage('Please enter the admin password.'); return false; }
                    return fetch('client-meeting.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'action=delete&id=' + encodeURIComponent(id) + '&admin_password=' + encodeURIComponent(pass)
                    }).then(function (r) { return r.json(); }).then(function (res) {
                        if (!res.success) { throw new Error(res.message || 'Incorrect password.'); }
                        return res;
                    }).catch(function (err) {
                        Swal.showValidationMessage(err.message || 'Verification failed.');
                        return false;
                    });
                }
            })).then(function (result) {
                if (result.isConfirmed) {
                    Swal.fire(Object.assign({}, window.SwalDefaults, {
                        icon: 'success', title: 'Deleted', text: 'Meeting request removed.', timer: 1200, showConfirmButton: false, position: 'center'
                    })).then(function () { location.reload(); });
                }
            });
        });
    });
});