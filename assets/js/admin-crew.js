document.addEventListener('DOMContentLoaded', function () {

    var placeholder = window.CREW_PLACEHOLDER || '';
    var originalPhoto = '';
    var isEditMode = false;

    document.querySelectorAll('.crew-tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            var target = tab.getAttribute('data-tab');
            document.querySelectorAll('.crew-tab').forEach(function (t) {
                t.classList.toggle('active', t === tab);
                t.setAttribute('aria-selected', t === tab ? 'true' : 'false');
            });
            var url = new URL(window.location.href);
            url.searchParams.set('tab', target);
            window.history.replaceState(null, '', url);
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
    var removePhotoFlag = document.getElementById('crewRemovePhotoFlag');
    var photoCrop = (typeof window.createCrewPhotoCrop === 'function')
        ? window.createCrewPhotoCrop({ input: photoInput, preview: photoPreview, removeFlag: removePhotoFlag, placeholder: placeholder })
        : { refresh: function () { }, reset: function () { } };
    var cropDialog = document.getElementById('crewPhotoCropDialog');
    if (cropDialog && cropDialog.parentElement !== document.body) {
        document.body.appendChild(cropDialog);
    }

    if (modal && modal.parentElement !== document.body) {
        document.body.appendChild(modal);
    }

    function openModal() {
        if (!modal) return;
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        setTimeout(function () {
            if (nameField) nameField.focus();
        }, 60);
    }

    function closeModal() {
        if (!modal) return;
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    function resetForm() {
        if (form) form.reset();
        if (idField) idField.value = '0';
        if (photoPreview) photoPreview.src = placeholder;
        if (removePhotoFlag) removePhotoFlag.value = '0';
        originalPhoto = '';
        isEditMode = false;
        if (photoInput) photoInput.value = '';
        if (photoCrop && typeof photoCrop.reset === 'function') photoCrop.reset();

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
            if (typeField) typeField.value = crewType;
            if (titleEl) titleEl.textContent = 'Add ' + crewType + ' Crew Member';
            if (sortField) {
                sortField.value = nextSort;
                sortField.setAttribute('required', 'required');
            }
            if (internWrap) internWrap.style.display = crewType === 'Expert' ? 'none' : 'flex';
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
            if (idField) idField.value = btn.getAttribute('data-id') || '0';
            if (nameField) nameField.value = btn.getAttribute('data-name') || '';
            if (posField) posField.value = btn.getAttribute('data-position') || '';
            if (linkField) linkField.value = btn.getAttribute('data-linkedin') || '';
            if (sortField) {
                sortField.value = btn.getAttribute('data-sort') || '1';
                sortField.setAttribute('required', 'required');
            }
            var intern = btn.getAttribute('data-intern') === '1';
            if (internField) internField.checked = intern;
            var crewType = btn.getAttribute('data-crew-type') || 'Expert';
            if (typeField) typeField.value = crewType;
            var domEl = document.getElementById('crewDomestic');
            if (domEl) domEl.value = btn.getAttribute('data-domestic') || '';
            var intlEl = document.getElementById('crewIntl');
            if (intlEl) intlEl.value = btn.getAttribute('data-international') || '';

            var qualifications = [];
            try { qualifications = JSON.parse(btn.getAttribute('data-education') || '[]'); } catch (_) { }
            if (!Array.isArray(qualifications)) qualifications = [];
            qualifications.forEach(function (qualification, index) {
                if (typeof qualification !== 'string') return;
                if (index > 0 && addEduBtn) addEduBtn.click();
                if (eduList && eduList.lastElementChild) {
                    var inEl = eduList.lastElementChild.querySelector('input');
                    if (inEl) inEl.value = qualification;
                }
            });

            var photoUrl = btn.getAttribute('data-photo') || '';
            originalPhoto = photoUrl;
            if (photoPreview) {
                if (photoUrl && photoUrl !== placeholder) {
                    photoPreview.src = photoUrl;
                } else {
                    photoPreview.src = placeholder;
                    originalPhoto = '';
                }
            }
            if (titleEl) titleEl.textContent = 'Edit ' + crewType + ' Crew Member';
            if (photoCrop && typeof photoCrop.refresh === 'function') {
                photoCrop.refresh();
            }
            if (internWrap) internWrap.style.display = crewType === 'Expert' ? 'none' : 'flex';
            if (expertFields) expertFields.style.display = crewType === 'Expert' ? 'flex' : 'none';
            openModal();
        });
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

    if (form) {
        form.addEventListener('submit', function (e) {
            if (nameField && posField && (nameField.value.trim() === '' || posField.value.trim() === '')) {
                e.preventDefault();
                if (typeof window.showWarningAlert === 'function') {
                    window.showWarningAlert('Required fields missing', 'Name and Position are required.');
                } else {
                    alert('Name and Position are required.');
                }
                return false;
            }
            var sortVal = sortField ? parseInt(sortField.value, 10) : 0;
            if (!sortVal || sortVal < 1) {
                e.preventDefault();
                if (typeof window.showWarningAlert === 'function') {
                    window.showWarningAlert('Sort Order required', 'Please enter a valid Sort Order (minimum 1).');
                } else {
                    alert('Please enter a valid Sort Order (minimum 1).');
                }
                return false;
            }
            var linkVal = linkField ? linkField.value.trim() : '';
            if (linkVal !== '' && !/^https?:\/\/\S+$/i.test(linkVal)) {
                e.preventDefault();
                if (typeof window.showWarningAlert === 'function') {
                    window.showWarningAlert('Invalid LinkedIn link', 'Please enter a valid URL starting with http:// or https://');
                } else {
                    alert('Please enter a valid URL starting with http:// or https://');
                }
                return false;
            }
        });
    }

    // Delete confirmation using SweetAlert2
    document.querySelectorAll('[data-delete]').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            var memberName = btn.getAttribute('data-name') || 'this member';
            var memberId = btn.getAttribute('data-id');
            var panel = btn.closest('[data-panel]');
            var tab = panel ? panel.getAttribute('data-panel') : 'expert';

            var doDelete = function () {
                var deleteForm = document.getElementById('crewDeleteForm');
                if (deleteForm) {
                    var idInput = document.getElementById('crewDeleteId');
                    var tabInput = document.getElementById('crewDeleteTab');
                    if (idInput) idInput.value = memberId;
                    if (tabInput) tabInput.value = tab;
                    deleteForm.submit();
                }
            };

            if (typeof window.confirmDelete === 'function') {
                window.confirmDelete({
                    title: 'Delete crew member?',
                    text: "You're about to permanently delete " + memberName + ". This will delete the record and its profile photo."
                }, function () {
                    doDelete();
                });
            } else if (confirm('Are you sure you want to delete ' + memberName + '?')) {
                doDelete();
            }
        });
    });

    // Remove photo confirmation using SweetAlert2
    var removePhotoBtn = document.getElementById('crewRemovePhotoBtn') || document.getElementById('crewPhotoRemoveBtn');
    if (removePhotoBtn) {
        removePhotoBtn.addEventListener('click', function (e) {
            if (photoPreview && photoPreview.src === placeholder && (!photoInput || !photoInput.files || !photoInput.files.length)) {
                return;
            }
            e.preventDefault();

            var doRemove = function () {
                if (photoPreview) photoPreview.src = placeholder;
                if (removePhotoFlag) removePhotoFlag.value = '1';
                if (photoInput) photoInput.value = '';
                if (photoCrop && typeof photoCrop.reset === 'function') photoCrop.reset();
            };

            if (typeof window.confirmImageRemoval === 'function') {
                window.confirmImageRemoval({
                    title: 'Remove profile photo?',
                    text: 'The profile photo will be cleared upon saving.'
                }, function () {
                    doRemove();
                });
            } else {
                doRemove();
            }
        });
    }

    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        var cropDlg = document.getElementById('crewPhotoCropDialog');
        if (cropDlg && cropDlg.open) return;
        if (modal && modal.getAttribute('aria-hidden') === 'false') {
            closeModal();
        }
    });
});
