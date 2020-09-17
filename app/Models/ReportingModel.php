<?php namespace App\Models;

use CodeIgniter\Model;

class ReportingModel extends Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function get_related_offline_reporting_logs($id, $recent=20)
    {
        # todo
        # same level, same type, recently solved
        $row = $this->get_log($id);
        $related_level = $row->level;
        $related_src_type = $row->src_type;

        $query   = $this->db->query("SELECT id, ts, src_type, content, level, todo FROM daily_log WHERE src_type = '{$related_src_type}' and level = '{$related_level}' ORDER BY id DESC LIMIT {$recent}");
        $results = $query->getResult();
//         print_r($results);
        return $results;
    }

    public function get_log($id)
    {
        $query = $this->db->query("SELECT id, ts, src_type, content, level, todo, solve_message FROM daily_log WHERE id = {$id}");
        $rows = $query->getResult();
//         print_r($rows);
//         print_r($rows[0]);
        return $rows[0];
    }

}