/**
 * CWT Admin Panel JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {

  // ─── Image Preview on file input change ───────────────────────
  document.querySelectorAll('input[type="file"][data-preview]').forEach(function (input) {
    input.addEventListener('change', function () {
      const previewId = input.dataset.preview;
      const preview = document.getElementById(previewId);
      if (!preview) return;

      const file = input.files[0];
      if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function (e) {
          preview.src = e.target.result;
          preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
      }
    });
  });

  // ─── Delete confirmation ───────────────────────────────────────
  document.querySelectorAll('[data-confirm]').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      const msg = btn.dataset.confirm || 'Are you sure you want to delete this item?';
      if (!confirm(msg)) {
        e.preventDefault();
        return false;
      }
    });
  });

  // ─── Auto-dismiss alerts ──────────────────────────────────────
  document.querySelectorAll('.alert[data-auto-dismiss]').forEach(function (alert) {
    const delay = parseInt(alert.dataset.autoDismiss, 10) || 3000;
    setTimeout(function () {
      alert.style.opacity = '0';
      alert.style.transition = 'opacity 0.4s ease';
      setTimeout(function () { alert.remove(); }, 400);
    }, delay);
  });

  // ─── Mobile sidebar toggle ────────────────────────────────────
  const sidebarToggle = document.getElementById('sidebarToggle');
  const adminSidebar  = document.querySelector('.admin-sidebar');
  if (sidebarToggle && adminSidebar) {
    sidebarToggle.addEventListener('click', function () {
      adminSidebar.classList.toggle('open');
    });
  }

  // ─── Slug auto-generate from title ───────────────────────────
  const titleInput = document.getElementById('insight_topic');
  const slugInput  = document.getElementById('insight_slug');
  if (titleInput && slugInput) {
    let autoSlug = true;

    slugInput.addEventListener('input', function () {
      autoSlug = false; // user manually edited slug
    });

    titleInput.addEventListener('input', function () {
      if (!autoSlug) return;
      const slug = titleInput.value
        .toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim();
      slugInput.value = slug;
    });
  }

  // ─── Sort order drag-reorder (basic up/down buttons) ─────────
  document.querySelectorAll('[data-reorder-form]').forEach(function (form) {
    form.addEventListener('submit', function () {
      // collect order from hidden inputs, handled server-side
    });
  });

  // ─── Tag input (hashtags) ─────────────────────────────────────
  const tagInput = document.getElementById('hashtag_input');
  const tagHidden = document.getElementById('hashtags_hidden');
  const tagContainer = document.getElementById('tag_container');
  if (tagInput && tagHidden && tagContainer) {
    let tags = tagHidden.value ? tagHidden.value.split(',').map(t => t.trim()).filter(Boolean) : [];

    function renderTags() {
      tagContainer.innerHTML = '';
      tags.forEach(function (tag, i) {
        const span = document.createElement('span');
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
        let val = tagInput.value.trim().replace(/^#/, '');
        val = '#' + val;
        if (!tags.includes(val)) {
          tags.push(val);
          tagHidden.value = tags.join(',');
          renderTags();
        }
        tagInput.value = '';
      }
    });
  }

});
