<style>

    #calendar {
      /* max-width: 1100px; */
      /* margin: 40px auto; */
    }

  </style>
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
				data-widget-sortable="false" id="testingtt">

				<header>
					<span class="widget-icon"> <i class="fa fa-map-marker"></i> </span>
					<h2>Information</Strong></h2>
				</header>

				<!-- widget div-->
				<div>
					<!-- widget content -->
					<div class="widget-body">
                        <div id='analysis_sites'></div>
                        
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
                    <h2>Logger - coming soon</h2>
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
                                        if ($report_item['alert_time']) {
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
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.7.0/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.7.0/main.min.js'></script>
<script type="text/javascript">
	
	/*
	 * FULL CALENDAR JS
	 */
	
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
        selectable: true,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        dateClick: function(info) {
            var reg = new RegExp("-","g");
            date = info.dateStr.replace(reg,"");
            sites = <?= json_encode($site_names) ?>;
            res = '';
            for (idx = 0; idx < sites.length; idx++) {
                res += "<a href='<?= base_url('analysis/') ?>/" + date + "/" + sites[idx].site_name + "' class='btn btn-success'>" + sites[idx].site_name + "</a> ";
            };
            $('#analysis_sites').html(res);
        },
        // select: function(info) {
        //     alert('selected ' + info.startStr + ' to ' + info.endStr);
        // }
        events: [<?php #foreach($reports_date as $report){echo json_encode($report).',';};?>],
        });
        
        calendar.render();
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
            $.get('<?= base_url('reporting/full_data')?>/'+data[0]+'/'+data[1], '', function(result){
                $('#testingtt').html(result);
            });
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