<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Privacy Policy — Creative Web Technologies';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= e($pageTitle) ?></title>
	<meta name="description" content="Creative Web Technologies Privacy Policy — how we collect, process, store, and disclose your personal data.">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
	<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/privacy-policy.css">
	<script>document.documentElement.classList.add('js');</script>
</head>

<body class="pp-page">

<div class="pp-progress" aria-hidden="true">
	<span class="pp-progress-bar" id="ppProgressBar"></span>
</div>

<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<section class="pp-hero" aria-label="Privacy policy hero">
	<div class="pp-hero-bg" aria-hidden="true">
		<span></span>
		<span></span>
		<span></span>
	</div>
	<div class="pp-hero-grid" aria-hidden="true"></div>
	<div class="pp-container">
		<div class="pp-hero-inner">
			<span class="pp-hero-pill">
				<span class="pp-hero-pill-dot"></span>
				Your Privacy Matters
			</span>
			<h1 class="pp-hero-title">
				Privacy <span class="pp-hero-accent">Policy</span>
			</h1>

			<div class="pp-hero-meta">
				<span class="pp-hero-meta-item">
					<i class="fa-regular fa-calendar" aria-hidden="true"></i>
					Last updated: September 1, 2026
				</span>
			</div>
		</div>
	</div>
</section>

<section class="pp-section" aria-label="Privacy policy content">
	<div class="pp-container">
		<div class="pp-layout">

			<aside class="pp-toc" aria-label="Table of contents">
				<div class="pp-toc-inner">
					<span class="pp-toc-label">
						<i class="fa-solid fa-list-ul" aria-hidden="true"></i>
						Contents
					</span>
					<nav class="pp-toc-nav" id="ppTocNav">
						<a href="#pp-intro" class="pp-toc-link is-active" data-target="pp-intro">Introduction</a>
						<a href="#pp-definitions" class="pp-toc-link" data-target="pp-definitions">Definitions</a>
						<a href="#pp-collect" class="pp-toc-link" data-target="pp-collect">What We Collect</a>
						<a href="#pp-why" class="pp-toc-link" data-target="pp-why">Why We Collect</a>
						<a href="#pp-how" class="pp-toc-link" data-target="pp-how">How We Collect</a>
						<a href="#pp-shared" class="pp-toc-link" data-target="pp-shared">Who We Share With</a>
						<a href="#pp-process" class="pp-toc-link" data-target="pp-process">How We Process</a>
						<a href="#pp-security" class="pp-toc-link" data-target="pp-security">Security</a>
						<a href="#pp-rights" class="pp-toc-link" data-target="pp-rights">Your Rights</a>
						<a href="#pp-retention" class="pp-toc-link" data-target="pp-retention">Data Retention</a>
						<a href="#pp-transfer" class="pp-toc-link" data-target="pp-transfer">Data Transfer</a>
						<a href="#pp-providers" class="pp-toc-link" data-target="pp-providers">Service Providers</a>
						<a href="#pp-thirdparty" class="pp-toc-link" data-target="pp-thirdparty">Third-Party Sites</a>
						<a href="#pp-changes" class="pp-toc-link" data-target="pp-changes">Policy Changes</a>
						<a href="#pp-contact" class="pp-toc-link" data-target="pp-contact">Contact Us</a>
					</nav>
				</div>
			</aside>

			<main class="pp-content" id="ppContent">

				<article class="pp-block reveal" id="pp-intro">
					<header class="pp-block-head">
						<span class="pp-block-num">01</span>
						<h2 class="pp-block-title">Introduction</h2>
					</header>
					<p>Creative Web Technologies, referred to as <strong>"the Company," "We," "Us,"</strong> or <strong>"Our"</strong> in this agreement, operates this website. This page informs you of our policies regarding the collection, processing, storage, and disclosure of Personal Data when you use our website and the choices you have associated with that data.</p>
					<p>By using the Website, you agree to the collection and use of information as outlined in this policy. We use your data to provide and improve the Website.</p>
				</article>

				<article class="pp-block reveal" id="pp-definitions">
					<header class="pp-block-head">
						<span class="pp-block-num">02</span>
						<h2 class="pp-block-title">Definitions</h2>
					</header>
					<div class="pp-def-grid">
						<div class="pp-def-item">
							<span class="pp-def-key">Company</span>
							<span class="pp-def-val">Refers to Creative Web Technologies, located at 413, R. A. De Mel Mawatha, Colombo 03, Sri Lanka.</span>
						</div>
						<div class="pp-def-item">
							<span class="pp-def-key">Website</span>
							<span class="pp-def-val">Refers to Creative Web Technologies and its public pages accessible online.</span>
						</div>
						<div class="pp-def-item">
							<span class="pp-def-key">Personal Data</span>
							<span class="pp-def-val">Data about an individual who can be identified from that data.</span>
						</div>
						<div class="pp-def-item">
							<span class="pp-def-key">Usage Data</span>
							<span class="pp-def-val">Data collected automatically by the use of the Website or from the Website infrastructure itself.</span>
						</div>
						<div class="pp-def-item">
							<span class="pp-def-key">Service</span>
							<span class="pp-def-val">Refers to the <a href="<?= SITE_URL ?>/index.php">our website</a>.</span>
						</div>
						<div class="pp-def-item">
							<span class="pp-def-key">You</span>
							<span class="pp-def-val">The individual accessing or using the Service, or the company or other legal entity on behalf of which such individual is accessing or using the Service, as applicable.</span>
						</div>
						<div class="pp-def-item">
							<span class="pp-def-key">Device</span>
							<span class="pp-def-val">Any device that can access the Service, such as a computer, a cell phone, or a digital tablet.</span>
						</div>
						<div class="pp-def-item">
							<span class="pp-def-key">Service Provider</span>
							<span class="pp-def-val">Any natural or legal person who processes data on behalf of the Company — third-party companies or individuals employed by the Company to facilitate the Service.</span>
						</div>
					</div>
				</article>

				<article class="pp-block reveal" id="pp-collect">
					<header class="pp-block-head">
						<span class="pp-block-num">03</span>
						<h2 class="pp-block-title">What Information Do We Collect?</h2>
					</header>
					<p>We collect several different types of information for various purposes to provide and improve our Website to you.</p>

					<div class="pp-sub-block">
						<h3 class="pp-sub-title">Personal Data</h3>
						<p>While using our Website, we may ask you to provide us with certain personally identifiable information that can be used to contact or identify you (<strong>"Personal Data"</strong>). Personally identifiable information may include, but is not limited to:</p>
						<ul class="pp-list">
							<li>Email address</li>
							<li>First and last name</li>
							<li>Phone number</li>
						</ul>
						<p>We may use your data to contact you with newsletters, marketing or promotional materials, and other information that may be of interest to you. You may request to opt-out of receiving any, or all, of these communications from us via email.</p>
					</div>

					<div class="pp-sub-block">
						<h3 class="pp-sub-title">Derivative Data</h3>
						<p>Derivative Data is collected automatically when using the Service. Derivative Data may include information such as your Device's Internet Protocol address (e.g., IP address), browser type, browser version, the pages of our Service that you visit, the time and date of your visit, the time spent on those pages, unique device identifiers, and other diagnostic data.</p>
					</div>

					<div class="pp-sub-block">
						<h3 class="pp-sub-title">Mobile Device Data</h3>
						<p>When you access the Service through a mobile device, we may collect certain information automatically, including, but not limited to, the type of mobile device you use, your mobile device's unique ID, the IP address of your mobile device, your mobile operating system, the type of mobile Internet browser you use, unique device identifiers, and other diagnostic data.</p>
					</div>
				</article>

				<article class="pp-block reveal" id="pp-why">
					<header class="pp-block-head">
						<span class="pp-block-num">04</span>
						<h2 class="pp-block-title">Why Do We Collect Your Data?</h2>
					</header>
					<p>The Company may use Personal Data for the following purposes:</p>
					<ul class="pp-list pp-list-icon">
						<li>To personalise your experience.</li>
						<li>To improve our Website.</li>
						<li>To notify you about changes to our Websites.</li>
						<li>To allow you to participate in interactive features of our Websites when you choose to do so.</li>
						<li>To analyse our marketing campaigns.</li>
						<li>We may track and analyse visitor actions on our website.</li>
					</ul>
				</article>

				<article class="pp-block reveal" id="pp-how">
					<header class="pp-block-head">
						<span class="pp-block-num">05</span>
						<h2 class="pp-block-title">How Do We Collect Your Information?</h2>
					</header>

					<div class="pp-sub-block">
						<h3 class="pp-sub-title">Tracking &amp; Cookie Data</h3>
						<p>We use cookies and similar tracking technologies to track the activity on our Website and hold certain information.</p>
						<p>We use visitor identification technology to understand which companies visit our website. This technology matches your IP address to publicly available company information. We do not identify individual visitors or track personal browsing behavior. This processing is based on your consent, which can be withdrawn at any time via cookie preferences.</p>
						<p>Cookies are files with a small amount of data that may include an anonymous unique identifier. Cookies are sent to your browser from a website and stored on your device. Tracking technologies also used are beacons, tags, and scripts to collect and track information and to improve and analyse our Website. You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent.</p>
					</div>

					<div class="pp-cookie-grid">
						<div class="pp-cookie-card">
							<span class="pp-cookie-icon"><i class="fa-solid fa-bolt" aria-hidden="true"></i></span>
							<h4 class="pp-cookie-title">Session Cookies</h4>
							<p class="pp-cookie-text">We use session cookies to operate our website.</p>
						</div>
						<div class="pp-cookie-card">
							<span class="pp-cookie-icon"><i class="fa-solid fa-sliders" aria-hidden="true"></i></span>
							<h4 class="pp-cookie-title">Preference Cookies</h4>
							<p class="pp-cookie-text">We use preference cookies to remember your preferences and various settings.</p>
						</div>
						<div class="pp-cookie-card">
							<span class="pp-cookie-icon"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i></span>
							<h4 class="pp-cookie-title">Security Cookies</h4>
							<p class="pp-cookie-text">We use security cookies for security purposes.</p>
						</div>
					</div>
				</article>

				<article class="pp-block reveal" id="pp-shared">
					<header class="pp-block-head">
						<span class="pp-block-num">06</span>
						<h2 class="pp-block-title">Who Is Your Information Shared With?</h2>
					</header>

					<div class="pp-sub-block">
						<h3 class="pp-sub-title">Business Transactions</h3>
						<p>If the Company is involved in a merger, acquisition, or asset sale, your Personal Data may be transferred. We will provide notice before your Personal Data is transferred and becomes subject to a different Privacy Policy.</p>
					</div>

					<div class="pp-sub-block">
						<h3 class="pp-sub-title">Disclosure for Law Enforcement</h3>
						<p>Under certain circumstances, we may be required to disclose your Personal Data if required to do so by law or in response to valid requests by public authorities (e.g., a court or a government agency).</p>
					</div>

					<div class="pp-sub-block">
						<h3 class="pp-sub-title">Other Legal Requirements</h3>
						<p>The Company may disclose your Personal Data in the good faith belief that such action is necessary to:</p>
						<ul class="pp-list pp-list-icon">
							<li>Comply with a legal obligation</li>
							<li>Protect and defend the rights or property of the Company</li>
							<li>Prevent or investigate possible wrongdoing in connection with the Service</li>
							<li>Protect the personal safety of users of the Service or the public</li>
							<li>Protect against legal liability</li>
						</ul>
					</div>
				</article>

				<article class="pp-block reveal" id="pp-process">
					<header class="pp-block-head">
						<span class="pp-block-num">07</span>
						<h2 class="pp-block-title">How Do We Process Your Data?</h2>
					</header>
					<p>We will only collect information as defined in this policy. Where we rely on your consent to process personal data, you have the right to withdraw or decline your consent at any time. Where we rely on legitimate interests, you have the right to object.</p>
				</article>

				<article class="pp-block reveal" id="pp-security">
					<header class="pp-block-head">
						<span class="pp-block-num">08</span>
						<h2 class="pp-block-title">Security of Your Data</h2>
					</header>
					<p>The security of your data is important to us. While we strive to use commercially acceptable means to protect your data, we cannot guarantee its absolute security as no method of transmission over the Internet or method of electronic storage is 100% secure.</p>
				</article>

				<article class="pp-block reveal" id="pp-rights">
					<header class="pp-block-head">
						<span class="pp-block-num">09</span>
						<h2 class="pp-block-title">Your Rights to Your Data</h2>
					</header>
					<p>If you wish to correct, amend, delete, or limit the use of your Personal Data, please contact us. If you wish to be informed about the Personal Data we hold about you or request its removal from our systems, please contact us.</p>
					<p>In certain circumstances, you have the right:</p>
					<ul class="pp-list pp-list-icon">
						<li>To receive a copy of the personal data we hold about you</li>
						<li>To rectify any personal data held about you that is inaccurate</li>
						<li>To request the deletion of personal data held about you</li>
					</ul>
					<p>You have the right to data portability for the information you provide to us. You can request to obtain a copy of your Personal Data by contacting us. Please note that we may ask you to verify your identity before responding to such requests.</p>
				</article>

				<article class="pp-block reveal" id="pp-retention">
					<header class="pp-block-head">
						<span class="pp-block-num">10</span>
						<h2 class="pp-block-title">How Long Do We Store Your Data?</h2>
					</header>
					<p>The Company will retain your Personal Data only for as long as necessary for the purposes set out in this Privacy Policy. We will retain and use your Personal Data to the extent necessary to comply with our legal obligations, resolve disputes, enforce our legal agreements and policies, and for internal analysis purposes.</p>
				</article>

				<article class="pp-block reveal" id="pp-transfer">
					<header class="pp-block-head">
						<span class="pp-block-num">11</span>
						<h2 class="pp-block-title">How Do We Transfer Your Personal Data?</h2>
					</header>
					<p>Your information, including Personal Data, may be processed at the Company's operating offices and in any other places where the parties involved in the processing are located. This information may be transferred to and maintained on computers located outside of your state, province, country, or other governmental jurisdiction, where data protection laws may differ from those of your jurisdiction.</p>
					<p>Your consent to this Privacy Policy followed by your submission of such information represents your agreement to the transfer. The Company will take all steps reasonably necessary to ensure that your data is treated securely and in accordance with this Privacy Policy. No transfer of your Personal Data will take place to an organisation or a country unless there are adequate controls in place, including the security of your data and other personal information.</p>
				</article>

				<article class="pp-block reveal" id="pp-providers">
					<header class="pp-block-head">
						<span class="pp-block-num">12</span>
						<h2 class="pp-block-title">Our Service Providers</h2>
					</header>
					<p>We may engage third-party companies and individuals to facilitate our Website (<strong>"Service Providers"</strong>), provide the Website on our behalf, perform Website-related services, or assist us in analysing how our Website is used. These third parties have access to your Personal Data only to perform these tasks on our behalf and are obligated not to disclose or use it for any other purpose.</p>

					<div class="pp-callout">
						<span class="pp-callout-icon"><i class="fa-brands fa-google" aria-hidden="true"></i></span>
						<div class="pp-callout-body">
							<h4 class="pp-callout-title">Google Analytics</h4>
							<p class="pp-callout-text">Google Analytics is a web analytics service offered by Google that tracks and reports website traffic. Google uses the data collected to track and monitor the use of our Website. This data is shared with other Google services. Google may use the collected data to contextualise and personalise the ads of its advertising network. For more information on the privacy practices of Google, please visit the <a href="http://www.google.com/intl/en/policies/privacy/" target="_blank" rel="noopener noreferrer">Google Privacy &amp; Terms web page</a>.</p>
						</div>
					</div>
				</article>

				<article class="pp-block reveal" id="pp-thirdparty">
					<header class="pp-block-head">
						<span class="pp-block-num">13</span>
						<h2 class="pp-block-title">Third-Party Websites and Services</h2>
					</header>
					<p>Our Service may contain links to other websites that are not operated by us. If you click on a third-party link, you will be directed to that third party's site. We strongly advise you to review the Privacy Policy of every site you visit. We have no control over and assume no responsibility for the content, privacy policies, or practices of any third-party sites or services.</p>
				</article>

				<article class="pp-block reveal" id="pp-changes">
					<header class="pp-block-head">
						<span class="pp-block-num">14</span>
						<h2 class="pp-block-title">Changes to This Privacy Policy</h2>
					</header>
					<p>We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page. The changes will become effective upon posting the revised Privacy Policy. The <strong>"Last Updated"</strong> date at the top of this Privacy Policy will indicate the date of the changes.</p>
					<p>We encourage you to review this Privacy Policy periodically for any changes.</p>
				</article>

				<article class="pp-block reveal" id="pp-contact">
					<header class="pp-block-head">
						<span class="pp-block-num">15</span>
						<h2 class="pp-block-title">Contact Us</h2>
					</header>
					<p>If you have any questions about this Privacy Policy, you can contact us at:</p>

					<div class="pp-contact-grid">
						<div class="pp-contact-card">
							<span class="pp-contact-icon"><i class="fa-solid fa-building" aria-hidden="true"></i></span>
							<div class="pp-contact-body">
								<span class="pp-contact-label">Company</span>
								<span class="pp-contact-value">Creative Web Technologies</span>
							</div>
                        </div>
						<a class="pp-contact-card" href="https://maps.google.com/?q=413+R+A+De+Mel+Mawatha+Colombo+03+Sri+Lanka" target="_blank" rel="noopener noreferrer">
							<span class="pp-contact-icon"><i class="fa-solid fa-location-dot" aria-hidden="true"></i></span>
							<div class="pp-contact-body">
								<span class="pp-contact-label">Address</span>
								<span class="pp-contact-value">No. 413, R. A. De Mel Mawatha, Colombo 03, Sri Lanka.,</span>
							</div>
						</a>
						<a class="pp-contact-card" href="<?= SITE_URL ?>/index.php">
							<span class="pp-contact-icon"><i class="fa-solid fa-globe" aria-hidden="true"></i></span>
							<div class="pp-contact-body">
								<span class="pp-contact-label">Website</span>
								<span class="pp-contact-value"><?= e(parse_url(SITE_URL, PHP_URL_HOST)) ?></span>
							</div>
						</a>
						<a class="pp-contact-card" href="mailto:kolitha@creativesoftware.com">
							<span class="pp-contact-icon"><i class="fa-solid fa-envelope" aria-hidden="true"></i></span>
							<div class="pp-contact-body">
								<span class="pp-contact-label">Email</span>
								<span class="pp-contact-value">kolitha@creativesoftware.com</span>
							</div>
						</a>
						<a class="pp-contact-card" href="tel:+94777256682">
							<span class="pp-contact-icon"><i class="fa-solid fa-phone" aria-hidden="true"></i></span>
							<div class="pp-contact-body">
								<span class="pp-contact-label">Phone</span>
								<span class="pp-contact-value">+94 77 725 6682</span>
							</div>
						</a>
						<a class="pp-contact-card" href="<?= SITE_URL ?>/contact.php">
							<span class="pp-contact-icon"><i class="fa-solid fa-comment-dots" aria-hidden="true"></i></span>
							<div class="pp-contact-body">
								<span class="pp-contact-label">Questions</span>
								<span class="pp-contact-value">Send us a message</span>
							</div>
						</a>
					</div>
				</article>

			</main>

		</div>
	</div>
</section>

<button type="button" class="pp-top" id="ppTop" aria-label="Back to top">
	<i class="fa-solid fa-chevron-up" aria-hidden="true"></i>
</button>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<script src="<?= SITE_URL ?>/assets/js/privacy-policy.js"></script>
</body>

</html>