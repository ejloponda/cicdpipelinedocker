<?php include('themes/templates/header.php'); ?>
<section id="content">
	<ul class="header">
		<li><img class="logo" src="<?php echo BASE_FOLDER; ?>themes/images/RPC-logo2.png"></li>
	</ul>
	<div id="result_wrapper"></div>
	<br>
	<section id="signincontent" class="forgot-pass-wrapper">
		
		<form class="signinform" name="signinform" action="<?php echo url('login/check_answer') ?>" method="post">
			
			<?php if($question){?>
			<input type="hidden" name="id" class="signintextfield" id="id" style="float:none!important;" value="<?php echo $question['id'];?>"/>
			<input type="hidden" name="user_id" class="signintextfield" id="user_id" style="float:none!important;" value="<?php echo $user['id'];?>"/>
			<center>
				<p class="header-txt" style="font-size:18px;">To verify if you are <span style="color: black;"><?php echo $user['username'];?></span>,  please answer the question you've set.</p>
			</center>	
				<label style="float:center!important;"><?php echo $question['question'];?></label>
				
				<input type="text" name="answer" class="signintextfield" id="answer" style="float:none!important;"/>
			
				<div class="clear"></div>

				<ul class="button-hldr">
					<li><button class="signinbutton" onSubmit="$('.signinform').submit();">submit</button></li>
					<li>or</li>
			<?php } else { ?>

				<label style="float:center!important;"><?php echo $error;?></label>
				<br>
				<br>
				<div class="clear"></div>
				<div class="clear"></div>
					
			<?php } ?>

			<li><a href="<?php echo url('forgotaccount'); ?>">Go back</a></li>
				</ul>
			<div class="clear"></div>
		</form>
	</section>
</section>

<script type="text/javascript">
	$(".signinform").ajaxForm({
	    success: function(o) {
	  		if(o.is_successful) {
	  			$("#result_wrapper").html(o.message);
	  			setTimeout(function(){
	  				window.location.replace(o.redirect_to);
	  			},3000)
	  		} else {
	  			$(".signinbutton").removeAttr('disabled');
	  			$("#answer").removeAttr('disabled');
	  			$(".signinbutton").html("SAVE CHANGES");
	  			//$("#result_wrapper").html(o.message);
			
				var confirm = new jBox('Modal', {
				content: '<h3>Sorry! Wrong Answer. Try Again!</h3>',
				
				animation: {open: 'tada', close: 'pulse'}
				});
				confirm.open();
	  		}
	    },
	    beforeSubmit : function(evt){
	    	$(".signinbutton").attr('disabled', 'disabled');
	    	$("#answer").attr('disabled', 'disabled');
	    	$(".signinbutton").html("Please wait...");
	    },
	    dataType : "json"
	});
</script>
<?php include('themes/templates/footer.php'); ?>