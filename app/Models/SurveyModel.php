<?php namespace App\Models;

use CodeIgniter\Model;

class SurveyModel extends Model
{	
	function __construct()
    {
        parent::__construct();
    }

    public function get_event()
    {
        // get survey event
        $builder = $this->db->table('survey_event');
        $builder->orderBy('id', 'DESC');
        $query = $builder->get();
        return $query;
    }

    public function api_data($source, $event_id)
    {
        $builder = $this->db->table($source);
        $builder->where('event_id', $event_id);
        $query = $builder->get();
        return $query;
    }

    public function get_event_by_id($event_id)
    {
        // get survey event
        $builder = $this->db->table('survey_event');
        $builder->where('event_id', $event_id);
        $query = $builder->get();
        return $query;
    }

    public function get_beacon_by_id($event_id)
    {
        // get survey event
        $builder = $this->db->table('survey_beacon');
        $builder->where('event_id', $event_id);
        $query = $builder->get();
        return $query;
    }

    public function get_wifi_by_id($event_id)
    {
        // get survey event
        $builder = $this->db->table('survey_wifi');
        $builder->where('event_id', $event_id);
        $query = $builder->get();
        return $query;
    }

    public function get_imu_by_id($event_id)
    {
        // get survey event
        $builder = $this->db->table('survey_imu');
        $builder->where('event_id', $event_id);
        $query = $builder->get();
        return $query;
    }

    public function get_uwb_loc_by_id($event_id)
    {
        // get survey event
        $builder = $this->db->table('survey_uwb_loc');
        $builder->where('event_id', $event_id);
        $query = $builder->get();
        return $query;
    }

    public function get_uwb_dist_by_id($event_id)
    {
        // get survey event
        $builder = $this->db->table('survey_uwb_dist');
        $builder->where('event_id', $event_id);
        $query = $builder->get();
        return $query;
    }

    public function add_event($data)
    {
        // add new survey event
        $builder = $this->db->table('survey_event');
        $builder->insert($data);
        return 1;
    }
}