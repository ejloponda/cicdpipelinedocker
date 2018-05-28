<?php include('themes/templates/includes/user-header.php'); ?>
<script>
	$(function(){
		$('.user-welcome').removeClass("hidden");
	});
</script>
	<div id="content">
		<ul class="head">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/icon-user.jpg"></li>
			<li>What do you want to do?</li>
		</ul>
		<section id="options">
			<ul class="box" onClick="window.location.href='<?php echo url('management'); ?>';">
				<li><img src="<?php echo BASE_FOLDER; ?>themes/images/icon_user.png"></li>
				<li>Patient Management</li>
			</ul>
			<span></span>
			<ul class="box" onClick="window.location.href='<?php echo url('management'); ?>';">
				<li><img src="<?php echo BASE_FOLDER; ?>themes/images/icon_pill.png"></li>
				<li>Regimen<br>Creator</li>
			</ul>
			<span></span>
			<ul class="box" onClick="window.location.href='<?php echo url('management'); ?>';">
				<li><img src="<?php echo BASE_FOLDER; ?>themes/images/icon_inventory.png"></li>
				<li>Inventory Management</li>
			</ul>
			<span></span>
			<ul class="box" onClick="window.location.href='<?php echo url('management'); ?>';">
				<li><img src="<?php echo BASE_FOLDER; ?>themes/images/icon_billing.png"></li>
				<li>Account<br>& Billing</li>
			</ul>
			<span></span>
			<ul class="box" onClick="window.location.href='<?php echo url('management'); ?>';">
				<li><img src="<?php echo BASE_FOLDER; ?>themes/images/icon_activity.png"></li>
				<li>Activity Tracker</li>
			</ul>
			<div class="clear"></div>
		</section>
	</div>
<?php include('themes/templates/footer.php'); ?>