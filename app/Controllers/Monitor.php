<?php namespace App\Controllers;

use App\Models\MonitorModel;
use CodeIgniter\Controller;

class Monitor extends Controller
{
    public function __construct()
    {
        // parent::__construct();
        $this->model = new MonitorModel();

        $this->beacon_status = 
        [
            'beacon_name' => '',
            'mac' => '',
            'rssi' => 0,
            'sensor_ble_mac' => '',
            'ts' => '',
            'vm' => 0,
        ];
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
            'faster_flag' => 0,
            'history_vel' => '',
        ];

        $this->sensor_info_all = $this->model->get_sensor_info_all()->getResult();
		$this->site_info_all = $this->model->get_site_info_all()->getResult();
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
		}elseif ($method == 'get_sensor_status_monitor')
		{
            $site = $params[0];
			return $this->get_sensor_status_monitor($site);
		}elseif ($method == 'get_sensor_status_mapbox')
		{
            $site = $params[0];
			return $this->get_sensor_status_mapbox($site);
        }elseif ($method == 'get_beacon_status_mapbox')
		{
            $site = $params[0];
			return $this->get_beacon_status_mapbox($site);
		}
        else
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
            'icon' => 'fa-desktop',
            'title' => 'Monitor',
            'sub_title' => '',
            'site_info' => $this->site_info,
            'site_all' => $this->model->get_site_all(),
            'mapbox_key' => config('ApiServer_')->mapbox['key'],

        ];
        // echo view('head', $data);
		// echo view('js');
		// echo view('ajax/site', $data);
		// echo view('foot');
    }

    public function monitor($site_name)
    {
        // find site infor by $site
        $site_info = $this->get_site_info($site_name);
        // print_r($this->model->get_site_beacon($site_info->site, 1));

        $data = 
        [  
            'icon' => 'fa-desktop',
            'title' => 'Monitor',
            'sub_title' => ' > '. $site_info->site_name,
            'site_names' => $this->get_site_names(),
            'site_info' => $site_info,
            // todo multi floor
            'site_geojson' => $this->model->get_site_geojson($site_info->site),
            'mapbox_key' => config('ApiServer_')->mapbox['key'],
            'site_beacons' => $this->model->get_site_beacons($site_info->site),
            'site_sensors' => $this->model->get_site_sensors($site_info->site),
            'default_sensor_status' => json_encode([0, 0, 1, 1, 1, 2, 2, 3, 3, 2, 2, 2]),
        ];
        // print_r($data['site_beacons']);
        echo view('head', $data);
		echo view('js');
		echo view('ajax/monitor', $data);
        echo view('foot');
    }

    private function get_site_names()
    {
        $site_names = array();
        foreach ($this->site_info_all as $site_info_item) {
            if (!in_array($site_info_item->site_name, $site_names)) {
                array_push($site_names, $site_info_item->site_name);
            }
        }
        return $site_names;
    }

    private function get_site_info($site_name)
    {
        foreach ($this->site_info_all as $site_info_item) {
            if ($site_name == $site_info_item->site_name) {
                return $site_info_item;
            }
        }
    }

    private function init_status_result()
    {
        $sensor_status_all = array();
        try {
            $raw_sensor_status = json_decode(file_get_contents('http://192.168.10.148:8080/latest_sensor_status'));
            // print_r($raw_beacon_status);
            foreach ($raw_sensor_status as $sensor => $sensor_each) {
                // init sensor status structure
                $sensor_status = $this->sensor_status;
                $sensor_status['sensor'] = $sensor;
                // search if sensor registered
                foreach ($this->sensor_info_all as $sensor_info_item) {
                    if ($sensor == $sensor_info_item->sensor) {
                        // registered!
                        $sensor_status['label'] = $sensor_info_item->label;
                        $sensor_status['site'] = $sensor_info_item->site;
                        break;
                    }
                }
                // search if sensor site existed
                foreach ($this->site_info_all as $site_info_item) {
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
						$hci_lable = 'success';
					}else {
						$hci_lable = 'default';
					}
					$sensor_status['hci_status'] = $sensor_status['hci_status']."<span class=\"label label-$hci_lable\">$hci_status_item</span> ";
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
                    $sensor_status['faster_flag'] = 0;
                    $sensor_status['history_vel'] = $sensor_each->history_vel;
                    if ($sensor_each->faster_flag == 1) {
                        $sensor_status['faster_flag'] = 'fa fa-caret-up icon-color-bad';
                    }elseif ($sensor_each->faster_flag == -1) {
                        $sensor_status['faster_flag'] = 'fa fa-caret-down icon-color-good';
                    }
                } catch (\Throwable $th) {
                }
                array_push($sensor_status_all, $sensor_status);
            }
            return $sensor_status_all;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    public function get_sensor_status_mapbox($site)
    {
        $maxbox_statue = ($this->init_status_result());
        // get mapping formular
        // 
        if ($maxbox_statue == 0) {
            return 0;
        }
        $res = array();
        foreach ($maxbox_statue as $sensor_status) {
            $coordinate = 
            [
                'label' => $sensor_status['label'],
                'vel_x' => $sensor_status['vel_x'],
                'vel_y' => $sensor_status['vel_y'],
                'loc_x' => $sensor_status['loc_x'],
                'loc_y' => $sensor_status['loc_y'],
                'loc_z' => $sensor_status['loc_z'],
                'alarm_flag' => $sensor_status['alarm_flag'],
            ];
            $res[$sensor_status['sensor']] = $coordinate;
        }
        echo json_encode($res);
    }

    public function get_beacon_status_mapbox($site)
    {
        try {
            $raw_beacon_status = file_get_contents('http://192.168.10.148:8080/latest_beacon_status');
            echo $raw_beacon_status;
        } catch (\Throwable $th) {
            //throw $th;
        }
        
    }

    public function get_sensor_status_dashboard()
    {
        $dashboard_statue = ($this->init_status_result());
        if ($dashboard_statue == 0) {
            return 0;
        }
        foreach ($dashboard_statue as $sensor_status) {
            echo "<tr>
            <td class=\"text-align-center\"><a href=\"site/".$sensor_status['site_name']."\">".$sensor_status['site_name']."</a></td>
            <td class=\"text-align-center\">".$sensor_status['label']."</td>
            <td class=\"text-align-center\">".$sensor_status['sensor']."</td>
            <td class=\"text-align-center\">".$sensor_status['hci_status']."</td>
            <td class=\"text-align-center\">".$sensor_status['vm']."</td>
            </tr>";
        }
    }
    
    public function get_sensor_status_monitor($site)
    {
        $monitor_statue = ($this->init_status_result());
        if ($monitor_statue == 0) {
            return 0;
        }
        $res = array();
        foreach ($monitor_statue as $sensor_status) {
            $res[$sensor_status['sensor']] = $sensor_status;
        }
        echo json_encode($res);
	}

}