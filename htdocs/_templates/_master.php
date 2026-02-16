<!doctype html>
<html lang="en">
<?php Session::loadTemplate('_head'); ?>

<body>

	<?php Session::loadTemplate('_header'); ?>
	<main>
		<?php
        if (Session::$isError) {
            Session::loadTemplate('_error');
        } else {
            Session::loadTemplate(Session::currentScript());
        }
?>
	</main>
	<?php Session::loadTemplate('_footer'); ?>
	<script
		src="<?=get_config('base_path')?>assets/dist/js/bootstrap.bundle.min.js">
	</script>
	<script>
	$(document).ready(function() {
		var grid = document.getElementById('masonry-grid');
		if (grid) {
			imagesLoaded(grid, function() {
				new Masonry(grid, {
					itemSelector: '.col-lg-4',
					percentPosition: true
				});
			});
		}
	});
	</script>

</body>

</html>