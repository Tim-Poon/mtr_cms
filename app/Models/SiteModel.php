<?php namespace App\Models;

use CodeIgniter\Model;

class SiteModel extends Model
{	
    function __construct()
    {
        parent::__construct();
    }

    public function get_site_info_all()
    {
        // get all sensor
        $builder = $this->db->table('site');
        $query = $builder->get();
        return $query;
    }

    public function get_sensor_info_all()
    {
        // get all sensor
        $builder = $this->db->table('sensor');
        $query = $builder->get();
        return $query;
    }

    public function get_site_sensors($site)
    {
        $builder = $this->db->table('sensor');
        $builder->where('site', $site);
        $builder->orderBy('label', 'ASC');
        $query = $builder->get();
        return $query->getResult();
    }

    public function get_site_beacons($site, $floor)
    {
        $builder = $this->db->table('beacon');
        $builder->where('major', $site.$floor);
        $query = $builder->get();
        return $query->getResult();
    }
    public function get_site_geojson($site, $floor)
    {
        // get site geojson data
        $builder = $this->db->table('site');
        $builder->where('site', $site);
        $builder->where('floor', $floor);
        $query = $builder->get();
        return $query->getResult()[0]->geojson;
    }

}