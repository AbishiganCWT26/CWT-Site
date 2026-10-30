		</div>
	</main>
</div>

<?php
$cwtFlash = function_exists('getFlash') ? getFlash() : null;
if ($cwtFlash):
?>
<script>
window.CWT_FLASH = <?= json_encode($cwtFlash, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
if (typeof window.triggerFlashAlert === 'function') {
	window.triggerFlashAlert(window.CWT_FLASH);
}
</script>
<?php endif; ?>
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

	document.addEventListener('contextmenu', function(e) {
		if (e.target.tagName === 'IMG' || e.target.closest('img')) e.preventDefault();
	}, true);
	document.addEventListener('dragstart', function(e) {
		if (e.target.tagName === 'IMG' || e.target.closest('img')) e.preventDefault();
	}, true);
	document.querySelectorAll('img').forEach(function(img) {
		img.setAttribute('draggable', 'false');
	});
})();
</script>
</body>
</html>
