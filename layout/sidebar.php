<?php
include_once('conn.php');
$reference      = $database->getReference($dpath.'tables');
$snapshot       = $reference->getSnapshot();
$result         = $snapshot->getValue();
$values_records = [];
$key_records = [];

$time = new DateTime('Asia/Colombo');
$inmonth = $time->format('d/m/Y');

	$referencelan      = $database->getReference($dpath.'zinfo/lan');
	$snapshotlan       = $referencelan->getSnapshot();
	$resultlan         = $snapshotlan->getValue();
	
	$lang = $resultlan;
	require_once ("./lan/lang." . $lang . ".php");

if(!empty($result))
{
    $values_records = array_values($result);
    $key_records    = array_keys($result);
	//foreach($values_records as $key => $record){
	
	$Vnlink = array_filter(array_column($values_records, 'name')); 
	
	if($lang == "en"){
	$Vn = array_filter(array_column($values_records, 'name')); 
	}elseif($lang == "ta"){
	$Vn = array_filter(array_column($values_records, 'nt')); 
	}elseif($lang == "si"){
	$Vn = array_filter(array_column($values_records, 'ns')); 
	}
	
	$Vc = array_filter(array_column($values_records, 'count'));
	$Kv = array_filter(array_column($key_records, 'key'));
	
	//Category = $Vn0  //Customer = $Vn1  //GR = $Vn2  //Inventory = $Vn3  //Invoice = $Vn4  //Return = $Vn5  //Route = $Vn6
	//SalesRep = $Vn7  //Summary = $Vn8  //Zinfo = $Vn9  
	
	
	/*if($Vc[0] !== null){$Vi3[0] = $Vc[0];}else{ $Vi3[0] = "0";}
	if($Vc[1] !== null){$Vi3[1] = $Vc[1];}else{ $Vi3[1] = "0";}
	if($Vc[2] !== null){$Vi3[2] = $Vc[2];}else{ $Vi3[2] = "0";}
	if($Vc[3] !== null){$Vi3[3] = $Vc[3];}else{ $Vi3[3] = "0";}*/
	//}
}?>
<!-- Sidenav -->
<style>
	.nav-item{
		transition-duration: 0.4s;
	}
.nav-item:hover{
    background-color: #E0DEDF;
	border-radius: 5px;
	
}
</style>
<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
        <!-- Brand -->
        <div class="sidenav-header d-flex align-items-center">
            <a class="navbar-brand" href="/dashboards">
                <img src="assets/img/brand/new_blue.png" class="navbar-brand-img" alt="...">
            </a>
            <div class="ml-auto">
                <!-- Sidenav toggler -->
                <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar-inner">
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <!-- Nav items -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?= ($_SESS['page'] == 'Dashboard') ? 'active' : '' ?>" href="dashboard.php" >
                            <i class="ni ni-shop text-primary"></i>
                            <span class="nav-link-text"><?php echo $lan['DASHBOARD']; ?></span>
                        </a>
                    </li>
					<li class="nav-item" id="expense" data-color="#eb006d">
                        <a class="nav-link <?=/*done*/ ($_SESS['page'] ==''/* 'View table'.$Vnlink[5]*/ || $_SESS['page'] =='' /*'Edit form view'.$Vnlink[5]*/ || $_SESS['page'] =='' /*'Add form view'.$Vnlink[5]*/ || $_SESS['page'] == '' /*'Edit table'.$Vnlink[5]*/) ? 'active' : '' ?>  " href="view_table.php?id=<?= $Vnlink[5].'&my='.$inmonth ?>">
                            <i class="ni ni-chart-bar-32 text-green"></i>
                            <span class="nav-link-text"><?= $Vn[5]?></span>
                        </a>
                    </li>
				</ul>
				<h6 class="navbar-heading p-0 text-muted"><?php echo $lan['PRODUCT']; ?></h6>
                    <!--li class="nav-item">
                        <a class="nav-link <?//= ($_SESS['page'] == 'Create table') ? 'active' : '' ?>" href="create_table.php" >
                            <i class="fa fa-plus text-orange"></i>
                            <span class="nav-link-text">Table Create</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?//= ($_SESS['page'] == 'List of table') ? 'active' : '' ?>" href="list_table.php">
                            <i class="ni ni-align-left-2 text-default"></i>
                            <span class="nav-link-text">Tables</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?//= ($_SESS['page'] == 'API DOC') ? 'active' : '' ?>  " href="api_doc.php">
                            <i class="ni ni-app text-success"></i>
                            <span class="nav-link-text">API</span>
                        </a>
                    </li-->
				<ul class="navbar-nav">
					<li class="nav-item">
						<div class="legend_data" style="line-height: 18px;word-break: break-word;overflow: hidden;">
							<div class="legend_data" style="float: left;">
                         <a class="nav-link <?= ($_SESS['page'] ==''/* 'View table'.$Vnlink[0]*/ || $_SESS['page'] ==''/* 'Edit form view'.$Vnlink[0]*/ || $_SESS['page'] ==''/* 'Add form view'.$Vnlink[0]*/ || $_SESS['page'] ==''/* 'Edit table'.$Vnlink[0]*/) ? 'active' : '' ?>  " href="view_table.php?id=<?= $Vnlink[0] ?>">
                            <i class="ni ni-ungroup text-brown"></i>
                            <span class="nav-link-text"><?= $Vn[0]?></span>
                        </a>
							</div>
							<div class="legend_data_content" style="justify-content: center; margin-top: 12px;">
								<span style="display: inline-block;vertical-align: middle;" ><i class="" style="color: #FF0004;"></i></span>	 
						 </div>
						</div>
                    </li>
					<li class="nav-item">
						<div class="legend_data" style="line-height: 18px;word-break: break-word;overflow: hidden;">
							<div class="legend_data" style="float: left;">
                         <a class="nav-link <?=/*done*/ ($_SESS['page'] ==''/* 'View table'.$Vnlink[7]*/ || $_SESS['page'] ==''/* 'Edit form view'.$Vnlink[7]*/ || $_SESS['page'] ==''/* 'Add form view'.$Vnlink[7]*/ || $_SESS['page'] =='' /*'Edit table'.$Vnlink[7]*/) ? 'active' : '' ?>  " href="view_table.php?id=<?= $Vnlink[7] ?>">
                            <i class="ni ni-active-40 text-purple"></i>
                            <span class="nav-link-text"><?= $Vn[7]?></span>
                        </a>
							</div>
							<div class="legend_data_content" style="justify-content: center; margin-top: 12px;">
								<span style="display: inline-block;vertical-align: middle;"><i class="" style="color: #FF0004;"></i></span>	 
						 </div>
						</div>
                    </li>
					<li class="nav-item">
                        <a class="nav-link <?=/*done*/ ($_SESS['page'] ==''/* 'View table'.$Vnlink[6]*/ || $_SESS['page'] ==''/* 'Edit form view'.$Vnlink[6]*/ || $_SESS['page'] ==''/* 'Add form view'.$Vnlink[6]*/ || $_SESS['page'] ==''/* 'Edit table'.$Vnlink[6]*/) ? 'active' : '' ?>  " href="view_table.php?id=<?= $Vnlink[6] ?>">
                            <i class="ni ni-ruler-pencil text-red"></i>
                            <span class="nav-link-text"><?= $Vn[6]?></span>
                        </a>
                    </li>
					<li class="nav-item">
                        <a class="nav-link <?=/*done*/ ($_SESS['page'] ==''/* 'View table'.$Vnlink[4]*/ || $_SESS['page'] ==''/* 'Edit form view'.$Vnlink[4]*/ || $_SESS['page'] ==''/* 'Add form view'.$Vnlink[4]*/ || $_SESS['page'] ==''/* 'Edit table'.$Vnlink[4]*/) ? 'active' : '' ?>  " href="view_table.php?id=<?= $Vnlink[4] ?>">
                            <i class="ni ni-chart-pie-35 text-pink"></i>
                            <span class="nav-link-text"><?= $Vn[4]?></span>
                        </a>
                    </li>
				</ul>
				
				<h6 class="navbar-heading p-0 text-muted"><?php echo $lan['CUSTOMER']; ?></h6>
				
				<ul class="navbar-nav">
					<li class="nav-item">
                        <a class="nav-link <?=/*done*/ ($_SESS['page'] ==''/* 'View table'.$Vnlink[10]*/ || $_SESS['page'] ==''/* 'Edit form view'.$Vnlink[10]*/ || $_SESS['page'] ==''/* 'Add form view'.$Vnlink[10]*/ || $_SESS['page'] =='' /*'Edit table'.$Vnlink[10]*/) ? 'active' : '' ?>  " href="view_table.php?id=<?= $Vnlink[10] ?>">
                            <i class="ni ni-bus-front-12 text-orange"></i>
                            <span class="nav-link-text"><?= $Vn[10]?></span>
                        </a>
                    </li>
					<li class="nav-item">
                        <a class="nav-link <?= ($_SESS['page'] ==''/* 'View table'.$Vnlink[2]*/ || $_SESS['page'] ==''/* 'Edit form view'.$Vnlink[2]*/ || $_SESS['page'] ==''/* 'Add form view'.$Vnlink[2]*/ || $_SESS['page'] =='' /*'Edit table'.$Vnlink[2]*/) ? 'active' : '' ?>  " href="view_table.php?id=<?= $Vnlink[2] ?>">
                            <i class="ni ni-circle-08 text-blue"></i>
                            <span class="nav-link-text"><?= $Vn[2]?></span>
                        </a>
                    </li>
				</ul>
				
				<h6 class="navbar-heading p-0 text-muted"><?php echo $lan['SALESREP']; ?></h6>
				
				<ul class="navbar-nav">
					<li class="nav-item">
                        <a class="nav-link <?=/*done*/ ($_SESS['page'] ==''/* 'View table'.$Vnlink[3]*/ || $_SESS['page'] ==''/* 'Edit form view'.$Vnlink[3]*/ || $_SESS['page'] ==''/* 'Add form view'.$Vnlink[3]*/ || $_SESS['page'] ==''/* 'Edit table'.$Vnlink[3]*/) ? 'active' : '' ?>  " href="view_table.php?id=<?= $Vnlink[3] ?>">
                            <i class="ni ni-tablet-button text-black"></i>
                            <span class="nav-link-text"><?= $Vn[3]?></span>
                        </a>
                    </li>
					<li class="nav-item">
                        <a class="nav-link <?=/*done*/ ($_SESS['page'] ==''/* 'View table'.$Vnlink[11]*/ || $_SESS['page'] ==''/* 'Edit form view'.$Vnlink[11]*/ || $_SESS['page'] ==''/* 'Add form view'.$Vnlink[11]*/ || $_SESS['page'] ==''/* 'Edit table'.$Vnlink[11]*/) ? 'active' : '' ?>  " href="view_table.php?id=<?= $Vnlink[11] ?>">
                            <i class="ni ni-briefcase-24 text-green"></i>
                            <span class="nav-link-text"><?= $Vn[11]?></span>
                        </a>
                    </li>
                </ul>
				
				<h6 class="navbar-heading p-0 text-muted"><?php echo $lan['BILL']; ?></h6>
				
				<ul class="navbar-nav">
					
					<li class="nav-item">
                        <a class="nav-link <?=/*done*/ ($_SESS['page'] ==''/* 'View table'.$Vnlink[8]*/ || $_SESS['page'] ==''/* 'Edit form view'.$Vnlink[8]*/ || $_SESS['page'] ==''/* 'Add form view'.$Vnlink[8]*/ || $_SESS['page'] ==''/* 'Edit table'.$Vnlink[8]*/) ? 'active' : '' ?>  " href="view_table.php?id=<?= $Vnlink[8].'&my='.$inmonth ?>">
                            <i class="ni ni-single-copy-04 text-pink"></i>
                            <span class="nav-link-text"><?= $Vn[8]?></span>
                        </a>
                    </li>
					<li class="nav-item">
                        <a class="nav-link <?=/*done*/ ($_SESS['page'] ==''/* 'View table'.$Vnlink[9]*/ || $_SESS['page'] ==''/* 'Edit form view'.$Vnlink[9]*/ || $_SESS['page'] ==''/* 'Add form view'.$Vnlink[9]*/ || $_SESS['page'] ==''/* 'Edit table'.$Vnlink[9]*/) ? 'active' : '' ?>  " href="view_table.php?id=<?= $Vnlink[9].'&my='.$inmonth ?>">
                            <i class="ni ni-money-coins text-red"></i>
                            <span class="nav-link-text"><?= $Vn[9]?></span>
                        </a>
                    </li>
					<li class="nav-item">
                        <a class="nav-link <?=/*done*/ ($_SESS['page'] ==''/* 'View table'.$Vnlink[12]*/ || $_SESS['page'] ==''/* 'Edit form view'.$Vnlink[12]*/ || $_SESS['page'] ==''/* 'Add form view'.$Vnlink[12]*/ || $_SESS['page'] ==''/* 'Edit table'.$Vnlink[12]*/) ? 'active' : '' ?>  " href="view_table.php?id=<?= $Vnlink[12].'&my='.$inmonth ?>">
                            <i class="ni ni-chart-bar-32 text-green"></i>
                            <span class="nav-link-text"><?= $Vn[12]?></span>
                        </a>
                    </li>
                </ul>
                
               <hr class="my-3">

                <h6 class="navbar-heading p-0 text-muted"><?php echo $lan['CONFIG']; ?></h6>

                <ul class="navbar-nav mb-md-3">
                    <li class="nav-item">
                        <a class="nav-link" href="#navbar-examples" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-examples">
                            <i class="ni ni-ui-04"></i>
                            <span class="nav-link-text"><?php echo $lan['SETTINGS']; ?></span>
                        </a>
                        <div class="collapse" id="navbar-examples">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
									<a href="view_table.php?id=Zinfo" class="nav-link">
										<i class="ni ni-single-02"></i>
										<span class="nav-link-text"><?php echo $lan['PROFILE']; ?></span></a>
                                </li>
                            </ul>
                        </div>
                    </li>
				</ul>
				
				<h6 class="navbar-heading p-0 text-muted"><?php echo $lan['DOC']; ?></h6>
					
				<ul class="navbar-nav mb-md-3">
					<li class="nav-item">
                        <a class="nav-link" href="#" target="_blank">
                            <i class="ni ni-palette"></i>
                            <span class="nav-link-text"><?php echo $lan['GETSTART']; ?></span>
                        </a>
                    </li>
                    <!--li class="nav-item">
                        <a class="nav-link" href="" target="_blank">
                            <i class="ni ni-palette"></i>
                            <span class="nav-link-text">Foundation</span>
                        </a>
                    </li-->
                    <li class="nav-item">
                        <a class="nav-link" href="#" target="_blank">
                            <i class="ni ni-spaceship"></i>
                            <span class="nav-link-text"><?php echo $lan['HELP']; ?></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script>
                $(document).ready(function(){
                    $("li").click(function(){
                        var Color = $(this).attr("data-color");
                        
                        //$(".nav-item").css("background-color", "#fff");
                        $("li").css("background-color", Color);
                        
                    })
</script>