<?php
$user = Session::isAuthenticated() ? Session::getUser() : null;
$avatar = ($user && $user->getAvatar()) ? $user->getAvatar() : null; 
$displayName = ($user && $user->getFirstname()) ? $user->getFirstname() . ' ' . $user->getLastname() : ($user ? $user->getUsername() : "Guest");
?>

<header>
	<?if(Session::isAuthenticated()){?>
	<!-- Profile Sidebar (Offcanvas) -->
	<div class="offcanvas offcanvas-end" tabindex="-1" id="profileSidebar" aria-labelledby="profileSidebarLabel">
		<div class="offcanvas-header">
			<h5 class="offcanvas-title" id="profileSidebarLabel">Account</h5>
			<button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
		</div>
		<div class="offcanvas-body p-0">
			<!-- Profile Card -->
			<div class="sidebar-profile-card">
				<div class="sidebar-avatar-wrapper">
					<?if($avatar){?>
						<img src="<?=$avatar?>" alt="Avatar" class="sidebar-avatar-lg">
					<?} else {?>
						<div class="sidebar-avatar-lg sidebar-avatar-placeholder">
							<span><?=strtoupper(substr($user->getUsername(), 0, 1))?></span>
						</div>
					<?}?>
				</div>
				<div class="sidebar-profile-info">
					<span class="sidebar-display-name"><?=$displayName?></span>
					<span class="sidebar-username">@<?=$user->getUsername()?></span>
				</div>
				<?php
					$bio = $user->getBio();
					if ($bio) {
				?>
					<p class="sidebar-bio"><?=htmlspecialchars($bio)?></p>
				<?php } ?>
			</div>

			<!-- Quick Stats -->
			<div class="sidebar-stats">
				<div class="sidebar-stat">
					<span class="sidebar-stat-num"><?php
						$db = Database::getConnection();
						$email_safe = $db->real_escape_string($user->getEmail());
						$r = $db->query("SELECT COUNT(*) as c FROM posts WHERE owner='$email_safe'");
						echo $r ? $r->fetch_assoc()['c'] : '0';
					?></span>
					<span class="sidebar-stat-label">Posts</span>
				</div>
				<div class="sidebar-stat">
					<span class="sidebar-stat-num"><?php
						$uid = $user->getID();
						$r2 = $db->query("SELECT COUNT(*) as c FROM likes WHERE user_id='$uid' AND `like`=1");
						echo $r2 ? $r2->fetch_assoc()['c'] : '0';
					?></span>
					<span class="sidebar-stat-label">Liked</span>
				</div>
			</div>

			<!-- Navigation -->
			<div class="sidebar-nav">
				<span class="sidebar-nav-section">Navigate</span>
				<a href="<?=get_config('base_path')?>" class="sidebar-link">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
					<span>Feed</span>
					<svg class="sidebar-link-arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
				</a>
				<a href="<?=get_config('base_path')?>setnget.php" class="sidebar-link">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
					<span>Your Profile</span>
					<svg class="sidebar-link-arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
				</a>
				<a href="<?=get_config('base_path')?>settings.php" class="sidebar-link">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
					<span>Settings</span>
					<svg class="sidebar-link-arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
				</a>
			</div>

			<!-- Sign Out -->
			<div class="sidebar-footer">
				<a href="<?=get_config('base_path')?>?logout" class="sidebar-signout">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
					Sign out
				</a>
			</div>
		</div>
	</div>
	<?}?>

	<nav class="navbar navbar-dark sticky-top">
		<div class="container">
			<a href="<?=get_config('base_path')?>" class="navbar-brand d-flex align-items-center">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="var(--accent-light)"
					stroke-linecap="round" stroke-linejoin="round" stroke-width="2" aria-hidden="true" class="me-2"
					viewBox="0 0 24 24">
					<path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z" />
					<circle cx="12" cy="13" r="4" />
				</svg>
				<strong>Photogram</strong>
			</a>
			
			<div class="d-flex align-items-center">
				<?if(Session::isAuthenticated()){?>
					<button class="nav-avatar-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#profileSidebar" aria-controls="profileSidebar">
						<?if($avatar){?>
                            <img src="<?=$avatar?>" alt="User" class="nav-avatar-img">
                        <?} else {?>
                            <div class="nav-avatar-placeholder">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                            </div>
                        <?}?>
					</button>
				<?} else {?>
					<a href="<?=get_config('base_path')?>login.php" class="btn btn-link text-white fw-semibold me-2" style="text-decoration: none; font-size: 0.95rem;">Log in</a>
					<a href="<?=get_config('base_path')?>signup.php" class="btn btn-primary btn-sm px-4" style="border-radius: 20px;">Sign up</a>
				<?}?>
			</div>
		</div>
	</nav>
</header>