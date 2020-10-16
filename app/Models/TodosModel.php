<?php namespace App\Models;

use CodeIgniter\Model;

class TodosModel extends Model
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

    public function get_related_sensor_logs($id, $recent=20)
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

    public function get_related_api_server_logs($id, $recent=20)
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

    public function get_related_reporting_server_logs($id, $recent=20)
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

    public function get_todos($minutes)
    {
        if($minutes <= 100)
        {
            // get lastest $minutes data
            $query   = $this->db->query('SELECT id, ts, src_type, content, level FROM daily_log WHERE todo=0');
            $results = $query->getResult();
            return $results;
        }
    }

    public function update_todos($id, $msg)
    {
        $data = ['solve_message' => $msg, 'todo' => 1];
        $builder = $this->db->table('daily_log');
        $builder->where('id', $id);
        $builder->update($data);
//         try:
  #      $query = $this->db->query("UPDATE msg FROM daily_log WHERE id = {$id}");  // todo
  #      $results = $query->getResult();
//         except:
//             return 0;
        return 1;
    }

}