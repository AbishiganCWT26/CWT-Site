		</div>
	</main>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function() {
	var toggle = document.getElementById('sidebarToggle');
	var sidebar = document.getElementById('adminSidebar');
	if (toggle && sidebar) {
		toggle.addEventListener('click', function(e) {
			e.stopPropagation();
			sidebar.classList.toggle('open');
		});
		document.addEventListener('click', function(e) {
			if (window.innerWidth <= 991 && sidebar.classList.contains('open')) {
				if (!sidebar.contains(e.target) && e.target !== toggle) {
					sidebar.classList.remove('open');
				}
			}
		});
	}
	window.SwalDefaults = {
		customClass: { popup: 'cwt-swal' },
		buttonsStyling: true,
		confirmButtonColor: '#001be4',
		cancelButtonColor: '#e2e8f0',
		reverseButtons: true,
		didOpen: function(popup) {
			popup.style.margin = 'auto';
		}
	};
	window.cwtAlert = function(title, text, icon) {
		icon = icon || 'info';
		return Swal.fire(Object.assign({}, window.SwalDefaults, { title: title, text: text, icon: icon, confirmButtonText: 'OK' }));
	};
	window.cwtConfirm = function(opts) {
		return Swal.fire(Object.assign({}, window.SwalDefaults, {
			title: opts.title || 'Are you sure?',
			text: opts.text || '',
			icon: opts.icon || 'warning',
			showCancelButton: true,
			confirmButtonText: opts.confirmText || 'Yes',
			cancelButtonText: opts.cancelText || 'Cancel'
		}));
	};
	document.querySelectorAll('[data-confirm]').forEach(function(el) {
		el.addEventListener('click', function(e) {
			var msg = el.getAttribute('data-confirm');
			var form = el.closest('form');
			if (!form) return;
			e.preventDefault();
			window.cwtConfirm({ text: msg, confirmText: 'Delete', icon: 'warning' }).then(function(r) {
				if (r.isConfirmed) form.submit();
			});
		});
	});
	var flash = document.querySelector('.alert[data-auto-dismiss]');
	if (flash) {
		var delay = parseInt(flash.getAttribute('data-auto-dismiss'), 10) || 3000;
		setTimeout(function() {
			flash.style.transition = 'opacity .3s ease, transform .3s ease';
			flash.style.opacity = '0';
			flash.style.transform = 'translateY(-8px)';
			setTimeout(function() { flash.remove(); }, 300);
		}, delay);
	}
})();
</script>
</body>
</html>