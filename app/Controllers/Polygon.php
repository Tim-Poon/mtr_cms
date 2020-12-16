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
		if ($method === 'add')
		{
			$site = $params[0];
			$floor = $params[1];
			$this->add_polygon($site, $floor);
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
		$polygon = $this->model->get_polygon($site, $floor)[0];
		$site_item = $this->model_site->get_site_item($site, $floor)[0];
		$data = 
		[
			'icon' => 'fa-map-marker',
			'title' => 'Polygon',
			'sub_title' => '> ' . $site_item->site_name . ' ' . $site_item->floor_name,
			'site_names' => $this->model_site->get_site_names(),
			'site_all' => $this->model_site->get_site_all(),
			'polygon' => $polygon->geojson,
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

	private function add_polygon($site, $floor)
	{
		$geojson = $this->request->getPost(['data']);
		$data = 
		[
			'site' => $site,
			'floor' => $floor,
			'poly' => 1,
			'geojson' => json_encode($geojson)
		];
		print_r($data);
		$this->model->set_polygon($data);
	}

	public function get_time()
	{
	    $time = Time::now('Asia/Hong_Kong', 'en_US');
	    echo $time;
	}
}