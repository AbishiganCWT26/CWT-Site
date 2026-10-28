/* Local square cropping; the resulting file follows the existing upload flow. */
window.createCrewPhotoCrop = function ({ input, preview, removeFlag, placeholder }) {
    const dialog = document.getElementById('crewPhotoCropDialog');
    const canvas = document.getElementById('crewCropCanvas'), context = canvas.getContext('2d');
    const zoom = document.getElementById('crewCropZoom'), zoomValue = document.getElementById('crewCropZoomValue');
    const apply = document.getElementById('crewCropApply'), original = document.getElementById('crewCropOriginal');
    const cropButton = document.getElementById('crewPhotoCropBtn');
    const status = document.getElementById('crewPhotoStatus'), cropStatus = document.getElementById('crewCropStatus');
    const size = canvas.width;
    let image = null, sourceFile = null, sourceUrl = null, previewUrl = null, committedFile = null, recropFile = null;
    let scale = 1, baseScale = 1, x = 0, y = 0, drag = null, version = 0;
    function setFile(file) {
        const transfer = new DataTransfer();
        if (file) transfer.items.add(file);
        input.files = transfer.files;
    }
    function refresh() {
        const hasPhoto = !!preview.getAttribute('src') && preview.getAttribute('src') !== placeholder;
        cropButton.hidden = !hasPhoto;
        document.getElementById('crewPhotoRemoveBtn').hidden = !hasPhoto;
        document.getElementById('crewPhotoUploadBtn').lastChild.textContent = hasPhoto ? 'Replace photo' : 'Upload photo';
    }
    function releaseSource() {
        if (sourceUrl) URL.revokeObjectURL(sourceUrl);
        sourceUrl = null; image = null; sourceFile = null; drag = null;
    }
    function close() { version++; dialog.close(); releaseSource(); apply.disabled = false; }
    function cancel() { setFile(committedFile); close(); }
    function draw() {
        if (!image) return;
        x = Math.max(size - image.naturalWidth * scale, Math.min(0, x));
        y = Math.max(size - image.naturalHeight * scale, Math.min(0, y));
        context.fillStyle = '#fff'; context.fillRect(0, 0, size, size);
        context.drawImage(image, x, y, image.naturalWidth * scale, image.naturalHeight * scale);
        context.save(); context.strokeStyle = '#ffffffaa'; context.lineWidth = 1;
        for (let i = 1; i < 3; i++) {
            context.beginPath();
            context.moveTo(size * i / 3, 0); context.lineTo(size * i / 3, size);
            context.moveTo(0, size * i / 3); context.lineTo(size, size * i / 3); context.stroke();
        }
        context.restore();
    }
    function resetPosition() {
        if (!image) return;
        scale = baseScale; zoom.value = '1'; zoomValue.value = '100%';
        x = (size - image.naturalWidth * scale) / 2; y = (size - image.naturalHeight * scale) / 2;
        draw();
    }
    function open(file) {
        releaseSource();
        const currentVersion = ++version;
        sourceFile = file; cropStatus.textContent = 'Loading photo…'; apply.disabled = true;
        original.hidden = !file; original.disabled = !!file && file.size > 5 * 1024 * 1024;
        context.clearRect(0, 0, size, size); dialog.showModal();
        const nextImage = new Image();
        nextImage.onload = () => {
            if (version !== currentVersion) return;
            image = nextImage; baseScale = Math.max(size / image.naturalWidth, size / image.naturalHeight);
            resetPosition(); apply.disabled = false;
            cropStatus.textContent = original.disabled ? 'Crop this photo to reduce its size before uploading.' : '';
            canvas.focus();
        };
        nextImage.onerror = () => {
            if (version !== currentVersion) return;
            cropStatus.textContent = 'This image could not be opened. Try a JPG, PNG, or WebP photo.';
            original.disabled = true;
        };
        if (file) { sourceUrl = URL.createObjectURL(file); nextImage.src = sourceUrl; }
        else nextImage.src = preview.src;
    }
    function commit(file, cropped) {
        recropFile = sourceFile || file;
        committedFile = file; setFile(file);
        if (previewUrl) URL.revokeObjectURL(previewUrl);
        previewUrl = URL.createObjectURL(file); preview.src = previewUrl; removeFlag.value = '0';
        status.textContent = cropped ? 'Photo cropped. Save Member to upload it.' : 'Photo ready. Save Member to upload it.';
        refresh(); close();
    }
    document.getElementById('crewPhotoUploadBtn').addEventListener('click', () => input.click());
    input.addEventListener('change', () => { if (input.files[0]) open(input.files[0]); });
    cropButton.addEventListener('click', () => open(recropFile));
    document.querySelectorAll('[data-cancel-crop]').forEach(button => button.addEventListener('click', cancel));
    dialog.addEventListener('cancel', event => { event.preventDefault(); cancel(); });
    document.getElementById('crewCropReset').addEventListener('click', resetPosition);
    original.addEventListener('click', () => { if (sourceFile && !original.disabled) commit(sourceFile, false); });
    zoom.addEventListener('input', () => {
        if (!image) return;
        const nextScale = baseScale * Number(zoom.value);
        x = size / 2 - (size / 2 - x) * nextScale / scale; y = size / 2 - (size / 2 - y) * nextScale / scale;
        scale = nextScale; zoomValue.value = Math.round(Number(zoom.value) * 100) + '%'; draw();
    });
    canvas.addEventListener('pointerdown', event => {
        if (!image) return;
        drag = { id: event.pointerId, x: event.clientX, y: event.clientY };
        canvas.setPointerCapture(event.pointerId); canvas.focus();
    });
    canvas.addEventListener('pointermove', event => {
        if (!drag || drag.id !== event.pointerId) return;
        const ratio = size / canvas.getBoundingClientRect().width;
        x += (event.clientX - drag.x) * ratio; y += (event.clientY - drag.y) * ratio;
        drag.x = event.clientX; drag.y = event.clientY; draw();
    });
    ['pointerup', 'pointercancel', 'lostpointercapture'].forEach(type => canvas.addEventListener(type, () => { drag = null; }));
    canvas.addEventListener('keydown', event => {
        if (!image || !['ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'].includes(event.key)) return;
        event.preventDefault(); const step = event.shiftKey ? 40 : 10;
        x += event.key === 'ArrowLeft' ? -step : event.key === 'ArrowRight' ? step : 0;
        y += event.key === 'ArrowUp' ? -step : event.key === 'ArrowDown' ? step : 0; draw();
    });
    apply.addEventListener('click', () => {
        if (!image || apply.disabled) return;
        apply.disabled = true; const currentVersion = version;
        try {
            const output = document.createElement('canvas'); output.width = output.height = 800;
            const ctx = output.getContext('2d'); ctx.fillStyle = '#fff'; ctx.fillRect(0, 0, 800, 800);
            ctx.drawImage(image, -x / scale, -y / scale, size / scale, size / scale, 0, 0, 800, 800);
            output.toBlob(blob => {
                if (version !== currentVersion) return;
                if (!blob) { cropStatus.textContent = 'Could not crop this photo. Please try another image.'; apply.disabled = false; return; }
                const name = (sourceFile?.name || 'profile-photo').replace(/\.[^.]+$/, '') + '-cropped.jpg';
                commit(new File([blob], name, { type: 'image/jpeg' }), true);
            }, 'image/jpeg', .92);
        } catch (_) { cropStatus.textContent = 'Could not crop this photo. Upload a new image and try again.'; apply.disabled = false; }
    });
    document.getElementById('crewPhotoRemoveBtn').addEventListener('click', () => {
        committedFile = null; recropFile = null; setFile(null);
        if (previewUrl) URL.revokeObjectURL(previewUrl);
        previewUrl = null; preview.src = placeholder; removeFlag.value = '1';
        status.textContent = 'Photo removed. Save Member to confirm.'; refresh();
    });
    return { refresh, reset() {
        if (dialog.open) close(); committedFile = null; recropFile = null;
        if (previewUrl) URL.revokeObjectURL(previewUrl);
        previewUrl = null; status.textContent = ''; refresh();
    } };
};
