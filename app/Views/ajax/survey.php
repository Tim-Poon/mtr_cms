<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-truck fa-fw "></i> 
				<a href="survey" style="color:#696969; cursor:pointer"><strong>Survey</strong></a> 
			<span>
			</span>
		</h1>
	</div>
	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
		<ul id="sparks" class="">
			<li class="sparks-info">
				<h5> My Income <span class="txt-color-blue">$47,171</span></h5>
				<div class="sparkline txt-color-blue hidden-mobile hidden-md hidden-sm">
					1300, 1877, 2500, 2577, 2000, 2100, 3000, 2700, 3631, 2471, 2700, 3631, 2471
				</div>
			</li>
			<li class="sparks-info">
				<h5> Site Traffic <span class="txt-color-purple"><i class="fa fa-arrow-circle-up" data-rel="bootstrap-tooltip" title="Increased"></i>&nbsp;45%</span></h5>
				<div class="sparkline txt-color-purple hidden-mobile hidden-md hidden-sm">
					110,150,300,130,400,240,220,310,220,300, 270, 210
				</div>
			</li>
			<li class="sparks-info">
				<h5> Site Orders <span class="txt-color-greenDark"><i class="fa fa-shopping-cart"></i>&nbsp;2447</span></h5>
				<div class="sparkline txt-color-greenDark hidden-mobile hidden-md hidden-sm">
					110,150,300,130,400,240,220,310,220,300, 270, 210
				</div>
			</li>
		</ul>
	</div>
</div>

<!-- widget grid -->
<section id="widget-grid" class="">

	<!-- row -->
	<div class="row">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
				<!-- widget options:
				usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

				data-widget-colorbutton="false"
				data-widget-editbutton="false"
				data-widget-togglebutton="false"
				data-widget-deletebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-custombutton="false"
				data-widget-collapsed="true"
				data-widget-sortable="false"

				-->
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>Survey Events </h2>

				</header>

				<!-- widget div-->
				<div>
					<!-- widget content -->
					<div class="widget-body no-padding">

						<div class="widget-body-toolbar">
                        </div>
						<table id="dt_basic" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>Event_ID</th>
									<th>Date</th>
									<th>Site</th>
									<th>Remark</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($events as $event) {?>
								<tr>
									<td><strong><a href="survey?id=<?=$event->event_id?>" style="cursor:pointer"><?= $event->event_id?></a></strong></td>
									<td><?= $event->date?></td>
									<td><?= $event->site_name?> </td>
								<td><?php if($event->remark){?><i class="fa fa-check fa-fw "><?php }?></td>
								</tr>
								<?php }?>
							</tbody>
						</table>
                        
					</div>
					<!-- end widget content -->
                    
				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
        </artivle>
        
    </div>
</section>
<!-- end widget grid -->

<link rel="stylesheet" type="text/css" href="css/DataTables-1.10.22/css/dataTables.bootstrap.min.css"/>

<script type="text/javascript">

	// DO NOT REMOVE : GLOBAL FUNCTIONS!
	pageSetUp();
	
	// PAGE RELATED SCRIPTS

	loadDataTableScripts();
	function loadDataTableScripts() {

		loadScript("js/plugin/datatables/datatables.min.js", dt_2);

		function dt_2() {
			loadScript("js/plugin/datatables/ColReorder-1.5.2/js/dataTables.colReorder.min.js", dt_3);
		}

		function dt_3() {
			loadScript("js/plugin/datatables/FixedColumns-3.3.1/js/dataTables.fixedColumns.min.js", dt_4);
		}

		function dt_4() {
			loadScript("js/plugin/datatables/dataTables.colVis.js", dt_6);
		}

		// function dt_5() {
		// 	loadScript("js/plugin/datatables/ZeroClipboard.js", dt_6);
		// }

		function dt_6() {
			loadScript("js/plugin/datatables/dataTables.tableTools.min.js", dt_7);
		}

		function dt_7() {
			loadScript("js/plugin/datatables/DataTables-1.10.22/js/dataTables.bootstrap4.min.js", runDataTables);
		}
	}

	function runDataTables() {

		/*
		 * BASIC
		 */

		$('#dt_basic').dataTable({

			sPaginationType : "full_numbers",
			dom : "<'dt-row dt-top-row'lf><'dt-row'B><'clear'>r<'dt-wrapper't><'dt-row dt-bottom-row'ip>",
			order: [[0, "desc"]],
			buttons: [
				{
					className: 'btn btn-primary btn-primary-sm',
					text: 'New Event',
					action: function ( e, dt, node, config ) {
						window.location.href="surveyNew";
					}
				}
			]
		});

		// $(document).ready(function() {
		// 	var table1 = $('#dt_basic').DataTable();
		// 	$("#dt_basic tbody").on("click","tr th",function(){
		// 		var data = table1.row( this ).data();
		// 		window.location.href="survey?id=" + data[0];
		// 	});
        // });

		/* END BASIC */
	}

</script>
