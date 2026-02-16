/**
 * Photogram – FingerprintJS Initializer
 * Generates a browser fingerprint and injects it into the
 * hidden #fingerprint input used for device recognition.
 */
$(document).ready(function () {
    'use strict';

    var $field = $('#fingerprint');
    if (!$field.length) return;

    if (typeof FingerprintJS !== 'undefined') {
        FingerprintJS.load()
            .then(function (fp) { return fp.get(); })
            .then(function (result) {
                $field.val(result.visitorId);
            })
            .catch(function () {
                $field.val('fallback-' + Math.random().toString(36).substring(7));
            });
    } else {
        // CDN blocked or unavailable
        $field.val('fallback-' + Math.random().toString(36).substring(7));
    }
});
