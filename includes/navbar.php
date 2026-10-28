<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$currentPage = ['project-detail.php' => 'products.php', 'insight-detail.php' => 'insights.php'][$currentPage] ?? $currentPage;
?>
<script>
/* Apply saved theme before CSS paints to avoid a flash. */
(function () {
	try {
		var saved = localStorage.getItem('cwt-theme');
		var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
		if (saved === 'dark' || (!saved && prefersDark)) {
			document.documentElement.setAttribute('data-theme', 'dark');
		}
	} catch (e) {}
})();
</script>

<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/navbar.css?v=<?= filemtime(__DIR__ . '/assets/css/navbar.css') ?>">
<a class="cwt-skip-link" href="#main-content">Skip to content</a>
<nav class="cwt-navbar" id="cwtNavbar" aria-label="Main navigation">
	<div class="cwt-navbar-inner">

		<a href="<?= SITE_URL ?>/index.php" class="cwt-logo" aria-label="Creative Web Technologies home">
			<img class="cwt-logo-icon cwt-logo-image" src="<?= SITE_URL ?>/assets/logos/Creativelogo_Blue.png" alt="" width="48" height="40">
			<span class="cwt-logo-text">
				Creative Web
				<span>Technologies</span>
			</span>
		</a>

		<ul class="cwt-nav">
			<li>
				<a href="<?= SITE_URL ?>/index.php" class="cwt-nav-link <?= $currentPage === 'index.php' ? 'is-active' : '' ?>">Home</a>
			</li>
			<li>
				<a href="<?= SITE_URL ?>/services.php" class="cwt-nav-link <?= $currentPage === 'services.php' ? 'is-active' : '' ?>">Our Services</a>
			</li>
			<li>
				<a href="<?= SITE_URL ?>/about.php" class="cwt-nav-link <?= $currentPage === 'about.php' ? 'is-active' : '' ?>">About Us</a>
			</li>
			<li>
				<a href="<?= SITE_URL ?>/our-crew.php" class="cwt-nav-link <?= $currentPage === 'our-crew.php' ? 'is-active' : '' ?>">Our Crew</a>
			</li>
			<li>
				<a href="<?= SITE_URL ?>/products.php" class="cwt-nav-link <?= $currentPage === 'products.php' ? 'is-active' : '' ?>">Our Products</a>
			</li>
			<li>
				<a href="<?= SITE_URL ?>/clients-say.php" class="cwt-nav-link <?= $currentPage === 'clients-say.php' ? 'is-active' : '' ?>">Clients Say</a>
			</li>
			<li>
				<a href="<?= SITE_URL ?>/insights.php" class="cwt-nav-link <?= $currentPage === 'insights.php' ? 'is-active' : '' ?>">Insights</a>
			</li>
		</ul>

		<div class="cwt-nav-actions">
			<button type="button" class="cwt-theme-toggle" id="cwtThemeToggle" aria-label="Switch to dark mode" aria-pressed="false" title="Switch to dark mode">
				<svg class="cwt-theme-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M20.5 14.5A9 9 0 0 1 9.5 3.5a9 9 0 1 0 11 11Z"/></svg>
				<svg class="cwt-theme-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2M2 12h2m16 0h2M5 5l1.5 1.5m11 11L19 19M5 19l1.5-1.5m11-11L19 5"/></svg>
			</button>
			<a href="<?= SITE_URL ?>/contact.php" class="cwt-cta">
				<span>Let's Talk</span>
			</a>

			<button type="button" class="cwt-burger" id="cwtBurger" aria-label="Toggle menu" aria-expanded="false" aria-controls="cwtMobile">
				<span></span>
				<span></span>
				<span></span>
			</button>
		</div>

	</div>

	<div class="cwt-mobile" id="cwtMobile" aria-label="Mobile navigation">
		<a href="<?= SITE_URL ?>/index.php" class="cwt-mobile-link <?= $currentPage === 'index.php' ? 'is-active' : '' ?>">Home</a>
		<a href="<?= SITE_URL ?>/services.php" class="cwt-mobile-link <?= $currentPage === 'services.php' ? 'is-active' : '' ?>">Our Services</a>
		<a href="<?= SITE_URL ?>/about.php" class="cwt-mobile-link <?= $currentPage === 'about.php' ? 'is-active' : '' ?>">About Us</a>
		<a href="<?= SITE_URL ?>/our-crew.php" class="cwt-mobile-link <?= $currentPage === 'our-crew.php' ? 'is-active' : '' ?>">Our Crew</a>
		<a href="<?= SITE_URL ?>/products.php" class="cwt-mobile-link <?= $currentPage === 'products.php' ? 'is-active' : '' ?>">Our Products</a>
		<a href="<?= SITE_URL ?>/clients-say.php" class="cwt-mobile-link <?= $currentPage === 'clients-say.php' ? 'is-active' : '' ?>">Clients Say</a>
		<a href="<?= SITE_URL ?>/insights.php" class="cwt-mobile-link <?= $currentPage === 'insights.php' ? 'is-active' : '' ?>">Insights</a>
		<div class="cwt-mobile-divider" aria-hidden="true"></div>
		<a href="<?= SITE_URL ?>/contact.php" class="cwt-mobile-cta">
			<span>Let's Talk</span>
		</a>
	</div>
</nav>

<script>
(function () {
	'use strict';

	var root = document.documentElement;

	/* ============================================================
   1. THEME TOGGLE  (single, capture-phase, duplicate-proof)
   ============================================================ */
	var themeToggle = document.getElementById('cwtThemeToggle');

	function isDark() {
		return root.getAttribute('data-theme') === 'dark';
	}

	function syncThemeButton() {
		if (!themeToggle) return;
		var dark = isDark();
		themeToggle.setAttribute('aria-pressed', dark ? 'true' : 'false');
		themeToggle.setAttribute('aria-label', dark ? 'Switch to light mode' : 'Switch to dark mode');
		themeToggle.setAttribute('title', dark ? 'Switch to light mode' : 'Switch to dark mode');
	}

	function applyTheme(mode) {
		if (mode === 'dark') {
			root.setAttribute('data-theme', 'dark');
		} else {
			root.removeAttribute('data-theme');
		}
		try { localStorage.setItem('cwt-theme', mode); } catch (e) {}
		syncThemeButton();
		document.dispatchEvent(new CustomEvent('cwt-theme-change', { detail: { theme: mode } }));
	}

	syncThemeButton();

	if (!window.__cwtThemeBound) {
		window.__cwtThemeBound = true;
		document.addEventListener('click', function (e) {
			var btn = e.target.closest && e.target.closest('#cwtThemeToggle');
			if (!btn) return;
			e.preventDefault();
			e.stopImmediatePropagation(); // blocks any other script also toggling
			applyTheme(isDark() ? 'light' : 'dark');
		}, true);
	}

	/* Cross-tab sync */
	window.addEventListener('storage', function (e) {
		if (e.key === 'cwt-theme') {
			if (e.newValue === 'dark') {
				root.setAttribute('data-theme', 'dark');
			} else {
				root.removeAttribute('data-theme');
			}
			syncThemeButton();
		}
	});

	/* ============================================================
	   2. NAVBAR & MOBILE MENU  (own guard — never blocks theme)
	   ============================================================ */
	var navbar = document.getElementById('cwtNavbar');
	var burger = document.getElementById('cwtBurger');
	var mobile = document.getElementById('cwtMobile');

	if (navbar && burger && mobile) {
		var onScroll = function () {
			navbar.classList.toggle('is-scrolled', window.scrollY > 60);
		};
		onScroll();
		window.addEventListener('scroll', onScroll, { passive: true });

		var setInert = function (el, val) {
			try { el.inert = val; } catch (e) {}
		};

		var closeMenu = function () {
			burger.classList.remove('is-active');
			mobile.classList.remove('is-open');
			burger.setAttribute('aria-expanded', 'false');
			setInert(mobile, true);
		};

		var openMenu = function () {
			burger.classList.add('is-active');
			mobile.classList.add('is-open');
			burger.setAttribute('aria-expanded', 'true');
			setInert(mobile, false);
		};

		burger.addEventListener('click', function (e) {
			e.stopPropagation();
			if (mobile.classList.contains('is-open')) closeMenu(); else openMenu();
		});

		mobile.querySelectorAll('a').forEach(function (link) {
			link.addEventListener('click', closeMenu);
		});

		document.addEventListener('click', function (e) {
			if (!navbar.contains(e.target)) closeMenu();
		});

		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && mobile.classList.contains('is-open')) {
				burger.focus();
				closeMenu();
			}
		});

		closeMenu();
		window.addEventListener('resize', function () {
			if (window.innerWidth > 960) closeMenu();
		});
	}

	/* ============================================================
	   3. ACTIVE LINK HIGHLIGHT  (independent)
	   ============================================================ */
	var links = document.querySelectorAll('.cwt-nav-link, .cwt-mobile-link');
	var currentPath = window.location.pathname.split('/').pop() || 'index.php';

	links.forEach(function (link) {
		var href = link.getAttribute('href') || '';
		var file = href.split('/').pop();

		if (file && file === currentPath) {
			link.classList.add('is-active');
			link.setAttribute('aria-current', 'page');
		} else if (file && file.replace('.php', '') && currentPath.indexOf(file.replace('.php', '')) !== -1) {
			link.classList.add('is-active');
			link.setAttribute('aria-current', 'page');
		}
	});
})();
</script>