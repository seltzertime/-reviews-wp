/**
 * Custom Reviews Display - Admin Scripts
 *
 * @package Custom_Reviews_Display
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        // Initialize color pickers
        if (typeof $.fn.wpColorPicker !== 'undefined') {
            $('.crd-color-picker').wpColorPicker();
        }

        // Add helpful tooltips or interactions here if needed
        console.log('Custom Reviews Display admin scripts loaded');
    });

})(jQuery);
