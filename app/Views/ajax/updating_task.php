<!-- widget grid -->
<section id="widget-grid" class="">

	<!-- row -->
	<div class="row">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">

			<!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget jarviswidget-color-blueDark"
				data-widget-editbutton="false"
				data-widget-colorbutton="false"
				data-widget-deletebutton="false"
				data-widget-togglebutton="false"
				data-widget-sortable="false"
				data-widget-fullscreenbutton="false">

				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>Register</h2>

				</header>

				<!-- widget div-->
				<div>
					<div class="widget-body no-padding">
						<form id="updatetask-form" class="smart-form">
							<header>New Sensor Form</header>
							<fieldset>
                                <section>
                                    <label class="label">ts_create</label>
                                    <label class="input state-success"> <i class="icon-prepend fa fa-barcode"></i>
                                        <input type="text" name="ts_create" value="<?= $task_info['ts_create'] ?>" readonly="readonly">
                                    </label>
                                </section>
                                <section>
									<label class="label">target</label>
                                    <label class="input state-success"> <i class="icon-prepend fa fa-barcode"></i>
                                        <input type="text" name="target" value="<?= $task_info['target'] ?>" readonly="readonly">
                                    </label>
                                </section>
                                <section>
                                    <label class="Label">Label</label>
                                    <label class="input state-success"> <i class="icon-prepend fa  fa-calendar"></i>
                                        <input name="label" value="<?= $task_info['label'] ?>" readonly="readonly">
                                    </label>
                                </section>
								<section>
                                    <label class="Label">MD5</label>
                                    <label class="input state-success"> <i class="icon-prepend fa  fa-calendar"></i>
										<input name="label" value="<?= $task_info['md5'] ?>" readonly="readonly">
                                    </label>
                                </section>
								<section>
									<label class="label">Remark</label>
									<label class="textarea state-info" >
										<textarea rows="4" name="remark" ><?= $task_info['remark'] ?></textarea>
									</label>
								</section>
							</fieldset>

							<footer>
								<button type="submit" class="btn btn-primary">Submit</button>
							</footer>
						</form>
					</div>
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
	// PAGE RELATED SCRIPTS
	$('#updatetask-form').submit(function(e){
		$.post("<?=base_url('updating/update_ota_task')?>", $( "#updatetask-form" ).serialize()).done(function(data) {
            if(data == '0'){
                alert('please fill ');
            }else{
                window.location.href="<?=base_url('updating')?>";
            }
		});
		return false;
	});

</script>
