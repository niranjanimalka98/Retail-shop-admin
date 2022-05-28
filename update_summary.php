<?php
include_once('conn.php');

if(isset($_POST['id']))
{
    $tid = $_GET['tid'];
    $id       = $_POST['id'];
	
	if($tid = "SummaryMonth"){
		$idsub = 'm/'.$id;
	}elseif($tid = "Summary"){
		$idsub = 'd/'.substr($id,2).'/'.$id;
	}
	
	//day web summary
	$referencew = $database->getReference($dpath .'Summary/'.$idsub);
	$snapshotw  = $referencew->getSnapshot();
	$resultw = $snapshotw->getValue();
	$values_records = [];
	$key_records = [];
	
	$values_records = array_values($resultw);
    $key_records    = array_keys($resultw);
	
	$run = 0;
	
	foreach($values_records as $key => $record){
		
		if($run == 0){
		$z00 = $record['z00'];
		$z01 = $record['z01'];
		$z02 = $record['z02'];
		$z03 = $record['z03'];
		$z04 = $record['z04'];
		$z05 = $record['z05'];
		$z06 = $record['z06'];
		$z07 = $record['z07'];
		$z08 = $record['z08'];
		$z09 = $record['z09'];
	}
		$run++;
	}
	
	$summary = "[{\"name\":\"z00\",\"value\":\"".$z00."\"},{\"name\":\"z01\",\"value\":\"".$z01."\"},{\"name\":\"z02\",\"value\":\"".$z02."\"},{\"name\":\"z03\",\"value\":\"".$z03."\"},{\"name\":\"z04\",\"value\":\"".$z04."\"},{\"name\":\"z05\",\"value\":\"".$z05."\"},{\"name\":\"z06\",\"value\":\"".$z06."\"},{\"name\":\"z07\",\"value\":\"".$z07."\"},{\"name\":\"z08\",\"value\":\"".$z08."\"},{\"name\":\"z09\",\"value\":\"".$z09."\"}]";
		
	
	if($tid = "SummaryMonth"){
		$idsubs = 'm';
	}elseif($tid = "Summary"){
		$idsubs = 'd/'.substr($id,2);
	}
	
		$newPost = $database->getReference($dpath.'summary/'.$idsubs)->update(
            [ $id => $summary ]
        );
	
	/*$month = substr($id,2);
	
	//month web summary
	$referencem = $database->getReference($dpath .'Summary/m/'.$month);
	$snapshotm  = $referencem->getSnapshot();
	$resultm = $snapshotm->getValue();
	$values_recordsm = [];
	$key_recordsm = [];
	
	$values_recordsm = array_values($resultm);
    $key_recordsm    = array_keys($resultm);
	
	$runm = 0;
	
	foreach($values_recordsm as $keym => $recordm){
		
		if($runm == 0){
		$z00m = $recordm['z00'];
		$z01m = $recordm['z01'];
		$z02m = $recordm['z02'];
		$z03m = $recordm['z03'];
		$z04m = $recordm['z04'];
		$z05m = $recordm['z05'];
		$z06m = $recordm['z06'];
		$z07m = $recordm['z07'];
		$z08m = $recordm['z08'];
		$z09m = $recordm['z09'];
	}
		$runm++;
	}
	
	$summarym = "[{\"name\":\"z00\",\"value\":\"".$z00m."\"},{\"name\":\"z01\",\"value\":\"".$z01m."\"},{\"name\":\"z02\",\"value\":\"".$z02m."\"},{\"name\":\"z03\",\"value\":\"".$z03m."\"},{\"name\":\"z04\",\"value\":\"".$z04m."\"},{\"name\":\"z05\",\"value\":\"".$z05m."\"},{\"name\":\"z06\",\"value\":\"".$z06m."\"},{\"name\":\"z07\",\"value\":\"".$z07m."\"},{\"name\":\"z08\",\"value\":\"".$z08m."\"},{\"name\":\"z09\",\"value\":\"".$z09m."\"}]";
		
		$newPost = $database->getReference($dpath.'summary/m')->update(
            [ $month => $summarym ]
        );*/
	
    if(!empty($newPost->getKey()))
    {		
		$new = $database->getReference($dpath.'zinfo')->update(
           [ 'us' => "0" ]
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
