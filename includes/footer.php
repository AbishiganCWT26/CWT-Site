<?php
$footer = getFooter($pdo);
$fp = array_map('htmlspecialchars', array_map('strval', $footer));
?>
<style>
	@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

	:root {
		--primary: #001be4;
		--primary-dark: #0050b8;
		--primary-light: #1a66ff;
		--primary-glow: rgba(34, 0, 228, 0.15);

		--white: #FFFFFF;
		--off-white: #F9F9F9;
		--light-grey: #F0F0F0;
		--light-pink: #7ea0f8ff;
		--border: #E8E8E8;

		--text-primary: #1A1A1A;
		--text-secondary: #333333;
		--text-muted: #666666;
		--text-light: #999999;

		--footer-bg: #2C2C2C;
		--footer-text: #B0B0B0;

		--card-bg: #FFFFFF;
		--card-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
		--card-shadow-hover: 0 12px 40px rgba(228, 0, 70, 0.15);

		--radius-sm: 6px;
		--radius: 12px;
		--radius-lg: 20px;
		--radius-full: 9999px;

		--transition: 0.3s ease;
		--transition-slow: 0.5s ease;

		--navbar-height: 72px;
		--section-pad: 90px;

		--v-blue-500: #2b7bff;
		--v-blue-400: #5b9cff;
		--v-blue-600: #1557c4;
		--v-blue-700: #0f3b8a;
		--v-ink: #0d2a5c;
		--v-ink-soft: #1d3f7d;
		--v-muted: #5b769f;
		--v-label: #1557c4;
		--v-card-bg: #ffffff;
		--v-panel-bg: #f4f8ff;
		--v-line: rgba(43, 123, 255, 0.18);
	}

	.cwt-footer {
		position: relative;
		background: linear-gradient(160deg, #0a1230 0%, #071a4a 45%, #04102e 100%);
		color: var(--footer-text);
		padding: 96px 0 0;
		overflow: hidden;
		isolation: isolate;
		font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
	}

	.cwt-footer *,
	.cwt-footer *::before,
	.cwt-footer *::after {
		box-sizing: border-box;
	}

	.cwt-footer-orb {
		position: absolute;
		border-radius: 50%;
		filter: blur(90px);
		opacity: 0.35;
		pointer-events: none;
		z-index: 0;
		animation: cwtOrbFloat 16s ease-in-out infinite;
	}

	.cwt-footer-orb-1 {
		width: 520px;
		height: 520px;
		background: radial-gradient(circle, var(--primary-light), transparent 70%);
		top: -220px;
		left: -120px;
	}

	.cwt-footer-orb-2 {
		width: 420px;
		height: 420px;
		background: radial-gradient(circle, var(--light-pink), transparent 70%);
		bottom: -180px;
		right: -100px;
		animation-delay: -6s;
	}

	.cwt-footer-orb-3 {
		width: 340px;
		height: 340px;
		background: radial-gradient(circle, var(--v-blue-500), transparent 70%);
		top: 40%;
		left: 45%;
		opacity: 0.22;
		animation-delay: -10s;
	}

	@keyframes cwtOrbFloat {
		0%, 100% {
			transform: translate(0, 0) scale(1);
		}
		33% {
			transform: translate(40px, -30px) scale(1.08);
		}
		66% {
			transform: translate(-30px, 25px) scale(0.96);
		}
	}

	.cwt-footer-grid-lines {
		position: absolute;
		inset: 0;
		background-image:
			linear-gradient(rgba(255, 255, 255, 0.028) 1px, transparent 1px),
			linear-gradient(90deg, rgba(255, 255, 255, 0.028) 1px, transparent 1px);
		background-size: 68px 68px;
		mask-image: radial-gradient(ellipse at 50% 0%, black 30%, transparent 80%);
		-webkit-mask-image: radial-gradient(ellipse at 50% 0%, black 30%, transparent 80%);
		z-index: 0;
		pointer-events: none;
	}

	.cwt-footer-container {
		max-width: 1240px;
		margin: 0 auto;
		padding: 0 24px;
		position: relative;
		z-index: 1;
		width: 100%;
	}

	.cwt-footer-grid {
		display: grid;
		grid-template-columns: 1.9fr 1fr 1.15fr;
		gap: 56px;
		padding-bottom: 64px;
	}

	.cwt-brand {
		position: relative;
	}

	.cwt-brand-head {
		display: flex;
		align-items: center;
		gap: 14px;
		margin-bottom: 22px;
	}

	.cwt-brand-logo {
		width: 52px;
		height: 52px;
		background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 60%, var(--v-blue-500) 100%);
		border-radius: 14px;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #ffffff;
		font-weight: 900;
		font-size: 1.15rem;
		letter-spacing: 0.5px;
		box-shadow: 0 14px 32px -12px rgba(0, 27, 228, 0.75), inset 0 1px 0 rgba(255, 255, 255, 0.25);
		flex-shrink: 0;
		transition: transform 0.5s cubic-bezier(0.34, 1.4, 0.5, 1), box-shadow 0.5s ease;
		position: relative;
		overflow: hidden;
	}

	.cwt-brand-logo::after {
		content: '';
		position: absolute;
		inset: 0;
		background: linear-gradient(110deg, transparent 25%, rgba(255, 255, 255, 0.35) 50%, transparent 75%);
		transform: translateX(-120%);
		transition: transform 0.8s ease;
	}

	.cwt-brand:hover .cwt-brand-logo {
		transform: rotate(-6deg) scale(1.05);
		box-shadow: 0 20px 40px -14px rgba(0, 27, 228, 0.9), inset 0 1px 0 rgba(255, 255, 255, 0.3);
	}

	.cwt-brand:hover .cwt-brand-logo::after {
		transform: translateX(120%);
	}

	.cwt-brand-title {
		font-size: 1.08rem;
		font-weight: 800;
		color: #ffffff;
		line-height: 1.2;
		letter-spacing: -0.02em;
	}

	.cwt-brand-title span {
		display: block;
		font-size: 0.68rem;
		font-weight: 600;
		color: var(--light-pink);
		letter-spacing: 0.18em;
		text-transform: uppercase;
		margin-top: 3px;
	}

	.cwt-brand-para {
		font-size: 0.9rem;
		line-height: 1.8;
		color: rgba(255, 255, 255, 0.62);
		margin: 0 0 28px;
		max-width: 400px;
	}

	.cwt-socials {
		display: flex;
		gap: 10px;
		flex-wrap: wrap;
	}

	.cwt-social {
		width: 42px;
		height: 42px;
		border-radius: 12px;
		background: rgba(255, 255, 255, 0.06);
		border: 1px solid rgba(255, 255, 255, 0.1);
		display: flex;
		align-items: center;
		justify-content: center;
		color: rgba(255, 255, 255, 0.7);
		text-decoration: none;
		transition: transform 0.4s cubic-bezier(0.34, 1.3, 0.5, 1), background 0.35s ease, color 0.35s ease, border-color 0.35s ease, box-shadow 0.35s ease;
		position: relative;
		overflow: hidden;
		backdrop-filter: blur(10px);
		-webkit-backdrop-filter: blur(10px);
	}

	.cwt-social::before {
		content: '';
		position: absolute;
		inset: 0;
		background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
		opacity: 0;
		transition: opacity 0.35s ease;
		z-index: 0;
	}

	.cwt-social svg {
		width: 18px;
		height: 18px;
		fill: currentColor;
		position: relative;
		z-index: 1;
		transition: transform 0.4s cubic-bezier(0.34, 1.3, 0.5, 1);
	}

	.cwt-social:hover {
		transform: translateY(-4px);
		color: #ffffff;
		border-color: transparent;
		box-shadow: 0 14px 28px -10px rgba(0, 27, 228, 0.65);
	}

	.cwt-social:hover::before {
		opacity: 1;
	}

	.cwt-social:hover svg {
		transform: scale(1.12) rotate(-6deg);
	}

	.cwt-col {
		position: relative;
	}

	.cwt-col-heading {
		font-size: 0.78rem;
		font-weight: 800;
		text-transform: uppercase;
		letter-spacing: 0.16em;
		color: #ffffff;
		margin: 0 0 26px;
		padding-bottom: 14px;
		position: relative;
		display: inline-block;
	}

	.cwt-col-heading::after {
		content: '';
		position: absolute;
		left: 0;
		bottom: 0;
		width: 42px;
		height: 3px;
		border-radius: var(--radius-full);
		background: linear-gradient(90deg, var(--primary-light), var(--light-pink));
		transition: width 0.45s cubic-bezier(0.34, 1.3, 0.5, 1);
	}

	.cwt-col:hover .cwt-col-heading::after {
		width: 68px;
	}

	.cwt-links {
		display: flex;
		flex-direction: column;
		gap: 6px;
	}

	.cwt-links a {
		display: inline-flex;
		align-items: center;
		gap: 10px;
		padding: 7px 0;
		font-size: 0.9rem;
		font-weight: 500;
		color: rgba(255, 255, 255, 0.66);
		text-decoration: none;
		transition: color 0.3s ease, transform 0.35s cubic-bezier(0.34, 1.3, 0.5, 1), padding 0.35s ease;
		position: relative;
		width: fit-content;
	}

	.cwt-links a::before {
		content: '';
		position: absolute;
		left: 0;
		bottom: 4px;
		width: 0;
		height: 1.5px;
		background: linear-gradient(90deg, var(--primary-light), var(--light-pink));
		transition: width 0.4s cubic-bezier(0.34, 1.3, 0.5, 1);
	}

	.cwt-links a svg {
		width: 12px;
		height: 12px;
		flex-shrink: 0;
		opacity: 0;
		transform: translateX(-6px);
		transition: opacity 0.3s ease, transform 0.35s cubic-bezier(0.34, 1.3, 0.5, 1);
		color: var(--primary-light);
	}

	.cwt-links a:hover {
		color: #ffffff;
		padding-left: 6px;
	}

	.cwt-links a:hover::before {
		width: calc(100% - 24px);
	}

	.cwt-links a:hover svg {
		opacity: 1;
		transform: translateX(0);
	}

	.cwt-contacts {
		display: flex;
		flex-direction: column;
		gap: 14px;
	}

	.cwt-contact {
		display: flex;
		align-items: flex-start;
		gap: 14px;
		padding: 12px 14px;
		border-radius: 14px;
		background: rgba(255, 255, 255, 0.035);
		border: 1px solid rgba(255, 255, 255, 0.07);
		transition: transform 0.4s cubic-bezier(0.34, 1.3, 0.5, 1), background 0.35s ease, border-color 0.35s ease, box-shadow 0.4s ease;
		text-decoration: none;
		color: inherit;
		position: relative;
		overflow: hidden;
		backdrop-filter: blur(10px);
		-webkit-backdrop-filter: blur(10px);
	}

	.cwt-contact::before {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		width: 3px;
		height: 100%;
		background: linear-gradient(180deg, var(--primary-light), var(--light-pink));
		transform: scaleY(0);
		transform-origin: top center;
		transition: transform 0.45s cubic-bezier(0.34, 1.3, 0.5, 1);
	}

	.cwt-contact:hover {
		transform: translateX(4px);
		background: rgba(255, 255, 255, 0.07);
		border-color: rgba(126, 160, 248, 0.35);
		box-shadow: 0 12px 30px -14px rgba(0, 27, 228, 0.6);
	}

	.cwt-contact:hover::before {
		transform: scaleY(1);
	}

	.cwt-contact-icon {
		width: 38px;
		height: 38px;
		flex-shrink: 0;
		border-radius: 11px;
		background: linear-gradient(140deg, rgba(26, 102, 255, 0.22), rgba(0, 27, 228, 0.08));
		border: 1px solid rgba(126, 160, 248, 0.25);
		display: flex;
		align-items: center;
		justify-content: center;
		color: var(--light-pink);
		transition: transform 0.5s cubic-bezier(0.34, 1.4, 0.5, 1), background 0.35s ease, color 0.35s ease, box-shadow 0.4s ease, border-color 0.35s ease;
	}

	.cwt-contact-icon svg {
		width: 16px;
		height: 16px;
		fill: currentColor;
	}

	.cwt-contact:hover .cwt-contact-icon {
		background: linear-gradient(140deg, var(--primary), var(--primary-light));
		border-color: transparent;
		color: #ffffff;
		transform: rotate(-8deg) scale(1.08);
		box-shadow: 0 12px 24px -10px rgba(0, 27, 228, 0.75);
	}

	.cwt-contact-body {
		flex: 1;
		min-width: 0;
		padding-top: 2px;
	}

	.cwt-contact-value {
		font-size: 0.88rem;
		font-weight: 500;
		color: rgba(255, 255, 255, 0.78);
		line-height: 1.5;
		word-break: break-word;
		transition: color 0.3s ease;
	}

	.cwt-contact:hover .cwt-contact-value {
		color: #ffffff;
	}

	.cwt-footer-bottom {
		border-top: 1px solid rgba(255, 255, 255, 0.08);
		padding: 26px 0;
		position: relative;
		z-index: 1;
	}

	.cwt-footer-bottom-inner {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 20px;
		flex-wrap: wrap;
	}

	.cwt-copy {
		font-size: 0.82rem;
		color: rgba(255, 255, 255, 0.5);
		margin: 0;
		line-height: 1.6;
	}

	.cwt-copy strong {
		color: rgba(255, 255, 255, 0.85);
		font-weight: 700;
	}

	.cwt-back-top {
		position: fixed;
		right: 24px;
		bottom: 24px;
		width: 46px;
		height: 46px;
		border-radius: 50%;
		border: none;
		background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 60%, var(--v-blue-500) 100%);
		color: #ffffff;
		display: flex;
		align-items: center;
		justify-content: center;
		cursor: pointer;
		box-shadow: 0 14px 32px -10px rgba(0, 27, 228, 0.75);
		opacity: 0;
		visibility: hidden;
		transform: translateY(16px) scale(0.9);
		transition: opacity 0.4s ease, visibility 0.4s ease, transform 0.45s cubic-bezier(0.34, 1.4, 0.5, 1), box-shadow 0.4s ease;
		z-index: 999;
	}

	.cwt-back-top svg {
		width: 18px;
		height: 18px;
		fill: none;
		stroke: currentColor;
		stroke-width: 2.6;
		stroke-linecap: round;
		stroke-linejoin: round;
		transition: transform 0.4s cubic-bezier(0.34, 1.4, 0.5, 1);
	}

	.cwt-back-top.is-visible {
		opacity: 1;
		visibility: visible;
		transform: translateY(0) scale(1);
	}

	.cwt-back-top:hover {
		transform: translateY(-4px) scale(1.06);
		box-shadow: 0 20px 42px -12px rgba(0, 27, 228, 0.95);
	}

	.cwt-back-top:hover svg {
		transform: translateY(-3px);
	}

	@media (min-width: 2560px) {
		.cwt-footer {
			padding-top: 120px;
		}

		.cwt-footer-container {
			max-width: 1640px;
		}

		.cwt-footer-grid {
			gap: 72px;
			padding-bottom: 80px;
		}

		.cwt-brand-para {
			font-size: 1rem;
			max-width: 500px;
		}

		.cwt-links a {
			font-size: 1rem;
		}

		.cwt-contact-value {
			font-size: 0.98rem;
		}
	}

	@media (min-width: 1501px) and (max-width: 2559px) {
		.cwt-footer-container {
			max-width: 1400px;
		}

		.cwt-footer-grid {
			gap: 60px;
		}
	}

	@media (min-width: 1400px) and (max-width: 1500px) {
		.cwt-footer-container {
			max-width: 1320px;
		}
	}

	@media (min-width: 1200px) and (max-width: 1399px) {
		.cwt-footer-grid {
			gap: 44px;
		}
	}

	@media (min-width: 1001px) and (max-width: 1199px) {
		.cwt-footer-container {
			max-width: 1120px;
		}

		.cwt-footer-grid {
			gap: 36px;
		}
	}

	@media (min-width: 992px) and (max-width: 1000px) {
		.cwt-footer-grid {
			gap: 32px;
		}
	}

	@media (min-width: 768px) and (max-width: 991px) {
		.cwt-footer {
			padding-top: 80px;
		}

		.cwt-footer-grid {
			grid-template-columns: 1fr 1fr;
			gap: 40px;
			padding-bottom: 52px;
		}

		.cwt-brand {
			grid-column: 1 / -1;
		}

		.cwt-brand-para {
			max-width: 100%;
		}

		.cwt-contacts {
			grid-column: 1 / -1;
			display: grid;
			grid-template-columns: repeat(2, 1fr);
			gap: 14px;
		}
	}

	@media (min-width: 576px) and (max-width: 767px) {
		.cwt-footer {
			padding-top: 68px;
		}

		.cwt-footer-grid {
			grid-template-columns: 1fr 1fr;
			gap: 34px;
			padding-bottom: 44px;
		}

		.cwt-brand {
			grid-column: 1 / -1;
		}

		.cwt-contacts {
			grid-column: 1 / -1;
			display: grid;
			grid-template-columns: repeat(2, 1fr);
			gap: 12px;
		}

		.cwt-brand-logo {
			width: 46px;
			height: 46px;
			font-size: 1rem;
		}

		.cwt-brand-title {
			font-size: 1rem;
		}

		.cwt-col-heading {
			font-size: 0.74rem;
			margin-bottom: 22px;
		}

		.cwt-links a {
			font-size: 0.86rem;
		}
	}

	@media (min-width: 481px) and (max-width: 575px) {
		.cwt-footer {
			padding-top: 60px;
		}

		.cwt-footer-container {
			padding: 0 20px;
		}

		.cwt-footer-grid {
			grid-template-columns: 1fr;
			gap: 32px;
			padding-bottom: 40px;
		}

		.cwt-contacts {
			display: flex;
			flex-direction: column;
			gap: 12px;
		}

		.cwt-brand-logo {
			width: 44px;
			height: 44px;
			font-size: 0.98rem;
			border-radius: 12px;
		}

		.cwt-brand-title {
			font-size: 0.98rem;
		}

		.cwt-brand-para {
			font-size: 0.86rem;
		}

		.cwt-social {
			width: 40px;
			height: 40px;
			border-radius: 11px;
		}

		.cwt-social svg {
			width: 16px;
			height: 16px;
		}

		.cwt-footer-bottom-inner {
			flex-direction: column;
			text-align: center;
		}

		.cwt-copy {
			font-size: 0.78rem;
		}

		.cwt-back-top {
			right: 18px;
			bottom: 18px;
			width: 42px;
			height: 42px;
		}
	}

	@media (min-width: 380px) and (max-width: 480px) {
		.cwt-footer {
			padding-top: 56px;
		}

		.cwt-footer-container {
			padding: 0 16px;
		}

		.cwt-footer-grid {
			grid-template-columns: 1fr;
			gap: 28px;
			padding-bottom: 34px;
		}

		.cwt-brand-head {
			gap: 12px;
			margin-bottom: 18px;
		}

		.cwt-brand-logo {
			width: 42px;
			height: 42px;
			font-size: 0.92rem;
			border-radius: 11px;
		}

		.cwt-brand-title {
			font-size: 0.94rem;
		}

		.cwt-brand-title span {
			font-size: 0.62rem;
			letter-spacing: 0.15em;
		}

		.cwt-brand-para {
			font-size: 0.84rem;
			line-height: 1.7;
			margin-bottom: 22px;
		}

		.cwt-socials {
			gap: 8px;
		}

		.cwt-social {
			width: 38px;
			height: 38px;
			border-radius: 10px;
		}

		.cwt-social svg {
			width: 15px;
			height: 15px;
		}

		.cwt-col-heading {
			font-size: 0.72rem;
			margin-bottom: 18px;
			padding-bottom: 11px;
		}

		.cwt-links {
			gap: 4px;
		}

		.cwt-links a {
			font-size: 0.85rem;
			padding: 6px 0;
		}

		.cwt-contacts {
			gap: 10px;
		}

		.cwt-contact {
			padding: 11px 12px;
			gap: 12px;
			border-radius: 12px;
		}

		.cwt-contact-icon {
			width: 34px;
			height: 34px;
			border-radius: 9px;
		}

		.cwt-contact-icon svg {
			width: 14px;
			height: 14px;
		}

		.cwt-contact-value {
			font-size: 0.83rem;
		}

		.cwt-footer-bottom {
			padding: 20px 0;
		}

		.cwt-footer-bottom-inner {
			flex-direction: column;
			text-align: center;
			gap: 12px;
		}

		.cwt-copy {
			font-size: 0.76rem;
		}

		.cwt-back-top {
			right: 14px;
			bottom: 14px;
			width: 40px;
			height: 40px;
		}

		.cwt-back-top svg {
			width: 16px;
			height: 16px;
		}
	}

	@media (max-width: 379px) {
		.cwt-footer {
			padding-top: 48px;
		}

		.cwt-footer-container {
			padding: 0 14px;
		}

		.cwt-footer-grid {
			grid-template-columns: 1fr;
			gap: 24px;
			padding-bottom: 28px;
		}

		.cwt-brand-head {
			gap: 10px;
			margin-bottom: 16px;
		}

		.cwt-brand-logo {
			width: 38px;
			height: 38px;
			font-size: 0.85rem;
			border-radius: 10px;
		}

		.cwt-brand-title {
			font-size: 0.88rem;
		}

		.cwt-brand-title span {
			font-size: 0.58rem;
			letter-spacing: 0.13em;
		}

		.cwt-brand-para {
			font-size: 0.8rem;
			line-height: 1.68;
			margin-bottom: 20px;
		}

		.cwt-socials {
			gap: 7px;
		}

		.cwt-social {
			width: 36px;
			height: 36px;
			border-radius: 10px;
		}

		.cwt-social svg {
			width: 14px;
			height: 14px;
		}

		.cwt-col-heading {
			font-size: 0.68rem;
			letter-spacing: 0.14em;
			margin-bottom: 16px;
			padding-bottom: 10px;
		}

		.cwt-col-heading::after {
			width: 34px;
			height: 2.5px;
		}

		.cwt-links {
			gap: 3px;
		}

		.cwt-links a {
			font-size: 0.8rem;
			padding: 5px 0;
		}

		.cwt-contacts {
			gap: 9px;
		}

		.cwt-contact {
			padding: 10px 11px;
			gap: 10px;
			border-radius: 11px;
		}

		.cwt-contact-icon {
			width: 32px;
			height: 32px;
			border-radius: 8px;
		}

		.cwt-contact-icon svg {
			width: 13px;
			height: 13px;
		}

		.cwt-contact-value {
			font-size: 0.78rem;
		}

		.cwt-footer-bottom {
			padding: 18px 0;
		}

		.cwt-footer-bottom-inner {
			flex-direction: column;
			text-align: center;
			gap: 10px;
		}

		.cwt-copy {
			font-size: 0.72rem;
		}

		.cwt-back-top {
			right: 12px;
			bottom: 12px;
			width: 38px;
			height: 38px;
		}

		.cwt-back-top svg {
			width: 15px;
			height: 15px;
		}
	}

	@media (prefers-reduced-motion: reduce) {
		.cwt-footer-orb {
			animation: none;
		}

		.cwt-brand-logo,
		.cwt-social,
		.cwt-contact,
		.cwt-links a,
		.cwt-back-top {
			transition: none;
		}
	}
</style>

<footer class="cwt-footer" role="contentinfo">
	<div class="cwt-footer-grid-lines" aria-hidden="true"></div>
	<div class="cwt-footer-orb cwt-footer-orb-1" aria-hidden="true"></div>
	<div class="cwt-footer-orb cwt-footer-orb-2" aria-hidden="true"></div>
	<div class="cwt-footer-orb cwt-footer-orb-3" aria-hidden="true"></div>

	<div class="cwt-footer-container">
		<div class="cwt-footer-grid">

			<div class="cwt-brand">
				<div class="cwt-brand-head">
					<div class="cwt-brand-logo">CWT</div>
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
					<a href="<?= SITE_URL ?>/about.php">
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
					<div class="cwt-contact">
						<span class="cwt-contact-icon">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6"/></svg>
						</span>
						<span class="cwt-contact-body">
							<span class="cwt-contact-value"><?= nl2br($fp['address']) ?></span>
						</span>
					</div>
					<?php endif; ?>
				</div>
			</div>

		</div>
	</div>

	<div class="cwt-footer-bottom">
		<div class="cwt-footer-container">
			<div class="cwt-footer-bottom-inner">
				<p class="cwt-copy">© Copyright <?= date('Y') ?> by <strong>Creative Web Technologies</strong>. All Rights Reserved.</p>
			</div>
		</div>
	</div>
</footer>

<button type="button" class="cwt-back-top" id="cwtBackTop" aria-label="Back to top">
	<svg viewBox="0 0 24 24"><path d="m18 15-6-6-6 6"/></svg>
</button>

<script>
	(function () {
		'use strict';

		var backTop = document.getElementById('cwtBackTop');

		function toggleBackTop() {
			if (!backTop) return;
			if (window.scrollY > 320) {
				backTop.classList.add('is-visible');
			} else {
				backTop.classList.remove('is-visible');
			}
		}

		toggleBackTop();
		window.addEventListener('scroll', toggleBackTop, { passive: true });

		if (backTop) {
			backTop.addEventListener('click', function () {
				window.scrollTo({ top: 0, behavior: 'smooth' });
			});
		}

		var footer = document.querySelector('.cwt-footer');
		if (!footer) return;

		var cards = footer.querySelectorAll('.cwt-contact, .cwt-social, .cwt-links a');

		cards.forEach(function (el) {
			el.addEventListener('mouseenter', function () {
				el.style.willChange = 'transform';
			});
			el.addEventListener('mouseleave', function () {
				el.style.willChange = '';
			});
		});
	})();
</script>