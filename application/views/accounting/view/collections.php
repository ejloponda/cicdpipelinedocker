<style>
.correct_alignment
{
	padding-bottom: 35px !important;
}
</style>
<script>
<!--
	$(function() {

		if($('.tipsy-inner')) {
			$('.tipsy-inner').remove();
		}
		
		$('#collections_list_dt').dataTable({
			"bJQueryUI": true,
	        "sPaginationType": "full_numbers",
	        "bPaginate": true,
	        "bLengthChange": false,
	        "bFilter": true,
	        "bInfo": false,     
	        "bScrollCollapse": false,
			"bProcessing": true,
			"bServerSide": true,
			"iDisplayLength": 10,
			"sAjaxSource": base_url + "account_billing/loadDataTableCollections?",
			"fnDrawCallback": function( oSettings ) {
		    }
		});

		reset_all();
		reset_all_topbars_menu();
		$('.billing_menu').addClass('hilited');
		$('.account_billing_menu').removeClass('hidden');
		$('.collections_list').addClass('sub-hilited');


		$(".add_official_receipt").on('click', function(){
			$.post(base_url + "account_billing/loadORModal", {}, function(o){
				$("#official_receipt_wrapper").html(o);
				$("#official_receipt_wrapper").modal("show");
			});
		});
	});
-->
</script>
<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-account.png"></li>
			<li><h1>Accounts & Billing - Collections</h1></li>
		</ul>
		<?php if($invoicing['can_add']){ ?>
			<button class="button01 add_official_receipt">+ Add Official Receipt</button>
		<?php } ?>
		<div class="clear"></div>

	</hgroup>

	<table id="collections_list_dt" class="datatable table" style="min-width: 90%;">
	    <thead>
		<tr>
			<th valign="top" width="10%" style="text-align: center;">Invoice ID</th>
			<th valign="top" width="10%" style="text-align: center;">Patient Name</th>
			<th valign="top" width="10%" style="text-align: center;">OR Number</th>
			<th valign="top" width="10%" style="text-align: center;">Amount Paid</th>
			<th valign="top" width="10%" style="text-align: center;">Date of Receipt</th>
			<!-- <th valign="top" width="7%" style="text-align: center;"></th> -->
		</tr>
	    </thead>
	    <tbody>
	    </tbody>
	</table>
<style>
	table.datatable td, th {padding-left:5px; padding: 5px; text-align: center;}
	.table_cb {vertical-align: middle; display: block; margin-left:10px;}
	td {font-size: 12px;}
</style>

</section>
<section class="clear"></section>