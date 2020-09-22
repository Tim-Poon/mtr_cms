<?php namespace App\Controllers;

use App\Models\SurveyModel;
use CodeIgniter\Controller;

class Survey extends Controller
{
	public function __construct()
    {
		// parent::__construct();
		$this->$model = new SurveyModel();
	}

	public function index()
	{
		// record each survey
		// todo: build survey by date
		// todo: show survey data (include raw & groundture)
		// todo: online simply analysis
		// todo: download servey data
        $data = [];
        echo view('head', $data);
		echo view('js');
		echo view('ajax/survey', $data);
	}
}
