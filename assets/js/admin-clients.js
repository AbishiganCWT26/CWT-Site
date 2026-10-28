document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('input[type="file"][data-preview]').forEach(function (fileInput) {
        fileInput.addEventListener('change', function () {
            var previewId = fileInput.getAttribute('data-preview');
            var preview = previewId ? document.getElementById(previewId) : null;
            if (!preview) return;

            var file = fileInput.files && fileInput.files[0];
            if (!file) return;

            var MAX = 5 * 1024 * 1024;
            if (file.size > MAX) {
                alert('The selected file is larger than 5 MB. Please choose a smaller image.');
                fileInput.value = '';
                return;
            }

            var isImage = (file.type && file.type.indexOf('image/') === 0)
                || /\.(jpe?g|png|gif|webp|svg)$/i.test(file.name);
            if (!isImage) {
                alert('Please select a JPG, PNG, GIF, WebP or SVG image.');
                fileInput.value = '';
                return;
            }

            console.log('[admin-clients.js] reading file for preview:', file.name, file.size, 'bytes', file.type);

            var reader = new FileReader();
            reader.onload = function (ev) {
                preview.src = ev.target.result;
                preview.style.display = 'block';
            };
            reader.onerror = function () {
                console.error('[admin-clients.js] FileReader error for', file.name);
            };
            reader.readAsDataURL(file);
        });
    });
});