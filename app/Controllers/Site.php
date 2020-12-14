<?php namespace App\Controllers;

use App\Models\SiteModel;
use CodeIgniter\Controller;

class Site extends Controller
{
    public function __construct()
    {
        // parent::__construct();
        $this->model = new SiteModel();     
        
    }

    public function _remap($method, ...$params)
	{
		if ($method == 'event')
		{
			return $this->event($params);
		}elseif ($method == 'new_event')
		{
			return $this->new_event();
		}elseif ($method == 'add_event')
		{
			$this->add_event();
		}elseif ($method == 'index')
		{
			return $this->index();
		}else
		{
            $site_name = $method;
			return $this->monitor($site_name);
		}
    }

    public function index()
    {
        // todo: show site's beacon table with vm
        // todo: show site's polygon with vm
        // todo: show site's configuration with vm
        $data = 
        [  
            'icon' => 'fa-truck',
            'title' => 'Site',
            'sub_title' => '',
            'site_names' => $this->model->get_site_names(),
            'geojson' => $this->model->get_geojson(1001, 1),
            'mapbox_key' => config('ApiServer_')->mapbox['key'],

        ];
        echo view('head', $data);
		echo view('js');
		echo view('ajax/site', $data);
		echo view('foot');
    }

    public function monitor($site_name)
    {
        $data = 
        [  
            'icon' => 'fa-truck',
            'title' => 'Site',
            'sub_title' => ' > '. $site_name,
            'site_names' => $this->model->get_site_names(),
            'site_all' => $this->model->get_site_all(),
            'geojson' => $this->model->get_geojson(1001, 1),
            'mapbox_key' => config('ApiServer_')->mapbox['key'],
            'beacons' => $this->model->get_site_beacon(1001, 1),
        ];
        echo view('head', $data);
		echo view('js');
		echo view('ajax/site', $data);
        echo view('foot');
    }
}