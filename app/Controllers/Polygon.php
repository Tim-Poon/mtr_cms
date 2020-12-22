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
			$this->add_polygons($site, $floor);
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
		$site_polygons = $this->model->get_lastest_polygon($site, $floor);
		
		$site_item = $this->model_site->get_site_item($site, $floor)[0];
		$data = 
		[
			'icon' => 'fa-map-marker',
			'title' => 'Polygon',
			'sub_title' => '> ' . $site_item->site_name . ' ' . $site_item->floor_name,
			'site_names' => $this->model_site->get_site_names(),
			'site_all' => $this->model_site->get_site_all(),
			'site_polygons' => $site_polygons,
			'site_item' => $site_item,
			'mapbox_key' => config('ApiServer_')->mapbox['key'],
		];

		// // todo
		// // visit -> view_polygon = [1. load map by geojson(done); 2. load default polygon, darw polygon on view; 3. draw/revise polygon by mapbox;, 4. upload and reflash]

		echo view('head', $data);
		echo view('js');
		echo view('ajax/polygon', $data);
		echo view('foot');
	}

	private function add_polygons($site, $floor)
	{
		$raw_polygons = $this->request->getPost(['raw_polygons']);
		if ($raw_polygons['raw_polygons']['features']) {
			// get raw ploygons
			// geometry coordinates
			$poly = 1;
			$ts_create = $this->get_timestamp();
			foreach ($raw_polygons['raw_polygons']['features'] as $polygon_item) {
				if (count($polygon_item['geometry']['coordinates'][0]) == (4 + 1)) {
					$polygon_data = 
					[
						'site' => $site,
						'floor' => $floor,
						'poly' => $poly,
						'geojson' => str_replace('"', '', json_encode($polygon_item['geometry']['coordinates'])),
						'ts_create' => $ts_create
					];
					$this->model->set_polygons($polygon_data);
					$poly += 1;
				}
			}
		}else {
			echo 0;
		}
	}

	private function get_timestamp()
	{
	    $time = Time::now('Asia/Hong_Kong', 'en_US');
	    return $time->getTimestamp();
	}
}