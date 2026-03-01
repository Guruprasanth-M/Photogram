<div class="col-lg-4 col-md-6 mb-4" id="post-<?=$p->getID()?>">
	<div class="card">
		<div class="card-img-wrapper">
			<img class="bd-placeholder-img card-img-top" loading="lazy"
				src="<?=get_config('base_path') . ltrim($p->getImageUri(), '/')?>"
				alt="<?=htmlspecialchars($p->getPostText())?>">
		</div>
		<div class="card-body">
			<div class="post-author">
				<div class="post-author-avatar"><?=strtoupper(substr($owner->getUsername(), 0, 1))?></div>
				<span class="post-author-meta">@<?=htmlspecialchars($owner->getUsername())?></span>
				<span class="text-muted ms-auto" style="font-size:.75rem"><?=$uploaded_time_str?></span>
			</div>
			<p class="card-text"><?=htmlspecialchars($p->getPostText())?></p>
			<div class="d-flex align-items-center gap-2" data-id="<?=$p->getID()?>">
				<?php
				$isLiked = false;
				if (Session::isAuthenticated()) {
					$like    = new Like($p);
					$isLiked = $like->isLiked();
				}
				?>
				<button type="button"
					class="btn btn-sm <?=$isLiked ? 'btn-outline-primary liked' : 'btn-outline-primary'?> btn-like"
					<?=!Session::isAuthenticated() ? 'disabled title="Log in to like"' : ''?>>
					<?=$isLiked ? '♥ Liked' : '♡ Like'?>
				</button>
				<?php if (Session::isOwnerOf($p->getOwner())) { ?>
				<button type="button" class="btn btn-sm btn-outline-danger btn-delete ms-auto">Delete</button>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
