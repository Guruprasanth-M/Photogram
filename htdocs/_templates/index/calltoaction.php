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