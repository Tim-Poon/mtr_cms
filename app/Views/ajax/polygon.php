<script src="https://api.tiles.mapbox.com/mapbox.js/plugins/turf/v3.0.11/turf.min.js"></script>
<script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-draw/v1.2.0/mapbox-gl-draw.js"></script>
<link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-draw/v1.2.0/mapbox-gl-draw.css" type="text/css" />
<style>
#map {
    position: absolute;
    top: 0;
    bottom: 0;
    width: 100%;
}
.calculation-box {
        height: 90px;
        width: 150px;
        position: absolute;
        bottom: 425px;
        left: 920px;
        background-color: rgba(255, 255, 255, 0.9);
        padding: 15px;
        text-align: center;
    }

    p {
        font-family: 'Open Sans';
        margin: 0;
        font-size: 13px;
    }
</style>
<!-- widget grid -->
<section id="widget-grid" class="">
	<!-- row -->
	<div class="row">
		<article class="col-sm-12 col-md-12 col-lg-12">
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" 
				data-widget-editbutton="false"
				data-widget-colorbutton="false"
				data-widget-deletebutton="false"
				data-widget-togglebutton="false"
				data-widget-sortable="false">

				<header>
					<span class="widget-icon"> <i class="fa fa-map-marker"></i> </span>
					<h2>Polygon</h2>

				</header>

				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->


					<!-- widget content -->
					<div class="widget-body">
						<?php foreach($site_all as $site_item) {?>
						<a href="<?= base_url('polygon/'.$site_item->site.'/'.$site_item->floor)?>" class="btn btn-success"><?= $site_item->site_name.' '.$site_item->floor_name?></a>
						<?php } ?>
					</div>
					<!-- end widget content -->
					<div class="row no-space">
						<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8" style="height:500px;">
							<!-- TODO: MAP -->
							<div id="map" ></div>
							<div class="calculation-box">
								<p>Draw a polygon using the draw tools.</p>
								<a ref="#" id="export">SAVE POLYGON</a>
								<div id="calculated-area"></div>
							</div>
                            <script>
                            mapboxgl.accessToken = '<?=$mapbox_key?>';
                            var map = new mapboxgl.Map({
                                container: 'map',
                                style: 'mapbox://styles/mapbox/streets-v11',
                                center: [114.21402, 22.3235],
                                zoom: 19,
                                bearing: 85
                            });
                           
							map.on('load', function() {
								map.addSource('national-park', {
									'type': 'geojson',
									'data': <?=$geojson?>
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
							var draw = new MapboxDraw({
								displayControlsDefault: false,
								controls: {
									polygon: true,
									trash: true
								}
							});
							map.addControl(draw);

							map.on('draw.create', updateArea);
							map.on('draw.delete', updateArea);
							map.on('draw.update', updateArea);

							function updateArea(e) {
								var data = draw.getAll();
								// console.log(turf.area(data));
								var answer = document.getElementById('calculated-area');
								if (data.features.length > 0) {
									var area = turf.area(data);
									// restrict to area to 2 decimal points
									var rounded_area = Math.round(area * 100) / 100;
									// answer.innerHTML =
									// 	'<p><strong>' +
									// 	rounded_area +
									// 	'</strong></p><p>square meters</p>';
								} else {
									// answer.innerHTML = '';
									if (e.type !== 'draw.delete')
										alert('Use the draw tools to draw a polygon!');
								}
							}

							document.getElementById('export').onclick = function(e){
								// extract GeoJson from featureGroup
								var data = draw.getAll();

								if(data.features.length > 0){
									// Stringify the GeoJson
									var convertedData = 'text/json;charset=utf-8,' + encodeURIComponent(JSON.stringify(data))

									// create export
									document.getElementById('export').setAttribute('href', 'data:' + convertedData);
									document.getElementById('export').setAttribute('download', 'data.geojson')
								}
								else{
									alert("Wouldn't you like to draw some data")
								}
								}
                            </script>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
							TODO: polygons 
						</div>
					</div>
					
					TODO: btn group (add point, save all) -> database
					TODO: history table by site (ts_id vertex) -> display
					<table class="table table-bordered">
						<thead> 
				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
		</article>
    </div>

</section>
<!-- end widget grid -->

<script type="text/javascript">
	// DO NOT REMOVE : GLOBAL FUNCTIONS!
	pageSetUp();

	// PAGE RELATED SCRIPTS
	$('#contact-form').submit(function(e){
		$.post( "todos/submit", $( "#contact-form" ).serialize())
		.done(function( data ) {
			$('#done').html(data);
		});
		return false;
	});
</script>
