<?php namespace App\Models;

use CodeIgniter\Model;

class UpdatingModel extends Model
{	
	function __construct()
    {
        parent::__construct();
    }

    // set new update task
    public function set_update_task($data)
    {
        // get all survey event
        $builder = $this->db->table('updating');
        // $builder->orderBy('id', 'DESC');
        $builder->insert($data);
        return 1;
    }

    public function get_updating_task_by_targer($ts_create)
    {
        $builder = $this->db->table('updating');
        $builder->where('ts_create', $ts_create);
        $query = $builder->get()->getResult('array');
        return $query;
    }

    public function get_updating_task()
    {
        $builder = $this->db->table('updating');
        $query = $builder->get()->getResult('array');
        return $query;
    }

    public function update_task_remark($ts_create, $data)
    {
        $builder = $this->db->table('updating');
        $builder->where('ts_create', $ts_create);
        $builder->update($data);
        return 1;
    }
}