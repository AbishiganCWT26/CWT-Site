document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.svc-modal').forEach(function (modal) {
        document.body.appendChild(modal);
    });

    const hasSwal = typeof Swal !== 'undefined';

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function openModal(id) {
        const modal = document.getElementById(id);
        if (!modal) return;
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('modal-open');
        if (window.matchMedia('(hover: hover)').matches) {
            const first = modal.querySelector('input:not([type="hidden"]):not([type="checkbox"]), textarea, select');
            if (first) {
                setTimeout(function () {
                    first.focus({ preventScroll: true });
                }, 120);
            }
        }
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (!modal) return;
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('modal-open');
    }

    document.querySelectorAll('[data-close]').forEach(function (el) {
        el.addEventListener('click', function () {
            closeModal(el.getAttribute('data-close'));
        });
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            if (hasSwal && Swal.isVisible()) return;
            document.querySelectorAll('.svc-modal.is-open').forEach(function (m) {
                closeModal(m.id);
            });
        }
    });

    document.querySelectorAll('.delete-form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const name = form.getAttribute('data-name') || 'this item';
            const msg = 'Are you sure you want to delete "' + name + '"? This cannot be undone.';
            if (typeof window.confirmDelete === 'function') {
                window.confirmDelete(msg, function () {
                    form.submit();
                });
            } else if (hasSwal) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Delete?',
                    text: msg,
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    cancelButtonText: 'Cancel'
                }).then(function (res) {
                    if (res.isConfirmed) form.submit();
                });
            } else {
                if (window.confirm(msg)) form.submit();
            }
        });
    });

    const serviceModal = document.getElementById('serviceModal');
    const serviceForm = document.getElementById('serviceForm');
    const serviceTitle = document.getElementById('serviceModalTitle');

    const addServiceBtn = document.getElementById('addServiceBtn');
    if (addServiceBtn) {
        addServiceBtn.addEventListener('click', function () {
            serviceForm.reset();
            serviceForm.querySelector('[name="id"]').value = '';
            serviceForm.querySelector('[name="sort_order"]').value = '0';
            serviceForm.querySelector('[name="is_active"]').checked = true;
            serviceTitle.textContent = 'Add Service';
            openModal('serviceModal');
        });
    }

    document.querySelectorAll('.edit-service-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            let data;
            try {
                data = JSON.parse(btn.getAttribute('data-service'));
            } catch (err) {
                return;
            }
            serviceForm.reset();
            serviceForm.querySelector('[name="id"]').value = data.id;
            serviceForm.querySelector('[name="num"]').value = data.num || '';
            serviceForm.querySelector('[name="title"]').value = data.title || '';
            serviceForm.querySelector('[name="description"]').value = data.description || '';
            serviceForm.querySelector('[name="short_description"]').value = data.short_description || '';
            serviceForm.querySelector('[name="consultant_name"]').value = data.consultant_name || '';
            serviceForm.querySelector('[name="consultant_role"]').value = data.consultant_role || '';
            serviceForm.querySelector('[name="sort_order"]').value = data.sort_order || 0;
            serviceForm.querySelector('[name="is_active"]').checked = !!data.is_active;
            serviceForm.querySelector('[name="lead"]').value = (data.lead || []).join(', ');
            serviceForm.querySelector('[name="team"]').value = (data.team || []).join(', ');
            serviceTitle.textContent = 'Edit Service';
            openModal('serviceModal');
        });
    });

    const capModal = document.getElementById('capabilityModal');
    const capForm = document.getElementById('capabilityForm');
    const capTitle = document.getElementById('capabilityModalTitle');
    const capCardsList = document.getElementById('capCardsList');
    const capCardTemplate = document.getElementById('capCardRowTemplate');
    let cardIndex = 0;

    function addCardRow(data) {
        if (!capCardTemplate) return;
        cardIndex += 1;
        const html = capCardTemplate.innerHTML.replace(/__INDEX__/g, String(cardIndex));
        const wrap = document.createElement('div');
        wrap.innerHTML = html.trim();
        const row = wrap.firstChild;
        capCardsList.appendChild(row);

        const titleInput = row.querySelector('[name$="[title]"]');
        const iconSelect = row.querySelector('[name$="[icon]"]');
        const descInput = row.querySelector('[name$="[description]"]');
        const sortInput = row.querySelector('[name$="[sort_order]"]');
        const removeBtn = row.querySelector('.cap-card-delete');

        if (data) {
            titleInput.value = data.title || '';
            iconSelect.value = data.icon || 'box';
            descInput.value = data.description || '';
            sortInput.value = data.sort_order || 0;
        } else {
            sortInput.value = capCardsList.children.length;
        }

        removeBtn.addEventListener('click', function () {
            row.classList.add('is-leaving');
            setTimeout(function () {
                row.remove();
            }, 200);
        });
    }

    const addCardBtn = document.getElementById('addCardBtn');
    if (addCardBtn) {
        addCardBtn.addEventListener('click', function () {
            addCardRow(null);
        });
    }

    const addCapabilityBtn = document.getElementById('addCapabilityBtn');
    if (addCapabilityBtn) {
        addCapabilityBtn.addEventListener('click', function () {
            capForm.reset();
            capForm.querySelector('[name="id"]').value = '';
            capForm.querySelector('[name="sort_order"]').value = '0';
            capForm.querySelector('[name="is_active"]').checked = true;
            capForm.querySelector('[name="icon"]').value = 'box';
            capForm.querySelector('[name="accent"]').value = '#001be4';
            capForm.querySelector('[name="accent2"]').value = '#0015b0';
            capForm.querySelector('[name="soft"]').value = 'rgba(0, 27, 228, 0.16)';
            capCardsList.innerHTML = '';
            cardIndex = 0;
            capTitle.textContent = 'Add Capability Section';
            openModal('capabilityModal');
        });
    }

    document.querySelectorAll('.edit-capability-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            let data;
            try {
                data = JSON.parse(btn.getAttribute('data-capability'));
            } catch (err) {
                return;
            }
            capForm.reset();
            capForm.querySelector('[name="id"]').value = data.id;
            capForm.querySelector('[name="label"]').value = data.label || '';
            capForm.querySelector('[name="slug"]').value = data.slug || '';
            capForm.querySelector('[name="icon"]').value = data.icon || 'box';
            capForm.querySelector('[name="accent"]').value = data.accent || '#001be4';
            capForm.querySelector('[name="accent2"]').value = data.accent2 || '#0015b0';
            capForm.querySelector('[name="soft"]').value = data.soft || 'rgba(0, 27, 228, 0.16)';
            capForm.querySelector('[name="sort_order"]').value = data.sort_order || 0;
            capForm.querySelector('[name="is_active"]').checked = !!data.is_active;
            capCardsList.innerHTML = '';
            cardIndex = 0;
            (data.cards || []).forEach(function (c) {
                addCardRow(c);
            });
            capTitle.textContent = 'Edit Capability Section';
            openModal('capabilityModal');
        });
    });
});
