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
        echo view('head');
        echo view('js');
        $data = [];
        if($this->request->getGet()['stype'])
        {
            // todo: get seq data from model
            $tmp_data = $this->get_todos($this->request->getGet());
            

            $data = [
                'stype' => $this->request->getGet()['stype'],
                'seq'   => $this->request->getGet()['seq'],
                'heading' => 'My Heading',
                'message' => 'My Message'
            ];
        }
        echo view('ajax/reporting', $data);
        echo view('foot');
        
    }

    protected function get_todos($tmp)
    {
        // todo: get seq data from model
        return 1;
    }

    public function submit()
    {
        if($this->request->getPost()['message'])
        {
            $data['msg'] = $this->request->getPost()['message'];
            echo view('submit', $data);
        }
    }
}