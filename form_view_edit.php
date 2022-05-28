<?php
session_start();
$_SESS['page'] = 'Edit form view'.$_GET['tid'];
if(empty($_SESSION))
{
    header('Location: login.php');
}
include_once('conn.php');

if(isset($_GET['id']))
{
	$referencelan      = $database->getReference($dpath.'zinfo/lan');
	$snapshotlan       = $referencelan->getSnapshot();
	$resultlan         = $snapshotlan->getValue();
	
	$lang = $resultlan;
	require_once ("./lan/lang." . $lang . ".php");
	

    $table_id      = $_GET['tid'];
	$reference_tab = $database->getReference($dpath.'tables/' . $table_id);
    $snapshot_tab  = $reference_tab->getSnapshot();
    $tabeldata     = $snapshot_tab->getValue();
	
if($lang == "en"){
	$referencel = $database->getReference($dpath.'tables/' . $table_id.'/name');
	$snapshot1  = $referencel->getSnapshot();
	$tableName = $snapshot1->getValue();
}elseif($lang == "ta"){
	$referencel = $database->getReference($dpath.'tables/' . $table_id.'/nt');
	$snapshot1  = $referencel->getSnapshot();
	$tableName = $snapshot1->getValue();
}elseif($lang == "si"){
	$referencel = $database->getReference($dpath.'tables/' . $table_id.'/ns');
	$snapshot1  = $referencel->getSnapshot();
	$tableName = $snapshot1->getValue();
}
	

    $id        = $_GET['id'];
	if($table_id == "Inventory"){
		$result=0;
		$reference = $database->getReference($dpath. 'products/' . $id);
		$reference1 = $database->getReference($dpath .'categories');
		$snapshot1  = $reference1->getSnapshot();
		$result = $snapshot1->getValue();

		if(!empty($result))
		{
			$table_result_val = array_values($result);
			$table_result_key = array_keys($result);
		}

	}elseif($table_id == "Customer"){
		$reference = $database->getReference($dpath. 'customers/' . $id);
		$reference1 = $database->getReference($dpath .'routes');
		$snapshot1  = $reference1->getSnapshot();
		$result = $snapshot1->getValue();

		if(!empty($result))
		{
			$table_result_val = array_values($result);
			$table_result_key = array_keys($result);
		}

	}elseif($table_id == "Discount"){
		$reference = $database->getReference($dpath. 'Discount/' . $id);
		$reference1 = $database->getReference($dpath .'products');
		$snapshot1  = $reference1->getSnapshot();
		$result = $snapshot1->getValue();

		if(!empty($result))
		{
			$table_result_val = array_values($result);
			$table_result_key = array_keys($result);
		}

	}elseif($table_id == "SalesRep"){
		$reference = $database->getReference($dpath. 'salesreps/' . $id);
		$reference1 = $database->getReference($dpath .'devices');
		$snapshot1  = $reference1->getSnapshot();
		$result = $snapshot1->getValue();

		if(!empty($result))
		{
			$table_result_val = array_values($result);
			$table_result_key = array_keys($result);
		}
		
	}elseif($table_id == "Zinfo"){
		$reference = $database->getReference($dpath. 'Zinfo/' . $id);

	}elseif($table_id == "Category"){
		$reference = $database->getReference($dpath. 'categories/' . $id);

	}elseif($table_id == "Route"){
		$reference = $database->getReference($dpath. 'routes/' . $id);

	}elseif($table_id == "Invoice"){
		$reference = $database->getReference($dpath. 'invoices/' . $id);

	}elseif($table_id == "Return"){
		$reference = $database->getReference($dpath. 'returns/' . $id);

	}elseif($table_id == "GR"){
		$reference = $database->getReference($dpath. 'GR/' . $id);
		$reference1 = $database->getReference($dpath .'products');
		$snapshot1  = $reference1->getSnapshot();
		$result = $snapshot1->getValue();

		if(!empty($result))
		{
			$table_result_val = array_values($result);
			$table_result_key = array_keys($result);
		}

	}elseif($table_id == "Summary"){
		$reference = $database->getReference($dpath. 'summary/' . $id);

	}
	
    $snapshot  = $reference->getSnapshot();
    $formdata  = $snapshot->getValue();

    $tabeldata_deco = json_decode($tabeldata['definition']);
    $formdata_deco  = json_decode($formdata);
	
    $tabel_form_data = array();
    foreach($tabeldata_deco as $key => $tabel_data)
    {
        foreach($formdata_deco as $key1 => $form_data){
            if($form_data->name==$tabel_data->name){
				//category
				if($tabel_data->label == "Category Name"){ $tabel_data->label = $lan['CATNAME']; }
				
				//inventory
				if($tabel_data->label == "SKU"){ $tabel_data->label = $lan['SKU']; }
				if($tabel_data->label == "Name in English<br>"){ $tabel_data->label = $lan['NAMEEN']; }
				if($tabel_data->label == "Name in Tamil<br>"){ $tabel_data->label = $lan['NAMETA']; }
				if($tabel_data->label == "Brand"){ $tabel_data->label = $lan['BRAND']; }
				if($tabel_data->label == "Expire"){ $tabel_data->label = $lan['EXPIRY']; }
				
				//gr
				if($tabel_data->label == "Qty"){ $tabel_data->label = $lan['QTY']; }
				if($tabel_data->label == "Unit"){ $tabel_data->label = $lan['UNIT']; }
				if($tabel_data->label == "Purchasing Cost<br>"){ $tabel_data->label = $lan['PURCOST']; }
				if($tabel_data->label == "Purchasing Cost Profit %<br>"){ $tabel_data->label = $lan['PURCOSTPRO']; }
				if($tabel_data->label == "Profit %<br>"){ $tabel_data->label = $lan['PROFIT']; }
				
				//route
				if($tabel_data->label == "Route"){ $tabel_data->label = $lan['ROUTE']; }
				
				//customers
				if($tabel_data->label == "Mobile"){ $tabel_data->label = $lan['MOBILE']; }
				if($tabel_data->label == "Address"){ $tabel_data->label = $lan['ADDRESS']; }
				if($tabel_data->label == "Balance"){ $tabel_data->label = $lan['BALANCE']; }
				
				//salesrep
				if($tabel_data->label == "Mobile<br>"){ $tabel_data->label = $lan['MOBILE']; }
				if($tabel_data->label == "Joined<br>"){ $tabel_data->label = $lan['JOINED']; }
				
				//invoice
				
				
                $tabel_data->value = $form_data->value;
                $tabel_form_data[] = $tabel_data;
            }
        }
    }
	
    $records = json_encode($tabel_form_data);
	
}

if($table_id == "Inventory"){
//product selected	
		
		$headersf = array();
		$recordf  = array();

		for($i = 0; $i < count($formdata_deco); $i++)
		{
			$headersf[] = $formdata_deco[$i]->name;
			$recordf[]  = $formdata_deco[$i]->value;
		}
	
	$recordf1 = array_combine(array_map(function($el) use ($headersf) {
    return $headersf[$el];
	}, array_keys($recordf)), array_values($recordf));

	$selcat = $recordf1['p02'];
	
}elseif($table_id == "GR" || $table_id == "Discount"){
//GR selected	
	$headersf = array();
	$recordf  = array();

		for($i = 0; $i < count($formdata_deco); $i++)
		{
			$headersf[] = $formdata_deco[$i]->name;
			$recordf[]  = $formdata_deco[$i]->value;
		}
	
	$recordf1 = array_combine(array_map(function($el) use ($headersf) {
    return $headersf[$el];
	}, array_keys($recordf)), array_values($recordf));

	$selcat = $recordf1['p01'];
	
}elseif($table_id == "Customer"){
//GR selected	
	$headersf = array();
	$recordf  = array();

		for($i = 0; $i < count($formdata_deco); $i++)
		{
			$headersf[] = $formdata_deco[$i]->name;
			$recordf[]  = $formdata_deco[$i]->value;
		}
	
	$recordf1 = array_combine(array_map(function($el) use ($headersf) {
    return $headersf[$el];
	}, array_keys($recordf)), array_values($recordf));

	$selcat = $recordf1['p13'];
	
}elseif($table_id == "SalesRep"){
//GR selected	
	$headersf = array();
	$recordf  = array();

		for($i = 0; $i < count($formdata_deco); $i++)
		{
			$headersf[] = $formdata_deco[$i]->name;
			$recordf[]  = $formdata_deco[$i]->value;
		}
	
	$recordf1 = array_combine(array_map(function($el) use ($headersf) {
    return $headersf[$el];
	}, array_keys($recordf)), array_values($recordf));

	$selcat = $recordf1['de01'];
	
}
?>
<script>
	console.log('<?=$selcat ?>');
</script>

<!DOCTYPE html>
<head>
    <?php include './layout/head.php';?>
</head>

<body>
<?php include './layout/sidebar.php';?>
<!-- Main content -->
<div class="main-content" id="panel">
    <!-- Topnav -->
    <?php include './layout/topnav.php';?>
    <!-- Header -->
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <h6 class="h2 text-white d-inline-block mb-0"><?php echo $lan['EDITREC']; ?></h6>
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="dashboard.php"><i class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="view_table.php?id=<?= $table_id ?>"><?php echo $tableName;?></a></li>
                                <li class="breadcrumb-item active" aria-current="page"><?php echo $lan['EDITREC']; ?></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header border-0">
                        <h3 class="mb-0"><?php echo $lan['UPDATEREC']; ?></h3>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="form-horizontal" >
                            <div class="form-group">
								
								<?php if($table_id == "GR" || $table_id == "Discount"){ 
	
									$records1 = array();
									foreach($table_result_val as $key => $record)
									{
										$record_deco = json_decode($record);
										?>
										<?php
										$headers = array();
										$record  = array();

										for($i = 0; $i < count($record_deco); $i++)
										{
											$headers[] = $record_deco[$i]->name;
											$record[]  = $record_deco[$i]->value;
										}
										$records1[] = $record;
										?>
									<?php }
								?>
										
								<div class="rendered-form"><div class="fb-select form-group field-gr-pro">
									<label for="gr-pro" class="fb-select-label"><?php echo $lan['SELPRODUCT']; ?><br></label>
									<select class="form-control" name="gr-pro-gr" id="gr-pro-gr">
										<option value="<?=$selcat ?>" selected="selected"> <?=$selcat ?></option>
							<?php for($j = 0; $j < count($records1); $j++) { ?>
										
                                    <?php for($k = 0; $k < (count($records1[$j]) < 2 ? count($records1[$j]) : 2); $k++) { ?>
                                        <td><?= $records1[$j][$k] ?></td>
                                    <?php }

                                    ?>
                                        <option value="<?=$table_result_key[$j] ?>" > <?=$table_result_key[$j]."/".$records1[$j][$k] ?></option>
                            <?php } ?>
										
										
									</select>
								</div></div> 
								
								<?php
								} elseif($table_id == "Inventory"){ 
	
									 $records1 = array();
									foreach($table_result_val as $key => $record)
									{
										$record_deco = json_decode($record);
										?>
										<?php
										$headers = array();
										$record  = array();

										for($i = 0; $i < count($record_deco); $i++)
										{
											$headers[] = $record_deco[$i]->name;
											$record[]  = $record_deco[$i]->value;
										}
										$records1[] = $record;
										?>
									<?php }
								?>
										
								<div class="rendered-form"><div class="fb-select form-group field-gr-pro">
									<label for="gr-pro" class="fb-select-label"><?php echo $lan['SELCAT']; ?><br></label>
									<select class="form-control" name="gr-pro-in" id="gr-pro-in" >
										<option value="<?=$selcat ?>" selected="selected"> <?=$selcat ?></option>
										
							<?php for($j = 0; $j < count($records1); $j++) { ?>
										
                                    <?php for($k = 0; $k < (count($records1[$j]) < 0 ? count($records1[$j]) : 0); $k++) { ?>
                                        <td><?= $records1[$j][$k] ?></td>
										<?php 
                                     }

                                    ?>
										<option value="<?=$records1[$j][$k] ?>" > <?=$records1[$j][$k] ?></option>
                            <?php } ?>
										
										
									</select>
								</div></div> 
	
								<?php }elseif($table_id == "Customer"){ 
	
									 $records1 = array();
									foreach($table_result_val as $key => $record)
									{
										$record_deco = json_decode($record);
										?>
										<?php
										$headers = array();
										$record  = array();

										for($i = 0; $i < count($record_deco); $i++)
										{
											$headers[] = $record_deco[$i]->name;
											$record[]  = $record_deco[$i]->value;
										}										
										$records1[] = $record;
										?>
									<?php }
								?>
										
								<div class="rendered-form"><div class="fb-select form-group field-gr-pro">
									<label for="gr-pro" class="fb-select-label"><?php echo $lan['SELROUTE']; ?><br></label>
									<select class="form-control" name="gr-pro-ro" id="gr-pro-ro" >
										<option value="<?=$selcat ?>" selected="selected"> <?=$selcat ?></option>
										
							<?php for($j = 0; $j < count($records1); $j++) { ?>
										
                                    <?php for($k = 0; $k < (count($records1[$j]) < 0 ? count($records1[$j]) : 0); $k++) { ?>
                                        <td><?= $records1[$j][$k] ?></td>
										<?php 
                                     }

                                    ?>
										<option value="<?=$records1[$j][$k] ?>" > <?=$records1[$j][$k] ?></option>
                            <?php } ?>
										
										
									</select>
								</div></div> 
	
								<?php }elseif($table_id == "SalesRep"){ 
	
									 $records1 = array();
									foreach($table_result_val as $key => $record)
									{
										$record_deco = json_decode($record);
										?>
										<?php
										$headers = array();
										$record  = array();

										for($i = 0; $i < count($record_deco); $i++)
										{
											$headers[] = $record_deco[$i]->name;
											$record[]  = $record_deco[$i]->value;
										}										
										$records1[] = $record;
										?>
									<?php }
								?>
										
								<div class="rendered-form"><div class="fb-select form-group field-gr-pro">
									<label for="gr-pro" class="fb-select-label"><?php echo "Select Device"; ?><br></label>
									<select class="form-control" name="sr-pro-ro" id="sr-pro-ro" >
										<option value="<?=$selcat ?>" selected="selected"> <?=$selcat ?></option>
										
							<?php for($j = 0; $j < count($records1); $j++) { ?>
										
                                    <?php for($k = 0; $k < (count($records1[$j]) < 0 ? count($records1[$j]) : 0); $k++) { ?>
                                        <td><?= $records1[$j][$k] ?></td>
										<?php 
                                     }

                                    ?>
										<option value="<?=$records1[$j][$k] ?>" > <?=$records1[$j][$k] ?></option>
                            <?php } ?>
										
										
									</select>
								</div></div> 
	
								<?php }
								?>
								
                                <form class="fb-render" id="fb-render"></form>
								
								<?php if($table_id == "Inventory"){ 
								
								$fileurl = 'https://pos.jkdesigns.app/demo/uploads/'.$id.'.jpeg';	
								$result=get_headers($fileurl);
	
								if(strpos($result[0],"200 OK")) {
									//$exists = false; ?>
									<span id="pip" class="pip" style="display: inline-block;margin: 10px 10px 0 0">
								<img id="image" class="image" height="100" style="padding: 1px;" src="https://pos.jkdesigns.app/demo/uploads/<?= $id ?>.jpeg" title="<?= $id ?>">
									<span id="removeURL" class="removeURL" style="display: block;background: #444;cursor: pointer;color: white;border: 1px solid black;text-align: center;">Remove image</span></span>
								
								<?php } else {
									//$exists = true; ?>
								<input type="file" id="files" name="files" style="display: block;padding-top:5;" accept="image/*" onchange="readURL(this)">
									<img id="img-upload" style="display: none;">
									<img id="show" style="display: none;">
								
								
										
								<?php } 
								} ?>
								
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">

                        <div class="form-group text-center">
							<?php if($table_id == "SalesRep"){ ?>
							<button type="button" id="get-device-data" class="btn btn-success"><?php echo "Unpair Device" ?></button>
							<?php } ?>
                            <button type="button" id="get-table-data" class="btn btn-success"><?php echo $lan['UPDATEBTN']; ?></button>
							<button type="button" id="download-data" class="btn btn-success"><?php echo "DOWNLOAD" ?></button>
                            <a href="view_table.php?id=<?= $table_id ?>" class="btn btn-info"><?php echo $lan['CANCELBTN']; ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <?php include './layout/footer.php';
		
		if($table_id == "Inventory"){
			$nav = "update_table_data.php";

		}elseif($table_id == "Customer"){
			$nav = "customer_update_table_data.php";

		}elseif($table_id == "SalesRep"){
			$nav = "salesrep_update_table_data.php";

		}elseif($table_id == "Zinfo"){
			$nav = "zinfo_update_table_data.php";

		}elseif($table_id == "Category"){
			$nav = "cat_update_table_data.php";

		}elseif($table_id == "Route"){
			$nav = "route_update_table_data.php";

		}elseif($table_id == "GR"){
			$nav = ""; //gr_update_table_data.php

		}elseif($table_id == "Discount"){
			$nav = "discount_update_table_data.php";

		}
															   
		?>
		
		<script src="./assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
		<!--script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script-->
		<script>
		  // Your web app's Firebase configuration
		  var firebaseConfig = {
			apiKey: "AIzaSyCRk2bDVAJGEVWc9QlB8JHvEo7Ae5Q7CfU",
			authDomain: "sale-f4270.firebaseapp.com",
			databaseURL: "https://sale-f4270.firebaseio.com",
			projectId: "sale-f4270",
			storageBucket: "sale-f4270.appspot.com",
			messagingSenderId: "116708826225",
			appId: "1:116708826225:web:e04e903d905c50ba07cfd0"
		  };
		  // Initialize Firebase
		  firebase.initializeApp(firebaseConfig);
		</script>
		
        <script>
			
				$(document).on('change',':text,textarea,input[type=text]', function () {
					
					if (this.value.match(/[^a-zA-Z0-9\-\+\.\*\|\~\!\#\_\@\/ ]/g)) {
					  $("<span id=\"rtext\"" + "class=\"rtext\"" + "style=\"display: block;cursor: pointer;padding: 5;background: #444;color: white;border: 1px solid black;text-align: center;\">Symbols \\ [ ] { } ` ' \" \, \: \; \= \< \> are not allowed</span>" +
						"</span>").insertBefore('#get-table-data');
						$(".rtext").click(function(){
						$('#rtext').remove();
							this.value = this.value.replace(/[^a-zA-Z0-9\-\+\.\*\|\~\!\#\_\@\/ ]/g, '');
					  });
						this.value = this.value.replace(/[^a-zA-Z0-9\-\+\.\*\|\~\!\#\_\@\/ ]/g, '');
				  }
				});
			
		
var img = $('#img-upload')[0];
var show = $('#show')[0];
$('#download-data').hide();
			
function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
	  
    reader.onload = function(e) {
      debugger;
      $('#img-upload').attr('src', e.target.result);
      img.onload = function() {
			var MAX_WIDTH = 300;
			var MAX_HEIGHT = 300;
			var width = img.width;
			var height = img.height;

		  if (height > MAX_HEIGHT || width > MAX_WIDTH) {
			  
			if (width > height) {
			  if (width > MAX_WIDTH) {
				height *= MAX_WIDTH / width;
				width = MAX_WIDTH;
			  }
			} else {
			  if (height > MAX_HEIGHT) {
				width *= MAX_HEIGHT / height;
				height = MAX_HEIGHT;
			  }
			}
			  $('#get-table-data').hide();
		  	$('#download-data').show();
		  }else if(height <= MAX_HEIGHT && width <= MAX_WIDTH){
			  $('#get-table-data').show();
		  	$('#download-data').hide();
		  }else{
			  $('#get-table-data').show();
		  	$('#download-data').hide();
		  }
			var canvas = document.createElement("canvas");
			canvas.width = width;
			canvas.height = height;
			canvas.getContext("2d").drawImage(this, 0, 0, width, height); 

			// Get dataURL of resized image from canvas 
		  show.src = canvas.toDataURL('image/jpeg', 1.0);
		  $("#download-data").attr("href", "#").attr("data-href", canvas.toDataURL('image/jpeg')).attr("download", "Img300x300.jpeg").attr("onclick", "forceDownload(this)");
      }

    }
    reader.readAsDataURL(input.files[0]);
  }
}
			function forceDownload(link){
				var url = link.getAttribute("data-href");
				var fileName = link.getAttribute("download");
				link.innerText = "Working...";
				var xhr = new XMLHttpRequest();
				xhr.open("GET", url, true);
				xhr.responseType = "blob";
				xhr.onload = function(){
					var urlCreator = window.URL || window.webkitURL;
					var imageUrl = urlCreator.createObjectURL(this.response);
					var tag = document.createElement('a');
					tag.href = imageUrl;
					tag.download = fileName;
					document.body.appendChild(tag);
					tag.click();
					document.body.removeChild(tag);
					link.innerText="Image Downloaded";
				}
				xhr.send();
				
			}
			
			$(document).ready(function() {
			  if (window.File && window.FileList && window.FileReader) {
				$("#files").on("change", function(e) {
				  var files = e.target.files,
					filesLength = files.length;
				  for (var i = 0; i < filesLength; i++) {
					var f = files[i]
					var fileReader = new FileReader();
					fileReader.onload = (function(e) {
					  var file = e.target;
					  $("<span class=\"pip\"" + "style=\"display: inline-block;margin: 10px 10px 0 0\">" +
						"<img class=\"image\"" + "height=\"100\"" + "style=\"padding: 1px;\"" + "src=\"" + e.target.result + "\" title=\"" + document.getElementById('p01').value+"."+"jpeg" + "\"/>" +
						"<br/><span class=\"remove\"" + "style=\"display: block;background: #444;cursor: pointer;color: white;border: 1px solid black;text-align: center;\">Remove image</span>" +
						"</span>").insertBefore("#files");
						$('#download-data').text("Download");
					  $(".remove").click(function(){
						$(this).parent(".pip").remove();
						$('#show').attr('src','');
						$('#files').val('');
						$('#download-data').hide();
						$('#get-table-data').show();
					  });

					});
					fileReader.readAsDataURL(f);
				  }
				});
			  } else {
				alert("Your browser doesn't support to File API")
			  }
			});
			
            $(document).on('click', '#get-table-data', function () {
				
				$("#get-table-data").attr("disabled", true);
                var tableData = [];
                var formData = JSON.stringify(jQuery('#fb-render').serializeArray());
				
                var id = '<?=$id?>';
                var tid = '<?=$table_id?>';
				
				<?php if($table_id == "Inventory"){ ?>
				var selected = document.getElementById("gr-pro-in").value;
				
				<?php } elseif($table_id == "GR" || $table_id == "Discount"){ ?>
				var selected = document.getElementById("gr-pro-gr").value;
				
				<?php } elseif($table_id == "Customer"){ ?>
				var selected = document.getElementById("gr-pro-ro").value;
				
				<?php } elseif($table_id == "SalesRep"){ ?>
				var selected = document.getElementById("sr-pro-ro").value;
				<?php } ?>
				
				console.log(id);
				console.log(tid);
				console.log(formData);
				
                tableData.push(id);
                tableData.push(tid);
                tableData.push(formData);
				
				<?php if($table_id == "Inventory"){ ?>
				tableData.push(selected);
				console.log(selected);
				
				<?php }elseif($table_id == "GR" || $table_id == "Discount"){ ?>
				tableData.push(selected);
				console.log(selected);
				
				<?php }elseif($table_id == "Customer"){ ?>
				tableData.push(selected);
				console.log(selected);
				
				<?php } elseif($id == "SalesRep"){ ?>
				tableData.push(selected);
				console.log(selected);
				<?php } ?>
				
                $.ajax({
                    type: "POST",
                    url: '<?=$nav?>',
                    dataType: 'json',
                    data: {json: JSON.stringify(tableData)},
                    success: function (data) {
                        $("#get-table-data").attr("disabled", false);
                        if (data.code == 100) {
                            swal({
                                title: "<?php echo $lan['NEWDATA']; ?>",
                                text: "<?php echo $lan['NEWDATAMSG']; ?>",
                                type: "success",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-success"
                            });
                            setTimeout(function () {
                                window.location = "view_table.php?id=" + tid;
                            }, 2000);
							
                        } else if (data.code == 300) {
                            swal({
                                title: "Duplicate",
                                text: "This data already in database!",
                                type: "error",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-success"
                            });
							$("#get-table-data").attr("disabled", false);
							
						}else if (data.code == 200) {
							<?php if($table_id == "Inventory"){ 
							if(!strpos($result[0],"200 OK")){
							?>
							
							const fname = document.getElementById("p01").value+".jpeg";
							const url = 'process.php';
							const files = document.querySelector('[type=file]').files;
							const formDatas = new FormData();

							for (let i = 0; i < files.length; i++) {
								let file = files[i];

								formDatas.append('files[]', file);
								formDatas.append('fname', fname);
							}

							fetch(url, {
								method: 'POST',
								body: formDatas
							}).then(response => {
								return response.text();
							}).then(data => {
								console.log(data);
							});
						
							//get your select image
							//var image=document.getElementById("hidden").files[0];
							/*var image=document.getElementById("files").files[0];
							if( document.getElementById("files").files.length == 1 ){
							//now get your image name
							var imageName=document.getElementById("p01").value+".jpeg";
							//firebase  storage reference
							//it is the path where your image will store
							var storageRef=firebase.storage().ref('products/'+imageName);
							//upload image to selected storage reference

							var uploadTask=storageRef.put(image);

							uploadTask.on('state_changed',function (snapshot) {
								//observe state change events such as progress , pause ,resume
								//get task progress by including the number of bytes uploaded and total
								//number of bytes
								var progress=(snapshot.bytesTransferred/snapshot.totalBytes)*100;
								console.log("upload is " + progress +" done");
							},function (error) {
								//handle error here
								console.log(error.message);
								swal({
                                title: "Oops",
                                text: "Image not uploaded!",
                                type: "error",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-success"
                            });
							},function () {
							   //handle successful uploads on complete

								uploadTask.snapshot.ref.getDownloadURL().then(function (downlaodURL) {
									//get your upload image url here...
									console.log(downlaodURL);
								});
							});
							}else{
								$("<span id=\"rtext\"" + "class=\"rtext\"" + "style=\"display: block;cursor: pointer;padding: 5;background: #444;color: white;border: 1px solid black;text-align: center;\">Image not selected.</span>" +
								"</span>").insertBefore('#get-table-data');
								$(".rtext").click(function(){
								$('#rtext').remove();
					  	});
						}*/
						<?php 
							}else{
							//TODO
						}
							   } ?>
                            swal({
                                title: "<?php echo $lan['SUCCESS']; ?>",
                                text: "<?php echo $lan['UPDATESUC']; ?>",
                                type: "success",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-success"
                            });
                            setTimeout(function () {
                                window.location = "view_table.php?id=" + tid;
                            }, 2000);

                        } else {
                            swal({
                                title: "<?php echo $lan['ERR']; ?>",
                                text: "<?php echo $lan['UPDATEFAIL']; ?>",
                                type: "error",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-success"
                            });
                            $("#get-table-data").attr("disabled", false);
                        }
                    }
                });
            });
			
			$(document).on('click', '#removeURL', function () {
				
                var id = '<?=$id?>';
                var tid = '<?=$table_id?>';
				var tableData = [];
                tableData.push(id);
                tableData.push(tid);
				
                $.ajax({
                    type: "POST",
                    url: "deleteImage.php",
                    dataType: 'json',
                    data: {json: JSON.stringify(tableData)},
                    success: function (data) {
                        if (data.code == 200) {
                            swal({
                                title: "<?php echo "Removed" ?>",
                                text: "<?php echo "Image removed Successfully" ?>",
                                type: "success",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-success"
                            });
                            $('.show').remove();
							$('.image').remove();
							$('.removeURL').remove();
							$('#files').val('');
							setTimeout(function () {
                                window.location = "form_view_edit.php?tid=" + tid + "&id=" + id;
                            }, 500);
                        } else {
                            swal({
                                title: "<?php echo $lan['ERR']; ?>",
                                text: "<?php echo $lan['UPDATEFAIL']; ?>",
                                type: "error",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-success"
                            });
                        }
                    }
                });
							//var image=document.getElementById("files").files[0];
							//now get your image name
							/*var imageName=".jpeg";
							//firebase  storage reference
							//it is the path where your image will store
							var storageRef=firebase.storage().ref('products/'+imageName);
							//upload image to selected storage reference

							var uploadTask=storageRef.delete();

							/*uploadTask.on('state_changed',function (snapshot) {
								//observe state change events such as progress , pause ,resume
								//get task progress by including the number of bytes uploaded and total
								//number of bytes
								var progress=(snapshot.bytesTransferred/snapshot.totalBytes)*100;
								console.log("upload is " + progress +" done");
							},function (error) {
								//handle error here
								console.log(error.message);
								swal({
                                title: "Oops",
                                text: "Image not uploaded!",
                                type: "error",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-success"
                            });
							},function () {
							   //handle successful uploads on complete

								uploadTask.snapshot.ref.getDownloadURL().then(function (downlaodURL) {
									//get your upload image url here...
									console.log(downlaodURL);
								});
							});*/
            });
			
			$(document).on('click', '#get-device-data', function () {
				$("#get-device-data").attr("disabled", true);
                var tableData = [];
                var formData = JSON.stringify(jQuery('#fb-render').serializeArray());
				
                var id = '<?=$id?>';
                var tid = '<?=$table_id?>';
				
				<?php if($table_id == "SalesRep"){ ?>
				var selected = document.getElementById("sr-pro-ro").value;
				<?php } ?>
				
				console.log(id);
				console.log(tid);
				console.log(formData);
				
                tableData.push(id);
                tableData.push(tid);
                tableData.push(formData);
				
				<?php if($table_id == "SalesRep"){ ?>
				tableData.push(selected);
				console.log(selected);
				<?php } ?>
				
                $.ajax({
                    type: "POST",
                    url: "unpair_update_table_data.php",
                    dataType: 'json',
                    data: {json: JSON.stringify(tableData)},
                    success: function (data) {
                        $("#get-device-data").attr("disabled", false);
                        if (data.code == 100) {
                            swal({
                                title: "<?php echo $lan['NEWDATA']; ?>",
                                text: "<?php echo $lan['NEWDATAMSG']; ?>",
                                type: "success",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-success"
                            });
                            setTimeout(function () {
                                window.location = "form_view_edit.php?tid=" + tid + "&id=" + id;
                            }, 2000);
							
                        } else if (data.code == 300) {
                            swal({
                                title: "Duplicate",
                                text: "This data already in database!",
                                type: "error",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-success"
                            });
							$("#get-device-data").attr("disabled", false);
							
						}else if (data.code == 200) {
                            swal({
                                title: "<?php echo $lan['SUCCESS']; ?>",
                                text: "<?php echo $lan['UPDATESUC']; ?>",
                                type: "success",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-success"
                            });
                            setTimeout(function () {
                                window.location = "form_view_edit.php?tid=" + tid + "&id=" + id;
                            }, 2000);

                        } else {
                            swal({
                                title: "<?php echo $lan['ERR']; ?>",
                                text: "<?php echo $lan['UPDATEFAIL']; ?>",
                                type: "error",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-success"
                            });
                            $("#get-device-data").attr("disabled", false);
                        }
                    }
                });
            });
			
            jQuery(function ($) {
                formRenderOpts = {
                    formData: <?= $records?>
                };
                var renderedForm = $('#fb-render');
                renderedForm.formRender(formRenderOpts);
                $('#view_editor').html(renderedForm.html());


                //apply theme style of checkbox
                $('input[type="checkbox"]').each(function() {
                    $(this).addClass('custom-control-input');
                    $(this).parent().addClass('custom-control custom-checkbox mb-3');
                    $(this).parent().find('label').addClass('custom-control-label');

                    if($(this). prop("checked") == true)
                    {
                        $(this). prop("checked",true);
                    }else{
                        $(this). prop("checked",false);
                    }
                });

                //apply theme style of checkbox
                $('input[type="radio"]').each(function() {
                    $(this).addClass('custom-control-input');
                    $(this).parent().addClass('custom-control custom-radio');
                    $(this).parent().find('label').addClass('custom-control-label')
                });

                //change inout type date to datepicker
                $('input[type="date"]').each(function() {
                    $(this).addClass('datepicker');
                    $(this).attr('type','text');
                    $(this).attr('readonly','true');
                });

                var Datepicker = (function() {
                    // Variables
                    var $datepicker = $('.datepicker');

                    // Methods
                    function init($this) {
                        var options = {
                            disableTouchKeyboard: true,
                            autoclose: false,
                            clearBtn:true,
                            todayBtn: true,
                        };
                        $this.datepicker(options);
                    }
                    // Events
                    if ($datepicker.length) {
                        $datepicker.each(function() {
                            init($(this));
                        });
                    }

                })();
            });

        </script>
</body>
</html>