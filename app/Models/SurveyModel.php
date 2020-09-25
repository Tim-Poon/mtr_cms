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
}