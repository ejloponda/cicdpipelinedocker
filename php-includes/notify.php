<?php
require_once('lib/squeeks-Pusher-PHP/lib/Pusher.php');
require_once('config.php');



$pusher = new Pusher(APP_KEY, APP_SECRET, APP_ID);

$message = sanitize( $_GET['message'] );
$title = sanitize( $_GET['title'] );
$type = sanitize( $_GET['type'] );
$data = array(
	'message' => $message, 
	'title' => $title, 
	'type' => $type);

$pusher->trigger('my_notifications', 'notification', $data);

function sanitize($data) {
  return htmlspecialchars($data);
}
?>