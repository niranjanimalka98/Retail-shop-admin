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
	
	//for Web
		$result=$database->getReference($dpath.'Zinfo')->update(array('z'=>
            $data[2])//$data[2]
        );
	
	//for App
	$newPost = $database->getReference($dpath.'zinfo')->update(
        [
			'i01' => $record['i01'],
			'i02' => $record['i02'],
			//'i02t' => $record['i02t'],
			'i03' => $record['i03'],
			'i04' => $record['i04'],
			//'i04t' => $record['i04t'],
			'i05' => $record['i05'],
			'i06' => $record['i06'],
			'i07' => $record['i07']
		]
            
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
