<?php namespace App\Controllers;

use App\Models\SurveyModel;
use CodeIgniter\Controller;

class Survey extends Controller
{
	public function __construct()
    {
		// parent::__construct();
		$this->model = new SurveyModel();
		$this->source = array('survey_beacon', 'survey_wifi', 'survey_imu', 'survey_uwb_loc', 'survey_uwb_dist');
	}

	public function index()
	{
		$events = $this->model->get_event()->getResult();
		// todo: online simply analysis
        $data = ['events' => $events];
        echo view('head', $data);
		echo view('js');
		$this->set_data();
		echo view('foot');
	}

	public function update()
	{
		if($this->request->getPost()['e_id'] && $this->request->getPost()['date'] && $this->request->getPost()['site_name']){
			$event_id = $this->request->getPost()['e_id'];
			$data = array(
                'date' => $this->request->getPost()['date'],
                'site_name' => $this->request->getPost()['site_name'],
                'site_geo' => $this->request->getPost()['site_geo'],
                'remark' => $this->request->getPost()['remark'],
            );
			$this->model->update_event($event_id, $data);
			$submit_msg = "Update Event # $event_id";
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