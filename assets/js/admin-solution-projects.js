document.addEventListener('DOMContentLoaded', function () {
    var logoSelect = document.getElementById('projectLogoSelect');
    var projectImage = document.getElementById('projImg');
    var removeImageFlag = document.getElementById('removeProjectImageFlag');
    var removeImageButton = document.getElementById('removeProjectImageBtn');
    if (removeImageButton && projectImage) {
        removeImageButton.addEventListener('click', function () {
            var input = document.querySelector('input[name="image"]');
            if (input) input.value = '';
            var fallback = projectImage.dataset.defaultSrc || '';
            projectImage.src = fallback;
            projectImage.dataset.custom = '0';
            projectImage.style.display = fallback ? 'block' : 'none';
            removeImageFlag.value = '1';
            updateLogoPreview();
        });
    }
    function updateLogoPreview() {
        if (!logoSelect || !projectImage) return;
        var selected = logoSelect.options[logoSelect.selectedIndex];
        var url = selected ? selected.dataset.logo || '' : '';
        var hasImage = !!projectImage.getAttribute('src') && projectImage.style.display !== 'none';
        document.getElementById('projectImagePreview').hidden = !hasImage;
        if (removeImageButton) removeImageButton.hidden = !hasImage || projectImage.dataset.custom !== '1';
        var badge = document.getElementById('projectLogoBadge');
        badge.hidden = !hasImage || !url;
        var image = document.getElementById('projectLogoOverlay');
        image.src = url;
        image.alt = selected ? selected.textContent : '';
    }
    if (logoSelect) {
        var picker = document.createElement('div');
        picker.className = 'project-logo-picker';
        var trigger = document.createElement('button');
        trigger.type = 'button'; trigger.className = 'project-logo-trigger';
        trigger.setAttribute('aria-haspopup', 'listbox'); trigger.setAttribute('aria-expanded', 'false');
        trigger.setAttribute('aria-label', 'Choose product logo'); trigger.setAttribute('aria-controls', 'projectLogoOptions');
        var selectedImage = document.createElement('img'); selectedImage.alt = '';
        var selectedName = document.createElement('span');
        trigger.append(selectedImage, selectedName);
        var menu = document.createElement('div');
        menu.className = 'project-logo-options'; menu.id = 'projectLogoOptions'; menu.hidden = true;
        menu.setAttribute('role', 'listbox'); menu.setAttribute('aria-label', 'Product logos');
        var choices = [];
        function closePicker() { menu.hidden = true; trigger.setAttribute('aria-expanded', 'false'); }
        function syncPicker() {
            var option = logoSelect.options[logoSelect.selectedIndex];
            selectedName.textContent = option.textContent;
            selectedImage.hidden = !option.dataset.logo;
            if (option.dataset.logo) selectedImage.src = option.dataset.logo;
            choices.forEach(function (choice) { choice.setAttribute('aria-selected', choice.dataset.value === logoSelect.value ? 'true' : 'false'); });
            updateLogoPreview();
        }
        Array.from(logoSelect.options).forEach(function (option) {
            if (option.disabled) return;
            var choice = document.createElement('button'); choice.type = 'button';
            choice.className = 'project-logo-option'; choice.dataset.value = option.value;
            choice.setAttribute('role', 'option'); choice.tabIndex = -1;
            if (option.dataset.logo) { var image = document.createElement('img'); image.src = option.dataset.logo; image.alt = ''; choice.appendChild(image); }
            var label = document.createElement('span'); label.textContent = option.textContent; choice.appendChild(label);
            choice.addEventListener('click', function () { logoSelect.value = option.value; logoSelect.dispatchEvent(new Event('change')); closePicker(); trigger.focus(); });
            menu.appendChild(choice); choices.push(choice);
        });
        function openPicker() { menu.hidden = false; trigger.setAttribute('aria-expanded', 'true'); (choices.find(function (choice) { return choice.dataset.value === logoSelect.value; }) || choices[0]).focus(); }
        trigger.addEventListener('click', function () { if (menu.hidden) openPicker(); else closePicker(); });
        picker.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') { event.preventDefault(); closePicker(); trigger.focus(); }
            else if (event.key === 'Tab') closePicker();
            else if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
                event.preventDefault();
                if (menu.hidden) { openPicker(); return; }
                var index = choices.indexOf(document.activeElement);
                choices[(index + (event.key === 'ArrowDown' ? 1 : -1) + choices.length) % choices.length].focus();
            }
        });
        document.addEventListener('click', function (event) { if (!picker.contains(event.target)) closePicker(); });
        picker.append(trigger, menu); logoSelect.insertAdjacentElement('afterend', picker);
        logoSelect.hidden = true; logoSelect.style.display = 'none';
        logoSelect.addEventListener('change', syncPicker);
        document.querySelector('label[for="projectLogoSelect"]').addEventListener('click', function (event) { event.preventDefault(); trigger.focus(); });
        syncPicker();
    }


    document.querySelectorAll('input[type="file"][data-preview]').forEach(function (fileInput) {
        fileInput.addEventListener('change', function () {
            var previewId = fileInput.getAttribute('data-preview');
            var preview = document.getElementById(previewId);
            if (!preview || !fileInput.files || !fileInput.files[0]) return;
            var selectedFile = fileInput.files[0];
            var reader = new FileReader();
            reader.onload = function (ev) {
                if (!fileInput.files.length || fileInput.files[0] !== selectedFile) return;
                if (removeImageFlag) removeImageFlag.value = '0';
                preview.src = ev.target.result;
                if (preview === projectImage) projectImage.dataset.custom = '1';
                preview.style.display = 'block';
                updateLogoPreview();
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