<?php namespace App\Controllers;

use App\Models\SiteModel;
use CodeIgniter\Controller;

class Monitor extends Controller
{
    public function __construct()
    {
        // parent::__construct();
        $this->model = new SiteModel();

        $this->sensor_status = 
        [
            'sensor' => '',
            'label' => 'unregistered',
            'site' => '',
            'site_name' => '',
            'alarm_flag' => 0,
            'hci_status' => '',
            'heartbeat_ts' => '',
            'loc_x' => 0,
            'loc_y' => 0,
            'loc_z' => 0,
            'location_ts' => '',  
            'vel_x' => 0.0,
            'vel_y' => 0.0,
            'vel_z' => 0.0,
            'vm' => '',
        ];

        $this->sensor_info = $this->model->get_sensor_info()->getResult();
		$this->site_info = $this->model->get_site_info()->getResult();
    }

    public function _remap($method, ...$params)
	{
		if ($method == 'event')
		{
			return $this->event($params);
		}elseif ($method == 'new_event')
		{
			return $this->new_event();
		}elseif ($method == 'add_event')
		{
			$this->add_event();
		}elseif ($method == 'index')
		{
			return $this->index();
		}elseif ($method == 'get_sensor_status_dashboard')
		{
			return $this->get_sensor_status_dashboard();
		}else
		{
            $site_name = $method;
			return $this->monitor($site_name);
		}
    }

    public function index()
    {
        // todo: show site's beacon table with vm
        // todo: show site's polygon with vm
        // todo: show site's configuration with vm
        $data = 
        [  
            'icon' => 'fa-truck',
            'title' => 'Site',
            'sub_title' => '',
            'site_names' => $this->model->get_site_names(),
            'site_all' => $this->model->get_site_all(),
            'mapbox_key' => config('ApiServer_')->mapbox['key'],

        ];
        echo view('head', $data);
		echo view('js');
		echo view('ajax/site', $data);
		echo view('foot');
    }

    public function monitor($site)
    {
        $data = 
        [  
            'icon' => 'fa-truck',
            'title' => 'Site',
            'sub_title' => ' > '. $site,
            'site_names' => $this->model->get_site_names(),
            'site_all' => $this->model->get_site_all(),
            'geojson' => $this->model->get_geojson(1001, 1),
            'mapbox_key' => config('ApiServer_')->mapbox['key'],
            'beacons' => $this->model->get_site_beacon(1001, 1),
            'site_sensor' => $this->model->get_site_sensor($site),
        ];
        echo view('head', $data);
		echo view('js');
		echo view('ajax/site', $data);
        echo view('foot');
    }

    private function init_status_result()
    {   
        $sensor_status_all = array();
        try {
            $raw_sensor_status = json_decode(file_get_contents('http://192.168.10.148:8080/latest_sensor_status'));
            // print_r($raw_sensor_status);
            foreach ($raw_sensor_status as $sensor => $sensor_each) {
                // init sensor status structure
                $sensor_status = $this->sensor_status;
                $sensor_status['sensor'] = $sensor;
                // search if sensor registered
                foreach ($this->sensor_info as $sensor_info_item) {
                    if ($sensor == $sensor_info_item->sensor) {
                        // registered!
                        $sensor_status['label'] = $sensor_info_item->label;
                        $sensor_status['site'] = $sensor_info_item->site;
                        break;
                    }
                }
                // search if sensor site existed
                foreach ($this->site_info as $site_info_item) {
                    if ($sensor_status['site'] == $site_info_item->site) {
                        // existed!
                        $sensor_status['site'] = $site_info_item->site;
                        $sensor_status['site_name'] = $site_info_item->site_name;
                        break;
                    }
                }
                // load hci data
                foreach ($sensor_each->hci_status as $hci_status_item) {
					if($hci_status_item){
						$lable = 'success';
					}else {
						$lable = 'default';
					}
					$sensor_status['hci_status'] = $sensor_status['hci_status']."<span class=\"label label-$lable\">$hci_status_item</span> ";
				}
                // load realtime data
                try {
                    $sensor_status['alarm_flag'] = $sensor_each->alarm_flag;
                    $sensor_status['heartbeat_ts'] = $sensor_each->heartbeat_ts;
                    $sensor_status['loc_x'] = $sensor_each->loc_x;
                    $sensor_status['loc_y'] = $sensor_each->loc_y;
                    $sensor_status['loc_z'] = $sensor_each->loc_z;
                    $sensor_status['location_ts'] = $sensor_each->location_ts;
                    $sensor_status['vel_x'] = $sensor_each->vel_x;
                    $sensor_status['vel_y'] = $sensor_each->vel_y;
                    $sensor_status['vel_z'] = $sensor_each->vel_z;
                    $sensor_status['vm'] = $sensor_each->vm;
                } catch (\Throwable $th) {
                }
                array_push($sensor_status_all, $sensor_status);
            }
            return $sensor_status_all;
        } catch (\Throwable $th) {
        }
    }

    public function get_sensor_status_dashboard()
    {
        $dashboard_statue = ($this->init_status_result());
        foreach ($dashboard_statue as $sensor_status) {
            echo "<tr>
            <td class=\"text-align-center\"><a href=\"site/".$sensor_status['site_name']."\">".$sensor_status['site_name']."</a></td>
            <td class=\"text-align-center\">".$sensor_status['site_label']."</td>
            <td class=\"text-align-center\">".$sensor_status['sensor']."</td>
            <td class=\"text-align-center\">".$sensor_status['hci_status']."</td>
            <td class=\"text-align-center\">".$sensor_status['vm']."</td>
            </tr>";
        }
        
    }
    
    public function get_realtime_sensor_status_monitor1()
    {
	}

}