document.addEventListener('DOMContentLoaded', function () {

    const navbar = document.querySelector('.cwt-navbar, .navbar');
    if (navbar) {
        const onScroll = function () {
            navbar.classList.toggle('is-scrolled', window.scrollY > 60);
            navbar.classList.toggle('scrolled', window.scrollY > 60);
        };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    const revealObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    document.querySelectorAll('.reveal').forEach(function (el) {
        revealObserver.observe(el);
    });

    const form = document.getElementById('ctForm');
    if (!form) return;

    const submitBtn = document.getElementById('ctSubmit');
    const submitText = submitBtn ? submitBtn.querySelector('.ct-submit-text') : null;
    const submitIcon = submitBtn ? submitBtn.querySelector('.ct-submit-ico i') : null;

    function removeAlerts() {
        form.parentElement.querySelectorAll('.ct-alert').forEach(function (el) {
            el.remove();
        });
    }

    function showAlert(type, message) {
        removeAlerts();

        const alert = document.createElement('div');
        alert.className = 'ct-alert ' + (type === 'success' ? 'ct-alert-success' : 'ct-alert-error');
        alert.setAttribute('role', type === 'success' ? 'status' : 'alert');

        const icon = document.createElement('i');
        icon.className = type === 'success'
            ? 'fa-solid fa-circle-check'
            : 'fa-solid fa-circle-exclamation';
        icon.setAttribute('aria-hidden', 'true');

        const span = document.createElement('span');
        span.textContent = message;

        alert.appendChild(icon);
        alert.appendChild(span);

        form.parentElement.insertBefore(alert, form);
    }

    function setLoading(loading) {
        if (!submitBtn) return;

        submitBtn.disabled = loading;
        submitBtn.classList.toggle('is-loading', loading);

        if (submitText) {
            submitText.textContent = loading ? 'Sending...' : 'Send Message';
        }
        if (submitIcon) {
            submitIcon.className = loading
                ? 'fa-solid fa-spinner'
                : 'fa-solid fa-paper-plane';
        }
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const formData = new FormData(form);

        setLoading(true);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(function (res) {
                return res.json();
            })
            .then(function (data) {
                setLoading(false);

                if (data && data.success) {
                    showAlert('success', data.message || 'Message sent successfully.');
                    form.reset();
                    toggleSubmitButton();

                    const box = form.querySelector('.ct-terms-box');
                    if (box) box.classList.remove('is-checked');
                } else {
                    showAlert('error', (data && data.message) || 'Something went wrong. Please try again.');
                }
            })
            .catch(function () {
                setLoading(false);
                showAlert('error', 'Network error. Please try again.');
            });
    });

    const inputs = form.querySelectorAll('input, textarea');
    inputs.forEach(function (input) {
        input.addEventListener('focus', function () {
            input.parentElement.classList.add('is-focused');
        });
        input.addEventListener('blur', function () {
            input.parentElement.classList.remove('is-focused');
        });
    });

    function toggleSubmitButton() {
        if (submitBtn) {
            submitBtn.disabled = !form.checkValidity();
        }
    }

    form.addEventListener('input', toggleSubmitButton);
    form.addEventListener('change', toggleSubmitButton);
    toggleSubmitButton();

});