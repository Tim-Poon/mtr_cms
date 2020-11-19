<?php namespace App\Models;

use CodeIgniter\Model;

class PolygonModel extends Model
{	
    function __construct()
    {
        parent::__construct();
    }

    public function get_sites()
    {
        $builder = $this->db->table('site');
        $query = $builder->get();
        return $query;
    }

    public function get_polygon($site_id)
    {
        // get site polygon
        $builder = $this->db->table('polygon');
        $builder->where('site_id', $site_id);
        $query = $builder->get();
        return $query;
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
            $query   = $this->db->query('SELECT id, ts, src_type, content, level FROM daily_log WHERE todo=0');
            $results = $query->getResult();
            return $results;
        }
    }

}