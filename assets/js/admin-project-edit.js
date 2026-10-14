document.addEventListener('DOMContentLoaded', function () {

    /* ======================================================================
       Quill editor
       ====================================================================== */
    var quill = new Quill('#quillEditor', {
        theme: 'snow',
        modules: {
            toolbar: '#quillToolbar',
            clipboard: {
                matchVisual: false
            }
        },
        placeholder: 'Start writing the project story — overview, challenge, approach, results...'
    });

    var form = document.getElementById('projectDetailsForm');
    var hiddenContent = document.getElementById('quillContent');

    form.addEventListener('submit', function (e) {
        // Always sync editor content to hidden input
        hiddenContent.value = quill.root.innerHTML;

        // If a mockup delete was requested, skip content validation
        var deleteFlag = document.getElementById('deleteMockupId');
        if (deleteFlag && deleteFlag.value) {
            return true;
        }

        // Otherwise require content
        var plain = quill.getText().trim();
        if (plain === '') {
            e.preventDefault();
            if (window.Swal) {
                Swal.fire(Object.assign({}, window.SwalDefaults, {
                    icon: 'warning',
                    title: 'Content required',
                    text: 'Please add some content before saving.'
                }));
            }
            return false;
        }
    });

    /* ======================================================================
       Banner upload / preview
       ====================================================================== */
    var bannerInput = document.getElementById('bannerInput');
    var bannerPreview = document.getElementById('bannerPreview');
    var bannerEmpty = document.getElementById('bannerEmpty');
    var removeBannerFlag = document.getElementById('removeBannerFlag');
    var removeBannerBtn = document.getElementById('removeBannerBtn');

    if (bannerInput) {
        bannerInput.addEventListener('change', function () {
            if (!bannerInput.files || !bannerInput.files[0]) return;
            var reader = new FileReader();
            reader.onload = function (ev) {
                bannerPreview.src = ev.target.result;
                bannerPreview.style.display = 'block';
                if (bannerEmpty) bannerEmpty.style.display = 'none';
                removeBannerFlag.value = '0';
            };
            reader.readAsDataURL(bannerInput.files[0]);
        });
    }

    if (removeBannerBtn) {
        removeBannerBtn.addEventListener('click', function () {
            bannerPreview.src = '';
            bannerPreview.style.display = 'none';
            if (bannerEmpty) bannerEmpty.style.display = 'flex';
            if (bannerInput) bannerInput.value = '';
            removeBannerFlag.value = '1';
        });
    }

    var bannerWrap = document.querySelector('.pe-banner-wrap');
    if (bannerWrap && bannerInput) {
        bannerWrap.addEventListener('click', function (e) {
            if (e.target.tagName === 'INPUT') return;
            bannerInput.click();
        });
    }

    /* ======================================================================
       Mockup dropzone
       ====================================================================== */
    var mockupDropzone = document.getElementById('mockupDropzone');
    var mockupInput = document.getElementById('mockupInput');
    var mockupGrid = document.getElementById('mockupGrid');
    var mockupHint = document.querySelector('.pe-mockup-hint');

    if (mockupDropzone && mockupInput) {
        // Click to open file picker
        mockupDropzone.addEventListener('click', function (e) {
            if (e.target.tagName === 'INPUT') return;
            mockupInput.click();
        });

        // Drag & drop
        ['dragenter', 'dragover'].forEach(function (evt) {
            mockupDropzone.addEventListener(evt, function (e) {
                e.preventDefault();
                e.stopPropagation();
                mockupDropzone.classList.add('is-dragover');
            });
        });

        ['dragleave', 'drop'].forEach(function (evt) {
            mockupDropzone.addEventListener(evt, function (e) {
                e.preventDefault();
                e.stopPropagation();
                mockupDropzone.classList.remove('is-dragover');
            });
        });

        mockupDropzone.addEventListener('drop', function (e) {
            var files = e.dataTransfer && e.dataTransfer.files;
            if (files && files.length) {
                // Assign dropped files to the hidden input so the form submits them
                var dt = new DataTransfer();
                for (var i = 0; i < files.length; i++) {
                    if (files[i].type.indexOf('image/') === 0) {
                        dt.items.add(files[i]);
                    }
                }
                mockupInput.files = dt.files;
                showPendingPreview(dt.files);
            }
        });

        // File picker change
        mockupInput.addEventListener('change', function () {
            showPendingPreview(mockupInput.files);
        });
    }

    function showPendingPreview(files) {
        if (!files || !files.length) return;

        // Remove any existing "pending" items
        document.querySelectorAll('.pe-mockup-item.is-pending').forEach(function (el) { el.remove(); });

        var grid = document.getElementById('mockupGrid');
        if (grid) grid.hidden = false;

        Array.prototype.forEach.call(files, function (file) {
            if (file.type.indexOf('image/') !== 0) return;
            var reader = new FileReader();
            reader.onload = function (ev) {
                var item = document.createElement('div');
                item.className = 'pe-mockup-item is-pending';
                item.style.opacity = '0.65';
                item.style.border = '2px dashed #3b82f6';
                item.title = 'Will be uploaded on save';
                item.innerHTML = '<img src="' + ev.target.result + '" alt="Pending upload">' +
                    '<span style="position:absolute;bottom:4px;left:4px;background:#3b82f6;color:#fff;font-size:9px;font-weight:700;padding:2px 6px;border-radius:99px;letter-spacing:.3px;">NEW</span>';
                grid.appendChild(item);
            };
            reader.readAsDataURL(file);
        });

        if (mockupHint) {
            mockupHint.textContent = files.length + ' new image(s) ready to upload. Click "Save Project Content" to persist.';
        }
    }

    /* ======================================================================
       Mockup delete (uses hidden delete_mockup_id + form submit)
       ====================================================================== */
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.pe-mockup-delete');
        if (!btn) return;
        e.preventDefault();

        var id = btn.getAttribute('data-id');
        if (!id) return;

        var doDelete = function () {
            var flag = document.getElementById('deleteMockupId');
            if (flag) flag.value = id;
            // Submit form (validation skipped via the delete flag)
            form.submit();
        };

        if (window.Swal) {
            Swal.fire(Object.assign({}, window.SwalDefaults, {
                icon: 'warning',
                title: 'Remove mockup?',
                text: 'This image will be permanently deleted.',
                showCancelButton: true,
                confirmButtonText: 'Yes, remove it',
                confirmButtonColor: '#ef4444',
                cancelButtonText: 'Cancel'
            })).then(function (result) {
                if (result.isConfirmed) doDelete();
            });
        } else if (window.confirm('Remove this mockup image?')) {
            doDelete();
        }
    });
});