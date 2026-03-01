<?php

$signup = false;
if (
    isset($_POST['username'], $_POST['password'], $_POST['email_address'], $_POST['phone'])
    && !empty($_POST['password'])
) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $email    = trim($_POST['email_address']);
    $phone    = trim($_POST['phone']);
    $error    = User::signup($username, $password, $email, $phone);
    $signup   = true;
}

if ($signup && $error === true) {
    ?>
    <script>window.location.href = "<?=get_config('base_path')?>login.php?signup=success";</script>
    <?php
    exit();
}
?>

<main class="form-signup">
	<form method="post" action="<?=get_config('base_path')?>signup.php" autocomplete="off">
		<!-- Logo -->
		<svg class="auth-logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
			stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
			<path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
			<circle cx="12" cy="13" r="4"/>
		</svg>
		<h1 class="h3 mb-4 fw-bold text-center">Create account</h1>

		<?php if ($signup && $error !== true) { ?>
			<div class="alert border-danger text-danger text-center mb-4 py-2 rounded-3">
				<small><?=htmlspecialchars($error)?></small>
			</div>
		<?php } ?>

		<div class="form-floating mb-3">
			<input name="username" type="text" class="form-control" id="floatingInputUsername"
				placeholder="Username" required
				value="<?=isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''?>">
			<label for="floatingInputUsername">Username</label>
		</div>
		<div class="form-floating mb-3">
			<input name="phone" type="tel" class="form-control" id="floatingInputPhone"
				placeholder="Phone" required
				value="<?=isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''?>">
			<label for="floatingInputPhone">Phone Number</label>
		</div>
		<div class="form-floating mb-3">
			<input name="email_address" type="email" class="form-control" id="floatingInput"
				placeholder="name@example.com" required
				value="<?=isset($_POST['email_address']) ? htmlspecialchars($_POST['email_address']) : ''?>">
			<label for="floatingInput">Email address</label>
		</div>
		<div class="form-floating mb-4">
			<input name="password" type="password" class="form-control" id="floatingPassword"
				placeholder="Password" required minlength="8">
			<label for="floatingPassword">Password</label>
		</div>

		<button class="w-100 btn btn-lg btn-primary mb-4" type="submit" id="btn-signup">Create Account</button>

		<div class="text-center">
			<p class="text-muted mb-0">Already a member?
				<a href="<?=get_config('base_path')?>login.php" class="fw-bold text-accent">Sign in</a>
			</p>
		</div>
	</form>
</main>
