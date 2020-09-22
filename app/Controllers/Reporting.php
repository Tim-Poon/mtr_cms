<?php namespace App\Controllers;

use App\Models\ReportingModel;
use CodeIgniter\Controller;

class Reporting extends Controller
{
	public function __construct()
    {
		// parent::__construct();
		$this->$model = new ReportingModel();
	}

	public function index()
	{
        // show reporting
        // todo: show reporting on calendar
        // todo: search reporting by date
        // todo: sort reporting (revice, del, insert delivery record)
        // todo: download reporting by date
        $data = [];
        echo view('head', $data);
		echo view('js');
		echo view('ajax/reporting', $data);
	}
}
