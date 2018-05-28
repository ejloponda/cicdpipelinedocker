<script>
	 $(function() {
        $('#regimen_history_list_dt').dataTable( {
	        "bJQueryUI": true,
	        "sPaginationType": "full_numbers",
	        "bPaginate": false,
	        "bLengthChange": false,
	        "bFilter": true,
	        "bInfo": false,     
	        "bScrollCollapse": false
        });

      
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
			<h1>Returns History List</h1>
			<div class="clear"></div>
		</hgroup>	
			<table id="regimen_history_list_dt" class="datatable table">
				<thead>
					<tr>
						<th style="width: 20%; text-align: center;">Invoice ID</th>
						<th style="width: 20%; text-align: center;">Date of Return</th>
						<th style="width: 10%; text-align: center;">Status</th>
					</tr>
				</thead>
				<tbody>	
					<?php foreach ($returns as $key => $value) { ?>
						<tr>
							<td class="first"><a href="javascript: void(0);" onclick="javascript: view_returns_record(<?php echo $value['id'] ?>);"><?php echo $value['invoice_num'] ?></a></td>
							<td><?php echo $value['date_return'] ?></td>
							<td><?php echo $value['status'] ?></td>	
						</tr>
					<?php } ?>
					
				</tbody>
				
			</table>
			
		<div class="clear"></div>
		<section id="buttons">
			<button class="previous_button" onClick="javascript: view_invoice_list();">Previous</button>
			<img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png">
			<button class="next_button" onClick="javascript: view_upload_list();">Next</button>
		</section>	
</section>