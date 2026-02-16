/**
 * Photogram – Main App Utilities
 * General helpers, event handlers, and page-level logic.
 */
$(document).ready(function () {
    'use strict';

    // ── Like / Unlike handler ────────────────────────────
    $(document).on('click', '.btn-like', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var postId = $btn.data('post-id');
        if (!postId) return;

        $.post('setnget.php', { action: 'like', post_id: postId }, function (res) {
            if (res && res.success) {
                $btn.toggleClass('liked');
            }
        }, 'json');
    });

    // ── Delete post handler ──────────────────────────────
    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault();
        if (!confirm('Delete this memory?')) return;
        var $btn = $(this);
        var postId = $btn.data('post-id');
        if (!postId) return;

        $.post('setnget.php', { action: 'delete', post_id: postId }, function (res) {
            if (res && res.success) {
                $btn.closest('.col-lg-4').fadeOut(300, function () {
                    $(this).remove();
                    // Trigger masonry re-layout
                    var grid = document.getElementById('masonry-grid');
                    if (grid && typeof Masonry !== 'undefined') {
                        var msnry = Masonry.data(grid);
                        if (msnry) msnry.layout();
                    }
                });
            }
        }, 'json');
    });

    // ── Share handler (Web Share API) ────────────────────
    $(document).on('click', '.btn-share', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var url = $btn.data('url') || window.location.href;
        var title = $btn.data('title') || 'Check out this photo on Photogram!';

        if (navigator.share) {
            navigator.share({ title: title, url: url }).catch(function () {});
        } else {
            // Fallback: copy URL to clipboard
            navigator.clipboard.writeText(url).then(function () {
                alert('Link copied to clipboard!');
            });
        }
    });

    // ── Auto-resize textarea in upload form ──────────────
    $(document).on('input', '.cta-glass textarea', function () {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // ── Smooth scroll to top ─────────────────────────────
    $(document).on('click', '.scroll-top', function (e) {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
});
