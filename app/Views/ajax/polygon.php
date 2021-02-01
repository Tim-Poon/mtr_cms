<script src="<?= base_url('/public/js/mapbox/turf.min.js')?>"></script>
<script src="<?= base_url('/public/js/mapbox/mapbox-gl-draw.js')?>"></script>
<link href="<?= base_url('/public/css/mapbox/mapbox-gl-draw.css')?>" rel="stylesheet" />
<style>
    /* style for switching floor */
    #menu {
        background: #fff;
        position: absolute;
        z-index: 1;
        top: 10px;
        right: 50px;
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
						<?php foreach($site_names as $site_name) {?>
						<a href="<?= base_url('polygon/'.$site_name['site_name'])?>" class="btn btn-success"><?= $site_name['site_name']?></a>
						<?php } ?>
						<hr class="simple">
						<div class="row no-space">
							<div class="col-xs-12 col-sm-12 col-md-8 col-lg-9" style="height:600px;">
								<!-- TODO: MAP -->
								<nav id="menu"></nav>
								<div id="map"></div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3"> 
								<div class="calculation-box">
									<button type="submit" id="save_polygon" class="btn btn-primary">Save</button>
									<!-- <button type="submit" id="export_polygon" class="btn btn-success">Export</button> -->
								</div>
								<hr class="simple">
								<form id="import-form" class="smart-form">
									<fieldset>
										<div class="row">
											<section class="col col-9">
												<label class="input state-success" >
													<input type="text" name="import"></input>
												</label>
											</section>
											<section class="col col-3">
												<button type="submit" class="btn btn-primary btn-sm">Import</button>
											</section>
										</div>
									</fieldset>
								</form>
								<hr class="simple">
								<h4>HISTORY</h4>
								<table id="dt_basic" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th class="text-align-center">TS CREATE</th>
											<th class="text-align-center"></th>
										</tr>
									</thead>
									<tbody id='ts_create_content'>
										
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
	var dot_mapping = <?= $site_info[0]['dot_mapping']?>;
	var floor_cur = <?php if($site_polygons){echo $site_polygons[0]->floor;}else{echo $site_info[0]['floor'];} ?>;

	var map = new mapboxgl.Map({
		container: 'map',
        style: 'mapbox://styles/mapbox/light-v10',
        center: [<?= $site_info[0]['mapbox_center_lng']?>, <?= $site_info[0]['mapbox_center_lat']?>],   
        zoom: <?= $site_info[0]['mapbox_zoom']?>,
        bearing: <?= $site_info[0]['mapbox_bearing']?> 
	});

	function get_floor_dot_mapping() {
        <?php foreach ($site_info as $site_floor_item) { ?>
            if (floor_cur == <?= $site_floor_item['floor'] ?>) {
                dot_mapping = <?= $site_floor_item['dot_mapping'] ?>;
            }
        <?php } ?>
    }

	function latlng2xy(lng, lat){
        // floor_cur
		var pixel_mapbox = map.project([lng, lat]);
        var coorA = [dot_mapping[0]['lng'], dot_mapping[0]['lat']];
        var coorB = [dot_mapping[1]['lng'], dot_mapping[1]['lat']];
        var mA = {x: dot_mapping[0]['x'], y: dot_mapping[0]['y']};
        var mB = {x: dot_mapping[1]['x'], y: dot_mapping[1]['y']};
        
        var pA = map.project(coorA);
        var pB = map.project(coorB);

		x = (pixel_mapbox.x - pA.x) * (mB.x - mA.x) / (pB.x - pA.x) + mA.x;
		y = (pixel_mapbox.y - pA.y) * (mB.y - mA.y) / (pB.y - pA.y) + mA.y;
        return [x,y]
	}
	
	var site_map_geojson;
	<?php foreach ($site_info as $site_info_item) { ?>
		if (<?= $site_info_item['floor']?> == floor_cur) {
			site_map_geojson = <?= $site_info_item['geojson']?>;
		}
	<?php } ?>
	
	map.on('load', function() {
		map.addSource('site_map', {
			'type': 'geojson',
			'data': site_map_geojson
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
                'line-color': '#BB8FCE',
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
	
	// map.on('draw.create', updateArea);
	// map.on('draw.delete', updateArea);
	// map.on('draw.update', updateArea);

	<?php if($site_polygons){ ?>
		<?php foreach($site_polygons as $site_polygon_item){?>
			if (<?= $site_polygon_item->floor ?> == floor_cur) {
				draw.add({type: 'Polygon', coordinates:  <?=$site_polygon_item->geojson?> });
			}
	<?php }}?>

	function updateArea(e) {
		var data = draw.getAll();
	}

	function get_floor_ts_create() {
		var ts_create_floor = '';
		<?php foreach ($site_ts_create as $key=>$site_ts_create_item) {?>
			if (<?= $site_ts_create_item->floor ?> == floor_cur) {
				ts_create_floor += '<tr><td class="text-align-center">';
				ts_create_floor += '<a href="<?= base_url('polygon/'.$site_info[0]['site_name'].'/'.$site_ts_create_item->ts_create)?>"><strong><?= $site_ts_create_item->ts_create?></strong></a>';
				ts_create_floor += '<?php if ($key == 0) {?><span class="label label-warning">New!</span><?php }?></td>';
				ts_create_floor += '<td class="text-align-center">';
				ts_create_floor += '<a href="<?= base_url('polygon/del/'.$site_info[0]['site'].'/'.$site_ts_create_item->ts_create)?>"><i class="fa fa-trash-o"></i></a>&nbsp';
				ts_create_floor += '<a href="<?= base_url('polygon/export/'.$site_info[0]['site'].'/'.$site_ts_create_item->ts_create)?>"><i class="fa fa-download"></i></a></td></tr>';	
			}
		<?php } ?>
		return ts_create_floor;
	}
	$('#ts_create_content').html(get_floor_ts_create());

	// floor switcher
	<?php foreach ($site_info as $idx => $site_floor_item) { ?>
            
		var link = document.createElement('a');
		link.href = '#';
		if (<?= $site_floor_item['floor'] ?> == floor_cur) {
			link.className = 'active';
		}
		link.textContent = '<?= $site_floor_item['floor_name']?>';
		
		link.onclick = function (e) {
			e.preventDefault();
			e.stopPropagation();
			$('a').removeClass('active');
			this.className = 'active';
			//todo:
			if (<?= $site_floor_item['floor'] ?> == floor_cur) {
				draw.deleteAll().getAll();
			}

			floor_cur = <?= $site_floor_item['floor']?>;

			get_floor_dot_mapping();
			map.getSource('site_map').setData(<?= $site_floor_item['geojson']?>);
			$('#ts_create_content').html(get_floor_ts_create());
		};
		var layers = document.getElementById('menu');
		layers.appendChild(link);
	<?php } ?>

	$('#save_polygon').click(function(){
		// extract GeoJson from featureGroup
		var data = draw.getAll();
		// console.log(data);
		if(data.features.length > 0){
			for(var i = 0; i < data['features'].length; i++){
				data['features'][i]['geometry']['xy'] = [[]];
				data['features'][i]['geometry']['coordinates'][0].forEach(lnglat => {
					data['features'][i]['geometry']['xy'][0].push(latlng2xy(lnglat[0], lnglat[1]));
				});
			}

			// console.log(data);
			$.post( "<?=base_url('polygon/save/'.$site_info[0]['site'])?>/" + floor_cur, {raw_polygons: data}).done(function(data) {
				// alert("Save Successfully!");
				link = "<?= base_url('polygon/'.$site_info[0]['site_name'])?>" + "/" + data;
				window.location.href= link;
			});
		}
		else{
			alert("Wouldn't you like to draw some data");
		}
	});

	$('#import-form').submit(function(e){
		$.post("<?=base_url('polygon/import/'.$site_item->site.'/'.$site_item->floor)?>", $("#import-form").serialize()).done(function(data) {
            if(data == '0'){
                alert('error format, please fill again.');
			}else{
				// alert("Save Successfully!");
				link = "<?= base_url('polygon/'.$site_item->site.'/'.$site_item->floor)?>" + "/" + data;
				window.location.href= link;
            }
		});
		return false;
	});
</script>
