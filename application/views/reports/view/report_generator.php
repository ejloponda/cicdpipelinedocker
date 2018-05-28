<script>
$(function(){
	/*();
	hideAllModule();*/
	var module_name = $("#module_name").val();

	showMainForm(module_name);

	$("#module_name").on('change', function(){
		var module_name = $("#module_name").val();
		showMainForm(module_name);
	});

	$(".mode_patient").on('click', function(){
		var patient_mode = $(".mode_patient:checked").val();
		showSubForm(patient_mode);
	});

	$(".mode_calendar").on('click', function(){
		var calendar_mode = $(".mode_calendar:checked").val();
		showSubForm(calendar_mode);
	});

	$(".mode_regimen").on('click', function(){
		var regimen_mode = $(".mode_regimen:checked").val();
		showSubForm(regimen_mode);
	});

	$(".mode_inventory").on('click', function(){
		var inventory_mode = $(".mode_inventory:checked").val();
		showSubForm(inventory_mode);
	});

	$(".mode_billing").on('click', function(){
		var inventory_mode = $(".mode_billing:checked").val();
		showSubForm(inventory_mode);
	});

	$(".date_picker").datepicker({
			format: 'yyyy-mm-dd'
	});

	$('.date_picker').on('changeDate', function(){
	    $(this).datepicker('hide');
	});
});

function openReportGenerator(){
	$.post(base_url + "account_billing/loadReportGeneratorModal", {}, function(o){
		$('#report_generator_wrapper').html(o);
		$('#report_generator_wrapper').modal();
	});
}
</script>

<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-account.png"></li>
			<li><h1>Reports Generator</h1></li>
		</ul>
		<div class="clear"></div>
	</hgroup>

	<section id="left" style="border-right: dashed 1px #d3d3d3;">
		<ul id="form02">
			<li>
				Filename
			</li>
			<li>
				<input type="text" class="" id="filename" name="filename" style="width: 150px;">
			</li>
		</ul>
		<div class="clear"></div>
		<ul id="form02">
			<li>
				Module
			</li>
			<li>
				<select id="module_name" name="module_name" class="select" style="width: 150px;">
					<option value="patients">Patients</option>
					<option value="regimen">Regimen</option>
					<option value="inventory">Inventory</option>
					<option value="billing">Accounts & Billing</option>
					<option value="returns">Returns</option>
					<option value="calendar">Calendar</option>
				</select>
			</li>
		</ul>
		<div class="clear"></div>
	</section>
	<section id="right">  
		<div id="calendar_form" class="main_form">
			<h3>Calendar</h3>
			<input type="radio" class="mode_calendar" name="calendar_form_type" value="event_daterange" checked> <label for="r1"><span></span>All Events</label><br>
			<input type="radio" class="mode_calendar" name="calendar_form_type" value="event_per_patient"> <label for="r2"><span></span>Per Patient</label><br>
			<input type="radio" class="mode_calendar" name="calendar_form_type" value="birthdays"> <label for="r2"><span></span>Birthdays</label>

			<div class="sub_form" id="event_daterange_form">
				<h4>Date Range ( Event Date )</h4>
				<ul id="form02">
					<li>
						From Date - To Date
					</li>
					<li style="width:315px;">
						<input type="text" class="date_picker" id="calendar_from_date" name="calendar_from_date" style="width: 150px;"> - <input type="text" class="date_picker" id="calendar_to_date" name="calendar_to_date" style="width: 150px;">
					</li>
				</ul>
				<div class="clear"></div>

				<div class="alert alert-info" role="alert">
					<b>Attention!</b><br/>This include all Event Name, Event Details, and Invitees based on date selected.
				</div>

				<div class="clear"></div>

				<section id="buttons" style="float:left !important;">
					<button type="button" class="form_button-green" id="submit_event_daterange" style="margin-left: 0px;">Generate Report</button>
				</section>
				<div class="clear"></div>
			</div>

			<div class="sub_form" id="event_per_patient_form">
				<script>
					$(function() {
						var opts=$("#user_source2").html(), opts2="<option></option>"+opts;
					    $("#patient_list2").each(function() { var e=$(this); e.html(e.hasClass("placeholder")?opts2:opts); });
					    $("#patient_list2").select2({allowClear: true});
					});
				</script>

			<select id="patient_list2" name="patient_id" class="populate" style="width:250px;"></select>
			<select id="user_source2" class="" style="display:none">
				<option value="0">Select Patient</option>
			  	<?php foreach($patients as $key=>$value): ?>
			    	<option value="<?php echo $value['id']; ?>"><?php echo $value['firstname'] . " " . $value['lastname']; ?></option>
			 	<?php endforeach; ?>
			</select>
			<div class="clear"></div>

			<div class="alert alert-info" role="alert">
				<b>Attention!</b><br/>This include Event Name, Event Details, and Invitees based on Selected Patient.
			</div>

			<div class="clear"></div>
			<section id="buttons" style="float:left !important;">
				<button type="button" class="form_button-green" id="submit_event_per_patient" style="margin-left: 0px;">Generate Report</button>
			</section>
			</div>
		</div>

		<div class="sub_form" id="birthdays_form">
			<select name="month" id="month" class="select" style="width: 150px;">
				<option value="All">All</option>
				<option value="January">January</option>
				<option value="February">February</option>
				<option value="March">March</option>
				<option value="April">April</option>
				<option value="May">May</option>
				<option value="June">June</option>
				<option value="July">July</option>
				<option value="August">August</option>
				<option value="September">September</option>
				<option value="October">October</option>
				<option value="November">November</option>
				<option value="December">December</option>
			</select>
			<div class="clear"></div>

			<div class="alert alert-info" role="alert">
				<b>Attention!</b><br/>This include Birthdays of Patients based on Selected Month.
			</div>

			<div class="clear"></div>
			<section id="buttons" style="float:left !important;">
				<button type="button" class="form_button-green" id="submit_birthday" style="margin-left: 0px;">Generate Report</button>
			</section>
			</div>
		</div>
<!-- Patient -->

		<div id="patient_form" class="main_form">
			<h3>Patient Module</h3>
			<input type="radio" class="mode_patient" name="patient_form_type" value="per_patient" checked> <label for="r1"><span></span>Per Patient</label><br>
			<input type="radio" class="mode_patient" name="patient_form_type" value="all_patient"> <label for="r2"><span></span>All Patients</label><br>
			<input type="radio" class="mode_patient" name="patient_form_type" value="all_patient_without_daterange"> <label for="r3"><span></span>All Patients (w/o daterange)</label><br>
			<input type="radio" class="mode_patient" name="patient_form_type" value="all_active_patient"> <label for="r4"><span></span>All Active Patients</label><br>
			<input type="radio" class="mode_patient" name="patient_form_type" value="all_inactive_patient"> <label for="r5"><span></span>All Inactive Patients</label>
 
			<!-- <div class="clear"></div> -->
			<div class="sub_form" id="per_patient_form">
				<script>
					$(function() {
						var opts=$("#user_source").html(), opts2="<option></option>"+opts;
					    $("#patient_list").each(function() { var e=$(this); e.html(e.hasClass("placeholder")?opts2:opts); });
					    $("#patient_list").select2({allowClear: true});
					});
				</script>

				<select id="patient_list" name="patient_id" class="populate" style="width:250px;"></select>
				<select id="user_source" class="" style="display:none">
					<option value="0">Select Patient</option>
				  	<?php foreach($patients as $key=>$value): ?>
				    	<option value="<?php echo $value['id']; ?>"><?php echo $value['firstname'] . " " . $value['lastname']; ?></option>
				 	<?php endforeach; ?>
				</select>
				<div class="clear"></div>

				<div class="alert alert-info" role="alert">
					<b>Attention!</b><br/>This also includes Medical History, Regimen History, Returns History of selected patient.
				</div>

				<div class="clear"></div>
				<section id="buttons" style="float:left !important;">
					<button type="button" class="form_button-green" id="submit_per_patient" style="margin-left: 0px;">Generate Report</button>
				</section>
				<div class="clear"></div>
			</div>

			<div class="sub_form" id="all_patients_form">
				<h4>Date Range ( Appointment Date )</h4>
				<ul id="form02">
					<li>
						From Date - To Date
					</li>
					<li style="width:315px;">
						<input type="text" class="date_picker" id="p_from_date" name="p_from_date" style="width: 150px;"> - <input type="text" class="date_picker" id="p_to_date" name="p_to_date" style="width: 150px;">
					</li>
				</ul>
				<div class="clear"></div>

				<div class="alert alert-info" role="alert">
					<b>Attention!</b><br/>This does not include Medical History, Regimen History, Returns History of patients.
				</div>

				<div class="clear"></div>

				<section id="buttons" style="float:left !important;">
					<button type="button" class="form_button-green" id="submit_all_patient" style="margin-left: 0px;">Generate Report</button>
				</section>
				<div class="clear"></div>
			</div>

			<div class="sub_form" id="all_patients_without_daterange_form">
				<h4>List of All Patients ( without date range )</h4>
				
				<div class="clear"></div>

				<div class="alert alert-info" role="alert">
					<b>Attention!</b><br/>This does not include Medical History, Regimen History, Returns History of patients.
				</div>

				<div class="clear"></div>

				<section id="buttons" style="float:left !important;">
					<button type="button" class="form_button-green" id="submit_all_patient_without_daterange" style="margin-left: 0px;">Generate Report</button>
				</section>
				<div class="clear"></div>
			</div>
		</div>

		<div class="sub_form" id="all_active_patients_form">
				<h4>List of All Active Patients </h4>
				
				<div class="clear"></div>

				<div class="alert alert-info" role="alert">
					<b>Attention!</b><br/>This does not include Medical History, Regimen History, Returns History of patients.
				</div>

				<div class="clear"></div>

				<section id="buttons" style="float:left !important;">
					<button type="button" class="form_button-green" id="submit_all_active_patient" style="margin-left: 0px;">Generate Report</button>
				</section>
				<div class="clear"></div>
			</div>

			<div class="sub_form" id="all_inactive_patients_form">
				<h4>List of All Inactive Patients </h4>
				
				<div class="clear"></div>

				<div class="alert alert-info" role="alert">
					<b>Attention!</b><br/>This does not include Medical History, Regimen History, Returns History of patients.
				</div>

				<div class="clear"></div>

				<section id="buttons" style="float:left !important;">
					<button type="button" class="form_button-green" id="submit_all_inactive_patient" style="margin-left: 0px;">Generate Report</button>
				</section>
				<div class="clear"></div>
			</div>
		</div>

		<div id="regimen_form" class="main_form">
			<h3>Regimen Module</h3>
			<input type="radio" class="mode_regimen" name="regimen_form_type" value="per_regimen" checked> <label for="r1"><span></span>Per Regimen</label><br>
			<input type="radio" class="mode_regimen" name="regimen_form_type" value="all_regimen"> <label for="r2"><span></span>All Regimen</label>

			<!-- <div class="clear"></div> -->
			<div class="sub_form" id="per_regimen_form">
				<script>
					$(function() {
						var opts=$("#regimen_source").html(), opts2="<option></option>"+opts;
					    $("#regimen_list").each(function() { var e=$(this); e.html(e.hasClass("placeholder")?opts2:opts); });
					    $("#regimen_list").select2({allowClear: true});
					});
				</script>

				<select id="regimen_list" name="regimen_id" class="populate" style="width:250px;"></select>
				<select id="regimen_source" class="" style="display:none">
					<option value="0">Select Regimen</option>
				  	<?php foreach($regimen as $key=>$value): ?>
				    	<option value="<?php echo $value['id']; ?>"><?php echo $value['regimen_number'] ?></option>
				 	<?php endforeach; ?>
				</select>
				<div class="clear"></div>

				<div class="alert alert-info" role="alert">
					<b>Attention!</b><br/>This also includes Medicines and Versions of selected regimen.
				</div>

				<div class="clear"></div>
				<section id="buttons" style="float:left !important;">
					<button type="button" class="form_button-green" id="submit_per_regimen" style="margin-left: 0px;">Generate Report</button>
				</section>
				<div class="clear"></div>
			</div>

			<div class="sub_form" id="all_regimen_form">
				<h4>Date Range ( Date Generated )</h4>
				<ul id="form02">
					<li>
						From Date - To Date
					</li>
					<li style="width:315px;">
						<input type="text" class="date_picker" id="r_from_date" name="r_from_date" style="width: 150px;"> - <input type="text" class="date_picker" id="r_to_date" name="r_to_date" style="width: 150px;">
					</li>
				</ul>
				<div class="clear"></div>

				<div class="alert alert-info" role="alert">
					<b>Attention!</b><br/>This does not include Medicines and Versions of Regimens.
				</div>

				<div class="clear"></div>

				<section id="buttons" style="float:left !important;">
					<button type="button" class="form_button-green" id="submit_all_regimen" style="margin-left: 0px;">Generate Report</button>
				</section>
				<div class="clear"></div>
			</div>
		</div>

		<div id="returns_form" class="main_form">
			<h3>Return Module</h3>

			<div class="sub_form" id="all_return_form">
				<h4>Date Range ( Date Generated )</h4>
				<ul id="form02">
					<li>
						From Date - To Date
					</li>
					<li style="width:315px;">
						<input type="text" class="date_picker" id="return_from_date" name="return_from_date" style="width: 150px;"> - <input type="text" class="date_picker" id="return_to_date" name="return_to_date" style="width: 150px;">
					</li>
				</ul>
				<div class="clear"></div>

				<div class="alert alert-info" role="alert">
					<b>Attention!</b><br/>This include all Returns.
				</div>

				<div class="clear"></div>

				<section id="buttons" style="float:left !important;">
					<button type="button" class="form_button-green" id="submit_all_returns" style="margin-left: 0px;">Generate Report</button>
				</section>
				<div class="clear"></div>
			</div>
		</div>
<!-- Inventory -->
		<div id="inventory_form" class="main_form">
			<h3>Inventory Module</h3>
			<input type="radio" class="mode_inventory" name="inventory_form_type" value="per_inventory" checked> <label for="r1"><span></span>Per Inventory</label><br>
			<input type="radio" class="mode_inventory" name="inventory_form_type" value="all_inventory"> <label for="r2"><span></span>All Inventory</label><br>
			<input type="radio" class="mode_inventory" name="inventory_form_type" value="per_batch" > <label for="r3"><span></span>Per Batch</label><br>
			<input type="radio" class="mode_inventory" name="inventory_form_type" value="all_batch"> <label for="r4"><span></span>All Batch</label><br>
			<input type="radio" class="mode_inventory" name="inventory_form_type" value="claim_sold_item"> <label for="r5"><span></span>Claim & Sold Item</label>
			<!-- <div class="clear"></div> -->
			<div class="sub_form" id="claim_sold_item_form">
				<ul id="form02">
				<br>
				
				<label>Date Range for Claim and Sold Medicine</label>
				<br>
					<li>
						Start Date - End Date
					</li>
				<br>
					<li style="width:315px;">
						<input type="text" class="date_picker" id="claim_from_date" name="claim_from_date" style="width: 150px;"> - <input type="text" class="date_picker" id="claim_to_date" name="claim_to_date" style="width: 150px;">
					</li>
				</ul>
				<div class="clear"></div>

				<div class="alert alert-info" role="alert">
					<b>Attention!</b><br/>This also includes the Claim and Sold Item based on the selected date range.
				</div>

				<div class="clear"></div>
				<section id="buttons" style="float:left !important;">
					<button type="button" class="form_button-green" id="submit_claim_sold_item" style="margin-left: 0px;">Generate Report</button>
				</section>
				<div class="clear"></div>
			</div>

			<div class="sub_form" id="per_inventory_form">
				<script>
					$(function() {
						var opts=$("#inventory_source").html(), opts2="<option></option>"+opts;
					    $("#inventory_list").each(function() { var e=$(this); e.html(e.hasClass("placeholder")?opts2:opts); });
					    $("#inventory_list").select2({allowClear: true});
					});
				</script>

				<select id="inventory_list" name="inventory_id" class="populate" style="width:250px;"></select>
				<select id="inventory_source" class="" style="display:none">
					<option value="0">Select Inventory</option>
				  	<?php foreach($inventory as $key=>$value): ?>
				    	<option value="<?php echo $value['id']; ?>"><?php echo $value['medicine_name'] . " " . $value['dosage'] . " " . $value['abbreviation']; ?></option>
				 	<?php endforeach; ?>
				</select>

				<div class="clear"></div>

				<ul id="form02">
					<li>
						<b> <center>Invoiced Meds From Date - To Date</center></b>
					</li>
					<li style="width:315px;">
						<input type="text" class="date_picker" id="inv_from_date" name="inv_from_date" style="width: 150px;"> - <input type="text" class="date_picker" id="inv_to_date" name="inv_to_date" style="width: 150px;">
					</li>
				</ul>
				<div class="clear"></div>

				<div class="clear"></div>

				<div class="alert alert-info" role="alert">
					<b>Attention!</b><br/>This also includes Batch List, Invoiced Medicine and Stock History of selected medicine.
				</div>

				<div class="clear"></div>
				<section id="buttons" style="float:left !important;">
					<button type="button" class="form_button-green" id="submit_per_inventory" style="margin-left: 0px;">Generate Report</button>
				</section>
				<!-- <div class="clear"></div> -->
			</div>

			<div class="sub_form" id="all_inventory_form">
				<!--<input type="radio" class="mode_inventory_sub" name="inventory_form_type_sub" value="PurchaseDate" checked> <label for="r1"><span></span>Purchase Date</label>
				<input type="radio" class="mode_inventory_sub" name="inventory_form_type_sub" value="ExpiryDate"> <label for="r2"><span></span>Expiry Date</label>

				 <ul id="form02">
					<li>
						From Date - To Date
					</li>
					<li style="width:315px;">
						<input type="text" class="date_picker" id="i_from_date" name="i_from_date" style="width: 150px;"> - <input type="text" class="date_picker" id="i_to_date" name="i_to_date" style="width: 150px;">
					</li>
				</ul>
				<div class="clear"></div>
				-->

				<div class="alert alert-info" role="alert">
					<b>Attention!</b><br/>This does not include Stock History.
				</div>

				<div class="clear"></div> 
			
				<section id="buttons" style="float:left !important;">
					<button type="button" class="form_button-green" id="submit_all_inventory" style="margin-left: 0px;">Generate Report</button>
				</section>
				<div class="clear"></div>
			</div>
		</div>

		<div class="sub_form" id="all_batch_form">
				<input type="radio" class="mode_inventory_batch_sub" name="inventory_form_type_sub" value="PurchaseDate" checked> <label for="r1"><span></span>Purchase Date</label>
				<input type="radio" class="mode_inventory_batch_sub" name="inventory_form_type_sub" value="ExpiryDate"> <label for="r2"><span></span>Expiry Date</label>

				<ul id="form02">
					<li>
						From Date - To Date
					</li>
					<li style="width:315px;">
						<input type="text" class="date_picker" id="batch_from_date" name="batch_from_date" style="width: 150px;"> - <input type="text" class="date_picker" id="batch_to_date" name="batch_to_date" style="width: 150px;">
					</li>
				</ul>
				<div class="clear"></div>

				<div class="alert alert-info" role="alert">
					<b>Attention!</b><br/>This does not include Stock History.
				</div>

				<div class="clear"></div>

				<section id="buttons" style="float:left !important;">
					<button type="button" class="form_button-green" id="submit_all_batch" style="margin-left: 0px;">Generate Report</button>
				</section>
				<div class="clear"></div>
			</div>
		</div>

		<div class="sub_form" id="per_batch_form">
				<script>
					$(function() {
						var opts=$("#inventory_batch_source").html(), opts2="<option></option>"+opts;
					    $("#inventory_batch_list").each(function() { var e=$(this); e.html(e.hasClass("placeholder")?opts2:opts); });
					    $("#inventory_batch_list").select2({allowClear: true});
					});
				</script>

				<select id="inventory_batch_list" name="inventory_id" class="populate" style="width:250px;"></select>
				<select id="inventory_batch_source" class="" style="display:none">
					<option value="0">Select Inventory</option>
				  	<?php foreach($inventory as $key=>$value): ?>
				    	<option value="<?php echo $value['id']; ?>"><?php echo $value['medicine_name'] . " " . $value['dosage'] . " " . $value['abbreviation']; ?></option>
				 	<?php endforeach; ?>
				</select>
				<div class="clear"></div>

				<div class="alert alert-info" role="alert">
					<b>Attention!</b><br/>This includes the Batch Details of selected medicine.
				</div>

				<div class="clear"></div>
				<section id="buttons" style="float:left !important;">
					<button type="button" class="form_button-green" id="submit_per_batch" style="margin-left: 0px;">Generate Report</button>
				</section>
				<div class="clear"></div>
		</div>
<div class="clear"></div>	

		<div id="billing_form" class="main_form" style="width:500px;">
			<h3>Billing Module</h3>
			<input type="radio" class="mode_billing" name="billing_form_type" value="per_collection" checked> <label for="r1"><span></span>Per Collection</label><br>
			<input type="radio" class="mode_billing" name="billing_form_type" value="all_collection"> <label for="r2"><span></span>All Collections</label><br>
			<input type="radio" class="mode_billing" name="billing_form_type" value="per_sales"> <label for="r3"><span></span>Sales / Account Receivables</label>

			<!-- <div class="clear"></div> -->
			<div class="sub_form" id="per_collection_form">
				<script>
					$(function() {
						var opts=$("#collection_source").html(), opts2="<option></option>"+opts;
					    $("#collection_list").each(function() { var e=$(this); e.html(e.hasClass("placeholder")?opts2:opts); });
					    $("#collection_list").select2({allowClear: true});
					});
				</script>

				<select id="collection_list" name="collection_id" class="populate" style="width:250px;"></select>
				<select id="collection_source" class="" style="display:none">
					<option value="0">Select Collections</option>
				  	<?php foreach($collections as $key=>$value): ?>
				    	<option value="<?php echo $value['id']; ?>"><?php echo $value['or_number'] ?></option>
				 	<?php endforeach; ?>
				</select>
				<div class="clear"></div>

				<!-- <div class="alert alert-info" role="alert">
					<b>Attention!</b><br/>This also includes Stock History of selected medicine.
				</div> -->

				<div class="clear"></div>
				<section id="buttons" style="float:left !important;">
					<button type="button" class="form_button-green" id="submit_per_collection" style="margin-left: 0px;">Generate Report</button>
				</section>
				<div class="clear"></div>
			</div>

			<div class="sub_form" id="all_collection_form">
				<h4>Date Range ( Date of Receipt )</h4>

				<ul id="form02">
					<li>
						From Date - To Date
					</li>
					<li style="width:345px;">
						<input type="text" class="date_picker" id="c_from_date" name="c_from_date" style="width: 150px;"> - <input type="text" class="date_picker" id="c_to_date" name="c_to_date" style="width: 150px;">
					</li>
				</ul>
				<div class="clear"></div>

				<!-- <div class="alert alert-info" role="alert">
					<b>Attention!</b><br/>This does not include Stock History.
				</div> -->

				<div class="clear"></div>

				<section id="buttons" style="float:left !important;">
					<button type="button" class="form_button-green" id="submit_all_collection" style="margin-left: 0px;">Generate Report</button>
				</section>
				<div class="clear"></div>
			</div>

			<div class="sub_form" id="per_sales_form">
				<script>
					$(function() {
						var opts=$("#sales_source").html(), opts2="<option></option>"+opts;
					    $("#sales_list").each(function() { var e=$(this); e.html(e.hasClass("placeholder")?opts2:opts); });
					    $("#sales_list").select2({allowClear: true});
					});
				</script>

				<select id="sales_list" name="inventory_id" class="populate" style="width:250px;"></select>
				<select id="sales_source" class="" style="display:none">
					<option value="0">Select Sales</option>
				  	<?php foreach($sales as $key=>$value): ?>
				    	<option value="<?php echo $value['id']; ?>"><?php echo $value['invoice_num'] ?></option>
				 	<?php endforeach; ?>
				</select>
				<div class="clear"></div>

				<!-- <div class="alert alert-info" role="alert">
					<b>Attention!</b><br/>This also includes Stock History of selected medicine.
				</div> -->

				<div class="clear"></div>
				<section id="buttons" style="float:left !important;">
					<button type="button" class="form_button-green" id="submit_per_sales" style="margin-left: 0px;">Generate Report</button>
				</section>
				<div class="clear"></div>

				<button type="button" class="btn btn-info" onclick="javascript: openReportGenerator();">All Sales Report Generator</button>
			</div>
		</div>


	</section>
	
<div class="clear"></div>
</section>


<script>
	$(function(){
		$("#submit_birthday").on('click', function(){
			var filename 		= $.trim($("#filename").val());
			var month 		= $.trim($("#month").val());

			if(hasValue(month) && hasValue(filename) && month != 0){
				window.open(base_url + "download/generate_report_birthdays/" + filename + "/" + month,'_blank');
				new PNotify({
			      title: "Generate Report",
			      text: "We have successfully generated the report.",
			      type: "info",
			    });
				clearThingsUP();
			} else {
				new PNotify({
			      title: "Oops! Error!",
			      text: "Error generating report, Please Select Regimen and Filename!",
			      type: "error",
			    });
			}
			
		});

		$("#submit_event_daterange").on('click', function(){
			var filename 	= $.trim($("#filename").val());

			var from_date 	= $.trim($("#calendar_from_date").val());
			var to_date 	= $.trim($("#calendar_to_date").val());
			if(hasValue(from_date) && hasValue(filename) && hasValue(to_date)){
				// alert(filename + "/" + from_date + "/" + to_date);
				window.open(base_url + "download/generate_report_event_daterange/" + filename + "/" + from_date + "/" + to_date,'_blank');
				new PNotify({
			      title: "Generate Report",
			      text: "We have successfully generated the report.",
			      type: "info",
			    });
				clearThingsUP();
			} else {
				new PNotify({
			      title: "Oops! Error!",
			      text: "Error generating report, Please Enter From Date / To Date and Filename!",
			      type: "error",
			    });
			}
			
		});

		$("#submit_event_per_patient").on('click', function(){
			var filename 		= $.trim($("#filename").val());
			var patient_id 		= $.trim($("#patient_list2").val());

			if(hasValue(patient_id) && hasValue(filename) && patient_id != 0){
				window.open(base_url + "download/generate_report_event_per_patient/" + filename + "/" + patient_id,'_blank');
				new PNotify({
			      title: "Generate Report",
			      text: "We have successfully generated the report.",
			      type: "info",
			    });
				clearThingsUP();
			} else {
				new PNotify({
			      title: "Oops! Error!",
			      text: "Error generating report, Please Select Regimen and Filename!",
			      type: "error",
			    });
			}
			
		});

		$("#submit_all_patient").on('click', function(){
			var filename 	= $.trim($("#filename").val());

			var from_date 	= $.trim($("#p_from_date").val());
			var to_date 	= $.trim($("#p_to_date").val());
			if(hasValue(from_date) && hasValue(filename) && hasValue(to_date)){
				// alert(filename + "/" + from_date + "/" + to_date);
				window.open(base_url + "download/generate_report_all_patients/" + filename + "/" + from_date + "/" + to_date,'_blank');
				new PNotify({
			      title: "Generate Report",
			      text: "We have successfully generated the report.",
			      type: "info",
			    });
				clearThingsUP();
			} else {
				new PNotify({
			      title: "Oops! Error!",
			      text: "Error generating report, Please Enter From Date / To Date and Filename!",
			      type: "error",
			    });
			}
			
		});

		$("#submit_per_inventory").on('click', function(){
			var filename 		= $.trim($("#filename").val());
			var inventory_id 	= $.trim($("#inventory_list").val());
			var inv_from_date   = $.trim($("#inv_from_date").val());
			var inv_to_date   = $.trim($("#inv_to_date").val());
			
			if(hasValue(inventory_id) && hasValue(filename) && inventory_id != 0 && hasValue(inv_from_date) && hasValue(inv_to_date)){
				// alert(filename + "/" + inventory_id);
				window.open(base_url + "download/generate_report_inventory/" + filename + "/" + inventory_id + "/" + inv_from_date + "/" + inv_to_date,'_blank');
				new PNotify({
			      title: "Generate Report",
			      text: "We have successfully generated the report.",
			      type: "info",
			    });
				clearThingsUP();
			} else {
				new PNotify({
			      title: "Oops! Error!",
			      text: "Error generating report, Please Select Inventory, Filename, Invoice Medicine from date and To date !",
			      type: "error",
			    });
			}
			
		});

		$("#submit_claim_sold_item").on('click', function(){
			var filename 		= $.trim($("#filename").val());
			var claim_from_date   = $.trim($("#claim_from_date").val());
			var claim_to_date   = $.trim($("#claim_to_date").val());
			
			if(hasValue(filename) && hasValue(claim_from_date) && hasValue(claim_to_date)){
				// alert(filename + "/" + inventory_id);
				window.open(base_url + "download/generate_report_claim_sold_report/" + filename + "/" + claim_from_date + "/" + claim_to_date,'_blank');
				new PNotify({
			      title: "Generate Report",
			      text: "We have successfully generated the report.",
			      type: "info",
			    });
				clearThingsUP();
			} else {
				new PNotify({
			      title: "Oops! Error!",
			      text: "Error generating report, Please Select Regimen and Filename!",
			      type: "error",
			    });
			}
			
		});


		$("#submit_per_patient").on('click', function(){
			var filename 		= $.trim($("#filename").val());
			var patient_id 		= $.trim($("#patient_list").val());

			if(hasValue(patient_id) && hasValue(filename) && patient_id != 0){
				window.open(base_url + "download/generate_report_patient/" + filename + "/" + patient_id,'_blank');
				new PNotify({
			      title: "Generate Report",
			      text: "We have successfully generated the report.",
			      type: "info",
			    });
				clearThingsUP();
			} else {
				new PNotify({
			      title: "Oops! Error!",
			      text: "Error generating report, Please Select Regimen and Filename!",
			      type: "error",
			    });
			}
			
		});

		$("#submit_per_regimen").on('click', function(){
			var filename 	= $.trim($("#filename").val());
			var regimen_id 	= $.trim($("#regimen_list").val());

			if(hasValue(regimen_id) && hasValue(filename) && regimen_id != 0){
				// alert(filename + "/" + regimen_id);
				window.open(base_url + "download/generate_report_regimen/" + filename + "/" + regimen_id,'_blank');
				new PNotify({
			      title: "Generate Report",
			      text: "We have successfully generated the report.",
			      type: "info",
			    });
				clearThingsUP();
			} else {
				new PNotify({
			      title: "Oops! Error!",
			      text: "Error generating report, Please Select Regimen and Filename!",
			      type: "error",
			    });
			}
			
		});

		$("#submit_all_regimen").on('click', function(){
			var filename 	= $.trim($("#filename").val());

			var from_date 	= $.trim($("#r_from_date").val());
			var to_date 	= $.trim($("#r_to_date").val());
			if(hasValue(from_date) && hasValue(filename) && hasValue(to_date)){
				// alert(filename + "/" + from_date + "/" + to_date);
				window.open(base_url + "download/generate_report_all_regimen/" + filename + "/" + from_date + "/" + to_date,'_blank');
				new PNotify({
			      title: "Generate Report",
			      text: "We have successfully generated the report.",
			      type: "info",
			    });
				clearThingsUP();
			} else {
				new PNotify({
			      title: "Oops! Error!",
			      text: "Error generating report, Please Enter From Date / To Date and Filename!",
			      type: "error",
			    });
			}
			
		});

		$("#submit_all_returns").on('click', function(){
			var filename 	= $.trim($("#filename").val());

			var from_date 	= $.trim($("#return_from_date").val());
			var to_date 	= $.trim($("#return_to_date").val());
			if(hasValue(from_date) && hasValue(filename) && hasValue(to_date)){
				// alert(filename + "/" + from_date + "/" + to_date);
				window.open(base_url + "download/generate_report_all_returns/" + filename + "/" + from_date + "/" + to_date,'_blank');
				new PNotify({
			      title: "Generate Report",
			      text: "We have successfully generated the report.",
			      type: "info",
			    });
				clearThingsUP();
			} else {
				new PNotify({
			      title: "Oops! Error!",
			      text: "Error generating report, Please Enter From Date / To Date and Filename!",
			      type: "error",
			    });
			}
			
		});

		$("#submit_all_inventory").on('click', function(){
			var filename 	= $.trim($("#filename").val());
			
			if(hasValue(filename)){
				window.open(base_url + "download/generate_report_all_inventory/" + filename);
				new PNotify({
			      title: "Generate Report",
			      text: "We have successfully generated the report.",
			      type: "info",
			    });
				clearThingsUP();
			} else {
				new PNotify({
			      title: "Oops! Error!",
			      text: "Error generating report, Please Enter From Date / To Date and Filename!",
			      type: "error",
			    });
			}
			
		});

		$("#submit_all_collection").on('click', function(){
			var filename 	= $.trim($("#filename").val());

			var from_date 	= $.trim($("#c_from_date").val());
			var to_date 	= $.trim($("#c_to_date").val());
			
			if(hasValue(from_date) && hasValue(filename) && hasValue(to_date)){
				// alert(filename + "/" + from_date + "/" + to_date);
				window.open(base_url + "download/generate_report_all_collection/" + filename + "/" + from_date + "/" + to_date,'_blank');
				new PNotify({
			      title: "Generate Report",
			      text: "We have successfully generated the report.",
			      type: "info",
			    });
				clearThingsUP();
			} else {
				new PNotify({
			      title: "Oops! Error!",
			      text: "Error generating report, Please Enter From Date / To Date and Filename!",
			      type: "error",
			    });
			}
			
		});

		$("#submit_per_collection").on('click', function(){
			var filename 	= $.trim($("#filename").val());
			var or_id 		= $.trim($("#collection_list").val());

			if(hasValue(or_id) && hasValue(filename) && or_id != 0){
				// alert(filename + "/" + or_id);
				window.open(base_url + "download/generate_report_collection/" + filename + "/" + or_id,'_blank');
				new PNotify({
			      title: "Generate Report",
			      text: "We have successfully generated the report.",
			      type: "info",
			    });
				clearThingsUP();
			} else {
				new PNotify({
			      title: "Oops! Error!",
			      text: "Error generating report, Please Select Regimen and Filename!",
			      type: "error",
			    });
			}
			
		});

		$("#submit_per_sales").on('click', function(){
			var filename 	= $.trim($("#filename").val());
			var sales_id	= $.trim($("#sales_list").val());

			if(hasValue(sales_id) && hasValue(filename) && sales_id != 0){
				// alert(filename + "/" + sales_id);
				window.open(base_url + "download/generate_report_sales/" + filename + "/" + sales_id,'_blank');
				new PNotify({
			      title: "Generate Report",
			      text: "We have successfully generated the report.",
			      type: "info",
			    });
				clearThingsUP();
			} else {
				new PNotify({
			      title: "Oops! Error!",
			      text: "Error generating report, Please Select Sales and Filename!",
			      type: "error",
			    });
			}
			
		}); 

		$("#submit_per_batch").on('click', function(){
			var filename 		= $.trim($("#filename").val());
			var inventory_id 	= $.trim($("#inventory_batch_list").val());

			if(hasValue(inventory_id) && hasValue(filename) && inventory_id != 0){
				// alert(filename + "/" + inventory_id);
				window.open(base_url + "download/generate_report_per_batch/" + filename + "/" + inventory_id,'_blank');
				new PNotify({
			      title: "Generate Report",
			      text: "We have successfully generated the report.",
			      type: "info",
			    });
				clearThingsUP();
			} else {
				new PNotify({
			      title: "Oops! Error!",
			      text: "Error generating report, Please Select Regimen and Filename!",
			      type: "error",
			    });
			}
			
		});

		$("#submit_all_batch").on('click', function(){
			var filename 	= $.trim($("#filename").val());

			var from_date 	= $.trim($("#batch_from_date").val());
			var to_date 	= $.trim($("#batch_to_date").val());

			var type 		= $.trim($(".mode_inventory_batch_sub:checked").val());
			if(hasValue(from_date) && hasValue(filename) && hasValue(to_date)){
				// alert(filename + "/" + from_date + "/" + to_date);
				window.open(base_url + "download/generate_report_all_batch/" + filename + "/" + from_date + "/" + to_date + "/" + type,'_blank');
				new PNotify({
			      title: "Generate Report",
			      text: "We have successfully generated the report.",
			      type: "info",
			    });
				clearThingsUP();
			} else {
				new PNotify({
			      title: "Oops! Error!",
			      text: "Error generating report, Please Enter From Date / To Date and Filename!",
			      type: "error",
			    });
			}
			
		});

		$("#submit_all_patient_without_daterange").on('click', function(){
			var filename 	= $.trim($("#filename").val());

			if(hasValue(filename)){
				window.open(base_url + "download/generate_report_all_patient_without_daterange/" + filename);
				new PNotify({
			      title: "Generate Report",
			      text: "We have successfully generated the report.",
			      type: "info",
			    });
				clearThingsUP();
			} else {
				new PNotify({
			      title: "Oops! Error!",
			      text: "Error generating report, Please Enter From Date / To Date and Filename!",
			      type: "error",
			    });
			}
		});

		$("#submit_all_active_patient").on('click', function(){
			var filename 	= $.trim($("#filename").val());

			if(hasValue(filename)){
				window.open(base_url + "download/generate_report_all_active_patient/" + filename);
				new PNotify({
			      title: "Generate Report",
			      text: "We have successfully generated the report.",
			      type: "info",
			    });
				clearThingsUP();
			} else {
				new PNotify({
			      title: "Oops! Error!",
			      text: "Error generating report, Please Enter From Date / To Date and Filename!",
			      type: "error",
			    });
			}
		});

		$("#submit_all_inactive_patient").on('click', function(){
			var filename 	= $.trim($("#filename").val());

			if(hasValue(filename)){
				window.open(base_url + "download/generate_report_all_inactive_patient/" + filename);
				new PNotify({
			      title: "Generate Report",
			      text: "We have successfully generated the report.",
			      type: "info",
			    });
				clearThingsUP();
			} else {
				new PNotify({
			      title: "Oops! Error!",
			      text: "Error generating report, Please Enter From Date / To Date and Filename!",
			      type: "error",
			    });
			}
		});
	
	});

	function clearThingsUP(){
		$("#filename").val("");
		$(".date_picker").val("");
		$("#filename").focus();
	}
</script>