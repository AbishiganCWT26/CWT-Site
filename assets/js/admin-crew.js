document.addEventListener('DOMContentLoaded', function () {

    var placeholder = window.CREW_PLACEHOLDER || '';
    var originalPhoto = '';
    var isEditMode = false;
    var maxPhotoSize = 5 * 1024 * 1024;

    function notify(options) {
        var config = Object.assign({}, window.SwalDefaults || {}, {
            customClass: { popup: 'crew-swal' },
            confirmButtonText: 'OK',
            buttonsStyling: true
        }, options);
        if (window.Swal) {
            return Swal.fire(config);
        }
        window.alert((config.title ? config.title + '\n' : '') + (config.text || ''));
        return Promise.resolve();
    }

    var flashEl = document.getElementById('crewFlash');
    if (flashEl) {
        var flashType = flashEl.getAttribute('data-type') === 'error' ? 'error' : 'success';
        var flashMsg = flashEl.getAttribute('data-msg') || '';
        notify({
            icon: flashType,
            title: flashType === 'error' ? 'Something went wrong' : 'Success',
            text: flashMsg,
            timer: flashType === 'error' ? undefined : 2400,
            timerProgressBar: flashType !== 'error',
            confirmButtonText: 'Got it'
        });
        if (window.history && window.history.replaceState) {
            var cleanUrl = window.location.pathname;
            window.history.replaceState({}, document.title, cleanUrl);
        }
    }

    document.querySelectorAll('.crew-tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            var target = tab.getAttribute('data-tab');
            document.querySelectorAll('.crew-tab').forEach(function (t) { t.classList.toggle('active', t === tab); });
            document.querySelectorAll('.crew-panel').forEach(function (p) {
                p.classList.toggle('active', p.getAttribute('data-panel') === target);
            });
        });
    });

    var modal = document.getElementById('crewModal');
    var form = document.getElementById('crewForm');
    var titleEl = document.getElementById('crewModalTitle');
    var idField = document.getElementById('crewId');
    var typeField = document.getElementById('crewType');
    var nameField = document.getElementById('crewName');
    var posField = document.getElementById('crewPosition');
    var linkField = document.getElementById('crewLinkedin');
    var sortField = document.getElementById('crewSort');
    var internField = document.getElementById('crewIntern');
    var internWrap = document.getElementById('crewInternWrap');
    var expertFields = document.getElementById('expertFieldsWrap');
    var photoPreview = document.getElementById('crewPhotoPreview');
    var addEduBtn = document.getElementById('addEduBtn');
    var eduList = document.getElementById('crewEduList');
    var photoInput = document.getElementById('crewPhotoInput');
    var removePhotoBtn = document.getElementById('crewPhotoRemoveBtn');
    var removePhotoFlag = document.getElementById('crewRemovePhotoFlag');
    var confirmBox = document.getElementById('crewConfirm');
    var confirmName = document.getElementById('crewConfirmName');
    var confirmId = document.getElementById('crewDeleteId');

    if (modal && modal.parentElement !== document.body) {
        document.body.appendChild(modal);
    }
    if (confirmBox && confirmBox.parentElement !== document.body) {
        document.body.appendChild(confirmBox);
    }

    function openModal() {
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        setTimeout(function () {
            if (nameField) nameField.focus();
        }, 60);
    }

    function closeModal() {
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    function closeConfirm() {
        confirmBox.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    function resetForm() {
        form.reset();
        idField.value = '0';
        photoPreview.src = placeholder;
        removePhotoFlag.value = '0';
        originalPhoto = '';
        isEditMode = false;
        photoInput.value = '';

        if (eduList) {
            var items = eduList.querySelectorAll('.crew-edu-item');
            for (var i = 1; i < items.length; i++) {
                items[i].remove();
            }
            if (items[0]) {
                var firstInput = items[0].querySelector('input');
                if (firstInput) firstInput.value = '';
            }
        }
    }

    document.querySelectorAll('[data-open-modal]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            resetForm();
            var crewType = btn.getAttribute('data-crew-type') || 'Expert';
            var nextSort = btn.getAttribute('data-next-sort') || '1';
            typeField.value = crewType;
            titleEl.textContent = 'Add ' + crewType + ' Crew Member';
            sortField.value = nextSort;
            sortField.setAttribute('required', 'required');
            internWrap.style.display = crewType === 'Expert' ? 'none' : 'flex';
            if (expertFields) expertFields.style.display = crewType === 'Expert' ? 'flex' : 'none';
            openModal();
        });
    });

    document.querySelectorAll('[data-close-modal]').forEach(function (el) {
        el.addEventListener('click', closeModal);
    });

    document.querySelectorAll('[data-edit]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            resetForm();
            isEditMode = true;
            idField.value = btn.getAttribute('data-id');
            nameField.value = btn.getAttribute('data-name');
            posField.value = btn.getAttribute('data-position');
            linkField.value = btn.getAttribute('data-linkedin') || '';
            sortField.value = btn.getAttribute('data-sort');
            sortField.setAttribute('required', 'required');
            var intern = btn.getAttribute('data-intern') === '1';
            internField.checked = intern;
            var crewType = btn.getAttribute('data-crew-type');
            typeField.value = crewType;
            document.getElementById('crewDomestic').value = btn.getAttribute('data-domestic') || '';
            document.getElementById('crewIntl').value = btn.getAttribute('data-international') || '';
            var qualifications = [];
            try { qualifications = JSON.parse(btn.getAttribute('data-education') || '[]'); } catch (_) { }
            if (!Array.isArray(qualifications)) qualifications = [];
            qualifications.forEach(function (qualification, index) {
                if (typeof qualification !== 'string') return;
                if (index > 0) addEduBtn.click();
                eduList.lastElementChild.querySelector('input').value = qualification;
            });
            var photoUrl = btn.getAttribute('data-photo') || '';
            originalPhoto = photoUrl;
            if (photoUrl && photoUrl !== placeholder) {
                photoPreview.src = photoUrl;
            } else {
                photoPreview.src = placeholder;
                originalPhoto = '';
            }
            titleEl.textContent = 'Edit ' + crewType + ' Crew Member';
            internWrap.style.display = crewType === 'Expert' ? 'none' : 'flex';
            if (expertFields) expertFields.style.display = crewType === 'Expert' ? 'flex' : 'none';
            openModal();
        });
    });

    photoInput.addEventListener('change', function () {
        if (!photoInput.files || !photoInput.files[0]) return;
        var file = photoInput.files[0];
        if (file.type && file.type.indexOf('image/') !== 0) {
            photoInput.value = '';
            notify({
                icon: 'error',
                title: 'Invalid file',
                text: 'Please choose an image file (JPG, PNG, WEBP or GIF).'
            });
            return;
        }
        if (file.size > maxPhotoSize) {
            photoInput.value = '';
            notify({
                icon: 'error',
                title: 'File too large',
                text: 'Profile photo must be 5 MB or smaller.'
            });
            return;
        }
        var reader = new FileReader();
        reader.onload = function (e) {
            photoPreview.src = e.target.result;
            removePhotoFlag.value = '0';
        };
        reader.onerror = function () {
            photoInput.value = '';
            notify({
                icon: 'error',
                title: 'Preview failed',
                text: 'The selected image could not be read. Please try another file.'
            });
        };
        reader.readAsDataURL(file);
    });

    removePhotoBtn.addEventListener('click', function () {
        photoPreview.src = placeholder;
        photoInput.value = '';
        removePhotoFlag.value = '1';
    });

    if (addEduBtn && eduList) {
        addEduBtn.addEventListener('click', function () {
            var item = document.createElement('div');
            item.className = 'crew-edu-item';

            var input = document.createElement('input');
            input.type = 'text';
            input.name = 'educational_qualification[]';
            input.maxLength = 255;
            input.placeholder = 'e.g. Master of Business Administration';

            var removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'crew-btn-icon-remove';
            removeBtn.innerHTML = '&times;';
            removeBtn.title = 'Remove Qualification';
            removeBtn.addEventListener('click', function () {
                item.remove();
            });

            item.appendChild(input);
            item.appendChild(removeBtn);
            eduList.appendChild(item);
        });
    }

    form.addEventListener('submit', function (e) {
        if (nameField.value.trim() === '' || posField.value.trim() === '') {
            e.preventDefault();
            notify({
                icon: 'warning',
                title: 'Required fields missing',
                text: 'Name and Position are required.'
            });
            return false;
        }
        var sortVal = parseInt(sortField.value, 10);
        if (!sortVal || sortVal < 1) {
            e.preventDefault();
            notify({
                icon: 'warning',
                title: 'Sort Order required',
                text: 'Please enter a valid Sort Order (minimum 1).'
            });
            return false;
        }
        var linkVal = linkField.value.trim();
        if (linkVal !== '' && !/^https?:\/\/\S+$/i.test(linkVal)) {
            e.preventDefault();
            notify({
                icon: 'warning',
                title: 'Invalid LinkedIn link',
                text: 'Please enter a valid URL starting with http:// or https://'
            });
            return false;
        }
    });

    document.querySelectorAll('[data-delete]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            confirmName.textContent = btn.getAttribute('data-name');
            confirmId.value = btn.getAttribute('data-id');
            confirmBox.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        });
    });

    document.querySelectorAll('[data-close-confirm]').forEach(function (el) {
        el.addEventListener('click', closeConfirm);
    });

    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        if (confirmBox.getAttribute('aria-hidden') === 'false') {
            closeConfirm();
        } else if (modal.getAttribute('aria-hidden') === 'false') {
            closeModal();
        }
    });
});