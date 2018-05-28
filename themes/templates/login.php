<?php include('header.php'); ?>
<section id="content">
	<ul class="header">
		<li><img class="logo" src="<?php echo BASE_FOLDER; ?>themes/images/RPC-logo2.png"></li>
	</ul>
	<section id="signincontent">
		<form class="signinform" name="signinform" method="post" action="<?php echo url('login/authenticate_account') ?>">
			<label>Username</label>
			<input type="text" name="username" class="signintextfield"/>
			<div class="clear"></div>
			<label>Password</label>
			<input type="password" name="password" class="signintextfield"/>
			<div class="clear"></div>
			<button class="signinbutton" onSubmit="$('.signinform').submit();">SIGN IN</button>
			<div class="clear"></div>
		</form>
	</section>
	<ul id="forgot">
		<li>Forgot your Username or Password?</li>
		<li><button class="click" onClick="window.location.href='<?php echo url('forgot'); ?>'">Click Here</button></li>
	</ul>
</section>
<?php include('footer.php'); ?>