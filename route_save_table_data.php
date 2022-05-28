<?php
include_once('conn.php');

if(isset($_POST['json']))
{
    $json = $_POST['json'];
    $data = json_decode($json);
    $table_records = $data[0];
    $table_id      = $data[1];
    $records       = $data[2];
	
	$recordsweb       = $data[2];
		
		$record_deco = json_decode($records);
		
		$headers = array();
		$record  = array();

		for($i = 0; $i < count($record_deco); $i++)
		{
			$headers[] = $record_deco[$i]->name;
			$record[]  = $record_deco[$i]->value;
		}
		
	$recordr = array_combine(array_map(function($el) use ($headers) {
    return $headers[$el];
	}, array_keys($record)), array_values($record));
	
	$num_padded = sprintf("%04d", $table_records);
	
	
	$OK = "[{\"name\":\"r1\",\"value\":\"".$recordr['r1']."\"},{\"name\":\"r2\",\"value\":\"".$num_padded."\"}]";
	
	$newPost = $database->getReference($dpath.'routes')->update(array($num_padded=>
		$OK)
        );
	
	$record_decop = json_decode($OK);
		
		$headersp = array();
		$recordp  = array();

		for($i = 0; $i < count($record_deco); $i++)
		{
			$headersp[] = $record_decop[$i]->name;
			$recordp[]  = $record_decop[$i]->value;
		}
		
	$recordp = array_combine(array_map(function($el) use ($headersp) {
    return $headersp[$el];
	}, array_keys($recordp)), array_values($recordp));

	
	$result = $database->getReference($dpath.$table_id."/R")->update(array($num_padded=>
            $recordp)
        );
	
	
    if(!empty($newPost->getKey()))
    {
		
        $result=$database->getReference($dpath.'tables/' . $table_id.'/count')->set(
            $table_records
        );

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
