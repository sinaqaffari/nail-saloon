// Minimal, dependency-free behavior test for the marquee and review carousel.
const assert = require('assert');
const fs = require('fs');
const vm = require('vm');
function element(extra = {}) {
  return Object.assign({hidden:false, attributes:{}, handlers:{}, classList:{values:new Set(),toggle(name,force){if(force)this.values.add(name);else this.values.delete(name)}}, setAttribute(name,value){this.attributes[name]=value}, getAttribute(name){return this.attributes[name] || null}, addEventListener(name,handler){this.handlers[name]=handler;}}, extra);
}
function run(reduce) {
  const marqueeButton = element();
  const marquee = element({querySelector(s){return s === '.lune-marquee-toggle' ? marqueeButton : null;}});
  const slides = [element(),element(),element()];
  const previous=element(), next=element(), pause=element(), count=element();
  const controls=element({querySelector(s){return s === '[data-review-prev]' ? previous : s === '[data-review-next]' ? next : null;}});
  const carousel=element({querySelectorAll(){return slides;},querySelector(s){return s === '.lune-review-controls' ? controls : s === '.lune-review-count' ? count : s === '[data-review-pause]' ? pause : null;}, contains(){return false;}});
  let intervals = 0, clears = 0;
  const document={hidden:false,addEventListener(){},querySelectorAll(s){return s === '.lune-marquee' ? [marquee] : s === '.lune-review-carousel' ? [carousel] : [];}};
  const window={matchMedia(){return {matches:reduce,addEventListener(){}}},setInterval(){intervals++;return intervals},clearInterval(){clears++}};
  vm.runInNewContext(fs.readFileSync('wp-content/themes/nue-studio/assets/js/lune.js','utf8'),{document,window});
  assert.equal(marqueeButton.hidden,reduce);
  assert.equal(pause.hidden,reduce);
  assert.equal(slides.filter(x=>!x.hidden).length,1);
  next.handlers.click();
  assert.equal(slides[0].hidden,true); assert.equal(slides[1].hidden,false);
  assert.equal(pause.getAttribute('aria-pressed'),'true');
  if (!reduce) {
    assert.equal(intervals,1); assert.equal(clears,1);
    marqueeButton.handlers.click();
    assert.equal(marqueeButton.getAttribute('aria-pressed'),'true');
    assert(marquee.classList.values.has('is-paused'));
    pause.handlers.click(); assert.equal(intervals,2);
  } else { assert.equal(intervals,0); }
}
run(false); run(true);
console.log('Marquee, review carousel and reduced-motion behavior passed.');
