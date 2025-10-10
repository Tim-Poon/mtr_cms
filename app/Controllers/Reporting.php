<?php namespace App\Controllers;

use App\Models\ReportingModel;
use App\Models\MonitorModel;
use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;

class Reporting extends Controller
{
	public function __construct()
    {
		// parent::__construct();
		$this->model = new ReportingModel();
		$this->model_monitor = new MonitorModel();
	}

	public function _remap($method, ...$params)
	{
		if ($method === 'update_remark')
		{
			$repord_id = $params[0];
			$this->update_remark($repord_id);
		}
		elseif($method === 'full_data')
		{
			$delivery_date = $params[0];
			$repord_id = $params[1];
			return $this->get_report_full_data($delivery_date, $repord_id);
		}
		elseif($method === 'download_csv')
		{
			$date = $params[0];
			$this->download_csv($date);
		}
		elseif ($method === 'index')
		{
			return $this->index();
		}
		else
		{
			$delivery_date = $method;
			$repord_id = $params[0];
			return $this->view_report($delivery_date, $repord_id);
		}
	}

	public function index()
	{
        $site_info = 0;
		$data = 
		[
			'icon' => 'fa-file-text',
			'title' => 'Reporting',
			'sub_title' => '',
			'site_names' => $this->model_monitor->get_site_name_all(),

			'reports_date' => $this->get_reports_date(),
			'reports' => [],

			'report_by_id' => [],
			'trajectory_data' => [],

		];
		// get reporting date
		// print_r($this->get_reports_date());
		echo view('head', $data);
		echo view('js');
		echo view('ajax/reporting', $data);
		echo view('foot');
	}

	public function view_report($delivery_date, $repord_id)
	{
		// if ($repord_id){
		
			// $report_by_id = $this->model->get_report_by_id($repord_id);
			// $report_item = $report_by_id[0];
		
			// $site_info = $this->model_monitor->get_site_info_by_name($report_item['site']);
			
			// $time_period = explode("-", $report_item['time_period']);
			// $s_time = Time::createFromFormat('Ymd-His', $report_item['delivery_date'].'-'.$time_period[0], 'Asia/Hong_Kong');
			// $e_time = Time::createFromFormat('Ymd-His', $report_item['delivery_date'].'-'.$time_period[1], 'Asia/Hong_Kong');
			// $s_timestamp = $s_time->getTimestamp();
			// $e_timestamp = $e_time->getTimestamp();
			
			// $sensor = $this->model->get_sensor_mac($report_item['site'], $report_item['sensor']);
			// $trajectory_data = $this->model->get_delivery_trajectory($sensor, $s_timestamp, $e_timestamp);
		// }else {
		// 	$report_by_id = [];
		// 	$trajectory_data = [];
		// }

		$data = 
		[
			'icon' => 'fa-file-text',
			'title' => 'Reporting',
			'sub_title' => ' > '.$delivery_date,
			'site_names' => $this->model_monitor->get_site_name_all(),

			// 'site_info' => $site_info,
			// 'mapbox_key' => config('ApiServer_')->mapbox['key'],

			// 'site_sources' => [],
            // 'site_sensors' => [],

			'reports_date' => $this->get_reports_date(),
			'reports' => $this->model->get_report($delivery_date),
			
			// 'report_by_id' => $report_by_id,
			// 'trajectory_data' => $trajectory_data,

		];
		// print_r($trajectory_data);
		echo view('head', $data);
		echo view('js');
		echo view('ajax/reporting', $data);
		echo view('foot');
	}

	public function get_report_full_data($delivery_date, $repord_id)
	{
		$report_by_id = $this->model->get_report_by_id($repord_id);
		$report_item = $report_by_id[0];
		$site_info = $this->model_monitor->get_site_info_by_name($report_item['site']);
		// get reporting date
		$time_period = explode("-", $report_item['time_period']);
		$s_time = Time::createFromFormat('Ymd-His', $report_item['delivery_date'].'-'.$time_period[0], 'Asia/Hong_Kong');
		$e_time = Time::createFromFormat('Ymd-His', $report_item['delivery_date'].'-'.$time_period[1], 'Asia/Hong_Kong');
		$s_timestamp = $s_time->getTimestamp();
		$e_timestamp = $e_time->getTimestamp();
		
		// echo $report_item['delivery_date'].'-'.$time_period[0];
		$sensor = $report_item['imei'];
		if ($sensor == NULL){
			$sensor = $this->model->get_sensor_mac($report_item['site'], $report_item['sensor']);
		}
		// echo $s_timestamp.'-'.$e_timestamp;
		$trajectory_data = $this->model->get_delivery_trajectory($sensor, $s_timestamp, $e_timestamp);

		$data = 
		[
			'site_info' => $site_info,
			'mapbox_key' => config('ApiServer_')->mapbox['key'],

			'report_by_id' => $report_by_id,
			'trajectory_data' => $trajectory_data,
		];
		//echo(json_encode($trajectory_data));
		echo view('ajax/reporting_map_data', $data);
	}
	
	public function get_reports_date()
	{
		$report_date = $this->model->get_reporing_all_date();
		$res = array();
		foreach($report_date as $date){
			$report_status['title'] = substr($date['delivery_date'], 4);
			$report_status['allDay'] = true;
			$report_status['className'] = array("event", "bg-color-greenLight");
			$report_status['start'] = substr($date['delivery_date'], 0, 4).'-'.substr($date['delivery_date'], 4, 2).'-'.substr($date['delivery_date'], 6, 2);
			
			// link to todos page
			$report_status['url'] = base_url("reporting/".$date['delivery_date']);
			array_push($res, $report_status);
		}
		return ($res);
	}

	private function update_remark($repord_id)
	{
		$remark = $this->request->getPost(['remark']);
		$data = 
		[
			'remark' => $remark
		];
		$this->model->update_remark($repord_id, $data);
		echo 1;
	}

	public function download_csv($delivery_date)
	{
		$reports = $this->model->get_report($delivery_date);
		$file_name = 'Report_raw_'.$delivery_date.'.csv';

		header('Content-Type: application/csv');
		header('Content-Disposition: attachment; filename="'.$file_name.'";');

		$f = fopen('php://output', 'w');
		fprintf($f, chr(0xEF).chr(0xBB).chr(0xBF));
		$heading = array(
			'site',
			'sensor',
			'shop_name',
			'shop_id',
			'delivery_date',
			'time_period',
			'alert',
			'alert_time',
			'warning',
		);
		fputcsv($f, $heading);
		foreach ($reports as $report) {
			$data = array(
				$report['site'],
				$report['sensor'],
				$report['shop_name'],
				$report['shop_id'],
				$report['delivery_date'],
				$report['time_period'],
				$report['alert'],
				$report['alert_time'],
				$report['warning'],
			);
			fputcsv($f, $data);
		}
	}

}
