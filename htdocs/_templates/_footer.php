<footer class="py-4">
    <div class="container d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <p class="mb-1">&copy; 2026 Photogram - Shared Memories.</p>
            <p class="mb-0">
                <a href="<?=get_config('base_path')?>">Home</a> &middot; 
                <a href="#">Privacy</a> &middot; 
                <a href="#">Terms</a>
            </p>
        </div>
        <span class="page-load-time"></span>
    </div>
</footer>
<script>
(function(){
    var startTime = window.__pageStartTime || performance.now();
    window.addEventListener('load', function(){
        setTimeout(function(){
            var totalMs = performance.now() - startTime;
            var display;
            if (totalMs < 1) display = (totalMs * 1000).toFixed(0) + 'μs';
            else if (totalMs < 1000) display = totalMs.toFixed(2) + 'ms';
            else display = (totalMs / 1000).toFixed(2) + 's';
            var el = document.querySelector('.page-load-time');
            if (el) el.textContent = display + ' to load';
        }, 0);
    });
    // Fallback if load event already fired
    if (document.readyState === 'complete') {
        var totalMs = performance.now() - startTime;
        var display;
        if (totalMs < 1) display = (totalMs * 1000).toFixed(0) + 'μs';
        else if (totalMs < 1000) display = totalMs.toFixed(2) + 'ms';
        else display = (totalMs / 1000).toFixed(2) + 's';
        var el = document.querySelector('.page-load-time');
        if (el) el.textContent = display + ' to load';
    }
})();
</script>