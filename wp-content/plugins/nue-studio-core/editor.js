(function (blocks, element, serverSideRender) {
  'use strict';
  var titles = {services:'کارت‌های خدمات', pricing:'قیمت خدمات', gallery:'گالری طراحی ناخن', team:'پروفایل تیم', testimonials:'دیدگاه‌ها', booking:'سامانه رزرو Acuity', 'contact-info':'اطلاعات تماس استودیو', 'contact-form':'فرم تماس', detail:'جزئیات محتوا', share:'اشتراک‌گذاری مطلب', 'related-posts':'مطالب مرتبط', 'featured-post':'مطلب برگزیده', 'gift-link':'پیوند خرید کارت هدیه'};
  Object.keys(titles).forEach(function (name) {
    blocks.registerBlockType('nue/' + name, {
      apiVersion: 2, title: titles[name], category: 'widgets', icon: 'heart',
      attributes: {limit:{type:'number',default:-1}},
      edit: function (props) { return element.createElement('div', {className:'nue-editor-block'}, element.createElement('strong', null, titles[name]), element.createElement(serverSideRender, {block:'nue/' + name, attributes:props.attributes})); },
      save: function () { return null; }
    });
  });
})(window.wp.blocks, window.wp.element, window.wp.serverSideRender);
