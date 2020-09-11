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
        $data = $model->getNews2();
        foreach ($data->getResult() as $row)
        {
            echo $row->id;
            echo $row->vm;
        }
    }

    public function try1()
    {
        $userModel = new \App\Models\LocOnlineModel;
        $users = $userModel->find([123,124,125]);
        // print_r($users);
    }

    public function view()
    {
    }
}