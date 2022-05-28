<?php
$id = $_GET['id'];
$indate = $_GET['my'];
$my = substr(str_replace('/','',$indate),2);
$dmy = str_replace('/','',$indate);

$arr = explode('/', $indate);
$datein = $arr[1].'/'.$arr[0].'/'.$arr[2];
	
session_start();
$_SESS['page'] = 'View table'.$id;

if(empty($_SESSION))
{
    header('Location: login.php');
}

include_once('conn.php');

	$referencelan      = $database->getReference($dpath.'zinfo/lan');
	$snapshotlan       = $referencelan->getSnapshot();
	$resultlan         = $snapshotlan->getValue();
	
	$lang = $resultlan;
	require_once ("./lan/lang." . $lang . ".php");

if($id == "Inventory"){
	$reference = $database->getReference($dpath .'products');

}elseif($id == "Customer"){
	$reference = $database->getReference($dpath .'customers');
	
}elseif($id == "Expense"){
	$reference = $database->getReference($dpath .'expenses/'.$my);
	
}elseif($id == "Discount"){
	$reference = $database->getReference($dpath .'Discount');
	
}elseif($id == "Device"){
	$reference = $database->getReference($dpath .'devices');
	
}elseif($id == "SalesRep"){
	$reference = $database->getReference($dpath .'salesreps');
	
}elseif($id == "Invoice"){
	$reference = $database->getReference($dpath .'invoices/'.$my);
	
}elseif($id == "Zinfo"){
	$reference = $database->getReference($dpath .'Zinfo');
	
}elseif($id == "Category"){
	$reference = $database->getReference($dpath .'categories');
	
}elseif($id == "GR"){
	$reference = $database->getReference($dpath .'GR');
	
}elseif($id == "Route"){
	$reference = $database->getReference($dpath .'routes');
	
}elseif($id == "Summary"){
	$reference = $database->getReference($dpath .'summary/d/'.$my);
	
}elseif($id == "SummaryMonth"){
	$reference = $database->getReference($dpath .'summary/m');
	
}elseif($id == "Return" || "Return+"){
	$reference = $database->getReference($dpath .'returns/'.$my);
	
}elseif($id == "1"){
	$reference = $database->getReference($dpath .'invoices');
}

$snapshot  = $reference->getSnapshot();
$result = $snapshot->getValue();

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

$table_result_val= [] ;
$table_result_key= [] ;

if(!empty($result))
{
    $table_result_val = array_values($result);
    $table_result_key = array_keys($result);
}
?>
<!DOCTYPE html>
<head>
    <?php include './layout/head.php';?>

    <link rel="stylesheet" href="./assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="./assets/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="./assets/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
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
                        <h6 class="h2 text-white d-inline-block mb-0"><?php echo $tableName;?></h6>
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="dashboard.php"><i class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page"> <a href="dashboard.php"><?php echo $lan['LISTTABLES']; ?></a></li>
                                <li class="breadcrumb-item active" aria-current="page"><?php echo $tableName;?></li>
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
                    <div class="card-header">
                        <div class="row">
							
							<div class="col-2 align-left d-flex">
                                <h4 class="text-uppercase text-muted font-weight-bold mt-2"><?php echo $tableName." ";?> </h4>
							</div>
							<div class="col-2 align-left d-flex">
								 <input type="text" class="form-control datepicker" name="my" value="<?php echo $datein ?>" id="my" readonly="readonly">
								<a style="margin-left: 10px;" class="btn btn-secondary btn-icon-only rounded-circle mt-1" data-toggle="tooltip"  title="<?php echo $lan['SUBMIT']; ?>" onClick="doload(this);">
                                    <i class="fa fa-check-double" style="font-size: 18px; padding-top: 10px;"></i>
                                </a>
							</div>
							
                            <div class="col-8 align-middle d-flex justify-content-end">
                                <a href="dashboard.php" class="btn btn-secondary btn-icon-only rounded-circle mt-1" data-toggle="tooltip"  title="<?php echo $lan['DASHBOARD']; ?>">
                                    <i class="fa fa-arrow-left"></i>
                                </a>
								<?php if($id == "Summary") { 
									
								}elseif($id == "SummaryMonth") {
	
								}elseif($id == "Device") {
	
								}elseif($id == "Invoice") {
								
								}elseif($id == "Return") {
								
								}elseif($id == "Expense") {
	
								}elseif($id == "Zinfo") {
								
								}else{ ?>
								<a href="form_view_table.php?id=<?= $id ?>" class="btn btn-primary text-white btn-icon-only rounded-circle mt-1" data-toggle="tooltip"  title="<?php echo $lan['NEW']; ?>">
                                    <i class="fa fa-plus"></i>
                                </a>
								<?php if($id == "Inventory") { ?>
								<!--button class="btn text-white btn-icon-only rounded-circle mt-1" data-json='' onclick="tableFromJson();" data-toggle="tooltip"  title="Old Data" style="background-color: #FFAC5B">
									<i class="fa fa-folder" ></i>
								</button-->
								
								<?php }
									 } ?>
                            </div>
                        </div>
                    </div>
                    <?php
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
                    <!-- Card Body -->
                    <div class="table-responsive py-4">
                        <table class="table table-flush " id="datatable-custom" >
                            <thead class="thead-light">
                            <tr>
                                <?php if(!empty($headers))
                                {
                                    for($i = 0; $i < (count($headers) < 4 ? count($headers) : 4); $i++) { ?>
										<?php $breadcum = str_replace('_',' ',$headers[$i]); ?>
                                        <th><?=$breadcum ?></th>
                                    <?php }
                                }
                                ?>
                                <th class="disabled-sorting text-right"><?php echo $lan['ACTIONS']; ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php for($j = 0; $j < count($records); $j++) { ?>
                                <tr>
                                    <?php				   
											for($k = 0; $k < (count($records[$j]) < 4 ? count($records[$j]) : 4); $k++) { ?>
                                        <td><?= $records[$j][$k] ?></td>
                                    <?php }

                                    ?>
                                    <td class="text-right">
                                        <button class="btn btn-sm btn-success btn-icon-only rounded-circle mt-1 text-white" data-json='<?= $table_result_val[$j]; ?>' onclick="get_record(this);" data-toggle="tooltip"  title="<?php echo $lan['VIEW']; ?>">
                                            <i class="fa fa-eye" ></i>
                                        </button>
										
										<?php if($id == "Summary" || $id == "SummaryMonth") { ?>
										
										<!--a href="view_table.php?id=<?//=$id; ?>&key=<?//=$table_result_key[$j]; ?>" class="btn btn-sm btn-primary btn-icon-only rounded-circle mt-1 text-white" data-toggle="tooltip"  title="Export">
                                            <i class="fa fa-arrow-down"></i>
                                        </a-->
										<button  data-id="<?= $table_result_key[$j] ?>" class="btn btn-sm btn-primary btn-icon-only rounded-circle mt-1 text-white update" data-toggle="tooltip"  title="UPDATE">
											<i class="fa fa-undo" style="position: absolute;left: 9px;top: 9px;"></i>
											
											<?php 	$referenceus = $database->getReference($dpath.'zinfo/us');
													$snapshotus  = $referenceus->getSnapshot();
													$tableus = $snapshotus->getValue(); 
																	
													$time = new DateTime('Asia/Colombo');
													$cdate = $time->format('dmY');
													if($tableus == "1"){
														if($table_result_key[$j] == $cdate){
											?>
											<span class="fa fa-undo" style="position: absolute;top: -5px;right: -2px;padding: 5px 5px; border-radius: 100%;background-color: red;color: red; font-size: 2px;"></span>
											<?php }} ?>
                                        </button>
										
										<button  data-id="<?= $table_result_key[$j] ?>" class="btn btn-sm btn-danger btn-icon-only rounded-circle mt-1 text-white export" data-toggle="tooltip"  title="EXPORT">
                                            <i class="fa fa-arrow-right"></i>
                                        </button>
										
										<?php }elseif($id == "Invoice") {

										}elseif($id == "Return") {

										}elseif($id == "Expense") {

										}elseif($id == "GR") {

										}else{ 
										
										if($id == "Device") {
										}elseif($id == "Category") {
										}else{ ?>
                                        <a href="form_view_edit.php?tid=<?= $id ?>&id=<?= $table_result_key[$j] ?>" class="btn btn-sm btn-primary btn-icon-only rounded-circle mt-1 text-white" data-toggle="tooltip"  title="<?php echo $lan['EDIT']; ?>">
                                            <i class="fa fa-pencil-alt"></i>
                                        </a>
										<?php 
										}
										if($id == "Discount") {
											
										}elseif($id == "Category") { ?>
										<button  data-id="<?= $table_result_key[$j] ?>" class="btn btn-sm btn-danger btn-icon-only rounded-circle mt-1 text-white remove" data-toggle="tooltip"  title="<?php echo $lan['REMOVE']; ?>">
										<i class="fa fa-trash"></i>
                                        </button>

										<?php }elseif($id == "Zinfo") {
											
										}else{ ?>
                                        <button  data-id="<?= $table_result_key[$j] ?>" class="btn btn-sm btn-danger btn-icon-only rounded-circle mt-1 text-white remove" data-toggle="tooltip"  title="<?php echo $lan['REMOVE']; ?>">
                                            <i class="fa fa-trash"></i>
                                        </button>
											<?php }
											 } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="modal-title-default"><?php echo $lan['RECDETAILS']; ?></h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="card shadow border-0">
                            <div class="table-responsive">
                                <table class="table table-flush">
                                    <thead class="thead-light">
                                        <tr>
                                            <th><?php echo $lan['FIELDNAME']; ?></th>
                                            <th><?php echo $lan['VALUE']; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody id="view_body"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

		<div class="modal fade" id="modal-form-old" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" style="position: static; margin-left: -70%; width: 250%;background-color: #FFAC5B">
                    <div class="modal-header">
                        <h6 class="modal-title" id="modal-title-default">Inventory of 07/2020</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="card shadow border-0" style="background-color: #FFECE0">
                            <div class="table-responsive">
                                <table class="table table-flush">
                                    <tbody id="view_body_old"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="card-header card-header-text" data-background-color="orange">
                            <h4 class="card-title"><?php echo $lan['RECDETAILS']; ?></h4>
                            <p class="category"></p>
                        </div>
                        <div class="card-content table-responsive">
                            <table class="table">
                                <tbody id="body">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $lan['CLOSE']; ?></button>
                    </div>
                </div>

            </div>
        </div>


        <?php include './layout/footer.php';?>

        <!-- Optional JS -->
        <script src="./assets/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="./assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="./assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="./assets/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
        <script src="./assets/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="./assets/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
        <script src="./assets/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="./assets/vendor/datatables.net-select/js/dataTables.select.min.js"></script>
		<script src="./assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

        <script type="text/javascript">
            $(document).ready(function () {
				
				var c1 = document.getElementById("my").value.substr(3);
				var c = c1.substr(0,3) + document.getElementById("my").value.substr(0, 3) + c1.substr(3);
                var table_ids = '<?=$id?>';
                var DatatableBasic = (function() {
                    var $dtBasic = $('#datatable-custom');
                    function init($this) {
                        var options = {
                            keys: !0,
                            select: false,
                            language: {
                                paginate: {
                                    previous: "<i class='fas fa-angle-left'>",
                                    next: "<i class='fas fa-angle-right'>"
                                }
                            },
                        };
                        var table = $this.on('init.dt', function() {
                           // $('div.dataTables_length select').removeClass('custom-select custom-select-sm');

                        }).DataTable(options);
                    }
                    if ($dtBasic.length) {
                        init($dtBasic);
                    }
                })();
				
			
                $(document).on('click', '.remove', function (e) {
                    var id = $(this).data("id");

                    Swal.fire({
                        title: "<?php echo $lan['AREYOUSURE']; ?>",
                        text: "<?php echo $lan['DELMSG']; ?>",
                        type: "warning",
                        showCancelButton: !0,
                        confirmButtonColor: "#0CC27E",
                        cancelButtonColor: "#FF586B",
                        confirmButtonText: "<?php echo $lan['DELMSGBTN']; ?>",
                        cancelButtonText: "<?php echo $lan['DELMSGCANCEL']; ?>",
                        confirmButtonClass: "btn btn-success mr-5",
                        cancelButtonClass: "btn btn-danger",
                        buttonsStyling: !1
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                type: "POST",
                                url: "table_record_delete.php?id=" + table_ids,
                                dataType: 'json',
                                data: {id: id},
                                success: function (data) {
                                    if (data.code == 200) {
                                        swal({
                                            title: "<?php echo $lan['SUCCESS']; ?>",
                                            text: "<?php echo $lan['SUCCESSMSG']; ?>",
                                            type: "success",
                                            buttonsStyling: !1,
                                            confirmButtonClass: "btn btn-success"
                                        });
                                        setTimeout(function () {
                                            window.location = "view_table.php?id=" + table_ids + "&my=" + c;
                                        }, 2000);
                                    } else {
                                        swal({
                                            title: "<?php echo $lan['ERR']; ?>",
                                            text: "<?php echo $lan['ERRMSG']; ?>",
                                            type: "error",
                                            buttonsStyling: !1,
                                            confirmButtonClass: "btn btn-success"
                                        });
                                    }
                                }
                            });
                        }
                    });

                });
				//update summary
				$(document).on('click', '.update', function (e) {
                    var id = $(this).data("id");

                    Swal.fire({
                        title: "Confirm Update",
                        text: "Click yes to update Summary",
                        type: "warning",
                        showCancelButton: !0,
                        confirmButtonColor: "#0CC27E",
                        cancelButtonColor: "#FF586B",
                        confirmButtonText: "YES",
                        cancelButtonText: "NO",
                        confirmButtonClass: "btn btn-success mr-5",
                        cancelButtonClass: "btn btn-danger",
                        buttonsStyling: !1
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                type: "POST",
                                url: "update_summary.php?tid=" + table_ids,
                                dataType: 'json',
                                data: {id: id},
                                success: function (data) {
                                    if (data.code == 200) {
                                        swal({
                                            title: "<?php echo $lan['SUCCESS']; ?>",
                                            text: "Summary updated successfully",
                                            type: "success",
                                            buttonsStyling: !1,
                                            confirmButtonClass: "btn btn-success"
                                        });
                                        setTimeout(function () {
                                            window.location = "view_table.php?id=" + table_ids + "&my=" + c;
                                        }, 2000);
                                    } else {
                                        swal({
                                            title: "<?php echo $lan['ERR']; ?>",
                                            text: "<?php echo $lan['ERRMSG']; ?>",
                                            type: "error",
                                            buttonsStyling: !1,
                                            confirmButtonClass: "btn btn-success"
                                        });
                                    }
                                }
                            });
                        }
                    });

                });
				
				$(document).on('click', '.export', function (e) {
                    var id = $(this).data("id");

                    Swal.fire({
                        title: "<?php echo $lan['CONDBEXPORT']; ?>",
                        text: "<?php echo $lan['EXPOMSG']; ?>",
                        type: "warning",
                        showCancelButton: !0,
                        confirmButtonColor: "#0CC27E",
                        cancelButtonColor: "#FF586B",
                        confirmButtonText: "<?php echo $lan['EXPOMSGBTN']; ?>",
                        cancelButtonText: "<?php echo $lan['EXPOMSGCANCEL']; ?>",
                        confirmButtonClass: "btn btn-success mr-5",
                        cancelButtonClass: "btn btn-danger",
                        buttonsStyling: !1
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                type: "POST",
                                url: "export.php?id=" + table_ids,
                                dataType: 'json',
                                data: {id: id},
                                success: function (data) {
                                    if (data.code == 200) {
                                        swal({
                                            title: "<?php echo $lan['EXPORTED']; ?>",
                                            text: "<?php echo $lan['EXPOSUCCESS']; ?>",
                                            type: "success",
                                            buttonsStyling: !1,
                                            confirmButtonClass: "btn btn-success"
                                        });
                                        /*setTimeout(function () {
                                            window.location = "view_table.php?id=" + table_ids + "&my=" + c;
                                        }, 2000);*/
                                    } else {
                                        swal({
                                            title: "<?php echo $lan['ERR']; ?>",
                                            text: "<?php echo $lan['ERRMSG']; ?>",
                                            type: "error",
                                            buttonsStyling: !1,
                                            confirmButtonClass: "btn btn-success"
                                        });
                                    }
                                }
                            });
                        }
                    });

                });
				
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
			
			function doload(obj) {
					var c1 = document.getElementById("my").value.substr(3);
					var c = c1.substr(0,3) + document.getElementById("my").value.substr(0, 3) + c1.substr(3);
						window.open('view_table.php?id=<?= $id."&my=" ?>'+c, '_self');
					return false;
				}
		/*	
		function tableFromJson() {
		// the json data. (you can change the values for output.)
				showPageLoader();

			var oXHR = new XMLHttpRequest();
			// Initiate request.
			oXHR.onreadystatechange = reportStatus;
			oXHR.open("GET", "inventory/072020.json", true);  // get json file.
			oXHR.send();
				
		function reportStatus() {
        if (oXHR.readyState == 4) {		// Check if request is complete.
            var finals = this.responseText;
			var mapObj = {
			   	ß1:"SKU",
			   	ß2:"CATEGORY",
			   	ß3:"ITEM",
				ß3t:"ITEM",
				ß4:"BRAND",
			   	ß5:"EXPIRE",
			   	ß6:"P.COST",
				ß7:"T.COST",
			   	ß8:"PRICE",
			   	ß9:"UNIT",
				ßa:"END QTY",
			   	ßb:"PROFIT %",
			   	ßc:"END TSV",
				ßd:"DATE ENTERED",
				ße:"INITAIL QTY",
				ßf:"INITIAL TSV",

			};
			var final = finals.replace(/ß1|ß2|ß3|ß3t|ß4|ß5|ß6|ß7|ß8|ß9|ßa|ßb|ßc|ßd|ße|ßf/gi, function(matched){
			  return mapObj[matched];
			});
			//var final = final2.replace(/\}\,.*?\{/g, '},{');
        }
			
		var myBooks = $.parseJSON('[' + final + ']')
		console.log(final);
			
        // Extract value from table header. 
        // ('Book ID', 'Book Name', 'Category' and 'Price')
        var col = [];
        for (var i = 0; i < myBooks.length; i++) {
            for (var key in myBooks[i]) {
                if (col.indexOf(key) === -1) {
                    col.push(key);
                }
            }
        }

        // Create a table.
        var table = document.createElement("table");

        // Create table header row using the extracted headers above.
        var tr = table.insertRow(-1);                   // table row.

        for (var i = 0; i < col.length; i++) {
            var th = document.createElement("th");      // table header.
            th.innerHTML = col[i];
            tr.appendChild(th);
        }

        // add json data to the table as rows.
        for (var i = 0; i < myBooks.length; i++) {

            tr = table.insertRow(-1);

            for (var j = 0; j < col.length; j++) {
                var tabCell = tr.insertCell(-1);
                tabCell.innerHTML = myBooks[i][col[j]];
            }
        }
				
				$('#view_body_old').html(table);
                closePageLoader();
                $('#modal-form-old').modal('show');
    		}
		}*/
			
			function get_record(obj) {
                showPageLoader();
                var json = obj.getAttribute('data-json');
                var data = JSON.parse(json);
                var body = '';
                for (var i = 0; i < data.length; i++) {
					
                    var name = str =  data[i].name.replace('_',' ').toLowerCase().replace(/\b[a-z]/g, function(letter) {
						//data[i].name['p1'].replace('p1','SKU')
                        return letter.toUpperCase();
						
                    });
					
					if(name == "P17"){
						if('<?=$id?>' == "Inventory") { 
							/*var trsnapro = data[5].value / 100 * data[6].value;
							var trsnaprofit = +data[5].value + +trsnapro;*/
							data[5].value = "<?php echo $lan['RS']; ?>" + (data[5].value);
							data[6].value = (data[6].value) + " %";
							data[7].value = "<?php echo $lan['RS']; ?>" + (data[7].value);
							data[10].value = (data[10].value) + " %";
							data[11].value = "<?php echo $lan['RS']; ?>" + (data[11].value);//(trsnaprofit * data[9].value).toLocaleString();
						}
					}
					
					//EXPENSES
					if(name == "B07"){ name = "<?php echo $lan['TOTAL']; ?>"; }
					
					//GR
					if(name == "Id"){ name = "GR ID"; }
					
					//DISCOUNT
					if(name == "D01"){ name = "Condition"; }
					if(name == "D02"){ name = "Qty"; }
					if(name == "D03"){ name = "Price"; }
					
					//DEVICE
					if(name == "DE01"){ name = "DEVICE ID"; }
					if(name == "DE02"){ name = "Model"; }
					if(name == "DE03"){ name = "VCODE"; }
					if(name == "DE04"){ name = "REGISTERED"; }
					if(name == "DE05"){ name = "SALESREP"; }
					
					//PRODUCTS
					if(name == "P01"){ name = "<?php echo $lan['SKU']; ?>"; }
					if(name == "P02"){ name = "<?php echo $lan['CATEGORY']; ?>"; }
					if(name == "P03"){ name = "<?php echo $lan['NAMEEN']; ?>"; }
					if(name == "P04"){ name = "<?php echo $lan['BRAND']; ?>"; }
					if(name == "P05"){ name = "<?php echo $lan['EXPIRY']; ?>"; }
					if(name == "P06"){ name = "<?php echo $lan['PURCOST']; ?>"; }
					if(name == "P07"){ name = "<?php echo "VAT %" ?>"; }
					if(name == "P08"){ name = "<?php echo $lan['PRODUCTPRI']; ?>"; }
					if(name == "P09"){ name = "<?php echo $lan['UNIT']; ?>"; }
					if(name == "P10"){ name = "<?php echo $lan['QTY']; ?>"; }
					if(name == "P11"){ name = "<?php echo $lan['PROFIT']; ?>"; }
					if(name == "P16"){ name = "<?php echo $lan['RECORDENTER']; ?>"; }
					if(name == "P17"){ name = "<?php echo $lan['CUSTOVAL']; ?>"; }
					if(name == "P21"){ name = "<?php echo "DISCOUNT" ?>"; }
					
					//SALESREP
					if(name == "Key"){ name = "<?php echo $lan['KEY']; ?>"; }
					if(name == "Log"){ name = "<?php echo $lan['LOG']; ?>"; }
					
					//CATEGORY
					if(name == "Ca1"){ name = "<?php echo $lan['CATEGORY']; ?>"; }
					if(name == "Ca2"){ name = "<?php echo $lan['CATID']; ?>"; }
					
					//ROUTE
					if(name == "R1"){ name = "<?php echo $lan['ROUTE']; ?>"; }
					if(name == "R2"){ name = "<?php echo $lan['ROUTEID']; ?>"; }
					
					//CUSTOMER
					if(name == "P12"){ name = "<?php echo $lan['CONTACT']; ?>"; }
					if(name == "P13"){ name = "<?php echo $lan['ROUTE']; ?>"; }
					if(name == "P14"){ name = "<?php echo $lan['BALANCE']; ?>"; }
					if(name == "P15"){ name = "<?php echo $lan['ADDRESS']; ?>"; }
					
					if(name == "z01"){
						if('<?=$id?>' == "Summary") {
							data[0].value = "<?php echo $lan['RS']; ?>" + (+0 + +data[0].value).toLocaleString();
							data[3].value = "<?php echo $lan['RS']; ?>" + (+0 + +data[3].value).toLocaleString();
							data[5].value = "<?php echo $lan['RS']; ?>" + (+0 + +data[5].value).toLocaleString();
							data[10].value = "<?php echo $lan['RS']; ?>" + (+0 + +data[10].value).toLocaleString();
							data[12].value = "<?php echo $lan['RS']; ?>" + (+0 + +data[12].value).toLocaleString();
						}
					}
					
					
					/*if('<?//=$id?>' == "Return") { 
						var lastSix = data[0].value.substr(data[5].value.length - 6);
						data[0].value = "re-"+lastSix;
					}
					if('<?//=$id?>' == "Invoice") { 
						var lastSix = data[0].value.substr(data[6].value.length - 6);
						data[0].value = "in-"+lastSix;
					}*/
					
					if('<?=$id?>' == "Invoice") { 
					//INVOICE
					if(name == "B01"){ name = "<?php echo $lan['SERIALNO']; ?>"; }
					if(name == "B02"){ name = "<?php echo $lan['CUSTOMER']; ?>"; }
					if(name == "B03"){ name = "<?php echo $lan['ADDRESS']; ?>"; }
					if(name == "B04"){ name = "<?php echo $lan['DATE']; ?>"; }
					if(name == "B05"){ name = "<?php echo $lan['SALESREP']; ?>"; }
					if(name == "B06"){ name = "<?php echo $lan['ITEMS']; ?>"; }
					if(name == "B07"){ name = "<?php echo $lan['TOTAL']; ?>"; }
					if(name == "B08"){ name = "<?php echo $lan['CUSPAID']; ?>"; }
					if(name == "B09"){ name = "<?php echo $lan['BALANCE']; ?>"; }
					if(name == "B10"){ name = "<?php echo $lan['TIME']; ?>"; }
					if(name == "B11"){ name = "TOTAL DISCOUNT"; }
					if(name == "Pay"){ name = "<?php echo $lan['PAYMETHOD']; ?>"; }
					if(name == "Key"){ name = "<?php echo $lan['KEY']; ?>"; }
					}
					
					if('<?=$id?>' == "Return") { 
					//RETURN
					if(name == "B01"){ name = "<?php echo $lan['SERIALNO']; ?>"; }
					if(name == "B02"){ name = "<?php echo $lan['CUSTOMER']; ?>"; }
					if(name == "B04"){ name = "<?php echo $lan['DATE']; ?>"; }
					if(name == "B05"){ name = "<?php echo $lan['SALESREP']; ?>"; }
					if(name == "B06"){ name = "<?php echo $lan['ITEMS']; ?>"; }
					if(name == "B07"){ name = "<?php echo $lan['TOTAL']; ?>"; }
					if(name == "B08"){ name = "<?php echo $lan['COMPAID']; ?>"; }
					if(name == "B09"){ name = "<?php echo $lan['BALANCE']; ?>"; }
					if(name == "B10"){ name = "<?php echo $lan['TIME']; ?>"; }
					if(name == "Pay"){ name = "<?php echo $lan['PAYMETHOD']; ?>"; }
					if(name == "Key"){ name = "<?php echo $lan['KEY']; ?>"; }
					}
					
					//STORE INFO
					if(name == "I01"){ name = "<?php echo $lan['OWNERNAME']; ?>"; }
					if(name == "I02"){ name = "<?php echo $lan['STONAME']; ?>"; }
					if(name == "I03"){ name = "<?php echo $lan['BRNO']; ?>"; }
					if(name == "I04"){ name = "<?php echo $lan['STOADDRESS']; ?>"; }
					if(name == "I05"){ name = "<?php echo $lan['CONTACT']; ?>"; }
					if(name == "I06"){ name = "<?php echo $lan['EMAIL']; ?>"; }
					if(name == "I07"){ name = "<?php echo $lan['ADINFO']; ?>"; }
					
					//SUMMARY
					if(name == "Z00"){ name = "<?php echo $lan['DATE']; ?>"; }
					if(name == "Z02"){ name = "<?php echo $lan['SV']; ?>"; }
					if(name == "Z03"){ name = "<?php echo $lan['SI']; ?>"; }
					if(name == "Z04"){ name = "<?php echo $lan['SC']; ?>"; }
					if(name == "Z05"){ name = "<?php echo $lan['RV']; ?>"; }
					if(name == "Z06"){ name = "<?php echo $lan['RI']; ?>"; }
					if(name == "Z07"){ name = "<?php echo $lan['RC']; ?>"; }
					if(name == "Z08"){ name = "<?php echo $lan['GRADDED']; ?>"; }
					if(name == "Z081"){ name = "<?php echo $lan['EXPENSE']; ?>"; }
					
					if('<?=$id?>' == "SummaryMonth") { 
					//SUMMARY
					if(name == "Z01"){ name = "<?php echo $lan['MSTART']; ?>"; }
					if(name == "Z09"){ name = "<?php echo $lan['MEND']; ?>"; }
					}
					
					if('<?=$id?>' == "Summary") { 
					//SUMMARY
					if(name == "Z01"){ name = "<?php echo $lan['DSTART']; ?>"; }
					if(name == "Z09"){ name = "<?php echo $lan['DEND']; ?>"; }
					}
					
                    body += '<tr><td>' + name + '</td><td>' + data[i].value + '</td></tr>';
					
                }
                $('#view_body').html(body);
                closePageLoader();
                $('#modal-form').modal('show');

            }

        </script>
		
</body>

</html>