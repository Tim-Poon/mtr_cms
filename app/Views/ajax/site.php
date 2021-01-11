<!-- widget grid -->
<section id="widget-grid" class="">

	<!-- row -->
	<div class="row">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" 
                data-widget-editbutton="false"
				data-widget-colorbutton="false"
				data-widget-deletebutton="false"
				data-widget-togglebutton="false"
				data-widget-sortable="false">

				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>Map</h2>

				</header>
                <div class="widget-body">
                    <form class="smart-form">
                        <div class="row">
                            <section class="col col-6">
                                <div class="note note-success">Beacon</div>
                                <div class="col col-3">
                                    <label class="toggle state-success"><input type="checkbox" name="checkbox-toggle" checked><i data-swchon-text="ON" data-swchoff-text="OFF"></i>Statue</label>
                                </div>
                                <div class="col col-3">
                                    <label class="toggle state-success"><input type="checkbox" name="checkbox-toggle"><i data-swchon-text="ON" data-swchoff-text="OFF"></i>Rssi</label>
                                </div>
                                <div class="col col-3">
                                    <label class="toggle state-success"><input type="checkbox" name="checkbox-toggle"><i data-swchon-text="ON" data-swchoff-text="OFF"></i>Label</label>
                                </div>
                            </section>
                            <section class="col col-6">
                                <div class="note note-success">Filter</div>
                                <div class="col col-3">
                                    <label class="toggle state-success"><input type="checkbox" name="checkbox-toggle"><i data-swchon-text="ON" data-swchoff-text="OFF"></i>Polygon</label>
                                </div>
                                <div class="col col-3">
                                    <label class="toggle state-success"><input type="checkbox" name="checkbox-toggle"><i data-swchon-text="ON" data-swchoff-text="OFF"></i>#</label>
                                </div>
                                <div class="col col-3">
                                    <label class="toggle state-success"><input type="checkbox" name="checkbox-toggle"><i data-swchon-text="ON" data-swchoff-text="OFF"></i>#</label>
                                </div>
                            </section>
                        </div>
                    </form>
                    <?= ($site_sensor)?>
                    <div class="show-stat-microcharts">
                        <?php foreach ($site_sensor as $sensor_item) {?>
                        <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                            <span class="sensor-status-title"> <?= $sensor_item->label?> <i class="fa fa-caret-up icon-color-bad"></i><i class="fa fa-caret-down icon-color-good"></i> </span>
                            
                            <ul class="smaller-stat hidden-sm pull-right">
                                <li>
                                    <span class="label bg-color-greenLight"><i class="fa fa-caret-up"></i> #</span>
                                </li>
                                <li>
                                    <span class="label bg-color-blueLight"><i class="fa fa-caret-down"></i> #</span>
                                </li>
                            </ul>
                            
                            <div id="sparkline" class="sparkline txt-color-greenLight hidden-sm hidden-md pull-right" data-sparkline-type="line" data-sparkline-height="33px" data-sparkline-width="80px" data-fill-color="transparent">
                                130, 187, 250, 257, 200, 210, 300, 270, 363, 247, 270, 363, 247
                            </div>
                            <span class="sensor-status-velocity" id='abc'>1.2 m/s</span>
                        </div>
                        <?php }?>
                    </div>
                </div>

				<!-- widget div-->
				<div>
					<!-- widget content -->
					<div class="widget-body no-padding" style="height:500px;">
                    	<div id="map"></div>
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
<?php if ($geojson) { ?>
<script type="text/javascript">
    var coordinate = new Array();
    mapboxgl.accessToken = '<?=$mapbox_key?>';
    var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/light-v10',
        center: [114.21402, 22.3235],
        zoom: 19,
        bearing: 85
    });
    function mappingklb(x, y){
        var tempX;
        var tempY;
        tempX = (x + 0.1097) * (1522.652698750814 - 694.000000406901) / (113.8655 + 0.1097) + 694.000000406901;
        tempY = y * (164.99999857584635 - 254.49999934895834) / 13.4663 + 254.49999934895834;
        return {tempX:tempX, tempY:tempY}
    }
    <?php foreach($beacons as $beacon_item){ ?>
    var temp = new Array();
    var obj;
    var tempLatLng = new Array();
    obj = mappingklb(<?= $beacon_item->x?>, <?= $beacon_item->y?>);
    temp.push(obj.tempX);
    temp.push(obj.tempY);
    tempLatLng.push(map.unproject(temp)['lng']);
    tempLatLng.push(map.unproject(temp)['lat']);
    var aaa = {
            "type": "Feature",
            "properties": {},
            "geometry": {
                "type": "Point",
                "coordinates": tempLatLng
            }
    };
    coordinate.push(aaa);
    <?php } ?>
    
    var marker = new mapboxgl.Marker();
    function getLonLat() {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "http://127.0.0.1/fakegps.html",
                success: function (result) {
                    // console.log(result['longitude']);
                    marker.setLngLat([result['longitude'],result['latitude']]);
                    marker.addTo(map);
                    getLonLat();
                }
            });
    }
    getLonLat();
    
    map.on('load', function() {
        map.addSource('national-park', {
            'type': 'geojson',
            'data': <?=$geojson?>                                
        });
        map.addSource('beacon_list', {
            type: 'geojson',
            data: {
                "type": "FeatureCollection",
                "features": coordinate
            }
            });
        map.addLayer({
            'id': 'park-boundary',
            'type': 'line',
            'source': 'national-park',
            'layout': {
            'line-join': 'round',
            'line-cap': 'round'
            },
            'paint': {
            'line-color': '#BF93E4',
            'line-width': 2
            }
        });

        map.addLayer({
            'id': 'park-volcanoes',
            'type': 'circle',
            'source': 'beacon_list',
            'paint': {
            'circle-radius': 6,
            'circle-color': '#B42222'
            },
            'filter': ['==', '$type', 'Point']
        });
    });
    // switch style change
	$('input[name="checkbox-style"]').change(function() {
		//alert($(this).val())
		$this = $(this);

		if ($this.attr('value') === "switch-1") {
			$("#switch-1").show();
			$("#switch-2").hide();
		} else if ($this.attr('value') === "switch-2") {
			$("#switch-1").hide();
			$("#switch-2").show();
		}

	});
    load_sensor_status();
	function load_sensor_status() {
		$.get("get_realtime_sensor_status_monitor", '', function(result){
			data = JSON.parse(result);
            // console.log(data);
			// $('#abcd').html(result).delay(100);
            $('#sparkline').sparkline(data, { 
                // class="sparkline txt-color-greenLight hidden-sm hidden-md pull-right" 
                type: "line",
                height: "33px",
                width: "80px",
                // data-fill-color="transparent"
                // width: data.length*5, 
                // height: 400, 
                // type: 'line',
                lineColor: '#1D8348',
                fillColor: '#EAFAF1',
                // lineWidth: 5,
                // spotColor: undefined,
                // minSpotColor: undefined,
                // maxSpotColor: undefined,
            });
			setTimeout(load_sensor_status, 1000);
		});
	}

    // $(function() {
    //     function drawMouseSpeedDemo() {
    //         var mrefreshinterval = 50; // update display every 500ms
    //         var lastmousex=-1; 
    //         var lastmousey=-1;
    //         var lastmousetime;
    //         var mousetravel = 0;
    //         var mpoints = [];
    //         var mpoints_max = 100;
    //         $('html').mousemove(function(e) {
    //             var mousex = e.pageX;
    //             var mousey = e.pageY;
    //             if (lastmousex > -1) {
    //                 mousetravel += Math.max( Math.abs(mousex-lastmousex), Math.abs(mousey-lastmousey) );
    //             }
    //             lastmousex = mousex;
    //             lastmousey = mousey;
    //         });
    //         var mdraw = function() {
    //             var md = new Date();
    //             var timenow = md.getTime();
    //             if (lastmousetime && lastmousetime!=timenow) {
    //                 var pps = Math.round(mousetravel / (timenow - lastmousetime) * 1000);
    //                 mpoints.push(pps);
    //                 if (mpoints.length > mpoints_max)
    //                     mpoints.splice(0,1);
    //                 mousetravel = 0;
    //                 $('#sparkline').sparkline(mpoints, { 
    //                     width: mpoints.length*20, 
    //                     height: 400, 
    //                     type: 'line',
    //                     lineColor: '#e3003d',
    //                     fillColor: '#061a1b',
    //                     lineWidth: 5,
    //                     spotColor: undefined,
    //                     minSpotColor: undefined,
    //                     maxSpotColor: undefined,
    //                     //tooltipSuffix: ' pixels per second' 
                        
    //                 });
    //             }
    //             lastmousetime = timenow;
    //             setTimeout(mdraw, mrefreshinterval);
    //         }
    //         // We could use setInterval instead, but I prefer to do it this way
    //         setTimeout(mdraw, mrefreshinterval); 
    //     }
    //     drawMouseSpeedDemo();
    //     /*
    //             var myvalues = [10,8,5,7,4,4,1];
    //     $("#sparkline").sparkline(myvalues, {
    //         type: 'line',
    //         width: '500',
    //         height: '500',
    //         drawNormalOnTop: true
    //     });*/
            
    //     });
</script>
<?php } ?>