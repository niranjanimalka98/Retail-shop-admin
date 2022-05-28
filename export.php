<?php
include_once('conn.php');
if(isset($_POST['id']))
{
    $table_id = $_GET['id'];
    $id       = $_POST['id'];
		
	$time = new DateTime('Asia/Colombo');
    $ctime = $time->format('d-m-Y-H-i-s');
	
	$currentm = substr($id,2);
	
	if($table_id == "Summary"){
		$link = "d/".$currentm;
	}elseif($table_id == "SummaryMonth"){
		$link = "m";
	}
	
	$referencez00 = $database->getReference($dpath .'Summary/'.$link.'/'.$id.'/00/z00');
	$snapshotz00  = $referencez00->getSnapshot();
	$resultz00 = $snapshotz00->getValue();
	
	
	$filename = str_replace("/","-",$resultz00)."-".str_replace("#","",$table_id).'-export('.$ctime.').json';
	if (!file_exists('export')) {
		mkdir('export', 0777, true);
	}
	$file_location = getcwd() . '/export/'.$filename;
	// Here using cUrl, making api request
	$curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => $database_path.$dpath."Summary/".$link.'/'.$id."/.json?format=export&auth=" .$database_secret,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "GET"
	));
	$response = curl_exec($curl);
	$json_pretty = json_encode(json_decode($response), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

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
                'message' => $filename,
            )
        );
		
	  if ($handle = opendir('.')) {
		while (false !== ($file = readdir($handle))) {
		  if ($file != "." && $file != "..") {
			$thelist .= '<li><a href="'.$file.'">'.$file.'</a></li>';
		  }
		}
		closedir($handle);
	  }
	} 
}
?>
