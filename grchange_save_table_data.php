<?php
include_once('conn.php');

if(isset($_POST['json']))
{
    $json = $_POST['json'];
    $data = json_decode($json);
    $table_records = $data[0];
    $table_id      = "GR";
    $records       = $data[2];
	$grpro       = $data[3];
	
	$time = new DateTime('Asia/Colombo');
    $grdate = $time->format('d/m/Y H:i:s');
	$cdate = $time->format('dmY');
	$gdate = $time->format('d/m/Y');
	$cmonth = $time->format('mY');
	$gmonth = $time->format('m/Y');
	
	$grid = sprintf("%06d", $table_records);
	
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
	
	if($recordgr['p10'] != "0"){
		
		echo json_encode(
            array(
                'code' => 400,
                'message' => 'fail',
            )
        );
		
	}else{
		
	$GR = "[{\"name\":\"id\",\"value\":\"".$grid."\"},{\"name\":\"p01\",\"value\":\"".$grpro."\"},{\"name\":\"p16\",\"value\":\"".$grdate."\"},{\"name\":\"p10\",\"value\":\"".$recordgr['p10']."\"},{\"name\":\"p06\",\"value\":\"".$recordgr['p06']."\"},{\"name\":\"p07\",\"value\":\"".$recordgr['p07']."\"},{\"name\":\"p11\",\"value\":\"".$recordgr['p11']."\"},{\"name\":\"p09\",\"value\":\"".$recordgr['p09']."\"}]";
		
		//product selected
	$referencef = $database->getReference($dpath .'products/'.$grpro);
	$snapshotf  = $referencef->getSnapshot();
	$resultf = $snapshotf->getValue();
	
	$record_deco = json_decode($resultf);
		
		$headersf = array();
		$recordf  = array();

		for($i = 0; $i < count($record_deco); $i++)
		{
			$headersf[] = $record_deco[$i]->name;
			$recordf[]  = $record_deco[$i]->value;
		}
	
	$recordf = array_combine(array_map(function($el) use ($headersf) {
    return $headersf[$el];
	}, array_keys($recordf)), array_values($recordf));
		
	if($recordf['p08'] == 0){
			echo json_encode(
            array(
                'code' => 400,
                'message' => 'fail',
            )
        );
	}else{
	$selprice5 = $recordgr['p06'] / 100 * $recordf['p07'];
	$selprice4 = $recordgr['p06'] + number_format($selprice5,2);
	$selprice3 = $selprice4 / 100 * $recordf['p11'];
	$selprice2 = $selprice3 + $selprice4;
	$tsv = $selprice4 * $recordf['p10'];
		
			//cash
	$referenceca = $database->getReference($dpath .'zinfo/cash');
	$snapshotca  = $referenceca->getSnapshot();
	$resultca = $snapshotca->getValue();
		//cheque
	$referencech = $database->getReference($dpath .'zinfo/cheque');
	$snapshotch  = $referencech->getSnapshot();
	$resultch = $snapshotch->getValue();
		//credit
	$referencecr = $database->getReference($dpath .'zinfo/credit');
	$snapshotcr  = $referencecr->getSnapshot();
	$resultcr = $snapshotcr->getValue();
		
	//zinfo tsv
	$referencetsv = $database->getReference($dpath .'zinfo/tsv');
	$snapshottsv  = $referencetsv->getSnapshot();
	$resulttsv = $snapshottsv->getValue();
	
	//$p10v = $recordgr['p10'] + $recordf['p10'];
	//$p17v = $tsv + $recordf['p17'];
	$endtsv = 0;
	$diff = 0;
	$timec = "";
		
	if($recordf['p06'] < $recordgr['p06']){
		$diff = $recordgr['p06'] - $recordf['p06'];
		$pORm = "+";
		$endtsv = $resulttsv + ($diff * $recordf['p10']);
		$totalday1 = ($diff * $recordf['p10']) + $recordf['p17'];
		
	}elseif($recordf['p06'] > $recordgr['p06']){
		$diff = $recordf['p06'] - $recordgr['p06'];
		$pORm = "-";
		$endtsv = $resulttsv - ($diff * $recordf['p10']);
		$totalday1 = $recordf['p17'] - ($diff * $recordf['p10']);
	}
	
	//start month
	$referencem = $database->getReference($dpath .'Summary/m/'.$cmonth.'/00/z00');
	$snapshotm  = $referencem->getSnapshot();
	$resultm = $snapshotm->getValue();
	
		if(empty($resultm)){
			$timec = "month";
			$addcmonth = $database->getReference($dpath.'Summary/m/'.$cmonth.'/00')->set(
				[ 
					'z00' => strval($gmonth),
					'z01' => strval($resulttsv),
					'z02' => "0",
					'z03' => "0",
					'z04' => "0",
					'z05' => "0",
					'z06' => "0",
					'z07' => "0",
					'z08' => "0",
					'z09' => strval($resulttsv)
				]
        	);
			$addcmonth00 = $database->getReference($dpath.'Summary/m/'.$cmonth.'/01')->set(
				[ 
					'z00' => strval($resultca),
					'z01' => "0",
					'z02' => "0",
					'z03' => strval($resultca),
					'z04' => strval($resultch),
					'z05' => "0",
					'z06' => strval($resultch),
					'z07' => "0.00",
					'z08' => "0",
					'z09' => "0.00",
					'z10' => "0.00"
				]
        	);
			
			$addexpensem = $database->getReference($dpath.'Summary/m/'.$cmonth.'/e-expense')->set(
				[
					'z01' => "Expense",
					'z02' => "Expense",
					'z03' => "0.00"
				]
        	);
			
			$addcmonth = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/00')->set(
				[ 
					'z00' => strval($gdate),
					'z01' => strval($resulttsv),
					'z02' => "0",
					'z03' => "0",
					'z04' => "0",
					'z05' => "0",
					'z06' => "0",
					'z07' => "0",
					'z08' => "0",
					'z09' => strval($resulttsv)
				]
        	);
			$addcmonth00 = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/01')->set(
				[ 
					'z00' => strval($resultca),
					'z01' => "0",
					'z02' => "0",
					'z03' => strval($resultca),
					'z04' => strval($resultch),
					'z05' => "0",
					'z06' => strval($resultch),
					'z07' => "0.00",
					'z08' => "0",
					'z09' => "0.00",
					'z10' => "0.00"
				]
        	);
			
			$addexpense = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/e-expense')->set(
				[
					'z01' => "Expense",
					'z02' => "Expense",
					'z03' => "0.00"
				]
        	);
			
			$z08sumOKm = "[{\"name\":\"z00\",\"value\":\"".strval($gmonth)."\"},{\"name\":\"z01\",\"value\":\"".strval($resulttsv)."\"},{\"name\":\"z02\",\"value\":\"0.00\"},{\"name\":\"z03\",\"value\":\"0\"},{\"name\":\"z04\",\"value\":\"0\"},{\"name\":\"z05\",\"value\":\"0.00\"},{\"name\":\"z06\",\"value\":\"0\"},{\"name\":\"z07\",\"value\":\"0\"},{\"name\":\"z08\",\"value\":\"0\"},{\"name\":\"z09\",\"value\":\"".strval($resulttsv)."\"}]";
		
			$z08sumOK1m = $database->getReference($dpath.'summary/m')->update(
				[ $cmonth => $z08sumOKm ]
			);
			
			$z08sumOK = "[{\"name\":\"z00\",\"value\":\"".strval($gdate)."\"},{\"name\":\"z01\",\"value\":\"".strval($resulttsv)."\"},{\"name\":\"z02\",\"value\":\"0.00\"},{\"name\":\"z03\",\"value\":\"0\"},{\"name\":\"z04\",\"value\":\"0\"},{\"name\":\"z05\",\"value\":\"0.00\"},{\"name\":\"z06\",\"value\":\"0\"},{\"name\":\"z07\",\"value\":\"0\"},{\"name\":\"z08\",\"value\":\"0\"},{\"name\":\"z09\",\"value\":\"".strval($resulttsv)."\"}]";
		
			$z08sumOK1 = $database->getReference($dpath.'summary/d/'.$cmonth)->update(
				[ $cdate => $z08sumOK ]
			);
			
			$dayend = $database->getReference($dpath.'zinfo')->update(
				[ 
					'day' => strval($gdate)
				]
        	);
			$result2 = $database->getReference($dpath.'zinfo')->update(
            	[ 'newprice' => "0" ]
        	);
			
		}else{
			$referenced = $database->getReference($dpath .'Summary/d/'.$cmonth.'/'.$cdate.'/00/z00');
			$snapshotd  = $referenced->getSnapshot();
			$resultd = $snapshotd->getValue();
	
		if(empty($resultd)){
			$addcmonth = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/00')->set(
				[ 
					'z00' => strval($gdate),
					'z01' => strval($resulttsv),
					'z02' => "0",
					'z03' => "0",
					'z04' => "0",
					'z05' => "0",
					'z06' => "0",
					'z07' => "0",
					'z08' => "0",
					'z09' => strval($resulttsv)
				]
        	);
			
			$z08sumOK = "[{\"name\":\"z00\",\"value\":\"".strval($gdate)."\"},{\"name\":\"z01\",\"value\":\"".strval($resulttsv)."\"},{\"name\":\"z02\",\"value\":\"0.00\"},{\"name\":\"z03\",\"value\":\"0\"},{\"name\":\"z04\",\"value\":\"0\"},{\"name\":\"z05\",\"value\":\"0.00\"},{\"name\":\"z06\",\"value\":\"0\"},{\"name\":\"z07\",\"value\":\"0\"},{\"name\":\"z08\",\"value\":\"0\"},{\"name\":\"z09\",\"value\":\"".strval($resulttsv)."\"}]";
		
			$z08sumOK1 = $database->getReference($dpath.'summary/d')->update(
				[ $cdate => $z08sumOK ]
			);
			
			$addcday = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/01')->set(
				[ 
					'z00' => strval($resultca),
					'z01' => "0",
					'z02' => "0",
					'z03' => strval($resultca),
					'z04' => strval($resultch),
					'z05' => "0",
					'z06' => strval($resultch),
					'z07' => strval($resultcr),
					'z08' => "0",
					'z09' => strval($resultcr),
					'z10' => "0.00"
				]
        	);
			
			$addexpense = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/e-expense')->set(
				[
					'z01' => "Expense",
					'z02' => "Expense",
					'z03' => "0.00"
				]
        	);
			
			$dayend = $database->getReference($dpath.'zinfo')->update(
				[ 
					'day' => strval($gdate)
				]
        	);
		}
		}
	
		/*$referencedgrpro = $database->getReference($dpath .'Summary/d/'.$cmonth.'/'.$cdate.'/'.$grpro);
		$snapshotdgrpro  = $referencedgrpro->getSnapshot();
		$resultdgrpro = $snapshotdgrpro->getValue();
		
		if(empty($resultdgrpro)){
	//add products to summary
	$reference = $database->getReference($dpath .'products');
	$snapshot  = $reference->getSnapshot();
	$resultp = $snapshot->getValue();
	$table_result_val= [] ;
	$table_result_key= [] ;

	if(!empty($resultp))
	{
		$table_result_val = array_values($resultp);
		$table_result_key = array_keys($resultp);
	}
	
	//$records = array();
	foreach($table_result_val as $key => $record)
	{
		$record_deco = json_decode($record);
		$headers = array();
		$record  = array();

		for($i = 0; $i < count($record_deco); $i++)
		{
			$headers[] = $record_deco[$i]->name;
			$record[]  = $record_deco[$i]->value;
		}
	//$records[] = $record;
	$record = array_combine(array_map(function($el) use ($headers) {
    return $headers[$el];
	}, array_keys($record)), array_values($record));
		
	$referencez01 = $database->getReference($dpath .'Summary/d/'.$cmonth.'/'.$cdate.'/'.$record['p01'].'/z01');
	$snapshotz01  = $referencez01->getSnapshot();
	$resultz01 = $snapshotz01->getValue();
		

		if(empty($resultz01)){
			$selpricegr5new = $record['p06'] / 100 * $record['p07'];
			$selpricegr4new = $record['p06'] + number_format($selpricegr5new,2);

			if($timec = "month"){
				$addtosum = $database->getReference($dpath.'Summary/m/'.$cmonth.'/'.$record['p01'])->update(
					[ 
						'z00' => strval($selpricegr4new),
						'z01' => strval($record['p03']),
						'z06' => strval($record['p10']),
						'z07' => strval($record['p10']),
						'z08' => strval($record['p17']),
						'z09' => strval($record['p03t'])
					]
				);
				$addtosumm = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/'.$record['p01'])->update(
					[ 
						'z00' => strval($selpricegr4new),
						'z01' => strval($record['p03']),
						'z06' => strval($record['p10']),
						'z07' => strval($record['p10']),
						'z08' => strval($record['p17']),
						'z09' => strval($record['p03t'])
					]
				);
			}else{
			$addtosumm = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/'.$record['p01'])->update(
					[ 
						'z00' => strval($selpricegr4new),
						'z01' => strval($record['p03']),
						'z06' => strval($record['p10']),
						'z07' => strval($record['p10']),
						'z08' => strval($record['p17']),
						'z09' => strval($record['p03t'])
					]
				);
		}
		}
	}
	}*/
		
		//product selected
	$referencesel = $database->getReference($dpath.'Inventory/Items/'.$grpro.'/p17');
	$snapshotsel  = $referencesel->getSnapshot();
	$resultsel = $snapshotsel->getValue();
	
	if($resultsel == 0){
		
		echo json_encode(
            array(
                'code' => 400,
                'message' => 'fail',
            )
        );

	}else{
		
		if($recordgr['p06'] == $recordf['p06']){
			
			echo json_encode(
            array(
                'code' => 400,
                'message' => 'fail',
            )
        );
			
		}else{
		
		$result1 = $database->getReference($dpath.$table_id)->update(array($grid=>
            $GR)
        );
			
	$reference00 = $database->getReference($dpath.'zinfo/newprice');
	$snapshot00  = $reference00->getSnapshot();
	$result00 = $snapshot00->getValue();
			
			
			$result00 = sprintf("%03d", $result00 + 1);
			
			$rz02 = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/'.$grpro.'/z02');
			$sz02  = $rz02->getSnapshot();
			$z02 = $sz02->getValue();
			
			$rz04 = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/'.$grpro.'/z04');
			$sz04  = $rz04->getSnapshot();
			$z04 = $sz04->getValue();
			
			$rz06 = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/'.$grpro.'/z06');
			$sz06  = $rz06->getSnapshot();
			$z06 = $sz06->getValue();
			
			$rz10 = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/'.$grpro.'/z10');
			$sz10  = $rz10->getSnapshot();
			$z10 = $sz10->getValue();
			
			$rz10t = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/'.$grpro.'/z10t');
			$sz10t  = $rz10t->getSnapshot();
			$z10t = $sz10t->getValue();
			
			$rz11 = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/'.$grpro.'/z11');
			$sz11  = $rz11->getSnapshot();
			$z11 = $sz11->getValue();
			
			$rz12 = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/'.$grpro.'/z12');
			$sz12  = $rz12->getSnapshot();
			$z12 = $sz12->getValue();
			
			$grprom = substr($grpro, -1);
			if($grprom == "0"){
				$g == "0";
			}else{
				$g = $grprom - 1;
			}
			$grprom = substr($grpro, 0, -1).$g;
			
			$grsum = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/'.$grprom.$result00)->update(
				[
					'z00' => strval($recordf['p08']),
					'z01' => strval($recordf['p03']),
					'z02' => strval($z02),
					'z03' => strval($z02 * $recordf['p08']),
					'z04' => strval($z04),
					'z05' => strval($z04 * $recordf['p08']),
					'z06' => strval($z06),
					'z07' => strval("0"),
					'z08' => strval("0.00"),
					'z10' => strval($z10),
					'z10t' => strval($z10t),
					'z11' => strval($z11),
					'z12' => strval($z12)
				]
			);

			$grsum = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/'.$grpro)->update(
				[
					'z00' => strval($selprice2),
					//'z01' => strval($z01[0]),
					'z02' => strval("0"),
					'z03' => strval("0.00"),
					'z04' => strval("0"),
					'z05' => strval("0.00"),
					'z06' => strval($recordf['p10']),
					'z08' => strval($totalday1),
					'z10' => strval($grid."(".$pORm.$diff.")x".$recordf['p10'].'->'.'('.$pORm.($diff * $recordf['p10']).')'),
					'z10t' => strval($pORm.($diff * $recordf['p10']))
				]
			);
			
			$deleted = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/'.$grpro.'/z11')->remove();
			$deleted = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/'.$grpro.'/z12')->remove();
			
		
			$result22 = $database->getReference($dpath.'zinfo')->update(
            	[ 'newprice' => $result00 ]
        	);
				
		$reference      = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/00/z08');
		$snapshot       = $reference->getSnapshot();
		$result         = $snapshot->getValue();
		
		if($recordf['p06'] < $recordgr['p06']){
			$totalday = ($diff * $recordf['p10']) + $result;
			//$totalend = ($diff * $recordf['p10']) + $endtsv;
			
		}elseif($recordf['p06'] > $recordgr['p06']){
			$totalday = $result - ($diff * $recordf['p10']);
			//$totalend = $endtsv - ($diff * $recordf['p10']);
		}
		
		$z08sum = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/00')->update(
            [ 
				'z08' => strval($totalday),
				'z09' => strval($endtsv)
			]
        );

		$referencem      = $database->getReference($dpath.'Summary/m/'.$cmonth.'/00/z08');
		$snapshotm       = $referencem->getSnapshot();
		$resultm         = $snapshotm->getValue();
		
		if($recordf['p06'] < $recordgr['p06']){
			$totalm = ($diff * $recordf['p10']) + $resultm;
			//$totalendm = ($diff * $recordf['p10']) + $endtsv;
			
		}elseif($recordf['p06'] > $recordgr['p06']){
			$totalm = $resultm - ($diff * $recordf['p10']);
			//$totalendm = $endtsv - ($diff * $recordf['p10']);
		}
		
		$z08summ = $database->getReference($dpath.'Summary/m/'.$cmonth.'/00')->update(
            [ 
				'z08' => strval($totalm),
				'z09' => strval($endtsv)
			]
        );
		
		$dayendtsv = $database->getReference($dpath.'zinfo')->update(
            [ 
				'tsv' => strval($endtsv)
			]
        );
		
		$OK = "[{\"name\":\"p01\",\"value\":\"".$recordf['p01']."\"},{\"name\":\"p02\",\"value\":\"".$recordf['p02']."\"},{\"name\":\"p03\",\"value\":\"".$recordf['p03']."\"},{\"name\":\"p04\",\"value\":\"".$recordf['p04']."\"},{\"name\":\"p05\",\"value\":\"".$recordf['p05']."\"},{\"name\":\"p06\",\"value\":\"".$selprice4."\"},{\"name\":\"p07\",\"value\":\"".$recordf['p07']."\"},{\"name\":\"p11\",\"value\":\"".$recordf['p11']."\"},{\"name\":\"p09\",\"value\":\"".$recordf['p09']."\"},{\"name\":\"p08\",\"value\":\"".$selprice2."\"},{\"name\":\"p10\",\"value\":\"".$recordf['p10']."\"},{\"name\":\"p17\",\"value\":\"".$totalday1."\"}]";
		
		$resultpro = $database->getReference($dpath.'products')->update(
            [
				$grpro => $OK
			]
        );
		
		$newPost = $database->getReference($dpath.'Inventory/Items/'.$grpro)->update(
			[ 
				'p17' => strval($totalday1),
				'p06' => strval($selprice4),
				'p08' => strval($selprice2)
			]
		);
		
		}
	}//end
	}
	}

    if(!empty($newPost->getKey()))
    {
        $result21 = $database->getReference($dpath.'zinfo')->update(
            [ 'us' => "1" ]
        );
		
		$result23=$database->getReference($dpath.'tables/' . $table_id.'/count')->set(
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
