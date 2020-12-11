<?php namespace App\Models;

use CodeIgniter\Model;

class SiteModel extends Model
{	
    function __construct()
    {
        parent::__construct();
    }

    public function get_site_item_by_name($site_name)
    {
        $builder = $this->db->table('site');
        $builder->where('site_name', $site_name);
        $query = $builder->get();
        return $query->getResult();
    }

    public function get_site_all()
    {
        $builder = $this->db->table('site');
        $query = $builder->get();
        return $query->getResult();
    }

    public function get_geojson($site, $floor)
    {
        // get site geojson data
        $builder = $this->db->table('site');
        $builder->where('site', $site);
        $builder->where('floor', $floor);
        $query = $builder->get();
        return $query->getResult()[0]->geojson;
    }

}