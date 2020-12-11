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

	private function check_valid_log_level($log_level){

	}

	private function check_valid_log_src_type($src_type){

	}

	public function index()
	{
		$sites = $this->model->get_sites()->getResult();
		$data = 
		[
			'icon' => 'fa-map-marker',
			'title' => 'Polygon',
			'sub_title' => '',
			'site_all' => $this->model_site->get_site_all(),
			'sites' => $sites,
		];

		echo view('head', $data);
		echo view('js');
		echo view('ajax/polygon', $data);
		echo view('foot');
	}


	public function site($site_id)
	{
		$sites = $this->model->get_sites()->getResult();
		$polygon = $this->model->get_polygon(1001)->getResult();
		$geo_json = $this->model->get_geojson(1001, 1);
		$data = 
		[
			'icon' => 'fa-map-marker',
			'title' => 'Polygon',
			'sub_title' => '',
			'site_all' => $this->model_site->get_site_all(),
			'sites' => $sites,
			'polygon' => $polygon,
			'geo_json' => $geo_json,
			'mapbox_key' => config('ApiServer_')->mapbox['key']
		];
		echo view('head', $data);
		echo view('js');
		echo view('ajax/polygon', $data);
		echo view('foot');
	}

	// public function _remap($method, ...$params)
	// {
	// 	if ($method === 'site')
	// 	{
	// 		$this->get_site();
	// 	}
	// 	elseif ($method === 'index')
	// 	{
	// 		return $this->index();
	// 	}else
	// 	{
	// 		// return $this->index($params);
	// 		// return redirect()->to('/'); 
	// 		echo 'not page ' . $method . '<br>';
	// 		print_r($params);
	// 	}
	// }

	public function get_time()
	{
	    $time = Time::now('Asia/Hong_Kong', 'en_US');
	    echo $time;
	}
}