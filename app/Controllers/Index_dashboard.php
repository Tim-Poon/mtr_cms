<?php namespace App\Controllers;

class Index_dashboard extends BaseController
{
	public function index()
	{
		$page_content = 'dashboard.html';
		$data = [
			'page_content'   => 'dashboard.html',
			'heading' => 'My Heading',
			'message' => 'My Message'
	];
		echo view('index', $data);
	}

	//--------------------------------------------------------------------

}
