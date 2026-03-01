<head>
    <script>window.__pageStartTime = performance.now();</script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Photogram — Share photos and discover inspiration with a community of visual storytellers.">
    <meta name="author" content="Guruprasanth M">
    <title>Photogram</title>

    <!-- Favicon (camera SVG inline data URI) -->
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%237c3aed' stroke-width='2'%3E%3Cpath d='M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z'/%3E%3Ccircle cx='12' cy='13' r='4'/%3E%3C/svg%3E" type="image/svg+xml">

    <!-- Bootstrap CSS -->
    <link href="<?=get_config('base_path')?>assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts: Inter + Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- App CSS (new theme) -->
    <link href="<?=get_config('base_path')?>css/style.css" rel="stylesheet">

    <!-- FingerprintJS (for session binding) -->
    <script src="https://cdn.jsdelivr.net/npm/@fingerprintjs/fingerprintjs@3/dist/fp.min.js"></script>
    <script>
    (function() {
        if (typeof FingerprintJS !== 'undefined') {
            FingerprintJS.load().then(fp => fp.get()).then(result => {
                document.querySelectorAll('#fingerprint').forEach(el => el.value = result.visitorId);
            });
        } else {
            const fallback = 'fb-' + Math.random().toString(36).substring(2, 9);
            document.querySelectorAll('#fingerprint').forEach(el => el.value = fallback);
        }
    })();
    </script>
</head>