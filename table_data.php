<?php
include_once('conn.php');
if(isset($_POST['json']))
{
    $json        = $_POST['json'];
    $data        = json_decode($json);
    $table       = $data[0];
	$table_records = "0";
	
    if(!empty($table)){
        $defifnition = $data[1];
        $newPost     = $database->getReference($dpath.'tables/')->update(array($table =>
            [
                'name' => $table,
                'definition' => $defifnition,
                'count' => $table_records,
            ])
        );
        if(!empty($newPost->getKey()))
        {
            echo json_encode(
                array(
                    'code' => 200,
                    'message' => 'success',
                )
            );
        }
        else
        {
            echo json_encode(
                array(
                    'code' => 400,
                    'message' => 'fail',
                )
            );
        }
    }else{
        echo json_encode(
            array(
                'code' => 401,
                'message' => 'fail',
            )
        );
    }
}
?>
