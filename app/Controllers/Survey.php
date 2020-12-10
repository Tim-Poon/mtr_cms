<?php namespace App\Controllers;

use App\Models\SurveyModel;
use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;

class Survey extends Controller
{
	public function __construct()
    {
		// parent::__construct();
		$this->model = new SurveyModel();
		$this->source = array('survey_beacon', 'survey_wifi', 'survey_imu', 'survey_uwb_loc', 'survey_uwb_dist');
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
		}else
		{
			return $this->index();
		}
    }

	public function index()
	{
		$event_all = $this->model->get_event_all()->getResult();
		// todo: online simply analysis
		$data = ['event_all' => $event_all,
				 'icon' => 'fa-truck',
				 'title' => 'Survey',
				 'sub_title' => ''];
        echo view('head', $data);
		echo view('js');
		$this->set_data();
		echo view('foot');
	}

	function new_event()
	{
		$time = Time::now('Asia/Hong_Kong', 'en_US');
		$data = array(
			'date' => $time->toLocalizedString('yyyy-MM-dd'),
			'ts'   => $time->getTimestamp(),
			'icon' => 'fa-truck',
			'title' => 'Survey',
			'sub_title' => '> New Event'
		);
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
			if ($event_item)
			{
				$event_beacon_data = $this->model->get_beacon($event)->getResult();
				$event_wifi_data = $this->model->get_wifi($event)->getResult();
				$event_imu_data = $this->model->get_imu($event)->getResult();
				// $event_uwb_loc_data = $this->model->get_uwb_loc_by_id($event_id)->getResult();
				// $event_uwb_dist_data = $this->model->get_uwb_dist_by_id($event_id)->getResult();
				$data = [
					'event_item' => $event_item[0],
					'data_beacon' => $event_beacon_data,
					'data_wifi' => $event_wifi_data,
					'data_imu' => $event_imu_data,
					// 'data_uwb_loc' => $event_uwb_loc_data,
					// 'data_uwb_dist' => $event_uwb_dist_data,
					'icon' => 'fa-truck',
					'title' => 'Survey',
					'sub_title' => '> Event #'.$event];
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

	protected function set_data(){
        # get parameter
        $param_from_url = $this->request->getGet();

        $event_id = $param_from_url['id'];
        if ($event_id) {
			
            $event_detail = $this->model->get_event_by_id($event_id)->getResult();
            $event_beacon_data = $this->model->get_beacon_by_id($event_id)->getResult();
			$event_wifi_data = $this->model->get_wifi_by_id($event_id)->getResult();
			$event_imu_data = $this->model->get_imu_by_id($event_id)->getResult();
			$event_uwb_loc_data = $this->model->get_uwb_loc_by_id($event_id)->getResult();
			$event_uwb_dist_data = $this->model->get_uwb_dist_by_id($event_id)->getResult();
            $data = [
                'event' => $event_detail[0],
				'data_beacon' => $event_beacon_data,
				'data_wifi' => $event_wifi_data,
				'data_imu' => $event_imu_data,
				'data_uwb_loc' => $event_uwb_loc_data,
				'data_uwb_dist' => $event_uwb_dist_data,
            ];
           echo view('ajax/survey_data', $data);
        }
        elseif ($stype == 'api_server') {
        }
        elseif ($stype == 'reporting_server') {
        }
        elseif ($stype == 'sensor') {
        }
        else {
            echo view('ajax/survey');
        }
	}
	
	public function api_data($source, $event_id)
	{
		if (in_array($source, $this->source)){
			$res = $this->model->api_data($source, $event_id)->getResult();
			echo json_encode($res);
		}else{
			echo "error source!";
		}	
	}
	
	public function download($source, $event_id)
	{	
		if (in_array($source, $this->source)){
			$res = $this->model->api_data($source, $event_id)->getResult('array');
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
}

// http://127.0.0.1/mtr_cms/survey/(index)
// http://127.0.0.1/mtr_cms/survey/lits