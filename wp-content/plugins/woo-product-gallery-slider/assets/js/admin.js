(function ($) {
  'use strict';
  $.wpgs_variation_images = {
    init: function () {

      $('#woocommerce-product-data').on('woocommerce_variations_loaded', function () {
        $('.csf-onload').csf_reload_script();
      });

      $('#variable_product_options').on('woocommerce_variations_added', function () {
        $('.csf-onload').csf_reload_script();
      });

    },


  };
  $.wpgs_variation_images.init();

  $(document).ready(function () {
    console.log('Experimental Feature: Additional Variation Images is current Active');
  });


})(jQuery);

// Other code using $ as an alias to the other library