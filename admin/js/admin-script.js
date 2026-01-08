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

        // Media uploader for profile pictures
        var mediaUploader;

        $('.crd-upload-profile-pic').on('click', function(e) {
            e.preventDefault();

            var button = $(this);
            var previewContainer = button.siblings('.crd-profile-pic-preview');
            var inputField = button.siblings('#crd_review_profile_pic');

            // If the uploader object has already been created, reopen the dialog
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }

            // Extend the wp.media object
            mediaUploader = wp.media({
                title: 'Choose Profile Picture',
                button: {
                    text: 'Use This Image'
                },
                multiple: false,
                library: {
                    type: 'image'
                }
            });

            // When a file is selected, run a callback
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();

                // Set the value
                inputField.val(attachment.id);

                // Display preview
                previewContainer.html('<img src="' + attachment.url + '" style="max-width: 100px; height: auto; border-radius: 4px;">');

                // Show remove button if not already visible
                if (button.siblings('.crd-remove-profile-pic').length === 0) {
                    button.after('<button type="button" class="button crd-remove-profile-pic">Remove Image</button>');
                }
            });

            // Open the uploader dialog
            mediaUploader.open();
        });

        // Remove profile picture
        $(document).on('click', '.crd-remove-profile-pic', function(e) {
            e.preventDefault();

            var button = $(this);
            button.siblings('#crd_review_profile_pic').val('');
            button.siblings('.crd-profile-pic-preview').html('');
            button.remove();
        });

        console.log('Custom Reviews Display admin scripts loaded');
    });

})(jQuery);
