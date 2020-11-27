<?php namespace App\Models;

use CodeIgniter\Model;

class SitesModel extends Model
{	
    function __construct()
    {
        parent::__construct();
    }

    public function get_geo_json($site_id, $floor)
    {
        // get site geojson data
        $builder = $this->db->table('site');
        $builder->where('site_id', $site_id);
        $builder->where('floor', $floor);
        $query = $builder->get();
        return $query->getResult()[0]->geo_json;
    }

}