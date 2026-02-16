<?php

$message = '';
$message_type = '';

// Read upload result from query params (set by PRG redirect in index.php)
if (isset($_GET['upload'])) {
    if ($_GET['upload'] === 'success') {
        $message = "Your photo has been shared successfully!";
        $message_type = 'success';
    } elseif ($_GET['upload'] === 'error') {
        $message = Session::get('upload_error', 'Upload failed. Please try again.');
        Session::delete('upload_error');
        $message_type = 'danger';
    }
}

?>

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

<?php if ($message): ?>
<div class="modal fade" id="uploadResultModal" tabindex="-1" aria-labelledby="uploadResultModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header border-0">
				<h5 class="modal-title" id="uploadResultModalLabel">
					<?= $message_type === 'success' ? '&#10004; Success' : '&#10060; Error' ?>
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body text-center py-4">
				<p class="mb-0 fs-5"><?=htmlspecialchars($message)?></p>
			</div>
			<div class="modal-footer border-0 justify-content-center">
				<a href="<?=get_config('base_path')?>" class="btn btn-<?= $message_type === 'success' ? 'success' : 'danger' ?>">OK</a>
			</div>
		</div>
	</div>
</div>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		new bootstrap.Modal(document.getElementById('uploadResultModal')).show();
	});
</script>
<?php endif; ?>