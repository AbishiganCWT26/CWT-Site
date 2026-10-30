/* Local square cropping; the resulting file follows the existing upload flow. */
window.createCrewPhotoCrop = function ({ input, preview, removeFlag, placeholder }) {
    const dialog = document.getElementById('crewPhotoCropDialog');
    const canvas = document.getElementById('crewCropCanvas');
    if (!dialog || !canvas) {
        return {
            refresh() {},
            reset() {}
        };
    }
    const context = canvas.getContext('2d');
    const zoom = document.getElementById('crewCropZoom');
    const zoomValue = document.getElementById('crewCropZoomValue');
    const apply = document.getElementById('crewCropApply');
    const original = document.getElementById('crewCropOriginal');
    const cropButton = document.getElementById('crewPhotoCropBtn');
    const status = document.getElementById('crewPhotoStatus');
    const cropStatus = document.getElementById('crewCropStatus');
    const size = canvas.width;
    let image = null, sourceFile = null, sourceUrl = null, previewUrl = null, committedFile = null, recropFile = null;
    let scale = 1, baseScale = 1, x = 0, y = 0, drag = null, version = 0;

    function setFile(file) {
        if (!input) return;
        const transfer = new DataTransfer();
        if (file) transfer.items.add(file);
        input.files = transfer.files;
    }

    function refresh() {
        if (!preview) return;
        const hasPhoto = !!preview.getAttribute('src') && preview.getAttribute('src') !== placeholder;
        if (cropButton) cropButton.hidden = !hasPhoto;
        const removeBtn = document.getElementById('crewPhotoRemoveBtn') || document.getElementById('crewRemovePhotoBtn');
        if (removeBtn) removeBtn.hidden = !hasPhoto;
        const uploadBtn = document.getElementById('crewPhotoUploadBtn');
        if (uploadBtn && uploadBtn.lastChild) {
            uploadBtn.lastChild.textContent = hasPhoto ? 'Replace photo' : 'Upload photo';
        }
    }

    function releaseSource() {
        if (sourceUrl && sourceUrl.startsWith('blob:')) URL.revokeObjectURL(sourceUrl);
        sourceUrl = null; image = null; sourceFile = null; drag = null;
    }

    function close() {
        version++;
        if (dialog && typeof dialog.close === 'function') dialog.close();
        releaseSource();
        if (apply) apply.disabled = false;
    }

    function cancel() {
        setFile(committedFile);
        close();
    }

    function draw() {
        if (!image || !context) return;
        const iw = image.naturalWidth * scale, ih = image.naturalHeight * scale;
        x = iw >= size ? Math.max(size - iw, Math.min(0, x)) : Math.max(0, Math.min(size - iw, x));
        y = ih >= size ? Math.max(size - ih, Math.min(0, y)) : Math.max(0, Math.min(size - ih, y));
        context.fillStyle = '#fff';
        context.fillRect(0, 0, size, size);
        context.drawImage(image, x, y, image.naturalWidth * scale, image.naturalHeight * scale);
        context.save();
        context.strokeStyle = '#ffffffaa';
        context.lineWidth = 1;
        for (let i = 1; i < 3; i++) {
            context.beginPath();
            context.moveTo(size * i / 3, 0);
            context.lineTo(size * i / 3, size);
            context.moveTo(0, size * i / 3);
            context.lineTo(size, size * i / 3);
            context.stroke();
        }
        context.restore();
    }

    function resetPosition() {
        if (!image) return;
        scale = baseScale;
        if (zoom) zoom.value = '1';
        if (zoomValue) zoomValue.value = '100%';
        x = (size - image.naturalWidth * scale) / 2;
        y = (size - image.naturalHeight * scale) / 2;
        draw();
    }

    function open(file) {
        releaseSource();
        const currentVersion = ++version;
        sourceFile = file;
        if (cropStatus) cropStatus.textContent = 'Loading photo…';
        if (apply) apply.disabled = true;
        if (original) {
            original.hidden = !file;
            original.disabled = !!file && file.size > 5 * 1024 * 1024;
        }
        if (context) context.clearRect(0, 0, size, size);
        if (dialog && typeof dialog.showModal === 'function') dialog.showModal();
        const nextImage = new Image();
        nextImage.onload = () => {
            if (version !== currentVersion) return;
            if (!nextImage.naturalWidth || !nextImage.naturalHeight) {
                if (cropStatus) cropStatus.textContent = 'This image could not be opened. Try a JPG, PNG, or WebP photo.';
                if (file && original) original.disabled = false;
                return;
            }
            image = nextImage;
            baseScale = Math.max(size / image.naturalWidth, size / image.naturalHeight);
            resetPosition();
            if (apply) apply.disabled = false;
            if (cropStatus) cropStatus.textContent = (original && original.disabled) ? 'Crop this photo to reduce its size before uploading.' : '';
            if (canvas && typeof canvas.focus === 'function') canvas.focus();
        };
        nextImage.onerror = () => {
            if (version !== currentVersion) return;
            if (cropStatus) cropStatus.textContent = 'This image could not be displayed in the crop tool. You can still use it as-is.';
            if (file && original) original.disabled = false;
        };
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                if (version !== currentVersion) return;
                sourceUrl = e.target.result;
                nextImage.src = sourceUrl;
            };
            reader.onerror = () => {
                if (version !== currentVersion) return;
                if (cropStatus) cropStatus.textContent = 'Could not read the selected file. Please try again.';
                if (file && original) original.disabled = false;
            };
            reader.readAsDataURL(file);
        } else if (preview) {
            nextImage.src = preview.src;
            sourceUrl = preview.src;
        }
    }

    function commit(file, cropped) {
        recropFile = sourceFile || file;
        committedFile = file;
        setFile(file);

        if (previewUrl && previewUrl.startsWith('blob:')) {
            URL.revokeObjectURL(previewUrl);
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            previewUrl = e.target.result;
            if (preview) preview.setAttribute('src', previewUrl);
            refresh();
        };
        reader.readAsDataURL(file);

        if (removeFlag) removeFlag.value = '0';
        if (status) status.textContent = cropped ? 'Photo cropped. Save Member to upload it.' : 'Photo ready. Save Member to upload it.';
        close();
    }

    const uploadBtn = document.getElementById('crewPhotoUploadBtn');
    if (uploadBtn && input) {
        uploadBtn.addEventListener('click', () => input.click());
    }

    if (input) {
        input.addEventListener('change', () => {
            if (input.files && input.files[0]) open(input.files[0]);
        });
    }

    if (cropButton) {
        cropButton.addEventListener('click', () => open(recropFile));
    }

    document.querySelectorAll('[data-cancel-crop]').forEach(button => button.addEventListener('click', cancel));
    if (dialog) {
        dialog.addEventListener('cancel', event => {
            event.preventDefault();
            cancel();
        });
    }

    const resetBtn = document.getElementById('crewCropReset');
    if (resetBtn) resetBtn.addEventListener('click', resetPosition);

    if (original) {
        original.addEventListener('click', () => {
            if (sourceFile && !original.disabled) commit(sourceFile, false);
        });
    }

    if (zoom) {
        zoom.addEventListener('input', () => {
            if (!image) return;
            const nextScale = baseScale * Number(zoom.value);
            x = size / 2 - (size / 2 - x) * nextScale / scale;
            y = size / 2 - (size / 2 - y) * nextScale / scale;
            scale = nextScale;
            if (zoomValue) zoomValue.value = Math.round(Number(zoom.value) * 100) + '%';
            draw();
        });
    }

    if (canvas) {
        canvas.addEventListener('pointerdown', event => {
            if (!image) return;
            drag = { id: event.pointerId, x: event.clientX, y: event.clientY };
            canvas.setPointerCapture(event.pointerId);
            canvas.focus();
        });

        canvas.addEventListener('pointermove', event => {
            if (!drag || drag.id !== event.pointerId) return;
            const ratio = size / canvas.getBoundingClientRect().width;
            x += (event.clientX - drag.x) * ratio;
            y += (event.clientY - drag.y) * ratio;
            drag.x = event.clientX;
            drag.y = event.clientY;
            draw();
        });

        ['pointerup', 'pointercancel', 'lostpointercapture'].forEach(type => canvas.addEventListener(type, () => { drag = null; }));

        canvas.addEventListener('keydown', event => {
            if (!image || !['ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'].includes(event.key)) return;
            event.preventDefault();
            const step = event.shiftKey ? 40 : 10;
            x += event.key === 'ArrowLeft' ? -step : event.key === 'ArrowRight' ? step : 0;
            y += event.key === 'ArrowUp' ? -step : event.key === 'ArrowDown' ? step : 0;
            draw();
        });
    }

    if (apply) {
        apply.addEventListener('click', () => {
            if (!image || apply.disabled) return;
            apply.disabled = true;
            const currentVersion = version;
            try {
                const output = document.createElement('canvas');
                output.width = output.height = 800;
                const ctx = output.getContext('2d');
                ctx.fillStyle = '#fff';
                ctx.fillRect(0, 0, 800, 800);
                ctx.drawImage(image, -x / scale, -y / scale, size / scale, size / scale, 0, 0, 800, 800);
                output.toBlob(blob => {
                    if (version !== currentVersion) return;
                    if (!blob) {
                        if (cropStatus) cropStatus.textContent = 'Could not crop this photo. Please try another image.';
                        apply.disabled = false;
                        return;
                    }
                    const name = (sourceFile?.name || 'profile-photo').replace(/\.[^.]+$/, '') + '-cropped.jpg';
                    commit(new File([blob], name, { type: 'image/jpeg' }), true);
                }, 'image/jpeg', .92);
            } catch (_) {
                if (cropStatus) cropStatus.textContent = 'Could not crop this photo. Upload a new image and try again.';
                apply.disabled = false;
            }
        });
    }

    const removeBtn = document.getElementById('crewPhotoRemoveBtn') || document.getElementById('crewRemovePhotoBtn');
    if (removeBtn) {
        removeBtn.addEventListener('click', () => {
            committedFile = null;
            recropFile = null;
            setFile(null);
            if (previewUrl && previewUrl.startsWith('blob:')) URL.revokeObjectURL(previewUrl);
            previewUrl = null;
            if (preview) preview.setAttribute('src', placeholder);
            if (removeFlag) removeFlag.value = '1';
            if (status) status.textContent = 'Photo removed. Save Member to confirm.';
            refresh();
        });
    }

    return {
        refresh,
        reset() {
            if (dialog && dialog.open) close();
            committedFile = null;
            recropFile = null;
            if (previewUrl && previewUrl.startsWith('blob:')) URL.revokeObjectURL(previewUrl);
            previewUrl = null;
            if (status) status.textContent = '';
            refresh();
        }
    };
};
