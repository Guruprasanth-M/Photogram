<?php

$login = true;

if (isset($_POST['email_address']) && isset($_POST['password'])) {
    $email_address = $_POST['email_address'];
    $password      = $_POST['password'];

    $result = UserSession::authenticate($email_address, $password);
    if ($result) {
        Session::$usersession = UserSession::authorize($result);
    }
    $login = false;
}

if (!$login && $result && Session::$usersession) {
    $redirect_url = Session::get('_redirect', get_config('base_path'));
    Session::delete('_redirect');
    ?>
    <script>window.location.href = "<?=$redirect_url?>";</script>
    <?php
    exit();
}

if (!$login && !($result ?? false)) {
    $error_msg = "Invalid username/email or password.";
}
?>

<main class="form-signin">
	<form method="post" action="<?=get_config('base_path')?>login.php" autocomplete="on">
		<!-- Logo -->
		<svg class="auth-logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
			stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
			<path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
			<circle cx="12" cy="13" r="4"/>
		</svg>
		<h1 class="h3 mb-4 fw-bold text-center">Sign in</h1>

		<input name="fingerprint" type="hidden" id="fingerprint" value="">

		<?php if (isset($error_msg)) { ?>
			<div class="alert border-danger text-danger text-center mb-4 py-2 rounded-3">
				<small><?=$error_msg?></small>
			</div>
		<?php } ?>

		<?php if (isset($_GET['signup']) && $_GET['signup'] === 'success') { ?>
			<div class="alert border-success text-success text-center mb-4 py-2 rounded-3">
				<small>Account created! Please sign in.</small>
			</div>
		<?php } ?>

		<div class="form-floating mb-3">
			<input name="email_address" type="text" class="form-control" id="floatingInput"
				placeholder="name@example.com" required autocomplete="username"
				value="<?=isset($_POST['email_address']) ? htmlspecialchars($_POST['email_address']) : ''?>">
			<label for="floatingInput">Username or Email</label>
		</div>
		<div class="form-floating mb-4">
			<input name="password" type="password" class="form-control" id="floatingPassword"
				placeholder="Password" required autocomplete="current-password">
			<label for="floatingPassword">Password</label>
		</div>

		<button class="w-100 btn btn-lg btn-primary mb-4" type="submit" id="btn-login">Sign in</button>

		<div class="text-center">
			<p class="text-muted mb-0">New here?
				<a href="<?=get_config('base_path')?>signup.php" class="fw-bold text-accent">Create an account</a>
			</p>
		</div>
	</form>
</main>
