<section class="cta-section text-center container">
	<div class="cta-glass">
		<h1 id="greeting" class="mb-1">
			Hey, <span class="text-accent"><?=htmlspecialchars(Session::getUser()->getUsername())?></span> 👋
		</h1>
		<p class="lead text-muted mb-4">Share a moment with the world.</p>

		<div class="mb-3">
			<textarea id="post_text" name="post_text" class="form-control"
				placeholder="Write a caption..." rows="2"></textarea>
		</div>
		<div class="mb-3">
			<input type="file" accept="image/*" class="form-control" name="post_image" id="post_image">
		</div>
		<button id="share-memory" class="btn btn-primary px-5" type="button">
			Share Photo
		</button>
	</div>
</section>

<?php
$upload_status = $_GET['upload'] ?? null;
if ($upload_status === 'success') {
	$msg_title = '✔ Uploaded';
	$msg_text  = 'Your photo has been shared!';
	$msg_type  = 'success';
} elseif ($upload_status === 'error') {
	$msg_title = '✘ Upload failed';
	$msg_text  = Session::get('upload_error', 'Please try again.');
	Session::delete('upload_error');
	$msg_type  = 'danger';
}

if ($upload_status) { ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
	toast(<?=json_encode($msg_title)?>, 'just now', <?=json_encode($msg_text)?>, {placement: 'top-center'});
	window.history.replaceState({}, document.title, window.__BASE_PATH);
});
</script>
<?php } ?>