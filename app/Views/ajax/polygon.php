<script src="<?= base_url('/public/js/mapbox/turf.min.js')?>"></script>
<script src="<?= base_url('/public/js/mapbox/mapbox-gl-draw.js')?>"></script>
<link href="<?= base_url('/public/css/mapbox/mapbox-gl-draw.css')?>" rel="stylesheet" />

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
							<div id="map"></div>
						</div>

						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
							TODO: polygons 
							TODO: btn group (add point, save all) -> database
							TODO: history table by site (ts_id vertex) -> display
							<div class="calculation-box">
								<p>Draw a polygon using the draw tools.</p>
								<a ref="#" id="export">SAVE POLYGON</a>
								<div id="calculated-area"></div>
							</div>
						
							<table id="datatable_sensor" class="table table-striped table-hover">
								<thead>
									<tr>
										<th class="text-align-center">ts_create</th>
										<th class="text-align-center">operation</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($site_ts_create as $site_ts_create_item) {?>
										<tr>
											<td class="text-align-center"><a href="<?= base_url('polygon/'.$site_item->site.'/'.$site_item->floor.'/'.$site_ts_create_item->ts_create)?>"><?= $site_ts_create_item->ts_create?></a></td>
											<td class="text-align-center"><a href="<?= base_url('polygon/del/'.$site_item->site.'/'.$site_item->floor.'/'.$site_ts_create_item->ts_create)?>">delete</a></td>
										</tr>
									<?php }?>
									
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<!-- end widget div -->
			</div>
			<!-- end widget -->
		</article>
    </div>
</section>

<!-- end widget grid -->
<script>
		mapboxgl.accessToken = '<?=$mapbox_key?>';
		var map = new mapboxgl.Map({
			container: 'map',
			style: 'mapbox://styles/mapbox/light-v10',
			center: [<?=$site_item->mapbox_center_lng?>, <?=$site_item->mapbox_center_lat?>],
			zoom: <?=$site_item->mapbox_zoom?>,
			bearing: <?=$site_item->mapbox_bearing?>
		});
		map.on('load', function() {
			map.addSource('national-park', {
				'type': 'geojson',
				'data': <?=$site_item->geojson?>
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

		<?php foreach($site_polygons as $site_polygon_item){?>
			draw.add({type: 'Polygon', coordinates:  <?=$site_polygon_item->geojson?> });
		<?php }?>

		function updateArea(e) {
			var data = draw.getAll();
			var answer = document.getElementById('calculated-area');
			if (data.features.length > 0) {
				var area = turf.area(data);
				// restrict to area to 2 decimal points
				var rounded_area = Math.round(area * 100) / 100;
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
				// download polygon
				// document.getElementById('export').setAttribute('href', 'data:' + convertedData);
				// document.getElementById('export').setAttribute('download', 'data.geojson');
				$.post( "<?=base_url('polygon/add/1001/1')?>", {raw_polygons: data}).done(function(data) {
					// if(data == '0'){
						alert(data);
					// }else{
					// 	window.location.href="<?=base_url('survey/event').'/'?>" + data;
					// }
				});
			}
			else{
				alert("Wouldn't you like to draw some data")
			}
			}
		</script>
