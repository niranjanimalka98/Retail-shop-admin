<?php
include_once('conn.php');

if(isset($_POST['json']))
{
    $json = $_POST['json'];
    $data = json_decode($json);
    $table_records = $data[0];
    $table_id      = $data[1];
    $records       = $data[2];
	$route       = $data[3];
	
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
	$v2 = $route;
	$v3 = preg_replace('/[^\p{L}\p{N} ]+/', '', $record['p12']);
	$v4 = $record['p14'];
	$v5 = $record['p15'];
	
	$record['key'] = $table_records;
	
	$time = new DateTime('Asia/Colombo');
    $date = $time->format('d-m-Y H:i');
	
	$record['log'] = '['.$date.'] Account '.$v1.' Updateded. Balance:'.$v4.'\n';
	
	$v6 = $record['key'];
	$v7 = $record['log'];
	
	if(empty($v2)){
		echo json_encode(array('code' => 400,'message' => 'fail',));
		
	}else{
		
	$OK = 
	"[{\"name\":\"p03\",\"value\":\"".$v1."\"},{\"name\":\"p12\",\"value\":\"".$v3."\"},{\"name\":\"p13\",\"value\":\"".$v2."\"},{\"name\":\"p15\",\"value\":\"".$v5."\"},{\"name\":\"p14\",\"value\":\"".$v4."\"},{\"name\":\"key\",\"value\":\"".$v6."\"},{\"name\":\"log\",\"value\":\"".$v7."\"}]";
	
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
	

	$newPost = $database->getReference($dpath.$table_id.'/Person')->update(array($v6=>
        //['web'=>$recordsweb,
		$record1)//'item'=>$record]
            
        );
	}
	
    if(!empty($newPost->getKey()))
    {
		$result=$database->getReference($dpath.'customers/'.$v6)->set(
            $OK
        );
		
		$result=$database->getReference($dpath.'Log')->set(
            [
			'log'=>$v7
			]
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
