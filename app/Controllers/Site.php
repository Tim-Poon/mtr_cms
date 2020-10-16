<?php namespace App\Controllers;

use App\Models\SitesModel;
use CodeIgniter\Controller;

class Site extends Controller
{
    public function __construct()
    {
        // parent::__construct();
        $this->model = new SitesModel();     
        
    }

    function _remap($method, $params) {
        echo $method;
        print_r($params);
        // if ($param == 'abc'){
        //     $this->abc($param);
        // }else{
        //     $this->index($param);
        // }
    }

    public function index($a)
    {
        // todo: show site's beacon table with vm
        // todo: show site's polygon with vm
        // todo: show site's configuration with vm
        echo $a;
        // $data = [];
        // echo view('head', $data);
		// echo view('js');
		// echo view('ajax/sites', $data);
		// echo view('foot');
    }

    public function abc($w)
    {
        # code...
        echo $w;
    }
}