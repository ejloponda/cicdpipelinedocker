<?php if(count($notifications)){ ?>
<ul>
	<?php foreach ($notifications as $key => $value) { ?>
		<li>
			<b>TYPE:</b> <?php echo $value['type'] ?><br>
			<b>NAME:</b> <?php echo $value['name'] ?><br>
			<b>HOST:</b> <?php echo $value['host'] ?><br>
			<b>LOCATION:</b> <?php echo $value['location'] ?><br>
			<span><?php echo Tool::humanTiming(strtotime($value['date_created'])) . " ago" ?></span>
		</li>
	<?php } ?>
	<!-- <li>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
	<span>3 minutes ago</span></li>
	<li>test</li>
	<li>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
	<span>3 minutes ago</span></li>
	<li>test</li>
	<li>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
	<span>3 minutes ago</span></li>
	<li>test</li>
	<li>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
	<li>test</li>
	<li>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
	<li>test</li>
	<li>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
	<li>test</li>
	<li>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
	<li>test</li>
	<li>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
	<li>test</li>
	<li>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
	<li>test</li>
	<li>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
	<li>test</li>
	<li>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
	<li>test</li>
	<li>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
	<li>test</li>
	<li>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
	<li>test</li>
	<li>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
	<li>test</li> -->
</ul>
<?php } else { echo "No Notifications."; } ?>