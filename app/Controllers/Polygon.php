<?php namespace App\Controllers;

use App\Models\PolygonModel;
use App\Models\MonitorModel;
use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;

class Polygon extends Controller
{
	public function __construct(){
		$this->model = new PolygonModel();
		$this->model_monitor = new MonitorModel();
	}

	public function _remap($method, ...$params)
	{
		if ($method === 'save')
		{
			$site = $params[0];
			$floor = $params[1];
			$this->save_polygons($site, $floor);
		}
		elseif($method === 'import')
		{
			$site = $params[0];
			$floor = $params[1];
			$this->import_polygons($site, $floor);
		}
		elseif($method === 'export')
		{
			$site = $params[0];
			$ts_create = $params[1];
			$this->export_polygons($site, $ts_create);
		}
		elseif($method === 'del')
		{
			$site = $params[0];
			$ts_create = $params[1];
			$this->del_polygon($site, $ts_create);
		}
		elseif ($method === 'index')
		{
			return $this->index();
		}
		else
		{
			$site_name = $method;
			$ts_create = $params[0];
			return $this->view_polygon($site_name, $ts_create);
		}
	}

	private function index()
	{	
		$site_info = $this->model_monitor->get_site_info_by_name($site_name);
		$data = 
		[
			'icon' => 'fa-map-marker',
			'title' => 'Polygon',
			'sub_title' => '',
			'site_names' => $this->model_monitor->get_site_name_all(),

			'site_info' => $site_info,
			'site_polygons' => $this->model->get_lastest_polygon(1001, 1),
			'site_ts_create' => $this->model->get_ts_create($site, $floor),
			'mapbox_key' => config('ApiServer_')->mapbox['key'],
		];

		echo view('head', $data);
		echo view('js');
		echo view('ajax/polygon', $data);
		echo view('foot');
	}

	private function view_polygon($site_name, $ts_create)
	{
		$site_info = $this->model_monitor->get_site_info_by_name($site_name);

		if($ts_create){
			// $site_polygons = $this->model->get_polygon($site, $site_info[0]['floor'], $ts_create);
			$site_polygons = $this->model->get_polygon($site_info[0]['site'], $ts_create);
		}else{
			// $site_polygons = $this->model->get_lastest_polygon($site, $floor);
			$site_polygons = 0;
		}
		$site_ts_create = $this->model->get_ts_create($site_info[0]['site']);

		$data = 
		[
			'icon' => 'fa-map-marker',
			'title' => 'Polygon',
			'sub_title' => ' > '. $site_info[0]['site_name'],
			'site_names' => $this->model_monitor->get_site_name_all(),

			'site_info' => $site_info,

			'site_polygons' => $site_polygons,
			'site_ts_create' => $site_ts_create,
			'mapbox_key' => config('ApiServer_')->mapbox['key'],
		];

		// print_r($site_info);
		echo view('head', $data);
		echo view('js');
		echo view('ajax/polygon', $data);
		echo view('foot');
	}

	private function import_polygons($site, $floor)
	{
		$import_polygons = $this->request->getPost(['import']);
		$raw_polygons = json_decode($import_polygons['import']);
		if($raw_polygons){
			$poly = 1;
			$ts_create = $this->get_timestamp();
			foreach($raw_polygons as $raw_polygon_item){
				if(count($raw_polygon_item) == 4){
					foreach($raw_polygon_item as $raw_polygon_item_coor){
						//conversion formula 
						//$raw_polygon_item_coor[0] == longitude
						//$raw_polygon_item_coor[1] == latitude
					}
					array_push($raw_polygon_item, $raw_polygon_item[0]);
					$polygon_data = 
					[
						'site' => $site,
						'floor' => $floor,
						'poly' => $poly,
						'geojson' => '['.json_encode($raw_polygon_item).']',
						'ts_create' => $ts_create,
						'flag' => 1
					];
					$this->model->set_polygons($polygon_data);
		 			$poly += 1;
					// print_r($polygon_data);				
				}else{
					echo 0;
				}				
			}
			echo $ts_create;
		}else{
			echo 0;
		}	
	}

	private function save_polygons($site, $floor)
	{
		$raw_polygons = $this->request->getPost(['raw_polygons']);
		if ($raw_polygons['raw_polygons']['features']) {
			// get raw ploygons
			// geometry coordinates
			$poly = 1;
			$ts_create = $this->get_timestamp();
			foreach ($raw_polygons['raw_polygons']['features'] as $polygon_item) {
				if (count($polygon_item['geometry']['coordinates'][0]) == (4 + 1)) {
					//todo vertex
					$polygon_data = 
					[
						'site' => $site,
						'floor' => $floor,
						'poly' => $poly,
						'geojson' => str_replace('"', '', json_encode($polygon_item['geometry']['coordinates'])),
						'vertex' => $this->xy2vertex(($polygon_item['geometry']['xy'][0]), $poly, $floor),
						'ts_create' => $ts_create,
						'flag' => 1
					];
					$this->model->set_polygons($polygon_data);
					$poly += 1;
				}
			}
			echo $ts_create;
		}else {
			echo 0;
		}	
	}

	private function xy2vertex($xy, $poly, $floor)
	{
		// 1: [[-58, 7, 0], [-58, 10, 0], [-51, 10, 0], [-51, 7, 0]]
		$vertex = [];
		foreach ($xy as $xy_item) {
			$vertex_item = [floatval(sprintf("%.1f", $xy_item[0])), floatval(sprintf("%.1f", $xy_item[1])), intval($floor)];
			array_push($vertex, $vertex_item);
		}
		return $poly.': '.json_encode($vertex);

	}

	private function del_polygon($site, $ts_create){
		$result = $this->model->del_polygon($site, $ts_create);
		$this->view_polygon($site, '');
	}

	private function export_polygons($site, $ts_create)
	{
		$polygons = $this->model->get_polygon($site, $ts_create);
		$vertex = '{';
		foreach ($polygons as $polygon_item) {
			$vertex = $vertex.$polygon_item->vertex.',';
		}
		$vertex = $vertex.'}';
		
		$file_name = 'Polygon_'.$site.'_'.$ts_create;
		// echo $vertex;

		header('Content-Type: application/vnd.ms-excel;charset=UTF-8');
		header('Content-Type: application/force-download');
		header('Content-Disposition: attachment;filename='.$file_name);
		$fp = fopen('php://output', 'w');
		fwrite($fp, json_encode($vertex));
		fclose($fp);
	}

	private function get_timestamp()
	{
	    $time = Time::now('Asia/Hong_Kong', 'en_US');
	    return $time->getTimestamp();
	}
}