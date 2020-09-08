<?php namespace App\Models;

use CodeIgniter\Model;

class NewsModel extends Model
{	
	function __construct()
    {
        parent::__construct();
    }

    public function getNews()
	{
        return $this->asArray()->first();
    }
    
    public function getNews2()
    {
        $builder = $this->db->table('heartbeat');
        $query   = $builder->get(10);
        return $query;
    }
}