(function () {
	function showNotice(msg, title, icon) {
		if (typeof Swal !== 'undefined') {
			Swal.fire({
				title: title || 'Notice',
				text: msg,
				icon: icon || 'warning',
				confirmButtonColor: '#001be4'
			});
		} else {
			alert(msg);
		}
	}

	document.querySelectorAll('input[type="file"][data-preview]').forEach(function (input) {
		var previewId = input.getAttribute('data-preview');
		var preview = document.getElementById(previewId);
		if (!preview) return;

		input.addEventListener('change', function () {
			var file = this.files && this.files[0];
			if (!file) {
				preview.src = '';
				preview.style.display = 'none';
				return;
			}

			var MAX = 10 * 1024 * 1024;
			if (file.size > MAX) {
				showNotice('The selected file is larger than 10 MB. Please choose a smaller image.', 'File Too Large', 'error');
				input.value = '';
				preview.src = '';
				preview.style.display = 'none';
				return;
			}

			var isImage = /\.(jpe?g|png|gif|webp)$/i.test(file.name);
			if (!isImage) {
				showNotice('Please select a JPG, JPEG, PNG, WebP, or GIF image.', 'Invalid Format', 'error');
				input.value = '';
				preview.src = '';
				preview.style.display = 'none';
				return;
			}

			var reader = new FileReader();
			reader.onload = function (e) {
				preview.src = e.target.result;
				preview.style.display = 'block';
			};
			reader.readAsDataURL(file);
		});
	});

	var award3dInput = document.getElementById('award3dFile');
	var award3dInfo = document.getElementById('award3dInfo');
	var award3dName = document.getElementById('award3dName');
	var award3dRemove = document.getElementById('award3dRemove');
	var award3dGroup = document.getElementById('award3dGroup');
	var awardCategory = document.getElementById('awardCategory');
	if (award3dInput) {
		var original3dName = award3dName ? award3dName.textContent : '';
		var original3dVisible = award3dInfo ? award3dInfo.style.display !== 'none' : false;

		award3dInput.addEventListener('change', function () {
			var file = this.files && this.files[0];
			if (award3dRemove) {
				award3dRemove.disabled = !!file;
				if (file) award3dRemove.checked = false;
			}
			if (!file) {
				if (award3dName) award3dName.textContent = original3dName;
				if (award3dInfo) award3dInfo.style.display = original3dVisible ? '' : 'none';
				return;
			}

			var MAX_3D = 10 * 1024 * 1024;
			if (file.size > MAX_3D) {
				showNotice('The selected 3D file is larger than 10 MB. Please choose a smaller file.', 'File Too Large', 'error');
				award3dInput.value = '';
				if (award3dRemove) award3dRemove.disabled = false;
				if (award3dName) award3dName.textContent = original3dName;
				if (award3dInfo) award3dInfo.style.display = original3dVisible ? '' : 'none';
				return;
			}

			if (!/\.html?$/i.test(file.name)) {
				showNotice('Please select an HTML file (.html or .htm) that contains the three.js 3D model.', 'Invalid Format', 'error');
				award3dInput.value = '';
				if (award3dRemove) award3dRemove.disabled = false;
				if (award3dName) award3dName.textContent = original3dName;
				if (award3dInfo) award3dInfo.style.display = original3dVisible ? '' : 'none';
				return;
			}

			if (award3dName) award3dName.textContent = file.name;
			if (award3dInfo) award3dInfo.style.display = '';
		});
	}

	function sync3dField() {
		if (!awardCategory || !award3dGroup) return;
		var isAward = awardCategory.value === 'award';
		award3dGroup.hidden = !isAward;
		if (award3dInput) {
			if (!isAward && award3dInput.value !== '') {
				award3dInput.value = '';
				award3dInput.dispatchEvent(new Event('change'));
			}
			award3dInput.disabled = !isAward;
		}
		if (award3dRemove) {
			award3dRemove.disabled = !isAward || !!(award3dInput && award3dInput.files && award3dInput.files.length);
			if (!isAward) award3dRemove.checked = false;
		}
	}

	if (awardCategory) {
		awardCategory.addEventListener('change', sync3dField);
		sync3dField();
	}

	document.querySelectorAll('button[data-confirm]').forEach(function (btn) {
		btn.addEventListener('click', function (e) {
			var msg = this.getAttribute('data-confirm') || 'Are you sure?';
			if (typeof Swal !== 'undefined') {
				e.preventDefault();
				var form = this.closest('form');
				Swal.fire({
					title: 'Confirm Delete',
					text: msg,
					icon: 'warning',
					showCancelButton: true,
					confirmButtonText: 'Yes, delete it',
					cancelButtonText: 'Cancel',
					confirmButtonColor: '#ef4444',
				}).then(function (result) {
					if (result.isConfirmed && form) {
						form.submit();
					}
				});
			} else {
				if (!confirm(msg)) {
					e.preventDefault();
				}
			}
		});
	});

	var awardForm = document.getElementById('awardForm');
	if (awardForm) {
		awardForm.addEventListener('submit', function (e) {
			var category = awardForm.querySelector('[name="award_category"]');
			var title = awardForm.querySelector('[name="award_title"]');
			var para = awardForm.querySelector('[name="award_para"]');
			var img = awardForm.querySelector('[name="award_image"]');
			var sec = awardForm.querySelector('[name="section"]') || awardForm.querySelector('[name="action"]');
			var isEdit = sec && (sec.value === 'award_edit' || sec.value === 'edit');

			if (category && category.value === '') {
				e.preventDefault();
				showNotice('Please select a recognition category.', 'Required Field');
				category.focus();
				return;
			}
			if (title && title.value.trim() === '') {
				e.preventDefault();
				showNotice('Title is required.', 'Required Field');
				title.focus();
				return;
			}
			if (para && para.value.trim() === '') {
				e.preventDefault();
				showNotice('Paragraph is required.', 'Required Field');
				para.focus();
				return;
			}
			if (!isEdit && img && (!img.files || img.files.length === 0)) {
				e.preventDefault();
				showNotice('Please select an image.', 'Required Image');
				img.focus();
				return;
			}
		});
	}
})();