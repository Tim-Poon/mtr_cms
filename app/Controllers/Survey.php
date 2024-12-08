<?php namespace App\Controllers;

use App\Models\SurveyModel;
use App\Models\MonitorModel;
use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;

class Survey extends Controller
{
	public function __construct()
    {
		// parent::__construct();
		$this->model = new SurveyModel();
		$this->model_monitor = new MonitorModel();
		$this->source = array('survey_beacon', 'survey_gt', 'survey_imu');
		$this->source_table = [
			'survey_beacon' => ' raw_beacon_data',
			'survey_imu' => 'raw_imu_data',
			'survey_gt' => 'event_plan'
		];

		$this->model_monitor = new MonitorModel();
	}

	public function _remap($method, ...$params)
	{
		if ($method == 'event')
		{
			return $this->event($params);
		}elseif ($method == 'new_event')
		{
			return $this->new_event();
		}elseif ($method == 'add_event')
		{
			$this->add_event();
		}elseif ($method == 'update')
		{
			$this->update();
		}elseif ($method == 'api_data')
		{
			$data_type = $params[0];
			$event = $params[1];
			$this->api_data($data_type, $event);
		}elseif ($method === 'download')
		{
			$data_type = $params[0];
			$event = $params[1];
			$this->download($data_type, $event);
		}elseif ($method === 'save_plan')
		{
			$event = $params[0];
			$floor = $params[1];
			$this->save_plan($event, $floor);
		}elseif ($method === 'save_plan_data')
		{
			$event = $params[0];
			$floor = $params[1];
			$this->save_plan_data($event, $floor);
		}elseif ($method === 'remove_plan_data')
		{
			$event = $params[0];
			$this->remove_plan_data($event);
		}elseif ($method === 'confirm_plan_data')
		{
			$event = $params[0];
			$this->confirm_plan_data($event);
		}else
		{
			return $this->index();
		}
    }

	public function get_survey_offline_file()
	{
		$path = "/var/www/html/mtr_cms/public/survey/";
		$list = scandir($path);
		$file_list = [];
		foreach ($list as $value) {
			
			$ext = pathinfo($value)['extension'];
			if (in_array($ext, array('csv')) or in_array($ext, array('MOV'))) {
				$data = 
				[
					'name' => $value,
					'size' => round(filesize($path.$value)/1024/1024, 2),
					'path' => base_url('public/survey/'.$value),
				];
				array_push($file_list, $data);
			}
		}
		return $file_list;
	}

	public function index()
	{
		$event_all = $this->model->get_event_all()->getResult();
		$offline_data_files = $this->get_survey_offline_file();
		// todo: online simply analysis
		$data = 
		[
			'icon' => 'fa-gear',
			'title' => 'Survey',
			'sub_title' => '',
			'site_names' => $this->model_monitor->get_site_name_all(),
			'offline_data_files' => $offline_data_files,
			'event_all' => $event_all,
		];
        echo view('head', $data);
		echo view('js');
		echo view('ajax/survey', $data);
		echo view('foot');
		// print_r($offline_data_files);
	}

	private function remove_plan_data($event)
	{
		$res = $this->model->remove_plan_data($event);
		echo $res;
	}

	private function confirm_plan_data($event)
	{
		$res = $this->model->confirm_plan_data($event);
		echo $res;
	}

	private function save_plan_data($event, $floor)
	{
		// echo $event;
		$raw_plan_data = $this->request->getPost(['raw_plan_data']);
		// print_r($raw_plan_data);
		if ($raw_plan_data) {
			$data = 
			[
				'ground_truth' => str_replace('"', '', json_encode($raw_plan_data['raw_plan_data']))
			];
			$a = $this->model->save_plan_data($event, $data);
		}else {
			echo 0;
		}	
	}
	
	private function save_plan($event, $floor)
	{
		$raw_plan = $this->request->getPost(['raw_plan']);
		if ($raw_plan['raw_plan']['features']) {
			$plan_type = 0; //point
			$type = '';
			// check type of plan, allow point or linestring
			foreach ($raw_plan['raw_plan']['features'] as $plan_item) {
				if ($plan_item['geometry']['type'] == 'LineString') {
					$plan_type ++;
				}
			}
			if ($plan_type == 0) {
				# point
				echo 'point';
				$type = 'point';
			}elseif($plan_type == 1 && count($raw_plan['raw_plan']['features']) == 1){
				# linestring
				echo 'linestring';
				$type = 'linestring';
			}else {
				echo '0';
				return 0;
			}
			$ts_create = $this->get_timestamp();
			if ($type == 'linestring') {
				$plan_data = 
				[
					'event' => $event,
					'floor' => $floor,
					'landmark' => 'linestring',
					'geojson' => str_replace('"', '', json_encode($raw_plan['raw_plan']['features'][0]['geometry']['coordinates'])),
					'ts_create' => $ts_create,
					'flag' => 1
				];
				print_r($plan_data);
				$a = $this->model->save_plan($plan_data);
				print_r($a);
			}elseif ($type == 'point') {
				$points = [];
				foreach ($raw_plan['raw_plan']['features'] as $plan_item) {
					echo str_replace('"', '', json_encode($plan_item['geometry']['coordinates']));
					array_push($points, str_replace('"', '', json_encode($plan_item['geometry']['coordinates'])));
				}
				$plan_data = 
				[
					'event' => $event,
					'floor' => $floor,
					'landmark' => 'point',
					'geojson' => str_replace('"', '', json_encode($points)),
					'ts_create' => $ts_create,
					'flag' => 1
				];
				print_r(json_encode($plan_data));
				$a = $this->model->save_plan($plan_data);
				print_r($a);
			}
		}else {
			echo 0;
		}	
	}

	function new_event()
	{
		$time = Time::now('Asia/Hong_Kong', 'en_US');
		$data = 
		[
			'icon' => 'fa-gear',
			'title' => 'Survey',
			'sub_title' => '> New Event',
			'site_names' => $this->model_monitor->get_site_name_all(),
			'date' => $time->toLocalizedString('yyyy-MM-dd'),
			'ts'   => $time->getTimestamp(),
		];
				
		echo view('head', $data);
		echo view('js');
		echo view('ajax/survey_new', $data);
		echo view('foot');
	}
	
	public function event($params)
	{
		$event = $params[0];
		if ($event) 
		{
			$event_item = $this->model->get_event_item($event)->getResult();
			$plan_data = $this->model->get_plan_data($event)->getResult();
			if ($event_item)
			{
				$site_info = $this->model_monitor->get_site_info($event_item[0]->site);
				$data = 
				[
					'icon' => 'fa-gear',
					'title' => 'Survey',
					'sub_title' => '> Event #'.$event,
					'site_names' => $this->model_monitor->get_site_name_all(),
					'event_item' => $event_item[0],
					'data_beacon' => $event_beacon_data,
					'data_wifi' => $event_wifi_data,
					'data_imu' => $event_imu_data,
					'site_info' => $site_info,
					'mapbox_key' => config('ApiServer_')->mapbox['key'],
					'plan_data' => $plan_data[0]
				];
				echo view('head', $data);
				echo view('js');
				echo view('ajax/survey_data', $data);
				echo view('foot');
			}
            
        }
	}

	public function add_event()
    {
        if($this->request->getPost()['event'] && $this->request->getPost()['date'] && $this->request->getPost()['site'])
        {
            $data = array(
                'event' => $this->request->getPost()['event'],
                'date' => $this->request->getPost()['date'],
                'site' => $this->request->getPost()['site'],
                'remark' => $this->request->getPost()['remark'],
            );
            $this->model->add_event($data);
            echo $data['event'];
        } else{
            echo '0';
        }
    }

	public function update()
	{
		if($this->request->getPost()['event'] && $this->request->getPost()['date'] && $this->request->getPost()['site']){
			$event = $this->request->getPost()['event'];
			$data = array(
                'date' => $this->request->getPost()['date'],
                'site' => $this->request->getPost()['site'],
                'remark' => $this->request->getPost()['remark'],
            );
			$this->model->update_event($event, $data);
			$submit_msg = "Update Event # $event";
			$submit_data = ['msg' => $submit_msg];
			echo view('submit', $submit_data);
		}else{
			echo 0;
		}
	}
	
	public function api_data($source, $event_id)
	{
		if (in_array($source, $this->source)){
			if ($source == 'survey_gt') {
				$event = $this->model->get_event_item($event_id)->getResult('array');
				$event_plan = $this->model->get_plan_data($event_id)->getResult('array');
				echo json_encode($event);
				echo json_encode($event_plan);
			}else{
				$res = $this->model->api_data($this->source_table[$source], $event_id)->getResult('array');
				echo json_encode($res);
			}
		}else{
			echo "error source!";
		}	
	}
	
	public function download($source, $event_id)
	{	
		if (in_array($source, $this->source) && $source != 'survey_gt'){
			$res = $this->model->api_data($this->source_table[$source], $event_id)->getResult('array');
			if ($res){
				$head = array_keys($res[0]);
				header('Content-Type: application/vnd.ms-excel;charset=UTF-8');
				header('Content-Type: application/force-download');
				$filename = 'event_'.$event_id.'_'.$source.'.csv';
				header('Content-Disposition: attachment;filename='.$filename);
				$fp = fopen('php://output', 'a');
				fputcsv($fp, $head);
				foreach($res as $row) {
					fputcsv($fp, $row);
				}
			}
		}else{
			echo "error source!";
		}
	}

	private function get_timestamp()
	{
	    $time = Time::now('Asia/Hong_Kong', 'en_US');
	    return $time->getTimestamp();
	}
}