<?php namespace App\Models;

use CodeIgniter\Model;

class PolygonModel extends Model
{	
    function __construct()
    {
        parent::__construct();
    }

    public function set_polygons($data)
    {
        $builder = $this->db->table('polygon');
        $builder->insert($data);
    }

    public function get_lastest_polygon($site, $floor)
    {
        // get site polygon
        $builder = $this->db->table('polygon');
        $builder->selectMax('ts_create');
        $max_ts_create = $builder->get()->getResult()[0]->ts_create;

        $builder->where('site', $site);
        $builder->where('floor', $floor);
        $builder->where('ts_create', $max_ts_create);
        $query = $builder->get();
        return $query->getResult();
    }

}