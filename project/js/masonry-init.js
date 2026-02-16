/**
 * Photogram – Masonry Grid Initializer
 * Initializes Masonry layout on the photo grid and re-lays out
 * after all images have loaded.
 */
$(document).ready(function () {
    'use strict';

    var grid = document.getElementById('masonry-grid');

    if (!grid) return;

    // Wait for all images to load before laying out masonry
    imagesLoaded(grid, function () {
        var msnry = new Masonry(grid, {
            itemSelector: '.col-lg-4',
            percentPosition: true,
            transitionDuration: '0.3s'
        });

        // Re-layout when new images are lazy-loaded (if applicable)
        var observer = new MutationObserver(function () {
            imagesLoaded(grid, function () {
                msnry.reloadItems();
                msnry.layout();
            });
        });

        observer.observe(grid, { childList: true, subtree: true });
    });
});
