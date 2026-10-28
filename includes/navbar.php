<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$currentPage = ['project-detail.php' => 'products.php', 'insight-detail.php' => 'insights.php'][$currentPage] ?? $currentPage;
?>


<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/navbar.css?v=<?= filemtime(__DIR__ . '/../assets/css/navbar.css') ?>">
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
			<a href="<?= SITE_URL ?>/contact" class="cwt-cta">
				<span>Let's Talk</span>
                <svg class="cwt-cta-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17 17 7M7 7h10v10"/></svg>
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

		var navbar = document.getElementById('cwtNavbar');
		var burger = document.getElementById('cwtBurger');
		var mobile = document.getElementById('cwtMobile');

		if (!navbar || !burger || !mobile) return;

		var onScroll = function () {
			navbar.classList.toggle('is-scrolled', window.scrollY > 60);
		};

		onScroll();
		window.addEventListener('scroll', onScroll, { passive: true });

		var closeMenu = function () {
			burger.classList.remove('is-active');
			mobile.classList.remove('is-open');
			burger.setAttribute('aria-expanded', 'false');
mobile.inert = true;
		};

		var openMenu = function () {
			burger.classList.add('is-active');
			mobile.classList.add('is-open');
			burger.setAttribute('aria-expanded', 'true');
mobile.inert = false;
		};

		burger.addEventListener('click', function (e) {
			e.stopPropagation();
			if (mobile.classList.contains('is-open')) {
				closeMenu();
			} else {
				openMenu();
			}
		});

		mobile.querySelectorAll('a').forEach(function (link) {
			link.addEventListener('click', function () {
				closeMenu();
			});
		});

		document.addEventListener('click', function (e) {
			if (!navbar.contains(e.target)) {
				closeMenu();
			}
		});

		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && mobile.classList.contains('is-open')) {
burger.focus();
				closeMenu();
			}
		});

		closeMenu();
window.addEventListener('resize', function () { if (window.innerWidth > 960) closeMenu(); });
var links = document.querySelectorAll('.cwt-nav-link, .cwt-mobile-link');
		var currentPath = window.location.pathname.split('/').pop() || 'index.php';

		links.forEach(function (link) {
			var href = link.getAttribute('href') || '';
			var file = href.split('/').pop();

			if (file && file === currentPath) {
				link.classList.add('is-active'); link.setAttribute('aria-current', 'page');
			} else if (file && file.replace('.php', '') && currentPath.indexOf(file.replace('.php', '')) !== -1) {
				link.classList.add('is-active'); link.setAttribute('aria-current', 'page');
			}
		});
	})();
</script>
