(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        var root = document.querySelector('.crew-admin');
        if (!root) return;

        var placeholder = window.CREW_PLACEHOLDER || '';

        var tabs = root.querySelectorAll('.crew-tab');
        var panels = root.querySelectorAll('.crew-panel');
        var indicator = root.querySelector('.crew-tab-indicator');

        function moveIndicator(tab) {
            if (!indicator || !tab || !tab.parentElement) return;
            var tabRect = tab.getBoundingClientRect();
            var parentRect = tab.parentElement.getBoundingClientRect();
            indicator.style.width = tabRect.width + 'px';
            indicator.style.transform = 'translateX(' + (tabRect.left - parentRect.left - 5.6) + 'px)';
        }

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                var target = tab.dataset.tab;
                tabs.forEach(function (t) { t.classList.toggle('active', t === tab); });
                panels.forEach(function (p) { p.classList.toggle('active', p.dataset.panel === target); });
                moveIndicator(tab);
            });
        });

        var activeTab = root.querySelector('.crew-tab.active');
        if (activeTab) {
            setTimeout(function () { moveIndicator(activeTab); }, 60);
        }

        window.addEventListener('resize', function () {
            var act = root.querySelector('.crew-tab.active');
            if (act) moveIndicator(act);
        });

        var modal = document.getElementById('crewModal');
        var form = document.getElementById('crewForm');
        var modalTitle = document.getElementById('crewModalTitle');
        var fieldId = document.getElementById('crewId');
        var fieldType = document.getElementById('crewType');
        var fieldName = document.getElementById('crewName');
        var fieldPos = document.getElementById('crewPosition');
        var fieldLinkedin = document.getElementById('crewLinkedin');
        var fieldSort = document.getElementById('crewSort');
        var fieldIntern = document.getElementById('crewIntern');
        var internWrap = document.getElementById('crewInternWrap');
        var photoInput = document.getElementById('crewPhotoInput');
        var photoPreview = document.getElementById('crewPhotoPreview');
        var photoRemove = document.getElementById('crewPhotoRemove');
        var removePhotoFlag = document.getElementById('crewRemovePhoto');

        if (!modal || !form) return;

        function openModal() {
            modal.classList.add('open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.classList.remove('open');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        function resetForm() {
            form.reset();
            fieldId.value = '0';
            fieldType.value = 'Expert';
            if (fieldIntern) fieldIntern.checked = false;
            removePhotoFlag.value = '0';
            photoPreview.src = placeholder;
            if (internWrap) internWrap.style.display = '';
        }

        function applyType(type) {
            fieldType.value = type;
            if (type === 'Expert') {
                if (internWrap) internWrap.style.display = 'none';
                if (fieldIntern) fieldIntern.checked = false;
            } else {
                if (internWrap) internWrap.style.display = '';
            }
        }

        document.querySelectorAll('[data-open-modal]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                resetForm();
                var type = btn.dataset.crewType || 'Expert';
                applyType(type);
                modalTitle.textContent = 'Add ' + type + ' Crew Member';
                openModal();
                setTimeout(function () { if (fieldName) fieldName.focus(); }, 90);
            });
        });

        document.querySelectorAll('[data-close-modal]').forEach(function (el) {
            el.addEventListener('click', closeModal);
        });

        document.querySelectorAll('[data-edit]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                resetForm();
                fieldId.value = btn.dataset.id || '0';
                fieldName.value = btn.dataset.name || '';
                fieldPos.value = btn.dataset.position || '';
                fieldLinkedin.value = btn.dataset.linkedin || '';
                fieldSort.value = btn.dataset.sort || '0';
                if (fieldIntern) fieldIntern.checked = btn.dataset.intern === '1';
                var type = btn.dataset.crewType || 'Team';
                applyType(type);
                var photo = btn.dataset.photo || placeholder;
                photoPreview.src = photo;
                modalTitle.textContent = 'Edit ' + type + ' Crew Member';
                openModal();
            });
        });

        if (photoInput) {
            photoInput.addEventListener('change', function () {
                var file = photoInput.files && photoInput.files[0];
                if (!file) return;
                var reader = new FileReader();
                reader.onload = function (ev) {
                    photoPreview.src = ev.target.result;
                    removePhotoFlag.value = '0';
                };
                reader.readAsDataURL(file);
            });
        }

        if (photoRemove) {
            photoRemove.addEventListener('click', function () {
                photoInput.value = '';
                photoPreview.src = placeholder;
                removePhotoFlag.value = '1';
            });
        }

        var confirmBox = document.getElementById('crewConfirm');
        var confirmName = document.getElementById('crewConfirmName');
        var confirmId = document.getElementById('crewDeleteId');

        if (confirmBox) {
            function closeConfirm() {
                confirmBox.classList.remove('open');
                confirmBox.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            }

            document.querySelectorAll('[data-delete]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    confirmId.value = btn.dataset.id || '0';
                    confirmName.textContent = btn.dataset.name || 'this member';
                    confirmBox.classList.add('open');
                    confirmBox.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';
                });
            });

            document.querySelectorAll('[data-close-confirm]').forEach(function (el) {
                el.addEventListener('click', closeConfirm);
            });
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                if (modal.classList.contains('open')) closeModal();
                var c = document.getElementById('crewConfirm');
                if (c && c.classList.contains('open')) {
                    c.classList.remove('open');
                    c.setAttribute('aria-hidden', 'true');
                    document.body.style.overflow = '';
                }
            }
        });

        form.addEventListener('submit', function (e) {
            var n = (fieldName.value || '').trim();
            var p = (fieldPos.value || '').trim();
            if (n === '' || p === '') {
                e.preventDefault();
                if (n === '') fieldName.focus(); else fieldPos.focus();
            }
        });
    });
})();