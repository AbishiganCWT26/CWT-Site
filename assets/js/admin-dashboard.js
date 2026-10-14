document.addEventListener('DOMContentLoaded', function () {
    var cards = document.querySelectorAll('.dash-stat-card, .dash-link');
    cards.forEach(function (el, i) {
        el.style.opacity = '0';
        el.style.transform = 'translateY(10px)';
        setTimeout(function () {
            el.style.transition = 'opacity .4s ease, transform .4s ease';
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, i * 40);
    });
});