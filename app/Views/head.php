<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->
		
		<title> SmartAdmin </title>
		<meta name="description" content="">
		<meta name="author" content="">
		
		<!-- http://davidbcalhoun.com/2010/viewport-metatag -->
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		
		<!-- Basic Styles -->
		<link rel="stylesheet" type="text/css" media="screen" href="<?= base_url('/public/css/bootstrap.min.css')?>">	
		<link rel="stylesheet" type="text/css" media="screen" href="<?= base_url('/public/css/font-awesome.min.css')?>">

		<!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->
		<link rel="stylesheet" type="text/css" media="screen" href="<?= base_url('/public/css/smartadmin-production.css')?>">
		<link rel="stylesheet" type="text/css" media="screen" href="<?= base_url('/public/css/smartadmin-skins.css')?>">	
		
		<!-- SmartAdmin RTL Support is under construction
			<link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-rtl.css"> -->
		
		<!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
		<link rel="stylesheet" type="text/css" media="screen" href="<?= base_url('/public/css/demo.css')?>">
		
		<!-- FAVICONS -->
		<link rel="shortcut icon" href="<?= base_url('/public/img/favicon/favicon.ico')?>" type="image/x-icon">
		<link rel="icon" href="<?= base_url('/public/img/favicon/favicon.ico')?>" type="image/x-icon">
		
		<!-- GOOGLE FONT -->
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">
		<script src="<?= base_url('/public/js/mapbox/mapbox-gl.js')?>"></script>
		<link href="<?= base_url('/public/css/mapbox-gl.css')?>" rel="stylesheet" />
	
	</head>
	<body class=""> <!-- possible classes: minified, fixed-ribbon, fixed-header, fixed-width-->
		
		<!-- HEADER -->
		<header id="header">
				<div id="logo-group">

                    <!-- PLACE YOUR LOGO HERE -->
                    <span id="logo">
                        <img src="<?= base_url('/public/img/logo.png')?>" alt="SmartAdmin">
                    </span>
                    <!-- END LOGO PLACEHOLDER -->

				</div>
				<!-- END AJAX-DROPDOWN -->
			</div>

			
			<!-- pulled right: nav area -->
			<div class="pull-right">
				
				<!-- collapse menu button -->
				<div id="hide-menu" class="btn-header pull-right">
					<span>
						<a href="javascript:void(0);" title="Collapse Menu"><i class="fa fa-reorder"></i></a>
					</span>
				</div>
				<!-- end collapse menu -->
				
				<!-- logout button -->
				<div id="logout" class="btn-header transparent pull-right">
					<span>
						<a href="login.html" title="Sign Out"><i class="fa fa-sign-out"></i></a>
					</span>
				</div>
				<!-- end logout button -->
			
			</div>
			<!-- end pulled right: nav area -->
			
		</header>
		<!-- END HEADER -->
		
		<!-- Left panel : Navigation area -->
		<!-- Note: This width of the aside area can be adjusted through LESS variables -->
		<aside id="left-panel">
			
			<!-- User info -->
			<div class="login-info">
				<span>
					<!-- User image size is adjusted inside CSS, it should stay as it --> 
					<img src="<?= base_url('/public/img/avatars/sunny.png')?>" alt="me" class="online" />	
					<a href="javascript:void(0);" id="show-shortcut">Welcome: Admin <i class="fa fa-angle-down"></i></a>
				</span>
			</div>
			<!-- end user info -->
			
			<!-- NAVIGATION : This navigation is also responsive 
				 
				 To make this navigation dynamic please make sure to link the node 
				 (the reference to the nav > ul) after page load. Or the navigation 
				 will not initialize.
			-->
			<nav>
				<!-- NOTE: Notice the gaps after each icon usage <i></i>.. 
					 Please note that these links work a bit different than
					 traditional hre="" links. See documentation for details.
					-->
				
				<ul>
					<li class=""><a href="<?=base_url()?>" title="Dashboard"><i class="fa fa-lg fa-fw fa-home"></i>  <span class="menu-item-parent">Dashboard</span></a></li>
					<li><a href="<?=base_url('todos')?>">
						<i class="fa fa-lg fa-fw fa-check-circle-o"></i>  
						<span class="menu-item-parent">Todos</span>
						<span class="badge pull-right inbox-badge"><?= $num_of_todos ?></span></a>
					</li>
					<li class=""><a href="<?=base_url('survey')?>" title="Survey"><i class="fa fa-lg fa-fw fa-truck"></i>  <span class="menu-item-parent">Survey</span></a></li>
					<li class=""><a href="<?=base_url('reporting')?>" title="Reporting"><i class="fa fa-lg fa-fw fa-file-text"></i>  <span class="menu-item-parent">Reporting</span></a></li>

					<li><a href="#"><i class="fa fa-lg fa-fw fa-map-marker"></i>  <span class="menu-item-parent">Sites</span></a>
						<ul>
							<li><a href="site">KOB</a></li>
							<li><a href="site/YMT">YMT</a></li>
							<li><a href="site/CEN">CEN</a></li>
						</ul>
					</li>
					<li><a href="#"><i class="fa fa-lg fa-fw fa-gear"></i>  <span class="menu-item-parent">Tools</span></a>
						<ul>
							<li><a href="<?=base_url('polygon')?>">Polygon</a></li>
							<li><a href="xx/YMT">xx</a></li>xx
							<li><a href="xx/CEN">xx</a></li>
						</ul>
					</li>
					<!-- <li><a href="#"><i class="fa fa-lg fa-fw fa-table"></i>  <span class="menu-item-parent">Tables</span></a>
						 	<ul>
								 <li><a href="ajax/table.html">Normal Tables</a></li>
								 <li><a href="ajax/datatables.html">Data Tables</a></li>
						 	</ul>
					</li>
					<li><a href="#"><i class="fa fa-lg fa-fw fa-pencil-square-o"></i>  <span class="menu-item-parent">Forms</span></a>
					 	<ul>
							 <li><a href="ajax/form-elements.html">Smart Form Elements</a></li>
							 <li><a href="ajax/form-templates.html">Smart Form Layouts</a></li>
							 <li><a href="ajax/validation.html">Smart Form Validation</a></li>
							 <li><a href="ajax/bootstrap-forms.html">Bootstrap Form Elements</a></li>
							 <li><a href="ajax/plugins.html">Form Plugins</a></li>
							 <li><a href="ajax/wizard.html">Wizards</a></li>
							 <li><a href="ajax/other-editors.html">Bootstrap Editors</a></li>
							 <li><a href="ajax/dropzone.html">Dropzone <span class="badge pull-right inbox-badge bg-color-yellow">new</span></a></li>
					 	</ul>
					</li>
					<li><a href="#"><i class="fa fa-lg fa-fw fa-desktop"></i>  <span class="menu-item-parent">UI Elements</span></a>
						<ul>
							 <li><a href="ajax/general-elements.html">General Elements</a></li>
							 <li><a href="ajax/buttons.html">Buttons</a></li>
							 <li><a href="#">Icons</a>
							 	<ul>
									 <li><a href="ajax/fa.html"><i class="fa fa-plane"></i> Font Awesome</a>
									 <li><a href="ajax/glyph.html"><i class="glyphicon glyphicon-plane"></i> Glyph Icons </a>	
							 	</ul>
							 </li>
							 <li><a href="ajax/grid.html">Grid</a></li>
							 <li><a href="ajax/treeview.html">Tree View</a></li>
							 <li><a href="ajax/nestable-list.html">Nestable Lists</a></li>
							 <li><a href="ajax/jqui.html">JQuery UI</a></li>
						</ul>
					</li>
					<li><a href="#"><i class="fa fa-lg fa-fw fa-folder-open"></i>  <span class="menu-item-parent">6 Level Navigation</span></a>
						<ul>
							<li><a href="#"><i class="fa fa-fw fa-folder-open"></i> 2nd Level</a>
							 	<ul>
									 <li><a href="#"><i class="fa fa-fw fa-folder-open"></i> 3ed Level </a>
									 	<ul>
									 		 <li><a href="#"><i class="fa fa-fw fa-file-text"></i> File</a></li>
											 <li><a href="#"><i class="fa fa-fw fa-folder-open"></i> 4th Level</a>
											 	<ul>
											 		 <li><a href="#"><i class="fa fa-fw fa-file-text"></i> File</a></li>
													 <li><a href="#"><i class="fa fa-fw fa-folder-open"></i> 5th Level</a>
													 	<ul>
													 		<li><a href="#"><i class="fa fa-fw fa-file-text"></i> File</a></li>
													 		<li><a href="#"><i class="fa fa-fw fa-file-text"></i> File</a></li>
													 	</ul>
													 </li>
											 	</ul>
											 </li>
									 	</ul>
									 </li>
							 	</ul>
							 </li>
							 <li><a href="#"><i class="fa fa-fw fa-folder-open"></i> Folder</a>
							 	
								<ul>
									 <li><a href="#"><i class="fa fa-fw fa-folder-open"></i> 3ed Level </a>
									 	<ul>
									 		 <li><a href="#"><i class="fa fa-fw fa-file-text"></i> File</a></li>
											 <li><a href="#"><i class="fa fa-fw fa-file-text"></i> File</a></li>
									 	</ul>
									 </li>
							 	</ul>
							 	
							 </li>
						</ul>
					</li>	
					<li><a href="ajax/calendar.html"><i class="fa fa-lg fa-fw fa-calendar"><em>3</em></i>  <span class="menu-item-parent">Calendar</span></a></li>
					<li><a href="ajax/widgets.html"><i class="fa fa-lg fa-fw fa-list-alt"></i>  <span class="menu-item-parent">Widgets</span></a></li>
					<li><a href="ajax/gallery.html"><i class="fa fa-lg fa-fw fa-picture-o"></i>  <span class="menu-item-parent">Gallery</span></a></li>
					<li><a href="ajax/gmap-xml.html"><i class="fa fa-lg fa-fw fa-map-marker"></i>  <span class="menu-item-parent">Google Map Skins</span><span class="badge bg-color-greenLight pull-right inbox-badge">9</span></a></li>					
					<li><a href="#"><i class="fa fa-lg fa-fw fa-windows"></i>  <span class="menu-item-parent">Miscellaneous</span></a>
						<ul>
							 <li><a href="ajax/typography.html">Typography</a></li>
							 <li><a href="ajax/pricing-table.html">Pricing Tables</a></li>
							 <li><a href="ajax/invoice.html">Invoice</a></li>
							 <li><a href="login.html" target="_top">Login</a></li>
							 <li><a href="register.html" target="_top">Register</a></li>
							 <li><a href="lock.html" target="_top">Locked Screen</a></li>
							 <li><a href="ajax/error404.html">Error 404</a></li>
							 <li><a href="ajax/error500.html">Error 500</a></li>
							 <li><a href="ajax/blank_.html">Blank Page</a></li>
							 <li><a href="ajax/email-template.html">Email Template</a></li>
							 <li><a href="ajax/search.html">Search Page</a></li>
							 <li><a href="ajax/ckeditor.html">CK Editor</a></li>
						</ul>
					</li>						 -->
				</ul>
			</nav>
			<span class="minifyme">
				<i class="fa fa-arrow-circle-left hit"></i>
			</span>

			
		</aside>
		<!-- END NAVIGATION -->
		
		<!-- MAIN PANEL -->
		<div id="main" role="main">
			
			<!-- RIBBON -->
			<div id="ribbon">
				
				<span class="ribbon-button-alignment">
					<lable class="txt-color-white"><div id="time"></div></lable>
				</span>

				<!-- breadcrumb -->
				<ol class="breadcrumb">
					<!-- This is auto generated -->
				</ol>		
				<!-- end breadcrumb -->	
				
				<!-- You can also add more buttons to the 
					 ribbon for further usability 
					 
					 Example below: 
					 
				<span class="ribbon-button-alignment pull-right">
					<span id="search" class="btn btn-ribbon hidden-xs" data-title="search"><i class="fa-grid"></i> Change Grid</span>
					<span id="add" class="btn btn-ribbon hidden-xs" data-title="add"><i class="fa-plus"></i> Add</span>
					<span id="search" class="btn btn-ribbon" data-title="search"><i class="fa-search"></i> <span class="hidden-mobile">Search</span></span>
				</span> -->
				
			</div>
			<!-- END RIBBON -->
			
			<!-- MAIN CONTENT -->
			<div id="content">
				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark">
							<i class="fa <?=$icon?> fa-fw "></i> 
								<a href="<?=base_url($title)?>" style="color:#696969; cursor:pointer"><strong><?=$title?></strong></a> 
							<span>
								<strong style="color:#496949"><?= $sub_title?></strong>
							</span>
						</h1>
					</div>
					<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
						<ul id="sparks" class="">
							<li class="sparks-info">
								<h5> CPU <span class="txt-color-blue"><i class="fa fa-info"></i>&nbsp;<label id="cpu"></label></span></h5>
								<div class="sparkline txt-color-blue hidden-mobile hidden-md hidden-sm">
									11, 12, 13, 14, 15, 16, 10, 11, 12, 13, 14, 15, 16
								</div>
							</li>
							<li class="sparks-info">
								<h5> MEMORY <span class="txt-color-purple"><i class="fa fa-info"></i>&nbsp;<label id="memory"></label></span></h5>
								<div class="sparkline txt-color-purple hidden-mobile hidden-md hidden-sm">
									110,150,300,130,400,240,220,310,220,300, 270, 210
								</div>
							</li>
							<li class="sparks-info">
								<h5> STORAGE <span class="txt-color-greenDark"><i class="fa fa-info"></i>&nbsp;<label id="storage"></label></span></h5>
								<div class="sparkline txt-color-greenDark hidden-mobile hidden-md hidden-sm">
									110,150,300,130,400,240,220,310,220,300, 270, 210
								</div>
							</li>
						</ul>
					</div>
				</div>
