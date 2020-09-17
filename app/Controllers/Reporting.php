<?php namespace App\Controllers;

use App\Models\ReportingModel;
use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;

class Reporting extends Controller
{
    public function __construct()
    {
        // parent::__construct();
        $this->view_data = [
            'page_content'   => 'reporting/page',
			'heading' => 'My Heading',
			'message' => 'My Message'
        ];
        $this->model = new ReportingModel();
        $this->request = \Config\Services::request();
    }

    public function index()
    {
        $data = [];
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
                'message' => 'My Message'
            ];
        }
        elseif ($stype == 'api_server') {
        }
        elseif ($stype == 'reporting_server') {
        }
        elseif ($stype == 'sensor') {
        }
        else {
        }

        echo view('head', $data);
        echo view('js');
        echo view('ajax/reporting', $data);
        echo view('foot');
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

    public function id($id)
    {
        $this->view_data['ppp'] = $id;
        $pages = view('head', $this->view_data).view('ajax/reporting', $this->view_data).view('foot');
        echo $pages;
    }
}