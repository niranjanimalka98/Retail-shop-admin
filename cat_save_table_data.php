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
		
	$record = array_combine(array_map(function($el) use ($headers) {
    return $headers[$el];
	}, array_keys($record)), array_values($record));
	
	function generateRandomString($length = 6) {
    $characters = '1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
	}
	
	$record['ca2'] = generateRandomString(6);
	$v1 = $record['ca1'];
	
	if(!empty($v1)){
	$OK = "[{\"name\":\"ca1\",\"value\":\"".$v1."\"},{\"name\":\"ca2\",\"value\":\"".$record['ca2']."\"}]";
	
	$newPost = $database->getReference($dpath.'categories')->update(array($record['ca2']=>

		$OK)
            
        );
	
	}else{
		echo json_encode(
            array(
                'code' => 400,
                'message' => 'Empty',
            )
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
