<section class="area" style="padding-bottom:0px !important">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER ?>themes/images/header-activity.png"></li>
			<li><h1>Activity Log</h1></li>
		</ul>
		<div class="clear"></div>
	</hgroup>
	<div class="li-holder">
		<ul class="notification">
			<?php foreach($logs as $key=>$value): ?>
				<li>
					<div class="notif-message "><?php echo $value['entity_id']; ?></div>
					<div class="notif-message "><?php echo $value['message_log']; ?></div>
					<div class="notif-timestamp "><?php echo Activity_Log::getHumanTimeDifference($value['date_created']); ?></div>
					<div class="clear-noheight"></div>
				</li>
			<?php endforeach; ?>
			</div>
		</ul>
	</div>
</section>