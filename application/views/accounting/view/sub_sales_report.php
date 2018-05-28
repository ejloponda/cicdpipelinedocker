<script>
	$(function(){

		if($('.tipsy-inner')) {
			$('.tipsy-inner').remove();
		}

		var filter = "<?php echo $post['filter'] ?>";
		var filter_to_search = "<?php echo $post['filter_to_search'] ?>";
		$('#sales_report_list_dt').dataTable({
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
			"aoColumns": [
			    null,null,null,null,null,null,null,null,null,null,null,null,
			    { "sClass": "highlight" },
			    null
			],
			"sAjaxSource": base_url + "account_billing/loadDataTableSalesReport?filter=" + filter + "&filter_to_search=" + filter_to_search ,
			"fnDrawCallback": function( oSettings ) {
				$('.edit_regimen').tipsy({gravity: 's'});
				$('.delete_regimen').tipsy({gravity: 's'});
				$('.medicine_title').tipsy({gravity: 's'});
				var total_sales = 0;
				$('.highlight').each(function(i) {
					if(i > 0){
						total_sales = total_sales + parseFloat($(this).html());
					}
				});

				$("#total_sales_span").html(total_sales.toFixed(2));
		    },

		});
	});
</script>

<style>
.correct_alignment
{
	padding-bottom: 35px !important;
}

td.highlight{
	color: blue;
}
</style>

<table id="sales_report_list_dt" class="datatable table" style="min-width: 90%;">
    <thead>
		<tr>
			<th valign="top" width="5%" style="text-align: center;">Invoice No.</th>
			<th valign="top" width="5%" style="text-align: center;">Invoice ID</th>
			<th valign="top" width="10%" style="text-align: center;">Doctor</th>
			<th valign="top" width="30%" style="text-align: center;">Patient Name</th>
			<th valign="top" width="5%" style="text-align: center;">TIN</th>
			<th valign="top" width="10%" style="text-align: center;">Invoice Date</th>
			<th valign="top" width="10%" style="text-align: center;">Due Date</th>
			<th valign="top" width="2%" style="text-align: center;">Aging</th>
			<th valign="top" width="5%" style="text-align: center;">Total Gross Sales</th>
			<th valign="top" width="5%" style="text-align: center;">Discount Amount</th>
			<th valign="top" width="5%" style="text-align: center;">Invoice Net of VAT</th>
			<th valign="top" width="5%" style="text-align: center;">VAT</th>
			<th valign="top" width="10%" style="text-align: center;">Invoice Amount</th>
			<th valign="top" width="5%" style="text-align: center;">Status</th>
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

