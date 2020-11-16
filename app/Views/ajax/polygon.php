<div id="done"> </div>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-map-marker fa-fw "></i> 
				<a href="polygon" style="color:#696969; cursor:pointer"><strong>Polygon</strong></a> 
			<span>
			</span>
		</h1>
	</div>
	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
		<ul id="sparks" class="">
			<li class="sparks-info">
				<h5> My Income <span class="txt-color-blue">$47,171</span></h5>
				<div class="sparkline txt-color-blue hidden-mobile hidden-md hidden-sm">
					1300, 1877, 2500, 2577, 2000, 2100, 3000, 2700, 3631, 2471, 2700, 3631, 2471
				</div>
			</li>
			<li class="sparks-info">
				<h5> Site Traffic <span class="txt-color-purple"><i class="fa fa-arrow-circle-up" data-rel="bootstrap-tooltip" title="Increased"></i>&nbsp;45%</span></h5>
				<div class="sparkline txt-color-purple hidden-mobile hidden-md hidden-sm">
					110,150,300,130,400,240,220,310,220,300, 270, 210
				</div>
			</li>
			<li class="sparks-info">
				<h5> Site Orders <span class="txt-color-greenDark"><i class="fa fa-shopping-cart"></i>&nbsp;2447</span></h5>
				<div class="sparkline txt-color-greenDark hidden-mobile hidden-md hidden-sm">
					110,150,300,130,400,240,220,310,220,300, 270, 210
				</div>
			</li>
		</ul>
	</div>
</div>

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
						<a href="javascript:void(0);" class="btn btn-success">KOB_1F</a>
						<a href="javascript:void(0);" class="btn btn-success">MOK_1F</a>
						<a href="javascript:void(0);" class="btn btn-success">KWF_1F</a>
						<a href="javascript:void(0);" class="btn btn-info disabled">YMT</a>
						<a href="javascript:void(0);" class="btn btn-info disabled">--</a>
						<a href="javascript:void(0);" class="btn btn-info disabled">--</a>
						<a href="javascript:void(0);" class="btn btn-info disabled">CEN_B1</a>
						<a href="javascript:void(0);" class="btn btn-info disabled">CEN_B2</a>
						<a href="javascript:void(0);" class="btn btn-info disabled">--</a>

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
