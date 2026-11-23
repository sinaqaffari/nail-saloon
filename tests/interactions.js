// Lightweight behavior test without a browser or third-party dependencies.
const assert = require('assert');
const fs = require('fs');
const vm = require('vm');
function element(extra = {}) { return Object.assign({ handlers: {}, addEventListener(name, fn) { this.handlers[name] = fn; } }, extra); }
const all = element({dataset:{filter:'all'}, classList:{toggle(){}}, setAttribute(k,v){this[k]=v;}});
const french = element({dataset:{filter:'french'}, classList:{toggle(k,v){this.active=v;}}, setAttribute(k,v){this[k]=v;}});
const abstract = element({dataset:{filter:'abstract'}, classList:{toggle(){}}, setAttribute(k,v){this[k]=v;}});
const cards = [{dataset:{categories:'french classic'}},{dataset:{categories:'chrome'}}];
const empty = {hidden:true};
const filters = element({querySelectorAll(){return [all,french,abstract];}, nextElementSibling:{querySelectorAll(){return cards;},querySelector(){return empty;}}});
const image = {removeAttribute(){this.src='';}};
const close = element({focus(){this.focused=true;}});
const dialog = element({matches(){return true;}, querySelector(s){return s === 'img' ? image : close;}, showModal(){this.open=true;}, close(){this.open=false; this.handlers.close();}});
const grid = element({nextElementSibling:dialog});
const document = {querySelectorAll(s){return s === '.gallery-filters' ? [filters] : [grid];}};
vm.runInNewContext(fs.readFileSync('wp-content/themes/nue-studio/assets/js/site.js','utf8'), {document});
filters.handlers.click({target:{closest(){return french;}}});
assert.equal(cards[0].hidden,false); assert.equal(cards[1].hidden,true); assert.equal(french['aria-pressed'],'true');
filters.handlers.click({target:{closest(){return abstract;}}}); assert.equal(empty.hidden,false);
filters.handlers.click({target:{closest(){return all;}}}); assert.equal(cards[1].hidden,false); assert.equal(empty.hidden,true);
const trigger = {dataset:{image:'image.jpg',alt:'French nails'},focus(){this.focused=true;}};
grid.handlers.click({target:{closest(){return trigger;}}}); assert.equal(dialog.open,true); assert.equal(image.alt,'French nails');
close.handlers.click(); assert.equal(dialog.open,false); assert.equal(trigger.focused,true);
console.log('Gallery filtering and lightbox behavior passed.');
