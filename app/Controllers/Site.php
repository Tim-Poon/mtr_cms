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

    // function _remap($method, $params) {
    //     echo $method;
    //     print_r($params);
    //     // if ($param == 'abc'){
    //     //     $this->abc($param);
    //     // }else{
    //     //     $this->index($param);
    //     // }
    // }

    public function index()
    {
        // todo: show site's beacon table with vm
        // todo: show site's polygon with vm
        // todo: show site's configuration with vm
        $data = ['icon' => 'fa-truck',
        'title' => 'Site',
        'sub_title' => '',
        'geo_json' => $this->model->get_geo_json(1001,1),
        'mapbox_key' => config('ApiServer_')->mapbox['key']];
        echo view('head', $data);
		echo view('js');
		echo view('ajax/sites', $data);
		echo view('foot');
    }
}