<?php namespace App\Controllers;

use App\Models\ReportingModel;
use App\Models\MonitorModel;
use CodeIgniter\Controller;

class Reporting extends Controller
{
	public function __construct()
    {
		// parent::__construct();
		$this->model = new ReportingModel();
		$this->model_monitor = new MonitorModel();
	}

	public function index()
	{
        $site_info = $this->model_monitor->get_site_info_by_name($site_name);
		$data = 
		[
			'icon' => 'fa-file-text',
			'title' => 'Reporting',
			'sub_title' => '',
			'site_names' => $this->model_monitor->get_site_name_all(),
		];

		echo view('head', $data);
		echo view('js');
		echo view('ajax/reporting', $data);
		echo view('foot');
	}
}
