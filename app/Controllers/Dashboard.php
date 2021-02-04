<?php namespace App\Controllers;

use App\Models\DashboardModel;
use App\Models\MonitorModel;
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
		$this->model_monitor = new MonitorModel();

		$this->valid_src_type = array('sensor', 'server', 'report');
		$this->request = \Config\Services::request();

		$this->sensor_info = $this->model->get_sensor_info()->getResult();
		
	}

	public function index()
	{
		$data = 
		[
			'icon' => 'fa-home',
			'title' => 'Dashboard',
			'sub_title' => '',
			'site_names' => $this->model_monitor->get_site_name_all(),
			'logs'   => $this->logs(),
			'num_of_todos' => 0,
			'todos' => $this->todos()
		];

		echo view('head', $data);
		echo view('js');
		echo view('ajax/dashboard', $data);
		echo view('foot');
	}

	public function logs()
	{
		$res = '';
		$logs = $this->model->get_logs(30);

		foreach($logs->getResult() as $log){
			$ts = Time::createFromTimestamp($log->ts / 1000, 'Asia/Hong_Kong', 'en_US');
			$level = $this->log_level_mapping[$log->level];
			$content = $log->content;
            $res = $res .
                  "<tr class= \"$level\">
				  <td> $ts </td>
				  <td> $log->src_type </td>
				  <td> $content </td>
				  <td> $log->todo </td>
			  	  </tr>";
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
                $time = Time::createFromTimestamp($todo->ts/1000, 'Asia/Hong_Kong', 'en_US');
                $todo_status['start'] = "{$time->getYear()}-{$time->getMonth()}-{$time->getDay()}";
                $todo_status['className'] = $this->loglevel2todo_mapping[$todo->level];


                $todo_status['id'] = "todos{$todo->id}"; //todo
                $todo_status['ts'] = $time;
                $todo_status['src_type'] = $todo->src_type;
                $todo_status['content'] = $todo->content;
				$todo_status['level'] = $todo->level;
				
				// link to todos page
				$todo_status['url'] = "todos?stype=$todo->src_type&id=$todo->id";
                array_push($res, $todo_status);
			}

		    return ($res);
	}

	public function get_time()
	{
	    $time = Time::now('Asia/Hong_Kong', 'en_US');
	    echo $time;
	}
}
