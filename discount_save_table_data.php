<?php
include_once('conn.php');

if(isset($_POST['json']))
{
    $json = $_POST['json'];
    $data = json_decode($json);
    $table_records = $data[0];
    $table_id      = $data[1];
    $records       = $data[2];
	$grpro       = $data[3];
	
	$time = new DateTime('Asia/Colombo');
    $grdate = $time->format('d/m/Y H:i:s'); //$time->format('d/m/Y H:i:s');//"30/06/2020 08:00:00";//
	$cdate = $time->format('dmY'); //$time->format('dmY');//"30062020";//
	$gdate = $time->format('d/m/Y'); //$time->format('d/m/Y');//"30/06/2020";//
	$cmonth = $time->format('mY'); //$time->format('mY');//"062020";//
	$gmonth = $time->format('m/Y'); //$time->format('m/Y');//"06/2020";//
	
	//$grid = sprintf("%06d", $table_records);
	
	//GR details
	$record_deco = json_decode($records);
		
		$headersgr = array();
		$recordgr  = array();

		for($i = 0; $i < count($record_deco); $i++)
		{
			$headersgr[] = $record_deco[$i]->name;
			$recordgr[]  = $record_deco[$i]->value;
		}
	
	$recordgr = array_combine(array_map(function($elgr) use ($headersgr) {
    return $headersgr[$elgr];
	}, array_keys($recordgr)), array_values($recordgr));
	
	
	if($recordgr['d02'] == "" || $recordgr['d03'] == ""){
		
		echo json_encode(
            array(
                'code' => 5000,
                'message' => 'Quantity or Discount cannot be blank',
            )
        );
		
	}else{
		
	$GR = "[{\"name\":\"p01\",\"value\":\"".$grpro."\"},{\"name\":\"d01\",\"value\":\"".$recordgr['d01']."\"},{\"name\":\"d02\",\"value\":\"".$recordgr['d02']."\"},{\"name\":\"d03\",\"value\":\"".$recordgr['d03']."\"}]";
		
		//save discount
		$result1 = $database->getReference($dpath.$table_id)->update(array($grpro=>
            $GR));
		
		//add to inventory
		$newPost = $database->getReference($dpath.'Inventory/Items/'.$grpro)->update(
            [ 
				'p21' => strval($recordgr['d01']."|".$recordgr['d02']."|".$recordgr['d03'])
			]
        );		
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
