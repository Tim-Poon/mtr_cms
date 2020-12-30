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
						<hr class="simple">
						<div class="row no-space">
							<div class="col-xs-12 col-sm-12 col-md-8 col-lg-9" style="height:600px;">
								<!-- TODO: MAP -->
								<div id="map"></div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3"> 
								<div class="calculation-box">
									<a href="javascript:void(0);" id="save_polygon" class="btn btn-primary">Save</a>
									<a href="javascript:void(0);" id="export_polygon" class="btn btn-success">todo: Export</a>
								</div>
								<hr class="simple">
								<form id="import-form" class="smart-form">
									<fieldset>
										<div class="row">
											<section class="col col-9">
												<label class="textarea state-info" >
													<textarea rows="1" name="import"></textarea>
												</label>
											</section>
											<section class="col col-3">
												<button type="submit" class="btn btn-primary">Import</button>
											</section>
										</div>
									</fieldset>
								</form>
								<!-- <div class="input-group">
									<input class="form-control" type="text" placeholder="Your Polygon" id="import_content">
									<div class="input-group-btn">
										<a href="javascript:void(0);" id="import_polygon" class="btn btn-primary">Import</a>
									</div>
								</div> -->
								<hr class="simple">
								<h4>HISTORY</h4>
								<table id="dt_basic" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th class="text-align-center">TS CREATE</th>
											<th class="text-align-center"></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($site_ts_create as $key=>$site_ts_create_item) {?>
											<tr>
												<td class="text-align-center">
													<a href="<?= base_url('polygon/'.$site_item->site.'/'.$site_item->floor.'/'.$site_ts_create_item->ts_create)?>">
														<strong><?= $site_ts_create_item->ts_create?></strong>
													</a>
													<?php if ($key == 0) {?>
														<span class="label label-warning">New!</span>
													<?php }?> 
												</td>
												<td class="text-align-center">
													<a href="<?= base_url('polygon/del/'.$site_item->site.'/'.$site_item->floor.'/'.$site_ts_create_item->ts_create)?>">
														<i class="fa fa-trash-o"></i>
													</a>
												</td>
											</tr>
										<?php }?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- end widget content -->				
				</div>
				<!-- end widget div -->
			</div>
			<!-- end widget -->
		</article>
    </div>
</section>

<!-- end widget grid -->
<script>
	loadDataTableScripts();
	function loadDataTableScripts() {

		loadScript("<?= base_url('public/js/plugin/datatables/datatables.min.js')?>", dt_2);

		function dt_2() {
			loadScript("<?= base_url('public/js/plugin/datatables/ColReorder-1.5.2/js/dataTables.colReorder.min.js')?>", dt_3);
		}

		function dt_3() {
			loadScript("<?= base_url('public/js/plugin/datatables/FixedColumns-3.3.1/js/dataTables.fixedColumns.min.js')?>", dt_4);
		}

		function dt_4() {
			loadScript("<?= base_url('public/js/plugin/datatables/dataTables.colVis.js')?>", dt_6);
		}

		function dt_6() {
			loadScript("<?= base_url('public/js/plugin/datatables/dataTables.tableTools.min.js')?>", dt_7);
		}

		function dt_7() {
			loadScript("<?= base_url('public/js/plugin/datatables/DataTables-1.10.22/js/dataTables.bootstrap4.min.js')?>", runDataTables);
		}
	}

	function runDataTables() {

		$('#dt_basic').dataTable({

			sPaginationType : "full_numbers",
			dom : "<'dt-row dt-top-row'><'clear'>r<'dt-wrapper't><'dt-row dt-bottom-row'ip>",
			order: [[0, "desc"]],
		});
	}
</script>
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
	}

	document.getElementById('save_polygon').onclick = function(e){
		// extract GeoJson from featureGroup
		var data = draw.getAll();
		if(data.features.length > 0){
			// Stringify the GeoJson
			var convertedData = 'text/json;charset=utf-8,' + encodeURIComponent(JSON.stringify(data))
			// download polygon
			// document.getElementById('export').setAttribute('href', 'data:' + convertedData);
			// document.getElementById('export').setAttribute('download', 'data.geojson');
			$.post( "<?=base_url('polygon/add/'.$site_item->site.'/'.$site_item->floor)?>", {raw_polygons: data}).done(function(data) {
				// alert("Save Successfully!");
				window.location.href="<?= base_url('polygon/'.$site_item->site.'/'.$site_item->floor)?>";
				// if(data == '0'){
					// alert(data);
				// }else{
				// 	window.location.href="<?=base_url('survey/event').'/'?>" + data;
				// }
			});
		}
		else{
			alert("Wouldn't you like to draw some data")
		}
	}
	$('#import-form').submit(function(e){console.log(1);
		$.post("<?=base_url('polygon/import/'.$site_item->site.'/'.$site_item->floor)?>", $("#import-form").serialize()).done(function(data) {
            if(data == '0'){
                alert('please fill required field.');
            }else{
				console.log(data);
            }
		});
		return false;
	});
</script>
