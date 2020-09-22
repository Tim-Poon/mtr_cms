<?php namespace App\Controllers;

use App\Models\TodosModel;
use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;

class Todos extends Controller
{
    public function __construct()
    {
        // parent::__construct();
        $this->model = new TodosModel();
        $this->request = \Config\Services::request();
    }

    public function index()
    {
        # page data
        $data = [];

        # echo todos page head
        echo view('head', $data);
        echo view('js');

        $this->set_data();

        echo view('foot');
    }

    protected function set_data(){
        # get parameter
        $param_from_url = $this->request->getGet();

        $id = $param_from_url['id'];
        $stype = $param_from_url['stype'];
        if ($stype == 'offline_reporting') {

            $log = $this->log($id);
            $related_logs = $this->related_offline_reporting_logs($id);
            $time = Time::createFromTimestamp($log->ts / 1000, 'Asia/Shanghai', 'en_US');
            $seq = $this->retrieve_process_seq($log);

            $data = [
                'stype' => $param_from_url['stype'],
                'id'   => $param_from_url['id'],
                'seq' => $seq,
                'content' => $log->content,
                'date' => "{$time->getYear()}-{$time->getMonth()}-{$time->getDay()}-{$time->getHour()}-{$time->getMinute()}",
                'related_logs' => $related_logs,
                'heading' => 'My Heading',
                'solve_message' => $log->solve_message,
                'todo' => $log->todo
            ];
           echo view('ajax/solve_reporting', $data);
        }
        elseif ($stype == 'api_server') {
        }
        elseif ($stype == 'reporting_server') {
        }
        elseif ($stype == 'sensor') {
        }
        else {
            $data['tabletodos'] = $this->tabletodos();
            echo view('ajax/todos', $data);
        }
    }

    protected function log($id)
    {
        // todo
        return $this->model->get_log($id);
    }

    protected function related_offline_reporting_logs($id)
    {
        // todo
        return $this->model->get_related_offline_reporting_logs($id);
    }

    protected function retrieve_process_seq($log)
    {
        # todo
        $content = $log->content;
        return 1;
    }

    protected function related_sensor_logs($id)
    {
        // todo
        return 1;
    }


    protected function related_api_server_logs($id)
    {
        // todo
        return 1;
    }

    protected function related_reporting_server_logs($id)
    {
        // todo
        return 1;
    }

    public function submit()
    {
        if($this->request->getPost()['message'])
        {
            $data['msg'] = $this->request->getPost()['message'];
            $data['id'] = $this->request->getPost()['id'];
            if($this->model->update_todos($data['id'], $data['msg'])){
//                 echo view('submit', $data);
                return 1;
            }

        }
    }

    public function tabletodos()
	{
	        $res_err = "<h5 class=\"todo-group-title\"><i class=\"fa fa-exclamation\"></i> Error</h5><ul id=\"sortable1\" class=\"todo\">";
	        $res_warning = "<h5 class=\"todo-group-title\"><i class=\"fa fa-warning\"></i> Warning</h5><ul id=\"sortable1\" class=\"todo\">";
	        $res_info = "<h5 class=\"todo-group-title\"><i class=\"fa fa-check\"></i> Info</h5><ul id=\"sortable1\" class=\"todo\">";

            $m = $this->model;
            $todos = $m->get_todos(30);

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
                $url = "todos?stype=$todo->src_type&id=$todo->id";

                $view_content = "<li>
                                    <span class=\"handle\"></span>
                                    <p>
                                        <strong>{$src_type} #{$id}</strong> - {$content} [<a href=\"{$url}\" class=\"font-xs\">More Details</a>]
                                        <span class=\"text-muted\">I don't know what to place in here</span>
                                        <span class=\"date\">{$todo_date}</span>
                                    </p>
                                </li>";

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