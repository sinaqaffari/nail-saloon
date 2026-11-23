(function (blocks, element, serverSideRender) {
  'use strict';
  var titles = {services:'Service cards', pricing:'Service pricing', gallery:'Nail art gallery', team:'Team profiles', testimonials:'Testimonials', booking:'Acuity scheduler', 'contact-info':'Studio contact information', 'contact-form':'Contact form', detail:'Content details', share:'Article sharing', 'related-posts':'Related articles', 'featured-post':'Featured article', 'gift-link':'Gift card purchase link'};
  Object.keys(titles).forEach(function (name) {
    blocks.registerBlockType('nue/' + name, {
      apiVersion: 2, title: titles[name], category: 'widgets', icon: 'heart',
      attributes: {limit:{type:'number',default:-1}},
      edit: function (props) { return element.createElement('div', {className:'nue-editor-block'}, element.createElement('strong', null, titles[name]), element.createElement(serverSideRender, {block:'nue/' + name, attributes:props.attributes})); },
      save: function () { return null; }
    });
  });
})(window.wp.blocks, window.wp.element, window.wp.serverSideRender);
