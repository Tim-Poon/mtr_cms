<style>
    #menu {
        background: #fff;
        position: absolute;
        z-index: 1;
        top: 10px;
        right: 10px;
        border-radius: 3px;
        width: 120px;
        border: 1px solid rgba(0, 0, 0, 0.4);
        font-family: 'Open Sans', sans-serif;
    }
    
    #menu a {
        font-size: 13px;
        color: #404040;
        display: block;
        margin: 0;
        padding: 0;
        padding: 10px;
        text-decoration: none;
        border-bottom: 1px solid rgba(0, 0, 0, 0.25);
        text-align: center;
    }
    
    #menu a:last-child {
        border: none;
    }
    
    #menu a:hover {
        background-color: #f8f8f8;
        color: #404040;
    }
    
    #menu a.active {
        background-color: #3887be;
        color: #ffffff;
    }
    
    #menu a.active:hover {
        background: #3074a4;
    }
</style>
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
                                    <label class="toggle state-success"><input type="checkbox" name="checkbox-toggle" id="Status" onclick="OncheckBox(this)" checked><i data-swchon-text="ON" data-swchoff-text="OFF"></i>Status</label>
                                </div>
                                <div class="col col-3">
                                    <label class="toggle state-success"><input type="checkbox" name="checkbox-toggle" id="Rssi" onclick="OncheckBox(this)"><i data-swchon-text="ON" data-swchoff-text="OFF"></i>Rssi</label>
                                </div>
                                <div class="col col-3">
                                    <label class="toggle state-success"><input type="checkbox" name="checkbox-toggle" id="Name" onclick="OncheckBox(this)"><i data-swchon-text="ON" data-swchoff-text="OFF"></i>Name</label>
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
                        <nav id="menu"></nav>
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
    var alarm = 0;
    var alarm_ = [];
    var alarm_count = 0;

    var pulsingDot = {
        width: size,
        height: size,
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
            
            var radius = (size / 2) * 0.2;
            var outerRadius = (size / 2) * 0.5 * t + radius;
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
            if(alarm){
                // alarm = alarm - 1;
                context.fillStyle = 'rgba(255, 200, 200,' + (1 - t) + ')';
            }else{
                // alarm = alarm + 1;
                context.fillStyle = 'rgba(171, 235, 198,' + (1 - t) + ')';
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
            if(alarm){
                context.fillStyle = 'rgba(255, 100, 100, 1)';
            }else{
                context.fillStyle = '#58D68D';
            }
            alarm_count = alarm_count + 1;
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
    
    var mA = {x: -0.1097, y: 0};
    var mB = {x: 115.6502, y: 11.2451};

    function mappingklb(x, y){
        var pA = map.project(coorA);
        var pB = map.project(coorB);
        var lng;
        var lat;
        lng = (x - mA.x) * (pB.x - pA.x) / (mB.x - mA.x) + pA.x;
        lat = (y - mA.y) * (pB.y - pA.y) / (mB.y - mA.y) + pA.y;
        
        return {lng:lng, lat:lat}
    }
    function OncheckBox(index){
        if(index.id == 'Name'){
            if($('#' + index.id).is(':checked')) {
                map.setLayoutProperty('beacon_name_layer', 'visibility', 'visible' );
            }else{
                map.setLayoutProperty('beacon_name_layer', 'visibility', 'none' );
            }
        }else if(index.id == 'Rssi'){    
                      
            if($('#' + index.id).is(':checked')) {
                map.setLayoutProperty('beacon_rssi_layer', 'visibility', 'visible' );
            }else{
                map.setLayoutProperty('beacon_rssi_layer', 'visibility', 'none' );
            }
        }else if(index.id == 'Status'){
            if($('#' + index.id).is(':checked')) {
                map.setLayoutProperty('beacon_list_layer', 'visibility', 'visible' );
            }else{
                map.setLayoutProperty('beacon_list_layer', 'visibility', 'none' );
            }
            
        }
    }
    
      
    map.on('load', function() {
        map.addImage('pulsing-dot', pulsingDot, { pixelRatio: 2 });
        map.addSource('site_map', {
            'type': 'geojson',
            'data': <?= $site_geojson?>                                
        });
        map.addSource('beacon_list', {
            type: 'geojson',
            data: {
                "type": "FeatureCollection",
                "features": []
            }
        });
        map.addLayer({
            'id': 'site_map_layer',
            'type': 'line',
            'source': 'site_map',
            'layout': {
                'line-join': 'round',
                'line-cap': 'round'
            },
            'paint': {
                'line-color': '#BF93E4',
                'line-width': 2
            }
        });
        map.addSource('dots', {
            'type': 'geojson',
            'data':{
                'type': 'FeatureCollection',
                'features': []
                }
        });

        map.addLayer({
            'id': 'beacon_list_layer',
            'type': 'circle',
            'source': 'beacon_list',
            'paint': {
                'circle-radius': 6,
                'circle-color': '#85C1E9'
            },
            'layout': {
                'visibility': 'visible'
            },
            'filter': ['==', '$type', 'Point']
        });
        map.addLayer({
            id: 'beacon_name_layer',
            type: 'symbol',
            source: 'beacon_list',
            layout: {
                'text-field': ['get', 'beacon_name'],
                'text-font': ['DIN Offc Pro Medium', 'Arial Unicode MS Bold'],
                'text-size': 12,
                'text-offset': [0, 0.3],
                'text-anchor': 'top',  
                'visibility': 'none'
            }
        });
        map.addLayer({
            id: 'beacon_rssi_layer',
            type: 'symbol',
            source: 'beacon_list',
            layout: {
                'text-field': ['get', 'rssi'],
                'text-font': ['DIN Offc Pro Medium', 'Arial Unicode MS Bold'],
                'text-size': 12,
                'text-offset': [0, 2.5],
                'text-anchor': 'top',  
                'visibility': 'none'

            },
            'paint': {
                'text-color': 'red',
                'text-halo-width': 2
            },
        });
        //for test
        map.addLayer({
            id: '1F',
            type: 'symbol',
            source: 'beacon_list',
            layout: {
                'text-field': ['get', 'beacon_name'],
                'text-font': ['DIN Offc Pro Medium', 'Arial Unicode MS Bold'],
                'text-size': 12,
                'text-offset': [0, 0.3],
                'text-anchor': 'top',  
                'visibility': 'visible'
            }
        });
        map.addLayer({
            id: '2F',
            type: 'symbol',
            source: 'beacon_list',
            layout: {
                'text-field': ['get', 'rssi'],
                'text-font': ['DIN Offc Pro Medium', 'Arial Unicode MS Bold'],
                'text-size': 12,
                'text-offset': [0, 2.5],
                'text-anchor': 'top',  
                'visibility': 'none'

            },
            'paint': {
                'text-color': 'red',
                'text-halo-width': 2
            },
        });
        map.addLayer({
            'id': 'dots_layer',
            'source': 'dots',
            'type': 'symbol',
            'layout': {
                'icon-image': 'pulsing-dot',
                'text-field': ['get', 'title'],
                'text-font': [
                    'Open Sans Semibold',
                    'Arial Unicode MS Bold'
                    ],
                'text-offset': [0, 1.25],
                'text-anchor': 'top',
            },
            'pulsing-dot' : {
                "pixelRatio": 5
            },
            'paint': {
                'text-color': ['get', 'color'],
                'text-halo-width': 2
            },
        });

        site_beacons();
        function site_beacons(){
            var rssi;
            $.get("get_beacon_status_mapbox/" + <?= $site_info->site ?>, '', function(result){
                var lastest_beacon =  JSON.parse(result);
                
                var stepper = {};
                stepper['type'] = 'FeatureCollection';
                stepper['features'] = new Array;   
                <?php foreach($site_beacons as $beacon_item){ ?>
                    var temp = new Array();
                    var obj;
                    var tempLatLng = new Array();
                    rssi = '';
                    obj = mappingklb(<?= $beacon_item->x?>, <?= $beacon_item->y?>);
                    temp.push(obj.lng);
                    temp.push(obj.lat);

                    tempLatLng.push(map.unproject(temp)['lng']);
                    tempLatLng.push(map.unproject(temp)['lat']);

                    for(var beacon_name in lastest_beacon){
                        if(<?= $beacon_item->major.$beacon_item->minor?> == beacon_name){
                            rssi = lastest_beacon[beacon_name]['rssi'];
                            break;
                        }else{
                            rssi = '';
                        }  
                    } 

                    var aaa = {
                            "type": "Feature",
                            "properties": {    
                                'beacon_name': <?= $beacon_item->minor?>,
                                'rssi': rssi,
                            },
                            "geometry": {
                                "type": "Point",
                                "coordinates": tempLatLng
                            }
                    };
                    coordinate.push(aaa);
                    stepper['features'].push(aaa);
                    
                <?php } ?>
                map.getSource('beacon_list').setData(stepper);
            });
            setTimeout(site_beacons, 3000);
        }
        // enumerate ids of the layers
        var toggleableLayerIds = ['1F', '2F'];
   
        // set up the corresponding toggle button for each layer
        for (var i = 0; i < toggleableLayerIds.length; i++) {
            var id = toggleableLayerIds[i];
            
            var link = document.createElement('a');
            link.href = '#';
            if(i == 0){
                link.className = 'active';
            }else{
                link.className = '';
            }
            link.textContent = id;
            
            link.onclick = function (e) {
                var clickedLayer = this.textContent;
                e.preventDefault();
                e.stopPropagation();
                
                var visibility = map.getLayoutProperty(clickedLayer, 'visibility');

                $('a').removeClass('active');
                    this.className = 'active';
                    map.setLayoutProperty(clickedLayer, 'visibility', 'visible');
                    
                    for (var i = 0; i < toggleableLayerIds.length; i++) {                    
                        if(clickedLayer != toggleableLayerIds[i]){
                            map.setLayoutProperty(toggleableLayerIds[i], 'visibility', 'none');                           
                        }
                    }
            };
            
            var layers = document.getElementById('menu');
            layers.appendChild(link);
        }
        var data_pre;
        var stepper_info_temp = {};
        animateMarker();
        function animateMarker() {
            $.get("get_sensor_status_mapbox/" + <?= $site_info->site ?>, '', function(result){
                if (result != 0) {
                    // console.log('---request');
                    data_cur = JSON.parse(result);
                    if (data_pre == undefined) {
                        data_pre = data_cur;
                        animateMarker();
                    }else{
                        var count = 0;
                        stepper_info_temp = {};
                        // alarm = [];
                        // alarm_count = 0;
                        for(var sensor in data_cur){
                            // console.log(data_cur['00:00:00:00:00:00']['loc_x']);
                            // 1. 对比data_pre, 找到需要移动的点 todo
                            // alarm.push(data_cur[sensor]['alarm_flag']);

                            var coor_mapping_cur = mappingklb(data_cur[sensor]['loc_x'], data_cur[sensor]['loc_y']);
                            var coor_latlng_cur = map.unproject([coor_mapping_cur.lng, coor_mapping_cur.lat]);

                            var coor_mapping_pre = mappingklb(data_pre[sensor]['loc_x'], data_pre[sensor]['loc_y']);
                            var coor_latlng_pre = map.unproject([coor_mapping_pre.lng, coor_mapping_pre.lat]);

                            var stepper_info_temp_ = {
                                'step_lng': (coor_latlng_cur['lng'] - coor_latlng_pre['lng']) / 60,
                                'step_lat': (coor_latlng_cur['lat'] - coor_latlng_pre['lat']) / 60,
                                'start_lng': coor_latlng_pre['lng'],
                                'start_lat': coor_latlng_pre['lat'],
                            };
                            stepper_info_temp[data_cur[sensor]['label']] = stepper_info_temp_;
                            // console.log(stepper_info_temp);
                            // break;
                        }
                        
                        step_dot();
                        function step_dot() {
                            var stepper = {};
                            stepper['type'] = 'FeatureCollection';
                            stepper['features'] = new Array;
                            for(var label in stepper_info_temp){
                                var stepper_dot = 
                                {
                                    'type': 'Feature',
                                    'geometry': {
                                        'type': 'Point',
                                        'coordinates': [
                                            stepper_info_temp[label]['start_lng'] + count * stepper_info_temp[label]['step_lng'],
                                            stepper_info_temp[label]['start_lat'] + count * stepper_info_temp[label]['step_lat']
                                            ],
                                        },
                                    'properties': {
                                        'title': label,
                                        'color': 'red'
                                        },
                                        
                                };
                                stepper['features'].push(stepper_dot);
                                // break;
                            };
                            // console.log('--setData 1');
                            map.getSource('dots').setData(stepper);
                            // console.log(stepper);
                            count = count + 1;
                            AniID = requestAnimationFrame(step_dot);
                            if (count > 60) {
                                cancelAnimationFrame(AniID)
                                data_pre = data_cur;
                                animateMarker();
                            }
                        }
                    }
                }
            });
        }
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