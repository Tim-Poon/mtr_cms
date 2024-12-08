# on-board-program-from-pi version of different sites
from collections import namedtuple
from enum import Enum
from config.common import SOURCE_INFO
import os

MAPS = {
11:[[12695625.500347674,2477519.3035682766,1,0],[12695686.211574443,2477519.1163073266,1,0],[12695686.211574443,2477510.3150365874,1,0],[12695624.690864628,2477509.7532531675,1,0]],
12:[[12695670.610155275,2477531.679348998,1,0],[12695670.66739466,2477518.5559897954,1,0],[12695721.67920573,2477518.270522878,1,0],[12695721.739366915,2477531.4543625666,1,0]],
13:[[12695721.587862933,2477531.3270940646,1,0],[12695721.64260645,2477525.536806534,1,0],[12695840.277028197,2477524.505347588,1,0],[12695840.38651553,2477530.790654527,1,0]],
14:[[12695840.277029822,2477531.1361548146,1,0],[12695880.724201532,2477530.988803646,1,0],[12695880.88344257,2477511.833132224,1,0],[12695840.117788786,2477512.127835046,1,0]],
15:[[12695871.824911287,2477512.370534222,1,0],[12695871.824911287,2477505.238460415,1,0],[12695897.480015209,2477505.544120753,1,0],[12695897.480015209,2477512.370534222,1,0]],
16:[[12695908.470382972,2477543.2244816474,1,0],[12695863.548705943,2477543.0288694724,1,0],[12695863.443007806,2477534.6175412736,1,0],[12695908.364684828,2477534.226316608,1,0]],
17:[[12695863.548705943,2477534.7153475136,1,0],[12695880.037603794,2477534.519735132,1,0],[12695880.354697999,2477530.6074879537,1,0],[12695863.548705943,2477530.3140693526,1,0]],
}

SOURCE_INFO_DICT = {
'1011110030': SOURCE_INFO(source_identifier='1011110030', x=12695865.622264, y=2477545.3145181, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110031': SOURCE_INFO(source_identifier='1011110031', x=12695833.642603, y=2477545.5836098, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110032': SOURCE_INFO(source_identifier='1011110032', x=12695801.729659, y=2477546.257451, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110033': SOURCE_INFO(source_identifier='1011110033', x=12695770.350451, y=2477547.5139537, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110034': SOURCE_INFO(source_identifier='1011110034', x=12695738.226236, y=2477548.4413194, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110035': SOURCE_INFO(source_identifier='1011110035', x=12695706.079783, y=2477549.5165743, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011120001': SOURCE_INFO(source_identifier='1011120001', x=12695740.884309, y=2477521.4525873, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011120002': SOURCE_INFO(source_identifier='1011120002', x=12695756.796046, y=2477521.2091977, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011120003': SOURCE_INFO(source_identifier='1011120003', x=12695771.084594, y=2477520.7756222, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011120004': SOURCE_INFO(source_identifier='1011120004', x=12695785.696378, y=2477520.8478848, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011120005': SOURCE_INFO(source_identifier='1011120005', x=12695800.640719, y=2477520.924581, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011120006': SOURCE_INFO(source_identifier='1011120006', x=12695815.185786, y=2477520.6577274, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110001': SOURCE_INFO(source_identifier='1011110001', x=12695629.166252, y=2477509.832217, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110002': SOURCE_INFO(source_identifier='1011110002', x=12695649.559402, y=2477509.832217, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110003': SOURCE_INFO(source_identifier='1011110003', x=12695668.796124, y=2477509.832217, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110004': SOURCE_INFO(source_identifier='1011110004', x=12695685.608797, y=2477509.6531931, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110005': SOURCE_INFO(source_identifier='1011110005', x=12695697.517774, y=2477515.836743, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110006': SOURCE_INFO(source_identifier='1011110006', x=12695709.315555, y=2477515.9256989, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110007': SOURCE_INFO(source_identifier='1011110007', x=12695673.533028, y=2477518.1618289, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110008': SOURCE_INFO(source_identifier='1011110008', x=12695673.577506, y=2477538.8763318, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110009': SOURCE_INFO(source_identifier='1011110009', x=12695723.226041, y=2477524.8935698, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110010': SOURCE_INFO(source_identifier='1011110010', x=12695733.300301, y=2477526.0588926, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110011': SOURCE_INFO(source_identifier='1011110011', x=12695748.200421, y=2477525.5207092, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110012': SOURCE_INFO(source_identifier='1011110012', x=12695749.946182, y=2477525.5207092, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110013': SOURCE_INFO(source_identifier='1011110013', x=12695762.967107, y=2477525.391723, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110014': SOURCE_INFO(source_identifier='1011110014', x=12695765.001975, y=2477525.480679, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110015': SOURCE_INFO(source_identifier='1011110015', x=12695777.489165, y=2477525.391723, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110016': SOURCE_INFO(source_identifier='1011110016', x=12695779.423957, y=2477525.3016552, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110017': SOURCE_INFO(source_identifier='1011110017', x=12695792.011222, y=2477525.3016552, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110018': SOURCE_INFO(source_identifier='1011110018', x=12695794.03497, y=2477525.0325634, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110019': SOURCE_INFO(source_identifier='1011110019', x=12695806.711192, y=2477525.480679, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110020': SOURCE_INFO(source_identifier='1011110020', x=12695808.846134, y=2477525.5696349, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110021': SOURCE_INFO(source_identifier='1011110021', x=12695821.311085, y=2477525.3149985, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110022': SOURCE_INFO(source_identifier='1011110022', x=12695823.534984, y=2477525.1359747, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110023': SOURCE_INFO(source_identifier='1011110023', x=12695835.733067, y=2477525.6463594, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110024': SOURCE_INFO(source_identifier='1011110024', x=12695838.145997, y=2477525.5574035, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110025': SOURCE_INFO(source_identifier='1011110025', x=12695851.422672, y=2477511.3277887, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110026': SOURCE_INFO(source_identifier='1011110026', x=12695865.444352, y=2477511.1487649, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110027': SOURCE_INFO(source_identifier='1011110027', x=12695884.480923, y=2477505.5178538, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110028': SOURCE_INFO(source_identifier='1011110028', x=12695900.804339, y=2477543.6599375, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1011110029': SOURCE_INFO(source_identifier='1011110029', x=12695881.033881, y=2477533.8859035, z=1, type='non-lon-lat', activated=True, addition_info={}),
}
# [data_pool]
# maximum time range inside a current data pool
window_size_in_sec = 2.
# time gap between two updates in data pool(second)
sec_per_update = 1

# [TRACKING]
CAL_PERIOD_SEC = 1.3  # time gap between two calculations (second)

ALARM_PARAMS = {
    'ALARM_PIN': 17,

    # rule 1: alarm |= within(pos, ALARM_ZONE) and recent(vel_history) > ALARM_VELOCITY_THRESHOLD_HIGHER
    'ALARM_VELOCITY_THRESHOLD_HIGHER': 1.54,
    'ALARM_TOLERANCE_NUMBER': 3,

    # rule 2: alarm |= within(pos, ALARM_ZONE) and step > STEP_ALARM_THRESHOLD_HIGHER
    'STEP_ALARM_THRESHOLD_HIGHER': 12,

    # rule 3: alarm |= within(pos, ALARM_ZONE) and step > STEP_ALARM_THRESHOLD_LOWER and abs(vel)
    # >ALARM_VELOCITY_THRESHOLD_LOWER
    'ALARM_VELOCITY_THRESHOLD_LOWER': 1.45,
    'STEP_ALARM_THRESHOLD_LOWER': 10,

    'ALARM_ZONE': [polygon for polyid, polygon in MAPS.items()],  # it can't be empty list or None.
}

FILTER_MODULE_PARAMS = {
    'RSSI_FILTER_ALGO_FILE': "avg_filter.py",
    'RSSI_THRESHOLD': -80,
}

LOCALIZATION_MODULE_PARAMS = {
	'LOCALIZATION_ALGO_FILE': "new_localization_pf.cpython-37m-arm-linux-gnueabihf.so",
	'num_of_particles': 500,
	'max_vel': 2.,  # maximum speed of human, unit in m/s
	'obs_model_mean': [-69.0656, 2.8871],  # mean and variance of P(rssi, distance)
	'obs_model_var': [[53.171, -5.218], [-5.218, 2.912]],
    'loss_model_range': [-120, -110],
    'weight_observe': 0.8,  # mean and variance of P(rssi, distance)
    'weight_loss': 1 - 0.8,
    'grid_size': 0.5,
	'cal_fq': CAL_PERIOD_SEC,
}


VELOCITY_MODULE_PARAMS = {
    'VELOCITY_ALGO_FILE': "analyse_velocity.py",
    'VELOCITY_LEAST_SQUARE_WINDOW': 5,  # maximum num of position used to calculate velocity via least square
    'LOCALIZATION_CAL_PERIOD': CAL_PERIOD_SEC,
}

