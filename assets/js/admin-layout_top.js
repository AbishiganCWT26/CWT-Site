document.addEventListener('DOMContentLoaded', function () {
    var sidebarToggle = document.getElementById('sidebarToggle');
    var adminSidebar = document.querySelector('.admin-sidebar');
    var sidebarOverlay = document.getElementById('sidebarOverlay');

    function closeSidebar() {
        if (adminSidebar) adminSidebar.classList.remove('open');
        if (sidebarOverlay) sidebarOverlay.classList.remove('active');
        document.body.classList.remove('no-scroll');
    }

    function openSidebar() {
        if (adminSidebar) adminSidebar.classList.add('open');
        if (sidebarOverlay) sidebarOverlay.classList.add('active');
        document.body.classList.add('no-scroll');
    }

    if (sidebarToggle && adminSidebar) {
        sidebarToggle.addEventListener('click', function () {
            if (adminSidebar.classList.contains('open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeSidebar);
    }

    document.querySelectorAll('.sidebar-link').forEach(function (link) {
        link.addEventListener('click', function () {
            if (window.innerWidth <= 999) closeSidebar();
        });
    });

    var resizeTimer;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            if (window.innerWidth > 999) closeSidebar();
        }, 150);
    });

    document.querySelectorAll('input[type="file"][data-preview]').forEach(function (input) {
        input.addEventListener('change', function () {
            var previewId = input.dataset.preview;
            var preview = document.getElementById(previewId);
            if (!preview) return;
            var file = input.files[0];
            if (file && file.type.startsWith('image/')) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    });

    document.querySelectorAll('.alert[data-auto-dismiss]').forEach(function (alert) {
        var delay = parseInt(alert.dataset.autoDismiss, 10) || 3000;
        setTimeout(function () {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(function () { alert.remove(); }, 400);
        }, delay);
    });

    var titleInput = document.getElementById('insight_topic');
    var slugInput = document.getElementById('insight_slug');
    if (titleInput && slugInput) {
        var autoSlug = true;
        slugInput.addEventListener('input', function () { autoSlug = false; });
        titleInput.addEventListener('input', function () {
            if (!autoSlug) return;
            var slug = titleInput.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim();
            slugInput.value = slug;
        });
    }

    var tagInput = document.getElementById('hashtag_input');
    var tagHidden = document.getElementById('hashtags_hidden');
    var tagContainer = document.getElementById('tag_container');
    if (tagInput && tagHidden && tagContainer) {
        var tags = tagHidden.value ? tagHidden.value.split(',').map(function (t) { return t.trim(); }).filter(Boolean) : [];

        function renderTags() {
            tagContainer.innerHTML = '';
            tags.forEach(function (tag, i) {
                var span = document.createElement('span');
                span.className = 'tag-pill';
                span.innerHTML = tag + ' <button type="button" data-i="' + i + '">&times;</button>';
                span.querySelector('button').addEventListener('click', function () {
                    tags.splice(i, 1);
                    renderTags();
                    tagHidden.value = tags.join(',');
                });
                tagContainer.appendChild(span);
            });
        }
        renderTags();
        tagInput.addEventListener('keydown', function (e) {
            if ((e.key === 'Enter' || e.key === ',') && tagInput.value.trim()) {
                e.preventDefault();
                var val = tagInput.value.trim().replace(/^#/, '');
                val = '#' + val;
                if (tags.indexOf(val) === -1) {
                    tags.push(val);
                    tagHidden.value = tags.join(',');
                    renderTags();
                }
                tagInput.value = '';
            }
        });
    }
});