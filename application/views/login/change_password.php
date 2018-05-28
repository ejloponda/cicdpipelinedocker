<?php include('themes/templates/header.php'); ?>
	<section id="content">
		<ul class="header">
			<li><img class="logo" src="<?php echo BASE_FOLDER; ?>themes/images/RPC-logo2.png"></li>
		</ul>
		<div id="result_wrapper"></div>
		<section id="signincontent" style="padding-bottom:20px">	
			<form class="signinform" name="signinform" method="post" action="<?php echo url('login/save_password') ?>">
				<h3 class="header-txt">Change Password</h3>
				<label>Username</label>
				<input type="hidden" name="user_id" class="signintextfield" value="<?php echo $user['id'];?>"/>
				<input type="text" name="username" class="signintextfield" value="<?php echo $user['username'];?>" disabled/>
				<div class="clear"></div>
				<label>New Password</label>
				<input type="password" name="password" class="signintextfield"/ id="password">
				<div class="clear"></div>
				<ul>
					<li><button class="signinbutton" onSubmit="$('.signinform').submit();" style="float:none">SAVE CHANGES</button></li>
					<li>or</li>
					<li><a href="<?php echo url('login'); ?>">CANCEL</a></li>

				</ul>
				<div class="clear"></div>
			</form>
		</section>

	</section>
<?php include('themes/templates/footer.php'); ?>

<script type="text/javascript">
	$('#password').val("");

	$(".signinform").ajaxForm({
	    success: function(o) {
	  		if(o.is_successful) {
	  			//$("#result_wrapper").html(o.message);
	  			new jBox('Notice', {
				    content: 'Successfully Updated Password!',
				    color: 'green',
				    autoClose: 3000
				  });
	  			setTimeout(function(){
	  				 window.location.assign("<?php echo BASE_FOLDER; ?>login")
	  			},3000)

	  			 
	  		} else {
	  			$(".signinbutton").removeAttr('disabled');
	  			$(".signinbutton").html("SAVE CHANGES");
	  			notify(o.message,'error');
	  		}
	    },
	    beforeSubmit : function(evt){
	    	$(".signinbutton").attr('disabled', 'disabled');
	    	$("#password").attr('disabled', 'disabled');
	    	$(".signinbutton").html("Please wait...");
	    },
	    dataType : "json"
	});

</script>
