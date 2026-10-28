<?php
/* =====================================================================
   loader.php  —  CWT site loader (show ONLY ONCE per browser session)
   Include this file right after <body> on every page.
   ===================================================================== */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* Show once per session. Change to 0 to reuse for a "reset" test. */
$cwt_show_loader = empty($_SESSION['cwt_loader_shown']);

/* Optional: /any-page?reset_loader=1 forces the loader to show again */
if (isset($_GET['reset_loader'])) {
    unset($_SESSION['cwt_loader_shown']);
    $cwt_show_loader = true;
}

if ($cwt_show_loader):

    /* Mark it BEFORE any output so even a redirect still counts */
    $_SESSION['cwt_loader_shown'] = time();

    /* Safe fallback if SITE_URL is not defined yet */
    $cwt_site_url = defined('SITE_URL') ? SITE_URL : '';
?>
<div id="cwt-site-loader" aria-hidden="true">
<style>
/* ============================================================
   COLOR SET 1 — LIGHT MODE (default / base)
   ============================================================ */
#cwt-site-loader{
  --cwt-ldr-blue:#3b5bdb !important;
  --cwt-ldr-stroke:#3b5bdb !important;
  --cwt-ldr-fill:#3b5bdb !important;
  --cwt-ldr-mask:#000 !important;
  --cwt-ldr-transparent:#0000 !important;
  --cwt-ldr-shadow:rgba(0,0,0,.1) !important;
  --cwt-ldr-fallback-bg:rgba(248,249,250,.75) !important;
}

/* ============================================================
   COLOR SET 2 — DARK MODE
   ============================================================ */
#cwt-site-loader.cwt-loader-dark{
  --cwt-ldr-blue:#ffffff !important;
  --cwt-ldr-stroke:#ffffff !important;
  --cwt-ldr-fill:#ffffff !important;
  --cwt-ldr-mask:#fff !important;
  --cwt-ldr-transparent:#0000 !important;
  --cwt-ldr-shadow:rgba(0,0,0,.35) !important;
  --cwt-ldr-fallback-bg:rgba(11,20,37,.75) !important;
}

#cwt-site-loader{
  position:fixed;
  inset:0;
  z-index:99999;
  display:flex;
  justify-content:center;
  align-items:center;
  background:var(--cwt-ldr-transparent) !important;
  -webkit-backdrop-filter:blur(14px) saturate(120%);
  backdrop-filter:blur(14px) saturate(120%);
  transition:opacity .5s ease,visibility .5s ease
}
@supports not ((backdrop-filter:blur(1px)) or (-webkit-backdrop-filter:blur(1px))){
  #cwt-site-loader{background:var(--cwt-ldr-fallback-bg) !important}
}
#cwt-site-loader.cwt-loader-hide{opacity:0;visibility:hidden;pointer-events:none}

.cwt-ldr-inner{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:22px;padding:0 16px;box-sizing:border-box;max-width:100%}
.cwt-ldr-box{position:relative;width:160px;height:160px;display:flex;justify-content:center;align-items:center}
.cwt-ldr-glow{
  position:absolute;inset:5px;border-radius:25px;padding:12px;box-sizing:border-box;
  mask:conic-gradient(var(--cwt-ldr-mask) 0 0) content-box exclude,conic-gradient(var(--cwt-ldr-mask) 0 0) !important;
  -webkit-mask:conic-gradient(var(--cwt-ldr-mask) 0 0) content-box exclude,conic-gradient(var(--cwt-ldr-mask) 0 0) !important;
  filter:blur(12px);z-index:1
}
.cwt-ldr-glow::before{
  content:"";position:absolute;inset:0;border-radius:inherit;
  background:repeating-conic-gradient(var(--cwt-ldr-transparent) 0 5%,var(--cwt-ldr-blue),var(--cwt-ldr-transparent) 20% 50%) !important;
  animation:cwtLdrSpin 1.5s linear infinite
}
.cwt-ldr-frame{position:absolute;inset:-15px;width:calc(100% + 30px);height:calc(100% + 30px);display:grid;z-index:2;pointer-events:none}
.cwt-ldr-frame::before,.cwt-ldr-frame::after{
  content:"";grid-area:1/1;
  --c:var(--cwt-ldr-transparent) calc(100%/3),var(--cwt-ldr-blue) 0 calc(2*100%/3),var(--cwt-ldr-transparent) 0;
  --c1:linear-gradient(90deg,var(--c));
  --c2:linear-gradient(0deg,var(--c));
  background-image:var(--c1),var(--c2),var(--c1),var(--c2) !important;
  background-position:50% 0,100% 100%,0 100%,0 0;
  background-size:300% 3px,3px 300%;
  background-repeat:no-repeat;
  animation:cwtLdrDash 1.5s infinite
}
.cwt-ldr-frame::after{animation-delay:-.75s}
.cwt-ldr-img{
  width:80px;height:80px;border-radius:16px;z-index:10;position:relative;object-fit:contain;
  background-color:var(--cwt-ldr-transparent) !important;
  filter:drop-shadow(0 4px 6px var(--cwt-ldr-shadow)) !important;
  animation:cwtLdrZoom 2s ease-in-out infinite
}

.cwt-ldr-text{
  width:fit-content;
  max-width:92vw;
  font-size:24px;
  font-family:system-ui,sans-serif;
  font-weight:bold;
  text-transform:uppercase;
  text-align:center;
  line-height:1.25;
  letter-spacing:.02em;
  color:var(--cwt-ldr-transparent) !important;
  -webkit-text-stroke:1px var(--cwt-ldr-stroke) !important;
  background-image:conic-gradient(var(--cwt-ldr-fill) 0 0) !important;
  background-size:0% 100%;
  background-position:0 0;
  background-repeat:no-repeat;
  -webkit-background-clip:text;
  background-clip:text;
  animation:cwtLdrText 3s linear forwards;
}
.cwt-ldr-text:before{content:"Creative Web Technologies"}

@keyframes cwtLdrSpin{to{rotate:1turn}}
@keyframes cwtLdrDash{0%{background-position:50% 0,100% 100%,0 100%,0 0}25%{background-position:0 0,100% 50%,0 100%,0 0}50%{background-position:0 0,100% 0,50% 100%,0 0}75%{background-position:0 0,100% 0,100% 100%,0 50%}75.01%{background-position:100% 0,100% 0,100% 100%,0 50%}100%{background-position:50% 0,100% 0,100% 100%,0 100%}}
@keyframes cwtLdrZoom{0%,100%{transform:scale(1)}50%{transform:scale(1.15)}}
@keyframes cwtLdrText{from{background-size:0% 100%}to{background-size:100% 100%}}

/* ================= XXXL / Ultrawide : 1501px and above ================= */
@media (min-width:1501px){
  .cwt-ldr-box{width:200px;height:200px}
  .cwt-ldr-img{width:104px;height:104px;border-radius:20px}
  .cwt-ldr-inner{gap:30px}
  .cwt-ldr-text{font-size:40px}
}
/* ================= XXL / Ultrawide : 1400px - 1500px ================= */
@media (min-width:1400px) and (max-width:1500px){
  .cwt-ldr-box{width:190px;height:190px}
  .cwt-ldr-img{width:96px;height:96px;border-radius:19px}
  .cwt-ldr-inner{gap:28px}
  .cwt-ldr-text{font-size:40px}
}
/* ================= XL : 1200px - 1399px ================= */
@media (min-width:1200px) and (max-width:1399px){
  .cwt-ldr-box{width:175px;height:175px}
  .cwt-ldr-img{width:90px;height:90px;border-radius:18px}
  .cwt-ldr-inner{gap:26px}
  .cwt-ldr-text{font-size:40px}
}
/* ================= Large : 1001px - 1199px ================= */
@media (min-width:1001px) and (max-width:1199px){
  .cwt-ldr-box{width:160px;height:160px}
  .cwt-ldr-img{width:84px;height:84px;border-radius:17px}
  .cwt-ldr-inner{gap:24px}
  .cwt-ldr-text{font-size:40px}
}
/* ================= Normal : 1000px ================= */
@media (min-width:1000px) and (max-width:1000px){
  .cwt-ldr-box{width:150px;height:150px}
  .cwt-ldr-img{width:78px;height:78px;border-radius:16px}
  .cwt-ldr-inner{gap:22px}
  .cwt-ldr-text{font-size:40px}
}
/* ================= Normal Medium : 992px - 999px ================= */
@media (min-width:992px) and (max-width:999px){
  .cwt-ldr-box{width:145px;height:145px}
  .cwt-ldr-img{width:76px;height:76px;border-radius:15px}
  .cwt-ldr-inner{gap:21px}
  .cwt-ldr-text{font-size:35px}
}
/* ================= Medium / Tablets : 768px - 991px ================= */
@media (min-width:768px) and (max-width:991px){
  .cwt-ldr-box{width:140px;height:140px}
  .cwt-ldr-img{width:72px;height:72px;border-radius:15px}
  .cwt-ldr-inner{gap:20px}
  .cwt-ldr-text{font-size:32px}
}
/* ================= Small / Large phones landscape : 576px - 767px ================= */
@media (min-width:576px) and (max-width:767px){
  .cwt-ldr-box{width:130px;height:130px}
  .cwt-ldr-img{width:66px;height:66px;border-radius:14px}
  .cwt-ldr-inner{gap:18px}
  .cwt-ldr-text{font-size:30px}
}
/* ================= Extra Small : 481px - 575px ================= */
@media (min-width:481px) and (max-width:575px){
  .cwt-ldr-box{width:120px;height:120px}
  .cwt-ldr-img{width:60px;height:60px;border-radius:13px}
  .cwt-ldr-inner{gap:16px;padding:0 14px}
  .cwt-ldr-text{font-size:28px}
}
/* ================= Very Small : 380px - 480px ================= */
@media (min-width:380px) and (max-width:480px){
  .cwt-ldr-box{width:110px;height:110px}
  .cwt-ldr-img{width:54px;height:54px;border-radius:12px}
  .cwt-ldr-inner{gap:14px;padding:0 12px}
  .cwt-ldr-text{font-size:25px}
}
/* ================= Ultra Small : 379px and below ================= */
@media (max-width:379px){
  .cwt-ldr-box{width:100px;height:100px}
  .cwt-ldr-img{width:48px;height:48px;border-radius:11px}
  .cwt-ldr-inner{gap:12px;padding:0 10px}
  .cwt-ldr-text{font-size:20px}
}
/* ================= Short landscape screens (height based) ================= */
@media (max-height:420px) and (orientation:landscape){
  .cwt-ldr-box{width:100px;height:100px}
  .cwt-ldr-img{width:52px;height:52px;border-radius:12px}
  .cwt-ldr-inner{gap:12px}
  .cwt-ldr-text{font-size:30px}
}
</style>
<div class="cwt-ldr-inner">
  <div class="cwt-ldr-box">
    <div class="cwt-ldr-glow"></div>
    <div class="cwt-ldr-frame"></div>
    <img
      src="<?= htmlspecialchars($cwt_site_url, ENT_QUOTES) ?>/assets/logos/Creativelogo_Blue.png"
      data-src-light="<?= htmlspecialchars($cwt_site_url, ENT_QUOTES) ?>/assets/logos/Creativelogo_Blue.png"
      data-src-dark="<?= htmlspecialchars($cwt_site_url, ENT_QUOTES) ?>/assets/logos/Creativelogo_white.png"
      alt=""
      class="cwt-ldr-img">
  </div>
  <div class="cwt-ldr-text"></div>
</div>
</div>
<script>
(function(){
  var ldr = document.getElementById('cwt-site-loader');
  if (!ldr) return;

  var isDark = (document.documentElement.getAttribute('data-theme') == 'dark');
  ldr.style.setProperty('--cwt-ldr-blue', isDark ? '#748ffc' : '#3b5bdb');
  ldr.classList.add(isDark ? 'cwt-loader-dark' : 'cwt-loader-light');

  /* NEW — swap logo based on current theme */
  var logo = ldr.querySelector('.cwt-ldr-img');
  if (logo) {
    logo.src = isDark ? logo.getAttribute('data-src-dark')
                      : logo.getAttribute('data-src-light');
  }

  /* body may not be parsed yet if this file is included in <head> */
  if (document.body) document.body.style.overflow = 'hidden';

  function dismiss(){
    ldr.classList.add('cwt-loader-hide');
    if (document.body) document.body.style.overflow = '';
    setTimeout(function(){
      if (ldr.parentNode) ldr.parentNode.removeChild(ldr);
    }, 600);
  }
  setTimeout(dismiss, 3000);
})();
</script>
<?php endif; ?>