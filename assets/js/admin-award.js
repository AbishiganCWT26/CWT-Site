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

				var isImage = (file.type && file.type.indexOf('image/') === 0) ||
					/\.(jpe?g|png|gif|webp|svg)$/i.test(file.name);
				if (!isImage) {
					showNotice('Please select a JPG, PNG, GIF, WebP, or SVG image.', 'Invalid Format', 'error');
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
			var title = awardForm.querySelector('[name="award_title"]');
			var para = awardForm.querySelector('[name="award_para"]');
			var img = awardForm.querySelector('[name="award_image"]');
			var sec = awardForm.querySelector('[name="section"]') || awardForm.querySelector('[name="action"]');
			var isEdit = sec && (sec.value === 'award_edit' || sec.value === 'edit');

			if (title && title.value.trim() === '') {
				e.preventDefault();
				showNotice('Award title is required.', 'Required Field');
				title.focus();
				return;
			}
			if (para && para.value.trim() === '') {
				e.preventDefault();
				showNotice('Award paragraph is required.', 'Required Field');
				para.focus();
				return;
			}
				if (!isEdit && img && (!img.files || img.files.length === 0)) {
					e.preventDefault();
					showNotice('Please select an award image.', 'Required Image');
					img.focus();
					return;
				}
			});
	}
})();
