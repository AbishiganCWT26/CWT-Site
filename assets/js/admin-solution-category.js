document.addEventListener('DOMContentLoaded', function () {

    var colorPicker = document.getElementById('colorPicker');
    var colorHex = document.getElementById('colorHex');
    if (colorPicker && colorHex) {
        colorPicker.addEventListener('input', function () {
            colorHex.value = colorPicker.value;
        });
    }

    var iconInput = document.getElementById('iconInput');
    var iconPreview = document.getElementById('iconPreview');
    if (iconInput && iconPreview) {
        iconInput.addEventListener('input', function () {
            var val = iconInput.value.trim() || 'fa-folder';
            iconPreview.className = 'fa-solid ' + val;
        });
    }

    document.querySelectorAll('.icon-chip').forEach(function (chip) {
        chip.addEventListener('click', function () {
            var icon = chip.getAttribute('data-icon');
            if (iconInput) iconInput.value = icon;
            if (iconPreview) iconPreview.className = 'fa-solid ' + icon;
            document.querySelectorAll('.icon-chip').forEach(function (c) { c.classList.remove('active'); });
            chip.classList.add('active');
        });
    });
});