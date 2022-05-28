<?php
session_start();
$_SESS['page'] = 'Add form view'.$_GET['id'];
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
	
	$id            = $_GET['id'];
	if($id == "GR" || $id == "GRAdd" || $id == "GRChange"){
		$reference     = $database->getReference($dpath.'tables/' . "GR");
	}else{
		$reference     = $database->getReference($dpath.'tables/' . $id);
	}
	
    $snapshot      = $reference->getSnapshot();
    $tabeldata     = $snapshot->getValue();
    $table_records = $tabeldata['count'] + 1;
    $table_records_id = $tabeldata['id'] + 1;
	
if($lang == "en"){
	$reference = $database->getReference($dpath.'tables/' . $id.'/name');
	$snapshot1  = $reference->getSnapshot();
	$tableName = $snapshot1->getValue();
}elseif($lang == "ta"){
	$reference = $database->getReference($dpath.'tables/' . $id.'/nt');
	$snapshot1  = $reference->getSnapshot();
	$tableName = $snapshot1->getValue();
}elseif($lang == "si"){
	$reference = $database->getReference($dpath.'tables/' . $id.'/ns');
	$snapshot1  = $reference->getSnapshot();
	$tableName = $snapshot1->getValue();
}
	
//$values_records = [];
//$key_records = [];
	
if($id == "GR" || $id == "1" || $id == "2" || $id == "GRAdd" || $id == "GRChange" || $id == "Discount"){
	$reference1 = $database->getReference($dpath .'products');//products
	$snapshot1  = $reference1->getSnapshot();
	$result = $snapshot1->getValue();
}elseif($id == "Inventory"){
	$reference1 = $database->getReference($dpath .'categories');
	$snapshot1  = $reference1->getSnapshot();
	$result = $snapshot1->getValue();
}elseif($id == "Customer"){
	$reference1 = $database->getReference($dpath .'routes');
	$snapshot1  = $reference1->getSnapshot();
	$result = $snapshot1->getValue();
}elseif($id == "SalesRep"){
	$reference1 = $database->getReference($dpath .'devices');
	$snapshot1  = $reference1->getSnapshot();
	$result = $snapshot1->getValue();
}

if($id == "GR" || $id == "1" || $id == "2"){
	$title = "GR New";
}elseif($id == "GRAdd"){
	$title = "GR Add More Products";
}elseif($id == "GRChange"){
	$title = "GR Change Product Price";
}
	
if(!empty($result))
{
    $table_result_val = array_values($result);
    $table_result_key = array_keys($result);
}

$tabeldata_deco = json_decode($tabeldata['definition']);

	$tabel_form_data = array();
	foreach($tabeldata_deco as $key => $tabel_data)
	{
		if(isset($tabel_data)){
			
			//category
			if($tabel_data->label == "Category Name"){ $tabel_data->label = $lan['CATNAME']; }

			//inventory
			if($tabel_data->label == "SKU"){ $tabel_data->label = $lan['SKU']; }
			if($tabel_data->label == "Name"){ $tabel_data->label = $lan['NAME']; }
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
				
			
			$tabel_form_data[] = $tabel_data;
		}
	} 
	
	$recordstable = json_encode($tabel_form_data);

}
?>

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
							<h6 class="h2 text-white d-inline-block mb-0"><?=$title; ?></h6>
                        
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="dashboard.php"><i class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="view_table.php?id=<?= $id ?>"><?php echo $tableName;?></a></li>
                                <li class="breadcrumb-item active" aria-current="page"><?php echo $lan['CRECORD']; ?></li>
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
                        <h3 class="mb-0"><?php echo $lan['NEWRECORD']; ?></h3>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="form-horizontal" >
                            <div class="form-group">
								
								<?php if($id == "GR" || $id == "GRAdd" || $id == "GRChange" || $id == "1" || $id == "2" || $id == "Discount"){ 
	
									$records = array();
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
										$records[] = $record;
										?>
									<?php } ?>
								
								
								<form action="#" method="post"> <div class="rendered-form"><div class="fb-select form-group field-gr-pro">
									<label for="gr-pro" class="fb-select-label"><?php echo $lan['SELPRODUCT']; ?><br></label>
									<select class="form-control" name="gr-pro-gr" id="gr-pro-gr" >
										
							<?php for($j = 0; $j < count($records); $j++) { ?>
										
                                    <?php for($k = 0; $k < (count($records[$j]) < 2 ? count($records[$j]) : 2); $k++) { ?>
                                        <td><?= $records[$j][$k] ?></td>
                                    <?php }

                                    ?>
                                        <option><?= $table_result_key[$j]."/".$records[$j][$k] ?></option><!--."/".$records[$j][$k]-->
										
                            <?php } ?>
										
										
									</select>
								</div></div> 
								</form>
								
							<?php	} elseif($id == "Inventory"){ 
	
									 $records = array();
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
										$records[] = $record;
										?>
									<?php }
								?>
										
								<div class="rendered-form"><div class="fb-select form-group field-gr-pro">
									<label for="gr-pro" class="fb-select-label"><?php echo $lan['SELCAT']; ?><br></label>
									<select class="form-control" name="gr-pro-in" id="gr-pro-in">
								<?php 
							for($j = 0; $j < count($records); $j++) { ?>
										
                                    <?php for($k = 0; $k < (count($records[$j]) < 0 ? count($records[$j]) : 0); $k++) { ?>
                                        <td><?= $records[$j][$k] ?></td>
                                    <?php }

                                    ?>
                                        <option value="<?=$records[$j][$k] ?>"><?= $records[$j][$k] ?></option>
                            <?php } ?>
										
										
									</select>
								</div></div> 
	
								<?php } elseif($id == "Customer"){ 
	
									 $records = array();
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
										$records[] = $record;
										?>
									<?php }
								?>
										
								<div class="rendered-form"><div class="fb-select form-group field-gr-pro">
									<label for="gr-pro" class="fb-select-label"><?php echo $lan['SELROUTE']; ?><br></label>
									<select class="form-control" name="gr-pro-ro" id="gr-pro-ro">
										
							<?php for($j = 0; $j < count($records); $j++) { ?>
										
                                    <?php for($k = 0; $k < (count($records[$j]) < 0 ? count($records[$j]) : 0); $k++) { ?>
                                        <td><?= $records[$j][$k] ?></td>
                                    <?php }

                                    ?>
                                        <option value="<?=$records[$j][$k] ?>"><?= $records[$j][$k] ?></option>
                            <?php } ?>
										
										
									</select>
								</div></div> 
	
								<?php }elseif($id == "SalesRep"){ 
	
									 $records = array();
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
										$records[] = $record;
										?>
									<?php }
								?>
										
								<div class="rendered-form"><div class="fb-select form-group field-gr-pro">
									<label for="gr-pro" class="fb-select-label"><?php echo "Select Device"; ?><br></label>
									<select class="form-control" name="sr-pro-ro" id="sr-pro-ro">
										<option value="" selected="selected"></option>
										
							<?php for($j = 0; $j < count($records); $j++) { ?>
										
                                    <?php for($k = 0; $k < (count($records[$j]) < 0 ? count($records[$j]) : 0); $k++) { ?>
                                        <td><?= $records[$j][$k] ?></td>
                                    <?php }

                                    ?>
                                        <option value="<?=$records[$j][$k] ?>"><?= $records[$j][$k] ?></option>
                            <?php } ?>
										
										
									</select>
								</div></div> 
	
								<?php }
								?>
								
								
                                <form class="fb-render" id="fb-render"></form>
								
								<?php if($id == "Inventory"){ ?>
								
								<input type="file" id="files" name="files" style="display: block;padding-top:5;" accept="image/*" onchange="readURL(this)">
								<img id="img-upload" style="display: none;">
								<img id="show" style="display: none;">
								<?php } ?>
								
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">

                        <div class="form-group text-center">
							<button type="button" id="get-table-data" class="btn btn-success"><?php echo $lan['SAVEBTN']; ?></button>
							<button type="button" id="download-data" class="btn btn-success"><?php echo "DOWNLOAD" ?></button>
                            <a href="view_table.php?id=<?= $id ?>" class="btn btn-info"><?php echo $lan['CANCELBTN']; ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <?php include './layout/footer.php';
		
		if($id == "Inventory"){
			$nav = "save_table_data.php";

		}elseif($id == "Customer"){
			$nav = "customer_save_table_data.php";

		}elseif($id == "SalesRep"){
			$nav = "salesrep_save_table_data.php";

		}elseif($id == "Zinfo"){
			$nav = "zinfo_save_table_data.php";

		}elseif($id == "Category"){
			$nav = "cat_save_table_data.php";

		}elseif($id == "GR"){
			$nav = "gr_save_table_data.php";

		}elseif($id == "GRChange"){
			$nav = "grchange_save_table_data.php";

		}elseif($id == "Route"){
			$nav = "route_save_table_data.php";

		}elseif($id == "Summary"){
			$nav = "summary_save_table_data.php";

		}elseif($id == "Discount"){
			$nav = "discount_save_table_data.php";

		}/*elseif($id == "1"){
			$nav = "1.php";

		}elseif($id == "2"){
			$nav = "2.php";

		}*/?>
		
        <script src="./assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
		<script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script>		
        
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
		  	var re = /(\.jpeg)$/i;
		  
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
		  $("#download-data").attr("href", "#").attr("data-href", canvas.toDataURL('image/jpeg')).attr("download", document.getElementById('p01').value+".jpeg").attr("onclick", "forceDownload(this)");
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
                //$("#get-table-data").attr("disabled", true);
                var tableData = [];
                var formData = JSON.stringify(jQuery('#fb-render').serializeArray());
				
				<?php if($id == "GRChange"){ ?>
                var id = "GR";
				<?php }else{ ?>
				var id = '<?=$id?>';
				<?php } ?>
				
                var table_records = '<?=$table_records?>';
				var $table_records_id = '<?=$table_records_id?>';
				
				<?php if($id == "Inventory"){ ?>
				var selected = document.getElementById("gr-pro-in").value;
				<?php } elseif($id == "GR" || $id == "GRAdd" || $id == "GRChange" || $id == "1" || $id == "2" || $id == "Discount"){ ?>
				var selected = document.getElementById("gr-pro-gr").value;
				var grpro = selected.split('/');//selected.split('/')
				<?php } elseif($id == "Customer"){ ?>
				var selected = document.getElementById("gr-pro-ro").value;
				<?php } elseif($id == "SalesRep"){ ?>
				var selected = document.getElementById("sr-pro-ro").value;
				<?php } ?>
				
				console.log(table_records);
				console.log(id);
				console.log(formData);
				
                tableData.push(table_records);
                tableData.push(id);
                tableData.push(formData);
				<?php if($id == "Inventory"){ ?>
				tableData.push(selected);
				console.log(selected);
				<?php } elseif($id == "GR" || $id == "GRAdd" || $id == "GRChange" || $id == "1" || $id == "2" || $id == "Discount"){ ?>
				tableData.push(grpro[0]);//grpro[0]
				console.log(grpro[0]);//grpro[0]
				<?php } elseif($id == "Customer"){ ?>
				tableData.push(selected);
				console.log(selected);
				<?php } elseif($id == "SalesRep"){ ?>
				tableData.push(selected);
				console.log(selected);
				<?php } ?>
				tableData.push($table_records_id);
				console.log($table_records_id);
				
                $.ajax({
                    type: "POST",
                    url: '<?=$nav?>',
                    dataType: 'json',
                    data: {json: JSON.stringify(tableData)},
                    success: function (data) {
                        $("#get-table-data").attr("disabled", false);
                        if (data.code == 300) {
                            swal({
                                title: "Duplicate record found.",
                                text: "Change the SKU code!",
                                type: "error",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-success"
                            });
							$('.show').remove();
							$('.image').remove();
							$('.remove').remove();
							$('#files').val('');
                            $("#get-table-data").attr("disabled", false);
							
                        } else if (data.code == 200) {
							<?php if($id == "Inventory"){ ?>
							
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
						<?php } ?>
                            swal({
                                title: "Success",
                                text: "Your data is successfully saved!",
                                type: "success",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-success"
                            });
                            setTimeout(function () {
                                window.location = "view_table.php?id=" + id;
                            }, 2000);

                        } 
                        else if (data.code == 5000) {
                            swal({
                                title: "Error",
                                text: data.message,
                                type: "error",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-success"
                            });
							$('.show').remove();
							$('.image').remove();
							$('.remove').remove();
							$('#files').val('');
                            $("#get-table-data").attr("disabled", false);
								
                        } 
                        
                        else {
                            swal({
                                title: "Oops",
                                text: "Your data not created!",
                                type: "error",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-success"
                            });
							$('.show').remove();
							$('.image').remove();
							$('.remove').remove();
							$('#files').val('');
                            $("#get-table-data").attr("disabled", false);
                        }
                    }
					
                });
				
				//$("#get-table-data").attr("disabled", false);
            });

            jQuery(function ($) {
                formRenderOpts = {
                    formData: <?=$recordstable ?>
				
                };
                var renderedForm = $('#fb-render');
                renderedForm.formRender(formRenderOpts);
                $('#view_editor').html(renderedForm.html());

                //apply theme style of checkbox
                $('input[type="checkbox"]').each(function() {
                    $(this).addClass('custom-control-input');
                    $(this).parent().addClass('custom-control custom-checkbox mb-3');
                    $(this).parent().find('label').addClass('custom-control-label')
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
