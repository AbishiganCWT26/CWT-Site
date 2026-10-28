<?php
$footer = getFooter($pdo);
$fp = array_map('htmlspecialchars', array_map('strval', $footer));
require __DIR__ . '/closing-cta.php';

/*
 * Optional per-site theme stored with the footer settings.
 * 'light' / 'dark' = force it. Anything else = follow the OS preference.
 */
$footerTheme = strtolower(trim((string) ($footer['theme'] ?? '')));
$footerThemeAttr = in_array($footerTheme, ['light', 'dark'], true)
    ? ' data-theme="' . $footerTheme . '"'
    : '';
?>

<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/footer.css?v=<?= filemtime(__DIR__ . '/../assets/css/footer.css') ?>">

<footer class="cwt-footer"<?= $footerThemeAttr ?> role="contentinfo">
	<!-- Animated background: particles.js canvas -->
	<div id="cwt-particles" class="cwt-footer-particles" aria-hidden="true"></div>

	<div class="cwt-footer-main">
		<div class="cwt-footer-container">
			<div class="cwt-footer-grid">
				<div class="cwt-brand">
					<div class="cwt-brand-head">
						<img class="cwt-brand-logo cwt-logo-image" src="<?= SITE_URL ?>/assets/logos/Creativelogo_Blue.png" alt="" width="48" height="40" loading="lazy">
						<div class="cwt-brand-title">
							Creative Web Technologies
							<span>Expert Tech Teams</span>
						</div>
					</div>
					<p class="cwt-brand-para">
						<?= nl2br($fp['para'] ?? 'Creative Web Technologies builds high-performance offshore development teams to bring big ideas to life.') ?>
					</p>
					<div class="cwt-socials">
						<?php if (!empty($footer['linkedin_url'])): ?>
						<a href="<?= $fp['linkedin_url'] ?>" target="_blank" rel="noopener" class="cwt-social" aria-label="LinkedIn">
							<svg viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
						</a>
						<?php endif; ?>
						<?php if (!empty($footer['facebook_url'])): ?>
						<a href="<?= $fp['facebook_url'] ?>" target="_blank" rel="noopener" class="cwt-social" aria-label="Facebook">
							<svg viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
						</a>
						<?php endif; ?>
						<?php if (!empty($footer['youtube_url'])): ?>
						<a href="<?= $fp['youtube_url'] ?>" target="_blank" rel="noopener" class="cwt-social" aria-label="YouTube">
							<svg viewBox="0 0 24 24"><path d="M23.495 6.205a3.007 3.007 0 0 0-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 0 0 .527 6.205a31.247 31.247 0 0 0-.522 5.805 31.247 31.247 0 0 0 .522 5.783 3.007 3.007 0 0 0 2.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 0 0 2.088-2.088 31.247 31.247 0 0 0 .5-5.783 31.247 31.247 0 0 0-.5-5.805zM9.609 15.601V8.408l6.264 3.602z"/></svg>
						</a>
						<?php endif; ?>
						<?php if (!empty($footer['instagram_url'])): ?>
						<a href="<?= $fp['instagram_url'] ?>" target="_blank" rel="noopener" class="cwt-social" aria-label="Instagram">
							<svg viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
						</a>
						<?php endif; ?>
					</div>
				</div>

				<div class="cwt-col">
					<h3 class="cwt-col-heading">Quick Links</h3>
					<nav class="cwt-links" aria-label="Footer navigation">
						<a href="<?= SITE_URL ?>/services.php">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
							<span>Our Services</span>
						</a>
						<a href="<?= SITE_URL ?>/about.php">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
							<span>About Us</span>
						</a>
						<a href="<?= SITE_URL ?>/our-crew.php">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
							<span>Our Crew</span>
						</a>
						<a href="<?= SITE_URL ?>/products.php">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
							<span>Product Portfolio</span>
						</a>
						<a href="<?= SITE_URL ?>/clients-say.php">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
							<span>Clients Say</span>
						</a>
						<a href="<?= SITE_URL ?>/insights.php">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
							<span>Insights</span>
						</a>
						<a href="<?= SITE_URL ?>/privacy-policy.php">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
							<span>Privacy policy</span>
						</a>
					</nav>
				</div>

				<div class="cwt-col">
					<h3 class="cwt-col-heading">Contact Us</h3>
					<div class="cwt-contacts">
						<?php if (!empty($footer['email'])): ?>
						<a href="mailto:<?= $fp['email'] ?>" class="cwt-contact">
							<span class="cwt-contact-icon">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/></svg>
							</span>
							<span class="cwt-contact-body">
								<span class="cwt-contact-value"><?= $fp['email'] ?></span>
							</span>
						</a>
						<?php endif; ?>
						<?php if (!empty($footer['phone'])): ?>
						<a href="tel:<?= preg_replace('/\s+/', '', $fp['phone']) ?>" class="cwt-contact">
							<span class="cwt-contact-icon">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.6 17.6 0 0 0 4.168 6.608 17.6 17.6 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.68.68 0 0 0-.58-.122l-2.19.547a1.75 1.75 0 0 1-1.657-.459L5.482 8.062a1.75 1.75 0 0 1-.46-1.657l.548-2.19a.68.68 0 0 0-.122-.58z"/></svg>
							</span>
							<span class="cwt-contact-body">
								<span class="cwt-contact-value"><?= $fp['phone'] ?></span>
							</span>
						</a>
						<?php endif; ?>
						<?php if (!empty($footer['address'])): ?>
						<a href="https://maps.app.goo.gl/Fvz6AdSDUzZ2B14u8">
							<div class="cwt-contact">
								<span class="cwt-contact-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6"/></svg>
								</span>
								<span class="cwt-contact-body">
									<span class="cwt-contact-value"><?= nl2br($fp['address']) ?></span>
								</span>
							</div>
						</a>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<!-- Bottom bar now lives inside .cwt-footer-main, in the same container as the grid -->
			<div class="cwt-footer-bottom">
				<div class="cwt-footer-bottom-inner">
					<p class="cwt-copy">© Copyright <?= date('Y') ?> by <strong>Creative Web Technologies</strong>. All Rights Reserved.</p>
				</div>
			</div>
		</div>
	</div>
</footer>

<button type="button" class="cwt-back-top" id="cwtBackTop" aria-label="Back to top">
	<svg viewBox="0 0 24 24"><path d="m18 15-6-6-6 6"/></svg>
</button>

<!-- Shared page motion: typography, fluid navigation, card effects and slider. -->
<script src="<?= SITE_URL ?>/assets/js/interactions.js?v=<?= filemtime(__DIR__ . '/../assets/js/interactions.js') ?>" defer></script>
<script src="<?= SITE_URL ?>/assets/js/navy-art.js?v=<?= filemtime(__DIR__ . '/../assets/js/navy-art.js') ?>" defer></script>
<!-- particles.js must load before footer.js (both deferred = executed in order) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js" defer></script>
<script src="<?= SITE_URL ?>/assets/js/footer.js?v=<?= filemtime(__DIR__ . '/../assets/js/footer.js') ?>" defer></script>