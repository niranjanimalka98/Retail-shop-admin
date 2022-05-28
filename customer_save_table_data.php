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
	
	$v1 = $record['p03'];// name
	$v2 = $route;// route
	$v3 = preg_replace('/[^\p{L}\p{N} ]+/', '', $record['p12']);//mobile
	$v4 = $record['p14'];//balance
	$v5 = $record['p15'];//address
	
	$num_padded = sprintf("%05d", $table_records);
	
	$time = new DateTime('Asia/Colombo');
    $date = $time->format('d-m-Y H:i');

	$record['log'] = '['.$date.'] Account '.$v1.' Created. Balance:'.$v4.'\n';
	
	$v7 = $record['log'];
	
	if(empty($v2)){
		echo json_encode(array('code' => 400,'message' => 'fail',));
		
	}elseif(empty($v1)){
			echo json_encode(array('code' => 5000,'message' => 'Customer name cannot be empty',));
		}
	elseif(empty($v3)){
			echo json_encode(array('code' => 5000,'message' => 'Mobile number cannot be empty',));
		}
		
	elseif(empty($v5)){
			echo json_encode(array('code' => 5000,'message' => 'Address cannot be empty',));
		}
		
	
	    else{
		
	$OK = 
	"[{\"name\":\"p03\",\"value\":\"".$v1."\"},{\"name\":\"p12\",\"value\":\"".$v3."\"},{\"name\":\"p13\",\"value\":\"".$v2."\"},{\"name\":\"p15\",\"value\":\"".$v5."\"},{\"name\":\"p14\",\"value\":\"".$v4."\"},{\"name\":\"key\",\"value\":\"".$num_padded."\"},{\"name\":\"log\",\"value\":\"".$v7."\"}]";
	
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
	
	//for App
	$newPost     = $database->getReference($dpath.$table_id.'/Person')->update(array($num_padded=>
        //['web'=>$recordsweb,
		$record1)//'item'=>$record]
            
        );
	}
	
	
    if(!empty($newPost->getKey()))
    {
        $result=$database->getReference($dpath.'tables/' . $table_id.'/count')->set(
            $table_records
        );
		//for Web
		$result=$database->getReference($dpath.'customers/'.$num_padded)->set(
            $OK//$data[2]
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
