
			</div>
			<!-- END MAIN CONTENT -->				
					
		</div>
		<!-- END MAIN PANEL -->
		
		<!--================================================== -->	

		<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)
		<script data-pace-options='{ "restartOnRequestAfter": true }' src="js/plugin/pace/pace.min.js"></script>-->

	    <!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
	    
		<!-- JS TOUCH : include this plugin for mobile drag / drop touch events 		
		<script src="js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->

<script>
get_time();
function get_time() {
    $.get("dashboard/get_time")
        .done(function(data) {
            $('#time').html(data);
            setTimeout(get_time, 1000);
        });
}

loadOSStatus();
	function loadOSStatus() {
    // console.log(url)

		$.ajax({
			type: "GET",
			url: "sysinfo/get_os_status",
			dataType: 'json',
			cache: true, // (warning: this will cause a timestamp and will call the request twice)
			success: function (data) {
				// console.log("loadLogs");
				$('#cpu').html(data['cpu'] + "%");
				$('#memory').html(data['memory']['usage'] + "%");
				$('#storage').html(data['storage'] + "%");
				// setTimeout(loadOSStatus, 5000);
			},
			error: function (xhr, ajaxOptions, thrownError) {
				// container.html(
				// 	'<h4 style="margin-top:10px; display:block; text-align:left"><i class="fa fa-warning txt-color-orangeDark"></i> Error 404! Page not found.</h4>'
				// );
			},
			async: false
		});

    //console.log("ajax request sent");
	}
</script>

		
	</body>	
	
</html>