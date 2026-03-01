<div class="col-lg-4 mb-4" id="post-<?=$p->getID()?>">
	<div class="card">
		<div class="card-img-wrapper">
			<img class="bd-placeholder-img card-img-top"
				src="<?=get_config('base_path') . ltrim($p->getImageUri(), '/')?>">
		</div>
		<div class="card-body">
			<div class="post-author">
				<div class="post-author-avatar"><?=strtoupper(substr($owner->getUsername(), 0, 1))?></div>
				<span class="post-author-meta">by @<?=htmlspecialchars($owner->getUsername())?></span>
			</div>
			<p class="card-text"><?=htmlspecialchars($p->getPostText())?></p>
			<div class="d-flex justify-content-between align-items-center">
				<div class="btn-group" data-id="<?=$p->getID()?>">
					<?php
					$isLiked = false;
					if (Session::isAuthenticated()) {
						$like = new Like($p);
						$isLiked = $like->isLiked();
					}
					?>
					<button type="button" class="btn btn-sm <?=$isLiked ? 'btn-primary liked' : 'btn-outline-primary'?> btn-like"><?=$isLiked ? 'Liked' : 'Like'?></button>
					<?php
					if (Session::isOwnerOf($p->getOwner())) {
					?>
					<button type="button" class="btn btn-sm btn-outline-danger btn-delete">Delete</button>
					<?}?>
				</div>
				<small class="text-muted"><?=$uploaded_time_str?> by @<?=htmlspecialchars($owner->getUsername())?></small>
			</div>
		</div>
	</div>
</div>
