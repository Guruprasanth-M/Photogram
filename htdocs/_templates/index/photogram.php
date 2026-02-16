<div class="album py-5 bg-light">
	<div class="container">

		<div class="row" data-masonry='{"percentPosition": true }'>
			<?php
                $posts = Post::getAllPosts();
				use Carbon\Carbon;
				
				if (empty($posts)) {
					?>
					<div class="col-12 text-center py-5">
						<div class="py-5">
							<svg class="mb-4" width="80" height="80" fill="currentColor" viewBox="0 0 16 16" style="color: #6c757d;">
								<path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
								<path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
							</svg>
							<h3 class="text-muted">No photos yet</h3>
							<p class="text-muted">Be the first to share a memory! Upload a photo to get started.</p>
							<?php if (Session::isAuthenticated()) { ?>
								<a href="#greeting" class="btn btn-success mt-3">Share Your First Photo</a>
							<?php } ?>
						</div>
					</div>
					<?php
				} else {
					foreach ($posts as $post) {
						$p = new Post($post['id']);
						$uploaded_time = Carbon::parse($p->getUploadTime());
						$uploaded_time_str = $uploaded_time->diffForHumans();
						?>
						<div class="col-lg-4 mb-4">
							<div class="card">
								<img class="bd-placeholder-img card-img-top" src="<?=$p->getImageUri()?>" alt="<?=htmlspecialchars($p->getPostText())?>">
								<div class="card-body">
									<p class="card-text"><?=htmlspecialchars($p->getPostText())?></p>
									<div class="d-flex justify-content-between align-items-center">
										<div class="btn-group">
											<button type="button" class="btn btn-sm btn-outline-primary">Like</button>
											<button type="button" class="btn btn-sm btn-outline-success">Share</button>
											<?php
											$user = Session::getUser();
											if (Session::isOwnerOf($p->getOwner())) {
											?>
											<button type="button" class="btn btn-sm btn-outline-danger">Delete</button>
											<?}?>
										</div>
										<small class="text-muted"><?=$uploaded_time_str?></small>
									</div>
								</div>
							</div>
						</div>
						<?php
					}
				}
				?>
		</div>
	</div>
</div>