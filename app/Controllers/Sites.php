<?php namespace App\Controllers;

use App\Models\SitesModel;
use CodeIgniter\Controller;

class Sites extends Controller
{
    public function __construct()
    {
        // parent::__construct();
        $this->model = new SitesModel();     
    }

    public function index()
    {
        // todo: show site's beacon table with vm
        // todo: show site's polygon with vm
        // todo: show site's configuration with vm

        $data = [];
        echo view('head', $data);
		echo view('js');
		echo view('ajax/sites', $data);
		echo view('foot');
    }
}