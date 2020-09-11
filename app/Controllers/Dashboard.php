<?php namespace App\Controllers;

class Dashboard extends BaseController
{
	public function index()
	{
		$page_content = 'dashboard.html';
		$data = [
			'page_content'   => 'dashboard.html',
			'heading' => 'My Heading',
			'message' => 'My Message'
	];
		echo view('ajax/dashboard', $data);
	}

	//--------------------------------------------------------------------

}
