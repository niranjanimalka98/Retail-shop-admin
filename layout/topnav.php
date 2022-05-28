<nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
		
			<div class="form-group mb-0" style="color: rgba(255,255,255,1.00); margin-right: 20px;">
				<h2 id="time" style="font-display:block; color: white"></h2>
			<?php $time = new DateTime('Asia/Colombo');
    			$ctime = $time->format('d/m/Y - H:i:s');
				$inmonth = $time->format('d/m/Y');
				//echo $ctime; ?>
				
					<script type="text/javascript">
					var timeDisplay = document.getElementById("time");

					function refreshTime() {
					  var dateString = new Date().toLocaleString("en-US", {timeZone: "Asia/Colombo"});
					  var formattedString = dateString.replace(", ", " - ");
					  timeDisplay.innerHTML = formattedString;
					}

					setInterval(refreshTime, 1000);
					</script>
			</div>
            <!-- Search form -->
            <!--form class="navbar-search navbar-search-light form-inline mr-sm-3" id="navbar-search-main">
                <div class="form-group mb-0">
                    <div class="input-group input-group-alternative input-group-merge">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input class="form-control" placeholder="Search" type="text">
                    </div>
                </div>
                <button type="button" class="close" data-action="search-close" data-target="#navbar-search-main" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </form-->
            <!-- Navbar links -->
            <ul class="navbar-nav align-items-center ml-md-auto">
                <li class="nav-item d-xl-none">
                    <!-- Sidenav toggler -->
                    <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </div>
                </li>
                <li class="nav-item d-sm-none">
                    <a class="nav-link" href="#" data-action="search-show" data-target="#navbar-search-main">
                        <i class="ni ni-zoom-split-in"></i>
                    </a>
                </li>
                <li class="nav-item dropdown d-none">
                    <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ni ni-bell-55"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right py-0 overflow-hidden">
                        <!-- Dropdown header -->
                        <div class="px-3 py-3">
                            <h6 class="text-sm text-muted m-0">You have <strong class="text-primary">13</strong> notifications.</h6>
                        </div>
                        <!-- List group -->
                        <div class="list-group list-group-flush">
                            <a href="#!" class="list-group-item list-group-item-action">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <!-- Avatar -->
                                        <img alt="Image placeholder" src="assets/img/theme/logo.png" class="avatar rounded-circle">
                                    </div>
                                    <div class="col ml--2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h4 class="mb-0 text-sm">SRI RAM</h4>
                                            </div>
                                            <div class="text-right text-muted">
                                                <small>2 hrs ago</small>
                                            </div>
                                        </div>
                                        <p class="text-sm mb-0">Let's meet at Starbucks at 11:30. Wdyt?</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#!" class="list-group-item list-group-item-action">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <!-- Avatar -->
                                        <img alt="Image placeholder" src="assets/img/theme/logo.png" class="avatar rounded-circle">
                                    </div>
                                    <div class="col ml--2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h4 class="mb-0 text-sm">SRI RAM</h4>
                                            </div>
                                            <div class="text-right text-muted">
                                                <small>3 hrs ago</small>
                                            </div>
                                        </div>
                                        <p class="text-sm mb-0">A new issue has been reported for Argon.</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#!" class="list-group-item list-group-item-action">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <!-- Avatar -->
                                        <img alt="Image placeholder" src="assets/img/theme/logo.png" class="avatar rounded-circle">
                                    </div>
                                    <div class="col ml--2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h4 class="mb-0 text-sm">SRI RAM</h4>
                                            </div>
                                            <div class="text-right text-muted">
                                                <small>5 hrs ago</small>
                                            </div>
                                        </div>
                                        <p class="text-sm mb-0">Your posts have been liked a lot.</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#!" class="list-group-item list-group-item-action">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <!-- Avatar -->
                                        <img alt="Image placeholder" src="assets/img/theme/logo.png" class="avatar rounded-circle">
                                    </div>
                                    <div class="col ml--2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h4 class="mb-0 text-sm">SRI RAM</h4>
                                            </div>
                                            <div class="text-right text-muted">
                                                <small>2 hrs ago</small>
                                            </div>
                                        </div>
                                        <p class="text-sm mb-0">Let's meet at Starbucks at 11:30. Wdyt?</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#!" class="list-group-item list-group-item-action">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <!-- Avatar -->
                                        <img alt="Image placeholder" src="assets/img/theme/logo.png" class="avatar rounded-circle">
                                    </div>
                                    <div class="col ml--2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h4 class="mb-0 text-sm">SRI RAM</h4>
                                            </div>
                                            <div class="text-right text-muted">
                                                <small>3 hrs ago</small>
                                            </div>
                                        </div>
                                        <p class="text-sm mb-0">A new issue has been reported for Argon.</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-- View all -->
                        <a href="#!" class="dropdown-item text-center text-primary font-weight-bold py-3">View all</a>
                    </div>
                </li>
                <li class="nav-item dropdown d-none">
                    <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ni ni-ungroup"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-dark bg-default dropdown-menu-right">
                        <div class="row shortcuts px-4">
                            <a href="#!" class="col-4 shortcut-item">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-red">
                      <i class="ni ni-calendar-grid-58"></i>
                    </span>
                                <small>Calendar</small>
                            </a>
                            <a href="#!" class="col-4 shortcut-item">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-orange">
                      <i class="ni ni-email-83"></i>
                    </span>
                                <small>Email</small>
                            </a>
                            <a href="#!" class="col-4 shortcut-item">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-info">
                      <i class="ni ni-credit-card"></i>
                    </span>
                                <small>Payments</small>
                            </a>
                            <a href="#!" class="col-4 shortcut-item">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-green">
                      <i class="ni ni-books"></i>
                    </span>
                                <small>Reports</small>
                            </a>
                            <a href="#!" class="col-4 shortcut-item">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-purple">
                      <i class="ni ni-pin-3"></i>
                    </span>
                                <small>Maps</small>
                            </a>
                            <a href="#!" class="col-4 shortcut-item">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-yellow">
                      <i class="ni ni-basket"></i>
                    </span>
                                <small>Shop</small>
                            </a>
                        </div>
                    </div>
                </li>
				
				<li class="nav-item dropdown">
                    <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-caret-square-down" style="font-size: 30px"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                         <a href="form_view_table.php?id=GRChange" class="dropdown-item" id="si">
                             <i class="ni ni-ungroup"></i>
                             <span>GR Change Price</span>
                         </a>
                    </div>
                </li>
				
				<li class="nav-item dropdown">
                    <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-file-alt" style="font-size: 30px"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
						
                        <a href="view_table.php?id=Summary<?='&my='.$inmonth ?>" class="dropdown-item">
                             <i class="ni ni-ungroup"></i>
                             <span>Day Summary</span>
                         </a>
                         <a href="view_table.php?id=SummaryMonth<?='&my='.$inmonth ?>" class="dropdown-item">
                             <i class="ni ni-ungroup"></i>
                             <span>Month Summary</span>
                         </a>
                         <!--a href="lang.php?lang=si" class="dropdown-item" id="si">
                             <i class="ni ni-ungroup"></i>
                             <span>සිංහල</span>
                         </a-->
                    </div>
                </li>
				
				<li class="nav-item dropdown">
                    <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-language" style="font-size: 30px"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
						
                        <a href="lang.php?lang=en" class="dropdown-item">
                             <i class="ni ni-ungroup"></i>
                             <span>English</span>
                         </a>
                         <!--a href="lang.php?lang=ta" class="dropdown-item">
                             <i class="ni ni-ungroup"></i>
                             <span>தமிழ்</span>
                         </a-->
                         <!--a href="lang.php?lang=si" class="dropdown-item" id="si">
                             <i class="ni ni-ungroup"></i>
                             <span>සිංහල</span>
                         </a-->
                    </div>
                </li>
            </ul>
            <ul class="navbar-nav align-items-center ml-auto ml-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="media align-items-center">
                  <span class="avatar avatar-sm rounded-circle">
                    <img alt="Image placeholder" src="assets/img/theme/logo.png">
                  </span>
                            <div class="media-body ml-2 d-none d-lg-block">
                                <span class="mb-0 text-sm  font-weight-bold"><?php echo $lan['OWNER']; ?></span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                       <div class="dropdown-header noti-title">
                             <h6 class="text-overflow m-0"><?php echo $lan['WELCOME']; ?></h6>
                         </div>
                        <a href="view_table.php?id=Zinfo" class="dropdown-item">
                             <i class="ni ni-single-02"></i>
                             <span><?php echo $lan['PROFILE']; ?></span>
                         </a>
                         <a href="exported.php" class="dropdown-item">
                             <i class="fa fa-arrow-right"></i>
                             <span><?php echo $lan['EXPORTS']; ?></span>
                         </a>
                         <a href="apps.php" class="dropdown-item">
                             <i class="ni ni-app"></i>
                             <span><?php echo $lan['APPS']; ?></span>
                         </a>
						
						<div class="legend_data" style="line-height: 18px;word-break: break-word;overflow: hidden;">
							<div class="legend_data" style="float: left;">
                         <span class="dropdown-item" onClick=window.open("https://m.me/jkdesignslk","Support","width=550,height=600,left=150,top=200,toolbar=0,status=0,");>
                             <i class="ni ni-support-16"></i>
                             <span><?php echo $lan['SUPPORT']; ?> </span></span>
							</div>
							<div class="legend_data_content" style="justify-content: center; margin-top: 6px;">
								<span style="display: inline-block;vertical-align: middle;" onClick=window.open("https://www.youtube.com/watch?v=52SjH47iqI0&list=PLjddSTQJzBeoSAvAxzUn0U9i6CSdJeE18","Video","width=720,height=480,left=150,top=200,toolbar=0,status=0,");><i class="fab fa-youtube" style="color: #FF0004;"></i></span>	 
						 </div>
						</div>
                         <div class="dropdown-divider"></div>
                        <a href="logout.php?key=M1g4Vlk2QllHOFFJTDA3amt1cWpsUnNKOmhoWkNlSEg0SzFuVVJ2" class="dropdown-item">
                            <i class="ni ni-user-run"></i>
                            <span><?php echo $lan['LOGOUT']; ?></span>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script type="text/javascript">
	function doSomething() {
    window.open("https://m.me/jkdesignslk","Support","width=550,height=600,left=150,top=200,toolbar=0,status=0,");
}
</script>
<!-- Header -->