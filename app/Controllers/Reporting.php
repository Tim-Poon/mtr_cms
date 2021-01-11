<?php namespace App\Controllers;

use App\Models\ReportingModel;
use App\Models\SiteModel;
use CodeIgniter\Controller;

class Reporting extends Controller
{
	public function __construct()
    {
		// parent::__construct();
		$this->model = new ReportingModel();
		$this->model_site = new SiteModel();
	}

	public function index()
	{
        // show reporting
        // todo: show reporting on calendar
        // todo: search reporting by date
        // todo: sort reporting (revice, del, insert delivery record)
        // todo: download reporting by date
		$data = 
		[
			'site_names' => $this->model_site->get_site_names(),
		];
        echo view('head', $data);
		echo view('js');
		echo view('ajax/reporting', $data);
	}
}
