<?php
include_once('conn.php');
if(isset($_POST['json']))
{
    $json = $_POST['json'];
    $data = json_decode($json);
    $id          = $data[0];
    $table       = $data[1];
    $defifnition = $data[2];
	$table_records = $data[3];
	
    $result=$database->getReference($dpath.'tables/'.$id)->set(
        [
            'name' => $table,
            'definition' => $defifnition,
            'count' => $table_records,
        ]
    );

    if(!empty($result))
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
}
?>
