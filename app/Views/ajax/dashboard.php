<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> Dashboard <span>> My Dashboard</span></h1>
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
				<h5> Site Traffic <span class="txt-color-purple"><i class="fa fa-arrow-circle-up"></i>&nbsp;45%</span></h5>
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
											<input type="text" name="search_date" value="Filter time" class="search_init">
										</label>
									</td>
									<td>
										<label class="input">
											<input type="text" name="search_src_type" value="Filter source type" class="search_init">
										</label>	
									</td>
									<td>
										<label class="input">
											<input type="text" name="search_content" value="Filter content" class="search_init">
										</label>	
									</td>
								</tr>
							</thead>
							<tbody id='logsbody'></tbody>
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

			<!-- new widget -->
			<div class="jarviswidget jarviswidget-color-blue" id="wid-id-4" data-widget-editbutton="false" data-widget-colorbutton="false">

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
					<span class="widget-icon"> <i class="fa fa-check txt-color-white"></i> </span>
					<h2> ToDo's </h2>
					<!-- <div class="widget-toolbar">
					add: non-hidden - to disable auto hide

					</div>-->
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

					<div class="widget-body no-padding smart-form">
						<!-- content goes here -->
						<h5 class="todo-group-title"><i class="fa fa-warning"></i> Critical Tasks (<small class="num-of-tasks">1</small>)</h5>
						<ul id="sortable1" class="todo">
							<li>
								<span class="handle"> <label class="checkbox">
										<input type="checkbox" name="checkbox-inline">
										<i></i> </label> </span>
								<p>
									<strong>Ticket #17643</strong> - Hotfix for WebApp interface issue [<a href="javascript:void(0);" class="font-xs">More Details</a>] <span class="text-muted">Sea deep blessed bearing under darkness from God air living isn't. </span>
									<span class="date">Jan 1, 2014</span>
								</p>
							</li>
						</ul>
						<h5 class="todo-group-title"><i class="fa fa-exclamation"></i> Important Tasks (<small class="num-of-tasks">3</small>)</h5>
						<ul id="sortable2" class="todo">
							<li>
								<span class="handle"> <label class="checkbox">
										<input type="checkbox" name="checkbox-inline">
										<i></i> </label> </span>
								<p>
									<strong>Ticket #1347</strong> - Inbox email is being sent twice <small>(bug fix)</small> [<a href="javascript:void(0);" class="font-xs">More Details</a>] <span class="date">Nov 22, 2013</span>
								</p>
							</li>
							<li>
								<span class="handle"> <label class="checkbox">
										<input type="checkbox" name="checkbox-inline">
										<i></i> </label> </span>
								<p>
									<strong>Ticket #1314</strong> - Call customer support re: Issue <a href="javascript:void(0);" class="font-xs">#6134</a><small>(code review)</small>
									<span class="date">Nov 22, 2013</span>
								</p>
							</li>
							<li>
								<span class="handle"> <label class="checkbox">
										<input type="checkbox" name="checkbox-inline">
										<i></i> </label> </span>
								<p>
									<strong>Ticket #17643</strong> - Hotfix for WebApp interface issue [<a href="javascript:void(0);" class="font-xs">More Details</a>] <span class="text-muted">Sea deep blessed bearing under darkness from God air living isn't. </span>
									<span class="date">Jan 1, 2014</span>
								</p>
							</li>
						</ul>

						<h5 class="todo-group-title"><i class="fa fa-check"></i> Completed Tasks (<small class="num-of-tasks">1</small>)</h5>
						<ul id="sortable3" class="todo">
							<li class="complete">
								<span class="handle" style="display:none"> <label class="checkbox state-disabled">
										<input type="checkbox" name="checkbox-inline" checked="checked" disabled="disabled">
										<i></i> </label> </span>
								<p>
									<strong>Ticket #17643</strong> - Hotfix for WebApp interface issue [<a href="javascript:void(0);" class="font-xs">More Details</a>] <span class="text-muted">Sea deep blessed bearing under darkness from God air living isn't. </span>
									<span class="date">Jan 1, 2014</span>
								</p>
							</li>
						</ul>

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
	 * RUN PAGE GRAPHS
	 */
	
	// Load FLOAT dependencies (related to page)
	loadScript("js/plugin/flot/jquery.flot.cust.js", loadFlotResize);
	
	function loadFlotResize() {
	    loadScript("js/plugin/flot/jquery.flot.resize.js", loadFlotToolTip);
	}
	
	function loadFlotToolTip() {
	    loadScript("js/plugin/flot/jquery.flot.tooltip.js", generatePageGraphs);
	}
	
	function generatePageGraphs() {
	
	    /* TAB 1: UPDATING CHART */
	    // For the demo we use generated data, but normally it would be coming from the server
	
	    var data = [],
	        totalPoints = 200,
	        $UpdatingChartColors = $("#updating-chart").css('color');
	
	    function getRandomData() {
	        if (data.length > 0)
	            data = data.slice(1);
	
	        // do a random walk
	        while (data.length < totalPoints) {
	            var prev = data.length > 0 ? data[data.length - 1] : 50;
	            var y = prev + Math.random() * 10 - 5;
	            if (y < 0)
	                y = 0;
	            if (y > 100)
	                y = 100;
	            data.push(y);
	        }
	
	        // zip the generated y values with the x values
	        var res = [];
	        for (var i = 0; i < data.length; ++i)
	            res.push([i, data[i]])
	        return res;
	    }
	
	    // setup control widget
	    var updateInterval = 1500;
	    $("#updating-chart").val(updateInterval).change(function () {
	
	        var v = $(this).val();
	        if (v && !isNaN(+v)) {
	            updateInterval = +v;
	            $(this).val("" + updateInterval);
	        }
	
	    });
	
	    // setup plot
	    var options = {
	        yaxis: {
	            min: 0,
	            max: 100
	        },
	        xaxis: {
	            min: 0,
	            max: 100
	        },
	        colors: [$UpdatingChartColors],
	        series: {
	            lines: {
	                lineWidth: 1,
	                fill: true,
	                fillColor: {
	                    colors: [{
	                        opacity: 0.4
	                    }, {
	                        opacity: 0
	                    }]
	                },
	                steps: false
	
	            }
	        }
	    };
	
	    var plot = $.plot($("#updating-chart"), [getRandomData()], options);
	
	    /* live switch */
	    $('input[type="checkbox"]#start_interval').click(function () {
	        if ($(this).prop('checked')) {
	            $on = true;
	            updateInterval = 1500;
	            update();
	        } else {
	            clearInterval(updateInterval);
	            $on = false;
	        }
	    });
	
	    function update() {
	        if ($on == true) {
	            plot.setData([getRandomData()]);
	            plot.draw();
	            setTimeout(update, updateInterval);
	
	        } else {
	            clearInterval(updateInterval)
	        }
	
	    }
	
	    var $on = false;
	
	    /*end updating chart*/
	
	    /* TAB 2: Social Network  */
	
	    $(function () {
	        // jQuery Flot Chart
	        var twitter = [
	            [1, 27],
	            [2, 34],
	            [3, 51],
	            [4, 48],
	            [5, 55],
	            [6, 65],
	            [7, 61],
	            [8, 70],
	            [9, 65],
	            [10, 75],
	            [11, 57],
	            [12, 59],
	            [13, 62]
	        ],
	            facebook = [
	                [1, 25],
	                [2, 31],
	                [3, 45],
	                [4, 37],
	                [5, 38],
	                [6, 40],
	                [7, 47],
	                [8, 55],
	                [9, 43],
	                [10, 50],
	                [11, 47],
	                [12, 39],
	                [13, 47]
	            ],
	            data = [{
	                label: "Twitter",
	                data: twitter,
	                lines: {
	                    show: true,
	                    lineWidth: 1,
	                    fill: true,
	                    fillColor: {
	                        colors: [{
	                            opacity: 0.1
	                        }, {
	                            opacity: 0.13
	                        }]
	                    }
	                },
	                points: {
	                    show: true
	                }
	            }, {
	                label: "Facebook",
	                data: facebook,
	                lines: {
	                    show: true,
	                    lineWidth: 1,
	                    fill: true,
	                    fillColor: {
	                        colors: [{
	                            opacity: 0.1
	                        }, {
	                            opacity: 0.13
	                        }]
	                    }
	                },
	                points: {
	                    show: true
	                }
	            }];
	
	        var options = {
	            grid: {
	                hoverable: true
	            },
	            colors: ["#568A89", "#3276B1"],
	            tooltip: true,
	            tooltipOpts: {
	                //content : "Value <b>$x</b> Value <span>$y</span>",
	                defaultTheme: false
	            },
	            xaxis: {
	                ticks: [
	                    [1, "JAN"],
	                    [2, "FEB"],
	                    [3, "MAR"],
	                    [4, "APR"],
	                    [5, "MAY"],
	                    [6, "JUN"],
	                    [7, "JUL"],
	                    [8, "AUG"],
	                    [9, "SEP"],
	                    [10, "OCT"],
	                    [11, "NOV"],
	                    [12, "DEC"],
	                    [13, "JAN+1"]
	                ]
	            },
	            yaxes: {
	
	            }
	        };
	
	        var plot3 = $.plot($("#statsChart"), data, options);
	    });
	
	    // END TAB 2
	
	    // TAB THREE GRAPH //
	    /* TAB 3: Revenew  */
	
	    $(function () {
	
	        var trgt = [
	            [1354586000000, 153],
	            [1364587000000, 658],
	            [1374588000000, 198],
	            [1384589000000, 663],
	            [1394590000000, 801],
	            [1404591000000, 1080],
	            [1414592000000, 353],
	            [1424593000000, 749],
	            [1434594000000, 523],
	            [1444595000000, 258],
	            [1454596000000, 688],
	            [1464597000000, 364]
	        ],
	            prft = [
	                [1354586000000, 53],
	                [1364587000000, 65],
	                [1374588000000, 98],
	                [1384589000000, 83],
	                [1394590000000, 980],
	                [1404591000000, 808],
	                [1414592000000, 720],
	                [1424593000000, 674],
	                [1434594000000, 23],
	                [1444595000000, 79],
	                [1454596000000, 88],
	                [1464597000000, 36]
	            ],
	            sgnups = [
	                [1354586000000, 647],
	                [1364587000000, 435],
	                [1374588000000, 784],
	                [1384589000000, 346],
	                [1394590000000, 487],
	                [1404591000000, 463],
	                [1414592000000, 479],
	                [1424593000000, 236],
	                [1434594000000, 843],
	                [1444595000000, 657],
	                [1454596000000, 241],
	                [1464597000000, 341]
	            ],
	            toggles = $("#rev-toggles"),
	            target = $("#flotcontainer");
	
	        var data = [{
	            label: "Target Profit",
	            data: trgt,
	            bars: {
	                show: true,
	                align: "center",
	                barWidth: 30 * 30 * 60 * 1000 * 80
	            }
	        }, {
	            label: "Actual Profit",
	            data: prft,
	            color: '#3276B1',
	            lines: {
	                show: true,
	                lineWidth: 3
	            },
	            points: {
	                show: true
	            }
	        }, {
	            label: "Actual Signups",
	            data: sgnups,
	            color: '#71843F',
	            lines: {
	                show: true,
	                lineWidth: 1
	            },
	            points: {
	                show: true
	            }
	        }]
	
	        var options = {
	            grid: {
	                hoverable: true
	            },
	            tooltip: true,
	            tooltipOpts: {
	                //content: '%x - %y',
	                //dateFormat: '%b %y',
	                defaultTheme: false
	            },
	            xaxis: {
	                mode: "time"
	            },
	            yaxes: {
	                tickFormatter: function (val, axis) {
	                    return "$" + val;
	                },
	                max: 1200
	            }
	
	        };
	
	        plot2 = null;
	
	        function plotNow() {
	            var d = [];
	            toggles.find(':checkbox').each(function () {
	                if ($(this).is(':checked')) {
	                    d.push(data[$(this).attr("name").substr(4, 1)]);
	                }
	            });
	            if (d.length > 0) {
	                if (plot2) {
	                    plot2.setData(d);
	                    plot2.draw();
	                } else {
	                    plot2 = $.plot(target, d, options);
	                }
	            }
	
	        };
	
	        toggles.find(':checkbox').on('change', function () {
	            plotNow();
	        });
	        plotNow()
	
	    });
	
	}
	
	/*
	 * VECTOR MAP
	 */
	
	data_array = {
	    "US": 4977,
	    "AU": 4873,
	    "IN": 3671,
	    "BR": 2476,
	    "TR": 1476,
	    "CN": 146,
	    "CA": 134,
	    "BD": 100
	};
	
	// Load Map dependency 1 then call for dependency 2
	loadScript("js/plugin/vectormap/jquery-jvectormap-1.2.2.min.js", loadMapFile);
	
	// Load Map dependency 2 then rendeder Map
	function loadMapFile() {
	    loadScript("js/plugin/vectormap/jquery-jvectormap-world-mill-en.js", renderVectorMap);
	}
	
	function renderVectorMap() {
	    $('#vector-map').vectorMap({
	        map: 'world_mill_en',
	        backgroundColor: '#fff',
	        regionStyle: {
	            initial: {
	                fill: '#c4c4c4'
	            },
	            hover: {
	                "fill-opacity": 1
	            }
	        },
	        series: {
	            regions: [{
	                values: data_array,
	                scale: ['#85a8b6', '#4d7686'],
	                normalizeFunction: 'polynomial'
	            }]
	        },
	        onRegionLabelShow: function (e, el, code) {
	            if (typeof data_array[code] == 'undefined') {
	                e.preventDefault();
	            } else {
	                var countrylbl = data_array[code];
	                el.html(el.html() + ': ' + countrylbl + ' visits');
	            }
	        }
	    });
	}
	
	/*
	 * FULL CALENDAR JS
	 */
	
	// Load Calendar dependency then setup calendar
	loadScript("js/plugin/fullcalendar/jquery.fullcalendar.min.js", setupCalendar);

	
	
	function setupCalendar() {
	
	    if ($("#calendar").length) {
	        var date = new Date();
	        var d = date.getDate();
	        var m = date.getMonth();
	        var y = date.getFullYear();
	
	        var calendar = $('#calendar').fullCalendar({
	
	            editable: true,
	            draggable: true,
	            selectable: false,
	            selectHelper: true,
	            unselectAuto: false,
	            disableResizing: false,
	
	            header: {
	                left: 'title', //,today
	                center: 'prev, next, today',
	                right: 'month, agendaWeek, agenDay' //month, agendaDay,
				},
				
	            // select: function (start, end, allDay) {
	            //     var title = prompt('Event Title:');
	            //     if (title) {
	            //         calendar.fullCalendar('renderEvent', {
	            //                 title: title,
	            //                 start: start,
	            //                 end: end,
	            //                 allDay: allDay
	            //             }, true // make the event "stick"
	            //         );
	            //     }
	            //     calendar.fullCalendar('unselect');
				// },
				
				eventClick: function (arg) {
					// window.location.href = arg.title;
					window.location.href = 'reporting';
					// console.log(arg);
				},
	
	            events: [{
	                title: 'miss model',
					start: new Date(y, m, d),
					className: ["event", "bg-color-red"],
					icon: 'fa-clock-o'
	            }, {
	                title: 'empty raw',
	                start: new Date(y, m, d),
					className: ["event", "bg-color-yellow"],
					icon: 'fa-check'
	            }, {
	                title: 'beacon miss',
	                start: new Date(y, m, d),
	                className: ["event", "bg-color-red"]
	            }],
	
	            eventRender: function (event, element, icon) {
	                if (!event.description == "") {
	                    element.find('.fc-event-title').append("<br/><span class='ultra-light'>" + event.description +
	                        "</span>");
	                }
	                if (!event.icon == "") {
	                    element.find('.fc-event-title').append("<i class='air air-top-right fa " + event.icon +
	                        " '></i>");
	                }
	            }
	        });
	
	    };
	
	    /* hide default buttons */
	    $('.fc-header-right, .fc-header-center').hide();
		loadCalendarTodos();
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


	function loadCalendarTodos() {
    // console.log(url)
		$.ajax({
			type: "GET",
			url: "dashboard/todos",
			dataType: 'html',
			cache: true, // (warning: this will cause a timestamp and will call the request twice)
			beforeSend: function () {
			},
			success: function (data) {
				// console.log("loadData");
				// todo:for loop to decode data array
				var myEvent = {
					title:"SUCCESS",
					allDay: true,
					start: '2020-09-19',
					className: ["event", "bg-color-darken"]
				};
				$('#calendar').fullCalendar('renderEvent', myEvent);
			},
			error: function (xhr, ajaxOptions, thrownError) {
			},
			async: false
		});

    //console.log("ajax request sent");
	}
	
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

	loadScript("js/plugin/datatables/jquery.dataTables-cust.min.js", dt_2);

	function dt_2() {
		loadScript("js/plugin/datatables/ColReorder.min.js", dt_3);
	}

	function dt_3() {
		loadScript("js/plugin/datatables/FixedColumns.min.js", dt_4);
	}

	function dt_4() {
		loadScript("js/plugin/datatables/ColVis.min.js", dt_5);
	}

	function dt_5() {
		loadScript("js/plugin/datatables/ZeroClipboard.js", dt_6);
	}

	function dt_6() {
		loadScript("js/plugin/datatables/media/js/TableTools.min.js", dt_7);
	}

	function dt_7() {
		loadScript("js/plugin/datatables/DT_bootstrap.js", runDataTables);
	}

	function runDataTables() {

		/*
		 * BASIC
		 */
		$('#dt_basic').dataTable({
			"sPaginationType" : "bootstrap_full"
		});

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
			"sDom" : "<'dt-top-row'><'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
			//"sDom" : "t<'row dt-wrapper'<'col-sm-6'i><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'>>",
			"oLanguage" : {
				"sSearch" : "Search all columns:"
			},
			"bSortCellsTop" : true,
			"aaSorting": [[0, "desc"]],
		});		
		


		/*
		 * COL ORDER
		 */
		$('#datatable_col_reorder').dataTable({
			"sPaginationType" : "bootstrap",
			"sDom" : "R<'dt-top-row'Clf>r<'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
			"fnInitComplete" : function(oSettings, json) {
				$('.ColVis_Button').addClass('btn btn-default btn-sm').html('Columns <i class="icon-arrow-down"></i>');
			}
		});
		
		/* END COL ORDER */

		/* TABLE TOOLS */
		$('#datatable_tabletools').dataTable({
			"sDom" : "<'dt-top-row'Tlf>r<'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
			"oTableTools" : {
				"aButtons" : ["copy", "print", {
					"sExtends" : "collection",
					"sButtonText" : 'Save <span class="caret" />',
					"aButtons" : ["csv", "xls", "pdf"]
				}],
				"sSwfPath" : "js/plugin/datatables/media/swf/copy_csv_xls_pdf.swf"
			},
			"fnInitComplete" : function(oSettings, json) {
				$(this).closest('#dt_table_tools_wrapper').find('.DTTT.btn-group').addClass('table_tools_group').children('a.btn').each(function() {
					$(this).addClass('btn-sm btn-default');
				});
			}
		});
		
		/* END TABLE TOOLS */

	}

	loadData();
	function loadData() {
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
				// console.log("loadData");
				$('#statusbody')
					.html(data)
					.delay(100);
				setTimeout(loadData, 1000);
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

	loadLogs();
	function loadLogs() {
    // console.log(url)

		$.ajax({
			type: "GET",
			url: "dashboard/logs",
			dataType: 'html',
			cache: true, // (warning: this will cause a timestamp and will call the request twice)
			beforeSend: function () {
				// container.html('<h1><i class="fa fa-cog fa-spin"></i> Loading...</h1>');
			},
			success: function (data) {
				// console.log("loadLogs");
				$('#logsbody')
					.html(data)
					.delay(100);
				setTimeout(loadLogs, 10 * 60 * 1000);
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
