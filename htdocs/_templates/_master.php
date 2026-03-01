<!doctype html>
<html lang="en">
<?php Session::loadTemplate('_head'); ?>

<body>

	<div class="pg-bg"></div>

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

	<!-- Dialog modal template (cloned dynamically by dialog.js) -->
	<div id="modalsGarbage" aria-hidden="true">
		<div class="modal fade" id="dummy-dialog-modal" tabindex="-1">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" style="font-weight:700;letter-spacing:-.02em"></h5>
						<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body" style="color:rgba(255,255,255,.75);font-size:.95rem"></div>
					<div class="modal-footer"></div>
				</div>
			</div>
		</div>
	</div>

	<script src="<?=get_config('base_path')?>assets/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js"
		integrity="sha384-GNFwBvfVxBkLMJpYMOABq3c+d3KnQxudP/mGPkzpZSTYykLBNsZEnG2D9G/X/+7D" crossorigin="anonymous"></script>
	<script src="https://unpkg.com/imagesloaded@5/imagesloaded.pkgd.min.js"></script>
	<script>window.__BASE_PATH = '<?=get_config('base_path')?>';</script>
	<script src="<?=get_config('base_path')?>js/app.min.js"></script>
	<script src="<?=get_config('base_path')?>js/dialog.js"></script>
	<script src="<?=get_config('base_path')?>js/toast.js"></script>

</body>

</html>