<?php namespace App\Controllers;

use App\Models\SurveyModel;
use CodeIgniter\Controller;

class Survey extends Controller
{
	public function __construct()
    {
		// parent::__construct();
		$this->model = new SurveyModel();
	}

	public function index()
	{
		$events = $this->model->get_event()->getResult();
		// record each survey
		// todo: build survey by date
		// todo: show survey data (include raw & groundture)
		// todo: online simply analysis
		// todo: download servey data
        $data = ['events' => $events];
        echo view('head', $data);
		echo view('js');
		$this->set_data();
		echo view('foot');
	}

	public function update()
	{
		# code...
	}

	public function api_data($source, $event_id)
	{
		if ($source == 'survey_beacon' || $source == 'survey_wifi' || $source == 'survey_imu' || $source == 'survey_uwb_loc' || $source == 'survey_uwb_dist' ){
			$res = $this->model->api_data($source, $event_id)->getResult();
			echo json_encode($res);
		}else{
			echo "error source!";
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
}
