<?php namespace App\Controllers;

use App\Models\HomeModel;
use CodeIgniter\Controller;

class Home extends Controller
{
	public function __construct()
    {
		// parent::__construct();
		$this->$model = new HomeModel();
		$this->valid_log_level = array('info', 'warning', 'error');
		$this->valid_src_type = array('sensor', 'server', 'report');
	}

	public function index()
	{
		$data = [
			'page_content'   => 'dashboard/page',
			'heading' => 'My Heading',
			'message' => 'My Message'
		];
		
		
		// print_r($this->real_time_sensor_status());
		#foreach ($res->getResult() as $row)
		#{
		#	echo $row->id.'</br>';
		#}
		echo view('index', $data);
	}


}
