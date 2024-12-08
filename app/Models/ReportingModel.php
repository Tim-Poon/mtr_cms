<?php namespace App\Models;

use CodeIgniter\Model;

class ReportingModel extends Model
{   
    function __construct()
    {
        parent::__construct();
    }

    public function get_report($delivery_date)
    {
        // get all delivery_date
        $builder = $this->db->table('reporting');
        $builder->where('delivery_date', $delivery_date);
        $query = $builder->get()->getResult('array');
        return $query;
    }

    public function get_reporing_all_date()
    {
        // get all delivery_date
        $builder = $this->db->table('reporting');
        $builder->select('delivery_date');
        $builder->groupBy('delivery_date');
        $query = $builder->get()->getResult('array');
        return $query;
    }
    
    public function get_report_by_id($report_id)
    {
        // get all delivery_date
        $builder = $this->db->table('reporting');
        $builder->where('id', $report_id);
        $query = $builder->get()->getResult('array');
        return $query;
    }

    public function get_delivery_trajectory($sensor, $s_ts, $e_ts)
    {
        // get all delivery_date
        $builder = $this->db->table('raw_loc_data');
        $builder->where('ts >', $s_ts);
        $builder->where('ts <', $e_ts);
        $builder->where('sensor', $sensor);
        $query = $builder->get()->getResult('array');
        return $query;
    }

    public function get_sensor_mac($site_name, $label)
    {
        $builder = $this->db->table('site');
        $builder->select('site, site_name');
        $builder->where('site_name', $site_name);
        $query = $builder->get()->getResult('array');

        $site = $query[0]['site'];

        // get all delivery_date
        $builder = $this->db->table('sensor');
        $builder->where('site', $site);
        $builder->where('label', $label);
        $query = $builder->get()->getResult('array');
        return $query[0]['sensor'];
    }

    public function update_remark($repord_id, $data)
    {
        // update survey event
        $builder = $this->db->table('reporting');
        $builder->where('id', $repord_id);
        $builder->update($data);
        return 1;
    }
}