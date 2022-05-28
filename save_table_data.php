<?php
include_once('conn.php');

if(isset($_POST['json']))
{
    $json = $_POST['json'];
    $data = json_decode($json);
    $table_records = $data[0];
    $table_id      = $data[1];
    $records       = $data[2];
	$selected       = $data[3];
	
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
	//for($j = 0; $j < count($headers); $j++){
	$v1 = $record['p01'];
	$v3 = $record['p03'];
	$v4 = $record['p04'];
	$v5 = $record['p05'];
	
	//check for duplicate SKU
	$referencef = $database->getReference($dpath .'products/'.$v1);
	$snapshotf  = $referencef->getSnapshot();
	$resultf = $snapshotf->getValue();
	//SKU check finishes
	
	if(empty($v1)){
		echo json_encode(
            array(
                'code' => 300,
                'message' => 'No SKU Found',
            )
        );
	}else{
	if(!empty($resultf)){
		echo json_encode(
            array(
                'code' => 300,
                'message' => 'fail',
            )
        );
	}else{
		
		$OK = "[{\"name\":\"p01\",\"value\":\"".$v1."\"},{\"name\":\"p02\",\"value\":\"".$selected."\"},{\"name\":\"p03\",\"value\":\"".$v3."\"},{\"name\":\"p04\",\"value\":\"".$v4."\"},{\"name\":\"p05\",\"value\":\"".$v5."\"},{\"name\":\"p06\",\"value\":\"0\"},{\"name\":\"p07\",\"value\":\"0\"},{\"name\":\"p11\",\"value\":\"0\"},{\"name\":\"p09\",\"value\":\"0\"},{\"name\":\"p08\",\"value\":\"0\"},{\"name\":\"p10\",\"value\":\"0\"},{\"name\":\"p17\",\"value\":\"0\"}]";
	
	$record_deco = json_decode($OK);
		
		$headers = array();
		$record  = array();

		for($i = 0; $i < count($record_deco); $i++)
		{
			$headers[] = $record_deco[$i]->name;
			$record[]  = $record_deco[$i]->value;
		}
		
	$record1 = array_combine(array_map(function($el) use ($headers) {
    return $headers[$el];
	}, array_keys($record)), array_values($record));
	
		
		$newPost1 = $database->getReference($dpath.'Category/C/'.$selected)->update(
        [
		$v1 => $v3,
            ]
        );
		
		$result=$database->getReference($dpath.'products/'.$v1)->set(
            $OK
        ); 
		
		$newPost = $database->getReference($dpath.$table_id.'/Items')->update(array($v1=>
        
		$record1)
            
        );
	}
}

	
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
