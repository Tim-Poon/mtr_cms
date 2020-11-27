<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
<script src="<?= base_url('/public/js/mapbox/mapbox-gl.js')?>"></script>
<link href="<?= base_url('/public/css/mapbox-gl.css')?>" rel="stylesheet" />
<style>
#map {
    position: absolute;
    top: 0;
    bottom: 0;
    width: 100%;
}
</style>
<!-- widget grid -->
<section id="widget-grid" class="">

	<!-- row -->
	<div class="row">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>Site Map</h2>

				</header>

				<!-- widget div-->
				<div>
					<!-- widget content -->
					<div class="widget-body no-padding" style="height:500px;">
                    	<div id="map"></div>
                            <script>
                            mapboxgl.accessToken = '<?=$mapbox_key?>';
                            var map = new mapboxgl.Map({
                                container: 'map',
                                style: 'mapbox://styles/mapbox/streets-v11',
                                center: [114.21402, 22.3235],
                                zoom: 19,
                                bearing: 85
                            });
                            var marker = new mapboxgl.Marker();

                            function getLonLat() {
                                    $.ajax({
                                        type: "POST",
                                        dataType: "json",
                                        url: "http://127.0.0.1/fakegps.html",
                                        success: function (result) {
                                            console.log(result['longitude']);
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
                                    'data': <?=$geo_json?>
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
                            });
                            </script>
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

	// DO NOT REMOVE : GLOBAL FUNCTIONS!
	pageSetUp();
</script>