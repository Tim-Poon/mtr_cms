<?php namespace App\Controllers;

use App\Models\PolygonModel;
use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;

class Polygon extends Controller
{
	public function __construct(){
	}

	private function check_valid_log_level($log_level){

	}

	private function check_valid_log_src_type($src_type){

	}

	public function index()
	{
		$data = [
		];

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
