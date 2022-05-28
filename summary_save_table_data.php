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
	
	$sum = "[{\"name\":\"s01\",\"value\":\"".$record['s01']."\"},{\"name\":\"s02\",\"value\":\"\"},{\"name\":\"s03\",\"value\":\"\"},{\"name\":\"s04\",\"value\":\"".$record['s04']."\"},{\"name\":\"s05\",\"value\":\"".$record['s05']."\"},{\"name\":\"s06\",\"value\":\"".$record['s06']."\"},{\"name\":\"s07\",\"value\":\"".$record['s07']."\"},{\"name\":\"s08\",\"value\":\"\"},{\"name\":\"s09\",\"value\":\"\"},{\"name\":\"s10\",\"value\":\"\"},{\"name\":\"s11\",\"value\":\"".$record['s11']."\"},{\"name\":\"s12\",\"value\":\"".$record['s12']."\"},{\"name\":\"s13\",\"value\":\"".$record['s13']."\"},{\"name\":\"s14\",\"value\":\"".$record['s14']."\"},{\"name\":\"s15\",\"value\":\"\"}]";
	
	$record_deco = json_decode($sum);
		
		$headerssum = array();
		$recordsum  = array();

		for($i = 0; $i < count($record_deco); $i++)
		{
			$headerssum[] = $record_deco[$i]->name;
			$recordsum[]  = $record_deco[$i]->value;
		}
	
	$recordsum = array_combine(array_map(function($elsum) use ($headerssum) {
    return $headerssum[$elsum];
	}, array_keys($recordsum)), array_values($recordsum));
	
	//for Web
		$result = $database->getReference($dpath.'summary')->update(array('s'=>
            $sum)
        );
	
	//for App
	$newPost = $database->getReference($dpath)->update(array('Summary'=>
		$recordsum)
       );
	
	
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
