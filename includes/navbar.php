<?php
$currentPage = basename($_SERVER['PHP_SELF']);
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

	.cwt-navbar {
		position: fixed;
		top: 18px;
		left: 50%;
		transform: translateX(-50%);
		width: calc(100% - 48px);
		max-width: 1240px;
		background: rgba(255, 255, 255, 0.78);
		backdrop-filter: saturate(180%) blur(22px);
		-webkit-backdrop-filter: saturate(180%) blur(22px);
		border-radius: var(--radius-full);
		border: 1px solid rgba(255, 255, 255, 0.85);
		box-shadow:
			0 4px 24px rgba(13, 42, 92, 0.06),
			0 1px 0 rgba(255, 255, 255, 0.9) inset,
			0 0 0 1px rgba(13, 42, 92, 0.02);
		z-index: 1000;
		transition:
			top 0.4s cubic-bezier(0.34, 1.3, 0.5, 1),
			background 0.4s ease,
			box-shadow 0.4s ease,
			border-color 0.4s ease,
			width 0.4s ease,
			max-width 0.4s ease;
		font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
	}

	.cwt-navbar *,
	.cwt-navbar *::before,
	.cwt-navbar *::after {
		box-sizing: border-box;
	}

	.cwt-navbar.is-scrolled {
		top: 10px;
		background: rgba(255, 255, 255, 0.92);
		box-shadow:
			0 12px 44px -12px rgba(13, 42, 92, 0.18),
			0 1px 0 rgba(255, 255, 255, 0.95) inset,
			0 0 0 1px rgba(13, 42, 92, 0.03);
	}

	.cwt-navbar-inner {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 0 14px 0 16px;
		height: var(--navbar-height);
		gap: 16px;
	}

	.cwt-logo {
		display: flex;
		align-items: center;
		gap: 12px;
		text-decoration: none;
		position: relative;
		padding: 6px 8px 6px 6px;
		border-radius: 14px;
		transition: background 0.35s ease, transform 0.35s ease;
	}

	.cwt-logo:hover {
		background: rgba(26, 102, 255, 0.05);
	}

	.cwt-logo-icon {
		position: relative;
		width: 42px;
		height: 42px;
		background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 55%, var(--v-blue-500) 100%);
		border-radius: 13px;
		display: flex;
		align-items: center;
		justify-content: center;
		color: var(--white);
		font-weight: 900;
		font-size: 0.92rem;
		letter-spacing: 0.5px;
		box-shadow:
			0 10px 24px -10px rgba(0, 27, 228, 0.65),
			inset 0 1px 0 rgba(255, 255, 255, 0.28);
		overflow: hidden;
		flex-shrink: 0;
		transition: transform 0.5s cubic-bezier(0.34, 1.4, 0.5, 1), box-shadow 0.5s ease;
	}

	.cwt-logo-icon::before {
		content: '';
		position: absolute;
		inset: 0;
		background: linear-gradient(110deg, transparent 25%, rgba(255, 255, 255, 0.42) 50%, transparent 75%);
		transform: translateX(-120%);
		transition: transform 0.9s ease;
	}

	.cwt-logo:hover .cwt-logo-icon {
		transform: rotate(-6deg) scale(1.06);
		box-shadow:
			0 16px 32px -12px rgba(0, 27, 228, 0.85),
			inset 0 1px 0 rgba(255, 255, 255, 0.35);
	}

	.cwt-logo:hover .cwt-logo-icon::before {
		transform: translateX(120%);
	}

	.cwt-logo-text {
		display: flex;
		flex-direction: column;
		line-height: 1.1;
		font-size: 0.86rem;
		font-weight: 700;
		color: var(--text-primary);
		letter-spacing: -0.01em;
	}

	.cwt-logo-text span {
		color: var(--primary);
		font-weight: 800;
		background: linear-gradient(100deg, var(--primary), var(--primary-light), var(--v-blue-500));
		-webkit-background-clip: text;
		background-clip: text;
		-webkit-text-fill-color: transparent;
	}

	.cwt-nav {
		display: flex;
		align-items: center;
		gap: 4px;
		padding: 6px;
		margin: 0;
		list-style: none;
		border-radius: var(--radius-full);
		background: rgba(13, 42, 92, 0.035);
		border: 1px solid rgba(13, 42, 92, 0.04);
		position: relative;
	}

	.cwt-nav-link {
		position: relative;
		display: inline-flex;
		align-items: center;
		padding: 9px 18px;
		border-radius: var(--radius-full);
		font-size: 0.85rem;
		font-weight: 600;
		color: var(--text-secondary);
		text-decoration: none;
		white-space: nowrap;
		letter-spacing: 0.005em;
		transition:
			color 0.35s ease,
			transform 0.4s cubic-bezier(0.34, 1.3, 0.5, 1);
		overflow: hidden;
		isolation: isolate;
	}

	.cwt-nav-link::before {
		content: '';
		position: absolute;
		inset: 0;
		background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 55%, var(--v-blue-500) 100%);
		border-radius: inherit;
		opacity: 0;
		transform: scale(0.85);
		transition: opacity 0.35s ease, transform 0.45s cubic-bezier(0.34, 1.3, 0.5, 1);
		z-index: -1;
	}

	.cwt-nav-link::after {
		content: '';
		position: absolute;
		left: 50%;
		bottom: 2px;
		width: 0;
		height: 2px;
		background: linear-gradient(90deg, var(--primary-light), var(--v-blue-500));
		border-radius: 2px;
		transform: translateX(-50%);
		transition: width 0.4s cubic-bezier(0.34, 1.3, 0.5, 1), opacity 0.3s ease;
	}

	.cwt-nav-link:hover {
		color: var(--primary);
		transform: translateY(-1px);
	}

	.cwt-nav-link:hover::after {
		width: 22px;
	}

	.cwt-nav-link.is-active {
		color: var(--white);
	}

	.cwt-nav-link.is-active::before {
		opacity: 1;
		transform: scale(1);
	}

	.cwt-nav-link.is-active::after {
		opacity: 0;
	}

	.cwt-nav-link.is-active:hover {
		color: var(--white);
	}

	.cwt-nav-actions {
		display: flex;
		align-items: center;
		gap: 10px;
	}

	.cwt-cta {
		position: relative;
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 11px 22px;
		border-radius: var(--radius-full);
		font-family: inherit;
		font-size: 0.84rem;
		font-weight: 700;
		letter-spacing: 0.01em;
		color: var(--white);
		text-decoration: none;
		background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 55%, var(--v-blue-500) 100%);
		box-shadow:
			0 10px 24px -10px rgba(0, 27, 228, 0.6),
			inset 0 1px 0 rgba(255, 255, 255, 0.22);
		overflow: hidden;
		isolation: isolate;
		transition:
			transform 0.4s cubic-bezier(0.34, 1.3, 0.5, 1),
			box-shadow 0.4s ease;
		white-space: nowrap;
	}

	.cwt-cta::before {
		content: '';
		position: absolute;
		inset: 0;
		background: linear-gradient(110deg, transparent 25%, rgba(255, 255, 255, 0.42) 50%, transparent 75%);
		transform: translateX(-120%);
		transition: transform 0.85s ease;
		pointer-events: none;
	}

	.cwt-cta svg {
		width: 14px;
		height: 14px;
		fill: none;
		stroke: currentColor;
		stroke-width: 2.6;
		stroke-linecap: round;
		stroke-linejoin: round;
		transition: transform 0.4s cubic-bezier(0.34, 1.3, 0.5, 1);
	}

	.cwt-cta:hover {
		transform: translateY(-2px);
		box-shadow:
			0 18px 36px -12px rgba(0, 27, 228, 0.85),
			inset 0 1px 0 rgba(255, 255, 255, 0.28);
	}

	.cwt-cta:hover::before {
		transform: translateX(120%);
	}

	.cwt-cta:hover svg {
		transform: translateX(3px);
	}

	.cwt-burger {
		display: none;
		position: relative;
		width: 44px;
		height: 44px;
		border-radius: 13px;
		background: rgba(13, 42, 92, 0.045);
		border: 1px solid rgba(13, 42, 92, 0.06);
		cursor: pointer;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		gap: 5px;
		padding: 0;
		transition: background 0.35s ease, border-color 0.35s ease, transform 0.35s ease;
	}

	.cwt-burger:hover {
		background: rgba(26, 102, 255, 0.1);
		border-color: rgba(26, 102, 255, 0.22);
	}

	.cwt-burger span {
		display: block;
		width: 20px;
		height: 2px;
		background: var(--text-primary);
		border-radius: 2px;
		transition:
			transform 0.4s cubic-bezier(0.34, 1.3, 0.5, 1),
			opacity 0.3s ease,
			background 0.3s ease,
			width 0.3s ease;
	}

	.cwt-burger.is-active {
		background: rgba(26, 102, 255, 0.12);
		border-color: rgba(26, 102, 255, 0.25);
	}

	.cwt-burger.is-active span:nth-child(1) {
		transform: translateY(7px) rotate(45deg);
		background: var(--primary);
	}

	.cwt-burger.is-active span:nth-child(2) {
		opacity: 0;
		transform: scaleX(0);
	}

	.cwt-burger.is-active span:nth-child(3) {
		transform: translateY(-7px) rotate(-45deg);
		background: var(--primary);
	}

	.cwt-mobile {
		position: absolute;
		top: calc(100% + 12px);
		left: 0;
		right: 0;
		background: rgba(255, 255, 255, 0.98);
		backdrop-filter: saturate(180%) blur(22px);
		-webkit-backdrop-filter: saturate(180%) blur(22px);
		border-radius: 24px;
		border: 1px solid rgba(255, 255, 255, 0.9);
		box-shadow: 0 24px 60px -18px rgba(13, 42, 92, 0.28);
		padding: 14px;
		display: flex;
		flex-direction: column;
		gap: 4px;
		opacity: 0;
		visibility: hidden;
		transform: translateY(-8px) scale(0.98);
		transform-origin: top center;
		transition:
			opacity 0.35s ease,
			visibility 0.35s ease,
			transform 0.4s cubic-bezier(0.34, 1.3, 0.5, 1);
		pointer-events: none;
	}

	.cwt-mobile.is-open {
		opacity: 1;
		visibility: visible;
		transform: translateY(0) scale(1);
		pointer-events: auto;
	}

	.cwt-mobile-link {
		display: flex;
		align-items: center;
		gap: 12px;
		padding: 14px 16px;
		border-radius: 14px;
		font-size: 0.92rem;
		font-weight: 600;
		color: var(--text-secondary);
		text-decoration: none;
		position: relative;
		overflow: hidden;
		transition:
			background 0.35s ease,
			color 0.35s ease,
			transform 0.4s cubic-bezier(0.34, 1.3, 0.5, 1),
			padding-left 0.4s ease;
	}

	.cwt-mobile-link::before {
		content: '';
		position: absolute;
		left: 0;
		top: 50%;
		transform: translateY(-50%);
		width: 3px;
		height: 0;
		background: linear-gradient(180deg, var(--primary-light), var(--v-blue-500));
		border-radius: 3px;
		transition: height 0.4s cubic-bezier(0.34, 1.3, 0.5, 1);
	}

	.cwt-mobile-link:hover {
		background: rgba(26, 102, 255, 0.07);
		color: var(--primary);
		padding-left: 22px;
	}

	.cwt-mobile-link:hover::before {
		height: 60%;
	}

	.cwt-mobile-link.is-active {
		background: linear-gradient(135deg, rgba(0, 27, 228, 0.1), rgba(43, 123, 255, 0.08));
		color: var(--primary);
	}

	.cwt-mobile-link.is-active::before {
		height: 60%;
	}

	.cwt-mobile-divider {
		height: 1px;
		margin: 8px 4px;
		background: linear-gradient(90deg, transparent, rgba(13, 42, 92, 0.09), transparent);
	}

	.cwt-mobile-cta {
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 8px;
		padding: 14px 20px;
		border-radius: 14px;
		background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 55%, var(--v-blue-500) 100%);
		color: var(--white);
		font-size: 0.9rem;
		font-weight: 700;
		text-decoration: none;
		letter-spacing: 0.01em;
		position: relative;
		overflow: hidden;
		box-shadow: 0 12px 26px -12px rgba(0, 27, 228, 0.7);
		transition: transform 0.4s cubic-bezier(0.34, 1.3, 0.5, 1), box-shadow 0.4s ease;
	}

	.cwt-mobile-cta::before {
		content: '';
		position: absolute;
		inset: 0;
		background: linear-gradient(110deg, transparent 25%, rgba(255, 255, 255, 0.42) 50%, transparent 75%);
		transform: translateX(-120%);
		transition: transform 0.85s ease;
	}

	.cwt-mobile-cta svg {
		width: 15px;
		height: 15px;
		fill: none;
		stroke: currentColor;
		stroke-width: 2.6;
		stroke-linecap: round;
		stroke-linejoin: round;
		transition: transform 0.4s cubic-bezier(0.34, 1.3, 0.5, 1);
	}

	.cwt-mobile-cta:hover {
		transform: translateY(-2px);
		box-shadow: 0 20px 36px -14px rgba(0, 27, 228, 0.85);
	}

	.cwt-mobile-cta:hover::before {
		transform: translateX(120%);
	}

	.cwt-mobile-cta:hover svg {
		transform: translateX(3px);
	}

	.cwt-mobile.is-open .cwt-mobile-link,
	.cwt-mobile.is-open .cwt-mobile-cta {
		animation: cwtMobileIn 0.45s cubic-bezier(0.22, 1, 0.36, 1) both;
	}

	.cwt-mobile.is-open .cwt-mobile-link:nth-child(1) { animation-delay: 0.04s; }
	.cwt-mobile.is-open .cwt-mobile-link:nth-child(2) { animation-delay: 0.08s; }
	.cwt-mobile.is-open .cwt-mobile-link:nth-child(3) { animation-delay: 0.12s; }
	.cwt-mobile.is-open .cwt-mobile-link:nth-child(4) { animation-delay: 0.16s; }
	.cwt-mobile.is-open .cwt-mobile-link:nth-child(5) { animation-delay: 0.2s; }
	.cwt-mobile.is-open .cwt-mobile-link:nth-child(6) { animation-delay: 0.24s; }
	.cwt-mobile.is-open .cwt-mobile-cta { animation-delay: 0.3s; }

	@keyframes cwtMobileIn {
		from {
			opacity: 0;
			transform: translateY(-8px);
		}
		to {
			opacity: 1;
			transform: translateY(0);
		}
	}

	@media (min-width: 2560px) {
		.cwt-navbar {
			max-width: 1680px;
			top: 24px;
		}

		.cwt-navbar-inner {
			height: 82px;
			padding: 0 20px 0 22px;
		}

		.cwt-logo-icon {
			width: 50px;
			height: 50px;
			font-size: 1.05rem;
			border-radius: 15px;
		}

		.cwt-logo-text {
			font-size: 1rem;
		}

		.cwt-nav-link {
			padding: 11px 22px;
			font-size: 0.95rem;
		}

		.cwt-cta {
			padding: 13px 26px;
			font-size: 0.92rem;
		}
	}

	@media (min-width: 1501px) and (max-width: 2559px) {
		.cwt-navbar {
			max-width: 1420px;
		}
	}

	@media (min-width: 1400px) and (max-width: 1500px) {
		.cwt-navbar {
			max-width: 1320px;
		}
	}

	@media (min-width: 1200px) and (max-width: 1399px) {
		.cwt-navbar {
			max-width: 1180px;
		}
	}

	@media (min-width: 1001px) and (max-width: 1199px) {
		.cwt-navbar {
			max-width: 980px;
		}

		.cwt-nav-link {
			padding: 8px 14px;
			font-size: 0.8rem;
		}

		.cwt-cta {
			padding: 10px 18px;
			font-size: 0.8rem;
		}
	}

	@media (min-width: 992px) and (max-width: 1000px) {
		.cwt-navbar {
			max-width: 960px;
		}

		.cwt-nav-link {
			padding: 8px 13px;
			font-size: 0.79rem;
		}

		.cwt-cta {
			padding: 10px 16px;
			font-size: 0.78rem;
		}
	}

	@media (min-width: 768px) and (max-width: 991px) {
		.cwt-navbar {
			top: 14px;
			width: calc(100% - 32px);
			max-width: 100%;
		}

		.cwt-navbar.is-scrolled {
			top: 8px;
		}

		.cwt-navbar-inner {
			height: 66px;
			padding: 0 12px 0 14px;
		}

		.cwt-nav,
		.cwt-nav-actions .cwt-cta {
			display: none;
		}

		.cwt-burger {
			display: flex;
		}

		.cwt-mobile {
			display: flex;
		}
	}

	@media (min-width: 576px) and (max-width: 767px) {
		.cwt-navbar {
			top: 12px;
			width: calc(100% - 24px);
			max-width: 100%;
		}

		.cwt-navbar.is-scrolled {
			top: 6px;
		}

		.cwt-navbar-inner {
			height: 62px;
			padding: 0 10px 0 12px;
			gap: 10px;
		}

		.cwt-logo-icon {
			width: 38px;
			height: 38px;
			font-size: 0.82rem;
			border-radius: 12px;
		}

		.cwt-logo-text {
			font-size: 0.79rem;
		}

		.cwt-nav,
		.cwt-nav-actions .cwt-cta {
			display: none;
		}

		.cwt-burger {
			display: flex;
			width: 40px;
			height: 40px;
			border-radius: 12px;
		}

		.cwt-burger span {
			width: 18px;
		}

		.cwt-mobile {
			display: flex;
			border-radius: 20px;
			padding: 12px;
		}

		.cwt-mobile-link {
			padding: 12px 14px;
			font-size: 0.88rem;
		}

		.cwt-mobile-cta {
			padding: 13px 18px;
			font-size: 0.86rem;
		}
	}

	@media (min-width: 481px) and (max-width: 575px) {
		.cwt-navbar {
			top: 10px;
			width: calc(100% - 20px);
			max-width: 100%;
			border-radius: 22px;
		}

		.cwt-navbar.is-scrolled {
			top: 6px;
		}

		.cwt-navbar-inner {
			height: 58px;
			padding: 0 8px 0 10px;
			gap: 8px;
		}

		.cwt-logo {
			padding: 4px 6px 4px 4px;
			gap: 10px;
		}

		.cwt-logo-icon {
			width: 36px;
			height: 36px;
			font-size: 0.76rem;
			border-radius: 11px;
		}

		.cwt-logo-text {
			font-size: 0.75rem;
		}

		.cwt-nav,
		.cwt-nav-actions .cwt-cta {
			display: none;
		}

		.cwt-burger {
			display: flex;
			width: 38px;
			height: 38px;
			border-radius: 11px;
		}

		.cwt-burger span {
			width: 17px;
			height: 1.8px;
		}

		.cwt-mobile {
			display: flex;
			border-radius: 18px;
			padding: 10px;
			top: calc(100% + 10px);
		}

		.cwt-mobile-link {
			padding: 11px 13px;
			font-size: 0.85rem;
			border-radius: 12px;
		}

		.cwt-mobile-cta {
			padding: 12px 16px;
			font-size: 0.83rem;
			border-radius: 12px;
		}

		.cwt-mobile-cta svg {
			width: 14px;
			height: 14px;
		}
	}

	@media (min-width: 380px) and (max-width: 480px) {
		.cwt-navbar {
			top: 10px;
			width: calc(100% - 16px);
			max-width: 100%;
			border-radius: 20px;
		}

		.cwt-navbar.is-scrolled {
			top: 5px;
		}

		.cwt-navbar-inner {
			height: 56px;
			padding: 0 8px 0 10px;
			gap: 8px;
		}

		.cwt-logo {
			padding: 4px 6px 4px 4px;
			gap: 9px;
			border-radius: 12px;
		}

		.cwt-logo-icon {
			width: 34px;
			height: 34px;
			font-size: 0.72rem;
			border-radius: 10px;
		}

		.cwt-logo-text {
			font-size: 0.7rem;
		}

		.cwt-nav,
		.cwt-nav-actions .cwt-cta {
			display: none;
		}

		.cwt-burger {
			display: flex;
			width: 36px;
			height: 36px;
			border-radius: 10px;
			gap: 4px;
		}

		.cwt-burger span {
			width: 16px;
			height: 1.6px;
		}

		.cwt-burger.is-active span:nth-child(1) {
			transform: translateY(6px) rotate(45deg);
		}

		.cwt-burger.is-active span:nth-child(3) {
			transform: translateY(-6px) rotate(-45deg);
		}

		.cwt-mobile {
			display: flex;
			border-radius: 16px;
			padding: 9px;
			top: calc(100% + 8px);
			gap: 3px;
		}

		.cwt-mobile-link {
			padding: 10px 12px;
			font-size: 0.82rem;
			border-radius: 11px;
			gap: 10px;
		}

		.cwt-mobile-divider {
			margin: 6px 4px;
		}

		.cwt-mobile-cta {
			padding: 11px 14px;
			font-size: 0.8rem;
			border-radius: 11px;
			gap: 7px;
		}

		.cwt-mobile-cta svg {
			width: 13px;
			height: 13px;
		}
	}

	@media (max-width: 379px) {
		.cwt-navbar {
			top: 8px;
			width: calc(100% - 12px);
			max-width: 100%;
			border-radius: 18px;
		}

		.cwt-navbar.is-scrolled {
			top: 4px;
		}

		.cwt-navbar-inner {
			height: 52px;
			padding: 0 6px 0 8px;
			gap: 6px;
		}

		.cwt-logo {
			padding: 3px 5px 3px 3px;
			gap: 8px;
			border-radius: 11px;
		}

		.cwt-logo-icon {
			width: 32px;
			height: 32px;
			font-size: 0.68rem;
			border-radius: 9px;
		}

		.cwt-logo-text {
			font-size: 0.66rem;
		}

		.cwt-nav,
		.cwt-nav-actions .cwt-cta {
			display: none;
		}

		.cwt-burger {
			display: flex;
			width: 34px;
			height: 34px;
			border-radius: 9px;
			gap: 3.5px;
		}

		.cwt-burger span {
			width: 15px;
			height: 1.5px;
		}

		.cwt-burger.is-active span:nth-child(1) {
			transform: translateY(5.5px) rotate(45deg);
		}

		.cwt-burger.is-active span:nth-child(3) {
			transform: translateY(-5.5px) rotate(-45deg);
		}

		.cwt-mobile {
			display: flex;
			border-radius: 15px;
			padding: 8px;
			top: calc(100% + 6px);
			gap: 2px;
		}

		.cwt-mobile-link {
			padding: 9px 11px;
			font-size: 0.78rem;
			border-radius: 10px;
			gap: 9px;
		}

		.cwt-mobile-divider {
			margin: 5px 3px;
		}

		.cwt-mobile-cta {
			padding: 10px 12px;
			font-size: 0.76rem;
			border-radius: 10px;
			gap: 6px;
		}

		.cwt-mobile-cta svg {
			width: 12px;
			height: 12px;
		}
	}

	@media (prefers-reduced-motion: reduce) {
		.cwt-navbar,
		.cwt-logo-icon,
		.cwt-nav-link,
		.cwt-cta,
		.cwt-burger,
		.cwt-mobile,
		.cwt-mobile-link,
		.cwt-mobile-cta {
			transition: none;
			animation: none;
		}
	}
</style>

<nav class="cwt-navbar" id="cwtNavbar">
	<div class="cwt-navbar-inner">

		<a href="<?= SITE_URL ?>/index.php" class="cwt-logo" aria-label="Creative Web Technologies home">
			<span class="cwt-logo-icon">CWT</span>
			<span class="cwt-logo-text">
				Creative Web
				<span>Technologies</span>
			</span>
		</a>

		<ul class="cwt-nav" role="navigation">
			<li>
				<a href="<?= SITE_URL ?>/services.php" class="cwt-nav-link <?= $currentPage === 'services.php' ? 'is-active' : '' ?>">Our Services</a>
			</li>
			<li>
				<a href="<?= SITE_URL ?>/about.php" class="cwt-nav-link <?= $currentPage === 'about.php' ? 'is-active' : '' ?>">About Us</a>
			</li>
			<li>
				<a href="<?= SITE_URL ?>/our-crew.php" class="cwt-nav-link <?= $currentPage === 'crew.php' ? 'is-active' : '' ?>">Our Crew</a>
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

	<div class="cwt-mobile" id="cwtMobile" role="navigation">
		<a href="<?= SITE_URL ?>/index.php" class="cwt-mobile-link <?= $currentPage === 'index.php' ? 'is-active' : '' ?>">Home</a>
		<a href="<?= SITE_URL ?>/services.php" class="cwt-mobile-link <?= $currentPage === 'services.php' ? 'is-active' : '' ?>">Our Services</a>
		<a href="<?= SITE_URL ?>/about.php" class="cwt-mobile-link <?= $currentPage === 'about.php' ? 'is-active' : '' ?>">About Us</a>
		<a href="<?= SITE_URL ?>/our-crew.php" class="cwt-mobile-link <?= $currentPage === 'crew.php' ? 'is-active' : '' ?>">Our Crew</a>
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
		};

		var openMenu = function () {
			burger.classList.add('is-active');
			mobile.classList.add('is-open');
			burger.setAttribute('aria-expanded', 'true');
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
			if (e.key === 'Escape') {
				closeMenu();
			}
		});

		var links = document.querySelectorAll('.cwt-nav-link, .cwt-mobile-link');
		var currentPath = window.location.pathname.split('/').pop() || 'index.php';

		links.forEach(function (link) {
			var href = link.getAttribute('href') || '';
			var file = href.split('/').pop();

			if (file && file === currentPath) {
				link.classList.add('is-active');
			} else if (file && file.replace('.php', '') && currentPath.indexOf(file.replace('.php', '')) !== -1) {
				link.classList.add('is-active');
			}
		});
	})();
</script>