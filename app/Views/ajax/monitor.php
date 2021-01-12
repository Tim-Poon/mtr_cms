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
                    <div class="show-stat-microcharts">
                        <?php foreach ($site_sensors as $site_sensor_item) {?>
                        <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                            <span class="sensor-status-title"> <?= $site_info->site_name.' - '.$site_sensor_item->label?>  </span>
                            
                            <ul class="smaller-stat hidden-sm pull-right">
                                <li>
                                    <span class="label bg-color-greenLight"><i class="fa fa-caret-up"></i> #</span>
                                </li>
                                <li>
                                    <span class="label bg-color-blueLight"><i class="fa fa-caret-down"></i> #</span>
                                </li>
                            </ul>
                            
                            <span id="sparkline_<?= $site_sensor_item->label?>" class="sparkline hidden-sm hidden-md pull-right" >
                                0
                            </span>
                            <span class="sensor-status-velocity" > <i id="vel_<?= $site_sensor_item->label?>"></i>
                            <i id="faster_flag_<?= $site_sensor_item->label?>"></i>
                            </span>
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
<?php if ($site_geojson) { ?>
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
    <?php foreach($site_beacons as $beacon_item){ ?>
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
            'data': <?= $site_geojson?>                                
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
		$.get("get_sensor_status_monitor/" + <?= $site_info->site ?>, '', function(result){
            if (result != 0) {
                data = JSON.parse(result);
                <?php foreach ($site_sensors as $site_sensor_item) { ?>
                if (data['<?= $site_sensor_item->sensor?>'] == undefined) {
                    $('#vel_<?= $site_sensor_item->label?>').html('').css('color', '#D5D8DC');
                    $("#faster_flag_<?= $site_sensor_item->label?>").removeClass();
                    $('#sparkline_<?= $site_sensor_item->label?>').sparkline(<?= $default_sensor_status?>, { 
                        type: "line",
                        height: "45px",
                        width: "85px",
                        lineColor: '#D5D8DC',
                        fillColor: '#EAFAF1',});
                }else {
                    $('#vel_<?= $site_sensor_item->label?>').html(data['<?= $site_sensor_item->sensor?>']['vel_x']).css('color', '#1D8348');
                    var faster_flag = $("#faster_flag_<?= $site_sensor_item->label?>");
                    if (!faster_flag.hasClass("data['<?= $site_sensor_item->sensor?>']['faster_flag']")) {
                        faster_flag.removeClass();
                        faster_flag.addClass(data['<?= $site_sensor_item->sensor?>']['faster_flag']);
                    }
                    $('#sparkline_<?= $site_sensor_item->label?>').sparkline(data['<?= $site_sensor_item->sensor?>']['history_vel'], { 
                        type: "line",
                        height: "45px",
                        width: "85px",
                        lineColor: '#1D8348',
                        fillColor: '#EAFAF1',
                        // data-fill-color="transparent"
                        // width: data.length*5, 
                        // height: 400, 
                        // type: 'line',
                        // lineWidth: 5,
                        // spotColor: undefined,
                        // minSpotColor: undefined,
                        // maxSpotColor: undefined,
                        });
                }
                <?php } ?>
            }else {
                <?php foreach ($site_sensors as $site_sensor_item) { ?>
                    $('#vel_<?= $site_sensor_item->label?>').html('').css('color', '#D5D8DC');
                    $("#faster_flag_<?= $site_sensor_item->label?>").removeClass();
                    $('#sparkline_<?= $site_sensor_item->label?>').sparkline(<?= $default_sensor_status?>, { 
                        type: "line",
                        height: "45px",
                        width: "85px",
                        lineColor: '#D5D8DC',
                        fillColor: '#D5D8DC',});
                <?php } ?>
            }
			setTimeout(load_sensor_status, 1000);
		});
	}
</script>
<?php } ?>