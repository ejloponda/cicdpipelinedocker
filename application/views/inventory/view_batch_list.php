
<?php if($batches) { ?> 
<strong>List of Batch</strong>
<section class="clear"></section>
<ul>
	<?php foreach ($batches as $key => $value) { ?>
		<li><a href="javascript: void(0);" onclick="javascript: batch_details(<?php echo $value['id'] ?>)"><?php echo $value['batch_no'] ?></a></li>
	<?php } ?>
</ul> 
<?php } else { ?>
<h1>No Stocks Found!</h1>
<?php }  ?>
<button type="button" class="button01" style="margin-right:10px;" onclick="javascript: addBatch();">+ Add Batch</button>
