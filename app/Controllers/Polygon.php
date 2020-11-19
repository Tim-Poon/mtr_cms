<?php namespace App\Controllers;

use App\Models\PolygonModel;
use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;

class Polygon extends Controller
{
	public function __construct(){
		$this->model = new PolygonModel();
	}

	private function check_valid_log_level($log_level){

	}

	private function check_valid_log_src_type($src_type){

	}

	public function index()
	{
		$sites = $this->model->get_sites()->getResult();
		$data = [
			'sites' => $sites,
		];

		echo view('head', $data);
		echo view('js');
		echo view('ajax/polygon', $data);
		echo view('foot');
	}


	public function site($site_id)
	{
		$polygon = $this->model->get_polygon($sites)->getResult();
		$data = [
			'polygon' => $polygon,
		];

		echo view('head', $data);
		echo view('js');
		echo view('ajax/polygon', $data);
		echo view('foot');
	}

	public function _remap($method, ...$params)
	{
		if ($method === 'get_site')
		{
			$this->get_site();
		}
		elseif ($method === 'index')
		{
			return $this->index();
		}else
		{
			// return $this->index($params);
			// return redirect()->to('/'); 
			echo 'not page ' . $method . '<br>';
			print_r($params);
		}
	}

	public function get_time()
	{
	    $time = Time::now('Asia/Hong_Kong', 'en_US');
	    echo $time;
	}
}
