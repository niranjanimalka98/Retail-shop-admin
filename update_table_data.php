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
	
	//from product update page
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
	
	//product selected
	$referencef = $database->getReference($dpath .'products/'.$v1);
	$snapshotf  = $referencef->getSnapshot();
	$resultf = $snapshotf->getValue();
	
	$record_deco = json_decode($resultf);
		
		$headersf = array();
		$recordf  = array();

		for($i = 0; $i < count($record_deco); $i++)
		{
			$headersf[] = $record_deco[$i]->name;
			$recordf[]  = $record_deco[$i]->value;
		}
	
	$recordf = array_combine(array_map(function($el) use ($headersf) {
    return $headersf[$el];
	}, array_keys($recordf)), array_values($recordf));
	
	
	$OK = "";
	$final = "";
	if($table_records == $v1){
		
		$final = $table_records;
		
		$OK = "[{\"name\":\"p01\",\"value\":\"".$final."\"},{\"name\":\"p02\",\"value\":\"".$selected."\"},{\"name\":\"p03\",\"value\":\"".$v3."\"},{\"name\":\"p04\",\"value\":\"".$v4."\"},{\"name\":\"p05\",\"value\":\"".$v5."\"},{\"name\":\"p06\",\"value\":\"".$recordf['p06']."\"},{\"name\":\"p07\",\"value\":\"".$recordf['p07']."\"},{\"name\":\"p11\",\"value\":\"".$recordf['p11']."\"},{\"name\":\"p09\",\"value\":\"".$recordf['p09']."\"},{\"name\":\"p08\",\"value\":\"".$recordf['p08']."\"},{\"name\":\"p10\",\"value\":\"".$recordf['p10']."\"},{\"name\":\"p17\",\"value\":\"".$recordf['p17']."\"}]";
	
	} else if(empty($resultf)){
		
		$final = $v1;
		
		$OK = "[{\"name\":\"p01\",\"value\":\"".$v1."\"},{\"name\":\"p02\",\"value\":\"".$selected."\"},{\"name\":\"p03\",\"value\":\"".$v3."\"},{\"name\":\"p04\",\"value\":\"".$v4."\"},{\"name\":\"p05\",\"value\":\"".$v5."\"},{\"name\":\"p06\",\"value\":\"0\"},{\"name\":\"p07\",\"value\":\"0\"},{\"name\":\"p11\",\"value\":\"0\"},{\"name\":\"p09\",\"value\":\"0\"},{\"name\":\"p08\",\"value\":\"0\"},{\"name\":\"p10\",\"value\":\"0\"},{\"name\":\"p17\",\"value\":\"0\"}]";
		
	} else {
		
		echo json_encode(
            array(
                'code' => 300,
                'message' => 'duplicate',
            )
        );
	}
	
	
	//save record
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
	
	
	if($recordf['p02'] != $selected){
		
		$newPost0 = $database->getReference($dpath.'/Category/C/'.$recordf['p02'].'/'.$v1)->remove();
		
		$newPost1 = $database->getReference($dpath.'/Category/C/'.$selected)->update(
        [
		$v1 => $v3,
            ]
       );
	}else{
		//TODO
	}
	
	$newPost     = $database->getReference($dpath.$table_id.'/Items')->update(array($final=>

		$record1)
            
        );
	
    if(!empty($newPost->getKey()))
    {
		
		
		if($table_records == $v1){
			
			$result=$database->getReference($dpath.'products/'.$final)->set(
            $OK
        );
		echo json_encode(
			array(
				'code' => 200,
				'message' => 'success',
			)
		);
			
		} else {
			
		$result=$database->getReference($dpath.'products/')->update(array($final=>
            $OK)
        );
			echo json_encode(
			array(
				'code' => 100,
				'message' => 'new',
			)
		);
		}
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
