<?php namespace App\Models;

use CodeIgniter\Model;

class NewsModel extends Model
{	
	function __construct()
    {
        // parent::__construct();
        //创建数据库连接
        // $this->Db = \Config\Database::connect();
    }
    protected $table = 'heartbeat';

    public function getNews()
	{
        return $this->asArray()
                     ->first();
	}
}