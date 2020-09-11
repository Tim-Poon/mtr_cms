<?php namespace App\Models;

use CodeIgniter\Model;

class MainDashboardModel extends Model
{	
    function __construct()
    {
        parent::__construct();
    }

    public function get_loc_online_data($minutes)
    {
        if($minutes <= 10)
        {
            // get lastest $minutes data
            $builder = $this->db->table('loc_online_data');
            $builder->orderBy('id', 'DESC');
            $query = $builder->get(10);
            return $query;
        }
    }
}