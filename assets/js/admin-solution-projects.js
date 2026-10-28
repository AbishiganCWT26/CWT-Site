document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('input[type="file"][data-preview]').forEach(function (fileInput) {
        fileInput.addEventListener('change', function () {
            var previewId = fileInput.getAttribute('data-preview');
            var preview = document.getElementById(previewId);
            if (!preview || !fileInput.files || !fileInput.files[0]) return;
            var reader = new FileReader();
            reader.onload = function (ev) {
                preview.src = ev.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(fileInput.files[0]);
        });
    });

    var nameInput = document.getElementById('projName');
    var slugPreview = document.getElementById('slugPreview');
    var slugInline = document.getElementById('slugInline');

    if (nameInput && slugPreview) {
        nameInput.addEventListener('input', function () {
            var slug = nameInput.value
                .replace(/[^a-zA-Z0-9\s-]/g, '')
                .replace(/[\s-]+/g, '-')
                .replace(/^-|-$/g, '');
            slugPreview.value = slug;
            if (slugInline) slugInline.textContent = slug || 'project-slug';
        });
    }
});