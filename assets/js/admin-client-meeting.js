document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.cm-inline-form-delete').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            const msg = form.getAttribute('data-confirm') || 'Are you sure?';
            if (!window.confirm(msg)) {
                e.preventDefault();
                return;
            }
            form.classList.add('is-pending');
        });
    });

    const cards = document.querySelectorAll('.cm-card');
    if (!cards.length) return;

    cards.forEach(function (card) {
        card.addEventListener('animationend', function () {
            card.style.animation = '';
        });
    });

    requestAnimationFrame(function () {
        cards.forEach(function (card, i) {
            card.style.opacity = '0';
            card.style.transform = 'translateY(14px)';
            card.style.transition = 'opacity 0.55s cubic-bezier(0.22, 1, 0.36, 1) ' + (i * 45) + 'ms, transform 0.55s cubic-bezier(0.22, 1, 0.36, 1) ' + (i * 45) + 'ms';
        });

        requestAnimationFrame(function () {
            cards.forEach(function (card) {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            });
        });
    });

});