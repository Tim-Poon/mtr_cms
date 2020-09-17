<?php namespace App\Controllers;

use App\Models\DashboardModel;
use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;

class Dashboard extends Controller
{
	public function __construct()
    {
		// parent::__construct();
		$this->valid_log_level = array('info', 'warning', 'error', 'debug');
		$this->log_level_mapping = array("info" => "default",
										 "debug" => "danger",
										 "warning" => "warning",
										 "error" => "danger");

        $this->loglevel2todo_mapping = array("info" => array("event", "bg-color-greenLight"),
										 "debug" => array("event", "bg-color-greenLight"),
										 "warning" => array("event", "bg-color-orange"),
										 "error" => array("event", "bg-color-red"));

		$this->model = new DashboardModel();

		$this->valid_src_type = array('sensor', 'server', 'report');
		$this->request = \Config\Services::request();
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
		$data = [
			// 'real_time_sensor_status'   => $this->real_time_sensor_status(),
			// 'logs' => $this->logs(),
			'message' => 'My Message'
		];
		// echo view('ajax/dashboard', $data);
		echo view('ajax/dashboard');
	}


	//--------------------------------------------------------------------

	public function real_time_sensor_status()
	{
		$sensors = $this->get_sensor_status_dev();

		$cur_ts = time();
		$res = array();
		foreach($sensors as $sensor){
			$sensor_status = array();
			# todo: get val for var 
			$sensor_status['site_id'] = $sensor->sensor_site;
			$sensor_status['sensor_id'] = $sensor->sensor_id;
			// $sensor_status['last_seen_time'] = $sensor->l_conn;
			$sensor_status['last_seen_time'] = Time::createFromTimestamp($sensor->l_conn / 1000, 'Asia/Shanghai', 'en_US');
			$sensor_status['lasting_time'] = intval(($sensor->l_conn - $sensor->s_conn) / (1000 * 60));  # if lasting_time is Null, 
			$sensor_status['recent_raw_flag'] = 'default';
			$sensor_status['recent_raw_flag'] = 'default';
			if($cur_ts - $sensor->ts_beacon / 1000 < 60)
			{
				$sensor_status['recent_raw_flag'] = 'success';
			}
			if($cur_ts - $sensor->ts_loc / 1000 < 60)
			{
				$sensor_status['recent_loc_flag'] = 'success';
			}
			$sensor_status['vm'] = $sensor->vm;
			array_push($res, $sensor_status);

		}
		foreach ($res as $sensor)
		{
			$site_id = $sensor['site_id'];
			$sensor_id = $sensor['sensor_id'];
			$sensor_val = $sensor['val'];
			$sensor_last_seen_time = $sensor['last_seen_time'];
			$sensor_lasting_time = $sensor['lasting_time'];
			$sensor_recent_raw_flag = $sensor['recent_raw_flag'];
			$sensor_recent_loc_flag = $sensor['recent_loc_flag'];
			$sensor_vm = $sensor['vm'];

			echo "<tr>
					<td class=\"text-align-center\">$site_id-$sensor_id</td>
					<td class=\"text-align-center\">$sensor_val</td>
					<td class=\"text-align-center\">$sensor_last_seen_time</td>
					<td class=\"text-align-center\">$sensor_lasting_time</td>
					<td class=\"text-align-center\"><span class=\"label label-$sensor_recent_raw_flag\">$sensor_recent_raw_flag</span></td>
					<td class=\"text-align-center\"><span class=\"label label-$sensor_recent_loc_flag\">$sensor_recent_loc_flag</span></td>
					<td class=\"text-align-center\">$sensor_vm</td>
				 </tr>";

		}
		// print_r($res);
		return $res;
	}

    public function get_sensor_status_dev()
    {
        # code...
        $a = file_get_contents(config('ApiServer_')->apiServerUrl . config('ApiServer_')->sensor['sensor_status_dev']);
        // return $a;
        return json_decode($a);
	}

	public function logs()
	{
		$res = array();
		$m = $this->model;
		$logs = $m->get_logs(30);


		foreach($logs->getResult() as $log){
			$log_status['ts'] = Time::createFromTimestamp($log->ts / 1000, 'Asia/Shanghai', 'en_US');
			$log_status['src_type'] = $log->src_type;
			$log_status['content'] = $log->content;
			$log_status['level'] = $log->level;
			array_push($res, $log_status);
		}

		foreach ($res as $log)
		{
			$ts = $log['ts'];
			$src_type = $log['src_type'];
			$content = $log['content'];
			$level = $this->log_level_mapping[$log['level']];

			// print_r($this->log_level_mapping);
			// echo $this->log_level_mapping[$level].'</br>';
			echo "<tr class= \"$level\">
				  <td> $ts </td>
				  <td> $src_type </td>
				  <td> $content </td>
			  	  </tr>";
				// <tr class="danger">
				// <tr class="warning">
				// <tr class="info">
		}

		return $res;
	}

	public function todos($src_type='all', $log_level='all')
	{
			$res = array();
            $m = $this->model;
            $todos = $m->get_todos(30);

            foreach($todos as $todo){
                $todo_status['title'] = $todo->content;
                $todo_status['allDay'] = true;
                $time = Time::createFromTimestamp($todo->ts / 1000, 'Asia/Shanghai', 'en_US');
                $todo_status['start'] = "{$time->getYear()}-{$time->getMonth()}-{$time->getDay()}";
                $todo_status['className'] = $this->loglevel2todo_mapping[$todo->level];

                $todo_status['ts'] = $time;
                $todo_status['src_type'] = $todo->src_type;
                $todo_status['content'] = $todo->content;
                $todo_status['level'] = $todo->level;
                array_push($res, $todo_status);
			}

		    return json_encode($res);
	}

	public function unhandled_reports()
	{
		// print_r($this->$request->getGet());
		# note: the returned value will show which date is left to be done.
		# code...
	}

}
