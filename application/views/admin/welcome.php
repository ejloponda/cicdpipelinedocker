<?php include('themes/templates/includes/user-header.php'); ?>
<script>
<!-- 
	$(function(){
		$('.user-welcome').removeClass("hidden");
	});
-->
</script>
<div id="content">
	<ul class="head">
		<li>
			<?php if($user['display_image_url'] != "") { ?>
				<img style="width:120px;" src="<?php echo BASE_FOLDER; ?>files/photos/tmp/users/<?php echo $user['display_image_url'] ?>">
			<?php } else { ?>
				<img src="<?php echo BASE_FOLDER; ?>themes/images/photo.png">
			<?php } ?>
		</li>
		<li>What do you want to do?</li>
	</ul>

	<section id="options">
			
			<ul class="box">
			<div class="col-md-12">
				<?php if($pm_fmh['can_add'] || $pm_fmh['can_update'] || $pm_fmh['can_delete'] || $pm_fmh['can_view'] || $pm_pi['can_add'] || $pm_pi['can_update'] || $pm_pi['can_delete'] || $pm_pi['can_view'] || $pm_pmh['can_add'] || $pm_pmh['can_update'] || $pm_pmh['can_delete'] || $pm_pmh['can_view']) { ?>
					<div class="col-md-2 col-sm-3 col-xs-3 ">
					<a onClick="window.location.href='<?php echo url('management/patient'); ?>';"><section class="img"><img src="<?php echo BASE_FOLDER; ?>themes/images/icon_user.png"></section><br>
						<div>Patient Management</div>
					</a>
					</div>
				<?php } ?>
				<div class="col-md-2 col-sm-3 col-xs-3">
				<a onClick="window.location.href='<?php echo url('management/calendar'); ?>';"><section class="img"><img src="<?php echo BASE_FOLDER; ?>themes/images/icon_calendar.png"></section><br>
						Calendar Management
					</a>
					</div>

				<?php if($rc_reg['can_add'] || $rc_reg['can_update'] || $rc_reg['can_delete'] || $rc_reg['can_view']) { ?>
					<div class="col-md-2 col-sm-3 col-xs-3">
					<a onClick="window.location.href='<?php echo url('management/regimen'); ?>';"><section class="img"><img src="<?php echo BASE_FOLDER; ?>themes/images/icon_pill.png"></section><br>
						<div>Regimen Creator</div>
					</a>
					</div>
				<?php } ?>
				<?php if($om_order['can_add'] || $om_order['can_update'] || $om_order['can_delete'] || $om_order['can_view']) { ?>
					<div class="col-md-2 col-sm-3 col-xs-3">
					<a onClick="window.location.href='<?php echo url('management/order'); ?>';"><section class="img"><img src="<?php echo BASE_FOLDER; ?>themes/images/icon_order.png"></section><br>
						Order Form
					</a>
					</div>
				<?php } ?>
				<?php if($invent['can_add'] || $invent['can_update'] || $invent['can_delete'] || $invent['can_view'] || $returns['can_add'] || $returns['can_update'] || $returns['can_delete'] || $returns['can_view']) { ?>
					<div class="col-md-2 col-sm-3 col-xs-3">
					<a onClick="window.location.href='<?php echo url('management/inventory'); ?>';"><section class="img"><img src="<?php echo BASE_FOLDER; ?>themes/images/icon_inventory.png"></section><br>
						Inventory Management
					</a>
					</div>
				<?php } ?>
				<?php if($accounting['can_add'] || $accounting['can_update'] || $accounting['can_delete'] || $accounting['can_view'] ) { ?>
					<div class="col-md-2 col-sm-3 col-xs-3">
					<a onClick="window.location.href='<?php echo url('management/billing'); ?>';"><section class="img"><img src="<?php echo BASE_FOLDER; ?>themes/images/icon_billing.png"></section><br>
						Account & Billing
					</a>
					</div>
				<?php } ?>
				
				<?php if($mm_dc['can_add'] || $mm_dc['can_update'] || $mm_dc['can_delete'] || $mm_dc['can_view'] || $mm_dt['can_add'] || $mm_dt['can_update'] || $mm_dt['can_delete'] || $mm_dt['can_view'] ) { ?>
					<div class="col-md-2 col-md-offset-1 col-sm-3 col-xs-3">
					<a onClick="window.location.href='<?php echo url('management/module'); ?>';"><section class="img"><img src="<?php echo BASE_FOLDER; ?>themes/images/icon_module-management.png"></section><br>
						Module Management
					</a>
					</div>
				<?php } ?>
				<?php if($mu_default['can_add'] || $mu_default['can_update'] || $mu_default['can_delete'] || $mu_default['can_view']) { ?>
					<div class="col-md-2 col-sm-3 col-xs-3">
					<a  onClick="window.location.href='<?php echo url('management/users'); ?>';"><section class="img"><img src="<?php echo BASE_FOLDER; ?>themes/images/icon_user-accounts.png"></section><br>
						User Accounts
					</a>
					</div>
				<?php } ?>
				<?php if($mu_roles['can_add'] || $mu_roles['can_update'] || $mu_roles['can_delete'] || $mu_roles['can_view']) { ?>
					<div class="col-md-2 col-sm-3 col-xs-3">
					<a onClick="window.location.href='<?php echo url('management/permissions'); ?>';"><section class="img"><img src="<?php echo BASE_FOLDER; ?>themes/images/icon_roles.png"></section><br>
						Role Assignments
					</a>
					</div>
				<?php } ?>
				<?php #if($mu_ms['can_add'] || $mu_ms['can_update'] || $mu_ms['can_delete'] || $mu_ms['can_view']) { ?>
					<!-- <li onClick="window.location.href='<?php echo url('management/users#permissions'); ?>';"><section class="img"><img src="<?php echo BASE_FOLDER; ?>themes/images/icon_access.png"></section><br>
						Access Permissions
					</li> -->
				<?php #} ?>
				<?php if($reps['can_view'] ) { ?>
					<div class="col-md-2 col-sm-3 col-xs-3">
					<a onClick="window.location.href='<?php echo url('management/reports'); ?>';"><section class="img"><img src="<?php echo BASE_FOLDER; ?>themes/images/report_gen_main.jpg"></section><br>
						Reports Generator
					</a>
					</div>
				<?php } ?>
					<div class="col-md-2 col-sm-3 col-xs-3">
					<a onClick="window.location.href='<?php echo url('management/activity_log'); ?>';"><section class="img"><img src="<?php echo BASE_FOLDER; ?>themes/images/icon_activity.png"></section><br>
						Activity Tracker
					</a>
					</div>

					</div>
			</ul>

	</section>
</div>
<div class="clear"></div>
<div class="modal fade" id="user_profile_form_wrapper" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>	
<div class="modal fade" id="update_user_profile_form_wrapper" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>	
<?php include('themes/templates/footer.php'); ?>