<section class="hero-section text-center container">
	<div class="row py-lg-5">
		<div class="col-lg-6 col-md-8 mx-auto">
			<h1 class="hero-title">What are you up to, <span class="text-success"><?=Session::getUser()->getUsername()?></span>?</h1>
			<p class="lead text-muted">Share a photo that talks about it.</p>
			<form method="post" action="<?=get_config('base_path')?>sg.php" enctype="multipart/form-data">
				<div class="mb-3">
					<textarea id="post_text" name="post_text" class="form-control bg-dark text-light border-secondary" placeholder="What's on your mind?" rows="3"></textarea>
				</div>
				<div class="input-group mb-3">
					<input type="file" class="form-control bg-dark text-light border-secondary" name="post_image" id="post_image" accept="image/*">
				</div>
				<button class="btn btn-success btn-lg px-5" type="submit">Share Memory</button>
			</form>
		</div>
	</div>
</section>