<?php include('themes/templates/header.php'); ?>
	<section id="content">
		<ul class="header">
			<li><img class="logo" src="<?php echo BASE_FOLDER; ?>themes/images/RPC-logo2.png"></li>
		</ul>
		<section id="signincontent" class="forgot-pass-wrapper">
			<div id="result_wrapper"></div>
			<form class="signinform" name="signinform" action="<?php echo url('login/sendEmailAccountDetails') ?>" method="post">
				<p class="header-txt">Forgot your password? <!-- Type in your email address and hit the Submit button to receive an email notification with a temporary new password to your account. --></p>
				<label>Username / Email Address</label>
				<form >
					<input type="text" name="email" class="signintextfield" id="email"/>
				
				<div class="clear"></div>
				<ul class="button-hldr">
					<li><button class="submit" onSubmit="">submit</button></li>
					<li>or</li>
					<li><a href="<?php echo url('login'); ?>">Go back</a></li>
				</ul>
				<div class="clear"></div>
			</form>
		</section>

	</section>
<?php include('themes/templates/footer.php'); ?>
