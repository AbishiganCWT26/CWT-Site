document.addEventListener('DOMContentLoaded', function () {

    var quill = new Quill('#quillEditor', {
        theme: 'snow',
        modules: { toolbar: '#quillToolbar' },
        placeholder: 'Write your insight here...'
    });

    var form = document.getElementById('postForm');
    var hiddenInput = document.getElementById('quillContent');
    var hiddenTags = document.getElementById('hashtags_hidden');
    var tagContainer = document.getElementById('tag_container');
    var tagInput = document.getElementById('hashtag_input');

    var tags = [];
    if (window.INITIAL_HASHTAGS) {
        tags = window.INITIAL_HASHTAGS.split(',').map(function (t) { return t.trim(); }).filter(Boolean);
    }

    function renderTags() {
        tagContainer.innerHTML = '';
        tags.forEach(function (tag, idx) {
            var pill = document.createElement('span');
            pill.className = 'tag-pill';
            var text = document.createTextNode(tag + ' ');
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.setAttribute('aria-label', 'Remove ' + tag);
            btn.innerHTML = '&times;';
            btn.addEventListener('click', function () { tags.splice(idx, 1); sync(); });
            pill.appendChild(text);
            pill.appendChild(btn);
            tagContainer.appendChild(pill);
        });
    }

    function sync() {
        hiddenTags.value = tags.join(',');
        renderTags();
    }

    function addTag(value) {
        value = (value || '').trim().replace(/^#/, '');
        if (!value) return;
        var formatted = value.indexOf('#') === 0 ? value : '#' + value;
        formatted = '#' + value.replace(/^#+/, '');
        if (tags.indexOf(formatted) === -1) {
            tags.push(formatted);
            sync();
        }
    }

    tagInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            addTag(tagInput.value);
            tagInput.value = '';
        } else if (e.key === 'Backspace' && tagInput.value === '' && tags.length > 0) {
            tags.pop();
            sync();
        }
    });

    tagInput.addEventListener('blur', function () {
        if (tagInput.value.trim() !== '') {
            addTag(tagInput.value);
            tagInput.value = '';
        }
    });

    form.addEventListener('submit', function (e) {
        hiddenInput.value = quill.root.innerHTML;
        if (tagInput.value.trim() !== '') {
            addTag(tagInput.value);
            tagInput.value = '';
        }
        if (tags.length < 2) {
            e.preventDefault();
            Swal.fire(Object.assign({}, window.SwalDefaults, {
                icon: 'warning',
                title: 'At least 2 hashtags required',
                text: 'Please add a minimum of 2 hashtags before saving.'
            }));
            return false;
        }
    });

    document.querySelectorAll('input[type="file"][data-preview]').forEach(function (fileInput) {
        fileInput.addEventListener('change', function () {
            var previewId = fileInput.getAttribute('data-preview');
            var preview = document.getElementById(previewId);
            if (!preview || !fileInput.files || !fileInput.files[0]) return;
            var reader = new FileReader();
            reader.onload = function (ev) { preview.src = ev.target.result; preview.style.display = 'block'; };
            reader.readAsDataURL(fileInput.files[0]);
        });
    });

    var topicInput = document.getElementById('insight_topic');
    var slugInput = document.getElementById('insight_slug');
    if (topicInput && slugInput) {
        topicInput.addEventListener('blur', function () {
            if (slugInput.value.trim() === '' && topicInput.value.trim() !== '') {
                slugInput.value = topicInput.value.toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '').replace(/[\s-]+/g, '-').replace(/^-|-$/g, '');
            }
        });
    }

    sync();
});