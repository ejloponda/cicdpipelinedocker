<section id="top">
	
		<div class="logo"><a href="<?php echo base_url(); ?>"><img src="<?php echo BASE_FOLDER; ?>themes/images/RPC-logo.png"></a></div>
		
		<div class="rightside">
			<ul class="red">
				<li class="notify show_myLastNotif" title="Show Notification"><img src="<?php echo BASE_FOLDER; ?>themes/images/icon-notification.png"></li>
				<li class="account">
					<?php if($user['display_image_url'] != "") { ?>
						<img style="width:20px; height:20px;" src="<?php echo BASE_FOLDER; ?>files/photos/tmp/users/<?php echo $user['display_image_url'] ?>">
					<?php } else { ?>
						<img src="<?php echo BASE_FOLDER; ?>themes/images/icon-user.jpg">
					<?php } ?>
					Welcome Back, <?php echo $username ?>!	
					<ul class="sub">
						<li><a href="javascript: void(0);" onClick="javascript: viewMyProfile();">View Profile</a></li>
						<li><a href="#">Permissions</a></li>
						<li><a href="<?php echo url("logout"); ?>">Logout</a></li>
					</ul>

				</li>
			</ul>
			<ul class="submenu module_menu_topbar hidden"><!-- 
				<li class="sub-hilited patient_info_settings_form"><a href="javascript: void(0);" onclick="">Patient Settings</a></li>
				<li>I</li> -->

				<li class="medical_history_settings_form"><a href="javascript: void(0);" onClick="javascript: loadMedicalSettings();">Patient Settings</a>
					<ul class="sub mngt-sub patient-sub">
						<li><a href="#" id="disease_category">Disease Category</a></li>
						<li><a href="#" id="disease_type">Disease Type</a></li>
						<li><a href="#" id="doctors_list">Doctors</a></li>
						<li><a href="#" id="files_category_list">Files Category</a></li>
					</ul>
				</li>
				<li>I</li>
				<li class="inventory_settings_form"><a href="javascript: void(0);" onClick="javascript: loadInventorySettings();">Inventory Settings</a>
					<ul class="sub mngt-sub inv-sub">
						<li><a href="#" class="dosage_type_list">Dosage Type</a></li>
						<li><a href="#" class="quantity_type_list">Quantity Type</a></li>
						<li><a href="#" id="reasons_list_form">Reasons</a></li>
					</ul>
				</li>
				<!-- <li>I</li>
				<li class="regimen_history_settings_form"><a href="javascript: void(0);">Regimen Settings </a> </li> -->
				<li>I</li>
				<li class="account_billing_settings_form"><a href="javascript: void(0);" onClick="javascript: loadAccountBillingSettings();">Account & Billing Settings</a>
					<ul class="sub mngt-sub acct-sub">
						<li><a href="#" class="other_charges">Other Charges</a></li>
						<li><a href="#" class="cost_modifier">Cost Modifier</a></li>
					</ul>
				</li>
				<!-- <li>I</li>
				<li class="returns_settings_form"> <a href="javascript: void(0);"> Returns Settings</a></li> -->
				<li>I</li>
				<li class="calendar_settings_form"><a href="javascript: void(0);" onClick="javascript: loadCalendarSettings();">Calendar Settings</a>
					<ul class="sub mngt-sub calendar-sub">
						<li><a href="#" class="calendar_location">Location</a></li>
						<li><a href="#" class="calendar_type">Type</a></li>
					</ul>
				</li>
			</ul>

			<ul class="submenu inventory_menu_topbar hidden">
				<?php if($invent['can_add'] || $invent['can_update'] || $invent['can_delete'] || $invent['can_view']) { ?>
					<li class="sub-hilited inventory_list_form"><a href="javascript: void(0);" >Product Information</a></li>
				<?php } ?>
				<?php if(($invent['can_add'] || $invent['can_update'] || $invent['can_delete'] || $invent['can_view']) && ($im_sa['can_add'] || $im_sa['can_update'] || $im_sa['can_view'])) { ?>
					<li>I</li>
				<?php } ?>
				<?php if($im_sa['can_add'] || $im_sa['can_update'] || $im_sa['can_view']) { ?>
					<li class="sub-hilited stock_adjustment_form"><a href="javascript: void(0);" >Stock Adjustments</a></li>
				<?php } ?>
				<?php if(($invent['can_add'] || $invent['can_update'] || $invent['can_delete'] || $invent['can_view']) && ($returns['can_add'] || $returns['can_update'] || $returns['can_delete'] || $returns['can_view'])) { ?>
					<li>I</li>
				<?php } ?>
				<?php if($returns['can_add'] || $returns['can_update'] || $returns['can_delete'] || $returns['can_view']) { ?>
					<li class="returns_form"><a href="javascript: void(0);" >Return History</a></li>
				<?php } ?>
			</ul>

			<ul class="submenu menu_returns_inv hidden">
				<li class="sub-hilited inventory_form"><a href="<?php echo url('management/inventory') ?>">Inventory</a></li>
				<li>I</li>
				<li class="sub-hilited returns_form"><a href="<?php echo url('management/returns') ?>">Returns</a></li>
			</ul> 

			<ul class="submenu regimen_menu_topbar hidden">
				<!-- <li class="sub-hilited regimen_list_form"><a href="javascript: void(0);" >Regimen</a></li> -->
				
			</ul>

			<ul class="submenu account_billing_menu hidden">
				<li class="sub-hilited sales_report_list"><a href="javascript: void(0);" >Sales Report</a></li>
				<li>I</li>
				<li class="accounts_receivable_list"><a href="javascript: void(0);" >Account Receivable</a></li>
				<li>I</li>
				<li class="collections_list"><a href="javascript: void(0);" >Collections</a></li>
				
			</ul>

			<!-- <ul class="submenu account_billing_add_invoice_menu hidden">
				<li class="sub-hilited rpc_form_invoice"><a href="javascript: void(0);" >RPC</a></li>
				<li>I</li>
				<li class="alist_form_invoice"><a href="javascript: void(0);" >A-List</a></li>
			</ul> -->
		</div>
	
	<div class="line"></div>
</section>