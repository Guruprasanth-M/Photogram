<?php

$login = true;

//TODO: Redirect to a requested URL instead of base path on login
if (isset($_POST['email_address']) and isset($_POST['password'])) {
    $email_address = $_POST['email_address'];
    $password = $_POST['password'];

    $result = UserSession::authenticate($email_address, $password);
    if ($result) {
        Session::$usersession = UserSession::authorize($result);
    }
    $login = false;
}
if (!$login) {
    if ($result && Session::$usersession) {
        ?>
<script>
	window.location.href = "<?=get_config('base_path')?>"
</script>

<?php
    } else {
        ?>
<main class="container">
	<div class="border border-danger p-5 rounded mt-3 text-center">
		<h1 class="text-danger">Login Failed</h1>
		<p class="lead">Invalid username/email or password. Please try again.</p>
        <a href="<?=get_config('base_path')?>login.php" class="btn btn-secondary">Try Again</a>
	</div>
</main>
<?php
    }
} else {
    ?>
	<?php if (isset($_GET['signup']) && $_GET['signup'] == 'success') { ?>
		<main class="container">
			<div class="border border-success p-5 rounded mt-3 text-center">
				<h1 class="text-success">Signup Success</h1>
				<p class="lead">Registration successful! Please login with your credentials below.</p>
			</div>
		</main>
	<?php } ?>


<main class="form-signin">
	<form method="post" action="<?=get_config('base_path')?>login.php">
		<img class="mb-4 d-block mx-auto" src="https://git.selfmade.ninja/uploads/-/system/appearance/logo/1/Logo_Dark.png" alt=""
			height="50">
		<input name="fingerprint" type="hidden" id="fingerprint" value="">
		<h1 class="h3 mb-3 fw-normal">Login</h1>

		<div class="form-floating mb-3">
			<input name="email_address" type="text" class="form-control" id="floatingInput"
				placeholder="name@example.com">
			<label for="floatingInput">Username or Email</label>
		</div>
		<div class="form-floating mb-3">
			<input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password">
			<label for="floatingPassword">Password</label>
		</div>

		<button class="w-100 btn btn-lg btn-primary mb-3" type="submit">Sign in</button>
        <div class="text-center">
            <span class="text-muted">Not a member?</span> 
            <a href="<?=get_config('base_path')?>signup.php" class="btn btn-link p-0">Register Now</a>
        </div>
	</form>
</main>

<?php
}
