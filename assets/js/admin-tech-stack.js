document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('input[type="file"][data-preview]').forEach(function (fileInput) {
        fileInput.addEventListener('change', function () {
            var previewId = fileInput.getAttribute('data-preview');
            var preview = document.getElementById(previewId);
            if (!preview || !fileInput.files || !fileInput.files[0]) return;
            var reader = new FileReader();
            reader.onload = function (ev) { preview.src = ev.target.result; preview.style.display = 'block'; };
            reader.readAsDataURL(fileInput.files[0]);
        });
    });
});