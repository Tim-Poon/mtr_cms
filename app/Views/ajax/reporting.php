<!-- widget grid -->
<section id="widget-grid" class="">

	<!-- row -->
	<div class="row">
        <article class="col-sm-12 col-md-12 col-lg-12">
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-blueDark"
				data-widget-editbutton="false"
				data-widget-colorbutton="false"
				data-widget-deletebutton="false"
				data-widget-togglebutton="false"
				data-widget-sortable="false" id="repoerting_map_data">

				<header>
					<span class="widget-icon"> <i class="fa fa-map-marker"></i> </span>
					<h2>Trajectory Reviewer</Strong></h2>
				</header>

				<!-- widget div-->
				<div>
					<!-- widget content -->
					<div class="widget-body">
						<div class="row no-space">
						</div>
					</div>
					<!-- end widget content -->				
				</div>
				<!-- end widget div -->
			</div>
			<!-- end widget -->
		</article>
	</div>
	<!-- end row -->

    <div class="row">
        <article class="col-sm-12 col-md-12 col-lg-3">

        <!-- new widget -->
        <div class="jarviswidget jarviswidget-color-blueDark"
            data-widget-editbutton="false"
            data-widget-colorbutton="false"
            data-widget-deletebutton="false"
            data-widget-togglebutton="false"
            data-widget-sortable="false">

            <header>
                <span class="widget-icon"> <i class="fa fa-calendar"></i> </span>
                <h2> Choose Date </h2>
                <div class="widget-toolbar">
                    <!-- add: non-hidden - to disable auto hide -->
                    <div class="btn-group">
                        <button class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown">
                            Showing <i class="fa fa-caret-down"></i>
                        </button>
                        <ul class="dropdown-menu js-status-update pull-right">
                            <li>
                                <a href="javascript:void(0);" id="mt">Month</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" id="ag">Agenda</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" id="td">Today</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- widget div-->
            <div>
                <!-- widget edit box -->
                <div class="jarviswidget-editbox">

                    <input class="form-control" type="text">

                </div>
                <!-- end widget edit box -->

                <div class="widget-body no-padding">
                    <!-- content goes here -->
                    <div class="widget-body-toolbar">

                        <div id="calendar-buttons">

                            <div class="btn-group">
                                <a href="javascript:void(0)" class="btn btn-default btn-xs" id="btn-prev"><i class="fa fa-chevron-left"></i></a>
                                <a href="javascript:void(0)" class="btn btn-default btn-xs" id="btn-next"><i class="fa fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div id="calendar"></div>

                    <!-- end content -->
                </div>

            </div>
            <!-- end widget div -->
        </div>
        <!-- end widget -->
        </article>
        <article class="col-sm-12 col-md-12 col-lg-9">

            <!-- new widget -->
            <div class="jarviswidget jarviswidget-color-blueDark" data-widget-colorbutton="false" 
                data-widget-editbutton="false"
                data-widget-colorbutton="false"
                data-widget-deletebutton="false"
                data-widget-togglebutton="false"
                data-widget-sortable="false">

                <header>
                    <span class="widget-icon"> <i class="fa fa-map-marker"></i> </span>
                    <h2>Reports</h2>
                </header>

                <!-- widget div-->
                <div>
                    <div class="widget-body no-padding">
                        <!-- content goes here -->
                        <table id="datatable_reports" class="table table-bordered smart-form table-hover">
                            <thead>
                                <tr>
                                <th class="text-align-center"> <i class="fa fa-building"></i> Date</th>
                                    <th class="text-align-center"> <i class="fa fa-building"></i> ID</th>
                                    <th class="text-align-center"> <i class="fa fa-calendar"></i> Site&Sensor</th>
                                    <th class="text-align-center"> <i class="glyphicon glyphicon-send"></i> Shop</th>
                                    <th class="text-align-center"> <i class="glyphicon glyphicon-send"></i> Period</th>
                                    <th class="text-align-center"> <i class="glyphicon glyphicon-send"></i> Remark</th>
                                </tr>
                                <tr class="second">
                                    <td>
                                        <label class="input">
                                            <input type="text" name="search_date" placeholder="Date" class="search_init">
                                        </label>
                                    </td>
                                    <td>
                                        <label class="input">
                                            <input type="text" name="search_date" placeholder="ID" class="search_init">
                                        </label>
                                    </td>
                                    <td>
                                        <label class="input">
                                            <input type="text" name="search_src_type" placeholder="Site&Sensor" class="search_init">
                                        </label>	
                                    </td>
                                    <td>
                                        <label class="input">
                                            <input type="text" name="search_content" placeholder="Shop" class="search_init">
                                        </label>	
                                    </td>
                                    <td>
                                        <label class="input">
                                            <input type="text" name="search_content" placeholder="Period" class="search_init">
                                        </label>	
                                    </td>
                                    <td>
                                        <label class="input">
                                            <input type="text" name="search_content" placeholder="Remark" class="search_init">
                                        </label>	
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($reports as $report_item){ 
                                        if ($report_item['alert'] == 'Y') {
                                            $class = 'danger';
                                        }else {
                                            $class = 'success';
                                        }
                                    ?>
                                    <tr class= "<?= $class?>">
                                        <td class="text-align-center"><?= $report_item['delivery_date']?></td>
                                        <td class="text-align-center"><?= $report_item['id']?></td>
                                        <td class="text-align-center"><Strong><?= $report_item['site'].'-'.$report_item['sensor']?></Strong></td>
                                        <td class="text-align-center"><?= $report_item['shop_name'].' ('.$report_item['shop_id'].')'?></td>
                                        <td class="text-align-center"><?= $report_item['time_period']?></td>
                                        <td class="text-align-center"><?php if ($report_item['remark']){echo '<i class="fa fa-check-circle"> '; echo $report_item['remark']; }?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <!-- end content -->
                    </div>
                </div>
                <!-- end widget div -->
            </div>
            <!-- end widget -->

        </article>
    </div>

</section>
<!-- end widget grid -->

<script type="text/javascript">
	
	/*
	 * FULL CALENDAR JS
	 */
	
	// Load Calendar dependency then setup calendar
	loadScript("<?= base_url('public/js/plugin/fullcalendar/jquery.fullcalendar.min.js')?>", setupCalendar);
	
	function setupCalendar() {
	
	    if ($("#calendar").length) {
	        var date = new Date();
	        var d = date.getDate();
	        var m = date.getMonth();
	        var y = date.getFullYear();
	        var calendar = $('#calendar').fullCalendar({

	            selectable: false,
	            unselectAuto: false,
	            disableResizing: false,
	
	            header: {
	                left: 'title', //,today
	                center: 'prev, next, today',
	                right: 'month, agendaWeek, agenDay' //month, agendaDay,
				},
				
				eventClick: function (arg) {
					// window.location.href = arg.title;
					// window.location.href = 'maintenance';
					// console.log(arg);
				},

	            eventRender: function (event, element, icon) {
	                if (!event.description == "") {
	                    element.find('.fc-event-title').append("<br/><span class='ultra-light'>" + event.description +
	                        "</span>");
	                }
	                if (!event.icon == "") {
	                    element.find('.fc-event-title').append("<i class='air air-top-right fa " + event.icon +
	                        " '></i>");
	                }
				},
				events: [<?php foreach($reports_date as $report){echo json_encode($report).',';};?>],
	        });
	
	    };
	
	    /* hide default buttons */
	    $('.fc-header-right, .fc-header-center').hide();
	}

	// calendar prev
	$('#calendar-buttons #btn-prev').click(function () {
	    $('.fc-button-prev').click();
	    return false;
	});
	
	// calendar next
	$('#calendar-buttons #btn-next').click(function () {
	    $('.fc-button-next').click();
	    return false;
	});
	
	// calendar today
	$('#calendar-buttons #btn-today').click(function () {
	    $('.fc-button-today').click();
	    return false;
	});
	
	// sampling
	// calendar month
	$('#mt').click(function () {
		$('#calendar').fullCalendar('changeView', 'month');
	});
	
	// calendar agenda week
	$('#ag').click(function () {
	    $('#calendar').fullCalendar('changeView', 'agendaWeek');
	});
	
	// calendar agenda day
	$('#td').click(function () {
	    $('#calendar').fullCalendar('changeView', 'agendaDay');
	});

	loadScript("<?= base_url('public/js/plugin/datatables/datatables.min.js')?>", dt_2);

	function dt_2() {
		loadScript("<?= base_url('public/js/plugin/datatables/ColReorder-1.5.2/js/dataTables.colReorder.min.js')?>", dt_3);
	}

	function dt_3() {
		loadScript("<?= base_url('public/js/plugin/datatables/FixedColumns-3.3.1/js/dataTables.fixedColumns.min.js')?>", dt_4);
	}

	function dt_4() {
		loadScript("<?= base_url('public/js/plugin/datatables/dataTables.colVis.js')?>", dt_6);
	}

	function dt_6() {
		loadScript("<?= base_url('public/js/plugin/datatables/dataTables.tableTools.min.js')?>", dt_7);
	}

	function dt_7() {
		loadScript("<?= base_url('public/js/plugin/datatables/DataTables-1.10.22/js/dataTables.bootstrap4.min.js')?>", runDataTables);
	}

	function runDataTables() {

		/* END BASIC */

		/* Add the events etc before DataTables hides a column */
		$("#datatable_reports thead input").keyup(function() {
			oTable.fnFilter(this.value, oTable.oApi._fnVisibleToColumnIndex(oTable.fnSettings(), $("thead input").index(this)));
		});

		$("#datatable_reports thead input").each(function(i) {
			this.initVal = this.value;
		});
		$("#datatable_reports thead input").focus(function() {
			if (this.className == "search_init") {
				this.className = "";
				this.value = "";
			}
		});
		$("#datatable_reports thead input").blur(function(i) {
			if (this.value == "") {
				this.className = "search_init";
				this.value = this.initVal;
			}
		});

        $("#datatable_reports").on("click","tr",function(){
            table = $('#datatable_reports').DataTable();
            var data = table.row(this).data();
            if (data){
            	$.get('<?= base_url('reporting/full_data')?>/'+data[0]+'/'+data[1], '', function(result){
	                $('#repoerting_map_data').html(result);
	            });
            }
            
        });

		var oTable = $('#datatable_reports').dataTable({
			dom : "<'dt-top-row'><'dt-wrapper't><'dt-row dt-bottom-row'ip>",
            // "sPaginationType" : "bootstrap_full",
			//"sDom" : "t<'row dt-wrapper'<'col-sm-6'i><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'>>",
			"oLanguage" : {
				"sSearch" : "Search all columns:"
			},
			"bSortCellsTop" : true,
			order: [[0, "desc"]],
		});
		/* END TABLE TOOLS */
	}
</script>