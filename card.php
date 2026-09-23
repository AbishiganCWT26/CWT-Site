<!-- HTML -->
<div class="fx-carousel" id="fxCarousel">

  <!-- horizontally scrolling track -->
  <div class="fx-track" id="fxTrack" tabindex="0"
       role="region" aria-label="Featured destinations, scrolls automatically">

    <article class="fx-hover-reveal" tabindex="0">
      <img class="fx-hover-reveal__img" src="https://picsum.photos/seed/peak/480/360" alt="Mountain Escape" loading="lazy">
      <div class="fx-hover-reveal__title">Allianz Insight Reporting (AIR)</div>
      <div class="reveal-layer"><p>To eliminate fragmented data silos and slow manual reporting, Creative Web Technologies partnered with Allianz to build the Allianz Insight Reporting platform. This cloud-native Azure solution automates ETL pipelines from 13+ systems into a centralized, three-tier warehouse. Delivering real-time Power BI dashboards with transaction-level drill-downs, it ensures a single source of truth. The business impact: a 40% cost reduction and strategic decisions made in minutes, not days.</p></div>
    </article>

    <article class="fx-hover-reveal" tabindex="0">
      <img class="fx-hover-reveal__img" src="https://picsum.photos/seed/coast/480/360" alt="Coastal Drive" loading="lazy">
      <div class="fx-hover-reveal__title">Coastal Drive</div>
      <div class="reveal-layer"><p>Winding roads along the shoreline, with hidden coves and fresh seafood stops around every bend.</p></div>
    </article>

    <article class="fx-hover-reveal" tabindex="0">
      <img class="fx-hover-reveal__img" src="https://picsum.photos/seed/desert/480/360" alt="Desert Bloom" loading="lazy">
      <div class="fx-hover-reveal__title">Desert Bloom</div>
      <div class="reveal-layer"><p>Golden dunes that flower after the rain — a landscape that changes colour hour by hour.</p></div>
    </article>

    <article class="fx-hover-reveal" tabindex="0">
      <img class="fx-hover-reveal__img" src="https://picsum.photos/seed/forest/480/360" alt="Forest Cabin" loading="lazy">
      <div class="fx-hover-reveal__title">Forest Cabin</div>
      <div class="reveal-layer"><p>Pine-scented mornings, wood smoke and total silence — just two hours from the city.</p></div>
    </article>

    <article class="fx-hover-reveal" tabindex="0">
      <img class="fx-hover-reveal__img" src="https://picsum.photos/seed/city/480/360" alt="City Lights" loading="lazy">
      <div class="fx-hover-reveal__title">City Lights</div>
      <div class="reveal-layer"><p>Rooftop views, late-night markets and a skyline that never really switches off.</p></div>
    </article>

    <article class="fx-hover-reveal" tabindex="0">
      <img class="fx-hover-reveal__img" src="https://picsum.photos/seed/island/480/360" alt="Island Hop" loading="lazy">
      <div class="fx-hover-reveal__title">Island Hop</div>
      <div class="reveal-layer"><p>Slow ferries, turquoise water and a different beach for every day of the week.</p></div>
    </article>

    <article class="fx-hover-reveal" tabindex="0">
      <img class="fx-hover-reveal__img" src="https://picsum.photos/seed/vineyard/480/360" alt="Vineyard Stay" loading="lazy">
      <div class="fx-hover-reveal__title">Vineyard Stay</div>
      <div class="reveal-layer"><p>Rolling rows of vines, long lunches and cellars older than the village itself.</p></div>
    </article>

    <article class="fx-hover-reveal" tabindex="0">
      <img class="fx-hover-reveal__img" src="https://picsum.photos/seed/aurora/480/360" alt="Northern Glow" loading="lazy">
      <div class="fx-hover-reveal__title">Northern Glow</div>
      <div class="reveal-layer"><p>Chase the aurora across frozen lakes and fall asleep under a glass roof.</p></div>
    </article>

    <article class="fx-hover-reveal" tabindex="0">
      <img class="fx-hover-reveal__img" src="https://picsum.photos/seed/lake/480/360" alt="Lake Mirror" loading="lazy">
      <div class="fx-hover-reveal__title">Lake Mirror</div>
      <div class="reveal-layer"><p>Still water, morning mist and a canoe waiting at the end of the jetty.</p></div>
    </article>

    <article class="fx-hover-reveal" tabindex="0">
      <img class="fx-hover-reveal__img" src="https://picsum.photos/seed/oldtown/480/360" alt="Old Town" loading="lazy">
      <div class="fx-hover-reveal__title">Old Town</div>
      <div class="reveal-layer"><p>Cobbled lanes, hidden courtyards and cafés that have kept the same corner for decades.</p></div>
    </article>

    <article class="fx-hover-reveal" tabindex="0">
      <img class="fx-hover-reveal__img" src="https://picsum.photos/seed/canyon/480/360" alt="Canyon Trail" loading="lazy">
      <div class="fx-hover-reveal__title">Canyon Trail</div>
      <div class="reveal-layer"><p>Red rock walls, river crossings and a view that opens up right at the last switchback.</p></div>
    </article>

    <article class="fx-hover-reveal" tabindex="0">
      <img class="fx-hover-reveal__img" src="https://picsum.photos/seed/market/480/360" alt="Winter Market" loading="lazy">
      <div class="fx-hover-reveal__title">Winter Market</div>
      <div class="reveal-layer"><p>String lights, spiced drinks and stalls that spill out across the whole square.</p></div>
    </article>

    <article class="fx-hover-reveal" tabindex="0">
      <img class="fx-hover-reveal__img" src="https://picsum.photos/seed/bamboo/480/360" alt="Bamboo Grove" loading="lazy">
      <div class="fx-hover-reveal__title">Bamboo Grove</div>
      <div class="reveal-layer"><p>Green light, creaking stalks and a path that disappears into the mist.</p></div>
    </article>

    <article class="fx-hover-reveal" tabindex="0">
      <img class="fx-hover-reveal__img" src="https://picsum.photos/seed/harbor/480/360" alt="Harbour Town" loading="lazy">
      <div class="fx-hover-reveal__title">Harbour Town</div>
      <div class="reveal-layer"><p>Fishing boats at dawn, painted houses and the smell of salt on the breeze.</p></div>
    </article>

    <article class="fx-hover-reveal" tabindex="0">
      <img class="fx-hover-reveal__img" src="https://picsum.photos/seed/highland/480/360" alt="Highland Road" loading="lazy">
      <div class="fx-hover-reveal__title">Highland Road</div>
      <div class="reveal-layer"><p>Empty single-track, heather on both sides and not another car for miles.</p></div>
    </article>

  </div>

  <!-- navigation dots (built by JS) -->
  <div class="fx-dots" id="fxDots" aria-label="Card navigation"></div>
</div>

<style>
:root{
  --primary:#6B5CE7;
  --surface-2:#F0EEFB;
}

/* ---------- carousel shell ---------- */
.fx-carousel{ position:relative; max-width:100%; }

.fx-track{
  position:relative;
  display:flex;
  gap:16px;
  padding:24px 0 28px;
  overflow-x:auto;
  overflow-y:hidden;
  scroll-snap-type:x mandatory;
  scroll-behavior:smooth;
  scrollbar-width:thin;
  scrollbar-color:#d6d1f4 transparent;
  outline:none;
  -webkit-overflow-scrolling:touch;
}
.fx-track::-webkit-scrollbar{ height:6px; }
.fx-track::-webkit-scrollbar-track{ background:transparent; }
.fx-track::-webkit-scrollbar-thumb{ background:#d6d1f4; border-radius:999px; }
.fx-track::-webkit-scrollbar-thumb:hover{ background:#bdb5ef; }
.fx-track:focus-visible{ box-shadow:0 0 0 2px var(--primary); border-radius:14px; }

/* ---------- the card ---------- */
.fx-hover-reveal{
  position:relative;
  flex:0 0 auto;
  width:240px;
  height:170px;
  border-radius:12px;
  background:linear-gradient(135deg,#c7c0f5,#8f83ec); /* fallback if image fails */
  overflow:hidden;
  cursor:pointer;
  isolation:isolate;
  scroll-snap-align:center;
  box-shadow:0 2px 10px rgba(20,16,60,.08);
  transition:transform .35s cubic-bezier(.25,.46,.45,.94),
             box-shadow .35s cubic-bezier(.25,.46,.45,.94);
}
.fx-hover-reveal:hover,
.fx-hover-reveal:focus-visible{
  transform:translateY(-4px);
  box-shadow:0 14px 30px rgba(107,92,231,.28);
  outline:none;
}

/* front: image + title */
.fx-hover-reveal__img{
  display:block;
  width:100%;
  height:100%;
  object-fit:cover;
  transition:transform .5s cubic-bezier(.25,.46,.45,.94);
}
.fx-hover-reveal:hover .fx-hover-reveal__img,
.fx-hover-reveal:focus-visible .fx-hover-reveal__img{
  transform:scale(1.06);
}

.fx-hover-reveal__title{
  position:absolute;
  left:0; right:0; bottom:0;
  padding:26px 14px 12px;
  color:#fff;
  font-size:.85rem;
  font-weight:600;
  line-height:1.25;
  letter-spacing:.01em;
  background:linear-gradient(to top, rgba(10,8,30,.78), rgba(10,8,30,0));
  pointer-events:none;
}

/* hover layer: paragraph */
.fx-hover-reveal .reveal-layer{
  position:absolute;
  inset:0;
  padding:16px;
  background:var(--primary);
  color:#fff;
  display:flex;
  align-items:center;
  justify-content:center;
  text-align:center;
  transform:translateY(100%);
  transition:transform .35s cubic-bezier(.25,.46,.45,.94);
}
.fx-hover-reveal .reveal-layer p{
  margin:0;
  font-size:.72rem;
  font-weight:500;
  line-height:1.55;
  opacity:0;
  transform:translateY(6px);
  transition:opacity .3s ease .08s, transform .3s ease .08s;
}
.fx-hover-reveal:hover .reveal-layer,
.fx-hover-reveal:focus-visible .reveal-layer{
  transform:translateY(0);
}
.fx-hover-reveal:hover .reveal-layer p,
.fx-hover-reveal:focus-visible .reveal-layer p{
  opacity:1;
  transform:translateY(0);
}

/* ---------- dots ---------- */
.fx-dots{
  display:flex;
  justify-content:center;
  flex-wrap:wrap;
  gap:6px;
  margin-top:4px;
}
.fx-dot{
  width:7px; height:7px;
  padding:0; border:0;
  border-radius:50%;
  background:#d6d1f4;
  cursor:pointer;
  transition:background .25s, width .25s, border-radius .25s;
}
.fx-dot:hover{ background:#bdb5ef; }
.fx-dot.is-active{ width:18px; border-radius:999px; background:var(--primary); }
.fx-dot:focus-visible{ outline:2px solid var(--primary); outline-offset:2px; }

/* ---------- reduced motion ---------- */
@media (prefers-reduced-motion: reduce){
  .fx-track{ scroll-behavior:auto; }
  .fx-carousel, .fx-carousel *{ transition-duration:.01ms !important; }
}
</style>

<script>
(function () {
  const carousel = document.getElementById('fxCarousel');
  const track    = document.getElementById('fxTrack');
  const dotsWrap = document.getElementById('fxDots');
  const cards    = Array.from(track.querySelectorAll('.fx-hover-reveal'));

  const DELAY = 5000; // ← change this to alter the 5s interval
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  let index = 0;
  let timer = null;
  let paused = false;
  let lockScrollSync = false;
  let lockTimer = null;

  /* ---------- dots ---------- */
  const dots = cards.map((_, i) => {
    const b = document.createElement('button');
    b.type = 'button';
    b.className = 'fx-dot';
    b.setAttribute('aria-label', 'Go to card ' + (i + 1));
    b.addEventListener('click', () => { goTo(i); restart(); });
    dotsWrap.appendChild(b);
    return b;
  });

  function paintDots() {
    dots.forEach((d, i) => d.classList.toggle('is-active', i === index));
  }

  /* ---------- scrolling ---------- */
  function scrollToCard(i) {
    const card = cards[i];
    if (!card) return;

    const target = card.offsetLeft - (track.clientWidth - card.offsetWidth) / 2;
    const max    = track.scrollWidth - track.clientWidth;
    const left   = Math.max(0, Math.min(target, max));

    // ignore scroll events generated by our own scrolling
    lockScrollSync = true;
    clearTimeout(lockTimer);
    lockTimer = setTimeout(() => { lockScrollSync = false; }, 700);

    track.scrollTo({ left, behavior: reduceMotion ? 'auto' : 'smooth' });
  }

  function goTo(i) {
    index = (i % cards.length + cards.length) % cards.length; // wrap both ways
    paintDots();
    scrollToCard(index);
  }

  /* ---------- autoplay ---------- */
  function start() {
    if (reduceMotion) return;         // never auto-scroll for reduced motion
    stop();
    timer = setInterval(() => {
      if (paused || document.hidden) return;
      goTo(index + 1);
    }, DELAY);
  }
  function stop()    { clearInterval(timer); timer = null; }
  function restart() { start(); }

  /* pause while hovering / focused inside */
  carousel.addEventListener('mouseenter', () => { paused = true;  });
  carousel.addEventListener('mouseleave', () => { paused = false; });
  carousel.addEventListener('focusin',    () => { paused = true;  });
  carousel.addEventListener('focusout', e => {
    if (!carousel.contains(e.relatedTarget)) paused = false;
  });

  /* restart the 5s clock after any manual scrolling */
  ['wheel', 'touchstart', 'pointerdown'].forEach(evt =>
    track.addEventListener(evt, restart, { passive: true })
  );

  /* keep the index in sync when the user scrolls by hand */
  let syncTimer;
  track.addEventListener('scroll', () => {
    if (lockScrollSync) return;
    clearTimeout(syncTimer);
    syncTimer = setTimeout(() => {
      const center = track.scrollLeft + track.clientWidth / 2;
      let best = index, bestDist = Infinity;
      cards.forEach((c, i) => {
        const d = Math.abs(c.offsetLeft + c.offsetWidth / 2 - center);
        if (d < bestDist) { bestDist = d; best = i; }
      });
      if (best !== index) { index = best; paintDots(); }
    }, 150);
  });

  /* keep the current card centred on resize */
  let resizeTimer;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => scrollToCard(index), 150);
  });

  /* arrow keys move one card at a time */
  track.addEventListener('keydown', e => {
    if (e.key === 'ArrowRight') { e.preventDefault(); goTo(index + 1); restart(); }
    if (e.key === 'ArrowLeft')  { e.preventDefault(); goTo(index - 1); restart(); }
  });

  /* ---------- boot ---------- */
  paintDots();
  requestAnimationFrame(() => { goTo(0); start(); });
})();
</script>