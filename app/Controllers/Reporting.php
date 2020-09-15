<?php namespace App\Controllers;

// use App\Models\ReportingModel;

use CodeIgniter\Controller;

class Reporting extends Controller
{
    public function __construct()
    {
        // parent::__construct();
        
    }

    public function index()
    {
        $data = [
			'page_content'   => 'reporting/page',
			'heading' => 'My Heading',
			'message' => 'My Message'
		];
		echo view('index', $data);
    }

    public function page()
	{
		echo view('ajax/reporting');
	}
}