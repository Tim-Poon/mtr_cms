<?php namespace Config;

use CodeIgniter\Config\BaseConfig;

class ApiServer_ extends BaseConfig
{
    // public $apiServerUrl = 'http://143.89.49.63:8080/';
    public $apiServerUrl = 'http://192.168.10.123:8080/';
    public $sensor = [
        'sensor_status'      => 'status',
    ];
    public $beacon = [
        'beacon_list'      => 'beacon',
    ];
    public $station = [
        'KowloonBay'      => 'KOB',
        'Central'      => 'CEN',
        'YauMaTei'      => 'YMT',
    ];
}