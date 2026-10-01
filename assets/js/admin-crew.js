document.addEventListener('DOMContentLoaded', function () {

    var placeholder = window.CREW_PLACEHOLDER || '';
    var originalPhoto = '';
    var isEditMode = false;

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
    var photoPreview = document.getElementById('crewPhotoPreview');
    var photoInput = document.getElementById('crewPhotoInput');
    var removePhotoBtn = document.getElementById('crewPhotoRemoveBtn');
    var removePhotoFlag = document.getElementById('crewRemovePhotoFlag');

    function openModal() {
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.setAttribute('aria-hidden', 'true');
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
            openModal();
        });
    });

    photoInput.addEventListener('change', function () {
        if (!photoInput.files || !photoInput.files[0]) return;
        var reader = new FileReader();
        reader.onload = function (e) {
            photoPreview.src = e.target.result;
            removePhotoFlag.value = '0';
        };
        reader.readAsDataURL(photoInput.files[0]);
    });

    removePhotoBtn.addEventListener('click', function () {
        photoPreview.src = placeholder;
        photoInput.value = '';
        removePhotoFlag.value = '1';
    });

    form.addEventListener('submit', function (e) {
        if (nameField.value.trim() === '' || posField.value.trim() === '') {
            e.preventDefault();
            if (window.Swal) {
                Swal.fire(Object.assign({}, window.SwalDefaults, {
                    icon: 'warning',
                    title: 'Required fields missing',
                    text: 'Name and Position are required.'
                }));
            }
            return false;
        }
        var sortVal = parseInt(sortField.value, 10);
        if (!sortVal || sortVal < 1) {
            e.preventDefault();
            if (window.Swal) {
                Swal.fire(Object.assign({}, window.SwalDefaults, {
                    icon: 'warning',
                    title: 'Sort Order required',
                    text: 'Please enter a valid Sort Order (minimum 1).'
                }));
            }
            return false;
        }
    });

    var confirmBox = document.getElementById('crewConfirm');
    var confirmName = document.getElementById('crewConfirmName');
    var confirmId = document.getElementById('crewDeleteId');

    document.querySelectorAll('[data-delete]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            confirmName.textContent = btn.getAttribute('data-name');
            confirmId.value = btn.getAttribute('data-id');
            confirmBox.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        });
    });

    document.querySelectorAll('[data-close-confirm]').forEach(function (el) {
        el.addEventListener('click', function () {
            confirmBox.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        });
    });
});