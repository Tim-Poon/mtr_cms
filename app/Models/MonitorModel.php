<?php namespace App\Models;

use CodeIgniter\Model;

class MonitorModel extends Model
{	
    function __construct()
    {
        parent::__construct();
    }

    public function get_site_info_all()
    {
        // get all sensor
        $builder = $this->db->table('site');
        $builder->orderBy('floor, site', 'ASC');
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

    public function get_site_beacons($site)
    {
        $builder = $this->db->table('site');
        $builder->select('floor');
        $builder->where('site', $site);
        $floors = $builder->get()->getResult();

        $builder = $this->db->table('beacon');
        foreach ($floors as $floor) {
            $builder->orWhere('major', $site.$floor->floor);
        }
        $query = $builder->get();
        return $query->getResult();
    }

    // public function get_site_geojson($site, $floor)
    // {
    //     // get site geojson data
    //     $builder = $this->db->table('site');
    //     $builder->where('site', $site);
    //     $builder->where('floor', $floor);
    //     $query = $builder->get();
    //     return $query->getResult()[0]->geojson;
    // }

    public function get_site_geojson($site)
    {
        // get site geojson data
        $builder = $this->db->table('site');
        $builder->where('site', $site);
        $query = $builder->get();
        $builder->orderBy('floor', 'ASC');
        return $query->getResult();
    }

    // public function get_site_dot_mapping($site)
    // {
    //     // get site geojson data
    //     $builder = $this->db->table('site');
    //     $builder->where('site', $site);
    //     $query = $builder->get();
    //     $builder->orderBy('floor', 'ASC');
    //     return $query->getResult();
    // }

}