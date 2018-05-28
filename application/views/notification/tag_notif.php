<?php if(count($notifications)){ ?>
<ul>
	<?php foreach ($notifications as $key => $value) { ?>
		<li>
			<b>ALERT MESSAGE:</b> <?php echo $value['message'] ?><br>
			<b>HOST:</b> <?php echo $value['host'] ?><br>
			<span><?php echo Tool::humanTiming(strtotime($value['date_created'])) . " ago" ?></span>
		</li>
	<?php } ?>
</ul>
<?php } else { echo "No Notifications."; } ?>