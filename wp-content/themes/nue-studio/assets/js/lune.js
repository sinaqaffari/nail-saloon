(function () {
  'use strict';
  var reduced = window.matchMedia('(prefers-reduced-motion: reduce)');
  if ('IntersectionObserver' in window && !reduced.matches) {
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) { entry.target.classList.add('is-in-view'); observer.unobserve(entry.target); }
      });
    }, {threshold: 0.1, rootMargin: '0px 0px -50px 0px'});
    document.querySelectorAll('.lune-section > h2, .lune-service-row, .gallery-card, .lune-steps .wp-block-column, .lune-price-card, .team-card, .lune-hero-media .wp-block-image, .footer-logo').forEach(function (element, index) {
      element.classList.add('nue-reveal');
      element.style.setProperty('--reveal-delay', (index % 4 * 0.05) + 's');
      // A fully clipped element has zero intersection area; reveal hero images
      // on the next frame instead of observing their clipped bounds.
      if (element.closest('.lune-hero-media')) {
        window.requestAnimationFrame(function () { element.classList.add('is-in-view'); });
      } else {
        observer.observe(element);
      }
    });
  }
  // Keep the decorative ticker static without JS and offer a pause control.
  document.querySelectorAll('.lune-marquee').forEach(function (marquee) {
    var toggle = marquee.querySelector('.lune-marquee-toggle');
    if (!toggle) return;
    toggle.setAttribute('aria-pressed', 'false');
    function motionSetting() {
      toggle.hidden = reduced.matches;
      marquee.classList.toggle('motion-enabled', !reduced.matches);
    }
    motionSetting();
    toggle.addEventListener('click', function () {
      var paused = toggle.getAttribute('aria-pressed') !== 'true';
      toggle.setAttribute('aria-pressed', paused ? 'true' : 'false');
      toggle.textContent = paused ? 'ادامه حرکت' : 'توقف حرکت';
      marquee.classList.toggle('is-paused', paused);
    });
    if (reduced.addEventListener) reduced.addEventListener('change', motionSetting);
  });
  document.querySelectorAll('.lune-review-carousel').forEach(function (carousel) {
    var slides = Array.prototype.slice.call(carousel.querySelectorAll('.lune-review-slide'));
    var controls = carousel.querySelector('.lune-review-controls');
    if (slides.length < 2 || !controls) return;
    var current = 0, timer = null, paused = false;
    var count = carousel.querySelector('.lune-review-count');
    var pauseButton = carousel.querySelector('[data-review-pause]');
    function setPaused(value) { paused = value; if (pauseButton) { pauseButton.setAttribute('aria-pressed', value ? 'true' : 'false'); pauseButton.textContent = value ? 'ادامه پخش' : 'توقف پخش'; } }
    function show(index, announce) {
      current = (index + slides.length) % slides.length;
      slides.forEach(function (slide, i) { slide.hidden = i !== current; });
      count.setAttribute('aria-live', announce ? 'polite' : 'off');
      count.textContent = (current + 1).toLocaleString('fa-IR') + ' / ' + slides.length.toLocaleString('fa-IR');
    }
    function stop() { if (timer) { window.clearInterval(timer); timer = null; } }
    function start() { stop(); if (!paused && !reduced.matches && !document.hidden) timer = window.setInterval(function () { show(current + 1, false); }, 7000); }
    controls.hidden = false; if (pauseButton) pauseButton.hidden = reduced.matches; show(0, false); start();
    controls.querySelector('[data-review-prev]').addEventListener('click', function () { setPaused(true); stop(); show(current - 1, true); });
    controls.querySelector('[data-review-next]').addEventListener('click', function () { setPaused(true); stop(); show(current + 1, true); });
    if (pauseButton) pauseButton.addEventListener('click', function () { setPaused(!paused); start(); });
    carousel.addEventListener('mouseenter', stop); carousel.addEventListener('mouseleave', start);
    carousel.addEventListener('focusin', stop); carousel.addEventListener('focusout', function (event) { if (!carousel.contains(event.relatedTarget)) start(); });
    document.addEventListener('visibilitychange', start);
    if (reduced.addEventListener) reduced.addEventListener('change', function () { if (pauseButton) pauseButton.hidden = reduced.matches; start(); });
  });
})();
