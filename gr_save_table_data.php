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
	
	
	if($recordgr['p10'] == "0"){
		
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
		
	$selprice5 = $recordf['p06'] / 100 * $recordf['p07'];
	$selprice4 = $recordf['p06'] + number_format($selprice5,2);
	$selprice3 = $selprice4 / 100 * $recordf['p11'];
	$selprice2 = $selprice3 + $selprice4;
	$tsv = $selprice4 * $recordgr['p10'];
		
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
		
	$endtsv = $resulttsv + $tsv;
	
	$p10v = $recordgr['p10'] + $recordf['p10'];
	$p17v = $tsv + $recordf['p17'];
	$timec = "";
	
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
			
			$newp = $database->getReference($dpath.'zinfo')->update(
				[ 
					'newprice' => strval("0")
				]
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
			
			$addexpense = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/e-expense')->set(
				[
					'z01' => "Expense",
					'z02' => "Expense",
					'z03' => "0.00"
				]
        	);
			
			$z08sumOK = "[{\"name\":\"z00\",\"value\":\"".strval($gdate)."\"},{\"name\":\"z01\",\"value\":\"".strval($resulttsv)."\"},{\"name\":\"z02\",\"value\":\"0.00\"},{\"name\":\"z03\",\"value\":\"0\"},{\"name\":\"z04\",\"value\":\"0\"},{\"name\":\"z05\",\"value\":\"0.00\"},{\"name\":\"z06\",\"value\":\"0\"},{\"name\":\"z07\",\"value\":\"0\"},{\"name\":\"z08\",\"value\":\"0\"},{\"name\":\"z09\",\"value\":\"".strval($resulttsv)."\"}]";
		
			$z08sumOK1 = $database->getReference($dpath.'summary/d/'.$cmonth)->update(
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
	$result = $snapshot->getValue();
	$table_result_val= [] ;
	$table_result_key= [] ;

	if(!empty($result))
	{
		$table_result_val = array_values($result);
		$table_result_key = array_keys($result);
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
			$selpricegr5new = $record['p06']/100*$record['p07'];
			$selpricegr4new = $record['p06']+ number_format($selpricegr5new,2);

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
		
	$selpricegr5 = $recordgr['p06'] / 100 * $recordgr['p07'];
	$selpricegr4 = $recordgr['p06'] + number_format($selpricegr5,2);
	$selpricegr3 = $selpricegr4 / 100 * $recordgr['p11'];
	$selpricegr2 = $selpricegr3 + $selpricegr4;
	$tsvgr = $selpricegr4 * $recordgr['p10'];
	$tsvgr1 = $selpricegr4 * $p10v;
	
	if($selprice2 == 0 || $selpricegr2 = $selprice2){

	//product selected
	$referencesel = $database->getReference($dpath.'Inventory/Items/'.$grpro.'/p17');
	$snapshotsel  = $referencesel->getSnapshot();
	$resultsel = $snapshotsel->getValue();
	
	if($resultsel == 0){
		
		$endtsvgr = $resulttsv + $tsvgr;
		//add gr save
		$reference      = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/00/z08');
		$snapshot       = $reference->getSnapshot();
		$result         = $snapshot->getValue();
		$totalday = $tsvgr + $result;
		
		$z08sum = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/00')->update(
            [ 
				'z08' => strval($totalday),
				'z09' => strval($endtsvgr)
			]
        );

		$referencem      = $database->getReference($dpath.'Summary/m/'.$cmonth.'/00/z08');
		$snapshotm       = $referencem->getSnapshot();
		$resultm         = $snapshotm->getValue();
		$totalm = $tsvgr + $resultm;
		
		$z08summ = $database->getReference($dpath.'Summary/m/'.$cmonth.'/00')->update(
            [ 
				'z08' => strval($totalm),
				'z09' => strval($endtsvgr)
			]
        );
		
		$dayend = $database->getReference($dpath.'zinfo')->update(
            [ 
				'tsv' => strval($endtsvgr)
			]
        );
		
		$OK = "[{\"name\":\"p01\",\"value\":\"".$recordf['p01']."\"},{\"name\":\"p02\",\"value\":\"".$recordf['p02']."\"},{\"name\":\"p03\",\"value\":\"".$recordf['p03']."\"},{\"name\":\"p04\",\"value\":\"".$recordf['p04']."\"},{\"name\":\"p05\",\"value\":\"".$recordf['p05']."\"},{\"name\":\"p06\",\"value\":\"".$recordgr['p06']."\"},{\"name\":\"p07\",\"value\":\"".$recordgr['p07']."\"},{\"name\":\"p11\",\"value\":\"".$recordgr['p11']."\"},{\"name\":\"p09\",\"value\":\"".$recordgr['p09']."\"},{\"name\":\"p08\",\"value\":\"".$selpricegr2."\"},{\"name\":\"p10\",\"value\":\"".$recordgr['p10']."\"},{\"name\":\"p17\",\"value\":\"".$tsvgr."\"}]";
		
		$result1 = $database->getReference($dpath.$table_id)->update(array($grid=>
            $GR)
        );
		
		$resultpro = $database->getReference($dpath.'products/'.$grpro)->set(
            $OK
        );
		
		$newPost = $database->getReference($dpath.'Inventory/Items/'.$grpro)->update(
			[
				//'p00' => "p18=0",
				'p10' => strval($recordgr['p10']),
				'p06' => strval($recordgr['p06']),
				'p07' => strval($recordgr['p07']),
				'p08' => strval($selpricegr2),
				'p11' => strval($recordgr['p11']),
				'p09' => strval($recordgr['p09']),
				'p17' => strval($tsvgr)
			]
        );
		
	//add product to day summary
	$referencegriday = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/'.$grpro.'/z00');
	$snapshotgriday  = $referencegriday->getSnapshot();
	$resultgriday = $snapshotgriday->getValue();
	
	if(empty($resultgriday)){
		$grsum = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/'.$grpro)->update(
				[ 
					'z00' => strval($selpricegr2),
					'z01' => strval($recordf['p03']),
					'z06' => strval("0") //jk
				]
			);
	}
	//add product to month summary
	$referencegridmo = $database->getReference($dpath.'Summary/m/'.$cmonth.'/'.$grpro.'/z00');
	$snapshotgridmo  = $referencegridmo->getSnapshot();
	$resultgridmo = $snapshotgridmo->getValue();
	
	if(empty($resultgridmo)){
		$grsum = $database->getReference($dpath.'Summary/m/'.$cmonth.'/'.$grpro)->update(
				[ 
					'z00' => strval($selpricegr2),
					'z01' => strval($recordf['p03']),
					'z06' => strval("0")
				]
			);
	}
		
	//check gr day
	$referencegrid = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/'.$grpro.'/z12');
	$snapshotgrid  = $referencegrid->getSnapshot();
	$resultgrid = $snapshotgrid->getValue();
	
	if(empty($resultgrid)){
		$grsum = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/'.$grpro)->update(
				[ 
					//'z07' => strval($selpricegr2),
					'z07' => strval($p10v),
					'z08' => strval($tsvgr),
					'z10' => strval($grid),
					'z11' => strval($tsvgr),
					'z12' => strval($recordgr['p10'])
				]
			);
	}else{
	$referencez11 = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/'.$grpro.'/z11');
	$snapshotz11  = $referencez11->getSnapshot();
	$resultz11 = $snapshotz11->getValue();
	
	$referencez12 = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/'.$grpro.'/z12');
	$snapshotz12  = $referencez12->getSnapshot();
	$resultz12 = $snapshotz12->getValue();
		
	$new = $resultz11 + $tsvgr;
	$newgr = $resultz12 + $recordgr['p10'];
	$grsum = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/'.$grpro)->update(
			[ 
				'z07' => strval($p10v),
				'z08' => strval($new),
				'z10' => strval($resultgrid.'/'.$grid),
				'z11' => strval($new),
				'z12' => strval($newgr)
			]
		);
	}
	//check gr month
	$referencegridm = $database->getReference($dpath.'Summary/m/'.$cmonth.'/'.$grpro.'/z12');
	$snapshotgridm  = $referencegridm->getSnapshot();
	$resultgridm = $snapshotgridm->getValue();
	
	if(empty($resultgridm)){
		$grsum = $database->getReference($dpath.'Summary/m/'.$cmonth.'/'.$grpro)->update(
				[ 
					'z07' => strval($p10v),
					'z08' => strval($tsvgr),
					'z10' => strval($grid),
					'z11' => strval($tsvgr),
					'z12' => strval($recordgr['p10'])
				]
			);
	}else{
	$referencez11m = $database->getReference($dpath.'Summary/m/'.$cmonth.'/'.$grpro.'/z11');
	$snapshotz11m  = $referencez11m->getSnapshot();
	$resultz11m = $snapshotz11m->getValue();
		
	$referencez12m = $database->getReference($dpath.'Summary/m/'.$cdate.'/'.$grpro.'/z12');
	$snapshotz12m  = $referencez12m->getSnapshot();
	$resultz12m = $snapshotz12m->getValue();
		
		$newm = $resultz11m + $tsvgr;
		$newgrm = $resultz12m + $recordgr['p10'];
		$grsumm = $database->getReference($dpath.'Summary/m/'.$cmonth.'/'.$grpro)->update(
			[ 
				'z07' => strval($p10v),
				'z08' => strval($newm),
				'z10' => strval($resultgridm.'/'.$grid),
				'z11' => strval($newm),
				'z12' => strval($newgrm)

			]
		);
	}
		
	}elseif(!empty($resultsel) && $resultsel != 0){
		
		if($recordgr['p06'] != $recordf['p06']){
			
			echo json_encode(
            array(
                'code' => 400,
                'message' => 'fail',
            )
        );
			
		}else{
			
	//add product to day summary
	$referencegriday = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/'.$grpro.'/z00');
	$snapshotgriday  = $referencegriday->getSnapshot();
	$resultgriday = $snapshotgriday->getValue();
	
	if(empty($resultgriday)){
		
		$grsum = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/'.$grpro)->update(
				[ 
					'z00' => strval($selpricegr2),
					'z01' => strval($recordf['p03']),
					'z06' => strval($recordf['p10']) //jk
				]
			);
	}else{
		//do noting
	}
	//add product to month summary
	$referencegridmo = $database->getReference($dpath.'Summary/m/'.$cmonth.'/'.$grpro.'/z00');
	$snapshotgridmo  = $referencegridmo->getSnapshot();
	$resultgridmo = $snapshotgridmo->getValue();
	
	if(empty($resultgridmo)){
		$grsum = $database->getReference($dpath.'Summary/m/'.$cmonth.'/'.$grpro)->update(
				[ 
					'z00' => strval($selpricegr2),
					'z01' => strval($recordf['p03']),
					'z06' => strval($recordgr['p10'])
				]
			);
	}else{
		//do noting
	}
			
		$reference      = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/00/z08');
		$snapshot       = $reference->getSnapshot();
		$result         = $snapshot->getValue();
		$totalday = $tsv + $result;
		
		$z08sum = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/00')->update(
            [ 
				'z08' => strval($totalday),
				'z09' => strval($endtsv)
			]
        );

		$referencem      = $database->getReference($dpath.'Summary/m/'.$cmonth.'/00/z08');
		$snapshotm       = $referencem->getSnapshot();
		$resultm         = $snapshotm->getValue();
		$totalm = $tsv + $resultm;
		
		$z08summ = $database->getReference($dpath.'Summary/m/'.$cmonth.'/00')->update(
            [ 
				'z08' => strval($totalm),
				'z09' => strval($endtsv)
			]
        );
		
		$dayend = $database->getReference($dpath.'zinfo')->update(
            [ 
				'tsv' => strval($endtsv)
			]
        );

		$OK = "[{\"name\":\"p01\",\"value\":\"".$recordf['p01']."\"},{\"name\":\"p02\",\"value\":\"".$recordf['p02']."\"},{\"name\":\"p03\",\"value\":\"".$recordf['p03']."\"},{\"name\":\"p04\",\"value\":\"".$recordf['p04']."\"},{\"name\":\"p05\",\"value\":\"".$recordf['p05']."\"},{\"name\":\"p06\",\"value\":\"".$recordf['p06']."\"},{\"name\":\"p07\",\"value\":\"".$recordf['p07']."\"},{\"name\":\"p11\",\"value\":\"".$recordf['p11']."\"},{\"name\":\"p09\",\"value\":\"".$recordf['p09']."\"},{\"name\":\"p08\",\"value\":\"".$recordf['p08']."\"},{\"name\":\"p10\",\"value\":\"".$p10v."\"},{\"name\":\"p17\",\"value\":\"".$p17v."\"}]";
		
		$result1 = $database->getReference($dpath.$table_id)->update(array($grid=>
            $GR)
        );
			
		$resultpro = $database->getReference($dpath.'products/'.$grpro)->set(
            $OK
        );
		
		$newPost = $database->getReference($dpath.'Inventory/Items/'.$grpro)->update(
			[ 
				'p10' => strval($p10v),
				//'p00' => "not empty",
				'p17' => strval($p17v)
			]
		);
		
	//check gr day
	$referencegrid = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/'.$grpro.'/z12');
	$snapshotgrid  = $referencegrid->getSnapshot();
	$resultgrid = $snapshotgrid->getValue();
	
	if(empty($resultgrid)){
		$grsum = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/'.$grpro)->update(
				[ 
					'z07' => strval($p10v),
					'z08' => strval($p17v),
					'z10' => strval($grid),
					'z11' => strval($tsvgr),
					'z12' => strval($recordgr['p10'])
				]
			);
	}else{
	$referencez11 = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/'.$grpro.'/z11');
	$snapshotz11  = $referencez11->getSnapshot();
	$resultz11 = $snapshotz11->getValue();
		
	$referencez12 = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/'.$grpro.'/z12');
	$snapshotz12  = $referencez12->getSnapshot();
	$resultz12 = $snapshotz12->getValue();
		
		$new = $resultz11 + $tsvgr;
		$newgr = $resultz12 + $recordgr['p10'];
		$grsum = $database->getReference($dpath.'Summary/d/'.$cmonth.'/'.$cdate.'/'.$grpro)->update(
				[ 
					'z07' => strval($p10v),
					'z08' => strval($p17v),
					'z10' => strval($resultgrid.'/'.$grid),
					'z11' => strval($new),
					'z12' => strval($newgr)
				]
			);
	}
	//check gr month
	$referencegridm = $database->getReference($dpath.'Summary/m/'.$cmonth.'/'.$grpro.'/z12');
	$snapshotgridm  = $referencegridm->getSnapshot();
	$resultgridm = $snapshotgridm->getValue();
			
	if(empty($resultgridm)){
		$grsum = $database->getReference($dpath.'Summary/m/'.$cmonth.'/'.$grpro)->update(
				[ 
					'z07' => strval($p10v),
					'z08' => strval($p17v),
					'z10' => strval($grid),
					'z11' => strval($tsvgr),
					'z12' => strval($recordgr['p10'])
				]
			);
	}else{
	$referencez11m = $database->getReference($dpath.'Summary/m/'.$cmonth.'/'.$grpro.'/z11');
	$snapshotz11m  = $referencez11m->getSnapshot();
	$resultz11m = $snapshotz11m->getValue();
		
	$referencez12m = $database->getReference($dpath.'Summary/m/'.$cdate.'/'.$grpro.'/z12');
	$snapshotz12m  = $referencez12m->getSnapshot();
	$resultz12m = $snapshotz12m->getValue();
		
		$newm = $resultz11m + $tsvgr;
		$newgrm = $resultz12m + $recordgr['p10'];
		$grsumm = $database->getReference($dpath.'Summary/m/'.$cmonth.'/'.$grpro)->update(
				[ 
					'z07' => strval($p10v),
					'z08' => strval($p17v),
					'z10' => strval($resultgridm.'/'.$grid),
					'z11' => strval($newm),
					'z12' => strval($newgrm)
				]
			);
	}
			
	}//end
	}
	}elseif($selpricegr2 != $selprice2){
	
		echo json_encode(
            array(
                'code' => 400,
                'message' => 'fail',
            )
        );
	}
			
}
	
	
    if(!empty($newPost->getKey()))
    {
        $result2 = $database->getReference($dpath.'zinfo')->update(
            [ 'us' => "1" ]
        );
		
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
