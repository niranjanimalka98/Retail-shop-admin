<?php
include_once('conn.php');

if (isset($_GET['lang'])) {
		
	$lang = $_GET['lang'];
	
	$newPost = $database->getReference($dpath.'zinfo')->update(array("lan"=>$lang));
	
	$url = $_SERVER['HTTP_REFERER']; // right back to the referrer page from where you came.
	echo '<meta http-equiv="refresh" content="0;URL=' . $url . '">';
}
?>
