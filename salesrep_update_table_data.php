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
	$time = new DateTime('Asia/Colombo');
	
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
	
	$record['key'] = $table_records;
	
	$date = $time->format('d-m-Y H:i');
	$record['log'] = '['.$date.'] Account Updateded. Balance:'.$v4.'\n';
	
	$v6 = $record['key'];
	$v7 = $record['log'];
	
	$reference = $database->getReference($dpath.'Device/'.$d.'/de05');
	$snapshot  = $reference->getSnapshot();
	$de5 = $snapshot->getValue();
	
	$reference1 = $database->getReference($dpath.'SalesRep/Person/'.$v6.'/de01');
	$snapshot1  = $reference1->getSnapshot();
	$de1 = $snapshot1->getValue();
	
	$reference2 = $database->getReference($dpath.'Device/'.$de1.'/de01');
	$snapshot2  = $reference2->getSnapshot();
	$ode1 = $snapshot2->getValue();
	
	
	if(empty($d) || $de5 == $v6 || empty($de5) && empty($de1)){
	$OK = 
	"[{\"name\":\"key\",\"value\":\"".$v6."\"},{\"name\":\"p03\",\"value\":\"".$v1."\"},{\"name\":\"p12\",\"value\":\"".$v3."\"},{\"name\":\"de01\",\"value\":\"".$d."\"},{\"name\":\"p13\",\"value\":\"".$v2."\"},{\"name\":\"p14\",\"value\":\"".$v5."\"},{\"name\":\"p15\",\"value\":\"".$v4."\"},{\"name\":\"log\",\"value\":\"".$v7."\"}]";
	
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
	
	if(!empty($d)){
		//Device
		$device = $database->getReference($dpath.'Device/'.$d)->update(
			[ 
				'de05' => strval($v6)
			]
		);
	}
	$result=$database->getReference($dpath.'salesreps/'.$v6)->set(
            $OK
        );
		
	
	$newPost = $database->getReference($dpath.$table_id.'/Person')->update(array($v6=>
        //['web'=>$recordsweb,
		$record1)//'item'=>$record] 
        );
		
	}elseif($de5 != $v1){
		echo json_encode(
            array(
                'code' => 400,
                'message' => 'fail',
            )
        );
	}elseif($de1 != $ode1){
		echo json_encode(
            array(
                'code' => 400,
                'message' => 'fail',
            )
        );
	}
	
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
}
?>
