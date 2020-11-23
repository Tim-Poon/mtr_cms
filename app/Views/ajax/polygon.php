<!-- widget grid -->
<section id="widget-grid" class="">
	<!-- row -->
	<div class="row">
		<article class="col-sm-12 col-md-12 col-lg-12">
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
				<!-- widget options:
				usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

				data-widget-colorbutton="false"
				data-widget-editbutton="false"
				data-widget-togglebutton="false"
				data-widget-deletebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-custombutton="false"
				data-widget-collapsed="true"
				data-widget-sortable="false"

				-->
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
						<?php foreach($sites as $site) {?>
						<a href="polygon/site/<?=$site->site_id?>" class="btn btn-success"><?= $site->site_name.' '.$site->floor_name?></a>
						<?php } ?>
					</div>
					<!-- end widget content -->
					<div class="row no-space">
						<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
							TODO: MAP 
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
