<?php namespace App\Controllers;

use App\Models\UpdatingModel;
use App\Models\MonitorModel;
use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;

class Updating extends Controller
{
	public function __construct()
    {
		// parent::__construct();
		$this->model = new UpdatingModel();
        $this->model_monitor = new MonitorModel();
	}

	public function _remap($method, ...$params)
	{
		if ($method == 'event')
		{
			return $this->event($params);
		}elseif ($method == 'new_ota_task')
		{
			return $this->new_ota_task();
		}elseif ($method == 'add_new_ota_task')
		{
			$this->add_new_ota_task();
		}elseif ($method == 'update_ota_task')
		{
			$this->update_ota_task();
        }elseif ($method == 'task')
		{
            $ts_create = $params[0];
            $this->task($ts_create);
		}else
		{
			return $this->index();
		}
    }

	public function index()
	{
		$updating_task = $this->model->get_updating_task();
		$data = 
		[
			'icon' => 'fa-cloud-upload',
			'title' => 'Updating',
			'sub_title' => '',
			'site_names' => $this->model_monitor->get_site_name_all(),

            'updating_task' => $updating_task,
		];
        echo view('head', $data);
		echo view('js');
		echo view('ajax/updating', $data);
		echo view('foot');
	}

	function new_ota_task()
	{
		$time = Time::now('Asia/Hong_Kong', 'en_US');

		$data = 
		[
			'icon' => 'fa-gear',
			'title' => 'Updating',
			'sub_title' => '> New OTA Task - '.$time->getTimestamp(),
			'site_names' => $this->model_monitor->get_site_name_all(),

			'date' => $time->toLocalizedString('yyyy-MM-dd'),
			'ts_create'   => $time->getTimestamp(),
            'targets_info' => $this->model_monitor->get_sensor_info_all(),
		];
				
		echo view('head', $data);
		echo view('js');
		echo view('ajax/updating_new', $data);
		echo view('foot');
	}
	
	public function task($ts_create)
	{
		if ($ts_create) 
		{
			$task_info = $this->model->get_updating_task_by_targer($ts_create);
			if ($task_info[0])
			{
				$data = 
				[
					'icon' => 'fa-gear',
                    'title' => 'Updating',
                    'sub_title' => '> OTA Task - '.$task_info[0]['ts_create'],
                    'site_names' => $this->model_monitor->get_site_name_all(),

                    'task_info' => $task_info[0],
				];
				echo view('head', $data);
				echo view('js');
				echo view('ajax/updating_task', $data);
				echo view('foot');
			}
        }
	}

	public function add_new_ota_task()
    {
        if($this->request->getPost()['target'] && $this->request->getPost()['label'] && $this->request->getPost()['md5'] && $this->request->getPost()['ts_create']){
            $data = array(
                'target' => $this->request->getPost()['target'],
                'label' => $this->request->getPost()['label'],
                'md5' => $this->request->getPost()['md5'],
                'ts_create' => $this->request->getPost()['ts_create'],
                'remark' => $this->request->getPost()['remark'],
            );
            $this->model->set_update_task($data);
        }else{
            echo 0;
        }
    }

	public function update_ota_task()
	{
		if($this->request->getPost()['ts_create'] && $this->request->getPost()['remark']){
			$data = array(
                'remark' => $this->request->getPost()['remark'],
            );
			$this->model->update_task_remark($this->request->getPost()['ts_create'], $data);
		}else{
			echo 0;
		}
	}
	
	public function api_data($source, $event_id)
	{
		if (in_array($source, $this->source)){
			$res = $this->model->api_data($source, $event_id)->getResult();
			echo json_encode($res);
		}else{
			echo "error source!";
		}	
	}
	
	public function download($source, $event_id)
	{	
		if (in_array($source, $this->source)){
			$res = $this->model->api_data($source, $event_id)->getResult('array');
			if ($res){
				$head = array_keys($res[0]);
				header('Content-Type: application/vnd.ms-excel;charset=UTF-8');
				header('Content-Type: application/force-download');
				$filename = 'event_'.$event_id.'_'.$source.'.csv';
				header('Content-Disposition: attachment;filename='.$filename);
				$fp = fopen('php://output', 'a');
				fputcsv($fp, $head);
				foreach($res as $row) {
					fputcsv($fp, $row);
				}
			}
		}else{
			echo "error source!";
		}
	}
}