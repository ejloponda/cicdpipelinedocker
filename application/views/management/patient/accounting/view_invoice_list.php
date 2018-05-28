<script>
	 $(function() {
        $('#regimen_history_list_dt, #stock_history_dt').dataTable( {
	        "bJQueryUI": true,
	        "sPaginationType": "full_numbers",
	        "bPaginate": false,
	        "bLengthChange": false,
	        "bFilter": true,
	        "bInfo": false,     
	        "bScrollCollapse": false
        });

       	$('#regimen_history_list_dt_length, #stock_history_dt_length').hide();
       	$('#regimen_history_list_dt_filter, #stock_history_dt_filter').hide();
      });
</script>
<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li>
				<?php if($patient_image){ ?>
					<img class="photoID" src="<?php echo BASE_FOLDER . $patient_image['base_path'] . $patient_image['filename'] . "." . $patient_image['extension']; ?>">
				<?php } else { ?>
					<img class="photoID" src="<?php echo BASE_FOLDER; ?>themes/images/photo.png">
				<?php } ?>
			</li>
			<li><h1>Patient ID: <?php echo $patient['patient_code'] ?></h1>
				<?php echo $patient['patient_name'] ?></li>
		</ul>
		
		<div class="clear"></div>
	</hgroup>
	<input type="hidden" id="id" value="<?php echo $patient['id'] ?>">
	<input type="hidden" id="header_category" name="header_category" value="Personal">
	<hgroup id="section-header">
			<h1>Invoice List</h1>
			<div class="clear"></div>
		</hgroup>	
			<table id="regimen_history_list_dt" class="datatable table">
				<thead>
					<tr>
						<th style="width: 20%; text-align: center;">Invoice ID</th>
						<th style="width: 20%; text-align: center;">Due Date</th>
						<th style="width: 20%; text-align: center;">Remaining Balance</th>
						<th style="width: 10%; text-align: center;">Status</th>
					</tr>
				</thead>
				<tbody>	
					<?php foreach ($invoice as $key => $value) { ?>
						<tr>
							<?php 
								$due = date('Y/m/d', strtotime($value['date_claimed']));
								$real_duedate = date('Y-m-d',strtotime($due . "+15 days"));
							 ?>
							<td class="first"><a href="javascript: void(0);" onclick="javascript: view_invoice_record(<?php echo $value['id'] ?>);"><?php echo $value['invoice_num'] ?></a></td>
							<td><?php echo $value['date_claimed'] != 0 ? $real_duedate : '0000-00-00' ; ?></td>
							<?php if($value['status'] == "Void") { ?>
							<td><?php echo $value['status'] ?></td>	
							<?php } else { ?>
							<td>P <?php echo number_format($value['remaining_balance'], 2, '.', ',') ?></td>
							<?php } ?>
							<td><?php echo $value['status'] ?></td>	
						</tr>
					<?php } ?>
					
				</tbody>
				
			</table>


			<h1>Stock History</h1>
			<div class="clear"></div>
		</hgroup>	
			<table id="stock_history_dt" class="datatable table">
				<thead>
					<tr>
						<th style="width: 20%; text-align: center;">Medicine Name</th>
						<th style="width: 20%; text-align: center;">Quantity</th>
						<th style="width: 20%; text-align: center;">Reason</th>
						<th style="width: 20%; text-align: center;">When</th>
					</tr>
				</thead>
				<tbody>	
					<?php foreach ($stocks as $key => $value) { ?>
						<tr>
							<td class="first"><?php echo $value['medicine_name'] ?></td>
							<td><?php echo $value['quantity'] ?></td>	
							<td><?php echo $value['reason'] ?></td>	
							<td><?php echo Tool::humanTiming(strtotime($value['created_at'])) ?> ago</td>	
						</tr>
					<?php } ?>
					
				</tbody>
				
			</table>
			<?php if(count($stocks) > 0){ ?>
			<button type="button" class="btn btn-default btn-sm" onclick="javascript: dlStockHistory();"><span class="glyphicon glyphicon-download-alt"></span> Export Stock History to Excel</button>
			<?php } ?>
		<div class="clear"></div>
		<section id="buttons">
			<button class="previous_button" onClick="javascript: view_regimen();">Previous</button>
			<img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png">
			<button class="next_button" onClick="javascript: view_return_list();">Next</button>
		</section>	
</section>

<script>
	function dlStockHistory(){
		var patient_id = "<?php echo $patient['id'] ?>";
		new PNotify({
	      title: "Export Stock History",
	      text: "You have successfully exported Stock History",
	      type: "info",
	    });
		window.open(base_url + "download/stock_history/" + patient_id,'_blank');
	}

</script>