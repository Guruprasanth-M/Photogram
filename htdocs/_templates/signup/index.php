<?php

$signup = false;
if (isset($_POST['username']) and isset($_POST['password']) and !empty($_POST['password']) and isset($_POST['email_address']) and isset($_POST['phone'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $email = trim($_POST['email_address']);
    $phone = trim($_POST['phone']);
    $error = User::signup($username, $password, $email, $phone);
    $signup = true;
}
?>

<?php
if ($signup) {
    if ($error === true) {
        ?>
        <script>
            window.location.href = "<?=get_config('base_path')?>login.php?signup=success"
        </script>
        <?php
    } else {
        ?>
<main class="container">
	<div class="p-5 rounded mt-3 text-center border border-danger">
		<h1 class="text-danger">Signup Failed</h1>
		<p class="lead"><?= $error ?>
		</p>
        <a href="<?=get_config('base_path')?>signup.php" class="btn btn-secondary">Try Again</a>
	</div>
</main>
<?php
    }
} else {
    ?>
<main class="form-signup">
	<form method="post" action="<?=get_config('base_path')?>signup.php">
		<img class="mb-4 d-block mx-auto" src="https://git.selfmade.ninja/uploads/-/system/appearance/logo/1/Logo_Dark.png" alt=""
			height="50">
		<h1 class="h3 mb-3 fw-normal">Join Photogram</h1>
		<div class="form-floating mb-2">
			<input name="username" type="text" class="form-control" id="floatingInputUsername"
				placeholder="name@example.com" required>
			<label for="floatingInputUsername">Username</label>
		</div>
		<div class="form-floating mb-2">
			<input name="phone" type="text" class="form-control" id="floatingInputPhone"
				placeholder="name@example.com" required>
			<label for="floatingInputPhone">Phone</label>
		</div>
		<div class="form-floating mb-2">
			<input name="email_address" type="email" class="form-control" id="floatingInput"
				placeholder="name@example.com" required>
			<label for="floatingInput">Email address</label>
		</div>
		<div class="form-floating mb-3">
			<input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password" required>
			<label for="floatingPassword">Password</label>
		</div>
		<button class="w-100 btn btn-lg btn-primary mb-3" type="submit">Create Account</button>
        <div class="text-center">
            <span class="text-muted">Already have an account?</span> 
            <a href="<?=get_config('base_path')?>login.php" class="btn btn-link p-0">Login Here</a>
        </div>
	</form>
</main>
<?php
}
