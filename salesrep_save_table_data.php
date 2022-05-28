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
	$table_records_id = $data[4];
	$time = new DateTime('Asia/Colombo');
	
	$recordsweb       = $data[2];
		
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
	
	$v1 = $record['p03'];
	$v2 = $record['p13'];
	$v3 = preg_replace('/[^\p{L}\p{N} ]+/', '', $record['p12']);
	$v4 = $record['p14'];
	$v5 = $record['p15'];
	$d = $selected;
	
	$num_padded = sprintf("%03d", $table_records);
	
    $date = $time->format('d-m-Y H:i');
	
	$record['log'] = '['.$date.'] Account '.$v1.' Created. Balance:'.$v4.'\n';
	
	$v7 = $record['log'];
	
	$reference = $database->getReference($dpath.'Device/'.$d.'/de05');
	$snapshot  = $reference->getSnapshot();
	$de5 = $snapshot->getValue();
	
	if(empty($de5) || empty($d)){
		
		if(!empty($d)){
			//Device
			$device = $database->getReference($dpath.'Device/'.$d.'/')->update(
				[ 
					'de05' => strval($num_padded)
				]
			);
		}
		
	$OK = 
	"[{\"name\":\"key\",\"value\":\"".$num_padded."\"},{\"name\":\"p03\",\"value\":\"".$v1."\"},{\"name\":\"p12\",\"value\":\"".$v3."\"},{\"name\":\"de01\",\"value\":\"".$d."\"},{\"name\":\"p13\",\"value\":\"".$v2."\"},{\"name\":\"p15\",\"value\":\"".$v5."\"},{\"name\":\"p14\",\"value\":\"".$v4."\"},{\"name\":\"log\",\"value\":\"".$v7."\"}]";
	
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
	
	
	//for Web
	$result = $database->getReference($dpath.'salesreps/'.$num_padded)->set(
            $OK//$data[2]
        );
	
	//for App
	$newPost = $database->getReference($dpath.$table_id.'/Person')->update(array($num_padded=>
        //['web'=>$recordsweb,
		$record1)//'item'=>$record]
            
        );
		
	}else{
		echo json_encode(
            array(
                'code' => 400,
                'message' => 'fail',
            )
        );
	}
	
    if(!empty($newPost->getKey()))
    {
        $result=$database->getReference($dpath.'tables/' . $table_id.'/count')->set(
            $table_records
        );
		$result=$database->getReference($dpath.'tables/' . $table_id.'/id')->set(
            $table_records_id
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
