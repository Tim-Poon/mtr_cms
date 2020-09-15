<?php namespace App\Models;

use CodeIgniter\Model;

class DashboardModel extends Model
{	
    function __construct()
    {
        parent::__construct();
    }

    public function get_raw_loc_data($minutes)
    {
        if($minutes <= 100)
        {
            // get lastest $minutes data
            $builder = $this->db->table('raw_loc_data');
            $builder->orderBy('id', 'DESC');
            $query = $builder->get($minutes);
            return $query;
        }
    }

    public function get_raw_beacon_data($minutes)
    {
        if($minutes <= 100)
        {
            // get lastest $minutes data
            $builder = $this->db->table('raw_beacon_data');
            $builder->orderBy('id', 'DESC');
            $query = $builder->get($minutes);
            return $query;
        }
    }

    public function get_logs($minutes)
    {
        if($minutes <= 100)
        {
            // get lastest $minutes data
            $builder = $this->db->table('daily_log');
            $builder->orderBy('ts', 'DESC');
            $query = $builder->get($minutes);
            return $query;
        }
    }

    public function get_todos($minutes)
    {
        if($minutes <= 100)
        {
            // get lastest $minutes data
            $builder = $this->db->table('daily_log');
            $builder->orderBy('ts', 'DESC');
            $query = $builder->get($minutes);
            return $query;
        }
    }

}