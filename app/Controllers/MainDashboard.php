<?php namespace App\Controllers;

use App\Models\MainDashboardModel;
use CodeIgniter\Controller;

class MainDashboard extends Controller
{
	public function __construct()
    {
		// parent::__construct();
		$this->$model = new MainDashboardModel();
    }
	public function index()
	{
		$page_content = 'dashboard.html';
		$data = [
			'page_content'   => 'dashboard.html',
			'heading' => 'My Heading',
			'message' => 'My Message'
		];
		
		
		$res = $this->$model->get_loc_online_data(9);
		// foreach ($res->getResult() as $row)
		// {
		// 	echo $row->id.'</br>';
		// }
		echo view('index', $data);
	}

	//--------------------------------------------------------------------

}
