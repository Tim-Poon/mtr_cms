<!-- widget grid -->
<section id="widget-grid" class="">

	<!-- row -->
	<div class="row">

		<article class="col-sm-12 col-md-12 col-lg-6">

		<!-- new widget -->
			<div class="jarviswidget" id="wid-id-2" data-widget-colorbutton="false" data-widget-editbutton="false">

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
					<span class="widget-icon"> <i class="fa fa-map-marker"></i> </span>
					<h2>Status</h2>
				</header>

				<!-- widget div-->
				<div>
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<div>
							<label>Title:</label>
							<input type="text" />
						</div>
					</div>
					<!-- end widget edit box -->

					<div class="widget-body no-padding">
						<!-- content goes here -->

						<table class="table table-striped table-hover table-condensed">
							<thead>
								<tr>
									<th>id</th>
									<th class="text-align-center">val</th>
									<th class="text-align-center">last_seen</th>
									<th class="text-align-center">lasting_time</th>
									<th class="text-align-center">recent_raw_flag</th>
									<th class="text-align-center">recent_loc_flag</th>
									<th class="text-align-center">vm</th>
								</tr>
							</thead>
							<tbody id='statusbody'>
							</tbody>
							<tfoot>
								<tr>
									<td colspan=5>
									<ul class="pagination pagination-xs no-margin">
										<li class="prev disabled">
											<a href="javascript:void(0);">Previous</a>
										</li>
										<li class="active">
											<a href="javascript:void(0);">1</a>
										</li>
										<li>
											<a href="javascript:void(0);">2</a>
										</li>
										<li>
											<a href="javascript:void(0);">3</a>
										</li>
										<li class="next">
											<a href="javascript:void(0);">Next</a>
										</li>
									</ul></td>
								</tr>
							</tfoot>
						</table>

						<!-- end content -->

					</div>

				</div>
				<!-- end widget div -->
			</div>
			<!-- end widget -->

			<!-- new widget -->
			<div class="jarviswidget" id="wid-id-2" data-widget-colorbutton="false" data-widget-editbutton="false">

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
					<span class="widget-icon"> <i class="fa fa-map-marker"></i> </span>
					<h2>Logger</h2>
				</header>

				<!-- widget div-->
				<div>
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<div>
							<label>Title:</label>
							<input type="text" />
						</div>
					</div>
					<!-- end widget edit box -->

					<div class="widget-body no-padding">
						<!-- content goes here -->
						<table id="datatable_fixed_column" class="table table-bordered smart-form">
							<thead>
								<tr>
									<th> <i class="fa fa-building"></i> date</th>
									<th> <i class="fa fa-calendar"></i> s-type</th>
									<th> <i class="glyphicon glyphicon-send"></i> content</th>
								</tr>
								<tr class="second">
									<td>
										<label class="input">
											<input type="text" name="search_date" placeholder="Filter time" class="search_init">
										</label>
									</td>
									<td>
										<label class="input">
											<input type="text" name="search_src_type" placeholder="Filter source type" class="search_init">
										</label>	
									</td>
									<td>
										<label class="input">
											<input type="text" name="search_content" placeholder="Filter content" class="search_init">
										</label>	
									</td>
								</tr>
							</thead>
							<tbody id='logsbody'>
								<?= $logs ?>
							</tbody>
						</table>

						<!-- end content -->

					</div>

				</div>
				<!-- end widget div -->
			</div>
			<!-- end widget -->

			

		</article>

		<article class="col-sm-12 col-md-12 col-lg-6">

			<!-- new widget -->
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-3" data-widget-colorbutton="false">

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
					<span class="widget-icon"> <i class="fa fa-calendar"></i> </span>
					<h2> Reporting </h2>
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

	</div>

	<!-- end row -->

</section>
<!-- end widget grid -->

<script type="text/javascript">
	// DO NOT REMOVE : GLOBAL FUNCTIONS!
	pageSetUp();
	
	/*
	 * PAGE RELATED SCRIPTS
	 */
	
	$(".js-status-update a").click(function () {
	    var selText = $(this).text();
	    $this = $(this);
	    $this.parents('.btn-group').find('.dropdown-toggle').html(selText + ' <span class="caret"></span>');
	    $this.parents('.dropdown-menu').find('li').removeClass('active');
	    $this.parent().addClass('active');
	});
	
	/*
	 * TODO: add a way to add more todo's to list
	 */
	
	// initialize sortable
	$(function () {
	    $("#sortable1, #sortable2").sortable({
	        handle: '.handle',
	        connectWith: ".todo",
	        update: countTasks
	    }).disableSelection();
	});
	
	// check and uncheck
	$('.todo .checkbox > input[type="checkbox"]').click(function () {
	    $this = $(this).parent().parent().parent();
	
	    if ($(this).prop('checked')) {
	        $this.addClass("complete");
	
	        // remove this if you want to undo a check list once checked
	        //$(this).attr("disabled", true);
	        $(this).parent().hide();
	
	        // once clicked - add class, copy to memory then remove and add to sortable3
	        $this.slideUp(500, function () {
	            $this.clone().prependTo("#sortable3").effect("highlight", {}, 800);
	            $this.remove();
	            countTasks();
	        });
	    } else {
	        // insert undo code here...
	    }
	
	})
	// count tasks
	function countTasks() {
	
	    $('.todo-group-title').each(function () {
	        $this = $(this);
	        $this.find(".num-of-tasks").text($this.next().find("li").size());
	    });
	
	}
	/*
	 * FULL CALENDAR JS
	 */
	
	// Load Calendar dependency then setup calendar
	loadScript("public/js/plugin/fullcalendar/jquery.fullcalendar.min.js", setupCalendar);
	
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
					window.location.href = 'todos';
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
				events: [<?php foreach($todos as $todo){echo json_encode($todo);};?>],
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
		$('#calendar').fullCalendar('changeView', 'agendaMonth');
	});
	
	// calendar agenda week
	$('#ag').click(function () {
	    $('#calendar').fullCalendar('changeView', 'agendaWeek');
	});
	
	// calendar agenda day
	$('#td').click(function () {
	    $('#calendar').fullCalendar('changeView', 'agendaDay');
	});

	/*
	 * CHAT
	 */
	
	$.filter_input = $('#filter-chat-list');
	$.chat_users_container = $('#chat-container > .chat-list-body')
	$.chat_users = $('#chat-users')
	$.chat_list_btn = $('#chat-container > .chat-list-open-close');
	$.chat_body = $('#chat-body');
	
	/*
	 * LIST FILTER (CHAT)
	 */
	
	// custom css expression for a case-insensitive contains()
	jQuery.expr[':'].Contains = function (a, i, m) {
	    return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
	};
	
	function listFilter(list) { // header is any element, list is an unordered list
	    // create and add the filter form to the header
	
	    $.filter_input.change(function () {
	        var filter = $(this).val();
	        if (filter) {
	            // this finds all links in a list that contain the input,
	            // and hide the ones not containing the input while showing the ones that do
	            $.chat_users.find("a:not(:Contains(" + filter + "))").parent().slideUp();
	            $.chat_users.find("a:Contains(" + filter + ")").parent().slideDown();
	        } else {
	            $.chat_users.find("li").slideDown();
	        }
	        return false;
	    }).keyup(function () {
	        // fire the above change event after every letter
	        $(this).change();
	
	    });
	
	}

	loadScript("public/js/plugin/datatables/datatables.min.js", dt_2);

	function dt_2() {
		loadScript("public/js/plugin/datatables/ColReorder-1.5.2/js/dataTables.colReorder.min.js", dt_3);
	}

	function dt_3() {
		loadScript("public/js/plugin/datatables/FixedColumns-3.3.1/js/dataTables.fixedColumns.min.js", dt_4);
	}

	function dt_4() {
		loadScript("public/js/plugin/datatables/dataTables.colVis.js", dt_6);
	}

	// function dt_5() {
	// 	loadScript("js/plugin/datatables/ZeroClipboard.js", dt_6);
	// }

	function dt_6() {
		loadScript("public/js/plugin/datatables/dataTables.tableTools.min.js", dt_7);
	}

	function dt_7() {
		loadScript("public/js/plugin/datatables/DataTables-1.10.22/js/dataTables.bootstrap4.min.js", runDataTables);
	}

	function runDataTables() {

		/* END BASIC */

		/* Add the events etc before DataTables hides a column */
		$("#datatable_fixed_column thead input").keyup(function() {
			oTable.fnFilter(this.value, oTable.oApi._fnVisibleToColumnIndex(oTable.fnSettings(), $("thead input").index(this)));
		});

		$("#datatable_fixed_column thead input").each(function(i) {
			this.initVal = this.value;
		});
		$("#datatable_fixed_column thead input").focus(function() {
			if (this.className == "search_init") {
				this.className = "";
				this.value = "";
			}
		});
		$("#datatable_fixed_column thead input").blur(function(i) {
			if (this.value == "") {
				this.className = "search_init";
				this.value = this.initVal;
			}
		});		
		

		var oTable = $('#datatable_fixed_column').dataTable({
			dom : "<'dt-top-row'><'dt-wrapper't><'dt-row dt-bottom-row'ip>",
			//"sDom" : "t<'row dt-wrapper'<'col-sm-6'i><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'>>",
			"oLanguage" : {
				"sSearch" : "Search all columns:"
			},
			"bSortCellsTop" : true,
			order: [[0, "desc"]],
		});		
		

		/* END TABLE TOOLS */

	}

	load_sensor_status();
	function load_sensor_status() {
    // console.log(url)

		$.ajax({
			type: "GET",
			url: "dashboard/real_time_sensor_status",
			dataType: 'html',
			cache: true, // (warning: this will cause a timestamp and will call the request twice)
			beforeSend: function () {
				// container.html('<h1><i class="fa fa-cog fa-spin"></i> Loading...</h1>');
			},
			success: function (data) {
				// console.log("load_sensor_status");
				$('#statusbody')
					.html(data)
					.delay(100);
				setTimeout(load_sensor_status, 1000);
			},
			error: function (xhr, ajaxOptions, thrownError) {
				// container.html(
				// 	'<h4 style="margin-top:10px; display:block; text-align:left"><i class="fa fa-warning txt-color-orangeDark"></i> Error 404! Page not found.</h4>'
				// );
			},
			async: false
		});

    //console.log("ajax request sent");
	}

</script>