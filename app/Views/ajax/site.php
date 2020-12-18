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
</script>