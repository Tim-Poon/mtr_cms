<?php namespace App\Controllers;

// use App\Models\ReportingModel;

use CodeIgniter\Controller;

class Reporting extends Controller
{
    public function __construct()
    {
        // parent::__construct();
        $this->view_data = [
            'page_content'   => 'reporting/page',
			'heading' => 'My Heading',
			'message' => 'My Message'
        ];
        $this->request = \Config\Services::request();
    }

    public function index()
    {
        $data = [];
        if($this->request->getGet()['seq'])
        {
            $data = [
                'page_content'   => 'reporting/page',
                'heading' => 'My Heading',
                'message' => 'My Message'
            ];
        }
        echo view('head', $data);
        echo view('js');
        echo view('ajax/reporting', $data);
        echo view('foot');
        
    }

    public function id($id)
    {
        $this->view_data['ppp'] = $id;
        $pages = view('head', $this->view_data).view('ajax/reporting', $this->view_data).view('foot');
        echo $pages;
    }
}