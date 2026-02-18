/**
 * Photogram – Main App Utilities
 * General helpers, event handlers, and page-level logic.
 */
$(document).ready(function () {
    'use strict';

    // ── Like / Unlike handler (uses API) ─────────────────
    $(document).on('click', '.btn-like', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var postId = $btn.parent().attr('data-id');
        if (!postId) return;

        $.post(window.__BASE_PATH + 'api/posts/like', { id: postId }, function (data) {
            if (data && data.message === 'success') {
                $btn.toggleClass('liked');
            }
        }, 'json');
    });

    // ── Delete post handler (uses Dialog + API) ─────────
    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault();
        var post_id = $(this).parent().attr('data-id');
        var d = new Dialog("Delete Post", "Are you sure you want to remove this post?");
        d.setButtons([
            {
                'name': "Delete",
                'class': "btn-danger",
                'onClick': function (event) {
                    $.post(window.__BASE_PATH + 'api/posts/delete', {
                        id: post_id
                    }, function (data, textSuccess) {
                        if (textSuccess === "success") {
                            $('#post-' + post_id).fadeOut(300, function () {
                                $(this).remove();
                                // Trigger masonry re-layout
                                var grid = document.getElementById('masonry-grid');
                                if (grid && typeof Masonry !== 'undefined') {
                                    var msnry = Masonry.data(grid);
                                    if (msnry) msnry.layout();
                                }
                            });
                        }
                    });
                    $(event.data.modal).modal('hide');
                }
            },
            {
                'name': "Cancel",
                'class': "btn-secondary",
                'onClick': function (event) {
                    $(event.data.modal).modal('hide');
                }
            }
        ]);
        d.show();
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
