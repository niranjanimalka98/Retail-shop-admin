<?php
include_once('conn.php');

if(isset($_POST['id']))
{
    $lang = $_GET['lan'];
	$id       = $_POST['id'];
	
	require_once ("./lan/lang." . $lang . ".php");
	
	$url = 'https://pos.jkdesigns.app/demo'.'/export/'.$id;
	$cURL = curl_init();
	curl_setopt($cURL, CURLOPT_URL, $url);
	curl_setopt($cURL, CURLOPT_HTTPGET, true);
	curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json; charset=UTF-8',
		'Accept: application/json',
		'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7'
	));
	
$result1 = curl_exec($cURL);
curl_close($cURL);

	$json = '[
  {
      "seasonNumber":1,
      "numWins":1,
      "numHighBracket":2,
      "numLowBracket":2,
      "seasonXp":111,
      "seasonLevel":5,
      "bookXp":0,
      "bookLevel":1,
      "purchasedVIP":false
   },{
      "seasonNumber":1,
      "numWins":1,
      "numHighBracket":2,
      "numLowBracket":2,
      "seasonXp":111,
      "seasonLevel":5,
      "bookXp":0,
      "bookLevel":1,
      "purchasedVIP":true
   }]';
	
	$result2 = substr($result1, 0, -1);
	$result3 = strstr($result2, ': {');
	$result = substr($result3, 1);
	
	if(strpos($id, "Inventory")){
		foreach (json_decode($result1) as $set) {
			$arraydata[] = [$lan['SKU'] => $set->p01, $lan['NAMEEN'] => $set->p03, $lan['CATEGORY'] => $set->p02, $lan['BRAND'] => $set->p04, $lan['EXPIRY'] => $set->p05, $lan['PURCOST'] => $set->p06, $lan['PURCOSTPRO'] => $set->p07, $lan['PROFIT'] => $set->p11, $lan['UNIT'] => $set->p09, $lan['PRODUCTPRI'] => $set->p08, $lan['CUSTOCK'] => $set->p10, $lan['CUSTOVAL'] => $set->p17, $lan['RECORDENTER'] => $set->p18, $lan['INSTOCK'] => $set->p19, $lan['INITIALSTOVAL'] => $set->p20];
		}

	}elseif(strpos($id, "Summary") || strpos($id, "SummaryMonth")){
		$i = 0;

		error_reporting(E_ALL);
		require_once 'Classes/PHPExcel.php';
		$objPHPExcel = new PHPExcel();
		$time = new DateTime('Asia/Colombo');
		$docdate = $time->format('d/m/Y H:i:s');
		$doc = $time->format('d/m/Y');
		
		// Set document properties
		$objPHPExcel->getProperties()->setCreator("pos.jkdesigns.app")
									 ->setLastModifiedBy("pos.jkdesigns.app @ ".$docdate)
									 ->setTitle("Day Summary")
									 ->setSubject("Summary generated.")
									 ->setDescription("Summary generated from pos.jkdesigns.app - ".$docdate)
									 ->setKeywords("jkdesigns summary")
									 ->setCategory("Accounting");

		// Create a first sheet, representing sales data
		$objPHPExcel->setActiveSheetIndex(0);
		if(strpos($id, "SummaryMonth")){
			$fname = substr($id,0,7);
			$cmonth = str_replace('-','',$fname);
		}else if(strpos($id, "Summary-")){
			$fname = substr($id,0,10);
			$cmonth = substr(str_replace('-','',$fname),2);
		}
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Summary of '.$fname);
		
		$objPHPExcel->getActiveSheet()->setCellValue('A3', 'Product');
		$objPHPExcel->getActiveSheet()->setCellValue('B3', 'Initial Stock');
		$objPHPExcel->getActiveSheet()->setCellValue('E3', 'GR');
		$objPHPExcel->getActiveSheet()->setCellValue('G3', 'Return');
		$objPHPExcel->getActiveSheet()->setCellValue('I3', 'Sales');
		$objPHPExcel->getActiveSheet()->setCellValue('O3', 'Price Change');
		$objPHPExcel->getActiveSheet()->setCellValue('P3', 'Remark');
		$objPHPExcel->getActiveSheet()->setCellValue('R3', 'End Stock');
		$objPHPExcel->getActiveSheet()->setCellValue('B4', 'Qty');
		$objPHPExcel->getActiveSheet()->setCellValue('C4', 'Unit Price');
		$objPHPExcel->getActiveSheet()->setCellValue('D4', 'Amount');
		$objPHPExcel->getActiveSheet()->setCellValue('E4', 'Qty');
		$objPHPExcel->getActiveSheet()->setCellValue('F4', 'Amount');
		$objPHPExcel->getActiveSheet()->setCellValue('G4', 'Qty');
		$objPHPExcel->getActiveSheet()->setCellValue('H4', 'Amount');
		$objPHPExcel->getActiveSheet()->setCellValue('I4', 'Cash');
		$objPHPExcel->getActiveSheet()->setCellValue('I5', 'Qty');
		$objPHPExcel->getActiveSheet()->setCellValue('J5', 'Amount');
		$objPHPExcel->getActiveSheet()->setCellValue('K5', 'Discount');
		$objPHPExcel->getActiveSheet()->setCellValue('L4', 'Credit');
		$objPHPExcel->getActiveSheet()->setCellValue('L5', 'Qty');
		$objPHPExcel->getActiveSheet()->setCellValue('M5', 'Amount');
		$objPHPExcel->getActiveSheet()->setCellValue('N5', 'Discount');
		$objPHPExcel->getActiveSheet()->setCellValue('Q4', 'Qty');
		$objPHPExcel->getActiveSheet()->setCellValue('R4', 'Amount');

		$excel = 6;
		$excount = 0;
		$ex = 6;
		$discount = "";
		$INsctock = "";
		$INcash = "";
		$INgr = "";
		$INpricepluse = 0.00;
		$INpriceminus = 0.00;
		$INex = 0.00;
		$INcreadit = "";
		$INestock = "";
		$INecash = "";
		$INexpense = "";
		foreach (json_decode($result1) as $set) {
			
			if($i == 0){
				//summary
			$arraydata[] = [$lan['DATE'] => $set->z00, $lan['DSTART'] => $set->z01, $lan['SV'] => $set->z02, $lan['SI'] => $set->z03, $lan['SC'] => $set->z04, $lan['RV'] => $set->z05, $lan['RI'] => $set->z06, $lan['RC'] => $set->z07, $lan['GRADDED'] => $set->z08, $lan['DEND'] => $set->z09];
				$INsctock = $set->z01;
				$INgr = $set->z08;
				$INestock = $set->z09;
				$i++;
				
			}elseif($i == 1){
			
				$arraydata[] = ["INITIAL CASH" => $set->z00, "DAY CASH" => $set->z01, "EXPENSE" => $set->z02, "END CASH" => $set->z03, "IN CHEQUE" => $set->z04, "DAY CHEQUE" => $set->z05, "END CHEQUE" => $set->z06, "IN CREDIT" => $set->z07, "DAY CREDIT" => $set->z08, "END CREDIT" => $set->z09, "DISCOUNT" => $set->z10];
				
				$objPHPExcel->getActiveSheet()->setCellValue('T4', 'Initial Cash');
				$objPHPExcel->getActiveSheet()->setCellValue('V4', $set->z00);
				$objPHPExcel->getActiveSheet()->setCellValue('T5', 'Day Cash');
				$objPHPExcel->getActiveSheet()->setCellValue('V5', $set->z01);
				$objPHPExcel->getActiveSheet()->setCellValue('T6', 'Total Cash');
				$INcash = $set->z00;
				$INcreadit = $set->z08;
				$discount = $set->z10;
				$INecash = $set->z03;
				$INexpense = $set->z02;
				$i++;
				
			}else{
				
				$arraydata[] = ["PRICE" => $set->z00, "PRODUCT" => $set->z01, "TAMIL" => $set->z09, "SALES COUNT" => $set->z02, "SALES COUNT VALUE" => $set->z03, "RETURN COUNT" => $set->z04, "RETURN VALUE" => $set->z05, "TOTAL STOCK" => $set->z06, "CURRENT STOCK" => $set->z07, "CURRENT VALUE" => $set->z08, "GR ID" => $set->z10, "GR ID T" => $set->z10t, "GR ADDED" => $set->z11, "GR QTY" => $set->z12, "scc" => $set->z02c, "scv" => $set->z03c, "rcc" => $set->z04c, "rcv" => $set->z05c];
				
		
				if($set->z01 == "Expense"){
					$ex++;
					$ex1 = $ex + 1;
					
					if(strpos($id, "SummaryMonth")){
						$objPHPExcel->getActiveSheet()->setCellValue('T'.$ex1, $set->z02);
						$objPHPExcel->getActiveSheet()->setCellValue('V'.$ex1, $INexpense);
					}else if(strpos($id, "Summary-")){
						$objPHPExcel->getActiveSheet()->setCellValue('T'.$ex1, $set->z02);
						$objPHPExcel->getActiveSheet()->setCellValue('V'.$ex1, $set->z03);
					}
					
					$INex = $INex + $set->z03;
					$excount++;
				}else{
					if($lang == "en"){
						$objPHPExcel->getActiveSheet()->setCellValue('A'.$excel, $set->z01);
					}else{
						$objPHPExcel->getActiveSheet()->setCellValue('A'.$excel, $set->z09);
					}
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$excel, $set->z06);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$excel, $set->z04);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$excel, $set->z05);
					//$objPHPExcel->getActiveSheet()->setCellValue('G'.$excel, $set->z04c);
					//$objPHPExcel->getActiveSheet()->setCellValue('H'.$excel, $set->z05c);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$excel, $set->z02);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.$excel, $set->z03);
					$objPHPExcel->getActiveSheet()->setCellValue('K'.$excel, $set->z03d);
					$objPHPExcel->getActiveSheet()->setCellValue('L'.$excel, $set->z02c);
					$objPHPExcel->getActiveSheet()->setCellValue('M'.$excel, $set->z03c);
					$objPHPExcel->getActiveSheet()->setCellValue('N'.$excel, $set->z03cd);
					$objPHPExcel->getActiveSheet()->setCellValue('Q'.$excel, $set->z07);
					$objPHPExcel->getActiveSheet()->setCellValue('R'.$excel, $set->z08);
					
					if(strpos($set->z10, 'x')){
						$objPHPExcel->getActiveSheet()->setCellValue('D'.$excel, '');
					}else{
						$objPHPExcel->getActiveSheet()->setCellValue('D'.$excel, '=IF(C'.$excel.'<>"",B'.$excel.'*C'.$excel.',"")');
					}
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$excel, $set->z00);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$excel, $set->z12);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$excel, $set->z11);
					$objPHPExcel->getActiveSheet()->setCellValue('P'.$excel, $set->z10);
					$objPHPExcel->getActiveSheet()->setCellValue('O'.$excel, $set->z10t);
				if(strpos($set->z10t,"-") !== false){
					$INpriceminus = "(".$INpriceminus.")" + "(".$set->z10t.")";
				}else{
					$INpricepluse = $INpricepluse + $set->z10t;
				}
					$excel++;
				}
				
			}
		}

		$excel++;
		$objPHPExcel->getActiveSheet()->mergeCells('A1:P1'); //title
		$objPHPExcel->getActiveSheet()->mergeCells('B3:D3'); //Int stock
		$objPHPExcel->getActiveSheet()->mergeCells('E3:F3'); //GR
		$objPHPExcel->getActiveSheet()->mergeCells('G3:H3'); //Return
		$objPHPExcel->getActiveSheet()->mergeCells('I3:N3'); //Sales
		$objPHPExcel->getActiveSheet()->mergeCells('I4:K4'); //Cash
		$objPHPExcel->getActiveSheet()->mergeCells('L4:N4'); //Credit
		$objPHPExcel->getActiveSheet()->mergeCells('Q3:R3'); //End stock
		$excel++;
		//$objPHPExcel->getActiveSheet()->getStyle('P'.$excel)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('R'.$excel)->getFont()->setSize(12);
		$excel2=$excel - $excount + 1;
		$excel=$excel - $excount + 1;
		$excel++;
		$objPHPExcel->getActiveSheet()->setCellValue('R'.$excel, '=SUM(R6:R'.$excel2.')');		
		$objPHPExcel->getActiveSheet()->getStyle('J'.$excel)->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->setCellValue('J'.$excel, '=SUM(J6:J'.$excel2.')');		
		$objPHPExcel->getActiveSheet()->getStyle('M'.$excel)->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->setCellValue('M'.$excel, '=SUM(M6:M'.$excel2.')');		
		$objPHPExcel->getActiveSheet()->getStyle('D'.$excel)->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$excel, '=SUM(D6:D'.$excel2.')');		
		$objPHPExcel->getActiveSheet()->getStyle('F'.$excel)->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$excel, '=SUM(F6:F'.$excel2.')');
		$objPHPExcel->getActiveSheet()->getStyle('H'.$excel)->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$excel, '=SUM(H6:H'.$excel2.')');
		$objPHPExcel->getActiveSheet()->getStyle('O'.$excel)->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->setCellValue('O'.$excel, '=SUM(O6:O'.$excel2.')');
		$objPHPExcel->getActiveSheet()->getStyle('K'.$excel)->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->setCellValue('K'.$excel, '=SUM(K6:K'.$excel2.')');
		$objPHPExcel->getActiveSheet()->getStyle('N'.$excel)->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->setCellValue('N'.$excel, '=SUM(N6:N'.$excel2.')');
		
		$excelm = $excel + 1;
			$objPHPExcel->getActiveSheet()->mergeCells('H'.$excelm.':'.'I'.$excelm);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$excelm, "DISCOUNT:");
			$objPHPExcel->getActiveSheet()->getStyle('H'.$excelm)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('H'.$excelm)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$excelm, $discount);
			$objPHPExcel->getActiveSheet()->getStyle('J'.$excelm)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('J'.$excelm)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_LKR_SIMPLE);
			//$excel = $excel + 3;
		
		if($excount > 0){
			$add = $excount + 7;
			$add1 = $add + 1;
			
			$objPHPExcel->getActiveSheet()->getStyle('V6')->getFont()->setSize(12);
			$objPHPExcel->getActiveSheet()->getStyle('V6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->setCellValue('V6', '=SUM(V4:V5)');
			$objPHPExcel->getActiveSheet()->getStyle('V'.$add1)->getFont()->setSize(12);
			$objPHPExcel->getActiveSheet()->getStyle('V'.$add1)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->setCellValue('T'.$add1, 'Total Expense');
			$objPHPExcel->getActiveSheet()->setCellValue('V'.$add1, '=SUM(V8:V'.$add.')');
			$add2 = $add1 + 2;
			$objPHPExcel->getActiveSheet()->getStyle('V'.$add2)->getFont()->setSize(12);
			$objPHPExcel->getActiveSheet()->getStyle('V'.$add2)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->setCellValue('T'.$add2, 'End Cash');
			$objPHPExcel->getActiveSheet()->setCellValue('V'.$add2, '=V6-V'.$add1);
			$newstart = $add2 + 2;
			$newstart13 = $add2 + 14;
			$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(13);
			$objPHPExcel->getActiveSheet()->getStyle('V4:V'.$newstart13)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_LKR_SIMPLE);			
		}
		
		$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
		$objPHPExcel->getActiveSheet()->protectCells('A3:R'.$excel, 'PHPExcel');		
		// Set cell number formats
		$excel5=$excel + 1;
		$objPHPExcel->getActiveSheet()->getStyle('D6:D'.$excel5)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_LKR_SIMPLE);
		$objPHPExcel->getActiveSheet()->getStyle('C6:C'.$excel)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_LKR_SIMPLE);
		$objPHPExcel->getActiveSheet()->getStyle('F6:F'.$excel)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_LKR_SIMPLE);
		$objPHPExcel->getActiveSheet()->getStyle('H6:H'.$excel)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_LKR_SIMPLE);
		$objPHPExcel->getActiveSheet()->getStyle('J6:J'.$excel)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_LKR_SIMPLE);
		$objPHPExcel->getActiveSheet()->getStyle('M6:M'.$excel)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_LKR_SIMPLE);
		$objPHPExcel->getActiveSheet()->getStyle('O6:O'.$excel)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_LKR_SIMPLE);
		$objPHPExcel->getActiveSheet()->getStyle('R6:R'.$excel)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_LKR_SIMPLE);
		$objPHPExcel->getActiveSheet()->getStyle('K6:K'.$excel)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_LKR_SIMPLE);
		$objPHPExcel->getActiveSheet()->getStyle('N6:N'.$excel)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_LKR_SIMPLE);
		
		//new summary
		$add3 = $add2 + 2;
		$objPHPExcel->getActiveSheet()->setCellValue('T'.$add3, 'Initial Stock');
		$objPHPExcel->getActiveSheet()->setCellValue('V'.$add3, $INsctock);
		$add3++;
		$objPHPExcel->getActiveSheet()->setCellValue('T'.$add3, 'Initial Cash');
		$objPHPExcel->getActiveSheet()->setCellValue('V'.$add3, $INcash);
		$add3++;
		$add4 = $add3 - 3;
		$add5 = $add3 - 1;
		$objPHPExcel->getActiveSheet()->setCellValue('V'.$add3, '=SUM(V'.$add4.':V'.$add5.')');
		$objPHPExcel->getActiveSheet()->getStyle('V'.$add3)->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->getStyle('V'.$add3)->getFont()->setBold(true);
		$add3++;
		$objPHPExcel->getActiveSheet()->setCellValue('T'.$add3, 'GR');
		$INgr = $INgr - $INpricepluse +  $INpriceminus;
		$objPHPExcel->getActiveSheet()->setCellValue('V'.$add3, $INgr);
		$add3++;
		$objPHPExcel->getActiveSheet()->setCellValue('T'.$add3, 'Price Change +');
		$objPHPExcel->getActiveSheet()->setCellValue('V'.$add3, $INpricepluse);
		$add3++;
		$add6 = $add3 - 3;
		$add7 = $add3 - 1;
		$objPHPExcel->getActiveSheet()->setCellValue('V'.$add3, '=SUM(V'.$add6.':V'.$add7.')');
		$objPHPExcel->getActiveSheet()->getStyle('V'.$add3)->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->getStyle('V'.$add3)->getFont()->setBold(true);
		$add3++;
		$objPHPExcel->getActiveSheet()->setCellValue('T'.$add3, 'Expense');
		
		if(strpos($id, "SummaryMonth")){
			$objPHPExcel->getActiveSheet()->setCellValue('V'.$add3, "-".$INexpense);
		}else if(strpos($id, "Summary-")){
			$objPHPExcel->getActiveSheet()->setCellValue('V'.$add3, "-".$INex);
		}
	
		$add3++;
		$objPHPExcel->getActiveSheet()->setCellValue('T'.$add3, 'Credit');
		$objPHPExcel->getActiveSheet()->setCellValue('V'.$add3, "-".$INcreadit);
		$add3++;
		$objPHPExcel->getActiveSheet()->setCellValue('T'.$add3, 'Price Change -');
		$objPHPExcel->getActiveSheet()->setCellValue('V'.$add3, "-".$INpriceminus);
		$add3++;
		$objPHPExcel->getActiveSheet()->setCellValue('T'.$add3, 'Discount');
		$objPHPExcel->getActiveSheet()->setCellValue('V'.$add3, "-".$discount);
		$add3++;
		$add8 = $add3 - 5;
		$add9 = $add3 - 1;
		$objPHPExcel->getActiveSheet()->setCellValue('V'.$add3, '=SUM(V'.$add8.':V'.$add9.')');
		$objPHPExcel->getActiveSheet()->getStyle('V'.$add3)->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->getStyle('V'.$add3)->getFont()->setBold(true);
		$add3++;
		$objPHPExcel->getActiveSheet()->setCellValue('T'.$add3, 'End Stock');
		$objPHPExcel->getActiveSheet()->setCellValue('V'.$add3, $INestock);
		$add3++;
		$objPHPExcel->getActiveSheet()->setCellValue('T'.$add3, 'End Cash');
		$add10 = $add3 - 2;
		$add11 = $add3 - 1;
		$objPHPExcel->getActiveSheet()->setCellValue('V'.$add3, '=V'.$add10.'-V'.$add11);
		//$objPHPExcel->getActiveSheet()->setCellValue('T'.$add3, $INecash);
		$objPHPExcel->getActiveSheet()->getStyle('V'.$add3)->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->getStyle('V'.$add3)->getFont()->setBold(true);
		
		// Set column widths
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(13);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(13);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(13);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(13);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(13);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(13);
		$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(20);

		// Set fonts
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('Calibri');
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		//New summary
		$objPHPExcel->getActiveSheet()->getStyle('T'.$newstart.':V'.$add3)->applyFromArray(
				array(
					'borders' => array(
						'top'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					),
				)
		);
		$newstart = $newstart + 2;
		$objPHPExcel->getActiveSheet()->getStyle('T'.$newstart.':V'.$newstart)->applyFromArray(
				array(
					'borders' => array(
						'bottom'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					),
				)
		);
		$objPHPExcel->getActiveSheet()->getStyle('T'.$newstart.':V'.$newstart)->applyFromArray(
				array(
					'borders' => array(
						'top'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					),
				)
		);
		$objPHPExcel->getActiveSheet()->getStyle('T'.$newstart.':V'.$newstart)->applyFromArray(
				array(
					'borders' => array(
						'bottom'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					),
				)
		);
		$newstart = $newstart + 3;
		$objPHPExcel->getActiveSheet()->getStyle('T'.$newstart.':V'.$newstart)->applyFromArray(
				array(
					'borders' => array(
						'bottom'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					),
				)
		);
		$objPHPExcel->getActiveSheet()->getStyle('T'.$newstart.':V'.$newstart)->applyFromArray(
				array(
					'borders' => array(
						'top'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					),
				)
		);
		$newstart = $newstart + 5;
		$objPHPExcel->getActiveSheet()->getStyle('T'.$newstart.':V'.$newstart)->applyFromArray(
				array(
					'borders' => array(
						'top'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					),
				)
		);
		$objPHPExcel->getActiveSheet()->getStyle('T'.$newstart.':V'.$newstart)->applyFromArray(
				array(
					'borders' => array(
						'bottom'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					),
				)
		);
		$newstart = $newstart + 2;
		$objPHPExcel->getActiveSheet()->getStyle('T'.$newstart.':V'.$newstart)->applyFromArray(
				array(
					'borders' => array(
						'top'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					),
				)
		);
		$objPHPExcel->getActiveSheet()->getStyle('T'.$newstart.':V'.$newstart)->applyFromArray(
				array(
					'borders' => array(
						'bottom'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					),
				)
		);
		$newstartl = $newstart - 12;
		$objPHPExcel->getActiveSheet()->getStyle('T'.$newstartl.':T'.$newstart)->applyFromArray(
				array(
					'borders' => array(
						'left'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					),
				)
		);
		$newstartr = $newstart - 12;
		$objPHPExcel->getActiveSheet()->getStyle('V'.$newstartr.':V'.$newstart)->applyFromArray(
				array(
					'borders' => array(
						'right'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					),
				)
		);
		//CASH AREA
		$objPHPExcel->getActiveSheet()->getStyle('T4:V'.$add2)->applyFromArray(
				array(
					'borders' => array(
						'top'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					),
				)
		);
		$objPHPExcel->getActiveSheet()->getStyle('T6:V6')->applyFromArray(
				array(
					'borders' => array(
						'bottom'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					),
				)
		);
		$objPHPExcel->getActiveSheet()->getStyle('T'.$add2.':V'.$add2)->applyFromArray(
				array(
					'borders' => array(
						'bottom'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						),
					),
				)
		);
		$objPHPExcel->getActiveSheet()->getStyle('T'.$add1.':V'.$add1)->applyFromArray(
				array(
					'borders' => array(
						'bottom'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						),
					),
				)
		);
		$objPHPExcel->getActiveSheet()->getStyle('T4:T'.$add2)->applyFromArray(
				array(
					'borders' => array(
						'left'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						),
					),
				)
		);
		$objPHPExcel->getActiveSheet()->getStyle('V4:V'.$add2)->applyFromArray(
				array(
					'borders' => array(
						'right'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						),
					),
				)
		);		
		// Set thin black border outline around column
		$styleThinBlackBorderOutline = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('argb' => 'FF000000'),
				),
			),
		);
		$objPHPExcel->getActiveSheet()->getStyle('A4:R'.$excel)->applyFromArray($styleThinBlackBorderOutline);

		// Set thick brown border outline around "Total"
		$styleThickBrownBorderOutline = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('argb' => 'FF000000'),
				),
			),
		);
		$objPHPExcel->getActiveSheet()->getStyle('A4:R5')->applyFromArray($styleThickBrownBorderOutline);
		// Set fills
		$objPHPExcel->getActiveSheet()->getStyle('A1:R1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:R1')->getFill()->getStartColor()->setARGB('FFFFFF');
		// Set style for header row using alternative method
		$objPHPExcel->getActiveSheet()->getStyle('A3:R3')->applyFromArray(
				array(
					'font'    => array(
						'bold'      => true
					),
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					),
					'borders' => array(
						'top'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					),
					'fill' => array(
						'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
						'rotation'   => 90,
						'startcolor' => array(
							'argb' => 'F6F6F6'
						),
						'endcolor'   => array(
							'argb' => 'F6F6F6'
						)
					)
				)
		);

		$objPHPExcel->getActiveSheet()->getStyle('A'.$excel.':R'.$excel)->applyFromArray(
				array(
					'font'    => array(
						'bold'      => true
					),
					'borders' => array(
						'top'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					),
					'fill' => array(
						'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
						'rotation'   => 90,
						'startcolor' => array(
							'argb' => 'F6F6F6'
						),
						'endcolor'   => array(
							'argb' => 'F6F6F6'
						)
					)
				)
		);
		
		$objPHPExcel->getActiveSheet()->getStyle('A4:R5')->applyFromArray(
				array(
					'font'    => array(
						'bold'      => true
					),
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					),
					'borders' => array(
						'top'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					),
					'fill' => array(
						'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
						'rotation'   => 90,
						'startcolor' => array(
							'argb' => 'F6F6F6'
						),
						'endcolor'   => array(
							'argb' => 'F6F6F6'
						)
					)
				)
		);

		$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray(
				array(
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					),
					'borders' => array(
						'left'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					)
				)
		);

		$objPHPExcel->getActiveSheet()->getStyle('B3')->applyFromArray(
				array(
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					)
				)
		);

		$objPHPExcel->getActiveSheet()->getStyle('R3')->applyFromArray(
				array(
					'borders' => array(
						'right'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					)
				)
		);

		$objPHPExcel->getActiveSheet()->getStyle('B3:D'.$excel)->applyFromArray(
				array(
					'borders' => array(
						'right'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						),
						'left'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					)
				)
		);

		$objPHPExcel->getActiveSheet()->getStyle('G3:H'.$excel)->applyFromArray(
				array(
					'borders' => array(
						'right'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						),
						'left'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					)
				)
		);

		$objPHPExcel->getActiveSheet()->getStyle('N3:N'.$excel)->applyFromArray(
				array(
					'borders' => array(
						'right'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					)
				)
		);

		$objPHPExcel->getActiveSheet()->getStyle('I4:N4')->applyFromArray(
				array(
					'borders' => array(
						'bottom'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					)
				)
		);

		$objPHPExcel->getActiveSheet()->getStyle('J3:J'.$excel)->applyFromArray(
				array(
					'borders' => array(
						'right'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					)
				)
		);

		$objPHPExcel->getActiveSheet()->getStyle('C4:C'.$excel)->applyFromArray(
				array(
					'borders' => array(
						'right'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						),
						'left'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					)
				)
		);

		$objPHPExcel->getActiveSheet()->getStyle('M5:M'.$excel)->applyFromArray(
				array(
					'borders' => array(
						'right'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						),
						'left'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					)
				)
		);
		
		$objPHPExcel->getActiveSheet()->getStyle('E4:E'.$excel)->applyFromArray(
				array(
					'borders' => array(
						'right'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					)
				)
		);

		$objPHPExcel->getActiveSheet()->getStyle('G4:G'.$excel)->applyFromArray(
				array(
					'borders' => array(
						'right'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					)
				)
		);
		
		$objPHPExcel->getActiveSheet()->getStyle('Q3:Q'.$excel)->applyFromArray(
				array(
					'borders' => array(
						'left'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					)
				)
		);
		
		$objPHPExcel->getActiveSheet()->getStyle('O3:O'.$excel)->applyFromArray(
				array(
					'borders' => array(
						'right'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					)
				)
		);

		$objPHPExcel->getActiveSheet()->getStyle('I5:I'.$excel)->applyFromArray(
				array(
					'borders' => array(
						'right'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					)
				)
		);

		$objPHPExcel->getActiveSheet()->getStyle('K4:K'.$excel)->applyFromArray(
				array(
					'borders' => array(
						'right'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					)
				)
		);

		$objPHPExcel->getActiveSheet()->getStyle('R3:R'.$excel)->applyFromArray(
				array(
					'borders' => array(
						'right'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					)
				)
		);

		$objPHPExcel->getActiveSheet()->getStyle('R4:R'.$excel)->applyFromArray(
				array(
					'borders' => array(
						'left'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					)
				)
		);

		$objPHPExcel->getActiveSheet()->getStyle('A6:A'.$excel)->applyFromArray(
				array(
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
					)
				)
		);
		
		// Set page orientation and size
		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL);

		// Rename first worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Summary');

		//-------------------------------------Sales Summary--------------------------------------//
		//2nd sheet
		
		
		//add products to summary
	$reference = $database->getReference($dpath .'invoices/'.$cmonth);
	$snapshot  = $reference->getSnapshot();
	$resultp = $snapshot->getValue();
	$table_result_val= [] ;
	$table_result_key= [] ;

	if(!empty($resultp))
	{
		$table_result_val = array_values($resultp);
		$table_result_key = array_keys($resultp);
	//}
	$objPHPExcel->createSheet();
		
		$objPHPExcel->setActiveSheetIndex(1);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Sales Ledger of '.$fname);
		
		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'No');
		$objPHPExcel->getActiveSheet()->setCellValue('B2', 'Invoice No');
		
		$checkdate = str_replace('-','/',$fname);
		$sales	= 3;
		$top = 'A'; 
		$top1 = 'A'; 
		$top2 = 'A'; 
		$C2= array(); //add products once as title
		$addition= array(); //addition of stock & total
		$first = true;
		
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
		
	if(strpos($id, "SummaryMonth")){
			$dateb4 = substr($record['b04'],3);
		}else if(strpos($id, "Summary-")){
			$dateb4 = $record['b04'];
		}
		
	if($dateb4 === $checkdate){
		
		if(strpos($record['b06'],"<br>") !== false){
			$str_br = explode ("<br>", $record['b06']);
			
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$sales, $sales - 2);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$sales, $record['b01']);
			
			for($j = 0; $j < count($str_br); $j++)
			{
				
				if(strpos($str_br[$j],"=") !== false){
					
				$str_arr = explode ("x", $str_br[$j]);
				$str_ar = str_replace("- 1", "", $str_arr[0]);
				$str_a = explode ("=", $str_arr[2]);
		
				if(in_array($str_ar, $C2)){
					$pos1 = array_search($str_ar, $C2);
					if($pos1 >= "1"){
						$pos1 = ($pos1 * 3) + 2;
					}else{
						$pos1 = $pos1 + 2;
					}					
					$pos = 'A';
					$pos3 = 'A';
					$pos4 = 'A';
					$pos2 = $sales;
					for($a5 = 0; $a5 < $pos1; $a5++) {
						$pos++;
						$pos3++;
						$pos4++;
					}
					
					$objPHPExcel->getActiveSheet()->setCellValue($pos.$pos2, intval($str_arr[1]));
					$pos++;
					$objPHPExcel->getActiveSheet()->setCellValue($pos.$pos2, intval($str_a[0]));
					$objPHPExcel->getActiveSheet()->getStyle($pos.$pos2)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_LKR_SIMPLE);
					$pos++;
					$pos4++;
					$objPHPExcel->getActiveSheet()->setCellValue($pos.$pos2, '=IF('.$pos4.$pos2.'<>"",'.$pos3.$pos2.'*'.$pos4.$pos2.',"")');					
				}else{
					
					if($first){
						for($a4 = 0; $a4 < 2; $a4++) {
							$top++;
							$top1++;
							$top2++;
						}
						
					if(!strpos($addition, $top)){
						array_push($addition, $top);
					}
					$objPHPExcel->getActiveSheet()->setCellValue($top.'2', $str_ar);
					//$objPHPExcel->getActiveSheet()->setCellValue('C'.$sales, $str_ar);
					$objPHPExcel->getActiveSheet()->setCellValue($top.$sales, intval($str_arr[1]));
					$top++;
					$objPHPExcel->getActiveSheet()->setCellValue($top.$sales, intval($str_a[0]));
						$objPHPExcel->getActiveSheet()->getStyle($top.$sales)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_LKR_SIMPLE);
					$top++;
					$top2++;
					$objPHPExcel->getActiveSheet()->setCellValue($top.$sales, '=IF('.$top2.$sales.'<>"",'.$top1.$sales.'*'.$top2.$sales.',"")');
						
					if(!strpos($addition, $top)){
						array_push($addition, $top);
					}						
					$top++;
					$first = false;						
					}else{					
					$top11 = 'A';
					$top22 = 'A';						
					$countbr = count($C2);				
					if($countbr >= "1"){
						$countbr = (count($C2) * 3)+2;
					}elseif($countbr === "0"){
						$countbr = (count($C2) + 3)+2;
					}
					
					for($abr = 0; $abr < $countbr; $abr++) {
						//$top++;
						$top11++;
						$top22++;
					}
						
					if(!strpos($addition, $top)){
						array_push($addition, $top);
					}
					$objPHPExcel->getActiveSheet()->setCellValue($top.'2', $str_ar);
					//$objPHPExcel->getActiveSheet()->setCellValue('C'.$sales, $str_ar);
					$objPHPExcel->getActiveSheet()->setCellValue($top.$sales, intval($str_arr[1]));
					$top++;
					$objPHPExcel->getActiveSheet()->setCellValue($top.$sales, intval($str_a[0]));
						$objPHPExcel->getActiveSheet()->getStyle($top.$sales)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_LKR_SIMPLE);
					$top++;
					$top22++;
					$objPHPExcel->getActiveSheet()->setCellValue($top.$sales, '=IF('.$top22.$sales.'<>"",'.$top11.$sales.'*'.$top22.$sales.',"")');
						
					if(!strpos($addition, $top)){
						array_push($addition, $top);
					}						
					$top++;
					$first = false;
					}
					
					if(!strpos($C2, $str_ar)){
						array_push($C2,$str_ar);
					}
				}	
				}
			}
				$sales++;
			
		}else{ //end of has <br>
			$str_arr = explode ("x", $record['b06']);
			$str_ar = str_replace("- 1", "", $str_arr[0]);
			$str_a = explode ("=", $str_arr[2]);
			
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$sales, $sales - 2);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$sales, $record['b01']);
						
			if(in_array($str_ar, $C2)){
				$pos11 = array_search($str_ar, $C2);
				if($pos11 >= "1"){
					$pos11 = ($pos11 * 3) + 2;
				}else{
					$pos11 = $pos11 + 2;
				}
				$poss = 'A';
				$pos3 = 'A';
				$pos4 = 'A';
				$pos2 = $sales;
				for($a3 = 0; $a3 < $pos11; $a3++) {
					$poss++;
					$pos3++;
					$pos4++;
				}
				
				$objPHPExcel->getActiveSheet()->setCellValue($poss.$pos2, intval($str_arr[1]));
				$poss++;
				$objPHPExcel->getActiveSheet()->setCellValue($poss.$pos2, intval($str_a[0]));
				$objPHPExcel->getActiveSheet()->getStyle($poss.$pos2)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_LKR_SIMPLE);
				$poss++;
				$pos4++;
				$objPHPExcel->getActiveSheet()->setCellValue($poss.$pos2, '=IF('.$pos4.$pos2.'<>"",'.$pos3.$pos2.'*'.$pos4.$pos2.',"")');
				
			}else{
				
				if($first){
					for($a2 = 0; $a2 < 2; $a2++) {
						$top++;
						$top1++;
						$top2++;
					}
					
					if(!strpos($addition, $top)){
						array_push($addition, $top);
					}
				$objPHPExcel->getActiveSheet()->setCellValue($top.'2', $str_ar);
				//$objPHPExcel->getActiveSheet()->setCellValue('C'.$sales, $str_ar);
				$objPHPExcel->getActiveSheet()->setCellValue($top.$sales, intval($str_arr[1]));
				$top++;
				$objPHPExcel->getActiveSheet()->setCellValue($top.$sales, intval($str_a[0]));
					$objPHPExcel->getActiveSheet()->getStyle($top.$sales)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_LKR_SIMPLE);
				$top++;
				$top2++;
				$objPHPExcel->getActiveSheet()->setCellValue($top.$sales, '=IF('.$top2.$sales.'<>"",'.$top1.$sales.'*'.$top2.$sales.',"")');
					
					if(!strpos($addition, $top)){
						array_push($addition, $top);
					}
					
				$top++;	
				$first = false;
					
				}else{
				
					$top111 = 'A';
					$top222 = 'A';
					
				$count = count($C2);
				
					if($count >= "1"){
						$count = (count($C2) * 3) + 2;
					}elseif($count === "0"){
						$count = (count($C2) + 3) + 2;
					}
					
				for($a1 = 0; $a1 < $count; $a1++) {
					//$top++;
					$top111++;
					$top222++;
				}
					if(!strpos($addition, $top)){
						array_push($addition, $top);
					}
				$objPHPExcel->getActiveSheet()->setCellValue($top.'2', $str_ar);
				//$objPHPExcel->getActiveSheet()->setCellValue('C'.$sales, $str_ar);
				$objPHPExcel->getActiveSheet()->setCellValue($top.$sales, intval($str_arr[1]));
				$top++;
				$objPHPExcel->getActiveSheet()->setCellValue($top.$sales, intval($str_a[0]));
					$objPHPExcel->getActiveSheet()->getStyle($top.$sales)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_LKR_SIMPLE);
				$top++;
				$top222++;
				$objPHPExcel->getActiveSheet()->setCellValue($top.$sales, '=IF('.$top222.$sales.'<>"",'.$top111.$sales.'*'.$top222.$sales.',"")');
					
					if(!strpos($addition, $top)){
						array_push($addition, $top);
					}	
				$top++;
				$first = false;
				}
				if(!strpos($C2, $str_ar)){
					array_push($C2,$str_ar);
				}
			}
			$sales++;
		}
		
	}//date equal
	}//end of foreach
	
		$sales1 = $sales - 1;
		$fval = 0;
		for($t = 0; $t < count($addition); $t++)
		{

			$objPHPExcel->getActiveSheet()->setCellValue($addition[$t].$sales, '=SUM('.$addition[$t].'3:'.$addition[$t].$sales1.')');
			
			$fval++;
			if ($fval % 2 == 0) {
				$objPHPExcel->getActiveSheet()->getStyle($addition[$t].'3:'.$addition[$t].$sales)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_LKR_SIMPLE);
				$objPHPExcel->getActiveSheet()->getColumnDimension($addition[$t])->setWidth(13);
				
		$objPHPExcel->getActiveSheet()->getStyle($addition[$t].'2:'.$addition[$t].$sales)->applyFromArray(
			array(
				'borders' => array(
					'left'     => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN
					),
					'right'     => array(
						'style' => PHPExcel_Style_Border::BORDER_MEDIUM
					),
				)
			)
		);
				
			}elseif($fval % 1 == 0){
				
				$inc = $addition[$t];
				for($f = 0; $f < 2; $f++){
					$inc++;
				}
				$objPHPExcel->getActiveSheet()->mergeCells($addition[$t].'2:'.$inc.'2');
				$objPHPExcel->getActiveSheet()->getColumnDimension($addition[$t])->setWidth(5);
				$objPHPExcel->getActiveSheet()->getStyle($addition[$t].'3:'.$addition[$t].$sales)->applyFromArray(
				array(
					'borders' => array(
						'right'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					)
				)
		);
			}
		}
	
		$countf = end($addition);
		$objPHPExcel->getActiveSheet()->getStyle('A2:'.$countf.'2')->applyFromArray(
				array(
					'font'    => array(
						'bold'      => true
					),
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					),
					'borders' => array(
						'top'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						),
						'bottom'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					),
					'fill' => array(
						'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
						'rotation'   => 90,
						'startcolor' => array(
							'argb' => 'F6F6F6'
						),
						'endcolor'   => array(
							'argb' => 'F6F6F6'
						)
					)
				)
		);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$sales.':'.$countf.$sales)->applyFromArray(
				array(
					'font'    => array(
						'bold'      => true
					),
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					),
					'borders' => array(
						'top'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						),
						'bottom'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					),
					'fill' => array(
						'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
						'rotation'   => 90,
						'startcolor' => array(
							'argb' => 'F6F6F6'
						),
						'endcolor'   => array(
							'argb' => 'F6F6F6'
						)
					)
				)
		);
		$objPHPExcel->getActiveSheet()->getStyle('B2:B'.$sales)->applyFromArray(
				array(
					'borders' => array(
						'right'     => array(
							'style' => PHPExcel_Style_Border::BORDER_MEDIUM
						),
						'left'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					)
				)
		);
		$objPHPExcel->getActiveSheet()->getStyle('A2:A'.$sales)->applyFromArray(
				array(
					'borders' => array(
						'left'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					)
				)
		);
	
		$objPHPExcel->getActiveSheet()->getStyle('A'.$sales.':'.$countf.$sales)->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$sales.':'.$countf.$sales)->getFont()->setBold(true);		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);		
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);		
		$objPHPExcel->getActiveSheet()->mergeCells('A1:'.$countf.'1');
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('Calibri');
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$sales, 'Total');
		
		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL);
	
		$objPHPExcel->getActiveSheet()->setTitle('Sales Ledger');
		
	}
		$objPHPExcel->setActiveSheetIndex(0);

		//-------------------------------------Sales Summary--------------------------------------//
		
		/** Error reporting */
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);

		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

		date_default_timezone_set('Asia/Colombo');

		/** Include PHPExcel_IOFactory */
		require_once 'Classes/PHPExcel/IOFactory.php';
	
		// Save Excel 2007 file
		$callStartTime = microtime(true);

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
	
	}elseif(strpos($id, "Invoice")){

		foreach (json_decode($result1) as $set) {
			$b6split = $set->b06;
			$b6spl = str_replace('®', ' | ', $b6split);
			$b6s = str_replace('©', ' x ', $b6spl);
			
			$b6tsplit = $set->b06t;
			$b6tspl = str_replace('®', ' | ', $b6tsplit);
			$b6ts = str_replace('©', ' x ', $b6tspl);
			
			$arraydata[] = [$lan['SERIALNO'] => $set->b01, $lan['CUSTOMER'] => $set->b02, $lan['DATE'] => $set->b04, $lan['TIME'] => $set->b10, $lan['SALESREP'] => $set->b05, $lan['NAMEEN'] => $b6s, $lan['TOTAL'] => $set->b07, $lan['CUSPAID'] => $set->b08, $lan['BALANCE'] => $set->b09, $lan['PAYMETHOD'] => $set->pay];

		}
		
	}elseif(strpos($id, "Return")){

		foreach (json_decode($result1) as $set) {
			$b6split = $set->b06;
			$b6spl = str_replace('®', ' | ', $b6split);
			$b6s = str_replace('©', ' x ', $b6spl);
			
			$b6tsplit = $set->b06t;
			$b6tspl = str_replace('®', ' | ', $b6tsplit);
			$b6ts = str_replace('©', ' x ', $b6tspl);
			
			$arraydata[] = [$lan['SERIALNO'] => $set->b01, $lan['CUSTOMER'] => $set->b02, $lan['DATE'] => $set->b04, $lan['TIME'] => $set->b10, $lan['SALESREP'] => $set->b05, $lan['NAMEEN'] => $b6s, $lan['TOTAL'] => $set->b07, $lan['CUSPAID'] => $set->b08, $lan['BALANCE'] => $set->b09, $lan['PAYMETHOD'] => $set->pay];
			
		}

	}elseif(strpos($id, "GR")){
	
	/*$reference1 = $database->getReference($dpath .'GR');
	$snapshot1  = $reference1->getSnapshot();
	$resultgr = $snapshot1->getValue();
		
		if(!empty($resultgr))
		{
			$table_result_val = array_values($resultgr);
			$table_result_key = array_keys($resultgr);
		}
		
		$records = array();
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
			
			$record = array_combine(array_map(function($el) use ($headers) {
				return $headers[$el];
				}, array_keys($record)), array_values($record));
		
			$grvalue = json_encode($record);
		}	
		foreach (json_decode($result1) as $set) {
		$arraydata[] = ["GR ID" => $set->id, "SKU" => $set->p1, "QTY" => $set->p10, "PURCHASING COST" => $set->p6, "PURCHASING COST PROFIT %" => $set->p7, "PROFIT %" => $set->p11, "UNIT" => $set->p9, "RECORD ENTERED" => $set->p16];
		}*/
		
	}elseif(strpos($id, "Customer")){

		foreach (json_decode($result1) as $set) {
			$arraydata[] = [$lan['CUSTOMER'] => $set->p3, $lan['CONTACT'] => $set->p12, $lan['ADDRESS'] => $set->p15, $lan['ROUTE'] => $set->p13, $lan['BALANCE'] => $set->p14];
		}
		
	}elseif(strpos($id, "Expense")){
		
		foreach (json_decode($result1) as $set) {
			$arraydata[] = [$lan['DATE'] => $set->p18, $lan['EXPENSE'] => $set->p3, $lan['AMOUNT'] => $set->b7];
		}
	}

	
	$final = json_encode($arraydata, JSON_UNESCAPED_UNICODE);
	
	if(!empty($final)){
		if(strpos($id, "Summary")){
			echo json_encode(
            array(
                'code' => 200,
                'message' => 'File '.str_replace('.json', '', $id).' is downloading...', /*'File '.$id.' ready to save',*/
				'file' => str_replace('json', '', $id),
				'record' => 'Summary',
				)
			);
		}else{
			echo json_encode(
            array(
                'code' => 200,
                'message' => 'File '.str_replace('.json', '', $id).' is downloading...', /*'File '.$id.' ready to save',*/
				'file' => str_replace('json', '', $id),
				'record' => $final,
				)
			);
		}
		
	//require_once 'save.php';
	}
	
	
	
	/*if(pathinfo($id) == "json"){
	$path = dirname(__FILE__).'export/'.$id;
	}elseif(pathinfo($id) == "apk"){
		$path = "http://localhost/main_file/".'app/'."APP-APK.apk";
	}
	
		$c = curl_init();
	  curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
	  curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
	  curl_setopt($c, CURLOPT_URL, $path);
	  $contents = curl_exec($c);
	  $err  = curl_getinfo($c,CURLINFO_HTTP_CODE);
	  curl_close($c);
	  if ($contents) return $contents;
	  else return FALSE;*/
	
    /*$filename = basename($path);
    if (!$new_filename) {
        $new_filename = $filename;
    }
    $mime_type = mime_content_type($path);
    header('Content-type: '.$mime_type);
    header('Content-Disposition: attachment; filename="'.$id.'"');
    readfile($path);*/
	

	/*if (file_exists($path)){
    if(readfile($path)){
       //deleted
		echo json_encode(
            array(
                'code' => 200,
                'message' => 'File \'' . $id . '\' deleted successfully.',
            )
        );
    }
	}else{
		 //error
		echo json_encode(
            array(
                'code' => 400,
                'message' => 'File not found',
            )
        );
	}*/
	
}
?>
