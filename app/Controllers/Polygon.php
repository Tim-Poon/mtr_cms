<?php namespace App\Controllers;

use App\Models\PolygonModel;
use App\Models\SiteModel;
use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;

class Polygon extends Controller
{
	public function __construct(){
		$this->model = new PolygonModel();
		$this->model_site = new SiteModel();
	}

	public function _remap($method, ...$params)
	{
		if ($method === 'default')
		{
			
		}
		elseif ($method === 'index')
		{
			return $this->index();
		}else
		{
			$site = $method;
			$floor = $params[0];
			return $this->view_polygon($site, $floor);
		}
	}

	private function index()
	{
		$data = 
		[
			'icon' => 'fa-map-marker',
			'title' => 'Polygon',
			'sub_title' => '',
			'site_names' => $this->model_site->get_site_names(),
			'site_all' => $this->model_site->get_site_all(),
		];

		echo view('head', $data);
		echo view('js');
		echo view('ajax/polygon', $data);
		echo view('foot');
	}

	private function view_polygon($site, $floor)
	{
		$polygon = $this->model->get_polygon($site, $floor);
		$site_item = $this->model_site->get_site_item($site, $floor)[0];
		$data = 
		[
			'icon' => 'fa-map-marker',
			'title' => 'Polygon',
			'sub_title' => '> ' . $site_item->site_name . ' ' . $site_item->floor_name,
			'site_names' => $this->model_site->get_site_names(),
			'site_all' => $this->model_site->get_site_all(),
			'polygon' => $polygon,
			'geojson' => $site_item->geojson,
			'mapbox_key' => config('ApiServer_')->mapbox['key']
		];

		// todo
		// visit -> view_polygon = [1. load map by geojson(done); 2. load default polygon, darw polygon on view; 3. draw/revise polygon by mapbox;, 4. upload and reflash]

		echo view('head', $data);
		echo view('js');
		echo view('ajax/polygon', $data);
		echo view('foot');
	}

	public function get_time()
	{
	    $time = Time::now('Asia/Hong_Kong', 'en_US');
	    echo $time;
	}
}