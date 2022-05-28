<?php
session_start();
$_SESS['page'] = 'Dashboard';
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
	
	if(!empty($resultlan) || $resultlan != null){
		$lang = $resultlan;
		require_once ("./lan/lang." . $lang . ".php");
	}else{
		echo "Language not found. Please contact developer.";
	}
	
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
                        <h6 class="h2 text-white d-inline-block mb-0"><?php echo $lan['DASHBOARD']; ?></h6>
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
                                <h4 class=" text-uppercase text-muted  font-weight-bold mt-3"><?php echo $lan['TABLES']; ?></h4>
                            </div>
                            <!--div class="col-6 align-middle d-flex justify-content-end">
                                <a href="./create_table.php" class=" btn btn-primary text-white btn-icon-only rounded-circle mt-1">
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div-->
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="table-responsive py-4">
                        <table class="table table-flush " id="custom-datatable" >
                            <thead class="thead-light">
                            <tr>
                                <th class="w-50"><?php echo $lan['NAME']; ?></th>
                                <th class="w-25"><?php echo $lan['SUBRECORDS']; ?></th>
                                <th class="w-25 disabled-sorting text-right"><?php echo $lan['ACTIONS']; ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($values_records as $key => $record)
                            {
                                ?>
                                <tr>

                                    <td><?= $record['name'] ?></td>
                                    <td><?= $record['count'] ?></td>
                                    <td class="text-right">
                                        <a href="view_table.php?id=<?= $key_records[$key] ?>" class="btn btn-sm btn-success btn-icon-only rounded-circle mt-1 text-white" data-toggle="tooltip"  title="<?php echo $lan['VIEWLIST']; ?>">
                                            <i class="fa fa-list"></i>
                                        </a>

										<?php if($key_records[$key] == "Expense"){
									
								}elseif($key_records[$key] == "Credit"){
									
								}elseif($key_records[$key] == "Invoice"){
									
								}elseif($key_records[$key] == "Return"){
									
								}elseif($key_records[$key] == "Summary"){
									
								}elseif($key_records[$key] == "Zinfo"){
									
								}else{ ?>
                                        <a href="form_view_table.php?id=<?= $key_records[$key] ?>" class="btn btn-sm btn-info btn-icon-only rounded-circle mt-1 text-white" data-toggle="tooltip"  title="<?php echo $lan['NEW']; ?>">
                                            <i class="fa fa-plus"></i>
                                        </a>
								<?php } ?>
                                        <!--a href="edit_table.php?id=<?//= $key_records[$key] ?>" class="btn btn-sm btn-primary btn-icon-only rounded-circle mt-1 text-white" data-toggle="tooltip"  title="Edit">
                                            <i class="fa fa-pencil-alt"></i>
                                        </a-->
							<?php if($key_records[$key] == "Category"){
									
								}elseif($key_records[$key] == "Credit"){
									
								}elseif($key_records[$key] == "GR"){
									
								}elseif($key_records[$key] == "Route"){
									
								}elseif($key_records[$key] == "SalesRep"){
									
								}elseif($key_records[$key] == "Summary"){
									
								}elseif($key_records[$key] == "Zinfo"){
									
								}else{ ?>
                                        <button  data-id="<?= $key_records[$key] ?>" class="btn btn-sm btn-danger btn-icon-only rounded-circle mt-1 text-white remove" data-toggle="tooltip"  title="<?php echo $lan['EXPORTDB']; ?>">
                                            <i class="fas fa-arrow-right"></i>
                                        </button>
									<?php } ?>
										
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
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


                $(document).on('click', '.remove', function (e) {
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
                                url: "export-dash.php",
                                dataType: 'json',
                                data: {id: id},
                                success: function (data) {
                                    if (data.code == 200) {
                                        swal({
                                            title: "<?php echo $lan['EXPORTED']; ?>",
                                            text: data.message + "<?php echo $lan['EXPOSUCCESS']; ?>",
                                            type: "success",
                                            buttonsStyling: !1,
                                            confirmButtonClass: "btn btn-success"
                                        });
                                        setTimeout(function () {
                                            window.location = "dashboard.php";
                                        }, 2000);
                                    } else {
                                        swal({
                                            title: "<?php echo $lan['ERR']; ?>",
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


