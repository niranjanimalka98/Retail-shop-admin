<?php
include_once('conn.php');
header('Content-Type: application/json; charset=UTF-8');
if(isset($_POST['id']))
{
    $table_id = $_GET['id'];
    $id       = $_POST['id'];
		
	$time = new DateTime('Asia/Colombo');
    $ctime = $time->format('d-m-Y-H-i-s');
	$cmonth = $time->format('mY');
	
	if($id == "Inventory"){
		$link = "Items";
	}elseif($id == "Customer"){
		$link = "Person";
	}elseif($id == "Expense"){
		$link = "E";
	}elseif($id == "Invoice"){
		$link = "Bill";
	}elseif($id == "Return"){
		$link = "R";
	}
	
	if($id == "GR"){
		$link = "GR";
		$gronly = "";
	}else{
		$gronly = "/".$link;
	}
	
	$filename = $link."-".$id.'-'.'('.$ctime.').json';
	if (!file_exists('export')) {
		mkdir('export', 0777, true);
	}
	$file_location = getcwd() . '/export/'.$filename;
	// Here using cUrl, making api request
	$curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => $database_path.$dpath.$id."/".$cmonth.$gronly."/.json?format=export&auth=" .$database_secret,
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
