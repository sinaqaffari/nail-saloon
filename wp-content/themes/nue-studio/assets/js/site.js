(function () {
  'use strict';
  document.querySelectorAll('.gallery-filters').forEach(function (filters) {
    filters.addEventListener('click', function (event) {
      var button = event.target.closest('button[data-filter]');
      if (!button) return;
      filters.querySelectorAll('button').forEach(function (item) { item.classList.toggle('is-active', item === button); item.setAttribute('aria-pressed', item === button ? 'true' : 'false'); });
      var grid = filters.nextElementSibling;
      if (!grid) return;
      var visible = 0;
      grid.querySelectorAll('.gallery-card').forEach(function (card) { card.hidden = button.dataset.filter !== 'all' && !(card.dataset.categories || '').split(' ').includes(button.dataset.filter); if (!card.hidden) visible++; });
      var empty = grid.querySelector('.gallery-empty');
      if (empty) empty.hidden = visible > 0;
    });
  });
  document.querySelectorAll('.gallery-grid').forEach(function (grid) {
    var dialog = grid.nextElementSibling;
    if (!dialog || !dialog.matches('dialog')) return;
    var close = dialog.querySelector('.lightbox-close');
    var image = dialog.querySelector('img');
    var lastTrigger;
    grid.addEventListener('click', function (event) {
      var trigger = event.target.closest('.lightbox-trigger');
      if (!trigger || typeof dialog.showModal !== 'function') return;
      lastTrigger = trigger; image.src = trigger.dataset.image; image.alt = trigger.dataset.alt || ''; dialog.showModal(); close.focus();
    });
    close.addEventListener('click', function () { dialog.close(); });
    dialog.addEventListener('click', function (event) { if (event.target === dialog) dialog.close(); });
    dialog.addEventListener('close', function () { image.removeAttribute('src'); if (lastTrigger) lastTrigger.focus(); });
  });
})();
