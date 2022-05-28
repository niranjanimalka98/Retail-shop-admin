<?php
include_once('conn.php');

if(isset($_POST['json']))
{
    $json = $_POST['json'];
    $data = json_decode($json);
    $table_records = $data[0];
    $table_id      = $data[1];
    $records       = $data[2];
		
		$record_deco = json_decode($records);
		
		$headers = array();
		$record  = array();

		for($i = 0; $i < count($record_deco); $i++)
		{
			$headers[] = $record_deco[$i]->name;
			$record[]  = $record_deco[$i]->value;
		}
		
	$record = array_combine(array_map(function($el) use ($headers) {
    return $headers[$el];
	}, array_keys($record)), array_values($record));

	$v1 = $record['ca1'];
	$v2 = $table_records;
	
	if(!empty($v1)){
	$OK = "[{\"name\":\"ca1\",\"value\":\"".$v1."\"},{\"name\":\"ca2\",\"value\":\"".$v2."\"}]";
	
	$referencef = $database->getReference($dpath .'Category/C/'.$v1);
	$snapshotf  = $referencef->getSnapshot();
	$resultf = $snapshotf->getValue();
	
	if(empty($resultf)){
		$newPost = $database->getReference($dpath.'categories/'.$v2)->set( $OK );
	}else{
		echo json_encode(
            array(
                'code' => 400,
                'message' => 'Products exists',
            )
        );
	}
		
	}else{
		echo json_encode(
            array(
                'code' => 400,
                'message' => 'Empty',
            )
        );
	}
	
    if(!empty($newPost->getKey()))
    {
		/*$result=$database->getReference($dpath.'tables/' . $table_id.'/count')->set(
            $table_records
        );*/
		
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
