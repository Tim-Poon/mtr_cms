<?php namespace App\Controllers;

use App\Models\MaintenanceModel;
use App\Models\MonitorModel;
use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;

class Maintenance extends Controller
{
    public function __construct()
    {
        // parent::__construct();
        $this->model = new MaintenanceModel();
        $this->model_monitor = new MonitorModel();
        $this->request = \Config\Services::request();
    }

    public function _remap($method, ...$params)
	{
		if ($method == 'todos')
		{
            $src_type = $params[0];
            $todo_id = $params[1];
			return $this->todos($src_type, $todo_id);
		}elseif ($method == 'update_todos')
		{
			return $this->update_todos();
		}elseif ($method == 'add_event')
		{
			$this->add_event();
		}elseif ($method == 'index')
		{
			return $this->index();
		}else
		{
            $site_name = $method;
			return $this->monitor($site_name);
		}
    }

    public function index()
    {
        $data = 
        [
            'icon' => 'fa-check-circle-o',
            'title' => 'Todos',
            'sub_title' => '',
            'site_names' => $this->model_monitor->get_site_name_all(),

            'tabletodos' => $this->tabletodos(),
        ];

        # echo todos page head
        echo view('head', $data);
        echo view('js');
        echo view('ajax/todos', $data);
        echo view('foot');
    }

    public function todos($src_type, $todo_id)
    {
        $todo = $this->model->get_log($todo_id);
        // $related_logs = $this->related_logs($src_type, $id);
        $time = Time::createFromTimestamp($log->ts / 1000, 'Asia/Shanghai', 'en_US');
        $seq = $this->retrieve_process_seq($log);

        $todos_item = 
        [

        ];

        $data = 
        [
            'icon' => 'fa-check-circle-o',
            'title' => 'Todos',
            'sub_title' => ' > '.$src_type.' #'.$todo_id,
            'site_names' => $this->model_monitor->get_site_name_all(),

            'todos_item' => $todo,
            // 'stype' => $param_from_url['stype'],
            // 'id'   => $param_from_url['id'],
            // 'seq' => $seq,
            // 'content' => $log->content,
            // 'date' => "{$time->getYear()}-{$time->getMonth()}-{$time->getDay()}-{$time->getHour()}-{$time->getMinute()}",
            // // 'related_logs' => $related_logs,
            // 'heading' => 'My Heading',
            // 'solve_message' => $log->solve_message,
            // 'todo' => $log->todo
        ];
        // echo view('ajax/solve_todos', $data);
        echo view('head', $data);
        echo view('js');
        echo view('ajax/todos_solve', $data);
        // print_r($todo);
        echo view('foot');
    }

    protected function related_logs($stype, $id)
    {
        // related logs are logs that may help you figure out how to solve current logs
        if($stype == 'offline_reporting'){
            return $this->model->get_related_offline_reporting_logs($id);
        }
        if($stype == 'api_server'){
            return $this->model->get_related_api_server_logs($id);
        }
        if($stype == 'reporting_server'){
            return $this->model->get_related_reporting_server_logs($id);
        }
        if($stype == 'sensor'){
            return $this->model->get_related_sensor_logs($id);
        }
        return $this->model->get_related_offline_reporting_logs($id);
    }

    protected function retrieve_process_seq($log)
    {
        # todo
        $content = $log->content;
        return 1;
    }

    public function update_todos()
    {
        $data['solve_msg'] = $this->request->getPost()['solve_msg'];
        if($data['solve_msg'])
        {
            $data['id'] = $this->request->getPost()['id'];
            $this->model->update_todos($data['id'], $data['solve_msg']);
            // echo view('submit', $data);
            // echo 1;
            
        }
    }

    public function tabletodos()
	{
	        $res_err = "<h5 class=\"todo-group-title\"><i class=\"fa fa-exclamation\"></i> Error</h5><ul id=\"sortable1\" class=\"todo\">";
	        $res_warning = "<h5 class=\"todo-group-title\"><i class=\"fa fa-warning\"></i> Warning</h5><ul id=\"sortable1\" class=\"todo\">";
	        $res_info = "<h5 class=\"todo-group-title\"><i class=\"fa fa-check\"></i> Info</h5><ul id=\"sortable1\" class=\"todo\">";

            $todos = $this->model->get_todos(30);

            foreach($todos as $todo){
                $todo_status['title'] = $todo->content;
                $todo_status['allDay'] = true;
                $time = Time::createFromTimestamp($todo->ts / 1000, 'Asia/Shanghai', 'en_US');
                $todo_date = "{$time->getYear()}-{$time->getMonth()}-{$time->getDay()}";
                $todo_status['className'] = $this->loglevel2todo_mapping[$todo->level];


                $id = $todo->id; //todo
                $todo_status['ts'] = $time;
                $src_type = $todo->src_type;
                $content = $todo->content;
				$level = $todo->level;

				// link to reporting page
                $url = "maintenance/todos/$src_type"."/".$todo->id;

                $view_content = "<li>
                                    <span class=\"handle\"></span>
                                    <p>
                                        <a href=\"{$url}\"><strong style=\"color:#935116\">{$src_type} #{$id}</strong> <strong style=\"color:#707b7c\">- {$content}</strong> </a>[<a href=\"{$url}\" class=\"font-xs\">SLOVED</a>]
                                        <span class=\"text-muted\">I don't know what to place in here</span>
                                        <span class=\"date\">{$todo_date}</span>
                                    </p>
                                </li>";

                                // <a href="survey" style="color:#696969; cursor:pointer"><strong>Survey</strong></a> 
                if ($level == 'error')
                {
                    $res_err = $res_err . $view_content;
                }
                elseif ($level == 'warning')
                {
                    $res_warning = $res_warning . $view_content;
                }
                else
                {
                    $res_info = $res_info . $view_content;
                }

            }

            return $res_err . "</ul>" . $res_warning . "</ul>" . $res_info . "</ul>";
	}
}