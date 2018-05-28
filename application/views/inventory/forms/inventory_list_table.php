<style>
.correct_alignment
{
	padding-bottom: 35px !important;
}
</style>
<script>
	$(function(){
		if($('.tipsy-inner')) {
			$('.tipsy-inner').remove();
		}

		if($("#alert_confirmation_wrapper")){
			setTimeout(function(){
				$("#alert_confirmation_wrapper").fadeOut();
			}, 3000)
		}
		
		stock_percentage = parseInt($("#stock_percentage").val());
		$('#inventory_list_dt').dataTable({
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
			"sAjaxSource": base_url + "inventory_management/getAllInventoryList?stock_percentage=" + stock_percentage,
			"fnDrawCallback": function( oSettings ) {
				$('.edit_item_inv').tipsy({gravity: 's'});
				$('.delete_item_inv').tipsy({gravity: 's'});
		    }
		});

	});
</script>
<?php #if($mm_dc['can_view']){ ?>
<table id="inventory_list_dt" class="datatable table" style="min-width: 90%;">
    <thead>
	<tr>
		<!-- <th valign="top" width="1%" style="text-align: center;">ID</th> -->
		<th valign="top" width="10%" style="text-align: center;">Product No.</th>
		<th valign="top" width="10%" style="text-align: center;">Generic Name</th>
		<th valign="top" width="20%" style="text-align: center;">Medicine Name</th>
		<th valign="top" width="3%" style="text-align: center;">Dosage</th>
		<th valign="top" width="3%" style="text-align: center;">Quantity</th>
		<th valign="top" width="3%" style="text-align: center;">Quantity Per Bottle</th>
		<th valign="top" width="3%" style="text-align: center;">Status</th>
		<!-- <th valign="top" width="8%" style="text-align: center;">From Stock</th> -->
		<!-- <th valign="top" width="3%" style="text-align: center;">Date Purchased</th>
		<th valign="top" width="3%" style="text-align: center;">Expiry Date</th> -->
		<?php if($invent['can_update'] || $invent['can_delete']) { ?>
		<th valign="top" width="5%" style="text-align: center;"></th>
		<?php } ?>
	</tr>
    </thead>
    <tbody>
    </tbody>
</table>
<?php #} else { echo "<h1>Ooop! Error viewing this Page. Please contact web administrator!</h1>"; } ?>
<style>
	table.datatable td, th {padding-left:5px; padding: 5px; text-align: center;}
	.table_cb {vertical-align: middle; display: block; margin-left:10px;}
	td {font-size: 12px;}
	.dataTables_paginate{width:100% !important;}
</style>