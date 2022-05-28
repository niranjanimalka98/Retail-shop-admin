<?php
include_once('conn.php');
if(isset($_POST['id']))
{
    $table_id = $_GET['id'];
    $id       = $_POST['id'];
	$path = 'export/'.$id;
	
	if (file_exists($path)){
    if(unlink($path)){
       //deleted
		echo json_encode(
            array(
                'code' => 200,
                'message' => 'File \'' . $id . '\' deleted successfully.',
            )
        );
    }
	}else{
		 //error
		echo json_encode(
            array(
                'code' => 400,
                'message' => 'File not found',
            )
        );
	}
}
?>
