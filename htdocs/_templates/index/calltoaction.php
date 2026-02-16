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

<section class="py-5 text-center container">
	<div class="row py-lg-5">
		<form method="post" action="<?=get_config('base_path')?>" enctype="multipart/form-data">
			<div class="col-lg-6 col-md-8 mx-auto">
				<?php if ($message): ?>
					<div class="alert alert-<?=$message_type?> alert-dismissible fade show" role="alert">
						<?=htmlspecialchars($message)?>
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php endif; ?>
				<h1 id="greeting" class="fw-light">What are you upto,
					<?=Session::getUser()->getUsername()?>?
				</h1>
				<p class="lead text-muted">Share a photo that talks about it.</p>
				<div class="mb-3">
					<textarea id="post_text" name="post_text" class="form-control" placeholder="What are you upto?"
						rows="3" required></textarea>
				</div>
				<div class="input-group mb-3">
					<input type="file" accept="image/*" class="form-control" name="post_image" id="inputGroupFile02" required>
				</div>
				<p>
					<button class="btn btn-success my-2" type="submit">Share memory</button>
					<!-- <a href="#" class="btn btn-secondary my-2">Clear</a> -->
				</p>
			</div>
		</form>
	</div>
</section>