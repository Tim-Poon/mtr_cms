<div id="done"> </div>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-check-circle-o fa-fw "></i> 
				<a href="todos" style="color:#696969; cursor:pointer"><strong>Todos</strong></a> 
			<span><strong style="color:#696969">> </strong>
                <strong style="color:#496949">Todo # </strong>
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

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0" data-widget-editbutton="false">
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
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>TODO</h2>

				</header>

				<!-- widget div-->
				<?php if($todo == '0'){; ?>
				<div>
					<div class="table-responsive">
						<table class="table table-bordered hidden-mobile">
							<thead>
								<tr>
									<th>Date</th>
									<th>scr_type</th>
									<th>content</th>
								</tr>
							</thead>
							<tbody>
								<tr class="danger">
									<td><?= $date ?></td>
									<td>
										<code>
											<?= $stype ?>
										</code
									></td>
									<td><?= $content ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="widget-body no-padding">
						<form id="contact-form" class="smart-form">
							<header>Check form</header>

							<fieldset>
								<section>
									<label class="label">message</label>
									<div class="note note-error">This is a required field.</div>
									<input type="hidden" name="id" value="<?= $id ?>">
									<label class="textarea state-error" >
										<textarea rows="4" name="message" id="reporting_message" ></textarea>
									</label>
								</section>

								<section>
									<label class="checkbox"><input type="checkbox" name="copy" id="copy"><i></i>Send a copy to my e-mail address</label>
								</section>
							</fieldset>

							<footer>
								<button type="submit" class="btn btn-primary">Submit</button>
							</footer>
						</form>
					</div>
				</div>
				<?php }else{ ?>
				 <div>
					<div class="table-responsive">
						<table class="table table-bordered hidden-mobile">
							<thead>
								<tr>
									<th>Date</th>
									<th>scr_type</th>
									<th>content</th>
									<th>msg</th>
								</tr>
							</thead>
							<tbody>
								<tr class="success">
									<td><?= $date ?></td>
									<td>
										<code>
											<?= $stype ?>
										</code
									></td>
									<td><?= $content ?></td>
									<td><?= $solve_message ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<?php } ?>
				<!-- end widget div -->

			</div>
			<!-- end widget -->

		</article>
		<!-- WIDGET END -->


		<!-- NEW WIDGET START -->
		<article class="col-sm-12 col-md-12 col-lg-12">

			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-greenLight" id="wid-id-3" data-widget-editbutton="false">
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
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>Relate </h2>

				</header>

				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body no-padding">

						<table class="table table-bordered">
							<thead>
								<tr>
									<th> <i class="fa fa-building"></i> SRC_TYPE </th>
									<th> <i class="fa fa-calendar"></i> Content</th>
									<th> <i class="fa fa-calendar"></i> Sovle MSG</th>
									<th> <i class="glyphicon glyphicon-send"></i> TODO </th>
								</tr>
							</thead>
							<tbody>
							    <?php if ($related_logs) {foreach($related_logs as $log){
							        if($log->todo==1)
							        {
							        }
							        elseif($log->todo==0)
							        {
							        }
							    // todo: if the todo is null, then make it grey, if it's done, make a green tick, otherwise make it empty
// 							        echo " <tr class=\"info\">
//                                             <td>{$log->src_type}</td>
//                                             <td>{$log->content}</td>
//                                             <td>{$log->solve_message}</td>
//                                             <td>{$log->todo}</td>
//                                         </tr>";
// 					             echo " <tr class=\"info\">
//                                             <td>{$log->src_type}</td>
//                                             <td>{$log->content}</td>
//                                             <td>{$log->solve_message}</td>
//                                             <td class=\"text-align-center demo-icon-font bg-color-grey\">
// 										        <i class=\"fa fa-check\"></i>
//
// 										    </td>
//                                         </tr>";
					             echo " <tr class=\"info\">
                                            <td>{$log->src_type}</td>
                                            <td>{$log->content}</td>
                                            <td>{$log->solve_message}</td>
                                            <td class=\"bg-color-grayLLight\"></td>
                                        </tr>";
							        }
							    }
							        ?>
							</tbody>
						</table>

					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->

		</article>
		<!-- WIDGET END -->

	</div>

	<!-- end row -->

</section>
<!-- end widget grid -->

<script type="text/javascript">
	// DO NOT REMOVE : GLOBAL FUNCTIONS!
	pageSetUp();

	// PAGE RELATED SCRIPTS
	$('#contact-form').submit(function(e){
		$.post( "todos/submit", $( "#contact-form" ).serialize())
		.done(function(data) {
// 			$('#done').html(data);
            location.reload();
		});
		return false;
	});

</script>
