<section class="cta-section text-center container">
	<div class="cta-glass">
		<form method="post" action="<?=get_config('base_path')?>" enctype="multipart/form-data">
			<h1 id="greeting" class="mb-2">What's up,
				<span class="text-success"><?=Session::getUser()->getUsername()?></span>?
			</h1>
			<p class="lead text-muted mb-4">Share a moment with the world.</p>
			<div class="mb-3">
				<textarea id="post_text" name="post_text" class="form-control" placeholder="What's on your mind?"
					rows="2" required></textarea>
			</div>
			<div class="mb-3">
				<input type="file" accept="image/*" class="form-control" name="post_image" id="inputGroupFile02" required>
			</div>
			<button class="btn btn-primary px-5" type="submit">Share Memory</button>
		</form>
	</div>
</section>

<?php
// Handle upload result from query params (set by PRG redirect in index.php)
$upload_status = $_GET['upload'] ?? null;
if ($upload_status === 'success') {
	$msg_title = '✔ Success';
	$msg_text = 'Your photo has been shared successfully!';
	$msg_type = 'success';
} elseif ($upload_status === 'error') {
	$msg_title = '✘ Error';
	$msg_text = Session::get('upload_error', 'Upload failed. Please try again.');
	Session::delete('upload_error');
	$msg_type = 'danger';
}

if ($upload_status) {
?>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		var d = new Dialog(
			<?= json_encode($msg_title) ?>,
			<?= json_encode($msg_text) ?>
		);
		d.setButtons([
			{
				'name': 'OK',
				'class': 'btn-<?= $msg_type ?>',
				'onClick': function(event) {
					$(event.data.modal).modal('hide');
					// Clean URL by removing upload params
					window.history.replaceState({}, document.title, window.__BASE_PATH);
				}
			}
		]);
		d.show(<?= json_encode($msg_type) ?>);
	});
</script>
<?php } ?>