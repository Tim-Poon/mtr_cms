<?php namespace App\Controllers;

use App\Models\MonitorModel;
use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;
class Monitor extends Controller
{
    public function __construct()
    {
        // parent::__construct();
        $this->model = new MonitorModel();

        $this->hci_color = ['bg-color-orange', 'bg-color-blueLight', 'bg-color-yellow', 'bg-color-blue', 'bg-color-greenLight', 'bg-color-redLight'];

        $this->led_idx = 
        [
            'ORANGE_LED' => 1, 
            'WHITE_LED' => 2, 
            'YELLOW_LED' => 3,
            'BLUE_LED' => 4, 
            'GREEN_LED' => 5, 
            'RED_LED' => 6, 
        ];

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
            'register' => 0,
            'label' => NULL,
            'site' => '',
            'site_name' => NULL,
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
        // init_status_result
        $this->sensor_info_all = $this->model->get_sensor_info_all();
        $this->site_name_all = $this->model->get_site_name_all();
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
        }elseif ($method == 'get_sensor_status_survey')
        {
            $site = $params[0];
            return $this->get_sensor_status_survey($site);
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
        return redirect()->to(base_url());
    }

    public function monitor($site_name)
    {
        $site_info = $this->model->get_site_info_by_name($site_name);
        $data =
        [
            // header
            'icon' => 'fa-desktop',
            'title' => 'Monitor',
            'sub_title' => ' > '. $site_info[0]['site_name'],
            'site_names' => $this->site_name_all,
            // site page
            'site_info' => $site_info,
            'mapbox_key' => config('ApiServer_')->mapbox['key'],
            // 'site_beacons' => $this->model->get_site_beacons($site_info[0]['site']),
            'site_sources' => $this->model->get_site_sources($site_info[0]['site']),
            'site_sensors' => $this->model->get_site_sensors($site_info[0]['site']),
            'default_sensor_status' => json_encode([0, 0, 1, 1, 1, 2, 2, 3, 3, 2, 2, 2]),
        ];
        // print_r($data['site_sources']);
        echo view('head', $data);
        echo view('js');
        echo view('ajax/monitor', $data);
        echo view('foot');
    }

    private function init_status_result()
    {
        $sensor_status_all = array();
        try {
            $contents = @file_get_contents('http://10.2.2.4:8080/latest_sensor_status');
            if ($contents === FALSE) {

            }else{
                $raw_sensor_status = json_decode($contents);
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
                            $sensor_status['register'] = 1;
                            break;
                        }
                    }
                    // search if sensor site existed
                    foreach ($this->site_name_all as $site_info_item) {
                        if ($sensor_status['site'] == $site_info_item['site']) {
                            // existed!
                            $sensor_status['site_name'] = $site_info_item['site_name'];
                            break;
                        }
                    }
                    // load hci data
                    if ($sensor_each->hci_status) {
                        foreach ($this->led_idx as $led_color => $led_idx) {
                            if ($sensor_each->hci_status->$led_color) {
                                $hci_lable = $this->hci_color[$led_idx - 1];
                            }else {
                                $hci_lable = 'default';
                            }
                            $sensor_status['hci_status'] = $sensor_status['hci_status']."<span class=\"label $hci_lable\">&nbsp&nbsp</span> ";
                        }
                    }

                    // load realtime data
                    try {
                        $sensor_status['hci_ts'] = $sensor_each->hci_ts;
                        $sensor_status['alarm_flag'] = $sensor_each->alarm_flag;
                        $sensor_status['loc_x'] = $sensor_each->loc_x;
                        $sensor_status['loc_y'] = $sensor_each->loc_y;
                        $sensor_status['loc_z'] = $sensor_each->loc_z;
                        $sensor_status['location_ts'] = $sensor_each->location_ts;
                        $sensor_status['vel_x'] = $sensor_each->vel_x;
                        $sensor_status['vel_y'] = $sensor_each->vel_y;
                        $sensor_status['vel_z'] = $sensor_each->vel_z;
                        $sensor_status['vel_norm'] = $sensor_each->vel_norm;
                        $sensor_status['vm'] = $sensor_each->vm;
                        $sensor_status['faster_flag'] = 0;
                        $sensor_status['history_vel'] = $sensor_each->history_vel;
                        $sensor_status['faster_flag'] = 0;
                        $sensor_status['beacon_n'] = $sensor_each->beacon_n;
                        $sensor_status['beacon_n_ts'] = $sensor_each->beacon_n_ts;
                        $sensor_status['imu_n'] = $sensor_each->imu_n;
                        $sensor_status['imu_n_ts'] = $sensor_each->imu_n_ts;
                        $sensor_status['raw_p'] = (json_decode($sensor_each->raw_p));
                        $sensor_status['cpu_t'] = $sensor_each->cpu_t;
                        $sensor_status['cpu_u'] = $sensor_each->cpu_u;
                        $sensor_status['sd'] = $sensor_each->sd;
                        // $sensor_status['raw_p'] = count($sensor_status['raw_p']);

                        if (count($sensor_status['raw_p']) > 0) {
                            for ($i=0; $i < count($sensor_status['raw_p']); $i++) {
                                # code...d
                                $sensor_status['raw_p'][$i][0][0] /= 111194.926644;
                                $sensor_status['raw_p'][$i][0][1] /= 111194.926644;
                            }
                        }

                        if ($sensor_each->faster_flag == 1) {
                            $sensor_status['faster_flag'] = 'fa fa-caret-up icon-color-bad';
                        }elseif ($sensor_each->faster_flag == -1) {
                            $sensor_status['faster_flag'] = 'fa fa-caret-down icon-color-good';
                        }
                    } catch (\Throwable $th) {
                    }
                    array_push($sensor_status_all, $sensor_status);
                }
                uasort($sensor_status_all, function($a, $b) {
                    return $b['hci_ts'] <=> $a['hci_ts'];
                });
                return $sensor_status_all;
            }
        } catch (\Throwable $th) {
            return 0;
        }
    }

    public function get_sensor_status_mapbox($site)
    {
        if (intval($site) >= '1012' or intval($site) == '1009'){
            $raw_sensor_status = json_decode(file_get_contents('http://10.2.2.4:8081/latest_label_status'));
            echo json_encode($raw_sensor_status);

        }else{
            $time_out = 60;
            $maxbox_statue = ($this->init_status_result());

            // get mapping formular
            //
            if ($maxbox_statue == 0) {
                return 0;
            }
            $res = array();
            foreach ($maxbox_statue as $sensor_status) {
                if (Time::now()->getTimestamp() - $sensor_status['location_ts'] < $time_out) {

                    if ($sensor_status['site'] == $site) {
                        $coordinate =
                        [
                            'label' => $sensor_status['label'],
                            'vel_norm' => $sensor_status['vel_norm'],
                            'loc_x' => $sensor_status['loc_x'],
                            'loc_y' => $sensor_status['loc_y'],
                            'loc_z' => $sensor_status['loc_z'],
                            'location_ts' => $sensor_status['label'].'-('.Time::createFromTimestamp($sensor_status['location_ts'], 'Asia/Hong_Kong', 'en_US').')',
                            'alarm_flag' => $sensor_status['alarm_flag'],
                            'raw_p' => $sensor_status['raw_p']
                        ];
                        $res[$sensor_status['sensor']] = $coordinate;
                    }
                }
            }
            echo json_encode($res);
        }
        
    }

    public function get_beacon_status_mapbox($site)
    {
        try {
            if (intval($site) < '1012' and intval($site) != '1009'){
                $raw_beacon_status = file_get_contents('http://10.2.2.4:8080/latest_beacon_status');
                echo $raw_beacon_status;
            }else{
                $raw_beacon_status = file_get_contents('http://10.2.2.4:8081/latest_beacon_status');
                echo $raw_beacon_status;
            }
        } catch (\Throwable $th) {
            echo 0;
            //throw $th;
        }
    }

    private function get_sensor_status_dashboard()
    {
        $time_out = 60 * 60 * 8;
        try {
            $dashboard_statue = ($this->init_status_result());
            if ($dashboard_statue == 0) {
                return 0;
            }
            // print_r($dashboard_statue[0]);
            foreach ($dashboard_statue as $sensor_status) {
                // timeout
                // if ($sensor_status['hci_status'] != '') {
                if (Time::now()->getTimestamp() - $sensor_status['hci_ts'] < $time_out) {
                        $register_site_url = base_url().'/'.'register/'.$sensor_status['sensor'];
                        $register_site_name = '<div style="color:#E74C3C">unregister</div>';
                        if ($sensor_status['register']){
                            if ($sensor_status['site_name'] == NULL) {
                                $register_site_name = '<div style="color:#F39C12">null</div>';
                            }else{
                                $register_site_url = base_url().'/'.'monitor/'.$sensor_status['site_name'];
                                $register_site_name = $sensor_status['site_name'];
                            }
                        }
                        $time = Time::createFromTimestamp($sensor_status['hci_ts'], 'Asia/Hong_Kong', 'en_US');
                        $color_normal = '#00A439';
                        $color_abnormal = '#E02C00';
                        $cpu_u_color = $color_normal;
                        $cpu_t_color = $color_normal;
                        $sd_color = $color_normal;
                        $last_ts_color = $color_normal;
                        if ($sensor_status['cpu_t'] > 65) {
                            $cpu_t_color = '#E02C00';
                        }
                        if ($sensor_status['cpu_u'] > 50) {
                            $cpu_u_color = '#E02C00';
                        }
                        if ($sensor_status['sd'] > 50) {
                            $sd_color = '#E02C00';
                        }
                        if (Time::now()->getTimestamp() - $sensor_status['hci_ts'] > 60 * 5) {
                            $last_ts_color = '#E02C00';
                        }
                        echo "<tr>
                        <td class=\"text-align-center\"><Strong><a href=\"".$register_site_url."\">".$register_site_name."</a></Strong></td>
                        <td class=\"text-align-center\">".$sensor_status['label']."</td>
                        <td class=\"text-align-center\">".substr($sensor_status['sensor'],12)."</td>
                        <td class=\"text-align-center\">".$sensor_status['hci_status']."</td>
                        <td class=\"text-align-center\"><div style=\"color:".$cpu_t_color."\">".$sensor_status['cpu_t'].'°C'."</div></td>
                        <td class=\"text-align-center\"><div style=\"color:".$cpu_u_color."\">".$sensor_status['cpu_u'].'%'."</div></td>
                        <td class=\"text-align-center\"><div style=\"color:".$sd_color."\">".$sensor_status['sd'].'%'."</div></td>
                        <td class=\"text-align-center\">".$sensor_status['beacon_n']."</td>
                        <td class=\"text-align-center\"><div style=\"color:".$last_ts_color."\">".substr($time, 10)."</div></td>
                        </tr>";
                    // }
                }
            }
        } catch (Exception $e) {
            
        }
        try {
            $contents = @file_get_contents('http://10.2.2.4:8081/latest_label_status');
            if ($contents === FALSE) {

            }else{
                $raw_sensor_status = json_decode($contents);
                foreach ($raw_sensor_status as $sensor_status) {
                    $site_id = "null";
                    $sensor_label = "";
                    foreach ($this->sensor_info_all as $all_sensor) {
                        if ($sensor_status->label == $all_sensor->sensor) {
                            // existed!
                            $site_id = $all_sensor->site;
                            $sensor_label = $all_sensor->label;
                            break;
                        }
                    }
                    $site_name = "";
                    foreach ($this->site_name_all as $site_info_item) {
                        if ($site_id == $site_info_item['site']) {
                            // existed!
                            $site_name = $site_info_item['site_name'];
                            break;
                        }
                    }
                    // print_r($sensor_status->label);
                    echo "<tr>
                    <td class=\"text-align-center\"><Strong><a href=\"".base_url().'/'.'monitor/'.$site_name."\">".$site_name."</a></Strong></td>
                    <td class=\"text-align-center\">".$sensor_label."</td>
                    <td class=\"text-align-center\">".$sensor_status->label."</td>
                    <td class=\"text-align-center\"></td>
                    <td class=\"text-align-center\"></td>
                    <td class=\"text-align-center\"></td>
                    <td class=\"text-align-center\"></td>
                    <td class=\"text-align-center\"></td>
                    <td class=\"text-align-center\"><div style=\"color:".$last_ts_color."\">".substr($time, 10)."</div></td>
                    </tr>";

                }
            }
            
        } catch (Exception $e) {
            
        }
        
            // echo json_encode($raw_sensor_status);
        // echo "";
    }

    private function get_sensor_status_survey($site)
    {
        $time_out = 60;
        $dashboard_statue = ($this->init_status_result());
        if ($dashboard_statue == 0) {
            return 0;
        }
        foreach ($dashboard_statue as $sensor_status) {
            // timeout
            // if ($sensor_status['hci_status'] != '') {
            if (Time::now()->getTimestamp() - $sensor_status['hci_ts'] < $time_out) {
                    // $register_site_url = base_url().'/'.'register/'.$sensor_status['sensor'];
                    // $register_site_name = '<div style="color:#E74C3C">unregister</div>';
                    // if ($sensor_status['register']){
                    //     if ($sensor_status['site_name'] == NULL) {
                    //         $register_site_name = '<div style="color:#F39C12">null</div>';
                    //     }else{
                    //         $register_site_url = base_url().'/'.'monitor/'.$sensor_status['site_name'];
                    //         $register_site_name = $sensor_status['site_name'];
                    //     }
                    // }
                    echo "<tr>
                    <td class=\"text-align-center\"><strong>#".$sensor_status['label']."</strong><br>(".date('H:i:s', $sensor_status['hci_ts']).")</td>
                    <td class=\"text-align-center\"><strong>".$sensor_status['beacon_n']."</strong><br>(".date('H:i:s', $sensor_status['beacon_n_ts']).")</td>
                    <td class=\"text-align-center\"><strong>".$sensor_status['imu_n']."</strong><br>(".date('H:i:s', $sensor_status['imu_n_ts']).")</td>
                    </tr>";
                // }
            }
        }
    }

    private function get_sensor_status_monitor($site)
    {
        if (intval($site) >= '1012' or intval($site) == '1009'){
            $raw_sensor_status = json_decode(file_get_contents('http://10.2.2.4:8081/latest_label_status'));
            foreach ($raw_sensor_status as $item) {
                $data[$item->label] = $item;
            }
            // $data[$raw_sensor_status[0]->label] = $raw_sensor_status[0];

            echo json_encode($data);
        }else{
            $time_out = 60;
            $monitor_statue = ($this->init_status_result());
            if ($monitor_statue == 0) {
                return 0;
            }
            $res = array();
            foreach ($monitor_statue as $sensor_status) {
                if (Time::now()->getTimestamp() - $sensor_status['location_ts'] < $time_out) {
                    if ($sensor_status['site'] == $site) {
                        $res[$sensor_status['sensor']] = $sensor_status;
                    }
                }
            }
            echo json_encode($res);
        }
        
    }
}