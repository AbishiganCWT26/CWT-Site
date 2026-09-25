<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CTA Band — Standalone</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
/* =========================================================
   TOKENS
   ========================================================= */
:root {
    --primary: #001be4;
    --primary-light: #1a66ff;
    --light-pink: #7ea0f8ff;

    --white: #FFFFFF;
    --off-white: #F9F9F9;
    --text-secondary: #333333;

    --radius-full: 9999px;

    --transition: 0.3s ease;
}

/* =========================================================
   RESET / BASE
   ========================================================= */
*,
*::before,
*::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

html {
    scroll-behavior: smooth;
    font-size: 16px;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    background: var(--off-white);
    color: var(--text-secondary);
    line-height: 1.7;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    overflow-x: hidden;
}

h1, h2, h3, h4, h5, h6 {
    font-weight: 800;
    line-height: 1.15;
    letter-spacing: -0.02em;
}

p {
    line-height: 1.8;
}

a {
    text-decoration: none;
    color: inherit;
}

img {
    max-width: 100%;
    height: auto;
    display: block;
}

.container {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 20px;
    width: 100%;
}

/* =========================================================
   BUTTONS
   ========================================================= */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 12px 26px;
    border-radius: var(--radius-full);
    font-family: inherit;
    font-size: 0.94rem;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: transform 0.35s cubic-bezier(0.34, 1.3, 0.5, 1),
                box-shadow 0.35s ease,
                background 0.3s ease,
                color 0.3s ease,
                border-color 0.3s ease;
    text-decoration: none;
    white-space: nowrap;
    position: relative;
    overflow: hidden;
    isolation: isolate;
    letter-spacing: 0.01em;
}

.btn svg {
    transition: transform 0.4s cubic-bezier(0.34, 1.3, 0.5, 1);
}

.btn:hover svg {
    transform: translateX(4px);
}

.btn-white {
    background: var(--white);
    color: var(--primary);
    box-shadow: 0 12px 32px -10px rgba(0, 0, 0, 0.3);
}

.btn-white:hover {
    transform: translateY(-3px);
    box-shadow: 0 20px 44px -12px rgba(0, 0, 0, 0.4);
}

/* =========================================================
   CTA BAND
   ========================================================= */
.cta-band {
    position: relative;
    padding: 26px 0;
    text-align: center;
    background: linear-gradient(135deg, #001be4 0%, #1a3a8a 45%, #415b7b 100%);
    overflow: hidden;
    isolation: isolate;
}

.cta-band-bg {
    position: absolute;
    inset: 0;
    pointer-events: none;
    overflow: hidden;
}

.cta-band-bg span {
    position: absolute;
    border-radius: 50%;
    filter: blur(60px);
    opacity: 0.4;
}

.cta-band-bg span:nth-child(1) {
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, var(--primary-light), transparent 70%);
    top: -200px;
    right: -100px;
    animation: orbFloat 16s ease-in-out infinite;
}

.cta-band-bg span:nth-child(2) {
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, var(--light-pink), transparent 70%);
    bottom: -180px;
    left: -80px;
    animation: orbFloat 18s ease-in-out infinite reverse;
}

.cta-band .container {
    position: relative;
    z-index: 1;
}

.cta-title {
    color: #ffffff;
    font-size: clamp(1.8rem, 4vw, 2.7rem);
    margin-bottom: 4px;
    letter-spacing: -0.03em;
}

.cta-text {
    color: rgba(255, 255, 255, 0.82);
    margin-bottom: 8px;
    max-width: 560px;
    margin-left: auto;
    margin-right: auto;
    font-size: 1.02rem;
    line-height: 1.7;
}

/* =========================================================
   ANIMATIONS
   ========================================================= */
@keyframes orbFloat {
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

.reveal {
    opacity: 0;
    transform: translateY(32px);
    transition: opacity 0.8s cubic-bezier(0.22, 1, 0.36, 1),
                transform 0.8s cubic-bezier(0.22, 1, 0.36, 1);
}

.reveal.visible {
    opacity: 1;
    transform: none;
}

/* =========================================================
   RESPONSIVE
   ========================================================= */
@media (min-width: 481px) and (max-width: 575px) {
    .cta-band {
        padding: 50px 0;
    }
}

@media (min-width: 380px) and (max-width: 480px) {
    .cta-band {
        padding: 26px 0;
    }

    .cta-title {
        font-size: 1.35rem;
    }

    .cta-text {
        font-size: 0.9rem;
    }
}

@media (max-width: 379px) {
    .container {
        padding: 0 14px;
    }

    .cta-band {
        padding: 22px 0;
    }

    .cta-title {
        font-size: 1.15rem;
    }

    .cta-text {
        font-size: 0.73rem;
    }
}

@media (prefers-reduced-motion: reduce) {
    .cta-band-bg span {
        animation: none;
    }

    .reveal {
        opacity: 1;
        transform: none;
        animation: none;
    }
}
</style>
</head>
<body>

<!-- =========================================================
     CTA BAND SECTION
     ========================================================= -->
<section class="cta-band">
    <div class="cta-band-bg" aria-hidden="true">
        <span></span>
        <span></span>
    </div>

    <div class="container reveal">
        <h2 class="cta-title">Want to Work With Us?</h2>

        <p class="cta-text">Let's map out your technology stack, team capacity, and roadmap so we can build a concrete plan to scale your growth</p>

        <a href="<?= SITE_URL ?>/contact.php" class="btn btn-white">
            <span>Talk to Us</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
            </svg>
        </a>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var revealObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    document.querySelectorAll('.reveal').forEach(function (el) {
        revealObserver.observe(el);
    });
});
</script>

</body>
</html>