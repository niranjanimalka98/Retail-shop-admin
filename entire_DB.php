<?php
include_once('conn.php');
if(isset($_POST['id']))
{
    $table_id = $_GET['id'];
    $id       = $_POST['id'];
	
	$time = new DateTime('Asia/Colombo');
    $ctime = $time->format('d-m-Y-H-i-s');
	
	$filename = 'EntireDB_'.str_replace("#","",$id).'_('.$ctime.').json';
	if (!file_exists('export')) {
		mkdir('export', 0777, true);
	}
	$file_location = getcwd() . '/export/'.$filename;
	// Here using cUrl, making api request
	$curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => $database_path.$dpath. ".json?format=export&auth=" .$database_secret,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "GET"
	));
	$response = curl_exec($curl);
	$json_pretty = json_encode(json_decode($response), JSON_PRETTY_PRINT);

	$err = curl_error($curl);
	curl_close($curl);

	if ($err) {

	//error message
		//echo "cURL Error #:" . $err;
		echo json_encode(
            array(
                'code' => 400,
                'message' => $err,
            )
        );
		
	} else {
	  $backup_file = fopen($file_location, 'wb');
	  fwrite($backup_file, $json_pretty);
	  fclose($backup_file);

		//success
		//echo 'File \'' . $filename . '\' successfully has been created.';
		echo json_encode(
            array(
                'code' => 200,
                'message' => 'File \'' . $filename . '\' successfully has been created.',
            )
        );
		
	} 
}
?>
