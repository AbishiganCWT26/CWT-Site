document.addEventListener('DOMContentLoaded', function () {

    /* ======================================================================
       Quill editor
       ====================================================================== */
    var BaseImage = Quill.import('formats/image');
    class ProjectImage extends BaseImage {
        static formats(node) {
            var formats = super.formats(node);
            var align = node.getAttribute('data-image-align');
            if (['left', 'center', 'right'].includes(align)) formats.imageAlign = align;
            return formats;
        }
        format(name, value) {
            if (name !== 'imageAlign') { super.format(name, value); return; }
            if (['left', 'center', 'right'].includes(value)) this.domNode.setAttribute('data-image-align', value);
            else this.domNode.removeAttribute('data-image-align');
        }
    }
    Quill.register('formats/image', ProjectImage, true);
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

    var imageTools = document.getElementById('projectImageTools');
    var imageWidth = document.getElementById('projectImageWidth');
    var selectedImage = null;
    var toolsFrame = 0;
    document.body.appendChild(imageTools);

    function positionImageTools() {
        if (!selectedImage || !quill.root.contains(selectedImage)) return;
        var imageRect = selectedImage.getBoundingClientRect();
        var editorRect = quill.root.getBoundingClientRect();
        var visibleTop = Math.max(imageRect.top, editorRect.top, 76);
        var visibleBottom = Math.min(imageRect.bottom, editorRect.bottom, window.innerHeight);
        if (visibleBottom <= visibleTop) { imageTools.hidden = true; return; }
        imageTools.hidden = false;
        var box = imageTools.getBoundingClientRect();
        var left = Math.max(12, Math.min(imageRect.left, window.innerWidth - box.width - 12));
        var top = visibleBottom + 10;
        if (top + box.height > window.innerHeight - 12) top = visibleTop - box.height - 10;
        // Tall images may fill the viewport; keep controls alongside the visible portion.
        top = Math.max(76, Math.min(top, window.innerHeight - box.height - 12));
        imageTools.style.left = left + 'px';
        imageTools.style.top = top + 'px';
    }
    function scheduleImageTools() {
        cancelAnimationFrame(toolsFrame);
        toolsFrame = requestAnimationFrame(positionImageTools);
    }
    function updateImageAlignment() {
        var align = selectedImage ? selectedImage.getAttribute('data-image-align') || 'left' : 'left';
        imageTools.querySelectorAll('[data-image-align]').forEach(function (button) {
            button.setAttribute('aria-pressed', String(button.dataset.imageAlign === align));
        });
    }

    function clearImageSelection() {
        if (selectedImage) selectedImage.classList.remove('pe-image-selected');
        selectedImage = null;
        imageTools.hidden = true;
        cancelAnimationFrame(toolsFrame);
    }
    function selectImage(image) {
        if (selectedImage && selectedImage !== image) selectedImage.classList.remove('pe-image-selected');
        selectedImage = image;
        image.classList.add('pe-image-selected');
        imageTools.hidden = false;
        imageWidth.value = Math.round(image.getBoundingClientRect().width);
        updateImageAlignment();
        positionImageTools();
    }
    function resizeImage(width) {
        if (!selectedImage || !quill.root.contains(selectedImage)) { clearImageSelection(); return; }
        var blot = Quill.find(selectedImage);
        if (!blot) return;
        quill.formatText(quill.getIndex(blot), 1, { width: width || false, height: false }, 'user');
        imageWidth.value = Math.round(selectedImage.getBoundingClientRect().width);
        scheduleImageTools();
    }
    quill.root.addEventListener('click', function (event) {
        if (event.target.tagName === 'IMG') {
            var blot = Quill.find(event.target);
            if (blot) quill.setSelection(quill.getIndex(blot), 1, 'silent');
            selectImage(event.target);
        }
        else clearImageSelection();
    });
    quill.on('selection-change', function (range) {
        if (!range) return; // Keep controls available when their input receives focus.
        var leaf = quill.getLeaf(range.index)[0];
        if (range.length === 1 && leaf && leaf.domNode.tagName === 'IMG') selectImage(leaf.domNode);
        else clearImageSelection();
    });
    quill.on('text-change', function () {
        if (selectedImage && !quill.root.contains(selectedImage)) clearImageSelection();
        else if (selectedImage) {
            imageWidth.value = Math.round(selectedImage.getBoundingClientRect().width);
            updateImageAlignment();
            scheduleImageTools();
        }
    });
    function applyImageWidth() {
        if (!imageWidth.reportValidity()) return;
        var value = Number(imageWidth.value);
        if (Number.isInteger(value) && value >= 40 && value <= 2400) resizeImage(value);
    }
    document.getElementById('projectImageApply').addEventListener('click', applyImageWidth);
    imageWidth.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') { event.preventDefault(); applyImageWidth(); }
    });
    imageTools.querySelectorAll('[data-image-width]').forEach(function (button) {
        button.addEventListener('click', function () {
            var style = getComputedStyle(quill.root);
            var available = quill.root.clientWidth - parseFloat(style.paddingLeft) - parseFloat(style.paddingRight);
            resizeImage(Math.max(40, Math.min(2400, Math.round(available * Number(button.dataset.imageWidth) / 100))));
        });
    });
    document.getElementById('projectImageReset').addEventListener('click', function () { resizeImage(false); });
    imageTools.querySelectorAll('[data-image-align]').forEach(function (button) {
        button.addEventListener('click', function () {
            if (!selectedImage || !quill.root.contains(selectedImage)) return;
            var blot = Quill.find(selectedImage);
            if (!blot) return;
            quill.formatText(quill.getIndex(blot), 1, 'imageAlign', button.dataset.imageAlign, 'user');
            updateImageAlignment();
            scheduleImageTools();
        });
    });
    document.getElementById('projectImageToolsClose').addEventListener('click', clearImageSelection);
    document.addEventListener('pointerdown', function (event) {
        if (selectedImage && !quill.root.contains(event.target) && !imageTools.contains(event.target)) clearImageSelection();
    });
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && selectedImage) { clearImageSelection(); quill.focus(); }
    });
    document.addEventListener('scroll', scheduleImageTools, true);
    window.addEventListener('resize', scheduleImageTools);
    quill.root.addEventListener('load', scheduleImageTools, true);

    form.addEventListener('submit', function (e) {
        clearImageSelection();
        // Always sync editor content to hidden input
        hiddenContent.value = quill.root.innerHTML;

        // If a mockup delete was requested, skip content validation
        var deleteFlag = document.getElementById('deleteMockupId');
        if (deleteFlag && deleteFlag.value) {
            return true;
        }

        // Otherwise require content
        var plain = quill.getText().trim();
        if (plain === '' && !quill.root.querySelector('img')) {
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
