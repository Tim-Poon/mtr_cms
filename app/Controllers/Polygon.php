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
		elseif($method === 'del')
		{
			$site = $params[0];
			$floor = $params[1];
			$ts_create = $params[2];
			$this->del_polygon($site, $floor, $ts_create);
		}
		elseif ($method === 'index')
		{
			return $this->index();
		}
		else
		{
			$site = $method;
			$floor = $params[0];
			$ts_create = $params[1];
			return $this->view_polygon($site, $floor, $ts_create);
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

			'site_polygons' => $this->model->get_lastest_polygon(1001, 1),
			'site_ts_create' => $this->model->get_ts_create($site, $floor),
			'mapbox_key' => config('ApiServer_')->mapbox['key'],
		];

		echo view('head', $data);
		echo view('js');
		echo view('ajax/polygon', $data);
		echo view('foot');
	}

	private function view_polygon($site, $floor, $ts_create)
	{
		if($ts_create){
			$site_polygons = $this->model->get_polygon($site, $floor, $ts_create);
		}else{
			$site_polygons = $this->model->get_lastest_polygon($site, $floor);
		}
		$site_item = $this->model_site->get_site_item($site, $floor)[0];
		$site_ts_create = $this->model->get_ts_create($site, $floor);

		$data = 
		[
			'icon' => 'fa-map-marker',
			'title' => 'Polygon',
			'sub_title' => '> ' . $site_item->site_name . ' ' . $site_item->floor_name,
			'site_names' => $this->model_site->get_site_names(),
			'site_all' => $this->model_site->get_site_all(),
			'site_polygons' => $site_polygons,
			'site_item' => $site_item,
			'site_ts_create' => $site_ts_create,
			'mapbox_key' => config('ApiServer_')->mapbox['key'],
		];

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

	private function del_polygon($site, $floor, $ts_create){
		$result = $this->model->del_polygon($site, $floor, $ts_create);
		$this->view_polygon($site, $floor, $ts_create);
	}

	private function get_timestamp()
	{
	    $time = Time::now('Asia/Hong_Kong', 'en_US');
	    return $time->getTimestamp();
	}
}