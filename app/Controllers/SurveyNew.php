<?php namespace App\Controllers;

use App\Models\SurveyModel;
use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;

class SurveyNew extends Controller
{
	public function __construct()
    {
		// parent::__construct();
		$this->model = new SurveyModel();
	}

	public function index()
	{
        $time = Time::now('Asia/Hong_Kong', 'en_US');
        $data = array(
            'date' => $time->toLocalizedString('yyyy-MM-dd'),
            'ts'   => $time->getTimestamp(),
        );
		echo view('head');
		echo view('js');
		echo view('ajax/survey_new', $data);
		echo view('foot');
	}

	public function submit()
    {
        if($this->request->getPost()['e_id'] && $this->request->getPost()['date'] && $this->request->getPost()['site_name'])
        {
            $data = array(
                'event_id' => $this->request->getPost()['e_id'],
                'date' => $this->request->getPost()['date'],
                'site_name' => $this->request->getPost()['site_name'],
                'site_geo' => $this->request->getPost()['site_geo'],
                'remark' => $this->request->getPost()['remark'],
            );
            $this->model->add_event($data);
            return json_encode($data);
        } else{
            return '0';
        }
    }
}
