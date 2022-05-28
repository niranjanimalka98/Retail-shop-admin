<?php
include_once('conn.php');
if(isset($_POST['id']))
{
    $table_id = $_GET['id'];
    $id = $_POST['id'];
		
	if($table_id == "Inventory"){
		
		$rcat = $database->getReference($dpath.$table_id.'/Items/'.$id.'/p17');
		$scat  = $rcat->getSnapshot();
		$result = $scat->getValue();
		
		if(empty($result) || $result == 0){
			
		$rcat2 = $database->getReference($dpath.$table_id.'/Items/'.$id.'/p02');
		$scat2  = $rcat2->getSnapshot();
		$result2 = $scat2->getValue();
		
		$rcat1 = $database->getReference($dpath.'Category/C/'.$result2.'/'.$id);
		$scat1  = $rcat1->getSnapshot();
		$result1 = $scat1->getValue();
		
		if(!empty($result1)){
			$deletedcat = $database->getReference($dpath.'Category/C/'.$result2.'/'.$id)->remove();
		}else{
			//TODO
		}
			
			$deletedItem = $database->getReference($dpath.$table_id.'/Items/'.$id)->remove();
			$deletedpro = $database->getReference($dpath.'products/'.$id)->remove();
			$newPost = unlink('uploads/'.$id.'.jpeg');

			echo json_encode(array('code' => 200,'message' => 'success',));
		}else{
			echo json_encode(array('code' => 400,'message' => 'fail',));
		}
		
	}elseif($table_id == "Customer"){
		
		$deleted1 = $database->getReference($dpath.$table_id.'/Person/'.$id)->remove();
		$deleted = $database->getReference($dpath.'customers/'.$id)->remove();
	
	}elseif($table_id == "SalesRep"){
		$deleted        = $database->getReference($dpath. $table_id . '/Person/' . $id)->remove();

	}elseif($table_id == "Zinfo"){
		$deleted        = $database->getReference($dpath. 'zinfo')->remove();

	}elseif($table_id == "GR"){
		$deleted        = $database->getReference($dpath.$table_id.'/'.$id)->remove();

	}elseif($table_id == "Discount"){
		$deleted        = $database->getReference($dpath.$table_id.'/'.$id)->remove();

	}elseif($table_id == "Route"){
		$deleted        = $database->getReference($dpath.$table_id.'/R/'.$id)->remove();

	}elseif($table_id == "Category"){
		
		$rcat = $database->getReference($dpath.'/categories/'.$id);
		$scat  = $rcat->getSnapshot();
		$result = $scat->getValue();
		$record_deco = json_decode($result);

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
		
		$rcat1 = $database->getReference($dpath.$table_id.'/C/'.$record1['ca1']);
		$scat1  = $rcat1->getSnapshot();
		$result1 = $scat1->getValue();
		
		if(empty($result1)){
			$deleted = $database->getReference($dpath.'categories/'.$id)->remove();
			echo json_encode(array('code' => 200,'message' => 'success',));
		}else{
			echo json_encode(array('code' => 400,'message' => 'fail',));
		}
		
	}elseif($table_id == "Invoice"){
		$result=$database->getReference($dpath.$table_id.'/Bill/'.$id)->remove();

	}elseif($table_id == "Return"){
		$result=$database->getReference($dpath.$table_id.'/R/'.$id)->remove();

	}
	
    
    /*if(!empty($deleted))
		
		
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
    }*/
}
?>
