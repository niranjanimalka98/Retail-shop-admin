<?php
session_start();
$_SESS['page'] = 'exported';
if(empty($_SESSION))
{
    header('Location: login.php');
}
include_once('conn.php');
$reference      = $database->getReference($dpath.'tables');
$snapshot       = $reference->getSnapshot();
$result         = $snapshot->getValue();
$values_records = [];
$key_records = [];
if(!empty($result))
{
    $values_records = array_values($result);
    $key_records    = array_keys($result);
}
?>
<!DOCTYPE html>
<head>
    <?php 

	$referencelan      = $database->getReference($dpath.'zinfo/lan');
	$snapshotlan       = $referencelan->getSnapshot();
	$resultlan         = $snapshotlan->getValue();
	
	$lang = $resultlan;
	require_once ("./lan/lang." . $lang . ".php");
	
	include './layout/head.php';?>

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
                        <h6 class="h2 text-white d-inline-block mb-0">EXPORTED FILES</h6>
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
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
                        <div class="row"><div class="col-6">
                                <h4 class=" text-uppercase text-muted  font-weight-bold mt-3">Exported Files</h4>
                            </div>
                            <div class="col-6 align-middle d-flex justify-content-end">
								
								<button class="btn btn-primary text-white btn-icon-only rounded-circle mt-1 entiredb" data-toggle="tooltip"  title="Download Entire DB">
                                            <i class="fa fa-arrow-circle-down"></i>
                                </button>

                            </div>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="table-responsive py-4">
                        <table class="table table-flush " id="custom-datatable" >
                            <thead class="thead-light">
                             <tr>
                                <th class="w-50">Name</th>
                                <th class="w-25">---</th>
                                <th class="w-25 disabled-sorting text-right">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
								
                     <?php if ($handle = opendir('./export')) {
							while (false !== ($file = readdir($handle))) {
							  if ($file != "." && $file != "..") {
								 //'<li><a href="'.$file.'">'.$file.'</a></li>' ?>
								  
								<tr>

                                    <td><i class="fa fa-file-alt" style="font-size: 20px;"></i> <?= ' '.$file ?></td>
                                    <td><?//= $record['count'] ?></td>
									
                                    <td class="text-right">
                                        <a href="<?= 'export/'.$file ?>" class="btn btn-sm btn-success btn-icon-only rounded-circle mt-1 text-white" data-toggle="tooltip" title="View" target="_blank">
                                            <i class="fa fa-eye"></i>
                                        </a>

										<button  data-id="<?= $file ?>" class="btn btn-sm btn-light btn-icon-only rounded-circle mt-1 text-white save" data-toggle="tooltip"  title="Save CSV">
                                            <i class="fas fa-save"></i>
                                        </button>
										
										<!--button  data-id="<?//= $file ?>" class="btn btn-sm btn-info btn-icon-only rounded-circle mt-1 text-white download" data-toggle="tooltip" title="Download">
                                            <i class="fas fa-arrow-down"></i>
                                        </button-->
										
                                        <!--a href="edit_table.php?id=<?//= $key_records[$key] ?>" class="btn btn-sm btn-primary btn-icon-only rounded-circle mt-1 text-white" data-toggle="tooltip"  title="Edit">
                                            <i class="fa fa-pencil-alt"></i>
                                        </a-->
                                        <button  data-id="<?= $file ?>" class="btn btn-sm btn-danger btn-icon-only rounded-circle mt-1 text-white remove" data-toggle="tooltip"  title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
						<?php	  }
								
								
							}
							closedir($handle);
						  } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <?php include './layout/footer.php'; ?>

        <!-- Optional JS -->
        <script src="./assets/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="./assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="./assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="./assets/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
        <script src="./assets/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="./assets/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
        <script src="./assets/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="./assets/vendor/datatables.net-select/js/dataTables.select.min.js"></script>
		<!--SHEETJS/script src="./api/xlsx.full.min.js"></script>
		<script src="./api/FileSaver.js"></script-->

        <script type="text/javascript">
            $(document).ready(function () {
                var DatatableBasic = (function() {
                    var $dtBasic = $('#custom-datatable');
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

				//export XLSX
				$(document).on('click', '.save', function (e) {
                    var id = $(this).data("id");
                    Swal.fire({
                        title: "Confirm Download?",
                        text: "Click yes if you want to download CSV file!",
                        type: "warning",
                        showCancelButton: !0,
                        confirmButtonColor: "#0CC27E",
                        cancelButtonColor: "#FF586B",
                        confirmButtonText: "Yes, Save!",
                        cancelButtonText: "No, cancel!",
                        confirmButtonClass: "btn btn-success mr-5",
                        cancelButtonClass: "btn btn-danger",
                        buttonsStyling: !1
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                type: "POST",
                                url: "download.php?lan=" + '<?=$lang ?>',
                                dataType: 'json',
                                data: {id: id},
                                success: function (data) {
                                    if (data.code == 200) {
                                        swal({
                                            title: "Saving",
                                            text: data.message,
                                            type: "success",
                                            buttonsStyling: !1,
                                            confirmButtonClass: "btn btn-success"
                                        });
										
										if(data.record != 'Summary'){
										//Sheetjs
										/*var wb = XLSX.utils.book_new();
										wb.Props = {
												Title: "Report",
												Subject: "Report",
												Author: "Implemented by jkdesigns.app",
												CreatedDate: new Date(2017,12,19)
										};

										wb.SheetNames.push("Report Download");
										var ws_data = [data.record];
										var ws = XLSX.utils.aoa_to_sheet(ws_data);
										wb.Sheets["Report"] = ws;
										var wbout = XLSX.write(wb, {bookType:'xlsx',  type: 'binary'});
										function s2ab(s) {

												var buf = new ArrayBuffer(s.length);
												var view = new Uint8Array(buf);
												for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
												return buf;

										}
										saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), 'Report_Download.xlsx');*/
	
										//export using js - csv
										var data = data.record;
										var filename = id.replace('.json','');
										if(data == '')
											return;

										JSONToCSVConvertor(data, filename, true);
										
										function JSONToCSVConvertor(JSONData, ReportTitle, ShowLabel) {
										//If JSONData is not an object then JSON.parse will parse the JSON string in an Object
										var arrData = typeof JSONData != 'object' ? JSON.parse(JSONData) : JSONData;
										var BOM = "\uFEFF";
										var CSV = '' /*+ '\r\n\n'*/;

										//This condition will generate the Label/Header
										if (ShowLabel) {
											var row = "";
											
											//This loop will extract the label from 1st index of on array
											for (var index in arrData[0]) {

												//Now convert each value to string and comma-seprated
												row += index + ',';
											}

											row = row.slice(0, -1);

											//append Label row with line break
											CSV += row + '\r\n';
										}

										//1st loop is to extract each row
										for (var i = 0; i < arrData.length; i++) {
											var row = "";
											
											//2nd loop will extract each column and convert it in string comma-seprated
											for (var index in arrData[i]) {
												row += '"' + arrData[i][index] + '",';
											}

											row.slice(0, row.length - 1);

											//add a line break after each row
											CSV += row + '\r\n';
										}

										if (CSV == '') {        
											alert("Invalid data");
											return;
										}   
    
										//Generate a file name
										var fileName = "SammanthanJaffna_";
										//this will remove the blank-spaces from the title and replace it with an underscore
										fileName += ReportTitle.replace(/ /g,"_");

										//Initialize file format you want csv or xls
										var uri = 'data:text/csv;charset=utf-8,%EF%BB%BF' + encodeURI(BOM + CSV);

										// Now the little tricky part.
										// you can use either>> window.open(uri);
										// but this will not work in some browsers
										// or you will not get the correct file extension    

										//this trick will generate a temp <a /> tag
										var link = document.createElement("a");    
										link.href = uri;

										//set the visibility hidden so it will not effect on your web-layout
										link.style = "visibility:hidden";
										link.download = fileName + ".csv";

										//this part will append the anchor tag and remove it after automatic click
										document.body.appendChild(link);
										link.click();
										document.body.removeChild(link);
									}
										}else{
											window.open("https://sammanthan-jaffna.jkdesigns.app/download.xlsx", "_blank");
										}
                                        /*setTimeout(function () {
                                            window.location = "exported.php";
                                        }, 2000);*/
                                    } else {
                                        swal({
                                            title: "Oops",
                                            text: data.message,
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
				
				//download
				$(document).on('click', '.download', function (e) {
                    var id = "id";
                    Swal.fire({
                        title: "Confirm Download?",
                        text: "Click yes if you need to download the file!",
                        type: "warning",
                        showCancelButton: !0,
                        confirmButtonColor: "#0CC27E",
                        cancelButtonColor: "#FF586B",
                        confirmButtonText: "Yes, Download!",
                        cancelButtonText: "No, cancel!",
                        confirmButtonClass: "btn btn-success mr-5",
                        cancelButtonClass: "btn btn-danger",
                        buttonsStyling: !1
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                type: "POST",
                                url: "download.php",
                                dataType: 'json',
                                data: {id: id},
                                success: function (data) {
                                    if (data.code == 200) {
                                        swal({
                                            title: "Downloaded",
                                            text: data.message,
                                            type: "success",
                                            buttonsStyling: !1,
                                            confirmButtonClass: "btn btn-success"
                                        });
                                        /*setTimeout(function () {
                                            window.location = "exported.php";
                                        }, 2000);*/
                                    } else {
                                        swal({
                                            title: "Oops",
                                            text: data.message,
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
				
				//entireDB
				$(document).on('click', '.entiredb', function (e) {
                    var id = "id";
                    Swal.fire({
                        title: "Confirm Entire DB export?",
                        text: "Click yes if you need to export entire DB!",
                        type: "warning",
                        showCancelButton: !0,
                        confirmButtonColor: "#0CC27E",
                        cancelButtonColor: "#FF586B",
                        confirmButtonText: "Yes, Export!",
                        cancelButtonText: "No, cancel!",
                        confirmButtonClass: "btn btn-success mr-5",
                        cancelButtonClass: "btn btn-danger",
                        buttonsStyling: !1
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                type: "POST",
                                url: "entire_DB.php?",
                                dataType: 'json',
                                data: {id: id},
                                success: function (data) {
                                    if (data.code == 200) {
                                        swal({
                                            title: "Exported",
                                            text: data.message,
                                            type: "success",
                                            buttonsStyling: !1,
                                            confirmButtonClass: "btn btn-success"
                                        });
                                        setTimeout(function () {
                                            window.location = "exported.php";
                                        }, 2000);
                                    } else {
                                        swal({
                                            title: "Oops",
                                            text: data.message,
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
				
				//delete
                $(document).on('click', '.remove', function (e) {
                    var id = $(this).data("id");
                    Swal.fire({
                        title: "Confirm Deletion?",
                        text: "Click yes if you want to Delete this file!",
                        type: "warning",
                        showCancelButton: !0,
                        confirmButtonColor: "#0CC27E",
                        cancelButtonColor: "#FF586B",
                        confirmButtonText: "Yes, Delete!",
                        cancelButtonText: "No, cancel!",
                        confirmButtonClass: "btn btn-success mr-5",
                        cancelButtonClass: "btn btn-danger",
                        buttonsStyling: !1
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                type: "POST",
                                url: "delete.php",
                                dataType: 'json',
                                data: {id: id},
                                success: function (data) {
                                    if (data.code == 200) {
                                        swal({
                                            title: "Deleted",
                                            text: data.message,
                                            type: "success",
                                            buttonsStyling: !1,
                                            confirmButtonClass: "btn btn-success"
                                        });
                                        setTimeout(function () {
                                            window.location = "exported.php";
                                        }, 2000);
                                    } else {
                                        swal({
                                            title: "Oops",
                                            text: data.message,
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

        </script>
</body>

</html>


