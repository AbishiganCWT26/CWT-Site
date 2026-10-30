document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('worldMapForm');
    if (!form || !window.fetch) return;
    const status = document.getElementById('worldMapStatus');
    const inputs = [...form.querySelectorAll('input[type="checkbox"]')];
    const rows = [...form.querySelectorAll('.world-map-country')];
    const token = form.querySelector('[name="csrf_token"]').value;
    document.getElementById('worldMapSave').hidden = true;
    status.textContent = 'Changes save automatically.';
    function updatePreview() {
        inputs.forEach(input => {
            const pin = document.querySelector(`[data-pin="${input.value}"]`);
            if (pin) pin.hidden = !input.checked;
        });
        const count = inputs.filter(input => input.checked).length;
        document.getElementById('worldMapCount').textContent = `${count} ${count === 1 ? 'country' : 'countries'} selected`;
    }
    // Serialize saves so messages and persisted state stay in the same order.
    let saveQueue = Promise.resolve();
    inputs.forEach(input => input.addEventListener('change', () => {
        const requested = input.checked;
        input.disabled = true;
        updatePreview();
        status.classList.remove('is-error');
        status.textContent = 'Saving…';
        saveQueue = saveQueue.then(async () => {
            try {
                const response = await fetch(form.action, {
                    method: 'POST', credentials: 'same-origin',
                    body: new URLSearchParams({ action: 'toggle', csrf_token: token, country_code: input.value, is_active: requested ? '1' : '0' })
                });
                if (!response.ok) throw new Error('Save failed');
                const result = await response.json();
                if (!result.ok) throw new Error(result.message || 'Save failed');
                status.classList.remove('is-error');
                status.textContent = `${input.closest('label').dataset.name} ${requested ? 'shown' : 'hidden'} on the client map. Saved.`;
            } catch (err) {
                input.checked = !requested;
                updatePreview();
                status.classList.add('is-error');
                status.textContent = 'Could not save. Please try again, or sign in again if your session expired.';
                if (typeof window.showErrorAlert === 'function') {
                    window.showErrorAlert('Could not save changes to the client map. Please try again, or reload the page if your session expired.', 'Save Failed');
                }
            } finally { input.disabled = false; }
        });
    }));
    document.getElementById('worldMapSearch').addEventListener('input', event => {
        const query = event.target.value.trim().toLocaleLowerCase();
        rows.forEach(row => row.hidden = !row.dataset.name.toLocaleLowerCase().includes(query));
        document.getElementById('worldMapEmpty').hidden = rows.some(row => !row.hidden);
    });
    form.addEventListener('submit', event => event.preventDefault());
});
