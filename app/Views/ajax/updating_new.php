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
					<h2>OTA Task</h2>

				</header>

				<!-- widget div-->
				<div>
					<div class="widget-body no-padding">
						<form id="newota-form" class="smart-form">
							<header>New OTA Task Form</header>
							<fieldset>
                                <section>
                                    <label class="label">ts_create</label>
                                    <label class="input state-success"> <i class="icon-prepend fa fa-barcode"></i>
                                        <input type="text" name="ts_create" value="<?= $ts_create?>" readonly="readonly">
                                    </label>
                                </section>
                                <section>
									<label class="label">Target</label>
                                    <label class="select state-success">
                                        <select name="target">
                                        <option value=NULL></option>
										
                                            <?php foreach ($targets_info as $targets_info_item) {
												$site_name = '';
												foreach ($site_names as $site_name_item){
													if ($targets_info_item->site == $site_name_item['site']) {
														$site_name = $site_name_item['site_name'];
														break;
													}
												}
                                                echo "<option value=\"".$targets_info_item->sensor."\">".$site_name." - ".$targets_info_item->sensor." - ".$targets_info_item->label."</option>";
                                            } ?>
                                        </select> <i></i> </label>
                                </section>
                                <section>
                                    <label class="Label">Label</label>
                                    <label class="input state-success"> <i class="icon-prepend fa  fa-calendar"></i>
                                        <input name="label">
                                    </label>
                                </section>
								<section>
                                    <label class="Label">MD5</label>
                                    <label class="input state-success"> <i class="icon-prepend fa  fa-calendar"></i>
                                        <input name="md5">
                                    </label>
                                </section>
								<section>
									<label class="label">Remark</label>
									<label class="textarea state-info" >
										<textarea rows="4" name="remark" ></textarea>
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
	$('#newota-form').submit(function(e){
		$.post("<?=base_url('updating/add_new_ota_task')?>", $( "#newota-form" ).serialize()).done(function(data) {
            if(data == '0'){
                alert('please fill ');
            }else{
                window.location.href="<?=base_url('updating')?>";
            }
		});
		return false;
	});

</script>