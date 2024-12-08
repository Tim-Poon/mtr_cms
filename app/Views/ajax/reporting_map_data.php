<header>
    <span class="widget-icon"> <i class="fa fa-map-marker"></i> </span>
    <h2>Trajectory - <Strong><?= $report_by_id[0]['id'] .' - '. $report_by_id[0]['site'].' Sensor-'.$report_by_id[0]['sensor'].' ('.$report_by_id[0]['shop_name'].' - '.$report_by_id[0]['shop_id'].') '.$report_by_id[0]['delivery_date'].' '.$report_by_id[0]['time_period']?></Strong></h2>
</header>

<!-- widget div-->
<div>
    <!-- widget content -->
    <div class="widget-body">
        <div class="row no-space">
            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-9" style="height:460px;">
                <!-- TODO: MAP -->
                <nav id="menu"></nav>
                <div id="map"></div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
                <form id="report_remark_form" class="smart-form">
                    <header>
                        <Strong style="color:#696969">Delovery #<?= $report_by_id[0]['id'] .' - '. $report_by_id[0]['site'].' Station '?></Strong><br>
                        <Strong style="color:#935116"><?='Sensor-'.$report_by_id[0]['sensor'].' ('.$report_by_id[0]['shop_name'].' - '.$report_by_id[0]['shop_id'].') '.$report_by_id[0]['delivery_date'].' '.$report_by_id[0]['time_period']?></Strong>
                    </header>
                    <fieldset>
                    <div class="row">
                        <div id="graph-B" class="chart no-padding"></div>
                        <label class="textarea state-info" >
                            <textarea rows="2" name="remark" id="reporting_remark" ><?= $report_by_id[0]['remark']?></textarea>
                        </label>
                        <footer>
                            <button type="submit" class="btn btn-primary">Update Remark <strong><?= $report_by_id[0]['remark']?></strong></button>
                        </footer>
                    </fieldset>
                </form>
            </div>
            <div class="note">
                <strong>NOTE: 
                    <font color="#04B404">GREEN DOT -> go</font> |
                    <font color="#58D3F7">BLUE DOT -> return</font> |
                    <font color="#CB4335">RED DOT -> over speec</font>
                </strong>
            </div>
        </div>
    </div>
    <!-- end widget content -->             
</div>


<style>
    /* style for switching floor */
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
<?php if ($site_info) { ?>
<script type="text/javascript">
    mapboxgl.accessToken = '<?=$mapbox_key?>';
    // map initial
    var alarm = 0;
    var alarm_ = [];
    var alarm_count = 0;
    var floor_cur = <?= $site_info[0]['floor']?>;
    var dot_mapping = <?= $site_info[0]['dot_mapping']?>;
    var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/light-v10',
        center: [<?= $site_info[0]['mapbox_center_lng']?>, <?= $site_info[0]['mapbox_center_lat']?>],   
        zoom: <?= $site_info[0]['mapbox_zoom']?>,
        bearing: <?= $site_info[0]['mapbox_bearing']?> 
    });

    // dots
    var size = 200;
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
                context.fillStyle = 'rgba(156, 156, 156,' + (1 - t) + ')';
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
                context.fillStyle = '#848484';
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
      
    map.on('load', function() {
        map.addImage('pulsing-dot', pulsingDot, { pixelRatio: 2 });

        // Source: map
        map.addSource('site_map', {
            'type': 'geojson',
            'data': <?= $site_info[0]['geojson']?>
        });

        // Source: beacon list
        map.addSource('beacon_list', {
            'type': 'geojson',
            'data': {
                "type": "FeatureCollection",
                "features": []
            }
        });

        // Source: dots
        map.addSource('dots', {
            'type': 'geojson',
            'data':{
                'type': 'FeatureCollection',
                'features': []
            }
        });

        // Layer: show map
        map.addLayer({
            'id': 'site_map_layer',
            'type': 'line',
            'source': 'site_map',
            'layout': {
                'line-join': 'round',
                'line-cap': 'round'
            },
            'paint': {
                'line-color': '#BB8FCE',
                'line-width': 2
            }
        });

        // Layer: beacon dots
        map.addLayer({
            'id': 'beacon_list_layer',
            'type': 'circle',
            'source': 'beacon_list',
            'paint': {
                'circle-radius': 6,
                'circle-color': ['get', 'color']
            },
            'layout': {
                'visibility': 'visible'
            },
            // 'filter': ['==', '$type', 'Point']
        });

        // Layer: beacon info (name & coor)
        map.addLayer({
            'id': 'beacon_info_layer',
            'type': 'symbol',
            'source': 'beacon_list',
            'layout': {
                "text-field": ["format", ["get", "beacon_name"], {
                    "text-color": '#424949',
                },
                "\n", {},
                ["get", "beacon_coor"], {
                    "text-color": '#F39C12',
                },
                "\n", {}],
                'text-font': ['DIN Offc Pro Medium', 'Arial Unicode MS Bold'],
                'text-size': 12,
                'text-offset': [0, 0.3],
                'text-anchor': 'top',  
                'visibility': 'none'
            }
        });

        // Layer: beacon rssi (realtime value)
        map.addLayer({
            id: 'beacon_rssi_layer',
            type: 'symbol',
            source: 'beacon_list',
            layout: {
                'text-field': ['get', 'rssi'],
                'text-font': ['DIN Offc Pro Medium', 'Arial Unicode MS Bold'],
                'text-size': 15,
                'text-offset': [0, -2],
                'visibility': 'none'
            },
            'paint': {
                'text-color': '#E74C3C',
                'text-halo-width': 2
            },
        });
        
        // Layer: dots (realtime coor)
        map.addLayer({
            'id': 'dots_layer',
            'source': 'dots',
            'type': 'symbol',
            'layout': {
                'icon-allow-overlap': true,
                'text-allow-overlap': true,
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

        // floor switcher
        <?php foreach ($site_info as $idx => $site_floor_item) { ?>
            
            var link = document.createElement('a');
            link.href = '#';
            if (!<?= $idx?>) {
                link.className = 'active';
            }
            link.textContent = '<?= $site_floor_item['floor_name']?>';
            
            link.onclick = function (e) {
                e.preventDefault();
                e.stopPropagation();
                $('a').removeClass('active');
                this.className = 'active';
                // check floor cur
                floor_cur = <?= $site_floor_item['floor']?>;
                dot_mapping = <?= $site_info[$site_floor_item['floor'] - 1]['dot_mapping']?>;
                // set data
                map.getSource('site_map').setData(<?= $site_floor_item['geojson']?>);
                map.flyTo({
                    center: [<?= $site_floor_item['mapbox_center_lng']?>, <?= $site_floor_item['mapbox_center_lat']?>],
                    bearing: <?= $site_floor_item['mapbox_bearing']?>,
                    zoom: <?= $site_floor_item['mapbox_zoom']?>,
                });
                show_site_beacons();
            };
            document.getElementById('menu').appendChild(link);
        <?php } ?>

        // flash beacon status
        // var
        show_site_beacons();
        function show_site_beacons(){
            var beacon_list = {};
            beacon_list['type'] = 'FeatureCollection';
            beacon_list['features'] = new Array;   
            <?php foreach($trajectory_data as $idx => $trajectory_item){ ?>
                if (<?= $trajectory_item['pos_z']?> == floor_cur) {
                    color = '#04B404';
                    if (<?= $idx ?> > <?= count($trajectory_data)/2 ?>) {
                        color = '#58D3F7';
                    }
                    if (<?= $trajectory_item['alarm'] ?>) {
                        color = '#CB4335';
                    }
                    var beacon_dot = {
                            "type": "Feature",
                            "properties": {    
                                'beacon_name': '',
                                'beacon_coor': '',
                                'color': color,
                            },
                            "geometry": {
                                "type": "Point",
                                "coordinates": [<?= $trajectory_item['pos_x'].', '.$trajectory_item['pos_y']?>],
                            }
                    };
                    beacon_list['features'].push(beacon_dot);
                }
            <?php } ?>
            map.getSource('beacon_list').setData(beacon_list);
        }
        function sleep(delay) {
            var start = (new Date()).getTime();
            while((new Date()).getTime() - start < delay) {
                continue;
            }
        }

        // flash dots status
        var data_pre;
        var stepper_info_temp = {};
        var result = (<?= json_encode($trajectory_data) ?>);
        var idx_len = result.length;
        var idx = 0;
        var review_speed = 30;
        animateMarker();
        function animateMarker() {
            if (data_pre == undefined || idx == 0) {
                data_pre = result[idx];
                idx = idx +1;
                animateMarker();
            }else{
                var count = 0;
                data_cur = result[idx];
                idx = idx +1;
                if (idx > idx_len) {
                    idx = 0;
                }
                stepper_info_temp = {};;
                if (data_cur.pos_z == floor_cur && data_cur.pos_x) {
                    var stepper_info_temp_ = {
                        'step_lng': (data_cur.pos_x - data_pre.pos_x) / review_speed,
                        'step_lat': (data_cur.pos_y - data_pre.pos_y) / review_speed,
                        'start_lng': data_pre.pos_x,
                        'start_lat': data_pre.pos_y,
                        'ts': data_cur.ts
                    };
                    stepper_info_temp = stepper_info_temp_;
                }
                step_dot();
                function step_dot() {
                    var stepper = {};
                    stepper['type'] = 'FeatureCollection';
                    stepper['features'] = new Array;
                    alarm = Number(data_cur.alarm);
                    var date2 = new Date(stepper_info_temp['ts'] * 1000);
                    ts = date2.getHours() +':' + date2.getMinutes()+':' + date2.getSeconds();
                    var stepper_dot = 
                    {
                        'type': 'Feature',
                        'geometry': {
                            'type': 'Point',
                            'coordinates': [
                                Number(stepper_info_temp['start_lng']) + Number(count * stepper_info_temp['step_lng']),
                                Number(stepper_info_temp['start_lat']) + Number(count * stepper_info_temp['step_lat'])
                                // stepper_info_temp['start_lng'],
                                // stepper_info_temp['start_lat']
                                ],
                            },
                        'properties': {
                            'title': '#'+'<?= $report_by_id[0]['sensor']?>'+'-'+ts,
                            'color': '#0021FF'
                            },
                    };
                    stepper['features'].push(stepper_dot);
                    map.getSource('dots').setData(stepper);
                    AniID = requestAnimationFrame(step_dot);
                    count = count + 1;
                    if (count > review_speed) {
                        cancelAnimationFrame(AniID)
                        data_pre = data_cur;
                        // setTimeout(animateMarker(), 1000)
                        animateMarker();
                    }
                }
            }
            
        }
    });
    // -- mapbox end

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

    $('#report-remark-form').submit(function(e){
        $.post("<?=base_url('reporting/update_remark/'.$repord_id)?>", $( "#updatesource-form" ).serialize()).done(function(data) {
            if(data == '0'){
                alert('please fill ');
            }else{
                link = "<?= base_url('polygon/'.$site_info[0]['site_name'])?>" + "/" + data;
                window.location.href= link;
            }
        });
        return false;
    });
    loadScript("<?= base_url('public/js/plugin/morris/raphael.2.1.0.min.js')?>", loadMorrisEngine);
    // Load morris dependency 2
    function loadMorrisEngine() {
        loadScript("<?= base_url('public/js/plugin/morris/morris.min.js')?>", runMorrisCharts);
    }

    // negative value
    function runMorrisCharts(){
        if ($('#graph-B').length){ 
            var neg_data = [
                // {"period": "2011-08-12", "a": 100},
                <?php foreach ($trajectory_data as $trajectory_item) {
                    $vel = sqrt($trajectory_item['vel_x'] * $trajectory_item['vel_x'] + $trajectory_item['vel_y'] * $trajectory_item['vel_y']);
                    if ($vel < 3) {
                        if ($trajectory_item['alarm']) {
                            $vel = 1.6;
                        }
                        echo '{time :'.($trajectory_item['ts']*1000). ",vel:".round($vel, 1).'},';
                    }
                }
            ?>
            ];
            Morris.Line({
                element: 'graph-B',
                data: neg_data,
                xkey: 'time',
                ykeys: ['vel'],
                dateFormat: function (ts) {
                    var d = new Date(ts);
                    return d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds();
                    // + ' ' + d.getFullYear() + '/' + (d.getMonth() + 1) + '/' + d.getDay()
                },
                labels: ['vel'],
                units: 'm/s',
                xLabels:'day',
                
            });
        }
    }
</script>
<!-- if ($site_geojson) {  -->
<?php } ?>

<script>
    $('#report_remark_form').submit(function(e){
        $.post("<?=base_url('reporting/update_remark/'.$report_by_id[0]['id'])?>", $( "#report_remark_form" ).serialize()).done(function(data) {
            if(data == '0'){
                alert('please fill ');
            }else{
                alert('success');
            }
        });
        return false;
    });
    
</script>