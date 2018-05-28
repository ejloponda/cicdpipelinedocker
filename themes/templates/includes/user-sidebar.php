<section id="content">
	<section class="menu custom-menu">
		<ul>
			<?php if($pm_fmh['can_add'] || $pm_fmh['can_update'] || $pm_fmh['can_delete'] || $pm_fmh['can_view'] || $pm_pi['can_add'] || $pm_pi['can_update'] || $pm_pi['can_delete'] || $pm_pi['can_view'] || $pm_pmh['can_add'] || $pm_pmh['can_update'] || $pm_pmh['can_delete'] || $pm_pmh['can_view']) { ?>
			<li class="hilited patient_menu"><img src="<?php echo BASE_FOLDER; ?>themes/images/menu_user.png" title="Patients"></li>
			<?php } ?>

			<li class="calendar_menu"><img src="<?php echo BASE_FOLDER; ?>themes/images/menu_calendar.png" title="Calendar Management" style="height: 120px; width:52px"></li>

			<?php if($rc_reg['can_add'] || $rc_reg['can_update'] || $rc_reg['can_delete'] || $rc_reg['can_view']) { ?>
			<li class="regimen_menu"><img src="<?php echo BASE_FOLDER; ?>themes/images/menu_regimen.png" title="Regimen"></li>
			<?php } ?>

			<?php if($om_order['can_add'] || $om_order['can_update'] || $om_order['can_delete'] || $om_order['can_view']) { ?>
			<li class="order_menu"><img src="<?php echo BASE_FOLDER; ?>themes/images/menu_order.png" title="Order Form"></li>
			<?php } ?>

			<?php if($invent['can_add'] || $invent['can_update'] || $invent['can_delete'] || $invent['can_view'] || $returns['can_add'] || $returns['can_update'] || $returns['can_delete'] || $returns['can_view']) { ?>
			<li class="inventory_management_menu"><img src="<?php echo BASE_FOLDER; ?>themes/images/menu_inventory.png" title="Inventory & Returns"></li>
			<?php } ?>

			<?php if($invent['can_add'] || $invent['can_update'] || $invent['can_delete'] || $invent['can_view'] || $returns['can_add'] || $returns['can_update'] || $returns['can_delete'] || $returns['can_view']) { ?>
			<li class="billing_menu"><img src="<?php echo BASE_FOLDER; ?>themes/images/menu_billing.png" title="Accounting & Billing"></li>
			<?php } ?>

			<?php if($mm_dc['can_add'] || $mm_dc['can_update'] || $mm_dc['can_delete'] || $mm_dc['can_view'] || $mm_dt['can_add'] || $mm_dt['can_update'] || $mm_dt['can_delete'] || $mm_dt['can_view'] ) { ?>
			<li class="module_management_menu"><img src="<?php echo BASE_FOLDER; ?>themes/images/menu_module-management.png" title="Modules"></li>
			<?php } ?>

			<?php if($mu_default['can_add'] || $mu_default['can_update'] || $mu_default['can_delete'] || $mu_default['can_view']) { ?>
			<li class="firm_admin_users"><img src="<?php echo BASE_FOLDER; ?>themes/images/menu_users.png" title="User Accounts"></li>
			<?php } ?>

			<?php if($mu_roles['can_add'] || $mu_roles['can_update'] || $mu_roles['can_delete'] || $mu_roles['can_view']) { ?>
			<li class="firm_admin_roles"><section class="admin_user_roles"><img src="<?php echo BASE_FOLDER; ?>themes/images/menu_roles.png" title="User Roles"></section></li>
			<?php } ?>

			<?php #if($mu_ms['can_add'] || $mu_ms['can_update'] || $mu_ms['can_delete'] || $mu_ms['can_view']) { ?>
			<!-- <li class="firm_admin_permissions"><img src="<?php echo BASE_FOLDER; ?>themes/images/menu_access.png" title="Access Permission"></li> -->
			<?php #} ?>

			<?php if($accounting['can_add'] || $accounting['can_update'] || $accounting['can_delete'] || $accounting['can_view'] ) { ?>
			<li class="reports_menu"><img src="<?php echo BASE_FOLDER; ?>themes/images/report_gen.jpg" title="Reports Generator"></li>
			<?php } ?>
			<li class="activity_log_menu"><img src="<?php echo BASE_FOLDER; ?>themes/images/menu-icons.png" title="Activity Log"></li>

			<!-- <li class="activity_menu"><img src="<?php echo BASE_FOLDER; ?>themes/images/menu_activity.png" title="Activity Tracker"></li> -->
		</ul>
	</section>