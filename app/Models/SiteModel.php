<?php namespace App\Models;

use CodeIgniter\Model;

class SiteModel extends Model
{	
    function __construct()
    {
        parent::__construct();
    }

    public function get_sensor_info()
    {
        // get all sensor
        $builder = $this->db->table('sensor');
        $query = $builder->get();
        return $query;
    }

    public function get_site_info()
    {
        // get all sensor
        $builder = $this->db->table('site');
        $builder->select('site, site_name');
        $query = $builder->get();
        return $query;
    }

    public function get_site_sensor($site)
    {
        $builder = $this->db->table('sensor');
        $builder->where('site', $site);
        $builder->orderBy('id', 'DESC');
        $query = $builder->get();
        return $query->getResult();
    }

    public function get_site_beacon($site, $floor)
    {
        $builder = $this->db->table('beacon');
        $builder->where('major', $site.$floor);
        $query = $builder->get();
        return $query->getResult();
    }

    public function get_site_item($site, $floor)
    {
        $builder = $this->db->table('site');
        $builder->where('site', $site);
        $builder->where('floor', $floor);
        $query = $builder->get();
        return $query->getResult();
    }

    public function get_site_all()
    {
        $builder = $this->db->table('site');
        $query = $builder->get();
        return $query->getResult();
    }

    public function get_site_names()
    {
        $builder = $this->db->table('site');
        $query = $builder->get();
        $site_names = array();
        foreach ($query->getResult() as $site_item) {
            if (!in_array($site_item->site_name, $site_names))
            {
                array_push($site_names, $site_item->site_name);
            }
        }
        return $site_names;
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