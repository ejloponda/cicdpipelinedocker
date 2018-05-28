<?php include('themes/templates/header.php'); ?>
<section id="content">
	<ul class="header">
		<li><img class="logo" src="<?php echo BASE_FOLDER; ?>themes/images/RPC-logo2.png"></li>
	</ul>
	<section id="signincontent">
		<?php echo $error_message ?>
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
		<li>Forgot your Email or Password?</li>
		<li><button class="click" onClick="window.location.href='<?php echo url('forgotaccount'); ?>'">Click Here</button></li>
	</ul>
</section>

<script type="text/javascript">
	$(".signinform").ajaxForm({
	    success: function(o) {
	  		if(o.is_successful) {
	  			$
	  			notify(o.message,'success');
	  			setTimeout(function(){
	  				window.location.replace(o.redirect_to);
	  			},3000)
	  		} else {
	  			$(".signinbutton").removeAttr('disabled');
	  			$(".signinbutton").html("SIGN IN");
	  			notify(o.message,'error');
	  		}
	    },
	    beforeSubmit : function(evt){
	    	$(".signinbutton").attr('disabled', 'disabled');
	    	$(".signinbutton").html("Please wait...");
	    },
	    dataType : "json"
	});

	function notify(message,type){
		var myStack = {"dir1":"down", "dir2":"right", "push":"top"};
		new PNotify({
		    title: "Login",
		    text: message,
		    addclass: "stack-topright",
		    type: type
		})
	}
</script>
<?php include('themes/templates/footer.php'); ?>