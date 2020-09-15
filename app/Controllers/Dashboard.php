<?php namespace App\Controllers;

use App\Models\DashboardModel;
use CodeIgniter\Controller;

class Dashboard extends Controller
{
	public function __construct()
    {
		// parent::__construct();
		$this->$model = new DashboardModel();
		$this->valid_log_level = array('info', 'warning', 'error');
		$this->valid_src_type = array('sensor', 'server', 'report');
	}

	private function check_valid_log_level($log_level){

	}

	private function check_valid_log_src_type($src_type){

	}

	private function ts2date($ts)
	{
		# todo
		$date = NULL;
		return $date;
	}

	public function index()
	{
		$data = [
			'page_content'   => 'dashboard/page',
			'heading' => 'My Heading',
			'message' => 'My Message'
		];
		echo view('index', $data);
	}

	public function page()
	{
		echo view('ajax/dashboard');
	}


	//--------------------------------------------------------------------
	public function real_time_sensor_status()
	{
		$sensors = $this->get_sensor_status_dev();

		#print_r($sensors);
		$cur_ts = time();
		$res = array();
		foreach($sensors as $sensor){
			$sensor_status = array();
			# todo: get val for var 
			$sensor_status['site_id'] = $senor->sensor_site;
			$sensor_status['sensor_id'] = $sensor->sensor_id;
			$sensor_status['last_seen_time'] = $sensor->l_conn;
			$sensor_status['lasting_time'] = $senor->l_conn - $senor->s_conn;  # if lasting_time is Null, 
			$sensor_status['recent_raw_flag'] = $cur_ts - $sensor->ts_beacon;
			$sensor_status['recent_loc_flag'] = $cur_ts - $seensor->ts_loc;
			$sensor_status['vm'] = $sensor->vm;
			array_push($res, $sensor_status);

		}
		return $res;
	}

    public function get_sensor_status_dev()
    {
        # code...
        $a = file_get_contents(config('ApiServer_')->apiServerUrl . config('ApiServer_')->sensor['sensor_status_dev']);
        // return $a;
        return json_decode($a);
	}

	public function logs($src_type='all', $log_level='all')
	{
		# note: sort by date in descend order
		$src_type = strtolower($src_type);
		$log_level = strtolower($log_level);
		$res = array();
		if (check_valid_log_src_type($src_type) and check_valid_log_level($log_level)){
			foreach($model->get_logs($src_type, $log_level)->getResult() as $row){
				$res['src_type'] = $row->src_type;
				$res['level'] = $row->level;
				$res['date'] = $this->ts2date($row->ts);
				$res['content'] = $row->content;
			}
		} else {
			echo "error";			
		}
		return $res;
	}

	public function todos($src_type='all', $log_level='all')
	{
		$src_type = strtolower($src_type);
		$log_level = strtolower($log_level);
		$res = array();
		if (check_valid_log_src_type($src_type) and check_valid_log_level($log_level)){
			foreach($model->get_todos($src_type, $log_level)->getResult() as $row){
				$res['src_type'] = $row->src_type;
				$res['date'] = $this->ts2date($row->ts);
				$res['flag'] = $row->flag;
			}
		} else {
			echo "error";			
		}
		return $res;
	# code...
	}

	public function unhandled_reports()
	{
		# note: the returned value will show which date is left to be done.
		# code...
	}

}
