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
		
		$('#accounts_receivables_list_dt').dataTable({
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
			"sAjaxSource": base_url + "account_billing/loadDataTableAccountsReceivables?",
			"fnDrawCallback": function( oSettings ) {
				$('.medicine_title').tipsy({gravity: 's'});
		    }
		});

		reset_all();
		reset_all_topbars_menu();
		$('.billing_menu').addClass('hilited');
		$('.account_billing_menu').removeClass('hidden');
		$('.accounts_receivable_list').addClass('sub-hilited');


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
			<li><h1>Accounts & Billing - Accounts Receivables</h1></li>
		</ul>
		<?php if($invoicing['can_add']){ ?>
			<button class="button01 add_official_receipt">+ Add Official Receipt</button>
		<?php } ?>
		<div class="clear"></div>

	</hgroup>

	<table id="accounts_receivables_list_dt" class="datatable table" style="min-width: 90%;">
	    <thead>
		<tr>
			<th valign="top" width="5%" style="text-align: center;">Invoice No.</th>
			<th valign="top" width="5%" style="text-align: center;">Invoice ID</th>
			<th valign="top" width="10%" style="text-align: center;">Doctor</th>
			<th valign="top" width="20%" style="text-align: center;">Patient Name</th>
			<th valign="top" width="5%" style="text-align: center;">TIN</th>
			<th valign="top" width="10%" style="text-align: center;">Invoice Date</th>
			<th valign="top" width="10%" style="text-align: center;">Due Date</th>
			<th valign="top" width="2%" style="text-align: center;">Aging</th>
			<th valign="top" width="10%" style="text-align: center;">Total Amount Paid</th>
			<th valign="top" width="10%" style="text-align: center;">Total Amount Outstanding</th>
			<th valign="top" width="3%" style="text-align: center;">Status</th>
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