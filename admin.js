/**
 * Admin script for the Rano Floating WhatsApp Chat plugin.
 *
 * This script initialises the `intl-tel-input` library on the phone number
 * input field.  It automatically prepends the selected country dial code
 * and ensures the stored value includes the full international number when
 * the settings form is submitted.  By leveraging the external library we
 * avoid maintaining a long list of country codes in PHP and benefit from
 * built‑in formatting and validation.
 */
(function ($) {
    'use strict';

    $(document).ready(function () {
        var input = document.querySelector('#fwac_phone_number');
        if (!input || typeof window.intlTelInput === 'undefined') {
            return;
        }

        // Initialise the telephone input with auto country detection based on
        // the user’s IP. Fallback to Bangladesh if detection fails.
        var iti = window.intlTelInput(input, {
            initialCountry: 'auto',
            // Provide the utils script path so the library can format numbers correctly.
            utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js',
            geoIpLookup: function (callback) {
                // Use a free service to determine the country.  If it fails,
                // default to Bangladesh (BD).  Note: This call is asynchronous;
                // we simply pass the country code to the callback.
                $.get('https://ipapi.co/json/').done(function (resp) {
                    var countryCode = resp && resp.country_code ? resp.country_code.toLowerCase() : 'bd';
                    callback(countryCode);
                }).fail(function () {
                    callback('bd');
                });
            }
        });

        // When the form is submitted, update the input value to contain the
        // full international number (E.164 format without the plus sign).  This
        // ensures the correct dial code is stored in the WordPress option.
        var form = $(input).closest('form');
        form.on('submit', function () {
            var number = iti.getNumber();
            if (number) {
                // Remove plus sign – WhatsApp API expects the number without it
                number = number.replace(/^\+/, '');
                input.value = number;
            }
        });
    });
})(jQuery);