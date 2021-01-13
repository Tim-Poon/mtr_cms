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

    var size = 200;
    var alarm = 1;

    var pulsingDot = {
        width: size,
        height: size,
        alarm: alarm,
        data: new Uint8Array(size * size * 4),
        
        // get rendering context for the map canvas when layer is added to the map
        onAdd: function () {
            var canvas = document.createElement('canvas');
            canvas.width = this.width;
            canvas.height = this.height;
            this.context = canvas.getContext('2d');
        },
        
        // called once before every frame where the icon will be used
        render: function () {
            var duration = 1000;
            var t = (performance.now() % duration) / duration;
            
            var radius = (size / 2) * 0.3;
            var outerRadius = (size / 2) * 0.7 * t + radius;
            var context = this.context;
            
            // draw outer circle
            context.clearRect(0, 0, this.width, this.height);
            context.beginPath();
            context.arc(
                this.width / 2,
                this.height / 2,
                outerRadius,
                0,
                Math.PI * 2
            );
            if(this.alarm == 1){
                context.fillStyle = 'rgba(255, 200, 200,' + (1 - t) + ')';
            }else{
                context.fillStyle = '#B8CCD4';
            }
            context.fill();
            
            // draw inner circle
            context.beginPath();
            context.arc(
                this.width / 2,
                this.height / 2,
                radius,
                0,
                Math.PI * 2
            );
            if(this.alarm == 1){
                context.fillStyle = 'rgba(255, 100, 100, 1)';
            }else{
                context.fillStyle = '#229DCF ';
            }
            context.strokeStyle = 'white';
            context.lineWidth = 2 + 4 * (1 - t);
            context.fill();
            context.stroke();
            
            // update this image's data with data from the canvas
            this.data = context.getImageData(
                0,
                0,
                this.width,
                this.height
            ).data;
            
            // continuously repaint the map, resulting in the smooth animation of the dot
            map.triggerRepaint();
            
            // return `true` to let the map know that the image was updated
            return true;
        }
    };
    //A = beacon 10032 and B = beacon 10060
    var coorA = [114.21400184074332,22.323663536861147];
    var coorB = [114.21419888819668,22.3226334019689];
    var pA = map.project(coorA);
    var pB = map.project(coorB);
    var mA = {x: -0.1097, y: 0};
    var mB = {x: 115.6502, y: 11.2451};

    function mappingklb(x, y){
        var lng;
        var lat;
        lng = (x - mA.x) * (pB.x - pA.x) / (mB.x - mA.x) + pA.x;
        lat = (y - mA.y) * (pB.y - pA.y) / (mB.y - mA.y) + pA.y;
        
        return {lng:lng, lat:lat}
    }

    <?php foreach($site_beacons as $beacon_item){ ?>
        var temp = new Array();
        var obj;
        var tempLatLng = new Array();
        obj = mappingklb(<?= $beacon_item->x?>, <?= $beacon_item->y?>);
        temp.push(obj.lng);
        temp.push(obj.lat);
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
    
    map.on('load', function() {
        map.addImage('pulsing-dot', pulsingDot, { pixelRatio: 2 });
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
        map.addSource('points', {
            'type': 'geojson',
            'data':{
                    'type': 'FeatureCollection',
                    'features': [
                    {
                    // feature for Mapbox DC
                    'type': 'Feature',
                    'geometry': {
                    'type': 'Point',
                    'coordinates': [
                        114.21402, 22.3235
                    ]
                    },
                    'properties': {
                    'title': 'Mapbox DC'
                    }
                    },
                    {
                    // feature for Mapbox SF
                    'type': 'Feature',
                    'geometry': {
                    'type': 'Point',
                    'coordinates': [114.21502, 22.3245]
                    },
                    'properties': {
                    'title': 'Mapbox SF'
                    }
                    }
                    ]
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
        map.addLayer({
            'id': 'points',
            'source': 'points',
            'type': 'symbol',
            'layout': {
                'icon-image': 'pulsing-dot',
                'text-field': ['get', 'title'],
                'text-font': [
                'Open Sans Semibold',
                'Arial Unicode MS Bold'
                ],
                'text-offset': [0, 1.25],
                'text-anchor': 'top'
            }
        });
        
        function animateMarker() {
            // Update the data to a new position based on the animation timestamp. The
            // divisor in the expression `timestamp / 1000` controls the animation speed.
            var t = {
                    'type': 'FeatureCollection',
                    'features': [
                    {
                    // feature for Mapbox DC
                    'type': 'Feature',
                    'geometry': {
                    'type': 'Point',
                    'coordinates': [
                        114.21402, 22.3265
                    ]
                    },
                    'properties': {
                    'title': 'Mapbox DC'
                    }
                    },
                    {
                    // feature for Mapbox SF
                    'type': 'Feature',
                    'geometry': {
                    'type': 'Point',
                    'coordinates': [114.21602, 22.3245]
                    },
                    'properties': {
                    'title': 'Mapbox SF'
                    }
                    }
                    ]
                };
            map.getSource('points').setData(t);
            // Request the next frame of the animation.
            requestAnimationFrame(animateMarker);
        }
        
        // Start the animation.
        animateMarker(0);
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