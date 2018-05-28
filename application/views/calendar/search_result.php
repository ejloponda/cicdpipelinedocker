<?php if(count($results)){ ?>
<ul>
	<?php foreach ($results as $key => $value) { ?>	   
		<?php 
			$start      = new DateTime($value['start_db']);
            $start_date = $start->format('D, M jS,Y h:ia');
		?>
		<li class = "view_event" value ="<?php echo $value['id'] ?>">
			<b>Event Name:</b> <?php echo $value['title'] ?><br>
			<b>Event Date:</b> <?php echo $start_date ?><br>
		</li>
	<?php } ?>
</ul>
<?php } else { ?>
    <p class="not-found"><?php echo "No results found."; ?> </p>
<?php } ?>

<script>
	$(".view_event").click(function(){
	    var id = $(this).val();
	    
	    $.post(base_url+'calendar_management/view_event',{event_id:id}, function(o){
              $("#viewEventModal").html(o);
              $("#viewEventModal").modal('show');
          });    
	});
</script>