<?php namespace App\Controllers;

use App\Models\NewsModel;
use CodeIgniter\Controller;

class News extends Controller
{
    public function __construct()
    {
        // parent::__construct();
        
    }

    public function index()
    {
        $model = new NewsModel();     
        $data = $model->getNews();
        echo json_encode($data);
    }

    public function view()
    {
        // $model = new NewsModel();

        // $data = $model->getNews();

        // echo $data;
    }
}