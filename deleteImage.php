<?php
include_once('conn.php');

if(isset($_POST['json']))
{
    $json = $_POST['json'];
    $data = json_decode($json);
    $id = $data[0];
    $tid = $data[1];
	
	if($tid = "Inventory"){
		$newPost = unlink('uploads/'.$id.'.jpeg');
		echo json_encode(array('code' => 200,'message' => 'success',));
	}else{
		//TODO
		echo json_encode(array('code' => 400,'message' => 'fail',));
	}
}
?>
