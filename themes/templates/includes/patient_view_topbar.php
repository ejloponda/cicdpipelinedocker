<section id="top">
	
		<div class="logo">
			<a href="<?php echo base_url(); ?>">
				<img class="logo-top" src="<?php echo BASE_FOLDER; ?>themes/images/RPC-logo.png">
			</a>
		</div>
		<div class="rightside">	
			<ul class="red">
				<li class="notify"><img src="<?php echo BASE_FOLDER; ?>themes/images/icon-notification.png"></li>
				<li class="account">
					<?php if($user['display_image_url'] != "") { ?>
						<img style="width:20px; height:20px;" src="<?php echo BASE_FOLDER; ?>files/photos/tmp/users/<?php echo $user['display_image_url'] ?>">
					<?php } else { ?>
						<img src="<?php echo BASE_FOLDER; ?>themes/images/icon-user.jpg">
					<?php } ?>Welcome Back,  <?php echo $username ?>!
					<ul class="sub">
						<li><a href="javascript: void(0);" onClick="javascript: viewMyProfile();">View Profile</a></li>
						<li><a href="#">Permissions</a></li>
						<li><a href="<?php echo url("logout"); ?>">Logout</a></li>
					</ul>

				</li>
			</ul>

			<ul class="submenu">
				<li class="sub-hilited patient_dashboard"><a href="javascript: void(0);" onclick="javascript: view_dashboard();">Patient Dashboard</a></li>
				<li>I</li>
				<li class="patient_info_form"><a href="javascript: void(0);" onclick="javascript: view_patient2();">Patient Information</a></li>
				<li>I</li>
				<li class="medical_history_form"><a href="javascript: void(0);" onclick="javascript: view_medical_history();">Medical History</a></li>
				<li>I</li>
				<li class="regimen_history_form"><a href="javascript: void(0);" onclick="javascript: view_regimen();">Regimen History</a></li>
				<li>I</li>
				<li class="account_billing_form"><a href="javascript: void(0);" onclick="javascript: view_invoice_list();">Account & Billing</a></li>
				<li>I</li>
				<li class="returns_history_form"><a href="javascript: void(0);" onclick="javascript: view_return_list();">Returns History</a></li>
				<li>I</li>
				<li class="patient_files_form"><a href="javascript: void(0);" onclick="javascript: view_upload_list();">Patient Files</a></li>
				<li>I</li>
				<li class="patient_test_form"><a href="javascript: void(0);" onclick="javascript: view_all_test();">Tests</a></li>
			</ul>
		</div>
	
	<div class="line"></div>
</section>